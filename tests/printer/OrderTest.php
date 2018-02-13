<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Order;
use API\Lib\PrintingInformation;
use API\Models\Event\EventPrinter;
use const API\PRINTER_TYPE_NETWORK;

$json = file_get_contents("../../src/public/js/i18n/de.json");
$localization = json_decode($json);

$container = new \API\Lib\Container();

require __DIR__ . '/../../src/API/serviceLocator.php';

$eventPrinter = new EventPrinter($container);
$eventPrinter->setType(PRINTER_TYPE_NETWORK);
$eventPrinter->setEventPrinterid(1);
$eventPrinter->setCharactersPerRow(48);
$eventPrinter->setAttr1("192.168.0.50");
$eventPrinter->setAttr2(9100);

$printingInformation = new PrintingInformation();
$printingInformation->setOrderNr(584);
$printingInformation->setTableNr("B32");
$printingInformation->setName("Test Order");
$printingInformation->setDate(new DateTime("-10 Minutes"));
$printingInformation->setDateFooter(new DateTime);

$printingInformation->addRow("Colla 0.5L", "2");
$printingInformation->addRow("Colla 0.5L mit Zitronne", "1");
$printingInformation->addRow("Mineral 0.25L", "2");
$printingInformation->addRow("Wiener Schnitzel", "1");
$printingInformation->addRow("Schweinsbratten ohne Knödel", "3");
$printingInformation->addRow("Schweinsbratten ohne Knödel, mit Wurstsemmel", "10");
$printingInformation->addRow("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1");
$printingInformation->addRow("Salat Klein", "1");

$printerConnector = new ThermalPrinter($eventPrinter, $localization->ReciepPrint);
$order = new Order($printingInformation, $printerConnector, $localization->ReciepPrint);
$order->printType();