<?php

namespace API\Lib\Printer\PrintingType;

use Mike42\Escpos\Printer;
use const API\DATE_PHP_DATEFORMAT;
use const API\DATE_PHP_TIMEFORMAT;

class Invoice extends AbstractPrintingType
{
    public function printType()
    {
        /* Print top logo and header */
        if($this->printingInformation->getLogoFile()) {
            $this->printerConnector->setLogo($this->printingInformation->getLogoFile(),
                                             $this->printingInformation->getLogoType());
        }
        $this->printerConnector->setHeader($this->printingInformation->getHeader());
        $this->printerConnector->setContactInformation($this->printingInformation->getContact());

        // Add customer data if set
        if ($this->printingInformation->getCustomer()) {
            $this->printerConnector->setCustomerContactInformation($this->printingInformation->getCustomer());
        }

        /* Title of receipt */
        if ($this->printingInformation->getPaymentid()) {
            $this->printerConnector->addHeaderInfo($this->localization->receiptNr, $this->printingInformation->getPaymentid());
        }

        $this->printerConnector->addHeaderInfo($this->localization->invoiceNr, $this->printingInformation->getInvoiceid());

        if ($this->printingInformation->getTableNr()) {
            $this->printerConnector->addHeaderInfo($this->localization->tableNr, $this->printingInformation->getTableNr());
        }

        /* Items */
        $this->printerConnector->setDetailHeader($this->localization->amountAndTitle, '', $this->localization->price);

        $total = 0;
        $taxes = array();

        foreach ($this->entries as $taxPercent => $entries) {
            if (!isset($taxes[$taxPercent])) {
                $taxes[$taxPercent] = 0;
            }

            foreach ($entries as $entrie) {
                $this->printerConnector->addDetail($entrie['name'], $entrie['amount'], $entrie['price'], true);
                $price = $entrie['amount'] * $entrie['price'];
                $total += $price;

                $taxes[$taxPercent] += $price;
            }
        }

        $this->printerConnector->addSumPos1($this->localization->totalSum, sprintf('%0.2f', $total));

        /* Tax and total */
        foreach ($taxes as $tax => $price) {
            $this->printerConnector->addTax($tax, $price);
        }

        // add payments if given
        if (count($this->paymentRecieved)) {
            foreach ($this->paymentRecieved as $paymentRecieved) {
                $total = bcsub($total, $paymentRecieved->getAmount(), 2);
                $this->printerConnector->addPayment($paymentRecieved);
            }

            $this->printerConnector->addSumPos2($this->localization->totalSumOpen, $total);
        }

        // add a maturity date if given
        if ($this->printingInformation->getMaturityDate()) {
            $this->printerConnector->setMaturityDate($this->printingInformation->getMaturityDate());
        }

        // add bank information if given
        if ($this->printingInformation->getBankinformation()) {
            $this->printerConnector->setBankinformation($this->printingInformation->getBankinformation());
        }

        /* Footer */
        $this->printerConnector->addFooterInfo($this->localization->thanks);
        $this->printerConnector->addFooterInfo(($this->printingInformation->getDate()) ? date_format($this->printingInformation->getDate(), DATE_PHP_TIMEFORMAT) : date(DATE_PHP_TIMEFORMAT));

        $this->printerConnector->printDocument();
        $this->printerConnector->close();
    }

}