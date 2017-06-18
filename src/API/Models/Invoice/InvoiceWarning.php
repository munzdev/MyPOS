<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarning;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningType;
use API\Models\Model;
use API\Models\ORM\Invoice\InvoiceWarning as InvoiceWarningORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'invoice_warning' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InvoiceWarning extends Model implements IInvoiceWarning
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new InvoiceWarningORM());
    }

    public function getInvoice(): IInvoice
    {
        $invoice = $this->model->getInvoice();

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }

    public function getInvoiceWarningType(): IInvoiceWarningType
    {
        $invoiceWarningType = $this->model->getInvoiceWarningType();

        $invoiceWarningTypeModel = $this->container->get(IInvoiceWarningType::class);
        $invoiceWarningTypeModel->setModel($invoiceWarningType);

        return $invoiceWarningTypeModel;
    }

    public function getInvoiceWarningTypeid(): int
    {
        return $this->model->getInvoiceWarningTypeid();
    }

    public function getInvoiceWarningid(): int
    {
        return $this->model->getInvoiceWarningid();
    }

    public function getInvoiceid(): int
    {
        return $this->model->getInvoiceid();
    }

    public function getMaturityDate(): DateTime
    {
        return $this->model->getMaturityDate();
    }

    public function getWarningDate(): DateTime
    {
        return $this->model->getWarningDate();
    }

    public function getWarningValue(): float
    {
        return $this->model->getWarningValue();
    }

    public function setInvoice($invoice): IInvoiceWarning
    {
        $this->model->setInvoice($invoice->getModel());
        return $this;
    }

    public function setInvoiceWarningType($invoiceWarningType): IInvoiceWarning
    {
        $this->model->setInvoiceWarningType($invoiceWarningType->getModel());
        return $this;
    }

    public function setInvoiceWarningTypeid($invoiceWarningTypeid): IInvoiceWarning
    {
        $this->model->setInvoiceWarningTypeid($invoiceWarningTypeid);
        return $this;
    }

    public function setInvoiceWarningid($invoiceWarningid): IInvoiceWarning
    {
        $this->model->setInvoiceWarningid($invoiceWarningid);
        return $this;
    }

    public function setInvoiceid($invoiceid): IInvoiceWarning
    {
        $this->model->setInvoiceid($invoiceid);
        return $this;
    }

    public function setMaturityDate($maturityDate): IInvoiceWarning
    {
        $this->model->setMaturityDate($maturityDate);
        return $this;
    }

    public function setWarningDate($warningDate): IInvoiceWarning
    {
        $this->model->setWarningDate($warningDate);
        return $this;
    }

    public function setWarningValue($warningValue): IInvoiceWarning
    {
        $this->model->setWarningValue($warningValue);
        return $this;
    }
}
