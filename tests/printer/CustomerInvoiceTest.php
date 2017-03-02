<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Payment\Coupon;
use API\Models\Payment\PaymentCoupon;
use API\Models\Payment\PaymentRecieved;
use API\Models\User\User;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PAYMENT_TYPE_CASH;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;

$str_json = file_get_contents("../../src/public/js/i18n/de.json");
$o_i18n = json_decode($str_json);

$o_event_contact = new EventContact();
$o_event_contact->setEventContactid(1);
$o_event_contact->setAddress("Test street 1");
$o_event_contact->setCity("City");
$o_event_contact->setName("Asdf all GmbH");
$o_event_contact->setTaxIdentificationNr("21234231");
$o_event_contact->setTitle("Firma");
$o_event_contact->setZip(1234);
$o_event_contact->setTelephon("12354");
$o_event_contact->setFax("FAX123");
$o_event_contact->setEmail("asdf@sadf.de");

$o_coupon = new Coupon();
$o_coupon->setCode("1234");
$o_coupon->setValue(20);

$o_payment_coupon = new PaymentCoupon();
$o_payment_coupon->setValueUsed(5);
$o_payment_coupon->setCoupon($o_coupon);

$o_user = new User();
$o_user->setFirstname("Test");
$o_user->setLastname("Cashier");

$o_payment_recieved = new PaymentRecieved();
$o_payment_recieved->setPaymentRecievedid(5232);
$o_payment_recieved->setAmount(12.4);
$o_payment_recieved->setDate(new DateTime);
$o_payment_recieved->setPaymentTypeid(PAYMENT_TYPE_BANK_TRANSFER);
$o_payment_recieved->setUser($o_user);
$o_payment_recieved->addPaymentCoupon($o_payment_coupon);

$o_payment_recieved2 = new PaymentRecieved();
$o_payment_recieved2->setPaymentRecievedid(5431);
$o_payment_recieved2->setAmount(80.9);
$o_payment_recieved2->setDate(new DateTime("+1 Day"));
$o_payment_recieved2->setPaymentTypeid(PAYMENT_TYPE_CASH);
$o_payment_recieved2->setUser($o_user);

$o_event_bankinformation = new EventBankinformation();
$o_event_bankinformation->setName("Test Bank Int.");
$o_event_bankinformation->setIban("AT32123456");
$o_event_bankinformation->setBic("ATOO12354");

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_reciep = new Printer($o_connector, 48, $o_i18n->ReciepPrint);
$o_reciep->setLogo("resources/escpos-php.png", PRINTER_LOGO_BIT_IMAGE_COLUMN);
$o_reciep->setHeader("Company bon printed\nStreet whatever 1\nCity 1234\nTAX:1234567");
$o_reciep->setCustomer($o_event_contact);
$o_reciep->setPaymentid(5323);
$o_reciep->setInvoiceid(587472);
$o_reciep->setTableNr("B32");
$o_reciep->setName("Test Cashier");

$o_reciep->addPaymentRecieved($o_payment_recieved);
$o_reciep->addPaymentRecieved($o_payment_recieved2);
$o_reciep->setBankinformation($o_event_bankinformation);
$o_reciep->setMaturityDate(new DateTime("+2 Weeks"));

$o_reciep->add("Colla 0.5L", "2", "5.00", "20");
$o_reciep->add("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$o_reciep->add("Mineral 0.25L", "2", "5.20", "20");


$o_reciep->add("Wiener Schnitzel", "1", "5.00", "10");
$o_reciep->add("Schweinsbratten ohne Knödel", "3", "10.20", "10");
$o_reciep->add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$o_reciep->add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1", "5.00", "10");
$o_reciep->add("Salat Klein", "1", "1.50", "10");

$o_reciep->printInvoice();
