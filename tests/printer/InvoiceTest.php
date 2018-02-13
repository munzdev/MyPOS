<?php
/* Call this file 'hello-world.php' */
require __DIR__ . '/../../src/vendor/autoload.php';
require __DIR__ . '/../../src/API/constants.php';
require __DIR__ . '/../../src/API/functions.php';

use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Invoice;
use API\Lib\PrintingInformation;
use API\Models\Event\EventPrinter;
use const API\PRINTER_LOGO_BIT_IMAGE_COLUMN;
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

$contact = new \API\Models\Event\EventContact($container);
$contact->setContactPerson("");
$contact->setAddress2("");
$contact->setTaxIdentificationNr("");
$contact->setFax("");
$contact->setEmail("");
$contact->setTelephon("");
$contact->setTitle("Mr");
$contact->setName("Test Name");
$contact->setAddress("Street 1");
$contact->setZip(1234);
$contact->setCity("City");

$printingInformation = new PrintingInformation();
$printingInformation->setLogoFile("resources/escpos-php.png");
$printingInformation->setLogoType(PRINTER_LOGO_BIT_IMAGE_COLUMN);
$printingInformation->setHeader("HEADER TOP LINE\nSECOND LINE\n THIRD LINE");
$printingInformation->setInvoiceid(587472);
$printingInformation->setTableNr("B32");
$printingInformation->setName("Test Cashier");
$printingInformation->setContact($contact);

$printingInformation->addRow("Colla 0.5L", "2", "5.00", "20");
$printingInformation->addRow("Colla 0.5L mit Zitronne", "1", "5.20", "20");
$printingInformation->addRow("Mineral 0.25L", "2", "5.20", "20");
$printingInformation->addRow("Wiener Schnitzel", "1", "5.00", "10");
$printingInformation->addRow("Schweinsbratten ohne Knödel", "3", "10.20", "10");
$printingInformation->addRow("Schweinsbratten ohne Knödel, mit Wurstsemmel", "3", "10.20", "10");
$printingInformation->addRow("Wiener Schnitzel klein ohne Pommes, mit extra Ketchup, bla blalsaf bsadfsdafsda fsdasd ds", "1", "5.00", "10");
$printingInformation->addRow("Salat Klein", "1", "1.50", "10");

$printerConnector = new ThermalPrinter($eventPrinter, $localization->ReciepPrint);
$invoice = new Invoice($printingInformation, $printerConnector, $localization->ReciepPrint);
$invoice->printType();