<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Invoice;
use API\Lib\PrintingInformation;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Event\EventPrinter;
use API\Models\Payment\Coupon;
use API\Models\Payment\PaymentCoupon;
use API\Models\Payment\PaymentRecieved;
use API\Models\User\User;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PAYMENT_TYPE_CASH;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;
use const API\PRINTER_TYPE_NETWORK;

$json = file_get_contents("../../src/public/js/i18n/de.json");
$localization = json_decode($json);

$container = new \API\Lib\Container();

require __DIR__ . '/../../src/API/serviceLocator.php';

$eventPrinter = new EventPrinter($container);
$eventPrinter->setType(PRINTER_TYPE_NETWORK);
$eventPrinter->setEventPrinterid(1);
$eventPrinter->setCharactersPerRow(48);
$eventPrinter->setAttr1("192.168.0.50");
$eventPrinter->setAttr2(9100);

$eventContact = new EventContact($container);
$eventContact->setEventContactid(1);
$eventContact->setAddress("Test street 1");
$eventContact->setCity("City");
$eventContact->setName("Asdf all GmbH");
$eventContact->setTaxIdentificationNr("21234231");
$eventContact->setTitle("Firma");
$eventContact->setZip(1234);
$eventContact->setTelephon("12354");
$eventContact->setFax("FAX123");
$eventContact->setEmail("asdf@sadf.de");

$coupon = new Coupon($container);
$coupon->setCode("1234");
$coupon->setValue(20);

$paymentCoupon = new PaymentCoupon($container);
$paymentCoupon->setValueUsed(5);
$paymentCoupon->setCoupon($coupon);

$user = new User($container);
$user->setFirstname("Test");
$user->setLastname("Cashier");

$paymentRecieved = new PaymentRecieved($container);
$paymentRecieved->setPaymentRecievedid(5232);
$paymentRecieved->setAmount(12.4);
$paymentRecieved->setDate(new DateTime);
$paymentRecieved->setPaymentTypeid(PAYMENT_TYPE_BANK_TRANSFER);
$paymentRecieved->setUser($user);
$paymentRecieved->addPaymentCoupon($paymentCoupon);

$paymentRecieved2 = new PaymentRecieved($container);
$paymentRecieved2->setPaymentRecievedid(5431);
$paymentRecieved2->setAmount(80.9);
$paymentRecieved2->setDate(new DateTime("+1 Day"));
$paymentRecieved2->setPaymentTypeid(PAYMENT_TYPE_CASH);
$paymentRecieved2->setUser($user);

$eventBankinformation = new EventBankinformation($container);
$eventBankinformation->setName("Test Bank Int.");
$eventBankinformation->setIban("AT32123456");
$eventBankinformation->setBic("ATOO12354");

$printingInformation = new PrintingInformation();
$printingInformation->setLogoFile("resources/escpos-php.png");
$printingInformation->setLogoType(PRINTER_LOGO_BIT_IMAGE_COLUMN);
$printingInformation->setHeader("Company bon printed\nStreet whatever 1\nCity 1234\nTAX:1234567");
$printingInformation->setContact($eventContact);
$printingInformation->setCustomer($eventContact);
$printingInformation->setPaymentid(5323);
$printingInformation->setInvoiceid(587472);
$printingInformation->setTableNr("B32");
$printingInformation->setName("Test Cashier");
$printingInformation->addPaymentRecieved($paymentRecieved);
$printingInformation->addPaymentRecieved($paymentRecieved2);
$printingInformation->setBankinformation($eventBankinformation);
$printingInformation->setMaturityDate(new DateTime("+2 Weeks"));

$printingInformation->addRow("Colla 0.5L", "2", "5.00", "20");
$printingInformation->addRow("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$printingInformation->addRow("Mineral 0.25L", "2", "5.20", "20");
$printingInformation->addRow("Wiener Schnitzel", "1", "5.00", "10");
$printingInformation->addRow("Schweinsbratten ohne Knödel", "3", "10.20", "10");
$printingInformation->addRow("Schweinsbratten ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$printingInformation->addRow("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1", "5.00", "10");
$printingInformation->addRow("Salat Klein", "1", "1.50", "10");

$printerConnector = new ThermalPrinter($eventPrinter, $localization->ReciepPrint);
$invoice = new Invoice($printingInformation, $printerConnector, $localization->ReciepPrint);
$invoice->printType();