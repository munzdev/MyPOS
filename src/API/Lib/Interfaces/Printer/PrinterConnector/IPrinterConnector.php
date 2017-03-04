<?php

namespace API\Lib\Interfaces\Printer\PrinterConnector;

use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Event\EventPrinter;
use API\Models\Payment\PaymentRecieved;
use DateTime;

interface IPrinterConnector
{
    function __construct(EventPrinter $eventPrinter, stdClass $localization);
    function setLogo(string $logoFile, int $logoType);
    function setHeader(string $header);
    function setContactInformation(EventContact $contact);
    function setCustomerContactInformation(EventContact $customer);
    function addHeaderInfo(string $title, string $value, bool $bigFont = false);
    function setDetailHeader(string $name, string $amount, string $price);
    function setDetailsTitle(string $name);
    function setFormatDetailAsList(bool $format);
    function addDetail(string $name, int $amount = null, float $price = null, bool $currencySign = false, bool $bold = false);
    function addSumPos1(string $name, float $value);
    function addTax(int $tax, float $price);
    function addPayment(PaymentRecieved $paymentRecieved);
    function addSumPos2(string $name, float $value);
    function setBankinformation(EventBankinformation $eventBankinformation);
    function setMaturityDate(DateTime $maturityDate);
    function addFooterInfo(string $info);
    function printDocument();
    function close();
}