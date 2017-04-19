<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningQuery;
use API\Models\ORM\Invoice\InvoiceWarningQuery as InvoiceWarningQueryORM;
use API\Models\Query;

class InvoiceWarningQuery extends Query implements IInvoiceWarningQuery
{
    public function find(): IInvoiceWarningCollection
    {
        $invoiceWarnings = InvoiceWarningQueryORM::create()->find();

        $invoiceWarningCollection = $this->container->get(IInvoiceWarningCollection::class);
        $invoiceWarningCollection->setCollection($invoiceWarnings);

        return $invoiceWarningCollection;
    }

    public function findPk($id): ?IInvoiceWarning
    {
        $invoiceWarning = InvoiceWarningQueryORM::create()->findPk($id);

        if(!$invoiceWarning) {
            return null;
        }

        $invoiceWarningModel = $this->container->get(IInvoiceWarning::class);
        $invoiceWarningModel->setModel($invoiceWarning);

        return $invoiceWarningModel;
    }
}
