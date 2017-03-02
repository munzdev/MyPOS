<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Printer;
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
    private $withPayments;

    public function __construct(App $app, bool $withPayments)
    {
        parent::__construct($app);

        $this->withPayments = $withPayments;
        $app->getContainer()['db'];
    }

    public function any() : void
    {
        $validatorsJson = array(
            'EventPrinterid' => v::alnum()->length(1),
        );

        $validatorsArgs = array(
            'Invoiceid' => v::alnum()->length(1),
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validatorsArgs, $this->args);
        $validate->assert($validatorsJson, $this->json);
    }

    protected function post() : void
    {
        $printer = EventPrinterQuery::create()
                                        ->findOneByEventPrinterid($this->json['EventPrinterid']);

        $invoice = InvoiceQuery::create()
                                ->joinWithInvoiceItem()
                                ->joinWithEventBankinformation()
                                ->joinWithEventContactRelatedByEventContactid()
                                //->leftJoinWithEventContactRelatedByCustomerEventContactid()
                                ->leftJoinWith("EventContactRelatedByCustomerEventContactid b")
                                ->joinWithUser()
                                ->filterByInvoiceid($this->args['Invoiceid'])
                                ->find()
                                ->getFirst();

        if ($this->withPayments) {
            $paymentsRecieved = PaymentRecievedQuery::create()
                                                        ->joinWithUser()
                                                        ->leftJoinWithPaymentCoupon()
                                                        ->usePaymentCouponQuery(null, Criteria::LEFT_JOIN)
                                                            ->leftJoinCoupon()
                                                        ->endUse()
                                                        ->with(PaymentCouponTableMap::getTableMap()->getPhpName())
                                                        ->filterByInvoice($invoice)
                                                        ->find();
        }

        $config = $this->app->getContainer()['settings'];
        $invoiceConfig = $config['Invoice'];
        $i18n = $this->app->getContainer()['i18n'];

        $invoiceUser = $invoice->getUser();
        $connector = Printer::getConnector($printer);
        $reciepPrint = new Printer($connector, $printer->getCharactersPerRow(), $i18n->ReciepPrint);

        $tableNr = null;

        foreach ($invoice->getInvoiceItems() as $invoiceItem) {
            if ($tableNr === null && $invoiceItem->getOrderDetailid() != null) {
                $tableNr = $invoiceItem->getOrderDetail()->getOrder()->getEventTable()->getName();
            }

            $reciepPrint->add(
                $invoiceItem->getDescription(),
                $invoiceItem->getAmount(),
                $invoiceItem->getPrice(),
                $invoiceItem->getTax()
            );
        }

        if ($this->withPayments) {
            $paymentRecievedid = null;
            foreach ($paymentsRecieved as $paymentRecieved) {
                if (!$paymentRecievedid || $paymentRecieved->getPaymentRecievedid() > $paymentRecievedid) {
                    $paymentRecievedid = $paymentRecieved->getPaymentRecievedid();
                }

                $reciepPrint->addPaymentRecieved($paymentRecieved);
            }
            $reciepPrint->setPaymentid($paymentRecievedid);
        }

        if ($invoice->getEventContactRelatedByCustomerEventContactid()) {
            $reciepPrint->setCustomer($invoice->getEventContactRelatedByCustomerEventContactid());
        }

        if ($invoiceConfig['Logo']['Use']) {
            $reciepPrint->setLogo($invoiceConfig['Logo']['Path'], $invoiceConfig['Logo']['Type']);
        }

        $reciepPrint->setHeader($invoiceConfig['Header']);
        $reciepPrint->setContact($invoice->getEventContactRelatedByEventContactid());
        $reciepPrint->setInvoiceid($invoice->getInvoiceid());
        $reciepPrint->setTableNr($tableNr);
        $reciepPrint->setName($invoiceUser->getFirstname() . " " . $invoiceUser->getLastname());
        $reciepPrint->setDate($invoice->getDate());
        $reciepPrint->setBankinformation($invoice->getEventBankinformation());
        $reciepPrint->setMaturityDate($invoice->getMaturityDate());

        try {
            $reciepPrint->printInvoice();

            $this->withJson(true);
        } catch (Exception $exception) {
            throw new Exception("Rechnungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Rechnungsnummer: {$this->args['Invoiceid']}", $exception->getCode(), $exception);
        }
    }
}
