<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
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

        if(!$order) {
            return null;
        }

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }
}
