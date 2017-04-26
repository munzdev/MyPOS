<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Models\ORM\Event\Map\EventTableTableMap;
use API\Models\ORM\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
use API\Models\ORM\Ordering\Map\OrderTableMap;
use API\Models\ORM\Ordering\OrderQuery as OrderQueryORM;
use API\Models\Query;
use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use const API\ORDER_AVAILABILITY_AVAILABLE;

class OrderQuery extends Query implements IOrderQuery
{
    public function find(): IOrderCollection
    {
        $orders = OrderQueryORM::create()->find();

        $orderCollection = $this->container->get(IOrderCollection::class);
        $orderCollection->setCollection($orders);

        return $orderCollection;
    }

    public function findPk($id): ?IOrder
    {
        $order = OrderQueryORM::create()->findPk($id);

        if(!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function findPKWithAllOrderDetails($orderid): ?IOrder
    {
        $order = OrderQueryORM::create()
                    ->joinWithOrderDetail()
                    ->useOrderDetailQuery()
                        ->leftJoinWithMenu()
                        ->leftJoinWithOrderDetailExtra()
                        ->useOrderDetailExtraQuery(null, Criteria::LEFT_JOIN)
                            ->leftJoinWithMenuPossibleExtra()
                            ->useMenuPossibleExtraQuery(null, Criteria::LEFT_JOIN)
                                ->leftJoinWithMenuExtra()
                            ->endUse()
                        ->endUse()
                        ->leftJoinWithOrderDetailMixedWith()
                    ->endUse()
                    ->findPk($orderid);

        if(!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getNextForDistribution(int $userid, int $eventid, bool $onlyUserTables) : ?IOrder
    {
        $orders = $this->getNextForTodoList($userid, $eventid, $onlyUserTables);

        if($orders->isEmpty()) {
            return null;
        }

        return $orders->getFirst();
    }

    public function getNextForTodoList(int $userid, int $eventid, bool $onlyUserTables, int $listAmount = 1, int $orderidToIgnore = 0) : IOrderCollection
    {
        $orders = OrderQueryORM::create()
                    ->getNextForDistribution(
                        $userid,
                        $eventid,
                        $onlyUserTables,
                        $listAmount
                    )
                    ->_if($orderidToIgnore)
                        ->where('`order`.orderid <> ' . $orderidToIgnore)
                    ->_endif()
                    ->joinWithOrderDetail()
                    ->joinWithEventTable()
                    ->useOrderDetailQuery()
                        ->leftJoinWithOrderInProgressRecieved()
                        ->leftJoinWithMenu()
                        ->leftJoinWithOrderDetailExtra()
                        ->useOrderDetailExtraQuery(null, ModelCriteria::LEFT_JOIN)
                            ->leftJoinWithMenuPossibleExtra()
                            ->useMenuPossibleExtraQuery(null, ModelCriteria::LEFT_JOIN)
                                ->leftJoinWithMenuExtra()
                            ->endUse()
                        ->endUse()
                        ->leftJoinWithOrderDetailMixedWith()
                    ->endUse()
                    //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                    ->find();

        $orderCollection = $this->container->get(IOrderCollection::class);
        $orderCollection->setCollection($orders);

        return $orderCollection;
    }

    public function getInfosForDistribution(int $orderid, array $menuGroupids) : ?IOrder
    {
        $order = OrderQueryORM::create()
                    ->filterByOrderid($orderid)
                    ->joinWithOrderDetail()
                    ->leftJoinWith('OrderDetail.Menu')
                    ->leftJoinWith('OrderDetail.MenuSize')
                    ->leftJoinWith('OrderDetail.OrderDetailExtra')
                    ->leftJoinWith('OrderDetailExtra.MenuPossibleExtra')
                    ->leftJoinWith('MenuPossibleExtra.MenuExtra')
                    ->useOrderDetailQuery()
                        ->filterByAvailabilityid(ORDER_AVAILABILITY_AVAILABLE)
                        ->useMenuQuery(null, Criteria::LEFT_JOIN)
                            ->filterByMenuGroupid($menuGroupids)
                        ->endUse()
                        ->_or()
                        ->filterByMenuGroupid($menuGroupids)
                        ->leftJoinWithOrderDetailMixedWith()
                        ->leftJoinWithOrderInProgressRecieved()
                    ->endUse()
                    ->joinWithEventTable()
                    ->joinWithUserRelatedByUserid()
                    ->joinOrderInProgress()
                    //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                    ->find()
                    ->getFirst();

        if(!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getOpenOrdersCount(array $menuGroupids, array $eventTableids, int $minutes = 0) : int
    {
         return OrderQueryORM::create()
                    ->useOrderDetailQuery()
                        ->useMenuQuery()
                            ->filterByMenuGroupid($menuGroupids)
                        ->endUse()
                        ->filterByMenuGroupid(array_merge([null], $menuGroupids))
                    ->endUse()
                    ->useEventTableQuery()
                        ->filterByEventTableid($eventTableids)
                    ->endUse()
                    ->filterByDistributionFinished()
                    ->filterByCancellation()
                    ->_if($minutes)
                        ->filterByOrdertime(['min' => new DateTime("-$minutes minutes")])
                    ->_endif()
                    ->count();
    }

    public function getWithEventTableAndUser(int $orderid) : ?IOrder
    {
        $order = OrderQueryORM::create()
            ->joinWithEventTable()
            ->joinUserRelatedByUserid()
            ->filterByOrderid($orderid)
            ->find()
            ->getFirst();

        if (!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }


    public function findWithPagingAndSearch($offset, $limit, $eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo) : IOrderCollection
    {
        $searchCriteria = $this->getSearchCriteria($eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo);

        if($offset !== null && $limit !== null) {
            $ordersList = OrderQueryORM::create(null, clone $searchCriteria)
                            ->offset($offset)
                            ->limit($limit)
                            ->find();
        }

        $criteriaData = $this->extendSearchCriteriaWithData($searchCriteria);

        $orders = $criteriaData
            ->_if(!empty($ordersList))
                ->where(OrderTableMap::COL_ORDERID . " IN ?", $ordersList->getColumnValues())
            ->_endif()
            //->setFormatter(ModelCriteria::FORMAT_ARRAY)
            ->find();

        $orderCollection = $this->container->get(IOrderCollection::class);
        $orderCollection->setCollection($orders);

        return $orderCollection;
    }

    public function getOrderCountBySearch($eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo) : int
    {
        $searchCriteria = $this->getSearchCriteria($eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo);
        $criteriaData = $this->extendSearchCriteriaWithData($searchCriteria);

        return $criteriaData->count();
    }

    public function getMaxPriority(int $eventid) : int
    {
        $orderDetailPriority = OrderQueryORM::create()
            ->useEventTableQuery()
            ->filterByEventid($eventid)
            ->endUse()
            ->addAsColumn('priority', 'MAX(' . OrderTableMap::COL_PRIORITY . ')')
            ->findOne();

        return $orderDetailPriority->getVirtualColumn('priority');
    }

    public function getDetails($orderid) : \stdClass
    {
        $orderInfo = OrderQueryORM::create()
            ->useOrderDetailQuery()
                ->useInvoiceItemQuery(null, ModelCriteria::LEFT_JOIN)
                    ->useInvoiceQuery(null, ModelCriteria::LEFT_JOIN)
                        ->filterByCanceled(null)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " * " . OrderDetailTableMap::COL_SINGLE_PRICE . ")", "price")
            ->withColumn("COUNT(" . OrderDetailTableMap::COL_ORDER_DETAILID . ") - COUNT(" . InvoiceItemTableMap::COL_INVOICE_ITEMID . ")", "open")
            ->withColumn("SUM(" . InvoiceItemTableMap::COL_AMOUNT . " * " . InvoiceItemTableMap::COL_PRICE . ")", "amountBilled")
            ->groupByOrderid()
            ->findByOrderid($orderid)
            ->getFirst();

        $return = new \stdClass();
        $return->price = $orderInfo->getVirtualColumn('price');
        $return->open = $orderInfo->getVirtualColumn('open');
        $return->amountBilled = $orderInfo->getVirtualColumn('amountBilled');

        return $return;
    }

    public function getOrderDetails($orderid) : ?IOrder
    {
        $order = OrderQueryORM::create()
                    ->joinWithEventTable()
                    ->joinWithOrderDetail()
                    ->joinWithUser()
                    ->leftJoinWithOrderInProgress()
                    ->useOrderDetailQuery()
                        ->useOrderInProgressRecievedQuery(null, Criteria::LEFT_JOIN)
                            ->leftJoinWithDistributionGivingOut()
                        ->endUse()
                        ->leftJoinWithMenuSize()
                        ->leftJoinWithOrderDetailExtra()
                        ->leftJoinWithOrderDetailMixedWith()
                        ->with(OrderInProgressRecievedTableMap::getTableMap()->getPhpName())
                    ->endUse()
                    //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                    ->findByOrderid($orderid)
                    ->getFirst();

        if (!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getWithModifyDetails(int $orderid) : ?IOrder
    {
        $order = OrderQueryORM::create()
                    ->joinWithOrderDetail()
                    ->useOrderDetailQuery()
                        ->leftJoinWithMenuSize()
                        ->leftJoinWithOrderDetailExtra()
                        ->leftJoinWithOrderDetailMixedWith()
                    ->endUse()
                    //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                    ->findByOrderid($orderid)
                    ->getFirst();

        if (!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function setFirstPriority(int $eventid, int $orderid) : IOrder
    {
        $order = OrderQueryORM::create()
            ->setFirstPriority(
                $orderid,
                $eventid
            );

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    private function getSearchCriteria($eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo) : OrderQueryORM
    {
        return OrderQueryORM::create()
            ->useEventTableQuery()
                ->filterByEventid($eventid)
                ->_if($tablenr)
                    ->filterByName($tablenr)
                ->_endif()
            ->endUse()
            ->_if($status == 'open')
                ->filterByDistributionFinished(null)
                ->_or()
                ->filterByInvoiceFinished(null)
            ->_elseif($status == 'completed')
                ->filterByDistributionFinished(null, Criteria::NOT_EQUAL)
                ->_and()
                ->filterByInvoiceFinished(null, Criteria::NOT_EQUAL)
            ->_endif()
            ->_if($orderid)
                ->filterByOrderid($orderid)
            ->_endif()
            ->_if($userid != '*')
                ->filterByUserid($userid)
            ->_endif()
            ->_if($dateFrom)
                ->filterByOrdertime(array('min' => DateTime::createFromFormat('H:i', $dateFrom)))
            ->_endif()
            ->_if($dateTo)
                ->filterByOrdertime(array('max' => DateTime::createFromFormat('H:i', $dateTo)))
            ->_endif();
    }

    private function extendSearchCriteriaWithData(OrderQueryORM $searchCriteria) : OrderQueryORM
    {
        return $searchCriteria
            ->joinOrderDetail()
            ->leftJoinWithOrderInProgress()
            ->with(EventTableTableMap::getTableMap()->getPhpName())
            ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " * " . OrderDetailTableMap::COL_SINGLE_PRICE . ")", "price")
            ->groupByOrderid()
            ->orderByPriority();
    }
}
