<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Models\Model;
use API\Models\ORM\Invoice\InvoiceItem as InvoiceItemORM;

/**
 * Skeleton subclass for representing a row from the 'invoice_item' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InvoiceItem extends Model implements IInvoiceItem
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new InvoiceItemORM());
    }

    public function getAmount(): int
    {
        return $this->model->getAmount();
    }

    public function getDescription(): string
    {
        return $this->model->getAmount();
    }

    public function getInvoice(): IInvoice
    {
        $invoice = $this->model->getInvoice();

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }

    public function getInvoiceItemid(): int
    {
        return $this->model->getInvoiceItemid();
    }

    public function getInvoiceid(): int
    {
        return $this->model->getInvoiceid();
    }

    public function getOrderDetail(): IOrderDetail
    {
        $orderDetail = $this->model->getOrderDetail();

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getOrderDetailid(): int
    {
        return $this->model->getOrderDetailid();
    }

    public function getPrice(): float
    {
        return $this->model->getPrice();
    }

    public function getTax(): int
    {
        return $this->model->getTax();
    }

    public function setAmount($amount): IInvoiceItem
    {
        $this->model->setAmount($amount);
        return $this;
    }

    public function setDescription($description): IInvoiceItem
    {
        $this->model->setDescription($description);
        return $this;
    }

    public function setInvoice($invoice): IInvoiceItem
    {
        $this->model->setInvoice($invoice);
        return $this;
    }

    public function setInvoiceItemid($invoiceItemid): IInvoiceItem
    {
        $this->model->setInvoiceItemid($invoiceItemid);
        return $this;
    }

    public function setInvoiceid($invoiceid): IInvoiceItem
    {
        $this->model->setInvoiceid($invoiceid);
        return $this;
    }

    public function setOrderDetail($orderDetail): IInvoiceItem
    {
        $this->model->setOrderDetail($orderDetail);
        return $this;
    }

    public function setOrderDetailid($orderDetailid): IInvoiceItem
    {
        $this->model->setOrderDetailid($orderDetailid);
        return $this;
    }

    public function setPrice($price): IInvoiceItem
    {
        $this->model->setPrice($price);
        return $this;
    }

    public function setTax($tax): IInvoiceItem
    {
        $this->model->setTax($tax);
        return $this;
    }

}
