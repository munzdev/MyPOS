<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;

$str_json = file_get_contents("../../src/public/js/i18n/de.json");
$o_i18n = json_decode($str_json);

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_reciep = new Printer($o_connector, 48, $o_i18n->ReciepPrint);
$o_reciep->setLogo("resources/escpos-php.png", PRINTER_LOGO_BIT_IMAGE_COLUMN);
$o_reciep->setHeader("HEADER TOP LINE\nSECOND LINE\n THIRD LINE");
$o_reciep->setInvoiceid(587472);
$o_reciep->setTableNr("B32");
$o_reciep->setName("Test Cashier");

$o_reciep->add("Colla 0.5L", "2", "5.00", "20");
$o_reciep->add("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$o_reciep->add("Mineral 0.25L", "2", "5.20", "20");


$o_reciep->add("Wiener Schnitzel", "1", "5.00", "10");
$o_reciep->add("Schweinsbratten ohne Knödel", "3", "10.20", "10");
$o_reciep->add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$o_reciep->add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1", "5.00", "10");
$o_reciep->add("Salat Klein", "1", "1.50", "10");

$o_reciep->printInvoice();
