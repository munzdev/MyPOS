<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\ORM\Menu\MenuExtraQuery;
use API\Models\ORM\Menu\MenuQuery;
use API\Models\ORM\Ordering\OrderDetailQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
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

        $app->getContainer()['db'];
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
        $connection = Propel::getConnection();

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
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menu = MenuQuery::create()
                            ->useMenuGroupQuery()
                               ->useMenuTypeQuery()
                                   ->filterByEventid($user->getEventUser()->getEventid())
                               ->endUse()
                            ->endUse()
                            ->filterByMenuid($this->json['id'])
                            ->findOne();

        if ($menu) {
            $menu->setAvailabilityAmount($this->json['amount']);
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

    private function setExtra()
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menuExtra = MenuExtraQuery::create()
                                    ->filterByEventid($user->getEventUser()->getEventid())
                                    ->filterByMenuExtraid($this->json['id'])
                                    ->findOne();

        if ($menuExtra) {
            $menuExtra->setAvailabilityAmount($this->json['amount']);
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

            foreach ($orderDetails as $orderDetail) {
                StatusCheck::verifyAvailability($orderDetail->getOrderDetailid());
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
                                                ->filterByEventid($user->getEventUser()->getEventid())
                                            ->endUse()
                                        ->endUse()
                                        ->filterByOrderDetailid($this->json['id'])
                                        ->findOne();
        if ($orderDetail) {
            $orderDetail->setAvailabilityAmount($this->json['amount']);
            $orderDetail->save();
        }
    }
}
