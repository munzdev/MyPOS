<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Models\ORM\Event\EventBankinformationQuery;
use API\Models\ORM\Event\EventContactQuery;
use API\Models\ORM\Invoice\Invoice as InvoiceModel;
use API\Models\ORM\Invoice\InvoiceItem;
use API\Models\ORM\Invoice\InvoiceQuery;
use API\Models\ORM\Invoice\Map\InvoiceTableMap;
use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_INVOICE_ADD;
use const API\USER_ROLE_INVOICE_OVERVIEW;

class Invoice extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_INVOICE_OVERVIEW,
                            'POST' => USER_ROLE_INVOICE_ADD];

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $status = 'unpaid';
        $invoiceid = null;
        $customerid = null;
        $canceled = null;
        $typeid = null;
        $dateFrom = null;
        $dateTo = null;
        $userid = $user->getUserid();

        $validate = $this->container->get(IValidate::class);

        if (isset($_REQUEST['search'])) {
            $validators = array(
                'search' => [
                    'status' => v::alnum()->noWhitespace()->length(1),
                    'invoiceid' => v::optional(v::intVal()->length(1)->positive()),
                    'customerid' => v::optional(v::intVal()->length(1)->positive()),
                    'canceled' => v::oneOf(
                        v::boolVal(),
                        v::nullType()
                    ),
                    'typeid' => v::optional(v::intVal()->length(1)->positive()),
                    'from' => v::optional(v::date()),
                    'to' => v::optional(v::date()),
                    'userid' => v::oneOf(
                        v::intVal()->length(1)->positive(),
                        v::equals('*')
                    )]
            );

            $validate->assert($validators, $_REQUEST);

            $status = $_REQUEST['search']['status'];
            $userid = $_REQUEST['search']['userid'];

            if (isset($_REQUEST['search']['invoiceid'])) {
                $invoiceid = $_REQUEST['search']['invoiceid'];
            }

            if (isset($_REQUEST['search']['customerid'])) {
                $customerid = $_REQUEST['search']['customerid'];
            }

            if (isset($_REQUEST['search']['canceled']) && $_REQUEST['search']['canceled'] !== "") {
                $canceled = filter_var($_REQUEST['search']['canceled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }

            if (isset($_REQUEST['search']['typeid'])) {
                $typeid = $_REQUEST['search']['typeid'];
            }

            if (isset($_REQUEST['search']['from'])) {
                $dateFrom = $_REQUEST['search']['from'];
            }

            if (isset($_REQUEST['search']['to'])) {
                $dateTo = $_REQUEST['search']['to'];
            }
        }

        $searchCriteria = InvoiceQuery::create()
                                        ->useEventContactRelatedByEventContactidQuery()
                                            ->filterByEventid($user->getEventUser()->getEventid())
                                        ->endUse()
                                        ->_if($status == 'paid')
                                            ->filterByPaymentFinished(null, Criteria::NOT_EQUAL)
                                        ->_elseif($status == 'unpaid')
                                            ->filterByPaymentFinished(null)
                                        ->_endif()
                                        ->_if($invoiceid)
                                            ->filterByInvoiceid($invoiceid)
                                        ->_endif()
                                        ->_if($customerid)
                                            ->filterByCustomerEventContactid($customerid)
                                        ->_endif()
                                        ->_if($canceled === true)
                                            ->filterByCanceledInvoiceid(null, Criteria::NOT_EQUAL)
                                        ->_elseif($canceled === false)
                                            ->filterByCanceledInvoiceid(null)
                                        ->_endif()
                                        ->_if($typeid)
                                            ->filterByInvoiceTypeid($typeid)
                                        ->_endif()
                                        ->_if($userid != '*')
                                            ->filterByUserid($userid)
                                        ->_endif()
                                        ->_if($dateFrom)
                                            ->filterByDate(array('min' => new DateTime($dateFrom)))
                                        ->_endif()
                                        ->_if($dateTo)
                                            ->filterByDate(array('max' => new DateTime($dateTo)))
                                        ->_endif()
                                        ->orderByDate();

        if (isset($_REQUEST['page']) && isset($_REQUEST['elementsPerPage'])) {
            $validators = array(
                'page' => v::intVal()->length(1)->positive(),
                'elementsPerPage' => v::intVal()->length(1)->positive()
            );

            $validate->assert($validators, $_REQUEST);

            $invoiceList = InvoiceQuery::create(null, clone $searchCriteria)
                                        ->offset(($_REQUEST['elementsPerPage'] * $_REQUEST['page']) - $_REQUEST['elementsPerPage'])
                                        ->limit($_REQUEST['elementsPerPage'])
                                        ->find();
        }

        $criteriaData = InvoiceQuery::create(null, $searchCriteria)
                                        ->joinWithInvoiceType();

        $invoiceCount = InvoiceQuery::create(null, clone $criteriaData)->count();

        $invoice = InvoiceQuery::create(null, $criteriaData)
                                ->_if(!empty($invoiceList))
                                    ->where(InvoiceTableMap::COL_INVOICEID . " IN ?", $invoiceList->getColumnValues())
                                ->_endif()
                                ->find();

        $this->withJson(
            ["Count" => $invoiceCount,
            "Invoice" => $invoice->toArray()]
        );
    }

    public function post() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $invoiceTemplate = new InvoiceModel();

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $invoiceTemplate);

            // fix given ISO Date
            $invoiceTemplate->setMaturityDate(date("c", strtotime($this->json['MaturityDate'])));

            $eventContact = EventContactQuery::create()
                                                ->filterByEventid($user->getEventUser()->getEventid())
                                                ->filterByDefault(true)
                                                ->findOne();

            $eventBankinformation = EventBankinformationQuery::create()
                                                                ->findOneByActive(true);

            $invoice = new InvoiceModel();
            $invoice->setEventContactRelatedByEventContactid($eventContact);
            $invoice->setUser($user);
            $invoice->setEventBankinformation($eventBankinformation);
            $invoice->setDate(new DateTime());

            $invoice->setInvoiceTypeid($invoiceTemplate->getInvoiceTypeid());
            $invoice->setMaturityDate($invoiceTemplate->getMaturityDate());

            if ($invoiceTemplate->getCustomerEventContactid()) {
                $invoice->setCustomerEventContactid($invoiceTemplate->getCustomerEventContactid());
            }

            $invoice->save();

            $amount = 0;

            foreach ($invoiceTemplate->getInvoiceItems() as $invoiceItemTemplate) {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->setInvoice($invoice);
                $invoiceItem->setAmount($invoiceItemTemplate->getAmount());
                $invoiceItem->setPrice($invoiceItemTemplate->getPrice());
                $invoiceItem->setDescription($invoiceItemTemplate->getDescription());
                $invoiceItem->setTax($invoiceItemTemplate->getTax());
                $invoiceItem->save();

                $amount += $invoiceItem->getPrice() * $invoiceItem->getAmount();
            }

            $invoice->setAmount($amount);
            $invoice->save();

            $connection->commit();

            $this->withJson($invoiceTemplate->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }
}
