<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoiceType;
use API\Models\Model;
use API\Models\ORM\Invoice\InvoiceType as InvoiceTypeORM;

/**
 * Skeleton subclass for representing a row from the 'invoice_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InvoiceType extends Model implements IInvoiceType
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new InvoiceTypeORM());
    }

    public function getInvoiceTypeid(): int
    {
        return $this->model->getInvoiceTypeid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function setInvoiceTypeid($invoiceTypeid): IInvoiceType
    {
        $this->model->setInvoiceTypeid($invoiceTypeid);
        return $this;
    }

    public function setName($name): IInvoiceType
    {
        $this->model->setName($name);
        return $this;
    }

}
