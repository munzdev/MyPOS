<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IStatusCheck;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Menu\IMenuExtraQuery;
use API\Lib\Interfaces\Models\Menu\IMenuQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\SecurityController;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_DISTRIBUTION_SET_AVAILABILITY;

class DistributionPlaceAmount extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['POST' => USER_ROLE_DISTRIBUTION_SET_AVAILABILITY];
    }

    public function any() : void
    {
        $validators = array(
            'type' => v::alnum()->length(1),
            'id' => v::intVal()->length(1),
            'amount' => v::intVal()->length(1),
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->json);
    }

    protected function post() : void
    {
        $connection = $this->container->get(IConnectionInterface::class);

        try {
            $connection->beginTransaction();

            if ($this->json['amount'] == '0') {
                $this->json['amount'] = null;
            }

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
        $statusCheck = $this->container->get(IStatusCheck::class);
        $menuQuery = $this->container->get(IMenuQuery::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
        $user = $auth->getCurrentUser();

        $menu = $menuQuery->getByEventid($this->json['id'], $user->getEventUsers()->getFirst()->getEventid());

        if ($menu) {
            $menu->setAvailabilityAmount($this->json['amount']);
            $menu->save();

            // TODO Optimize performance
            $orderDetails = $orderDetailQuery->getDistributionUnfinishedByMenuid($menu->getMenuid());

            foreach ($orderDetails as $orderDetail) {
                $statusCheck->verifyAvailability($orderDetail->getOrderDetailid());
            }
        }
    }

    private function setExtra()
    {
        $auth = $this->container->get(IAuth::class);
        $statusCheck = $this->container->get(IStatusCheck::class);
        $menuExtraQuery = $this->container->get(IMenuExtraQuery::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
        $user = $auth->getCurrentUser();

        $menuExtra = $menuExtraQuery->getByEventid($this->json['id'], $user->getEventUsers()->getFirst()->getEventid());

        if ($menuExtra) {
            $menuExtra->setAvailabilityAmount($this->json['amount']);
            $menuExtra->save();

            // TODO Optimize performance
            $orderDetails = $orderDetailQuery->getDistributionUnfinishedByMenuExtraid($menuExtra->getMenuExtraid());

            foreach ($orderDetails as $orderDetail) {
                $statusCheck->verifyAvailability($orderDetail->getOrderDetailid());
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
            $orderDetail->setAvailabilityAmount($this->json['amount']);
            $orderDetail->save();
        }
    }
}
