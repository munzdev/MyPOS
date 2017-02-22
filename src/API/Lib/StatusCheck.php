<?php
namespace API\Lib;

use API\Models\Invoice\Base\InvoiceQuery;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\OIP\OrderInProgressQuery;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\OrderQuery;
use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use const API\ORDER_AVAILABILITY_AVAILABLE;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;

/**
 * Checks status of different DB Table fields that depend on sub-tables.
 */
abstract class StatusCheck
{
    /**
     * Checks if distribution and invoice of an order are finished and sets them in the table row.
     *
     * @param int $orderid
     */
    public static function verifyOrder(int $orderid) : void
    {
        $order = OrderQuery::create()
                            ->joinWithOrderDetail()
                            ->with(OrderDetailTableMap::getTableMap()->getPhpName())
                            ->leftJoinWithOrderInProgress()
                            ->filterByOrderid($orderid)
                            ->find()
                            ->getFirst();

        $distributionFinished = false;
        $invoiceFinished = false;

        foreach ($order->getOrderDetails() as $orderDetail) {
            self::verifyOrderDetail($orderDetail->getOrderDetailid());

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
    public static function verifyOrderDetail(int $orderDetailid) : void
    {
        $orderDetail = OrderDetailQuery::create()
                                        ->useInvoiceItemQuery(null, Criteria::LEFT_JOIN)
                                            ->useInvoiceQuery(null, Criteria::LEFT_JOIN)
                                                ->filterByCanceledInvoiceid(null)
                                            ->endUse()
                                        ->endUse()
                                        ->joinWithOrder()
                                        ->with(InvoiceItemTableMap::getTableMap()->getPhpName())
                                        ->leftJoinWithOrderInProgressRecieved()
                                        ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " - IFNULL(" . OrderInProgressRecievedTableMap::COL_AMOUNT . ", 0))", "DistribtuionLeft")
                                        ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " - IFNULL(" . InvoiceItemTableMap::COL_AMOUNT . ", 0))", "InvoiceLeft")
                                        ->groupByOrderDetailid()
                                        ->findByOrderDetailid($orderDetailid)
                                        ->getFirst();

        foreach ($orderDetail->getInvoiceItems() as $invoiceItem) {
            self::verifyInvoice($invoiceItem->getInvoiceid());
        }

        foreach ($orderDetail->getOrderInProgressRecieveds() as $orderInProgressRecieved) {
            self::verifyOrderInProgress($orderInProgressRecieved->getOrderInProgressid());
        }

        if ($orderDetail->getMenuid()) {
            self::verifyAvailability($orderDetailid);
        }

        $distribution = $orderDetail->getDistributionFinished();
        $invoice = $orderDetail->getInvoiceFinished();

        if ($orderDetail->getVirtualColumn('DistribtuionLeft') == 0 && $distribution == null) {
            $distribution = new DateTime();
        } elseif ($orderDetail->getVirtualColumn('DistribtuionLeft') != 0 && $distribution != null) {
            $distribution = null;
        }

        if ($orderDetail->getVirtualColumn('InvoiceLeft') == 0 && $invoice == null) {
            $invoice = new DateTime();
        } elseif ($orderDetail->getVirtualColumn('InvoiceLeft') != 0 && $invoice != null) {
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
    public static function verifyInvoice(int $invoiceid) : void
    {
        $invoice = InvoiceQuery::create()
                                ->joinInvoiceItem()
                                ->findByInvoiceid($invoiceid)
                                ->getFirst();

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
    public static function verifyOrderInProgress(int $orderInProgressid) : void
    {
        // TODO make use of SQL group by and SUM to retrieve amount left. But propel somehow creates a problem here
        $orderInProgress = OrderInProgressQuery::create()
                                                ->joinWithOrderInProgressRecieved()
                                                ->useOrderInProgressRecievedQuery()
                                                    ->joinWithOrderDetail()
                                                    //->withColumn(OrderDetailTableMap::COL_AMOUNT . " - IFNULL(SUM(" . OrderInProgressRecievedTableMap::COL_AMOUNT . "), 0)" , "AmountLeft")
                                                    //->groupByOrderDetailid()
                                                ->endUse()
                                                ->joinWithOrder()
                                                ->findByOrderInProgressid($orderInProgressid)
                                                ->getFirst();

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
    public static function verifyAvailability(int $orderDetailid) : void
    {
        $orderDetailCollection = OrderDetailQuery::create()
                                        ->leftJoinWithMenu()
                                        ->leftJoinWithOrderDetailExtra()
                                        ->leftJoinWithOrderDetailMixedWith()
                                        ->filterByOrderDetailid($orderDetailid)
                                        ->find();

        if (!$orderDetailCollection->count()) {
            return;
        }

        $orderDetail = $orderDetailCollection->getFirst();

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
