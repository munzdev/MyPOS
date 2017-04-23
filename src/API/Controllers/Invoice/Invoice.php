<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Event\IEventBankinformationQuery;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Event\IEventContactQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
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

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $invoiceQuery = $this->container->get(IInvoiceQuery::class);

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

        $invoiceCount = $invoiceQuery->getInvoiceCountBySearch($user->getEventUsers()->getFirst()->getEventid(),
            $status,
            $invoiceid,
            $customerid,
            $canceled,
            $typeid,
            $userid,
            $dateFrom,
            $dateTo);

        if (isset($_REQUEST['page']) && isset($_REQUEST['elementsPerPage'])) {
            $validators = array(
                'page' => v::intVal()->length(1)->positive(),
                'elementsPerPage' => v::intVal()->length(1)->positive()
            );

            $validate->assert($validators, $_REQUEST);

            $invoices = $invoiceQuery->findWithPagingAndSearch(($_REQUEST['elementsPerPage'] * $_REQUEST['page']) - $_REQUEST['elementsPerPage'],
                $_REQUEST['elementsPerPage'],
                $user->getEventUsers()->getFirst()->getEventid(),
                $status,
                $invoiceid,
                $customerid,
                $canceled,
                $typeid,
                $userid,
                $dateFrom,
                $dateTo);
        } else {
            $invoices = $invoiceQuery->findWithPagingAndSearch(null,
                null,
                $user->getEventUsers()->getFirst()->getEventid(),
                $status,
                $invoiceid,
                $customerid,
                $canceled,
                $typeid,
                $userid,
                $dateFrom,
                $dateTo);
        }

        $this->withJson(
            ["Count" => $invoiceCount,
            "Invoice" => $invoices->toArray()]
        );
    }

    public function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $eventContactQuery = $this->container->get(IEventContactQuery::class);
        $eventBankinformationQuery = $this->container->get(IEventBankinformationQuery::class);

        $user = $auth->getCurrentUser();

        $connection = $this->container->get(IConnectionInterface::class);

        try {
            $connection->beginTransaction();

            $invoiceTemplate = $this->container->get(IInvoice::class);

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $invoiceTemplate);

            // fix given ISO Date
            $invoiceTemplate->setMaturityDate(date("c", strtotime($this->json['MaturityDate'])));

            $eventContact = $eventContactQuery->getDefaultForEventid($user->getEventUsers()->getFirst()->getEventid());

            $eventBankinformation = $eventBankinformationQuery->getDefaultForEventid($user->getEventUsers()->getFirst()->getEventid());

            $invoice = $this->container->get(IInvoice::class);
            $invoice->setEventContact($eventContact);
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
                $invoiceItem = $this->container->get(IInvoiceItem::class);
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
