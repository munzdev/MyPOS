<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Invoice\Invoice;
use API\Models\Invoice\InvoiceItem;
use API\Models\Invoice\InvoiceQuery;
use DateTime;
use Exception;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\INVOICE_TYPE_CANCELLATION;
use const API\USER_ROLE_INVOICE_CANCEL;

class InvoiceModify extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['PATCH' => USER_ROLE_INVOICE_CANCEL];

        $o_app->getContainer()['db'];
    }

    protected function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive()
        );

        $this->validate($a_validators, $this->a_args);
    }

    protected function PATCH() : void {
        $o_invoice = InvoiceQuery::create()
                                   ->innerJoinWithInvoiceItem()
                                   ->filterByInvoiceid($this->a_args['id'])
                                   ->find()
                                   ->getFirst();

        //-- allready canceled
        if($o_invoice->getCanceledInvoiceid())
            throw new Exception ('Invoice allready canceled');

        $o_user = Auth::GetCurrentUser();
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $o_new_invoice = new Invoice();
            $o_new_invoice->setInvoiceTypeid(INVOICE_TYPE_CANCELLATION);
            $o_new_invoice->setEventContactid($o_invoice->getEventContactid());
            $o_new_invoice->setUser($o_user);
            $o_new_invoice->setEventBankinformationid($o_invoice->getEventBankinformationid());
            $o_new_invoice->setCustomerEventContactid($o_invoice->getCustomerEventContactid());
            $o_new_invoice->setDate(new DateTime());
            $o_new_invoice->setAmount($o_invoice->getAmount() * -1);
            $o_new_invoice->setMaturityDate(new DateTime());
            $o_new_invoice->save();

            foreach($o_invoice->getInvoiceItems() as $o_invoiceItem) {
                $o_new_invoiceItem = new InvoiceItem();
                $o_new_invoiceItem->setInvoice($o_new_invoice);
                $o_new_invoiceItem->setAmount($o_invoiceItem->getAmount());
                $o_new_invoiceItem->setPrice($o_invoiceItem->getPrice() * -1);
                $o_new_invoiceItem->setDescription($o_invoiceItem->getDescription());
                $o_new_invoiceItem->setTax($o_invoiceItem->getTax());
                $o_new_invoiceItem->save();
            }

            $o_invoice->setCanceledInvoiceid($o_new_invoice->getInvoiceid());
            $o_invoice->save();

            $o_connection->commit();

            $this->withJson($o_new_invoice->toArray());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }
}