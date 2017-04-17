<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\ORM\DistributionPlace\DistributionPlaceUserQuery;
use API\Models\ORM\Menu\Base\MenuQuery;
use API\Models\ORM\Menu\MenuExtraQuery;
use API\Models\ORM\OIP\Base\OrderInProgressQuery;
use API\Models\ORM\OIP\DistributionGivingOut;
use API\Models\ORM\OIP\DistributionGivingOutQuery;
use API\Models\ORM\OIP\OrderInProgress;
use API\Models\ORM\OIP\OrderInProgressRecieved;
use API\Models\ORM\Ordering\Order;
use API\Models\ORM\Ordering\OrderDetailQuery;
use API\Models\ORM\Ordering\OrderQuery;
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
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_DISTRIBUTION_OVERVIEW,
                            'PUT' => USER_ROLE_DISTRIBUTION_OVERVIEW];

        $this->container->get(IConnectionInterface::class);
    }

    protected function put() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();
            $orderTemplate = new Order();

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $orderTemplate);

            $order = OrderQuery::create()
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
                                   ->findPk($orderTemplate->getOrderid());

            $orderInProgresses = OrderInProgressQuery::create()
                                        ->filterByUser($user)
                                        ->filterByOrder($order)
                                        ->filterByDone()
                                        ->find();

            $distributionGivingOut = new DistributionGivingOut();
            $distributionGivingOut->setDate(new DateTime());
            $distributionGivingOut->save();

            foreach ($orderTemplate->getOrderDetails() as $orderDetailTemplate) {
                foreach ($order->getOrderDetails() as $orderDetail) {
                    if ($orderDetailTemplate->getOrderDetailid() == $orderDetail->getOrderDetailid()) {
                        $orderInProgressRecieved = new OrderInProgressRecieved();
                        $orderInProgressRecieved->setDistributionGivingOut($distributionGivingOut);
                        $orderInProgressRecieved->setAmount($orderDetailTemplate->getAmount());
                        $orderInProgressRecieved->setOrderDetail($orderDetail);

                        if ($orderDetail->getMenuid()) {
                            $menu = $orderDetail->getMenu();
                            $menuGroupid = $menu->getMenuGroupid();

                            if ($menu->getAvailabilityAmount() != null) {
                                $menu->setAvailabilityAmount($menu->getAvailabilityAmount() - $orderDetailTemplate->getAmount());

                                if ($menu->getAvailabilityAmount() == 0) {
                                    $menu->setAvailabilityAmount(null);
                                    $menu->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                }

                                $menu->save();

                                // TODO Optimize performance
                                $orderDetailFilter = OrderDetailQuery::create()
                                                                        ->filterByDistributionFinished()
                                                                        ->useMenuQuery()
                                                                            ->filterByMenuid($menu->getMenuid())
                                                                        ->endUse()
                                                                        ->_or()
                                                                        ->useOrderDetailMixedWithQuery(null, Criteria::LEFT_JOIN)
                                                                            ->useMenuQuery('re', Criteria::LEFT_JOIN)
                                                                                ->filterByMenuid($menu->getMenuid())
                                                                            ->endUse()
                                                                        ->endUse();

                                $orderDetailsToCheck = $orderDetailFilter->find();

                                foreach ($orderDetailsToCheck as $orderDetailToCheck) {
                                    StatusCheck::verifyAvailability($orderDetailToCheck->getOrderDetailid());
                                }
                            }

                            foreach ($orderDetail->getOrderDetailExtras() as $orderDetailExtra) {
                                $menuExtra = $orderDetailExtra->getMenuPossibleExtra()->getMenuExtra();

                                if ($menuExtra->getAvailabilityAmount() != null) {
                                    $menuExtra->setAvailabilityAmount($menuExtra->getAvailabilityAmount() - $orderDetailTemplate->getAmount());

                                    if ($menuExtra->getAvailabilityAmount() == 0) {
                                        $menuExtra->setAvailabilityAmount(null);
                                        $menuExtra->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                    }

                                    $menuExtra->save();

                                    // TODO Optimize performance
                                    $orderDetailFilter = OrderDetailQuery::create()
                                                                            ->filterByDistributionFinished()
                                                                            ->useOrderDetailExtraQuery()
                                                                                ->useMenuPossibleExtraQuery()
                                                                                    ->filterByMenuExtraid($menuExtra->getMenuExtraid())
                                                                                ->endUse()
                                                                            ->endUse();

                                    $orderDetails = $orderDetailFilter->find();

                                    if ($menuExtra->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {
                                        $ids = [];
                                        foreach ($orderDetails as $orderDetail) {
                                            $ids[] = $orderDetail->getOrderDetailid();
                                        }

                                        if (!empty($ids)) {
                                            OrderDetailQuery::create()
                                                                ->filterByOrderDetailid($ids)
                                                                ->update(['Availabilityid' => $menuExtra->getAvailabilityid()]);
                                        }
                                    } else {
                                        foreach ($orderDetails as $orderDetail) {
                                            StatusCheck::verifyAvailability($orderDetail->getOrderDetailid());
                                        }
                                    }
                                }
                            }

                            foreach ($orderDetail->getOrderDetailMixedWiths() as $orderDetailMixedwith) {
                                $menu = $orderDetailMixedwith->getMenu();

                                if ($menu->getAvailabilityAmount() != null) {
                                    $menu->setAvailabilityAmount($menu->getAvailabilityAmount() - $orderDetailTemplate->getAmount());

                                    if ($menu->getAvailabilityAmount() == 0) {
                                        $menu->setAvailabilityAmount(null);
                                        $menu->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                    }

                                    $menu->save();

                                    // TODO Optimize performance
                                    $orderDetailFilter = OrderDetailQuery::create()
                                                                            ->filterByDistributionFinished()
                                                                            ->useMenuQuery()
                                                                                ->filterByMenuid($menu->getMenuid())
                                                                            ->endUse()
                                                                            ->_or()
                                                                            ->useOrderDetailMixedWithQuery(null, Criteria::LEFT_JOIN)
                                                                                ->useMenuQuery('re', Criteria::LEFT_JOIN)
                                                                                    ->filterByMenuid($menu->getMenuid())
                                                                                ->endUse()
                                                                            ->endUse();

                                    $orderDetails = $orderDetailFilter->find();

                                    foreach ($orderDetails as $orderDetail) {
                                        StatusCheck::verifyAvailability($orderDetail->getOrderDetailid());
                                    }
                                }
                            }
                        } else {
                            $menuGroupid = $orderDetail->getMenuGroupid();

                            if ($orderDetail->getAvailabilityAmount() != null) {
                                $orderDetail->setAvailabilityAmount($orderDetail->getAvailabilityAmount() - $orderDetailTemplate->getAmount());

                                if ($orderDetail->getAvailabilityAmount() == 0) {
                                    $orderDetail->setAvailabilityAmount(null);
                                    $orderDetail->setAvailabilityid(ORDER_AVAILABILITY_DELAYED);
                                }

                                $orderDetail->save();
                            }
                        }

                        $orderInProgressid = null;
                        foreach ($orderInProgresses as $orderInProgress) {
                            if ($orderInProgress->getMenuGroupid() == $menuGroupid) {
                                $orderInProgressid = $orderInProgress->getOrderInProgressid();
                                break;
                            }
                        }

                        if (!$orderInProgressid) {
                            throw new Exception("No order in progress found for MenuGroupid $menuGroupid");
                        }

                        $orderInProgressRecieved->setOrderInProgressid($orderInProgressid);
                        $orderInProgressRecieved->save();

                        continue 2;
                    }
                }
            }

            StatusCheck::verifyOrder($order->getOrderid());

            $connection->commit();

            $this->withJson($distributionGivingOut->getDistributionGivingOutid());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    protected function get() : void
    {
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $order = $this->getCurrentOrder();
            $ordersInTodo = $this->getOrdersInTodo();
            $orderDetailwithSpecialExtra = $this->getOrderDetailWithSpecialExtra();
            $menuExtras = $this->getMenuExtras();
            $eventPrinterid = $this->getPrinterid();

            $orderStatistic = $this->getOrderStatisic();

            $connection->commit();

            $this->withJson(
                ['Order' => $order,
                'OrdersInTodo' => $ordersInTodo,
                'OrderDetailWithSpecialExtra'=> $orderDetailwithSpecialExtra,
                'MenuExtras' => $menuExtras,
                'OpenOrders' => $orderStatistic['OpenOrders'],
                'DoneOrders' => $orderStatistic['DoneOrders'],
                'NewOrders' => $orderStatistic['NewOrders'],
                'Minutes' => $orderStatistic['Minutes'],
                'EventPrinterid' => $eventPrinterid]
            );
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function getCurrentOrder()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];
        $assist = $config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'];

        //-- First try fetch allready started order to handle, witch is not finished yet
        //-- (like page reloaded or the status of a product has changed back to available)
        $orderInProgress = $this->getOpenOrderInProgress();

        //-- if no existing progress order found, take a new order from priority list
        if (!$orderInProgress) {
            //-- first try to find a order that is associated to the distribution place tables
            $order = OrderQuery::create()
                                ->getNextForDistribution(
                                    $user->getUserid(),
                                    $user->getEventUsers()->getFirst()->getEventid(),
                                    true
                                )
                                ->joinWithOrderDetail()
                                ->useOrderDetailQuery()
                                    ->leftJoinWithMenu()
                                ->endUse()
                                ->find()
                                ->getFirst();

            //-- Secondly try to find an order, which belongs to an other distribution place tables but
            //-- has the same menu_groupid and can also be handeled by the current user
            //-- this lets ordes be done faster
            if (!$order && $assist) {
                $order = OrderQuery::create()
                                    ->getNextForDistribution(
                                        $user->getUserid(),
                                        $user->getEventUsers()->getFirst()->getEventid(),
                                        false
                                    )
                                    ->joinWithOrderDetail()
                                    ->useOrderDetailQuery()
                                        ->leftJoinWithMenu()
                                    ->endUse()
                                    ->find()
                                    ->getFirst();
            }

            if (!$order) {
                return null;
            }

            $menuGroupids = [];

            foreach ($order->getOrderDetails() as $orderDetail) {
                if ($orderDetail->getMenuid()) {
                    $menuGroupids[] = $orderDetail->getMenu()->getMenuGroupid();
                } elseif ($orderDetail->getVerified()) {
                    $menuGroupids[] = $orderDetail->getMenuGroupid();
                }
            }

            $menuGroupids = array_unique($menuGroupids);

            $distributionPlaceGroups = DistributionPlaceGroupQuery::create()
                                                                    ->filterByMenuGroupid($menuGroupids)
                                                                    ->useDistributionPlaceQuery()
                                                                        ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                                                        ->useDistributionPlaceUserQuery()
                                                                            ->filterByUserid($user->getUserid())
                                                                        ->endUse()
                                                                    ->endUse()
                                                                    ->find();

            foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
                $orderInProgress = new OrderInProgress();
                $orderInProgress->setOrder($order);
                $orderInProgress->setUser($user);
                $orderInProgress->setMenuGroupid($distributionPlaceGroup->getMenuGroupid());
                $orderInProgress->setBegin(new DateTime());
                $orderInProgress->save();
            }
        } else {
            $order = $orderInProgress->getOrder();
        }

        $orderInProgresses = OrderInProgressQuery::create()
                                                    ->filterByOrder($order)
                                                    ->filterByUser($user)
                                                    ->filterByDone()
                                                    ->find();

        $menuGroupids = [];
        foreach ($orderInProgresses as $orderInProgress) {
            $menuGroupids[] = $orderInProgress->getMenuGroupid();
        }

        $orderToReturn = OrderQuery::create()
                                    ->filterByOrderid($order->getOrderid())
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
                                    ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                    ->find()
                                    ->getFirst();

        $orderToReturn['UserRelatedByUserid'] = $this->cleanupUserData($orderToReturn['UserRelatedByUserid']);

        // :TODO: fix hydration problem. This datas should be included directly in the abouve query but this doesn't work yet
        foreach ($orderToReturn['OrderDetails'] as $key => &$orderDetail) {
            foreach ($orderDetail['OrderInProgressRecieveds'] as $orderInProgressRecieved) {
                if (isset($orderInProgressRecieved['Amount'])) {
                    $orderDetail['Amount'] -= $orderInProgressRecieved['Amount'];
                }
            }

            if ($orderDetail['Amount'] == 0) {
                unset($orderToReturn['OrderDetails'][$key]);
                continue;
            }

            // verify that only availably amount is displayed
            if ($orderDetail['AvailabilityAmount'] && $orderDetail['AvailabilityAmount'] < $orderDetail['Amount']) {
                $orderDetail['Amount'] = $orderDetail['AvailabilityAmount'];
            }

            foreach ($orderDetail['OrderDetailMixedWiths'] as &$orderDetailMixedWith) {
                if (empty($orderDetailMixedWith['Menuid'])) {
                    continue;
                }

                $menu = MenuQuery::create()
                                    ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                    ->findPk($orderDetailMixedWith['Menuid']);

                $orderDetailMixedWith['Menu'] = $menu;
            }
        }

        return $orderToReturn;
    }

    private function getOpenOrderInProgress()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $ordersInProgress = OrderInProgressQuery::create()
                                                    ->filterByUser($user)
                                                    ->filterByDone()
                                                    ->useOrderQuery()
                                                        ->useEventTableQuery()
                                                            ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                                        ->endUse()
                                                        ->useOrderDetailQuery()
                                                            ->filterByAvailabilityid(ORDER_AVAILABILITY_AVAILABLE)
                                                        ->endUse()
                                                        ->orderByPriority()
                                                    ->endUse()
                                                    ->joinWithOrder()
                                                    ->find()
                                                    ->getFirst();

        return $ordersInProgress;
    }

    private function getMyDistributionPlaceGroups()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $distributionPlaceGroups = DistributionPlaceGroupQuery::create()
                                                                    ->useDistributionPlaceQuery()
                                                                        ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                                                        ->useDistributionPlaceUserQuery()
                                                                            ->filterByUserid($user->getUserid())
                                                                        ->endUse()
                                                                    ->endUse()
                                                                    ->joinWithDistributionPlaceTable()
                                                                    ->find();

        return $distributionPlaceGroups;
    }

    private function getOrdersInTodo()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];
        $assist = $config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'];

        $orderInProgress = $this->getOpenOrderInProgress();

        if ($orderInProgress) {
            $inProgressOrder = $orderInProgress->getOrder();
            $distributionPlaceGroups = $this->getMyDistributionPlaceGroups();

            $menuGroupids = [];
            foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
                $menuGroupids[] = $distributionPlaceGroup->getMenuGroupid();
            }

            //-- first try to find a order that is associated to the distribution place tables
            $orders = OrderQuery::create()
                                    ->getNextForDistribution(
                                        $user->getUserid(),
                                        $user->getEventUsers()->getFirst()->getEventid(),
                                        true,
                                        $config['App']['Distribution']['AmountDisplayedInTodoList'] + 1
                                    )
                                    ->where('`order`.orderid <> ' . $inProgressOrder->getOrderid())
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
            if (!$orders && $assist) {
                $orders = OrderQuery::create()
                                        ->getNextForDistribution(
                                            $user->getUserid(),
                                            $user->getEventUsers()->getFirst()->getEventid(),
                                            false,
                                            $config['App']['Distribution']['AmountDisplayedInTodoList'] + 1
                                        )
                                        ->where('`order`.orderid <> ' . $inProgressOrder->getOrderid())
                                        ->joinWithOrderDetail()
                                        ->joinWithEventTable()
                                        ->useOrderDetailQuery()
                                            ->leftJoinWithOrderInProgressRecieved()
                                            ->leftJoinWithMenu()
                                        ->endUse()
                                        ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                        ->find();
            }

            if (!$orders) {
                return null;
            }

            $orders = $orders->toArray();

            foreach ($orders as &$order) {
                $count = 0;
                foreach ($order['OrderDetails'] as $orderDetail) {
                    if (($orderDetail['Menuid'] && in_array($orderDetail['Menu']['MenuGroupid'], $menuGroupids))
                        || in_array($orderDetail['MenuGroupid'], $menuGroupids)
                    ) {
                        $count += $orderDetail['Amount'];

                        foreach ($orderDetail['OrderInProgressRecieveds'] as $orderInProgressRecieved) {
                            if (isset($orderInProgressRecieved['Amount'])) {
                                $count -= $orderInProgressRecieved['Amount'];
                            }
                        }
                    }
                }
                $order["OrderDetailCount"] = $count;
            }

            return $orders;
        }
    }

    private function getOrderDetailWithSpecialExtra()
    {
        $orderDetail = OrderDetailQuery::create()
                                        ->filterByMenuid()
                                        ->filterByDistributionFinished()
                                        ->filterByVerified(true)
                                        ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                        ->find();

        return $orderDetail->toArray();
    }

    private function getOrderStatisic()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];
        $minutes = $config['App']['Distribution']['OrderProgressTimeRangeMinutes'];

        $distributionPlaceGroups = $this->getMyDistributionPlaceGroups();

        $eventTableids = [];
        $menuGroupids = [];
        foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
            $menuGroupids[] = $distributionPlaceGroup->getMenuGroupid();
            foreach ($distributionPlaceGroup->getDistributionPlaceTables() as $distributionPlaceTable) {
                $eventTableids[] = $distributionPlaceTable->getEventTableid();
            }
        }

        $eventTableids = array_unique($eventTableids);

        $doneOrder = DistributionGivingOutQuery::create()
                                                ->useOrderInProgressRecievedQuery()
                                                    ->useOrderInProgressQuery()
                                                        ->filterByUser($user)
                                                    ->endUse()
                                                ->endUse()
                                                ->filterByDate(['min' => new DateTime("-$minutes minutes")])
                                                ->count();

        $openOrderFilter = OrderQuery::create()
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
                                        ->filterByCancellation();

        $openOrder = $openOrderFilter->count();

        $newOrder = $openOrderFilter->filterByOrdertime(['min' => new DateTime("-$minutes minutes")])
                                    ->count();

        return ['OpenOrders' => $openOrder,
                'DoneOrders' => $doneOrder,
                'NewOrders' => $newOrder,
                'Minutes' => $minutes];
    }

    private function getMenuExtras()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menuExtras = MenuExtraQuery::create()
                                        ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                        ->find();

        return $menuExtras->toArray();
    }

    private function getPrinterid()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $distributionPlaceUser = DistributionPlaceUserQuery::create()
                                                            ->filterByUserid($user->getUserid())
                                                            ->useDistributionPlaceQuery()
                                                                ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                                            ->endUse()
                                                            ->findOne();

        if (!$distributionPlaceUser) {
            return;
        }

        return $distributionPlaceUser->getEventPrinterid();
    }
}
