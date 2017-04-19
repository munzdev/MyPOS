<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningType;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningTypeCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningTypeQuery;
use API\Models\ORM\Invoice\InvoiceWarningTypeQuery as InvoiceWarningTypeQueryORM;
use API\Models\Query;

class InvoiceWarningTypeQuery extends Query implements IInvoiceWarningTypeQuery
{
    public function find(): IInvoiceWarningTypeCollection
    {
        $invoiceWarningTypes = InvoiceWarningTypeQueryORM::create()->find();

        $invoiceWarningTypeCollection = $this->container->get(IInvoiceWarningTypeCollection::class);
        $invoiceWarningTypeCollection->setCollection($invoiceWarningTypes);

        return $invoiceWarningTypeCollection;
    }

    public function findPk($id): ?IInvoiceWarningType
    {
        $invoiceWarningType = InvoiceWarningTypeQueryORM::create()->findPk($id);

        if(!$invoiceWarningType) {
            return null;
        }

        $invoiceWarningTypeModel = $this->container->get(IInvoiceWarningType::class);
        $invoiceWarningTypeModel->setModel($invoiceWarningType);

        return $invoiceWarningTypeModel;
    }
}
