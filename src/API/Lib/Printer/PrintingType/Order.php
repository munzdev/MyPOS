<?php

namespace API\Lib\Printer\PrintingType;

use const API\DATE_PHP_TIMEFORMAT;

class Order extends AbstractPrintingType
{
    public function printType()
    {
        /* Print top logo and header */
        if($this->printingInformation->getLogoFile()) {
            $this->printerConnector->setLogo($this->printingInformation->getLogoFile(),
                                             $this->printingInformation->getLogoType());
        }

        /* Title of receipt */
        $this->printerConnector->addHeaderInfo($this->localization->orderNr, $this->printingInformation->getOrderNr());
        $this->printerConnector->addHeaderInfo($this->localization->tableNr, $this->printingInformation->getTableNr(), true);
        $this->printerConnector->addHeaderInfo($this->localization->recordedBy, $this->printingInformation->getName());
        $this->printerConnector->addHeaderInfo($this->localization->recordedBy, date_format($this->printingInformation->getDate(), DATE_PHP_TIMEFORMAT));

        /* Items Title */
        $this->printerConnector->setDetailsTitle($this->localization->title);

        /* Items */
        $this->printerConnector->setFormatDetailAsList(true);
        foreach ($this->printingInformation->getRows() as $entries) {
            foreach ($entries as $entrie) {
                $this->printerConnector->addDetail($entrie['name'], $entrie['amount']);
            }
        }

        /* Footer */
        $this->printerConnector->addFooterInfo($this->localization->distributionTime . ": " . date_format($this->printingInformation->getDateFooter(), DATE_PHP_TIMEFORMAT));

        $this->printerConnector->printDocument();
        $this->printerConnector->close();
    }

}