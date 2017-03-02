<?php

namespace API\Lib\Printer\PrintingType;

use API\Lib\Interfaces\IPrintingInformation;
use API\Lib\Interfaces\Printer\PrinterConnector\IPrinterConnector;
use API\Lib\Interfaces\Printer\PrintingType\IPrintingType;
use stdClass;

abstract class AbstractPrintingType implements IPrintingType
{
    /**
     *
     * @var IPrintingInformation
     */
    protected $printingInformation;

    /**
     *
     * @var IPrinterConnector
     */
    protected $printerConnector;

    /**
     *
     * @var \stdClass
     */
    protected $localization;

    public function __construct(IPrintingInformation $printingInformation, IPrinterConnector $printerConnector, stdClass $localization)
    {
        $this->printingInformation = $printingInformation;
        $this->printerConnector = $printerConnector;
        $this->localization = $localization;
    }

    abstract function printType();
}