<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Models\Invoice\Base\Invoice as BaseInvoice;

/**
 * Skeleton subclass for representing a row from the 'invoice' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Invoice extends BaseInvoice implements IInvoice
{
    public function getCanceledInvoice(): IInvoice
    {
        if ($this->getCanceledInvoiceid()) {

            return InvoiceQuery::create()->findPk($this->getCanceledInvoiceid());
        }
    }

    public function getCustomerEventContact(): IEventContact
    {
        return $this->getEventContactRelatedByCustomerEventContactid();
    }

    public function getEventContact(): IEventContact
    {
        return $this->getEventContactRelatedByEventContactid();
    }

    public function setCanceledInvoice($invoice): IInvoice
    {
        $this->setCanceledInvoiceid($invoice->getInvoiceid());
        return $this;
    }

    public function setCustomerEventContact($eventContact): IInvoice
    {
        $this->setEventContactRelatedByCustomerEventContactid($eventContact);
    }

    public function setEventContact($eventContact): IInvoice
    {
        $this->setEventContactRelatedByEventContactid($eventContact);
    }

}
