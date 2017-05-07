<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IPrintingInformation;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedQuery;
use API\Lib\Printer;
use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Invoice;
use API\Lib\SecurityController;
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
        $this->container->get(IConnectionInterface::class);
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
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);
        $auth = $this->container->get(IAuth::class);
        $invoiceQuery = $this->container->get(IInvoiceQuery::class);
        $paymentRecievedQuery = $this->container->get(IPaymentRecievedQuery::class);
        $user = $auth->getCurrentUser();

        $eventPrinter = $eventPrinterQuery->findPk($this->json['EventPrinterid']);
        $invoice = $invoiceQuery->getWithDetails($user->getEventUsers()->getFirst()->getEventid(), $this->args['Invoiceid']);

        if ($this->withPayments) {
            $paymentsRecieveds = $paymentRecievedQuery->getDetailsForInvoice($invoice->getInvoiceid());
        }

        $config = $this->container->get('settings');
        $invoiceConfig = $config['Invoice'];

        $invoiceUser = $invoice->getUser();

        $printingInformation = $this->container->get(IPrintingInformation::class);

        $tableNr = null;

        foreach ($invoice->getInvoiceItems() as $invoiceItem) {
            if ($tableNr === null && $invoiceItem->getOrderDetailid() != null) {
                $tableNr = $invoiceItem->getOrderDetail()->getOrder()->getEventTable()->getName();
            }

            $printingInformation->addRow(
                $invoiceItem->getDescription(),
                $invoiceItem->getAmount(),
                $invoiceItem->getPrice(),
                $invoiceItem->getTax()
            );
        }

        if ($this->withPayments) {
            $paymentRecievedid = null;
            foreach ($paymentsRecieveds as $paymentRecieved) {
                if (!$paymentRecievedid || $paymentRecieved->getPaymentRecievedid() > $paymentRecievedid) {
                    $paymentRecievedid = $paymentRecieved->getPaymentRecievedid();
                }

                $printingInformation->addPaymentRecieved($paymentRecieved);
            }
            $printingInformation->setPaymentid($paymentRecievedid);
        }

        if ($invoice->getEventContactRelatedByCustomerEventContactid()) {
            $printingInformation->setCustomer($invoice->getCustomerEventContactid());
        }

        if ($invoiceConfig['Logo']['Use']) {
            $printingInformation->setLogoFile($invoiceConfig['Logo']['Path']);
            $printingInformation->setLogoType($invoiceConfig['Logo']['Type']);
        }

        $printingInformation->setHeader($invoiceConfig['Header']);
        $printingInformation->setContact($invoice->getEventContactRelatedByEventContactid());
        $printingInformation->setInvoiceid($invoice->getInvoiceid());
        $printingInformation->setTableNr($tableNr);
        $printingInformation->setName($invoiceUser->getFirstname() . " " . $invoiceUser->getLastname());
        $printingInformation->setDate($invoice->getDate());
        $printingInformation->setBankinformation($invoice->getEventBankinformation());
        $printingInformation->setMaturityDate($invoice->getMaturityDate());

        try {
            $printerConnector = $this->container->get(ThermalPrinter::class);
            $printerConnector->setEventPrinter($eventPrinter);

            $invoice = $this->container->get(Invoice::class);
            $invoice->setPrinterConnector($printerConnector);
            $invoice->setPrintingInformation($printingInformation);
            $invoice->printType();

            $this->withJson(true);
        } catch (Exception $exception) {
            throw new Exception("Rechnungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Rechnungsnummer: {$this->args['Invoiceid']}", $exception->getCode(), $exception);
        }
    }
}
