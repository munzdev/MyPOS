<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeQuery;
use API\Models\ORM\Invoice\InvoiceTypeQuery;
use API\Models\Query;

/**
 * Skeleton subclass for performing query and update operations on the 'invoice_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InvoiceTypeQuery extends Query implements IInvoiceTypeQuery
{
    public function find(): IInvoiceTypeCollection
    {
        $invoiceTypes = InvoiceTypeQuery::create()->find();

        $invoiceTypeCollection = $this->container->get(IInvoiceTypeCollection::class);
        $invoiceTypeCollection->setCollection($invoiceTypes);

        return $invoiceTypeCollection;
    }

}
