<?php

namespace API\Lib\Interfaces\Models\Invoice;

use API\Lib\Interfaces\Models\IQuery;

interface IInvoiceQuery extends IQuery {

    public function getInvoiceCountBySearch(
        $eventid,
        $status,
        $invoiceid,
        $customerid,
        $canceled,
        $typeid,
        $userid,
        $dateFrom,
        $dateTo
    ): int;

    public function findWithPagingAndSearch(
        $offset,
        $limit,
        $eventid,
        $status,
        $invoiceid,
        $customerid,
        $canceled,
        $typeid,
        $userid,
        $dateFrom,
        $dateTo
    ): IInvoiceCollection;

    public function getWithDetails(int $eventid, int $invoiceid) : ?IInvoice;
    public function getWithItems(int $invoiceid) : ?IInvoice;
}