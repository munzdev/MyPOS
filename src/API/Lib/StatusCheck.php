<?php
namespace API\Lib;

use API\Lib\Interfaces\IStatusCheck;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use DateTime;
use const API\ORDER_AVAILABILITY_AVAILABLE;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;

/**
 * Checks status of different DB Table fields that depend on sub-tables.
 */
class StatusCheck implements IStatusCheck
{
    /**
     * @var Container
     */
    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Checks if distribution and invoice of an order are finished and sets them in the table row.
     *
     * @param int $orderid
     */
    public function verifyOrder(int $orderid) : void
    {
        $orderQuery = $this->container->get(IOrderQuery::class);

        $order = $orderQuery->getOrderDetails($orderid);

        $distributionFinished = false;
        $invoiceFinished = false;

        foreach ($order->getOrderDetails() as $orderDetail) {
            $this->verifyOrderDetail($orderDetail->getOrderDetailid());

            if ($distributionFinished === false || ($distributionFinished !== null && ($orderDetail->getDistributionFinished() == null
                || $orderDetail->getDistributionFinished() > $distributionFinished))
            ) {
                $distributionFinished = $orderDetail->getDistributionFinished();
            }

            if ($invoiceFinished === false || ($invoiceFinished !== null && ($orderDetail->getInvoiceFinished() == null
                || $orderDetail->getInvoiceFinished() > $invoiceFinished))
            ) {
                $invoiceFinished = $orderDetail->getInvoiceFinished();
            }
        }

        $order->setDistributionFinished($distributionFinished);
        $order->setInvoiceFinished($invoiceFinished);
        $order->save();
    }

    /**
     * Checks if distribution and invoice of an order_details is finished and sets them in the table row.
     *
     * @param int $orderDetailid
     */
    public function verifyOrderDetail(int $orderDetailid) : void
    {
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);

        $orderDetail = $orderDetailQuery->getWithDetails($orderDetailid);
        $sumData = $orderDetailQuery->getDetailsSum($orderDetailid);

        foreach ($orderDetail->getInvoiceItems() as $invoiceItem) {
            $this->verifyInvoice($invoiceItem->getInvoiceid());
        }

        foreach ($orderDetail->getOrderInProgressRecieveds() as $orderInProgressRecieved) {
            $this->verifyOrderInProgress($orderInProgressRecieved->getOrderInProgressid());
        }

        if ($orderDetail->getMenuid()) {
            $this->verifyAvailability($orderDetailid);
        }

        $distribution = $orderDetail->getDistributionFinished();
        $invoice = $orderDetail->getInvoiceFinished();

        if ($sumData->distribtuionLeft == 0 && $distribution == null) {
            $distribution = new DateTime();
        } elseif ($sumData->distribtuionLeft != 0 && $distribution != null) {
            $distribution = null;
        }

        if ($sumData->invoiceLeft == 0 && $invoice == null) {
            $invoice = new DateTime();
        } elseif ($sumData->invoiceLeft != 0 && $invoice != null) {
            $invoice = null;
        }

        if (!$distribution && $orderDetail->getOrder()->getCancellation()) {
            $distribution = $orderDetail->getOrder()->getCancellation();
        }

