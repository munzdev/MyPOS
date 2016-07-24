<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/api/constants.php';
require __DIR__ . '/../../src/api/lib/Invoice.php';
require __DIR__ . '/../../src/api/lib/Invoice/Item.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);

$o_invoice = new Lib\Invoice($o_connector);
$o_invoice->SetDate(date('l jS \of F Y h:i:s A'));
$o_invoice->SetHeader("HEADER TOP LINE\nSECOND LINE");
$o_invoice->SetInvoiceNr(587472);
$o_invoice->SetTableNr("B32");

$o_invoice->Add("Colla 0.5L", "2", "5.00", "20");
$o_invoice->Add("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$o_invoice->Add("Mineral 0.25L", "2", "5.20", "20");


$o_invoice->Add("Wiener Schnitzel Normal", "1", "5.00", "10");
$o_invoice->Add("Schweinsbratten Normale ohne Knödel", "3", "10.20", "10");
$o_invoice->Add("Schweinsbratten Normale ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$o_invoice->Add("Salat Klein", "1", "1.50", "10");

$o_invoice->PrintInvoice();