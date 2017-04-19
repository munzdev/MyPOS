<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Models\ORM\Invoice\InvoiceQuery as InvoiceQueryORM;
use API\Models\Query;

class InvoiceQuery extends Query implements IInvoiceQuery
{
    public function find(): IInvoiceCollection
    {
        $invoices = InvoiceQueryORM::create()->find();

        $invoiceCollection = $this->container->get(IInvoiceCollection::class);
        $invoiceCollection->setCollection($invoices);

        return $invoiceCollection;
    }

    public function findPk($id): ?IInvoice
    {
        $invoice = InvoiceQueryORM::create()->findPk($id);

        if(!$invoice) {
            return null;
        }

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }
}
