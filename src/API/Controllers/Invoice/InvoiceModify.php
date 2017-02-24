<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
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
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['PATCH' => USER_ROLE_INVOICE_CANCEL];

        $app->getContainer()['db'];
    }

    protected function any() : void
    {
        $validators = array(
            'id' => v::intVal()->positive()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function patch() : void
    {
        $invoice = InvoiceQuery::create()
                                ->innerJoinWithInvoiceItem()
                                ->filterByInvoiceid($this->args['id'])
                                ->find()
                                ->getFirst();

        //-- allready canceled
        if ($invoice->getCanceledInvoiceid()) {
            throw new Exception('Invoice allready canceled');
        }

        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $newInvoice = new Invoice();
            $newInvoice->setInvoiceTypeid(INVOICE_TYPE_CANCELLATION);
            $newInvoice->setEventContactid($invoice->getEventContactid());
            $newInvoice->setUser($user);
            $newInvoice->setEventBankinformationid($invoice->getEventBankinformationid());
            $newInvoice->setCustomerEventContactid($invoice->getCustomerEventContactid());
            $newInvoice->setDate(new DateTime());
            $newInvoice->setAmount($invoice->getAmount() * -1);
            $newInvoice->setMaturityDate(new DateTime());
            $newInvoice->save();

            foreach ($invoice->getInvoiceItems() as $invoiceItem) {
                $newInvoiceItem = new InvoiceItem();
                $newInvoiceItem->setInvoice($newInvoice);
                $newInvoiceItem->setAmount($invoiceItem->getAmount());
                $newInvoiceItem->setPrice($invoiceItem->getPrice() * -1);
                $newInvoiceItem->setDescription($invoiceItem->getDescription());
                $newInvoiceItem->setTax($invoiceItem->getTax());
                $newInvoiceItem->save();
            }

            $invoice->setCanceledInvoiceid($newInvoice->getInvoiceid());
            $invoice->save();

            $connection->commit();

            $this->withJson($newInvoice->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }
}
