<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemQuery;
use API\Models\ORM\Invoice\InvoiceItemQuery as InvoiceItemQueryORM;
use API\Models\Query;

class InvoiceItemQuery extends Query implements IInvoiceItemQuery
{
    public function find(): IInvoiceItemCollection
    {
        $invoiceItems = InvoiceItemQueryORM::create()->find();

        $invoiceItemCollection = $this->container->get(IInvoiceItemCollection::class);
        $invoiceItemCollection->setCollection($invoiceItems);

        return $invoiceItemCollection;
    }

    public function findPk($id): IInvoiceItem
    {
        $invoiceItem = InvoiceItemQueryORM::create()->findPk($id);

        $invoiceItemModel = $this->container->get(IInvoiceItem::class);
        $invoiceItemModel->setModel($invoiceItem);

        return $invoiceItemModel;
    }
}
