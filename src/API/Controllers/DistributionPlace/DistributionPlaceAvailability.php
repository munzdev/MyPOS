<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Menu\IMenuExtraQuery;
use API\Lib\Interfaces\Models\Menu\IMenuQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\ORM\Menu\MenuExtraQuery;
use API\Models\ORM\Ordering\OrderDetailQuery;
use Exception;
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
        $connection = $this->container->get(IConnectionInterface::class);

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
        $auth = $this->container->get(IAuth::class);
        $menuQuery = $this->container->get(IMenuQuery::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
        $user = $auth->getCurrentUser();

        $menu = $menuQuery->getByEventid($this->json['id'], $user->getEventUsers()->getFirst()->getEventid());

        if ($menu) {
            $menu->setAvailabilityid($this->json['status']);
            $menu->save();

            // TODO Optimize performance
            $orderDetails = $orderDetailQuery->getDistributionUnfinishedByMenuid($menu->getMenuid());

            if ($menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {
                $ids = [];
                foreach ($orderDetails as $orderDetail) {
                    $ids[] = $orderDetail->getOrderDetailid();
                }

                if (!empty($ids)) {
                    $orderDetailQuery->setAvailabilityidByOrderDetailIds($this->json['status'], $ids);
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
        $auth = $this->container->get(IAuth::class);
        $menuExtraQuery = $this->container->get(IMenuExtraQuery::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
        $user = $auth->getCurrentUser();

        $menuExtra = $menuExtraQuery->getByEventid($this->json['id'], $user->getEventUsers()->getFirst()->getEventid());

        if ($menuExtra) {
            $menuExtra->setAvailabilityid($this->json['status']);
            $menuExtra->save();

            // TODO Optimize performance
            $orderDetails = $orderDetailQuery->getDistributionUnfinishedByMenuExtraid($menuExtra->getMenuExtraid());

            if ($menuExtra->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {
                $ids = [];
                foreach ($orderDetails as $orderDetail) {
                    $ids[] = $orderDetail->getOrderDetailid();
                }

                if (!empty($ids)) {
                    $orderDetailQuery->setAvailabilityidByOrderDetailIds($this->json['status'], $ids);
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
        $auth = $this->container->get(IAuth::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
        $user = $auth->getCurrentUser();

        $orderDetail = $orderDetailQuery->getByEventid($this->json['id'], $user->getEventUsers()->getFirst()->getEventid());

        if ($orderDetail) {
            $orderDetail->setAvailabilityid($this->json['status']);
            $orderDetail->save();
        }
    }
}
