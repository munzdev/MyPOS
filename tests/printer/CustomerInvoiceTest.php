<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\ReciepPrint;
use API\Models\Invoice\Customer;
use API\Models\Payment\Coupon;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;

$str_json = file_get_contents("../../src/public/js/i18n/de.json");
$o_i18n = json_decode($str_json);

$o_customer = new Customer();
$o_customer->setCustomerid(1);
$o_customer->setAddress("Test street 1");
$o_customer->setCity("City");
$o_customer->setName("Asdf all GmbH");
$o_customer->setTaxIdentificationNr("21234231");
$o_customer->setTitle("Firma");
$o_customer->setZip(1234);
$o_customer->setTelephon("12354");
$o_customer->setFax("FAX123");
$o_customer->setEmail("asdf@sadf.de");

$o_coupon = new Coupon();
$o_coupon->setCode("1234");
$o_coupon->setValue("12");

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_reciep = new ReciepPrint($o_connector, 48, $o_i18n->ReciepPrint);
$o_reciep->SetLogo("resources/escpos-php.png", PRINTER_LOGO_BIT_IMAGE_COLUMN);
$o_reciep->SetHeader("Company bon printed\nStreet whatever 1\nCity 1234\nTAX:1234567");
$o_reciep->SetCustomer($o_customer);
$o_reciep->AddCoupon($o_coupon);
$o_reciep->SetNr(587472);
$o_reciep->SetTableNr("B32");
$o_reciep->SetName("Test Cashier");

$o_reciep->SetPaymentType(PAYMENT_TYPE_BANK_TRANSFER);
$o_reciep->SetBankName("Test Bank Int.");
$o_reciep->SetIBAN("AT32123456");
$o_reciep->SetBIC("ATOO12354");
$o_reciep->SetMaturityDate(new DateTime("+2 Weeks"));

$o_reciep->Add("Colla 0.5L", "2", "5.00", "20");
$o_reciep->Add("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$o_reciep->Add("Mineral 0.25L", "2", "5.20", "20");


$o_reciep->Add("Wiener Schnitzel", "1", "5.00", "10");
$o_reciep->Add("Schweinsbratten ohne Knödel", "3", "10.20", "10");
$o_reciep->Add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$o_reciep->Add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1", "5.00", "10");
$o_reciep->Add("Salat Klein", "1", "1.50", "10");

$o_reciep->PrintInvoice();