<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\ReciepPrint;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$str_json = file_get_contents("../../src/public/js/i18n/de.json");
$o_i18n = json_decode($str_json);

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_invoice = new ReciepPrint($o_connector, 48, $o_i18n->ReciepPrint);
$o_invoice->SetNr(584);
$o_invoice->SetTableNr("B32");
$o_invoice->SetName("Test Order");
$o_invoice->SetDate(new DateTime("-10 Minutes"));

$o_invoice->Add("Colla 0.5L", "2");
$o_invoice->Add("Colla 0.5L mit Zitronne", "1");
$o_invoice->Add("Mineral 0.25L", "2");


$o_invoice->Add("Wiener Schnitzel", "1");
$o_invoice->Add("Schweinsbratten ohne Knödel", "3");
$o_invoice->Add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "10");
$o_invoice->Add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1");
$o_invoice->Add("Salat Klein", "1");

$o_invoice->PrintOrder();