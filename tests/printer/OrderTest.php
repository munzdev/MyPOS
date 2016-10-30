<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_invoice = new API\Lib\ReciepPrint($o_connector, 48);
$o_invoice->SetNr(584);
$o_invoice->SetTableNr("B32");
$o_invoice->SetName("Test Order");
$o_invoice->SetDate(date("d.m.Y H:i:s", strtotime("-10 Minutes")));

$o_invoice->Add("Colla 0.5L", "2");
$o_invoice->Add("Colla 0.5L mit Zitronne", "1");
$o_invoice->Add("Mineral 0.25L", "2");


$o_invoice->Add("Wiener Schnitzel", "1");
$o_invoice->Add("Schweinsbratten ohne Knödel", "3");
$o_invoice->Add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "10");
$o_invoice->Add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1");
$o_invoice->Add("Salat Klein", "1");

$o_invoice->PrintOrder();