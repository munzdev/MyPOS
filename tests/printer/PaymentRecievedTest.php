<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer;
use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\PrintingInformation;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventPrinter;
use API\Models\Payment\Coupon;
use API\Models\Payment\PaymentCoupon;
use API\Models\Payment\PaymentRecieved;
use API\Models\User\User;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;

$json = file_get_contents("../../src/public/js/i18n/de.json");
$localization = json_decode($json);

$eventPrinter = new EventPrinter();
$eventPrinter->setEventPrinterid(1);
$eventPrinter->setCharactersPerRow(48);
$eventPrinter->setAttr1("192.168.0.50");
$eventPrinter->setAttr2(9100);

$coupon = new Coupon();
$coupon->setCode("1234");
$coupon->setValue(20);

$paymentCoupon = new PaymentCoupon();
$paymentCoupon->setValueUsed(5);
$paymentCoupon->setCoupon($coupon);

$user = new User();
$user->setFirstname("Test");
$user->setLastname("Cashier");

$paymentRecieved = new PaymentRecieved();
$paymentRecieved->setPaymentRecievedid(5232);
$paymentRecieved->setAmount(12.4);
$paymentRecieved->setDate(new DateTime);
$paymentRecieved->setPaymentTypeid(PAYMENT_TYPE_BANK_TRANSFER);
$paymentRecieved->setUser($user);
$paymentRecieved->addPaymentCoupon($paymentCoupon);

$eventBankinformation = new EventBankinformation();
$eventBankinformation->setName("Test Bank Int.");
$eventBankinformation->setIban("AT32123456");
$eventBankinformation->setBic("ATOO12354");

$printingInformation = new PrintingInformation();
$printingInformation->setLogoFile("resources/escpos-php.png");
$printingInformation->setLogoType(PRINTER_LOGO_BIT_IMAGE_COLUMN);
$printingInformation->setHeader("Company bon printed\nStreet whatever 1\nCity 1234\nTAX:1234567");
$printingInformation->setInvoiceid(587472);
$printingInformation->addPaymentRecieved($paymentRecieved);
$printingInformation->setBankinformation($eventBankinformation);

$printerConnector = new ThermalPrinter($eventPrinter, $localization->ReciepPrint);
$paymentRecievedType = new Printer\PrintingType\PaymentRecieved($printingInformation, $printerConnector, $localization->ReciepPrint);
$paymentRecievedType->printType();