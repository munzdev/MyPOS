<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Models\ORM\Ordering\OrderQuery as OrderQueryORM;
use API\Models\Query;

class OrderQuery extends Query implements IOrderQuery
{
    public function find(): IOrderCollection
    {
        $orders = OrderQueryORM::create()->find();

        $orderCollection = $this->container->get(IOrderCollection::class);
        $orderCollection->setCollection($orders);

        return $orderCollection;
    }

    public function findPk($id): IOrder
    {
        $order = OrderQueryORM::create()->findPk($id);

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function findPKWithAllOrderDetails($orderid): IOrder
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

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getNextForDistribution(int $userid, int $eventid, bool $onlyUserTables) : IOrder
    {
        $order = OrderQueryORM::create()
                    ->getNextForDistribution(
                        $userid,
                        $eventid,
                        $onlyUserTables
                    )
                    ->joinWithOrderDetail()
                    ->useOrderDetailQuery()
                        ->leftJoinWithMenu()
                    ->endUse()
                    ->find()
                    ->getFirst();

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getNextForTodoList(int $orderidToIgnore, int $userid, int $eventid, bool $onlyUserTables, int $listAmount) : IOrderCollection
    {
        $orders = OrderQueryORM::create()
                    ->getNextForDistribution(
                        $userid,
                        $eventid,
                        $onlyUserTables,
                        $listAmount + 1
                    )
                    ->where('`order`.orderid <> ' . $orderidToIgnore)
                    ->joinWithOrderDetail()
                    ->joinWithEventTable()
                    ->useOrderDetailQuery()
                        ->leftJoinWithOrderInProgressRecieved()
                        ->leftJoinWithMenu()
                    ->endUse()
                    //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                    ->find();

        $orderCollection = $this->container->get(IOrderCollection::class);
        $orderCollection->setCollection($orders);

        return $orderCollection;
    }

    public function getInfosForDistribution(int $orderid, array $menuGroupids) : IOrder
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

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getOpenOrdersCount(array $menuGroupids, array $eventTableids, ?int $minutes = null) : int
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
}
