<?php
namespace API\Lib;

use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Event\EventPrinter;
use API\Models\Payment\PaymentRecieved;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use stdClass;
use const API\DATE_PHP_DATEFORMAT;
use const API\DATE_PHP_TIMEFORMAT;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PAYMENT_TYPE_CASH;
use const API\PRINTER_CHARACTER_EURO;
use const API\PRINTER_LOGO_BIT_IMAGE;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;
use const API\PRINTER_LOGO_DEFAULT;
use const API\PRINTER_TYPE_CUPS;
use const API\PRINTER_TYPE_DUMMY;
use const API\PRINTER_TYPE_FILE;
use const API\PRINTER_TYPE_NETWORK;
use const API\PRINTER_TYPE_WINDOWS;
use function mb_str_pad;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReciepPrint
{
    private $i18n;

    private $entries = array();

    private $printer;

    private $date;

    private $dateFooter;

    private $invoiceid;

    private $orderNr;

    private $paymentid;

    private $tableNr;

    private $header;

    private $contact;

    private $customer;

    private $paperRowLength;

    private $paymentRecieved = array();

    private $eventBankinformation;

    private $name;

    private $maturityDate;

    private $connectorOpen = false;

    private $logo;

    private $logoType;

    const RIGHT_COLS = 8;

    const LEFT_PADDING = 4;

    public function __construct(PrintConnector $connector, $paperRowLength, stdClass $i18n)
    {
        $this->printer = new Printer($connector);
        $this->connectorOpen = true;
        $this->paperRowLength = $paperRowLength;
        $this->i18n = $i18n;
    }

    public function __destruct()
    {
        $this->close();
    }

    public static function getConnector(EventPrinter $printer) : PrintConnector
    {
        switch ($printer->getType()) {
            case PRINTER_TYPE_NETWORK:
                return new NetworkPrintConnector($printer->getAttr1(), $printer->getAttr2());

            case PRINTER_TYPE_FILE:
                return new FilePrintConnector($printer->getAttr1());

            case PRINTER_TYPE_WINDOWS:
                return new WindowsPrintConnector($printer->getAttr1());

            case PRINTER_TYPE_CUPS:
                return new CupsPrintConnector($printer->getAttr1());

            case PRINTER_TYPE_DUMMY:
                return new DummyPrintConnector();
        }
    }

    public function add($name, $amount, $price = null, $tax = null)
    {
        if (!isset($this->entries[$tax])) {
            $this->entries[$tax] = array();
        }

        foreach ($this->entries[$tax] as $key => $entrie) {
            if ($entrie['name'] == $name && $entrie['price'] == $price) {
                $this->entries[$tax][$key]['amount'] += $amount;
                return;
            }
        }

        $entrie = array('name' => $name,
                          'amount' => $amount,
                          'price' => $price);

        $this->entries[$tax][] = $entrie;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setDateFooter($date)
    {
        $this->dateFooter = $date;
    }

    public function setInvoiceid($invoiceid)
    {
        $this->invoiceid = $invoiceid;
    }

    public function setPaymentid($paymentid)
    {
        $this->paymentid = $paymentid;
    }

    public function setOrderNr($orderNr)
    {
        $this->orderNr = $orderNr;
    }

    public function setTableNr($tableNr)
    {
        $this->tableNr = $tableNr;
    }

    public function setHeader($text)
    {
        $this->header = $text;
    }

    public function setContact(EventContact $eventContact)
    {
        $this->contact = $eventContact;
    }

    public function setCustomer(EventContact $eventContact)
    {
        $this->customer = $eventContact;
    }

    public function addPaymentRecieved(PaymentRecieved $paymentRecieved)
    {
        $this->paymentRecieved[] = $paymentRecieved;
    }

    public function setBankinformation(EventBankinformation $eventBankinformation)
    {
        $this->eventBankinformation = $eventBankinformation;
    }

    public function setMaturityDate($maturityDate)
    {
        $this->maturityDate = $maturityDate;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setLogo($file, $type)
    {
        $this->logo = $file;
        $this->logoType = $type;
    }

    public function close()
    {
        if ($this->connectorOpen) {
            $this->printer->close();
            $this->connectorOpen = false;
        }
    }

    public function printOrder()
    {
        /* Print top logo and header */

        if ($this->logo) {
            $logo = EscposImage::load($this->logo);

            if ($this->logoType == PRINTER_LOGO_DEFAULT) {
                $this->printer->graphics($logo);
            } elseif ($this->logoType == PRINTER_LOGO_BIT_IMAGE) {
                $this->printer->bitImage($logo);
            } elseif ($this->logoType == PRINTER_LOGO_BIT_IMAGE_COLUMN) {
                $this->printer->bitImageColumnFormat($logo);
            }
        }

        /* Title of receipt */
        $this->printer -> setEmphasis(true);
        $this->printer -> text($this->i18n->orderNr . ": " . $this->orderNr  . "\n");
        $this->printer -> text($this->i18n->tableNr . ": ");
        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
        $this->printer -> text($this->tableNr  . "\n");
        $this->printer -> selectPrintMode();
        $this->printer -> text($this->i18n->recordedBy . ": " . $this->name  . "\n");
        $this->printer -> text($this->i18n->recordedAt . ": " . date_format($this->date, DATE_PHP_TIMEFORMAT) . "\n");
        $this->printer -> setEmphasis(false);

        /* Items */
        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer -> setEmphasis(true);
        $this->printer -> text($this->i18n->title . "\n");
        $this->printer -> selectPrintMode();
        $this->printer -> setEmphasis(false);

        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        $leftCols = $this->paperRowLength;

        $leftCols = $leftCols / 2;

        foreach ($this->entries as $entries) {
            foreach ($entries as $entrie) {
                $text = $entrie['amount'] . $this->i18n->multiplier . " " . $entrie['name'];

                $leftElements = explode(' ', $text);
                $final = array('');
                $row = "";
                $rowCounter = 0;

                for ($i = 0; $i < count($leftElements); $i++) {
                    $word = $leftElements[$i];

                    if ($i == 0) {
                        $leftPadding = strlen($word) + 1;
                    }

                    if ($row == '') {
                        $tmp = $word;
                    } else {
                        $tmp = $row . ' ' . $word;
                    }

                    if ($i > 1 && strlen($tmp) > $leftCols) {
                        $final[$rowCounter] .= mb_str_pad($row, $leftCols);
                        $rowCounter++;
                        $final[$rowCounter] = '';

                        $row = mb_str_pad(' ', $leftPadding) . $word;
                    } else {
                        $row = $tmp;
                    }
                }

                $final[$rowCounter] .= mb_str_pad($row, $leftCols);

                $this->printer -> text(join("\n", $final) . "\n");
            }
        }

        $this->printer -> setEmphasis(false);
        $this->printer -> selectPrintMode();

        /* Footer */
        $this->printer -> feed(2);
        $this->printer -> text($this->i18n->distributionTime . ": " . date_format($this->dateFooter, DATE_PHP_TIMEFORMAT));
        $this->printer -> feed(2);

        /* Cut the receipt and open the cash drawer */
        $this->printer -> cut();
        $this->printer -> pulse();

        $this->close();
    }

    public function printInvoice()
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

    public function printPaymentRecieved()
    {
        $paymentRecieved = $this->paymentRecieved[0];
        $user = $paymentRecieved->getUser();

        /* Print top logo and header */
        $this->printHeader();

        // Add customer data if set
        if ($this->customer) {
            $this->printCustomer();
        }
        $this->printer -> feed();

        /* Title of receipt */
        $this->printer -> setEmphasis(true);
        $this->printer -> text($this->i18n->receiptNr . ": " . $paymentRecieved->getPaymentRecievedid() . "\n");
        $this->printer -> text($this->i18n->invoiceNr . ": " . $this->invoiceid  . "\n");
        $this->printer -> text($this->i18n->cashier . ": " . $user->getFirstname() . " " . $user->getLastname()  . "\n");
        $this->printer -> feed();
        $this->printer -> setEmphasis(false);

        // Add Coupons if used
        $total = $paymentRecieved->getAmount();
        $tmpTotal = $total;
        if (count($paymentRecieved->getPaymentCoupons()) > 0) {
            $this->printCoupons($paymentRecieved, $tmpTotal);
        }

        $payedByCoupons = bcsub($total, $tmpTotal, 2);
        $total = bcsub($total, $paymentRecieved->getAmount(), 2);

        // add payment type and bank information if given
        $this->printPaymentRecievedType($paymentRecieved, bcsub($paymentRecieved->getAmount(), $payedByCoupons, 2));

        // add bank information if given
        if ($this->eventBankinformation) {
            $this->printBankInformation();
        }

        $this->printer -> feed();

        /* Cut the receipt and open the cash drawer */
        $this->printer -> cut();
        $this->printer -> pulse();

        $this->close();
    }

    private function printBankInformation()
    {
        $this->printer -> text($this->i18n->bankInformation . ":\n");
        $this->printer -> text($this->i18n->bankName . ": " . $this->eventBankinformation->getName() . "\n");
        $this->printer -> text($this->i18n->iban . ": " . $this->eventBankinformation->getIban() . "\n");
        $this->printer -> text($this->i18n->bic . ": " . $this->eventBankinformation->getBic() . "\n");
    }

    private function printPaymentRecievedType(PaymentRecieved $paymentRecieved, $total)
    {
        $this->printer -> feed();

        if ($paymentRecieved->getPaymentTypeid() == PAYMENT_TYPE_CASH) {
            $paymentType = $this->i18n->paymentTypeCash;
        } elseif ($paymentRecieved->getPaymentTypeid() == PAYMENT_TYPE_BANK_TRANSFER) {
            $paymentType = $this->i18n->paymentTypeBankTransfer;
        }

        $this->printItem($paymentType, '', sprintf('%0.2f', $total), true);
        $this->printItem($this->i18n->datePaymentRecieved . ": " . date_format($paymentRecieved->getDate(), DATE_PHP_DATEFORMAT), '', '', false);
    }

    private function printCoupons(PaymentRecieved $paymentRecieved, &$total)
    {
        $this->printItem($this->i18n->usedCoupons, '', '', false);

        foreach ($paymentRecieved->getPaymentCoupons() as $paymentCoupon) {
            $total = bcsub($total, $paymentCoupon->getValueUsed(), 2);
            $this->printItem($this->i18n->couponCode . ": " . $paymentCoupon->getCoupon()->getCode(), '', sprintf('%0.2f', $paymentCoupon->getValueUsed()), true);
        }
    }

    private function printHeader()
    {
        $this->printer -> setJustification(Printer::JUSTIFY_CENTER);

        if ($this->logo) {
            $logo = EscposImage::load($this->logo);

            if ($this->logoType == PRINTER_LOGO_DEFAULT) {
                $this->printer->graphics($logo);
            } elseif ($this->logoType == PRINTER_LOGO_BIT_IMAGE) {
                $this->printer->bitImage($logo);
            } elseif ($this->logoType == PRINTER_LOGO_BIT_IMAGE_COLUMN) {
                $this->printer->bitImageColumnFormat($logo);
            }
        }

        $this->printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer -> text($this->header);

        if ($this->contact) {
            $this->printEventContact($this->contact);
        }

        $this->printer -> selectPrintMode();
        $this->printer -> feed();
        $this->printer -> setJustification(Printer::JUSTIFY_LEFT);
    }

    private function printCustomer()
    {
        $this->printer -> feed();
        $this->printer -> setEmphasis(true);
        $this->printer -> text($this->i18n->customerData . ":\n");
        $this->printer -> setEmphasis(false);
        $this->printer -> text($this->i18n->customerid . ": " . $this->customer->getEventContactid() . "\n");

        $this->printEventContact($this->customer);
    }

    private function printEventContact(EventContact $eventContact)
    {
        $text = $eventContact->getTitle() . "\n";
        $text .= $eventContact->getName() . "\n";

        if ($eventContact->getContactPerson()) {
            $text .= $eventContact->getContactPerson() . "\n";
        }

        $text .= $eventContact->getAddress() . "\n";

        if ($eventContact->getAddress2()) {
            $text .= $eventContact->getAddress2() . "\n";
        }

        $text .= $eventContact->getZip() . ' ' . $eventContact->getCity() . "\n";

        if ($eventContact->getTaxIdentificationNr()) {
            $text .= $this->i18n->tax . ": " . $eventContact->getTaxIdentificationNr() . "\n";
        }

        if ($eventContact->getTelephon()) {
            $text .= $this->i18n->tel . ": " . $eventContact->getTelephon(). "\n";
        }

        if ($eventContact->getFax()) {
            $text .= $this->i18n->fax . ": " . $eventContact->getFax(). "\n";
        }

        if ($eventContact->getEmail()) {
            $text .= $this->i18n->email . ": " . $eventContact->getEmail();
        }

        $this->printer -> text(trim($text));
    }

    private function printItem($name, $amount = 0, $price = 0, $currencySign = false, $bold = false)
    {
        $rightCols = self::RIGHT_COLS;
        $leftPadding = self::LEFT_PADDING;
        $leftCols = $this->paperRowLength - $rightCols;

        if ($bold) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }

        $leftElements = explode(' ', $name);
        $final = array('');
        $row = "";
        $rowCounter = 0;

        for ($i = 0; $i < count($leftElements); $i++) {
            $word = $leftElements[$i];
            $tmp = $row . ' ' . $word;

            if (strlen($tmp) > $leftCols) {
                $final[$rowCounter] .= mb_str_pad($row, $leftCols);
                $rowCounter++;
                $final[$rowCounter] = '';

                $row = mb_str_pad(' ', $leftPadding) . $word;
            } else {
                $row = $tmp;
            }
        }

        $final[$rowCounter] .= mb_str_pad($row, $leftCols);

        $sign = ($currencySign ? $this->i18n->currency . ' ' : '');

        if ($amount) {
            $right = mb_str_pad($sign . sprintf('%0.2f', $price * $amount), $rightCols, ' ', STR_PAD_LEFT);
        } else {
            $right = mb_str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
        }

        $final[0] .= $right;
        $final = join("\n", $final);

        if ($amount) {
            $final .= "\n" . mb_str_pad(' ', $leftPadding) . $amount . ' ' . $this->i18n->multiplier . ' ' . $this->i18n->currency . ' ' . sprintf('%0.2f', $price);
        }

        $final .= "\n";

        //-- special EURO sign handling needed as this sign is in ESC/POS standard in an special caracter table
        $finalParts = explode('€', $final);

        for ($i = 0; $i < count($finalParts); $i++) {
            if ($i > 0) {
                $this->printer->getPrintConnector()->write(PRINTER_CHARACTER_EURO);
            }

            $this->printer->text($finalParts[$i]);
        }
    }
}
