<?php

namespace API\Lib\Interfaces\Printer\PrintingType;

interface IPrintingType {
    function __construct(IPrintingInformation $printingInformation, IPrinterConnector $printerConnector, stdClass $localization);
    function printType();
}