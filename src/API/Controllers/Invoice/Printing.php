<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\ReciepPrint;
use API\Lib\SecurityController;
use API\Models\Event\Base\EventPrinterQuery;
use API\Models\Invoice\Base\InvoiceQuery;
use API\Models\Payment\Base\PaymentRecievedQuery;
use API\Models\Payment\Map\PaymentCouponTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;

class Printing extends SecurityController
{
    private $b_withPayments;

    public function __construct(App $o_app, bool $b_withPayments) {
        parent::__construct($o_app);

        $this->b_withPayments = $b_withPayments;
        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators_json = array(
            'EventPrinterid' => v::alnum()->length(1),
        );

        $a_validators_args = array(
            'Invoiceid' => v::alnum()->length(1),
        );

        $this->validate($a_validators_args, $this->a_args);
        $this->validate($a_validators_json, $this->a_json);
    }

    protected function POST() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_printer = EventPrinterQuery::create()
                                        ->findOneByEventPrinterid($this->a_json['EventPrinterid']);

        $o_invoice = InvoiceQuery::create()
                                   ->joinWithInvoiceItem()
                                   ->joinWithEventBankinformation()
                                   ->joinWithEventContactRelatedByEventContactid()
                                   //->leftJoinWithEventContactRelatedByCustomerEventContactid()
                                   ->leftJoinWith("EventContactRelatedByCustomerEventContactid b")
                                   ->joinWithUser()
                                   ->filterByInvoiceid($this->a_args['Invoiceid'])
                                   ->find()
                                   ->getFirst();

        if($this->b_withPayments) {
            $o_payments_recieved = PaymentRecievedQuery::create()
                                                        ->joinWithUser()
                                                        ->leftJoinWithPaymentCoupon()
                                                        ->usePaymentCouponQuery(null, Criteria::LEFT_JOIN)
                                                            ->leftJoinCoupon()
                                                        ->endUse()
                                                        ->with(PaymentCouponTableMap::getTableMap()->getPhpName())
                                                        ->filterByInvoice($o_invoice)
                                                        ->find();
        }

        $a_config = $this->o_app->getContainer()['settings'];
        $a_organisation = $a_config['Organisation'];
        $a_invoice = $a_config['Invoice'];
        $o_i18n = $this->o_app->getContainer()['i18n'];

        $o_invoiceUser = $o_invoice->getUser();
        $o_connector = ReciepPrint::GetConnector($o_printer);
        $o_reciepPrint = new ReciepPrint($o_connector, $o_printer->getCharactersPerRow(), $o_i18n->ReciepPrint);

        $str_tableNr = null;

        foreach($o_invoice->getInvoiceItems() as $o_invoice_item) {

            if($str_tableNr === null && $o_invoice_item->getOrderDetailid() != null ) {
                $str_tableNr = $o_invoice_item->getOrderDetail()->getOrder()->getEventTable()->getName();
            }

            $o_reciepPrint->Add($o_invoice_item->getDescription(),
                                $o_invoice_item->getAmount(),
                                $o_invoice_item->getPrice(),
                                $o_invoice_item->getTax());
        }

        if($this->b_withPayments) {
            $i_payment_recievedid = null;
            foreach($o_payments_recieved as $o_payment_recieved) {
                if(!$i_payment_recievedid || $o_payment_recieved->getPaymentRecievedid() > $i_payment_recievedid)
                    $i_payment_recievedid = $o_payment_recieved->getPaymentRecievedid();

                $o_reciepPrint->AddPaymentRecieved($o_payment_recieved);
            }
            $o_reciepPrint->SetPaymentid($i_payment_recievedid);
        }

        if($o_invoice->getEventContactRelatedByCustomerEventContactid())
            $o_reciepPrint->SetCustomer($o_invoice->getEventContactRelatedByCustomerEventContactid());

        if($a_invoice['Logo']['Use'])
            $o_reciepPrint->SetLogo($a_invoice['Logo']['Path'], $a_invoice['Logo']['Type']);

        $o_reciepPrint->SetHeader($a_invoice['Header']);
        $o_reciepPrint->SetContact($o_invoice->getEventContactRelatedByEventContactid());
        $o_reciepPrint->SetInvoiceid($o_invoice->getInvoiceid());
        $o_reciepPrint->SetTableNr($str_tableNr);
        $o_reciepPrint->SetName($o_invoiceUser->getFirstname() . " " . $o_invoiceUser->getLastname());
        $o_reciepPrint->SetDate($o_invoice->getDate());
        $o_reciepPrint->SetBankinformation($o_invoice->getEventBankinformation());
        $o_reciepPrint->SetMaturityDate($o_invoice->getMaturityDate());

        try {
            $o_reciepPrint->PrintInvoice();

            $this->withJson(true);
        } catch(Exception $o_exception) {
            throw new Exception("Rechnungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Rechnungsnummer: $a_params[invoiceid]", $o_exception->getCode(), $o_exception);
        }
    }
}