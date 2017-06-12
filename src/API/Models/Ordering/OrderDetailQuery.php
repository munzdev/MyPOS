<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailUnbilledCollection;
use API\Models\ORM\Invoice\Map\InvoiceItemTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
use API\Models\ORM\Ordering\OrderDetailQuery as OrderDetailQueryORM;
use API\Models\Query;
use Propel\Runtime\ActiveQuery\Criteria;
use stdClass;

class OrderDetailQuery extends Query implements IOrderDetailQuery
{
    public function find(): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function findPk($id): ?IOrderDetail
    {
        $orderDetail = OrderDetailQueryORM::create()->findPk($id);

        if(!$orderDetail) {
            return null;
        }

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getDistributionUnfinishedByMenuid($menuid): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
                                ->filterByDistributionFinished()
                                ->useMenuQuery()
                                    ->filterByMenuid($menuid)
                                ->endUse()
                                ->_or()
                                ->useOrderDetailMixedWithQuery(null, Criteria::LEFT_JOIN)
                                    ->useMenuQuery('re', Criteria::LEFT_JOIN)
                                        ->filterByMenuid($menuid)
                                    ->endUse()
                                ->endUse()
                                ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function getDistributionUnfinishedByMenuExtraid($menuExtraid): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
                            ->filterByDistributionFinished()
                            ->useOrderDetailExtraQuery()
                                ->useMenuPossibleExtraQuery()
                                    ->filterByMenuExtraid($menuExtraid)
                                ->endUse()
                            ->endUse()
                            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function setAvailabilityidByOrderDetailIds(int $availabilityid, array $ids): int
    {
        return OrderDetailQueryORM::create()
                                    ->filterByOrderDetailid($ids)
                                    ->update(['Availabilityid' => $availabilityid]);
    }

    public function getVerifiedDistributionUnfinishedWithSpecialExtras(): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
                            ->filterByMenuid()
                            ->filterByDistributionFinished()
                            ->filterByVerified(true)
                            //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function getByEventid(int $orderDetailid, int $eventid) : ?IOrderDetail
    {
        $orderDetail = OrderDetailQueryORM::create()
            ->useOrderQuery()
                ->useEventTableQuery()
                    ->filterByEventid($eventid)
                ->endUse()
            ->endUse()
            ->filterByOrderDetailid($orderDetailid)
            ->findOne();

        if(!$orderDetail) {
            return null;
        }

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getUnrecievedOfOrder(int $orderid) : IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
            ->filterByOrderid($orderid)
            ->filterByDistributionFinished(null)
            ->leftJoinWithOrderInProgressRecieved()
            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function findUnbilled($orderid, $eventTableid = null): IOrderDetailUnbilledCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
            ->_if($eventTableid)
                ->useOrderQuery()
                    ->filterByEventTableid($eventTableid)
                ->endUse()
            ->_else()
                ->filterByOrderid($orderid)
            ->_endIf()
            ->leftJoinWithMenuSize()
            ->leftJoinWithOrderDetailExtra()
            ->leftJoinWithOrderDetailMixedWith()
            ->leftJoinWithInvoiceItem()
            ->filterByInvoiceFinished(null)
            //->setFormatter(ModelCriteria::FORMAT_ARRAY)
            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailUnbilledCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function getMenuDetails(int $orderDetailid) : ?IOrderDetail
    {
        $orderDetail = OrderDetailQueryORM::create()
            ->leftJoinWithMenu()
            ->leftJoinWithOrderDetailExtra()
            ->leftJoinWithOrderDetailMixedWith()
            ->filterByOrderDetailid($orderDetailid)
            ->find()
            ->getFirst();

        if(!$orderDetail) {
            return null;
        }

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getWithDetails(int $orderDetailid) : ?IOrderDetail
    {
        $orderDetail = OrderDetailQueryORM::create()
            ->useInvoiceItemQuery(null, Criteria::LEFT_JOIN)
                ->useInvoiceQuery(null, Criteria::LEFT_JOIN)
                    ->filterByCanceledInvoiceid(null)
                ->endUse()
            ->endUse()
            ->joinWithOrder()
            ->with(InvoiceItemTableMap::getTableMap()->getPhpName())
            ->leftJoinWithOrderInProgressRecieved()
            ->findByOrderDetailid($orderDetailid)
            ->getFirst();

        if(!$orderDetail) {
            return null;
        }

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getDetailsSum(int $orderDetailid) : ?stdClass
    {
        $orderDetail = OrderDetailQueryORM::create()
            ->useInvoiceItemQuery(null, Criteria::LEFT_JOIN)
                ->useInvoiceQuery(null, Criteria::LEFT_JOIN)
                    ->filterByCanceledInvoiceid(null)
                ->endUse()
            ->endUse()
            //->joinWithOrder()
            //->with(InvoiceItemTableMap::getTableMap()->getPhpName())
            //->leftJoinWithOrderInProgressRecieved()
            ->leftJoinOrderInProgressRecieved()
            ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " - IFNULL(" . OrderInProgressRecievedTableMap::COL_AMOUNT . ", 0))", "DistribtuionLeft")
            ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " - IFNULL(" . InvoiceItemTableMap::COL_AMOUNT . ", 0))", "InvoiceLeft")
            ->groupByOrderDetailid()
            ->findByOrderDetailid($orderDetailid)
            ->getFirst();

        if(!$orderDetail) {
            return null;
        }

        $data = new stdClass();
        $data->distribtuionLeft = $orderDetail->getVirtualColumn('DistribtuionLeft');
        $data->invoiceLeft = $orderDetail->getVirtualColumn('InvoiceLeft');

        return $data;
    }

    public function getUnverifiedOrders(int $verified, int $eventid) : IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
            ->joinWithOrder()
            ->useOrderQuery()
                ->joinWithEventTable()
                ->joinWithUserRelatedByUserid()
                ->useEventTableQuery()
                    ->filterByEventid($eventid)
                ->endUse()
            ->endUse()
            ->leftJoinWith('OrderDetail.User editUser')
            ->leftJoinWithMenuGroup()
            ->filterByVerified($verified)
            ->filterByMenuid(null)
            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }
}
