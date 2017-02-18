<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\ReciepPrint;
use API\Models\Event\EventBankinformation;
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

$o_event_bankinformation = new EventBankinformation();
$o_event_bankinformation->setName("Test Bank Int.");
$o_event_bankinformation->setIban("AT32123456");
$o_event_bankinformation->setBic("ATOO12354");

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_reciep = new ReciepPrint($o_connector, 48, $o_i18n->ReciepPrint);
$o_reciep->setLogo("resources/escpos-php.png", PRINTER_LOGO_BIT_IMAGE_COLUMN);
$o_reciep->setHeader("Company bon printed\nStreet whatever 1\nCity 1234\nTAX:1234567");
$o_reciep->setInvoiceid(587472);

$o_reciep->addPaymentRecieved($o_payment_recieved);
$o_reciep->setBankinformation($o_event_bankinformation);

$o_reciep->printPaymentRecieved();
