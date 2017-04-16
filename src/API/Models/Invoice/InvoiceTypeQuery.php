<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoiceType;
use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeQuery;
use API\Models\ORM\Invoice\InvoiceTypeQuery as InvoiceTypeQueryORM;
use API\Models\Query;

class InvoiceTypeQuery extends Query implements IInvoiceTypeQuery
{
    public function find(): IInvoiceTypeCollection
    {
        $invoiceTypes = InvoiceTypeQueryORM::create()->find();

        $invoiceTypeCollection = $this->container->get(IInvoiceTypeCollection::class);
        $invoiceTypeCollection->setCollection($invoiceTypes);

        return $invoiceTypeCollection;
    }

    public function findPk($id): IInvoiceType
    {
        $invoiceType = InvoiceTypeQueryORM::create()->findPk($id);

        $invoiceTypeModel = $this->container->get(IInvoiceType::class);
        $invoiceTypeModel->setModel($invoiceType);

        return $invoiceTypeModel;
    }
}
