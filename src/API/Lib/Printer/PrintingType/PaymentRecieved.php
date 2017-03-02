<?php

namespace API\Lib\Printer\PrintingType;

class PaymentRecieved extends AbstractPrintingType
{
    public function printType()
    {
        $paymentRecieved = $this->printingInformation->getPaymentRecieved()[0];
        $user = $paymentRecieved->getUser();

        /* Print top logo and header */
        if($this->printingInformation->getLogoFile()) {
            $this->printerConnector->setLogo($this->printingInformation->getLogoFile(),
                                             $this->printingInformation->getLogoType());
        }
        $this->printerConnector->setHeader($this->printingInformation->getHeader());
        $this->printerConnector->setContactInformation($this->printingInformation->getContact());

        // Add customer data if set
        if ($this->printingInformation->getCustomer()) {
            $this->printerConnector->setCustomerContactInformation($this->printingInformation->getCustomer());
        }

        /* Title of receipt */
        $this->printerConnector->addHeaderInfo($this->localization->receiptNr, $paymentRecieved->getPaymentRecievedid());
        $this->printerConnector->addHeaderInfo($this->localization->invoiceNr, $this->invoiceid);
        $this->printerConnector->addHeaderInfo($this->localization->cashier, $user->getFirstname() . " " . $user->getLastname());

        // Add Coupons if used
        $this->printerConnector->addPayment($paymentRecieved);

        // add bank information if given
        if ($this->printingInformation->getBankinformation()) {
            $this->printerConnector->setBankinformation($this->printingInformation->getBankinformation());
        }

        $this->printerConnector->printDocument();
        $this->printerConnector->close();
    }
}