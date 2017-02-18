<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\DistributionPlace\DistributionPlaceUserQuery;
use API\Models\Menu\Base\MenuQuery;
use API\Models\Menu\MenuExtraQuery;
use API\Models\OIP\Base\OrderInProgressQuery;
use API\Models\OIP\DistributionGivingOut;
use API\Models\OIP\DistributionGivingOutQuery;
use API\Models\OIP\OrderInProgress;
use API\Models\OIP\OrderInProgressRecieved;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\OrderQuery;
use DateTime;
use Exception;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Propel;
use Slim\App;
use const API\ORDER_AVAILABILITY_AVAILABLE;
use const API\ORDER_AVAILABILITY_DELAYED;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;
use const API\USER_ROLE_DISTRIBUTION_OVERVIEW;

class DistributionPlace extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_DISTRIBUTION_OVERVIEW,
                             'PUT' => USER_ROLE_DISTRIBUTION_OVERVIEW];

        $o_app->getContainer()['db'];
    }

    protected function PUT() : void {
        $o_user = Auth::GetCurrentUser();
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();
            $o_order_template = new Order();
            $this->jsonToPropel($this->a_json, $o_order_template);

            $o_order = OrderQuery::create()
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
                                   ->findPk($o_order_template->getOrderid());

            $o_orderInProgresses = OrderInProgressQuery::create()
                                        ->filterByUser($o_user)
                                        ->filterByOrder($o_order)
                                        ->filterByDone()
                                        ->find();

            $o_distributionGivingOut = new DistributionGivingOut();
            $o_distributionGivingOut->setDate(new DateTime());
            $o_distributionGivingOut->save();

            foreach($o_order_template->getOrderDetails() as $o_orderDetail_template) {
                foreach($o_order->getOrderDetails() as $o_orderDetail) {
                    if($o_orderDetail_template->getOrderDetailid() == $o_orderDetail->getOrderDetailid()) {
                        $o_orderInProgressRecieved = new OrderInProgressRecieved();
                        $o_orderInProgressRecieved->setDistributionGivingOut($o_distributionGivingOut);
                        $o_orderInProgressRecieved->setAmount($o_orderDetail_template->getAmount());
                        $o_orderInProgressRecieved->setOrderDetail($o_orderDetail);

                        if($o_orderDetail->getMenuid()) {
                            $o_menu = $o_orderDetail->getMenu();
                            $i_menu_groupid = $o_menu->getMenuGroupid();

                            if($o_menu->getAvailabilityAmount() != null) {
                                $o_menu->setAvailabilityAmount($o_menu->getAvailabilityAmount() - $o_orderDetail_template->getAmount());

                                if($o_menu->getAvailabilityAmount() == 0) {
                                    $o_menu->setAvailabilityAmount(null);
                                    $o_menu->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                }

                                $o_menu->save();

                                // TODO Optimize performance
                                $o_orderDetailFilter = OrderDetailQuery::create()
                                                                        ->filterByDistributionFinished()
                                                                        ->useMenuQuery()
                                                                            ->filterByMenuid($o_menu->getMenuid())
                                                                        ->endUse()
                                                                        ->_or()
                                                                        ->useOrderDetailMixedWithQuery(null, Criteria::LEFT_JOIN)
                                                                            ->useMenuQuery('re', Criteria::LEFT_JOIN)
                                                                                ->filterByMenuid($o_menu->getMenuid())
                                                                            ->endUse()
                                                                        ->endUse();

                                $o_orderDetails = $o_orderDetailFilter->find();

                                foreach($o_orderDetails as $o_orderDetail) {
                                    StatusCheck::verifyAvailability($o_orderDetail->getOrderDetailid());
                                }
                            }

                            foreach($o_orderDetail->getOrderDetailExtras() as $o_orderDetailExtra) {
                                $o_menuExtra = $o_orderDetailExtra->getMenuPossibleExtra()->getMenuExtra();

                                if($o_menuExtra->getAvailabilityAmount() != null) {
                                    $o_menuExtra->setAvailabilityAmount($o_menuExtra->getAvailabilityAmount() - $o_orderDetail_template->getAmount());

                                    if($o_menuExtra->getAvailabilityAmount() == 0) {
                                        $o_menuExtra->setAvailabilityAmount(null);
                                        $o_menuExtra->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                    }

                                    $o_menuExtra->save();

                                    // TODO Optimize performance
                                    $o_orderDetailFilter = OrderDetailQuery::create()
                                                                            ->filterByDistributionFinished()
                                                                            ->useOrderDetailExtraQuery()
                                                                                ->useMenuPossibleExtraQuery()
                                                                                    ->filterByMenuExtraid($o_menuExtra->getMenuExtraid())
                                                                                ->endUse()
                                                                            ->endUse();

                                    $o_orderDetails = $o_orderDetailFilter->find();

                                    if($o_menuExtra->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {

                                        $a_ids = [];
                                        foreach($o_orderDetails as $o_orderDetail) {
                                            $a_ids[] = $o_orderDetail->getOrderDetailid();
                                        }

                                        if(!empty($a_ids))
                                            OrderDetailQuery::create()
                                                                ->filterByOrderDetailid($a_ids)
                                                                ->update(['Availabilityid' => $o_menuExtra->getAvailabilityid()]);
                                    } else {
                                        foreach($o_orderDetails as $o_orderDetail) {
                                            StatusCheck::verifyAvailability($o_orderDetail->getOrderDetailid());
                                        }
                                    }
                                }
                            }

                            foreach($o_orderDetail->getOrderDetailMixedWiths() as $o_orderDetailMixedwith) {
                                $o_menu = $o_orderDetailMixedwith->getMenu();

                                if($o_menu->getAvailabilityAmount() != null) {
                                    $o_menu->setAvailabilityAmount($o_menu->getAvailabilityAmount() - $o_orderDetail_template->getAmount());

                                    if($o_menu->getAvailabilityAmount() == 0) {
                                        $o_menu->setAvailabilityAmount(null);
                                        $o_menu->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                    }

                                    $o_menu->save();

                                    // TODO Optimize performance
                                    $o_orderDetailFilter = OrderDetailQuery::create()
                                                                            ->filterByDistributionFinished()
                                                                            ->useMenuQuery()
                                                                                ->filterByMenuid($o_menu->getMenuid())
                                                                            ->endUse()
                                                                            ->_or()
                                                                            ->useOrderDetailMixedWithQuery(null, Criteria::LEFT_JOIN)
                                                                                ->useMenuQuery('re', Criteria::LEFT_JOIN)
                                                                                    ->filterByMenuid($o_menu->getMenuid())
                                                                                ->endUse()
                                                                            ->endUse();

                                    $o_orderDetails = $o_orderDetailFilter->find();

                                    foreach($o_orderDetails as $o_orderDetail) {
                                        StatusCheck::verifyAvailability($o_orderDetail->getOrderDetailid());
                                    }
                                }
                            }
                        } else {
                            $i_menu_groupid = $o_orderDetail->getMenuGroupid();

                            if($o_orderDetail->getAvailabilityAmount() != null) {
                                $o_orderDetail->setAvailabilityAmount($o_orderDetail->getAvailabilityAmount() - $o_orderDetail_template->getAmount());

                                if($o_orderDetail->getAvailabilityAmount() == 0) {
                                    $o_orderDetail->setAvailabilityAmount(null);
                                    $o_orderDetail->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                }

                                $o_orderDetail->save();
                            }
                        }

                        $i_orderInProgressid = null;
                        foreach($o_orderInProgresses as $o_orderInProgress) {
                            if($o_orderInProgress->getMenuGroupid() == $i_menu_groupid) {
                                $i_orderInProgressid = $o_orderInProgress->getOrderInProgressid();
                                break;
                            }
                        }

                        if (!$i_orderInProgressid) {
                            throw new Exception("No order in progress found for MenuGroupid $i_menu_groupid");
                        }

                        $o_orderInProgressRecieved->setOrderInProgressid($i_orderInProgressid);
                        $o_orderInProgressRecieved->save();
                        
                        continue 2;
                    }
                }
            }

            StatusCheck::verifyOrder($o_order->getOrderid());

            $o_connection->commit();

            $this->withJson($o_distributionGivingOut->getDistributionGivingOutid());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }

    protected function GET() : void {
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $a_order = $this->getCurrentOrder();
            $a_ordersInTodo = $this->getOrdersInTodo();
            $a_orderDetailwithSpecialExtra = $this->getOrderDetailWithSpecialExtra();
            $a_menuExtras = $this->getMenuExtras();
            $i_eventPrinterid = $this->getPrinterid();

            $a_orderStatistic = $this->getOrderStatisic();

            $o_connection->commit();

            $this->withJson(['Order' => $a_order,
                             'OrdersInTodo' => $a_ordersInTodo,
                             'OrderDetailWithSpecialExtra'=> $a_orderDetailwithSpecialExtra,
                             'MenuExtras' => $a_menuExtras,
                             'OpenOrders' => $a_orderStatistic['OpenOrders'],
                             'DoneOrders' => $a_orderStatistic['DoneOrders'],
                             'NewOrders' => $a_orderStatistic['NewOrders'],
                             'Minutes' => $a_orderStatistic['Minutes'],
                             'EventPrinterid' => $i_eventPrinterid]);
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
                                            ->leftJoinWithOrderInProgressRecieved()
                                        ->endUse()
                                        ->joinWithEventTable()
                                        ->joinWithUserRelatedByUserid()
                                        ->joinOrderInProgress()
                                        ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                        ->find()
                                        ->getFirst();

        $a_orderToReturn['UserRelatedByUserid'] = $this->cleanupUserData($a_orderToReturn['UserRelatedByUserid']);

        // :TODO: fix hydration problem. This datas should be included directly in the abouve query but this doesn't work yet
        foreach($a_orderToReturn['OrderDetails'] as $i_key => &$a_orderDetail) {

            foreach($a_orderDetail['OrderInProgressRecieveds'] as $a_orderInProgressRecieved) {
                if(isset($a_orderInProgressRecieved['Amount'])) {
                    $a_orderDetail['Amount'] -= $a_orderInProgressRecieved['Amount'];
                }
            }

            if($a_orderDetail['Amount'] == 0) {
                unset($a_orderToReturn['OrderDetails'][$i_key]);
                continue;
            }

            // verify that only availably amount is displayed
            if($a_orderDetail['AvailabilityAmount'] && $a_orderDetail['AvailabilityAmount'] < $a_orderDetail['Amount'])
                $a_orderDetail['Amount'] = $a_orderDetail['AvailabilityAmount'];

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
                                                            ->filterByAvailabilityid(ORDER_AVAILABILITY_AVAILABLE)
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
                                        ->leftJoinWithOrderInProgressRecieved()
                                        ->leftJoinWithMenu()
                                    ->endUse()
                                    ->setFormatter(ModelCriteria::FORMAT_ARRAY)
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
                                            ->leftJoinWithOrderInProgressRecieved()
                                            ->leftJoinWithMenu()
                                        ->endUse()
                                        ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                        ->find();

            if(!$o_orders)
                return null;

            $a_orders = $o_orders->toArray();

            foreach($a_orders as &$a_order) {
                $i_count = 0;
                foreach($a_order['OrderDetails'] as $a_orderDetail) {
                    if( ($a_orderDetail['Menuid'] && in_array($a_orderDetail['Menu']['MenuGroupid'], $i_menuGroupids))
                        ||
                        in_array($a_orderDetail['MenuGroupid'], $i_menuGroupids)) {
                        $i_count += $a_orderDetail['Amount'];

                        foreach($a_orderDetail['OrderInProgressRecieveds'] as $a_orderInProgressRecieved) {
                            if(isset($a_orderInProgressRecieved['Amount'])) {
                                $i_count -= $a_orderInProgressRecieved['Amount'];
                            }
                        }
                    }
                }
                $a_order["OrderDetailCount"] = $i_count;
            }

            return $a_orders;
        }
    }

    private function getOrderDetailWithSpecialExtra() {
        $o_orderDetail = OrderDetailQuery::create()
                                            ->filterByMenuid()
                                            ->filterByDistributionFinished()
                                            ->filterByVerified(true)
                                            ->setFormatter(ModelCriteria::FORMAT_ARRAY)
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

    private function getPrinterid() {
        $o_user = Auth::GetCurrentUser();

        $o_distributionPlaceUser = DistributionPlaceUserQuery::create()
                                                                ->filterByUserid($o_user->getUserid())
                                                                ->useDistributionPlaceQuery()
                                                                    ->filterByEventid($o_user->getEventUser()->getEventid())
                                                                ->endUse()
                                                                ->findOne();

        return $o_distributionPlaceUser->getEventPrinterid();
    }
}