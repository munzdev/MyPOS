<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Menu\IMenuQuery;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressQuery;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecieved;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\ORM\DistributionPlace\DistributionPlaceUserQuery;
use API\Models\ORM\Menu\MenuExtraQuery;
use API\Models\ORM\OIP\DistributionGivingOutQuery;
use API\Models\ORM\Ordering\OrderDetailQuery;
use API\Models\ORM\Ordering\OrderQuery;
use DateTime;
use Exception;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Slim\App;
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
        $connection = $this->container->get(IConnectionInterface::class);

        try {
            $connection->beginTransaction();
            $orderTemplate = $this->container->get(IOrder::class);
            $orderQuery = $this->container->get(IOrderQuery::class);
            $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
            $orderInProgressQuery = $this->container->get(IOrderInProgressQuery::class);

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $orderTemplate);

            $order = $orderQuery->findPKWithAllOrderDetails($orderTemplate->getOrderid());
            $orderInProgresses = $orderInProgressQuery->getActiveByUserAndOrder($user, $order);

            $distributionGivingOut = $this->container->get(IDistributionGivingOut::class);
            $distributionGivingOut->setDate(new DateTime());
            $distributionGivingOut->save();

            foreach ($orderTemplate->getOrderDetails() as $orderDetailTemplate) {
                foreach ($order->getOrderDetails() as $orderDetail) {
                    if ($orderDetailTemplate->getOrderDetailid() == $orderDetail->getOrderDetailid()) {
                        $orderInProgressRecieved = $this->container->get(IOrderInProgressRecieved::class);
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
                                $orderDetailsToCheck = $orderDetailQuery->getDistributionUnfinishedByMenuid($menu->getMenuid());

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
                                    $orderDetails = $orderDetailQuery->getDistributionUnfinishedByMenuExtraid($menuExtra->getMenuExtraid());

                                    if ($menuExtra->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {
                                        $ids = [];
                                        foreach ($orderDetails as $orderDetail) {
                                            $ids[] = $orderDetail->getOrderDetailid();
                                        }

                                        if (!empty($ids)) {
                                            $orderDetailQuery->setAvailabilityidByOrderDetailIds($menuExtra->getAvailabilityid(), $ids);
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
                                    $orderDetailsToCheck = $orderDetailQuery->getDistributionUnfinishedByMenuid($menu->getMenuid());

                                    foreach ($orderDetailsToCheck as $orderDetail) {
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
        $connection = $this->container->get(IConnectionInterface::class);
        $orderDetailQuery = $this->container->get(IOrderDetail::class);
        $menuExtraQuery = $this->container->get(IMenuExtraQuery::class);
        $auth = $this->app->getContainer()->get(IAuth::class);

        try {
            $connection->beginTransaction();

            $user = $auth->getCurrentUser();
            $order = $this->getCurrentOrder();
            $ordersInTodo = $this->getOrdersInTodo();
            $orderDetailwithSpecialExtra = $orderDetailQuery->getVerifiedDistributionUnfinishedWithSpecialExtras();
            $menuExtras = $menuExtraQuery->findByEventid($user->getEventUsers()->getFirst()->getEventid())->toArray();
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
        $orderInProgressQuery = $this->app->getContainer()->get(IOrderInProgressQuery::class);

        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];
        $assist = $config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'];

        $orderQuery = $this->container->get(IOrderQuery::class);
        $menuQuery = $this->container->get(IMenuQuery::class);
        $orderInProgressQuery = $this->container->get(IOrderInProgress::class);
        $distributionPlaceGroupQuery = $this->container->get(IDistributionPlaceGroupQuery::class);

        //-- First try fetch allready started order to handle, witch is not finished yet
        //-- (like page reloaded or the status of a product has changed back to available)
        $orderInProgress = $orderInProgressQuery->getOpenOrderInProgress($user->getUserid(),
                                                                         $user->getEventUsers()->getFirst()->getEventid());


        //-- if no existing progress order found, take a new order from priority list
        if (!$orderInProgress) {
            $order = $orderQuery->getNextForDistribution($user->getUserid(),
                                                         $user->getEventUsers()->getFirst()->getEventid(),
                                                         true);

            //-- Secondly try to find an order, which belongs to an other distribution place tables but
            //-- has the same menu_groupid and can also be handeled by the current user
            //-- this lets ordes be done faster
            if (!$order && $assist) {
                $order = $orderQuery->getNextForDistribution($user->getUserid(),
                                                             $user->getEventUsers()->getFirst()->getEventid(),
                                                             false);
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

            $distributionPlaceGroups = $distributionPlaceGroupQuery->getByMenuGroupsAndUser($menuGroupids,
                                                                                            $user->getEventUsers()->getFirst()->getEventid(),
                                                                                            $user->getUserid());

            foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
                $orderInProgress = $this->container->get(IOrderInProgress::class);
                $orderInProgress->setOrder($order);
                $orderInProgress->setUser($user);
                $orderInProgress->setMenuGroupid($distributionPlaceGroup->getMenuGroupid());
                $orderInProgress->setBegin(new DateTime());
                $orderInProgress->save();
            }
        } else {
            $order = $orderInProgress->getOrder();
        }

        $orderInProgresses = $orderInProgressQuery->getActiveByUserAndOrder($user, $order);

        $menuGroupids = [];
        foreach ($orderInProgresses as $orderInProgress) {
            $menuGroupids[] = $orderInProgress->getMenuGroupid();
        }

        $orderToReturn = $orderQuery->getInfosForDistribution($order->getOrderid(), $menuGroupids)->toArray();

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

                $menu = $menuQuery->findPk($orderDetailMixedWith['Menuid'])->toArray();

                $orderDetailMixedWith['Menu'] = $menu;
            }
        }

        return $orderToReturn;
    }

    private function getOrdersInTodo()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $distributionPlaceGroupQuery = $this->app->getContainer()->get(IDistributionPlaceGroupQuery::class);
        $orderInProgressQuery = $this->app->getContainer()->get(IOrderInProgressQuery::class);
        $orderQuery = $this->app->getContainer()->get(IOrderQuery::class);

        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];
        $assist = $config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'];

        $orderInProgress = $orderInProgressQuery->getOpenOrderInProgress($user->getUserid(),
                                                                         $user->getEventUsers()->getFirst()->getEventid());

        if ($orderInProgress) {
            $inProgressOrder = $orderInProgress->getOrder();
            $distributionPlaceGroups = $distributionPlaceGroupQuery->getUserDistributionPlaceGroups($user->getEventUsers()->getFirst()->getEventid(),
                                                                                                    $user->getUserid());

            $menuGroupids = [];
            foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
                $menuGroupids[] = $distributionPlaceGroup->getMenuGroupid();
            }

            //-- first try to find a order that is associated to the distribution place tables
            $orders = $orderQuery->getNextForTodoList($inProgressOrder->getOrderid(),
                                                      $user->getUserid(),
                                                      $user->getEventUsers()->getFirst()->getEventid(),
                                                      true,
                                                      $config['App']['Distribution']['AmountDisplayedInTodoList']);

            //-- Secondly try to find an order, which belongs to an other distribution place tables but
            //-- has the same menu_groupid and can also be handeled by the current user
            //-- this lets ordes be done faster
            if (!$orders && $assist) {
                 $orders = $orderQuery->getNextForTodoList($inProgressOrder->getOrderid(),
                                                           $user->getUserid(),
                                                           $user->getEventUsers()->getFirst()->getEventid(),
                                                           false,
                                                           $config['App']['Distribution']['AmountDisplayedInTodoList']);
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

    private function getOrderStatisic()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $distributionPlaceGroupQuery = $this->app->getContainer()->get(IDistributionPlaceGroupQuery::class);
        $distributionGivingOutQuery = $this->app->getContainer()->get(IDistributionGivingOut::class);
        $orderQuery = $this->app->getContainer()->get(IOrderQuery::class);

        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];
        $minutes = $config['App']['Distribution']['OrderProgressTimeRangeMinutes'];

        $distributionPlaceGroups = $distributionPlaceGroupQuery->getUserDistributionPlaceGroups($user->getEventUsers()->getFirst()->getEventid(),
                                                                                                $user->getUserid());

        $eventTableids = [];
        $menuGroupids = [];
        foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
            $menuGroupids[] = $distributionPlaceGroup->getMenuGroupid();
            foreach ($distributionPlaceGroup->getDistributionPlaceTables() as $distributionPlaceTable) {
                $eventTableids[] = $distributionPlaceTable->getEventTableid();
            }
        }

        $eventTableids = array_unique($eventTableids);

        $doneOrder = $distributionGivingOutQuery->getDoneOrdersCount($user->getUserid(), $minutes);
        $openOrder = $orderQuery->getOpenOrdersCount($menuGroupids, $eventTableids);
        $newOrder = $orderQuery->getOpenOrdersCount($menuGroupids, $eventTableids, $minutes);

        return ['OpenOrders' => $openOrder,
                'DoneOrders' => $doneOrder,
                'NewOrders' => $newOrder,
                'Minutes' => $minutes];
    }

    private function getPrinterid()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $distributionPlaceUserQuery = $this->app->getContainer()->get(IDistributionPlaceUserQuery::class);
        $user = $auth->getCurrentUser();

        $distributionPlaceUser = $distributionPlaceUserQuery->getByUser($user->getUserid(),
                                                                        $user->getEventUsers()->getFirst()->getEventid());

        if (!$distributionPlaceUser) {
            return;
        }

        return $distributionPlaceUser->getEventPrinterid();
    }
}
