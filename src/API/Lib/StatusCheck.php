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
     * @param int $i_orderid
     */
    public static function verifyOrder(int $i_orderid) : void {
        $o_order = OrderQuery::create()
                                ->joinWithOrderDetail()
                                ->with(OrderDetailTableMap::getTableMap()->getPhpName())
                                ->leftJoinWithOrderInProgress()
                                ->filterByOrderid($i_orderid)
                                ->find()
                                ->getFirst();

        $d_distribution_finished = false;
        $d_invoice_finished = false;

        foreach($o_order->getOrderDetails() as $o_order_detail) {
            self::verifyOrderDetail($o_order_detail->getOrderDetailid());

            if($d_distribution_finished === false || ($d_distribution_finished !== null && ($o_order_detail->getDistributionFinished() == null ||
                                                                                            $o_order_detail->getDistributionFinished() > $d_distribution_finished)))
                $d_distribution_finished = $o_order_detail->getDistributionFinished();

            if($d_invoice_finished === false || ($d_invoice_finished !== null && ($o_order_detail->getInvoiceFinished() == null ||
                                                                                  $o_order_detail->getInvoiceFinished() > $d_invoice_finished)))
                $d_invoice_finished = $o_order_detail->getInvoiceFinished();
        }

        $o_order->setDistributionFinished($d_distribution_finished);
        $o_order->setInvoiceFinished($d_invoice_finished);
        $o_order->save();
    }

    /**
     * Checks if distribution and invoice of an order_details is finished and sets them in the table row.
     *
     * @param int $i_order_detailid
     */
    public static function verifyOrderDetail(int $i_order_detailid) : void {
        $o_order_detail = OrderDetailQuery::create()
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
                                            ->findByOrderDetailid($i_order_detailid)
                                            ->getFirst();

        foreach($o_order_detail->getInvoiceItems() as $o_invoice_item) {
            self::verifyInvoice($o_invoice_item->getInvoiceid());
        }

        foreach($o_order_detail->getOrderInProgressRecieveds() as $o_order_in_progress_recieved) {
            self::verifyOrderInProgress($o_order_in_progress_recieved->getOrderInProgressid());
        }

        if($o_order_detail->getMenuid())
            self::verifyAvailability($i_order_detailid);

        $d_distribution = $o_order_detail->getDistributionFinished();
        $d_invoice = $o_order_detail->getInvoiceFinished();

        if($o_order_detail->getVirtualColumn('DistribtuionLeft') == 0 && $d_distribution == null)
            $d_distribution = new DateTime();
        elseif($o_order_detail->getVirtualColumn('DistribtuionLeft') != 0 && $d_distribution != null)
            $d_distribution = null;

        if($o_order_detail->getVirtualColumn('InvoiceLeft') == 0 && $d_invoice == null)
            $d_invoice = new DateTime();
        elseif($o_order_detail->getVirtualColumn('InvoiceLeft') != 0 && $d_invoice != null)
            $d_invoice = null;

        if(!$d_distribution && $o_order_detail->getOrder()->getCancellation()) {
            $d_distribution = $o_order_detail->getOrder()->getCancellation();
        }

        $o_order_detail->setDistributionFinished($d_distribution);
        $o_order_detail->setInvoiceFinished($d_invoice);
        $o_order_detail->save();
    }

    /**
     * Checks if an invoice is payed and marke the invoice as payed
     *
     * @param int $i_invoiceid
     */
    public static function verifyInvoice(int $i_invoiceid) : void {
        $o_invoice = InvoiceQuery::create()
                                    ->joinInvoiceItem()
                                    ->findByInvoiceid($i_invoiceid)
                                    ->getFirst();

        $d_payment_finished = $o_invoice->getPaymentFinished();

        if($o_invoice->getAmount() == $o_invoice->getAmountRecieved() && $d_payment_finished == null)
            $d_payment_finished = new DateTime();
        elseif($o_invoice->getAmount() != $o_invoice->getAmountRecieved() && $d_payment_finished != null)
            $d_payment_finished = null;

        $o_invoice->setPaymentFinished($d_payment_finished);
        $o_invoice->save();
    }

    /**
     * Checks if an order in progress is finished and sets the done time
     *
     * @param int $i_order_in_progressid
     */
    public static function verifyOrderInProgress(int $i_order_in_progressid) : void {
        $o_order_in_progress = OrderInProgressQuery::create()
                                                    ->useOrderInProgressRecievedQuery()
                                                        ->joinOrderDetail()
                                                        ->withColumn(OrderDetailTableMap::COL_AMOUNT . " - IFNULL(SUM(" . OrderInProgressRecievedTableMap::COL_AMOUNT . "), 0)" , "AmountLeft")
                                                        ->groupByOrderDetailid()
                                                    ->endUse()
                                                    ->joinWithOrder()
                                                    ->with(OrderInProgressRecievedTableMap::getTableMap()->getPhpName())
                                                    ->findByOrderInProgressid($i_order_in_progressid)
                                                    ->getFirst();

        $d_done = $o_order_in_progress->getDone();
        $i_amount_left = 0;

        foreach($o_order_in_progress->getOrderInProgressRecieveds() as $o_order_in_progress_recieved) {
            $i_amount_left += ($o_order_in_progress_recieved->getVirtualColumn('AmountLeft') == 0) ? 0 : 1;
        }

        if($i_amount_left == 0 && $d_done == null)
            $d_done = new DateTime();
        elseif($i_amount_left != 0 && $d_done != null)
            $d_done = null;

        if(!$d_done && $o_order_detail->getOrder()->getCancellation()) {
            $d_done = $o_order_detail->getOrder()->getCancellation();
        }

        $o_order_in_progress->setDone($d_done);
        $o_order_in_progress->save();
    }

    /**
     * Checks for menu availability of given order_detailid and sets correct status in order_detail
     *
     * @param int $i_order_detailid
     */
    public static function verifyAvailability(int $i_order_detailid) : void {
        $o_order_detail = OrderDetailQuery::create()
                                            ->leftJoinWithMenu()
                                            ->leftJoinWithOrderDetailExtra()
                                            ->leftJoinWithOrderDetailMixedWith()
                                            ->filterByOrderDetailid($i_order_detailid)
                                            ->find();

        if(!$o_order_detail->count())
            return;

        $o_order_detail = $o_order_detail->getFirst();

        $o_menu = $o_order_detail->getMenu();

        if($o_menu) {
            $o_order_detail->setAvailabilityid($o_menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER ? ORDER_AVAILABILITY_OUT_OF_ORDER : ORDER_AVAILABILITY_AVAILABLE);
            $o_order_detail->save();
        }

        $i_availbilityid = $o_order_detail->getAvailabilityid();

        foreach($o_order_detail->getOrderDetailExtras() as $o_order_detail_extra)
        {
            if($i_availbilityid == ORDER_AVAILABILITY_AVAILABLE) {
                $o_menu = $o_order_detail_extra->getMenuPossibleExtra()->getMenu();
                $i_availbilityid = ($o_menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) ? ORDER_AVAILABILITY_OUT_OF_ORDER : ORDER_AVAILABILITY_AVAILABLE;
            }
        }

        foreach($o_order_detail->getOrderDetailMixedWiths() as $o_order_detail_mixed_with)
        {
            if($i_availbilityid == ORDER_AVAILABILITY_AVAILABLE) {
                $o_menu = $o_order_detail_mixed_with->getMenu();
                $i_availbilityid = ($o_menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) ? ORDER_AVAILABILITY_OUT_OF_ORDER : ORDER_AVAILABILITY_AVAILABLE;
            }
        }

        if($i_availbilityid != $o_order_detail->getAvailabilityid()) {
            $o_order_detail->setAvailabilityid($i_availbilityid);
            $o_order_detail->save();
        }
    }
}
