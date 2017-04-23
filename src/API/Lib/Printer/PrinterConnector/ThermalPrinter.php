<?php

namespace API\Lib\Printer\PrinterConnector;

use API\Lib\Interfaces\Printer\PrinterConnector\IPrinterConnector;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Event\EventPrinter;
use API\Models\Payment\PaymentRecieved;
use DateTime;
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

class ThermalPrinter implements IPrinterConnector
{
    const RIGHT_COLS = 8;
    const LEFT_PADDING = 4;

    /**4
     * @var Printer
     */
    private $printer;

    /**
     * @var EventPrinter|null
     */
    private $eventPrinter;

    /**
     * @var PrintConnector
     */
    private $printConnector;

    /**
     * @var stdClass
     */
    private $localization;

    private $connectorOpen = false;
    private $paperRowLength;


    private $eventBankinformation;
    private $maturityDate;
    private $sumPos1;
    private $sumPos2;
    private $contactInformation;
    private $customerContactInformation;
    private $header;
    private $logo;
    private $detailsHeader;
    private $detailsTitle;
    private $formatDetailAsList;
    private $details = array();
    private $headerInfos = array();
    private $footerInfos = array();
    private $payments = array();
    private $taxes = array();

    public function __construct(?EventPrinter $eventPrinter, stdClass $localization)
    {
        $this->localization = $localization;

        if($eventPrinter) {
            $this->setEventPrinter($eventPrinter);
        }
    }

    public function setEventPrinter(EventPrinter $eventPrinter)
    {
        $this->eventPrinter = $eventPrinter;
        $this->printConnector = $this->getConnector();
        $this->paperRowLength = $eventPrinter->getCharactersPerRow();
        $this->printer = new Printer($this->printConnector);
        $this->connectorOpen = true;
    }

    private function getConnector() : PrintConnector
    {
        switch ($this->eventPrinter->getType()) {
            case PRINTER_TYPE_NETWORK:
                return new NetworkPrintConnector($this->eventPrinter->getAttr1(), $this->eventPrinter->getAttr2());

            case PRINTER_TYPE_FILE:
                return new FilePrintConnector($this->eventPrinter->getAttr1());

            case PRINTER_TYPE_WINDOWS:
                return new WindowsPrintConnector($this->eventPrinter->getAttr1());

            case PRINTER_TYPE_CUPS:
                return new CupsPrintConnector($this->eventPrinter->getAttr1());

            case PRINTER_TYPE_DUMMY:
                return new DummyPrintConnector();
        }
    }

    private function printBankInformation()
    {
        $this->printer->text($this->localization->bankInformation . ":\n");
        $this->printer->text($this->localization->bankName . ": {$this->eventBankinformation->getName()}\n");
        $this->printer->text($this->localization->iban . ": {$this->eventBankinformation->getIban()}\n");
        $this->printer->text($this->localization->bic . ": {$this->eventBankinformation->getBic()}\n");
    }

    private function printPaymentRecievedType(PaymentRecieved $paymentRecieved, $total)
    {
        $this->printer->feed();

        if ($paymentRecieved->getPaymentTypeid() == PAYMENT_TYPE_CASH) {
            $paymentType = $this->localization->paymentTypeCash;
        } elseif ($paymentRecieved->getPaymentTypeid() == PAYMENT_TYPE_BANK_TRANSFER) {
            $paymentType = $this->localization->paymentTypeBankTransfer;
        }

        $this->printItemColumnsFormatted($paymentType, '', sprintf('%0.2f', $total), true);
        $this->printItemColumnsFormatted($this->localization->datePaymentRecieved . ": " . date_format($paymentRecieved->getDate(), DATE_PHP_DATEFORMAT), '', '', false);
    }

    private function printCoupons(PaymentRecieved $paymentRecieved, &$total)
    {
        $this->printItemColumnsFormatted($this->localization->usedCoupons, '', '', false);

        foreach ($paymentRecieved->getPaymentCoupons() as $paymentCoupon) {
            $total = bcadd($total, $paymentCoupon->getValueUsed(), 2);
            $this->printItemColumnsFormatted($this->localization->couponCode . ": " . $paymentCoupon->getCoupon()->getCode(), '', sprintf('%0.2f', $paymentCoupon->getValueUsed()), true);
        }
    }

