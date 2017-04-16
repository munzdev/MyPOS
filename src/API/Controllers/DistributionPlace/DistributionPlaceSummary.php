<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\ORM\OIP\Base\OrderInProgressQuery;
use API\Models\ORM\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Slim\App;
use const API\ORDER_AVAILABILITY_AVAILABLE;
use const API\USER_ROLE_DISTRIBUTION_PREVIEW;

class DistributionPlaceSummary extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_DISTRIBUTION_PREVIEW];

        $app->getContainer()['db'];
    }

    protected function get() : void
    {        
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $config = $this->app->getContainer()['settings'];

        $orderInProgress = $this->getOpenOrderInProgress();

        $orderidOfInProgress = null;
        if ($orderInProgress) {
            $orderidOfInProgress = $orderInProgress->getOrder()->getOrderid();
        }
        
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
                                    $config['App']['Distribution']['AmountOrdersToPreShow']
                                )
                                ->_if($orderidOfInProgress)
                                    ->where('`order`.orderid <> ' . $orderidOfInProgress)
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
                                ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                ->find();

        if (!$orders) {
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
}
