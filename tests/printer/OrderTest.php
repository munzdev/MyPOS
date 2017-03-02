<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$str_json = file_get_contents("../../src/public/js/i18n/de.json");
$o_i18n = json_decode($str_json);

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_reciep = new Printer($o_connector, 48, $o_i18n->ReciepPrint);
$o_reciep->setOrderNr(584);
$o_reciep->setTableNr("B32");
$o_reciep->setName("Test Order");
$o_reciep->setDate(new DateTime("-10 Minutes"));
$o_reciep->setDateFooter(new DateTime);

$o_reciep->add("Colla 0.5L", "2");
$o_reciep->add("Colla 0.5L mit Zitronne", "1");
$o_reciep->add("Mineral 0.25L", "2");


$o_reciep->add("Wiener Schnitzel", "1");
$o_reciep->add("Schweinsbratten ohne Knödel", "3");
$o_reciep->add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "10");
$o_reciep->add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1");
$o_reciep->add("Salat Klein", "1");

$o_reciep->printOrder();
