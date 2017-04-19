<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\SecurityController;
use Slim\App;
use const API\USER_ROLE_DISTRIBUTION_PREVIEW;

class DistributionPlaceSummary extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_DISTRIBUTION_PREVIEW];

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $distributionPlaceGroupQuery = $this->container->get(IDistributionPlaceGroupQuery::class);
        $orderInProgressQuery = $this->container->get(IOrderInProgressQuery::class);
        $orderQuery = $this->container->get(IOrderQuery::class);

        $user = $auth->getCurrentUser();
        $config = $this->container->get('settings');

        $orderInProgress = $orderInProgressQuery->getOpenOrderInProgress($user->getUserid(),
                                                                         $user->getEventUsers()->getFirst()->getEventid());

        $orderidOfInProgress = 0;
        if ($orderInProgress) {
            $orderidOfInProgress = $orderInProgress->getOrder()->getOrderid();
        }

        $distributionPlaceGroups = $distributionPlaceGroupQuery->getUserDistributionPlaceGroups($user->getEventUsers()->getFirst()->getEventid(),
                                                                                                $user->getUserid());

        $menuGroupids = [];
        foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
            $menuGroupids[] = $distributionPlaceGroup->getMenuGroupid();
        }

        //-- first try to find a order that is associated to the distribution place tables
        $orders = $orderQuery->getNextForTodoList($user->getUserid(),
                                                  $user->getEventUsers()->getFirst()->getEventid(),
                                                  true,
                                                  $config['App']['Distribution']['AmountOrdersToPreShow'],
                                                  $orderidOfInProgress);

        if ($orders->isEmpty()) {
            $this->withJson([]);
            return;
        }

        $orders = $orders->toArray();
        $orderDetailsToReturn = [];

        foreach ($orders as &$order) {
            foreach ($order['OrderDetails'] as $orderDetail) {
                if (($orderDetail['Menuid'] && in_array($orderDetail['Menu']['MenuGroupid'], $menuGroupids))
                    || in_array($orderDetail['MenuGroupid'], $menuGroupids)
                ) {
                    foreach ($orderDetail['OrderInProgressRecieveds'] as $orderInProgressRecieved) {
                        if (isset($orderInProgressRecieved['Amount'])) {
                            $orderDetail['Amount'] -= $orderInProgressRecieved['Amount'];
                        }
                    }

                    if ($orderDetail['Amount'] == 0) {
                        continue;
                    }

                    $index = $orderDetail['Menuid'] . '-' . $orderDetail['MenuGroupid'] . '-' . $orderDetail['ExtraDetail'] . '-';

                    foreach ($orderDetail['OrderDetailExtras'] as $orderDetailExtra) {
                        if (empty($orderDetailExtra['OrderDetailid'])) {
                            $orderDetail['OrderDetailExtras'] = [];
                            continue;
                        }

                        $index .= $orderDetailExtra['MenuPossibleExtraid'];
                    }

                    $index .= '-';

                    foreach ($orderDetail['OrderDetailMixedWiths'] as $orderDetailMixedWith) {
                        if (empty($orderDetailMixedWith)) {
                            $orderDetail['OrderDetailMixedWiths'] = [];
                            continue;
                        }

                        $index .= $orderDetailMixedWith['Menuid'];
                    }

                    if (isset($orderDetailsToReturn[$index])) {
                        $orderDetailsToReturn[$index]['Amount'] += $orderDetail['Amount'];
                        continue;
                    }

                    $orderDetailsToReturn[$index] = $orderDetail;
                }
            }
        }

        $this->withJson(array_values($orderDetailsToReturn));
    }
}
