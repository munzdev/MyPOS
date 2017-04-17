<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\ORM\Menu\MenuExtraQuery;
use API\Models\ORM\Menu\MenuQuery;
use API\Models\ORM\Ordering\OrderDetailQuery;
use Exception;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;
use const API\USER_ROLE_DISTRIBUTION_SET_AVAILABILITY;

class DistributionPlaceAvailability extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['POST' => USER_ROLE_DISTRIBUTION_SET_AVAILABILITY];

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'type' => v::alnum()->length(1),
            'id' => v::intVal()->length(1),
            'status' => v::intVal()->length(1),
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->json);
    }

    protected function post() : void
    {
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            if ($this->json['type'] == 'menu') {
                $this->setMenu();
            }

            if ($this->json['type'] == 'extra') {
                $this->setExtra();
            }

            if ($this->json['type'] == 'specialExtra') {
                $this->setSpecialExtra();
            }

            $connection->commit();

            $this->withJson(true);
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function setMenu()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menu = MenuQuery::create()
                            ->useMenuGroupQuery()
                               ->useMenuTypeQuery()
                                   ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                               ->endUse()
                            ->endUse()
                            ->filterByMenuid($this->json['id'])
                            ->findOne();

        if ($menu) {
            $menu->setAvailabilityid($this->json['status']);
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

            if ($menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {
                $ids = [];
                foreach ($orderDetails as $orderDetail) {
                    $ids[] = $orderDetail->getOrderDetailid();
                }

                if (!empty($ids)) {
                    OrderDetailQuery::create()
                                        ->filterByOrderDetailid($ids)
                                        ->update(['Availabilityid' => $this->json['status']]);
                }
            } else {
                foreach ($orderDetails as $orderDetail) {
                    StatusCheck::verifyAvailability($orderDetail->getOrderDetailid());
                }
            }
        }
    }

    private function setExtra()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menuExtra = MenuExtraQuery::create()
                                    ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                    ->filterByMenuExtraid($this->json['id'])
                                    ->findOne();

        if ($menuExtra) {
            $menuExtra->setAvailabilityid($this->json['status']);
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
                                        ->update(['Availabilityid' => $this->json['status']]);
                }
            } else {
                foreach ($orderDetails as $orderDetail) {
                    StatusCheck::verifyAvailability($orderDetail->getOrderDetailid());
                }
            }
        }
    }

    private function setSpecialExtra()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $orderDetail = OrderDetailQuery::create()
                                        ->useOrderQuery()
                                            ->useEventTableQuery()
                                                ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                            ->endUse()
                                        ->endUse()
                                        ->filterByOrderDetailid($this->json['id'])
                                        ->findOne();
        if ($orderDetail) {
            $orderDetail->setAvailabilityid($this->json['status']);
            $orderDetail->save();
        }
    }
}
