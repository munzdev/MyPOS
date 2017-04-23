<?php

namespace API\Lib\Interfaces\Printer\PrintingType;

use API\Lib\Interfaces\IPrintingInformation;
use API\Lib\Interfaces\Printer\PrinterConnector\IPrinterConnector;

interface IPrintingType {
    function __construct(?IPrintingInformation $printingInformation, ?IPrinterConnector $printerConnector, \stdClass $localization);
    public function setPrintingInformation(IPrintingInformation $printingInformation);
    public function setPrinterConnector(IPrinterConnector $printerConnector);
    function printType();
}