<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/api/constants.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);

$o_printer = new Printer($o_connector);

$o_printer -> getPrintConnector() -> write(MyPOS\PRINTER_CARACTER_EURO);
$o_printer -> text(" EUR Zeichen\n");
$o_printer -> cut();
$o_printer -> close();