    private function printHeader()
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);

        if ($this->logo) {
            $logo = EscposImage::load($this->logo[0]);

            if ($this->logo[1] == PRINTER_LOGO_DEFAULT) {
                $this->printer->graphics($logo);
            } elseif ($this->logo[1] == PRINTER_LOGO_BIT_IMAGE) {
                $this->printer->bitImage($logo);
            } elseif ($this->logo[1] == PRINTER_LOGO_BIT_IMAGE_COLUMN) {
                $this->printer->bitImageColumnFormat($logo);
            }
        }

        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        if($this->header) {
            $this->printer->text($this->header);
        }

        if ($this->contactInformation) {
            $this->printEventContact($this->contactInformation);
        }

        $this->printer->selectPrintMode();
        $this->printer->feed();
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
    }

    private function printCustomer()
    {
        $this->printer->feed();
        $this->printer->setEmphasis(true);
        $this->printer->text($this->localization->customerData . ":\n");
        $this->printer->setEmphasis(false);
        $this->printer->text($this->localization->customerid . ": " . $this->customerContactInformation->getEventContactid() . "\n");

        $this->printEventContact($this->customerContactInformation);
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
            $text .= $this->localization->tax . ": " . $eventContact->getTaxIdentificationNr() . "\n";
        }

        if ($eventContact->getTelephon()) {
            $text .= $this->localization->tel . ": " . $eventContact->getTelephon(). "\n";
        }

        if ($eventContact->getFax()) {
            $text .= $this->localization->fax . ": " . $eventContact->getFax(). "\n";
        }

        if ($eventContact->getEmail()) {
            $text .= $this->localization->email . ": " . $eventContact->getEmail();
        }

        $this->printer->text(trim($text));
    }

    private function printItemsListFormatted($name, $amount) {

        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        $leftCols = $this->paperRowLength / 2;

        $text = $amount . $this->localization->multiplier . " " . $name;

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

        $this->printer->text(join("\n", $final) . "\n");
        $this->printer->setEmphasis(false);
        $this->printer->selectPrintMode();
    }

    private function printItemColumnsFormatted($name, $amount = 0, $price = 0, $currencySign = false, $bold = false)
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

        $sign = ($currencySign ? $this->localization->currency . ' ' : '');

        if ($amount) {
            $right = mb_str_pad($sign . sprintf('%0.2f', $price * $amount), $rightCols, ' ', STR_PAD_LEFT);
        } else {
            $right = mb_str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
        }

        $final[0] .= $right;
        $finalJoined = join("\n", $final);

        if ($amount) {
            $finalJoined .= "\n" . mb_str_pad(' ', $leftPadding) . $amount . ' ' . $this->localization->multiplier . ' ' . $this->localization->currency . ' ' . sprintf('%0.2f', $price);
        }

        $finalJoined .= "\n";

        //-- special EURO sign handling needed as this sign is in ESC/POS standard in an special caracter table
        $finalParts = explode('€', $finalJoined);

        for ($i = 0; $i < count($finalParts); $i++) {
            if ($i > 0) {
                $this->printer->getPrintConnector()->write(PRINTER_CHARACTER_EURO);
            }

            $this->printer->text($finalParts[$i]);
        }
    }

    private function printHeaderInfos()
    {
        foreach($this->headerInfos as $info) {
            list($title, $value, $bigFont) = $info;

            $this->printer->text("$title: ");

            if ($bigFont) {
                $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
            }

            $this->printer->text("$value\n");

            if ($bigFont) {
                $this->printer->selectPrintMode();
            }
        }
    }

    private function printDetailsTitle()
    {
        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer->setEmphasis(true);
        $this->printer->text($this->detailsTitle . "\n");
        $this->printer->selectPrintMode();
        $this->printer->setEmphasis(false);
    }

    public function printDocument()
    {
        /* Print top logo and header */
        $this->printHeader();

        /* Print customer info */
        if ($this->customerContactInformation) {
            $this->printCustomer();
            $this->printer->feed();
        }
        $this->printer->feed();

        /* Title of receipt */
        $this->printer->setEmphasis(true);
        $this->printHeaderInfos();
        $this->printer->feed();
        $this->printer->setEmphasis(false);

        /* Details Header */
        if ($this->detailsHeader) {
            $this->printer->setEmphasis(true);
            $this->printItemColumnsFormatted(detailsHeader[0], detailsHeader[1], detailsHeader[2]);
            $this->printer->setEmphasis(false);
        }

        /* Details Title */
        if ($this->detailsTitle) {
            $this->printDetailsTitle();
        }

        /* Details */
        foreach ($this->details as $detail) {
            list($name, $amount, $price, $currencySign, $bold) = $detail;

            if ($this->formatDetailAsList) {
                $this->printItemsListFormatted($name, $amount);
                continue;
            }

            $this->printItemColumnsFormatted($name, $amount, $price, $currencySign, $bold);
        }

        /* Sum Position 1 */
        if ($this->sumPos1) {
            list($name, $value) = $this->sumPos1;

            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->setEmphasis(true);

            $this->printItemColumnsFormatted($name, '', sprintf('%0.2f', $value), true, true);
            $this->printer->setEmphasis(false);
            $this->printer->selectPrintMode();
            $this->printer->feed();
        }

        /* Tax and total */
        if ($this->taxes) {
            $this->printer->text($this->localization->totalSumContainsTax . "\n");

            foreach ($this->taxes as $tax => $price) {
                $this->printItemColumnsFormatted($tax . $this->localization->percentTaxOfCurrency . sprintf('%0.2f', $price), '', sprintf('%0.2f', $price * ($tax / 100)), true);
            }
        }

        /* Payment informations */
        if ($this->payments) {
            $this->printer->feed();
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->text($this->localization->payments);
            $this->printer->selectPrintMode();
            $this->printer->feed();

            foreach ($this->payments as $paymentRecieved) {
                $total = 0;

                // Add Coupons if used
                if (count($paymentRecieved->getPaymentCoupons()) > 0) {
                    $this->printCoupons($paymentRecieved, $total);
                }

                $notPayedByCoupons = bcsub($paymentRecieved->getAmount(), $total, 2);

                $this->printPaymentRecievedType($paymentRecieved, $notPayedByCoupons);
            }
        }

         /* Sum Position 2 */
        if ($this->sumPos2) {
            list($name, $value) = $this->sumPos2;
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->setEmphasis(true);
            $this->printItemColumnsFormatted($name, '', sprintf('%0.2f', $value), true, true);
            $this->printer->setEmphasis(false);
            $this->printer->selectPrintMode();
            $this->printer->feed();
        }

        // add a maturity date if given
        if ($this->maturityDate) {
            $this->printer->feed();
            $this->printer->text($this->localization->maturityDate . ": " . date_format($this->maturityDate, DATE_PHP_DATEFORMAT) . "\n");
        }

        // add bank information if given
        if ($this->eventBankinformation) {
            $this->printBankInformation();
        }

        /* Footer */
        if ($this->footerInfos) {
            $this->printer->feed(2);
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);

            foreach($this->footerInfos as $info) {
                $this->printer->text($info . "\n");
            }
            $this->printer->feed(2);
        }

        /* Cut the receipt and open the cash drawer */
        $this->printer->cut();
        $this->printer->pulse();
    }

    public function close()
    {
        if ($this->connectorOpen) {
            $this->printer->feed();

            $this->printer->close();
            $this->connectorOpen = false;
        }
    }

    public function addDetail(string $name, int $amount = null, float $price = null, bool $currencySign = false, bool $bold = false)
    {
        $this->details[] = [$name, $amount, $price, $currencySign, $bold];
    }

    public function addFooterInfo(string $info)
    {
        $this->footerInfos[] = $info;
    }

    public function addHeaderInfo(string $title, string $value, bool $bigFont = false)
    {
        $this->headerInfos[] = [$title, $value, $bigFont];
    }

    public function addPayment(PaymentRecieved $paymentRecieved)
    {
        $this->payments[] = $paymentRecieved;
    }

    public function addSumPos1(string $name, float $value)
    {
        $this->sumPos1 = [$name, $value];
    }

    public function addSumPos2(string $name, float $value)
    {
        $this->sumPos2 = [$name, $value];
    }

    public function addTax(int $tax, float $price)
    {
        $this->taxes[$tax] = $price;
    }

    public function setBankinformation(EventBankinformation $eventBankinformation)
    {
        $this->eventBankinformation = $eventBankinformation;
    }

    public function setContactInformation(EventContact $eventContact)
    {
        $this->contactInformation = $eventContact;
    }

    public function setCustomerContactInformation(EventContact $eventContact)
    {
        $this->customerContactInformation = $eventContact;
    }

    public function setHeader(string $header)
    {
        $this->header = $header;
    }

    public function setLogo(string $logoFile, int $logoType)
    {
        $this->logo = [$logoFile, $logoType];
    }

    public function setDetailHeader(string $name, string $amount, string $price)
    {
        $this->detailsHeader = [$name, $amount, $price];
    }

    public function setDetailsTitle(string $name)
    {
        $this->detailsTitle = $name;
    }

    public function setFormatDetailAsList(bool $format)
    {
        $this->formatDetailAsList = $format;
    }

    public function setMaturityDate(DateTime $maturityDate)
    {
        $this->maturityDate = $maturityDate;
    }

}
