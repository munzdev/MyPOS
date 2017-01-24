<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventBankinformationQuery;
use API\Models\Event\EventContactQuery;
use API\Models\Invoice\Invoice as InvoiceModel;
use API\Models\Invoice\InvoiceItem;
use API\Models\Invoice\InvoiceQuery;
use API\Models\Invoice\Map\InvoiceTableMap;
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
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_INVOICE_OVERVIEW,
                             'POST' => USER_ROLE_INVOICE_ADD];

        $o_app->getContainer()['db'];
    }

    protected function GET() : void
    {
        $o_user = Auth::GetCurrentUser();

        $str_status = 'unpaid';
        $i_invoiceid = null;
        $i_customerid = null;
        $b_canceled = null;
        $i_typeid = null;
        $str_from = null;
        $str_to = null;
        $i_userid = $o_user->getUserid();

        if(isset($_REQUEST['search']))
        {
            $a_validators = array(
                'search' => [
                    'status' => v::alnum()->noWhitespace()->length(1),
                    'invoiceid' => v::optional(v::intVal()->length(1)->positive()),
                    'customerid' => v::optional(v::intVal()->length(1)->positive()),
                    'canceled' => v::oneOf(v::boolVal(),
                                           v::nullType()),
                    'typeid' => v::optional(v::intVal()->length(1)->positive()),
                    'from' => v::optional(v::date()),
                    'to' => v::optional(v::date()),
                    'userid' => v::oneOf(v::intVal()->length(1)->positive(),
                                         v::equals('*'))]
            );

            $this->validate($a_validators, $_REQUEST);

            $str_status = $_REQUEST['search']['status'];
            $i_userid = $_REQUEST['search']['userid'];

            if(isset($_REQUEST['search']['invoiceid']))
                $i_invoiceid = $_REQUEST['search']['invoiceid'];

            if(isset($_REQUEST['search']['customerid']))
                $i_customerid = $_REQUEST['search']['customerid'];

            if(isset($_REQUEST['search']['canceled']) && $_REQUEST['search']['canceled'] !== "")
                $b_canceled = filter_var($_REQUEST['search']['canceled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            if(isset($_REQUEST['search']['typeid']))
                $i_typeid = $_REQUEST['search']['typeid'];

            if(isset($_REQUEST['search']['from']))
                $str_from = $_REQUEST['search']['from'];

            if(isset($_REQUEST['search']['to']))
                $str_to = $_REQUEST['search']['to'];
        }

        $o_searchCriteria = InvoiceQuery::create()
                                        ->useEventContactRelatedByEventContactidQuery()
                                            ->filterByEventid($o_user->getEventUser()->getEventid())
                                        ->endUse()
                                        ->_if($str_status == 'paid')
                                            ->filterByPaymentFinished(null, Criteria::NOT_EQUAL)
                                        ->_elseif($str_status == 'unpaid')
                                            ->filterByPaymentFinished(null)
                                        ->_endif()
                                        ->_if($i_invoiceid)
                                            ->filterByInvoiceid($i_invoiceid)
                                        ->_endif()
                                        ->_if($i_customerid)
                                            ->filterByCustomerEventContactid($i_customerid)
                                        ->_endif()
                                        ->_if($b_canceled === true)
                                            ->filterByCanceledInvoiceid(null, Criteria::NOT_EQUAL)
                                        ->_elseif($b_canceled === false)
                                            ->filterByCanceledInvoiceid(null)
                                        ->_endif()
                                        ->_if($i_typeid)
                                            ->filterByInvoiceTypeid($i_typeid)
                                        ->_endif()
                                        ->_if($i_userid != '*')
                                            ->filterByUserid($i_userid)
                                        ->_endif()
                                        ->_if($str_from)
                                            ->filterByDate(array('min' => new DateTime($str_from)))
                                        ->_endif()
                                        ->_if($str_to)
                                            ->filterByDate(array('max' => new DateTime($str_to)))
                                        ->_endif()
                                        ->orderByDate();

        if(isset($_REQUEST['page']) && isset($_REQUEST['elementsPerPage'])) {
            $a_validators = array(
                'page' => v::intVal()->length(1)->positive(),
                'elementsPerPage' => v::intVal()->length(1)->positive()
            );

            $this->validate($a_validators, $_REQUEST);

            $o_invoiceList = InvoiceQuery::create(null, clone $o_searchCriteria)
                                            ->offset(($_REQUEST['elementsPerPage'] * $_REQUEST['page']) - $_REQUEST['elementsPerPage'])
                                            ->limit($_REQUEST['elementsPerPage'])
                                            ->find();
        }

        $o_criteriaData = InvoiceQuery::create(null, $o_searchCriteria)
                                        ->joinWithInvoiceType();

        $i_invoice_count = InvoiceQuery::create(null, clone $o_criteriaData)->count();

        $o_invoice = InvoiceQuery::create(null, $o_criteriaData)
                                    ->_if(!empty($o_invoiceList))
                                        ->where(InvoiceTableMap::COL_INVOICEID . " IN ?", $o_invoiceList->getColumnValues())
                                    ->_endif()
                                    ->find();

        $this->withJson(["Count" => $i_invoice_count,
                         "Invoice" => $o_invoice->toArray()]);
    }

    function POST() : void {
        $o_user = Auth::GetCurrentUser();
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $o_invoice_template = new InvoiceModel();
            $this->jsonToPropel($this->a_json, $o_invoice_template);

            // fix given ISO Date
            $o_invoice_template->setMaturityDate(date("c", strtotime($this->a_json['MaturityDate'])));

            $o_event_contact = EventContactQuery::create()
                                                ->filterByEventid($o_user->getEventUser()->getEventid())
                                                ->filterByDefault(true)
                                                ->findOne();

            $o_event_bankinformation = EventBankinformationQuery::create()
                                                                ->findOneByActive(true);

            $o_invoice = new InvoiceModel();
            $o_invoice->setEventContactRelatedByEventContactid($o_event_contact);
            $o_invoice->setUser($o_user);
            $o_invoice->setEventBankinformation($o_event_bankinformation);
            $o_invoice->setDate(new DateTime());

            $o_invoice->setInvoiceTypeid($o_invoice_template->getInvoiceTypeid());
            $o_invoice->setMaturityDate($o_invoice_template->getMaturityDate());

            if($o_invoice_template->getCustomerEventContactid())
                $o_invoice->setCustomerEventContactid ($o_invoice_template->getCustomerEventContactid());

            $o_invoice->save();

            $i_amount = 0;

            foreach($o_invoice_template->getInvoiceItems() as $o_invoiceItem_template) {
                $o_invoiceItem = new InvoiceItem();
                $o_invoiceItem->setInvoice($o_invoice);
                $o_invoiceItem->setAmount($o_invoiceItem_template->getAmount());
                $o_invoiceItem->setPrice($o_invoiceItem_template->getPrice());
                $o_invoiceItem->setDescription($o_invoiceItem_template->getDescription());
                $o_invoiceItem->setTax($o_invoiceItem_template->getTax());
                $o_invoiceItem->save();

                $i_amount += $o_invoiceItem->getPrice() * $o_invoiceItem->getAmount();
            }

            $o_invoice->setAmount($i_amount);
            $o_invoice->save();

            $o_connection->commit();

            $this->withJson($o_invoice_template->toArray());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }
}