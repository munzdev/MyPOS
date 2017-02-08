<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\Menu\Base\MenuQuery;
use API\Models\Menu\MenuExtraQuery;
use API\Models\OIP\Base\OrderInProgressQuery;
use API\Models\OIP\DistributionGivingOutQuery;
use API\Models\OIP\OrderInProgress;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\OrderQuery;
use DateTime;
use Exception;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Propel;
use Slim\App;
use const API\ORDER_AVAILABILITY_AVAILABLE;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;
use const API\USER_ROLE_DISTRIBUTION_OVERVIEW;

class DistributionPlace extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_DISTRIBUTION_OVERVIEW];

        $o_app->getContainer()['db'];
    }

    protected function GET() : void
    {
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $a_order = $this->getCurrentOrder();
            $a_ordersInTodo = $this->getOrdersInTodo();
            $a_orderDetailwithSpecialExtra = $this->getOrderDetailWithSpecialExtra();
            $a_menuExtras = $this->getMenuExtras();

            $a_orderStatistic = $this->getOrderStatisic();

            $o_connection->commit();

            $this->withJson(['Order' => $a_order,
                             'OrdersInTodo' => $a_ordersInTodo,
                             'OrderDetailWithSpecialExtra'=> $a_orderDetailwithSpecialExtra,
                             'MenuExtras' => $a_menuExtras,
                             'OpenOrders' => $a_orderStatistic['OpenOrders'],
                             'DoneOrders' => $a_orderStatistic['DoneOrders'],
                             'NewOrders' => $a_orderStatistic['NewOrders'],
                             'Minutes' => $a_orderStatistic['Minutes']]);
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }

    private function getCurrentOrder() {
        $o_user = Auth::GetCurrentUser();
        $a_config = $this->o_app->getContainer()['settings'];
        $b_assist = $a_config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'];

        //-- First try fetch allready started order to handle, witch is not finished yet
        //-- (like page reloaded or the status of a product has changed back to available)
        $o_orderInProgress = $this->getOpenOrderInProgress();

        //-- if no existing progress order found, take a new order from priority list
        if(!$o_orderInProgress) {
            //-- first try to find a order that is associated to the distribution place tables
            $o_order = OrderQuery::create()
                                    ->getNextForDistribution($o_user->getUserid(),
                                                             $o_user->getEventUser()->getEventid(),
                                                             true)
                                    ->joinWithOrderDetail()
                                    ->useOrderDetailQuery()
                                        ->leftJoinWithMenu()
                                    ->endUse()
                                    ->find()
                                    ->getFirst();

            //-- Secondly try to find an order, which belongs to an other distribution place tables but
            //-- has the same menu_groupid and can also be handeled by the current user
            //-- this lets ordes be done faster
            if(!$o_order && $b_assist)
                $o_order = OrderQuery::create()
                                ->getNextForDistribution($o_user->getUserid(),
                                                         $o_user->getEventUser()->getEventid(),
                                                         false)
                                ->joinWithOrderDetail()
                                ->useOrderDetailQuery()
                                    ->leftJoinWithMenu()
                                ->endUse()
                                ->find()
                                ->getFirst();

            if(!$o_order)
                return null;

            $a_menuGroupids = [];

            foreach($o_order->getOrderDetails() as $o_orderDetail) {
                if($o_orderDetail->getMenuid())
                    $a_menuGroupids[] = $o_orderDetail->getMenu()->getMenuGroupid();
                elseif($o_orderDetail->getVerified())
                    $a_menuGroupids[] = $o_orderDetail->getMenuGroupid();
            }

            $a_menuGroupids = array_unique($a_menuGroupids);

            $o_distributionPlaceGroups = DistributionPlaceGroupQuery::create()
                                                                    ->filterByMenuGroupid($a_menuGroupids)
                                                                    ->useDistributionPlaceQuery()
                                                                        ->filterByEventid($o_user->getEventUser()->getEventid())
                                                                        ->useDistributionPlaceUserQuery()
                                                                            ->filterByUserid($o_user->getUserid())
                                                                        ->endUse()
                                                                    ->endUse()
                                                                    ->find();

            foreach($o_distributionPlaceGroups as $o_distributionPlaceGroup) {
                $o_orderInProgress = new OrderInProgress();
                $o_orderInProgress->setOrder($o_order);
                $o_orderInProgress->setUser($o_user);
                $o_orderInProgress->setMenuGroupid($o_distributionPlaceGroup->getMenuGroupid());
                $o_orderInProgress->setBegin(new DateTime());
                $o_orderInProgress->save();
            }
        } else {
            $o_order = $o_orderInProgress->getOrder();
        }

        $o_orderInProgresses = OrderInProgressQuery::create()
                                                    ->filterByOrder($o_order)
                                                    ->filterByUser($o_user)
                                                    ->filterByDone()
                                                    ->find();

        $a_menuGroupids = [];
        foreach($o_orderInProgresses as $o_orderInProgress) {
            $a_menuGroupids[] = $o_orderInProgress->getMenuGroupid();
        }

        $a_orderToReturn = OrderQuery::create()
                                        ->filterByOrderid($o_order->getOrderid())
                                        ->joinWithOrderDetail()
                                        ->leftJoinWith('OrderDetail.Menu')
                                        ->leftJoinWith('OrderDetail.MenuSize')
                                        ->leftJoinWith('OrderDetail.OrderDetailExtra')
                                        ->leftJoinWith('OrderDetailExtra.MenuPossibleExtra')
                                        ->leftJoinWith('MenuPossibleExtra.MenuExtra')
                                        ->useOrderDetailQuery()
                                            ->filterByAvailabilityid(ORDER_AVAILABILITY_AVAILABLE)
                                            ->useMenuQuery(null, Criteria::LEFT_JOIN)
                                                ->filterByMenuGroupid($a_menuGroupids)
                                            ->endUse()
                                            ->_or()
                                            ->filterByMenuGroupid($a_menuGroupids)
                                            ->leftJoinWithOrderDetailMixedWith()
                                        ->endUse()
                                        ->joinWithEventTable()
                                        ->joinWithUserRelatedByUserid()
                                        ->joinOrderInProgress()
                                        ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                        ->find()
                                        ->getFirst();

        $a_orderToReturn['UserRelatedByUserid'] = $this->cleanupUserData($a_orderToReturn['UserRelatedByUserid']);

        // :TODO: fix hydration problem. This datas should be included directly in the abouve query but this doesn't work yet
        foreach($a_orderToReturn['OrderDetails'] as &$a_orderDetail) {
            foreach($a_orderDetail['OrderDetailMixedWiths'] as &$a_orderDetailMixedWith) {

                if(empty($a_orderDetailMixedWith['Menuid']))
                    continue;

                $a_menu = MenuQuery::create()
                                    ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                    ->findPk($a_orderDetailMixedWith['Menuid']);

                $a_orderDetailMixedWith['Menu'] = $a_menu;
            }
        }

        return $a_orderToReturn;
    }

    private function getOpenOrderInProgress() {
        $o_user = Auth::GetCurrentUser();

        $o_ordersInProgress = OrderInProgressQuery::create()
                                                    ->filterByUser($o_user)
                                                    ->filterByDone()
                                                    ->useOrderQuery()
                                                        ->useEventTableQuery()
                                                            ->filterByEventid($o_user->getEventUser()->getEventid())
                                                        ->endUse()
                                                        ->useOrderDetailQuery()
                                                            ->filterByAvailabilityid(ORDER_AVAILABILITY_OUT_OF_ORDER, Criteria::NOT_EQUAL)
                                                        ->endUse()
                                                        ->orderByPriority()
                                                    ->endUse()
                                                    ->joinWithOrder()
                                                    ->find()
                                                    ->getFirst();

        return $o_ordersInProgress;
    }

    private function getMyDistributionPlaceGroups() {
        $o_user = Auth::GetCurrentUser();

        $o_distributionPlaceGroups = DistributionPlaceGroupQuery::create()
                                                                    ->useDistributionPlaceQuery()
                                                                        ->filterByEventid($o_user->getEventUser()->getEventid())
                                                                        ->useDistributionPlaceUserQuery()
                                                                            ->filterByUserid($o_user->getUserid())
                                                                        ->endUse()
                                                                    ->endUse()
                                                                    ->joinWithDistributionPlaceTable()
                                                                    ->find();

        return $o_distributionPlaceGroups;
    }

    private function getOrdersInTodo() {
        $o_user = Auth::GetCurrentUser();
        $a_config = $this->o_app->getContainer()['settings'];
        $b_assist = $a_config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'];

        $o_orderInProgress = $this->getOpenOrderInProgress();

        if($o_orderInProgress) {
            $o_inProgressOrder = $o_orderInProgress->getOrder();
            $o_distributionPlaceGroups = $this->getMyDistributionPlaceGroups();

            $i_menuGroupids = [];
            foreach($o_distributionPlaceGroups as $o_distributionPlaceGroup) {
                $i_menuGroupids[] = $o_distributionPlaceGroup->getMenuGroupid();
            }

            //-- first try to find a order that is associated to the distribution place tables
            $o_orders = OrderQuery::create()
                                    ->getNextForDistribution($o_user->getUserid(),
                                                             $o_user->getEventUser()->getEventid(),
                                                             true,
                                                             $a_config['App']['Distribution']['AmountDisplayedInTodoList'] + 1)
                                    ->where('`order`.orderid <> ' . $o_inProgressOrder->getOrderid())
                                    ->joinWithOrderDetail()
                                    ->joinWithEventTable()
                                    ->useOrderDetailQuery()
                                        ->leftJoinWithMenu()
                                    ->endUse()
                                    ->find();

            //-- Secondly try to find an order, which belongs to an other distribution place tables but
            //-- has the same menu_groupid and can also be handeled by the current user
            //-- this lets ordes be done faster
            if(!$o_orders && $b_assist)
                $o_orders = OrderQuery::create()
                                        ->getNextForDistribution($o_user->getUserid(),
                                                                 $o_user->getEventUser()->getEventid(),
                                                                 false,
                                                                 $a_config['App']['Distribution']['AmountDisplayedInTodoList'] + 1)
                                        ->where('`order`.orderid <> ' . $o_inProgressOrder->getOrderid())
                                        ->joinWithOrderDetail()
                                        ->joinWithEventTable()
                                        ->useOrderDetailQuery()
                                            ->leftJoinWithMenu()
                                        ->endUse()
                                        ->find();

            if(!$o_orders)
                return null;

            foreach($o_orders as $o_order) {
                $i_count = 0;
                foreach($o_order->getOrderDetails() as $o_orderDetail) {
                    if( ($o_orderDetail->getMenuid() && in_array($o_orderDetail->getMenu()->getMenuGroupid(), $i_menuGroupids))
                        ||
                        in_array($o_orderDetail->getMenuGroupid(), $i_menuGroupids)) {
                        $i_count += $o_orderDetail->getAmount();
                    }
                }
                $o_order->setVirtualColumn("OrderDetailCount", $i_count);
            }

            return $this->cleanupRecursionData($o_orders->toArray());
        }
    }

    private function getOrderDetailWithSpecialExtra() {
        $o_orderDetail = OrderDetailQuery::create()
                                            ->filterByMenuid()
                                            ->filterByDistributionFinished()
                                            ->filterByVerified(true)
                                            ->find();

        return $o_orderDetail->toArray();
    }

    private function getOrderStatisic() {
        $o_user = Auth::GetCurrentUser();
        $a_config = $this->o_app->getContainer()['settings'];
        $i_minutes = $a_config['App']['Distribution']['OrderProgressTimeRangeMinutes'];

        $o_distributionPlaceGroups = $this->getMyDistributionPlaceGroups();

        $a_eventTableids = [];
        $a_menuGroupids = [];
        foreach($o_distributionPlaceGroups as $o_distributionPlaceGroup) {
            $a_menuGroupids[] = $o_distributionPlaceGroup->getMenuGroupid();
            foreach($o_distributionPlaceGroup->getDistributionPlaceTables() as $o_distributionPlaceTable) {
                $a_eventTableids[] = $o_distributionPlaceTable->getEventTableid();
            }
        }

        $a_eventTableids = array_unique($a_eventTableids);

        $i_doneOrder = DistributionGivingOutQuery::create()
                                                    ->useOrderInProgressRecievedQuery()
                                                        ->useOrderInProgressQuery()
                                                            ->filterByUser($o_user)
                                                        ->endUse()
                                                    ->endUse()
                                                    ->filterByDate(['min' => new DateTime("-$i_minutes minutes")])
                                                    ->count();

        $o_openOrderFilter = OrderQuery::create()
                                ->useOrderDetailQuery()
                                    ->useMenuQuery()
                                        ->filterByMenuGroupid($a_menuGroupids)
                                    ->endUse()
                                    ->filterByMenuGroupid(array_merge([null], $a_menuGroupids))
                                ->endUse()
                                ->useEventTableQuery()
                                    ->filterByEventTableid($a_eventTableids)
                                ->endUse()
                                ->filterByDistributionFinished()
                                ->filterByCancellation();

        $i_openOrder = $o_openOrderFilter->count();

        $i_newOrder = $o_openOrderFilter->filterByOrdertime(['min' => new DateTime("-$i_minutes minutes")])
                                        ->count();

        return ['OpenOrders' => $i_openOrder,
                'DoneOrders' => $i_doneOrder,
                'NewOrders' => $i_newOrder,
                'Minutes' => $i_minutes];
    }

    private function getMenuExtras() {
        $o_user = Auth::GetCurrentUser();

        $o_menuExtras = MenuExtraQuery::create()
                                        ->filterByEventid($o_user->getEventUser()->getEventid())
                                        ->find();

        return $o_menuExtras->toArray();
    }
}