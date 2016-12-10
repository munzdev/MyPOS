<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\ReciepPrint;
use API\Lib\SecurityController;
use API\Models\Event\Base\EventPrinterQuery;
use API\Models\Invoice\Base\InvoiceQuery;
use API\Models\Payment\Map\PaymentCouponTableMap;
use API\Models\Payment\Map\PaymentTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\DATE_PHP_TIMEFORMAT;

class Printing extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);        
        
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
                                   ->joinWithUser()
                                   ->usePaymentQuery(null, Criteria::LEFT_JOIN)
                                        ->usePaymentCouponQuery(null, Criteria::LEFT_JOIN)
                                            ->leftJoinWithCoupon()
                                        ->endUse()
                                   ->endUse()
                                   ->with(PaymentTableMap::getTableMap()->getPhpName())
                                   ->with(PaymentCouponTableMap::getTableMap()->getPhpName())
                                   ->findByInvoiceid($this->a_args['Invoiceid'])
                                   ->getFirst();
        
        $a_config = $this->o_app->getContainer()['settings']['Organisation'];
        $o_i18n = $this->o_app->getContainer()['i18n'];
        $a_invoice = $a_config['Invoice'];

        $o_cashierUser = $o_invoice->getUser();
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

        if($a_invoice['Logo']['Use'])
            $o_reciepPrint->SetLogo($a_invoice['Logo']['Path'], $a_invoice['Logo']['Type']);

        $o_reciepPrint->SetHeader($a_invoice['Header']);
        $o_reciepPrint->SetNr($o_invoice->getInvoiceid());
        $o_reciepPrint->SetTableNr($str_tableNr);
        $o_reciepPrint->SetName($o_cashierUser->getFirstname() . " " . $o_cashierUser->getLastname());
        $o_reciepPrint->SetDate($o_invoice->getDate());

        try
        {
            $o_reciepPrint->PrintInvoice();

            $this->o_response->withJson(true);
        }
        catch(Exception $o_exception)
        {
            throw new Exception("Rechnungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Rechnungsnummer: $a_params[invoiceid]", $o_exception->getCode(), $o_exception);
        }
    }
}