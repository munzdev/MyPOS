<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/api/constants.php';
require __DIR__ . '/../../src/api/functions.php';
require __DIR__ . '/../../src/api/lib/Invoice.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
//$o_connector = new FilePrintConnector("php://stdout");

$o_invoice = new Lib\Invoice($o_connector, 36);
$o_invoice->SetLogo("resources/escpos-php.png", MyPOS\PRINTER_LOGO_BIT_IMAGE_COLUMN);
$o_invoice->SetHeader("HEADER TOP LINE\nSECOND LINE\n THIRD LINE");
$o_invoice->SetNr(587472);
$o_invoice->SetTableNr("B32");
$o_invoice->SetName("Test Cashier");

$o_invoice->Add("Colla 0.5L", "2", "5.00", "20");
$o_invoice->Add("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$o_invoice->Add("Mineral 0.25L", "2", "5.20", "20");


$o_invoice->Add("Wiener Schnitzel", "1", "5.00", "10");
$o_invoice->Add("Schweinsbratten ohne Knödel", "3", "10.20", "10");
$o_invoice->Add("Schweinsbratten ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$o_invoice->Add("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1", "5.00", "10");
$o_invoice->Add("Salat Klein", "1", "1.50", "10");

$o_invoice->PrintInvoice();