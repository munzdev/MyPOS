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
        $this->printHeader();

        // Add customer data if set
        if ($this->customer) {
            $this->printCustomer();
            $this->printer -> feed();
        }
        $this->printer -> feed();

        /* Title of receipt */
        $this->printer -> setEmphasis(true);

        if ($this->paymentid) {
            $this->printer -> text($this->i18n->receiptNr . ": " . $this->paymentid . "\n");
        }

        $this->printer -> text($this->i18n->invoiceNr . ": " . $this->invoiceid  . "\n");

        if ($this->tableNr) {
            $this->printer -> text($this->i18n->tableNr . ": " . $this->tableNr  . "\n");
        }

        $this->printer -> text($this->i18n->cashier . ": " . $this->name  . "\n");
        $this->printer -> feed();
        $this->printer -> setEmphasis(false);

        /* Items */
        $this->printer -> setEmphasis(true);
        $this->printItem($this->i18n->amountAndTitle, '', $this->i18n->price);
        $this->printer -> setEmphasis(false);

        $total = 0;
        $taxes = array();

        foreach ($this->entries as $taxPercent => $entries) {
            if (!isset($taxes[$taxPercent])) {
                $taxes[$taxPercent] = 0;
            }

            foreach ($entries as $entrie) {
                $this->printItem($entrie['name'], $entrie['amount'], $entrie['price'], true);
                $price = $entrie['amount'] * $entrie['price'];
                $total += $price;

                $taxes[$taxPercent] += $price;
            }
        }

        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer -> setEmphasis(true);

        $totalText = $this->i18n->totalSum;

        $this->printItem($totalText, '', sprintf('%0.2f', $total), true, true);
        $this->printer -> setEmphasis(false);
        $this->printer -> selectPrintMode();
        $this->printer -> feed();

        /* Tax and total */
        $this->printer->text($this->i18n->totalSumContainsTax . "\n");

        foreach ($taxes as $tax => $price) {
            $this->printItem($tax . $this->i18n->percentTaxOfCurrency . sprintf('%0.2f', $price), '', sprintf('%0.2f', $price * ($tax / 100)), true);
        }

        // add payments if given
        if (count($this->paymentRecieved)) {
            $this->printer->feed();
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->text($this->i18n->payments);
            $this->printer->selectPrintMode();
            $this->printer->feed();

            foreach ($this->paymentRecieved as $paymentRecieved) {
                $tmpTotal = $total;

                // Add Coupons if used
                if (count($paymentRecieved->getPaymentCoupons()) > 0) {
                    $this->printCoupons($paymentRecieved, $tmpTotal);
                }

                $payedByCoupons = bcsub($total, $tmpTotal, 2);

                $total = bcsub($total, $paymentRecieved->getAmount(), 2);

                $this->printPaymentRecievedType($paymentRecieved, bcsub($paymentRecieved->getAmount(), $payedByCoupons, 2));
            }

            $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer -> setEmphasis(true);
            $this->printItem($this->i18n->totalSumOpen, '', sprintf('%0.2f', $total), true, true);
            $this->printer -> setEmphasis(false);
            $this->printer -> selectPrintMode();
            $this->printer -> feed();
        }

        // add a maturity date if given
        if ($this->maturityDate) {
            $this->printer -> feed();
            $this->printer -> text($this->i18n->maturityDate . ": " . date_format($this->maturityDate, DATE_PHP_DATEFORMAT) . "\n");
        }

        // add bank information if given
        if ($this->eventBankinformation) {
            $this->printBankInformation();
        }

        /* Footer */
        $this->printer -> feed(2);
        $this->printer -> setJustification(Printer::JUSTIFY_CENTER);
        $this->printer -> text($this->i18n->thanks . "!\n");
        $this->printer -> text(($this->date) ? date_format($this->date, DATE_PHP_TIMEFORMAT) : date(DATE_PHP_TIMEFORMAT) . "\n");
        $this->printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->printer -> cut();
        $this->printer -> pulse();

        $this->close();
    }

}