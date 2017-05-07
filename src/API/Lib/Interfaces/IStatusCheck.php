<?php
namespace API\Lib\Interfaces;


/**
 * Checks status of different DB Table fields that depend on sub-tables.
 */
interface IStatusCheck
{
    /**
     * Checks if distribution and invoice of an order are finished and sets them in the table row.
     *
     * @param int $orderid
     */
    public function verifyOrder(int $orderid): void;

    /**
     * Checks if distribution and invoice of an order_details is finished and sets them in the table row.
     *
     * @param int $orderDetailid
     */
    public function verifyOrderDetail(int $orderDetailid): void;

    /**
     * Checks if an invoice is payed and marke the invoice as payed
     *
     * @param int $invoiceid
     */
    public function verifyInvoice(int $invoiceid): void;

    /**
     * Checks if an order in progress is finished and sets the done time
     *
     * @param int $orderInProgressid
     */
    public function verifyOrderInProgress(int $orderInProgressid): void;

    /**
     * Checks for menu availability of given order_detailid and sets correct status in order_detail
     *
     * @param int $orderDetailid
     */
    public function verifyAvailability(int $orderDetailid): void;
}