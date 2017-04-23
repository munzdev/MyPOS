<?php

namespace API\Lib\Printer\PrinterConnector;

use API\Lib\Interfaces\Printer\PrinterConnector\IPrinterConnector;
use API\Models\Event\EventContact;
use API\Models\Event\EventPrinter;
use API\Models\Payment\PaymentRecieved;
use Exception;

class PdfPrinter implements IPrinterConnector
{
    private $eventPrinter;

    /**
     * @var stdClass
     */
    private $localization;

    public function __construct(?EventPrinter $eventPrinter, \stdClass $localization)
    {
        $this->localization = $localization;

        if($eventPrinter) {
            $this->setEventPrinter($eventPrinter);
        }

        throw new Exception("PDF is not implemented yet!");
    }

    public function addDetail(string $name, int $amount = null, float $price = null, bool $currencySign = false, bool $bold = false)
    {

    }

    public function addFooterInfo(string $info)
    {

    }

    public function addHeaderInfo(string $title, string $value, bool $bigFont = false)
    {

    }

    public function addPayment(PaymentRecieved $paymentRecieved)
    {

    }

    public function addSumPos1(string $name, float $value)
    {

    }

    public function addSumPos2(string $name, float $value)
    {

    }

    public function addTax(int $tax, float $price)
    {

    }

    public function close()
    {

    }

    public function printDocument()
    {

    }

    public function setBankinformation(\API\Models\Event\EventBankinformation $eventBankinformation, \DateTime $maturityDate = null)
    {

    }

    public function setContactInformation(EventContact $contact)
    {

    }

    public function setCustomerContactInformation(EventContact $customer)
    {

    }

    public function setDetailHeader(string $name, string $amount, string $price)
    {

    }

    public function setHeader(string $header)
    {

    }

    public function setLogo(string $logoFile, int $logoType)
    {

    }

    public function setDetailsTitle(string $name)
    {

    }

    public function setFormatDetailAsList(bool $format)
    {

    }

    public function setMaturityDate(\DateTime $maturityDate)
    {

    }

    function setEventPrinter(EventPrinter $eventPrinter)
    {

    }
}