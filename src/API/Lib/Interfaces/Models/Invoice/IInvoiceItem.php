<?php

namespace API\Lib\Interfaces\Models\Invoice;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;

interface IInvoiceItem extends IModel {
    /**
     * @return int
     */
    function getInvoiceItemid();

    /**
     * @return int
     */
    function getInvoiceid();

    /**
     * @return IInvoice
     */
    function getInvoice();

    /**
     * @return int
     */
    function getOrderDetailid();

    /**
     * @return IOrderDetail
     */
    function getOrderDetail();

    /**
     * @return int
     */
    function getAmount();

    /**
     * @return float
     */
    function getPrice();

    /**
     * @return string
     */
    function getDescription();

    /**
     * @return int
     */
    function getTax();

    /**
     *
     * @param int $invoiceItemid Description
     * @return IInvoiceItem Description
     */
    function setInvoiceItemid($invoiceItemid);

    /**
     *
     * @param int $invoiceid Description
     * @return IInvoiceItem Description
     */
    function setInvoiceid($invoiceid);

    /**
     *
     * @param IInvoice $invoice Description
     * @return IInvoiceItem Description
     */
    function setInvoice($invoice);

    /**
     *
     * @param int $orderDetailid Description
     * @return IInvoiceItem Description
     */
    function setOrderDetailid($orderDetailid);

    /**
     *
     * @param IOrderDetail $orderDetail Description
     * @return IInvoiceItem Description
     */
    function setOrderDetail($orderDetail);

    /**
     *
     * @param int $amount Description
     * @return IInvoiceItem Description
     */
    function setAmount($amount);

    /**
     *
     * @param float $price Description
     * @return IInvoiceItem Description
     */
    function setPrice($price);

    /**
     *
     * @param string $description Description
     * @return IInvoiceItem Description
     */
    function setDescription($description);

     /**
     *
     * @param int $tax Description
     * @return IInvoiceItem Description
     */
    function setTax($tax);
}