        $orderDetail->setDistributionFinished($distribution);
        $orderDetail->setInvoiceFinished($invoice);
        $orderDetail->save();
    }

    /**
     * Checks if an invoice is payed and marke the invoice as payed
     *
     * @param int $invoiceid
     */
    public function verifyInvoice(int $invoiceid) : void
    {
        $invoiceQuery = $this->container->get(IInvoiceQuery::class);

        $invoice = $invoiceQuery->getWithItems($invoiceid);

        $paymentFinished = $invoice->getPaymentFinished();

        if ($invoice->getAmount() == $invoice->getAmountRecieved() && $paymentFinished == null) {
            $paymentFinished = new DateTime();
        } elseif ($invoice->getAmount() != $invoice->getAmountRecieved() && $paymentFinished != null) {
            $paymentFinished = null;
        }

        $invoice->setPaymentFinished($paymentFinished);
        $invoice->save();
    }

    /**
     * Checks if an order in progress is finished and sets the done time
     *
     * @param int $orderInProgressid
     */
    public function verifyOrderInProgress(int $orderInProgressid) : void
    {
        $orderInProgressQuery = $this->container->get(IOrderInProgressQuery::class);

        // TODO make use of SQL group by and SUM to retrieve amount left. But propel somehow creates a problem here
        $orderInProgress = $orderInProgressQuery->getWithDetails($orderInProgressid);

        $done = $orderInProgress->getDone();
        $amountLeft = null;
        $amount = [];

        foreach ($orderInProgress->getOrderInProgressRecieveds() as $orderInProgressRecieved) {
            if (!isset($amount[$orderInProgressRecieved->getOrderDetailid()])) {
                $amount[$orderInProgressRecieved->getOrderDetailid()] = $orderInProgressRecieved->getOrderDetail()->getAmount();
            }

            $amount[$orderInProgressRecieved->getOrderDetailid()] -= $orderInProgressRecieved->getAmount();
        }

        if (!empty($amount)) {
            $amountLeft = array_sum($amount);
        }

        if ($amountLeft == 0 && $done == null) {
            $done = new DateTime();
        } elseif ($amountLeft != 0 && $done != null) {
            $done = null;
        }

        if (!$done && $orderInProgress->getOrder()->getCancellation()) {
            $done = $orderInProgress->getOrder()->getCancellation();
        }

        $orderInProgress->setDone($done);
        $orderInProgress->save();
    }

    /**
     * Checks for menu availability of given order_detailid and sets correct status in order_detail
     *
     * @param int $orderDetailid
     */
    public function verifyAvailability(int $orderDetailid) : void
    {
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);

        $orderDetail = $orderDetailQuery->getMenuDetails($orderDetailid);

        if(!$orderDetail) {
            return;
        }

        $menu = $orderDetail->getMenu();

        if ($menu) {
            $orderDetail->setAvailabilityid($menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER ? ORDER_AVAILABILITY_OUT_OF_ORDER : ORDER_AVAILABILITY_AVAILABLE);
            $orderDetail->setAvailabilityAmount($menu->getAvailabilityAmount());
            $orderDetail->save();
        }

        $availbilityid = $orderDetail->getAvailabilityid();
        $availbilityAmount = $orderDetail->getAvailabilityAmount();

        foreach ($orderDetail->getOrderDetailExtras() as $orderDetailExtra) {
            $menuExtra = $orderDetailExtra->getMenuPossibleExtra()->getMenu();

            if ($availbilityid == ORDER_AVAILABILITY_AVAILABLE) {
                $availbilityid = ($menuExtra->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) ? ORDER_AVAILABILITY_OUT_OF_ORDER : ORDER_AVAILABILITY_AVAILABLE;
            }

            if ($availbilityAmount == null || $availbilityAmount > $menuExtra->getAvailabilityAmount()) {
                $availbilityAmount = $menuExtra->getAvailabilityAmount();
            }
        }

        foreach ($orderDetail->getOrderDetailMixedWiths() as $orderDetailMixedWith) {
            $menu = $orderDetailMixedWith->getMenu();

            if ($availbilityid == ORDER_AVAILABILITY_AVAILABLE) {
                $availbilityid = ($menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) ? ORDER_AVAILABILITY_OUT_OF_ORDER : ORDER_AVAILABILITY_AVAILABLE;
            }

            if ($availbilityAmount == null || $availbilityAmount > $menu->getAvailabilityAmount()) {
                $availbilityAmount = $menu->getAvailabilityAmount();
            }
        }

        if ($availbilityid != $orderDetail->getAvailabilityid() || $availbilityAmount != $orderDetail->getAvailabilityAmount()) {
            $orderDetail->setAvailabilityid($availbilityid);
            $orderDetail->setAvailabilityAmount($availbilityAmount);
            $orderDetail->save();
        }
    }
}
