<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

$o_connector = new NetworkPrintConnector("192.168.0.50", 9100);
$o_printer = new Printer($o_connector);

try
{
    $o_printer->text("Hello World!\n This is a Test!\n\n");
    $o_printer->cut();
}
finally
{
    $o_printer->close();
}