<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\Menu\MenuExtraQuery;
use API\Models\Menu\MenuQuery;
use API\Models\Ordering\OrderDetailQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_DISTRIBUTION_SET_AVAILABILITY;

class DistributionPlaceAmount extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['POST' => USER_ROLE_DISTRIBUTION_SET_AVAILABILITY];

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators = array(
            'type' => v::alnum()->length(1),
            'id' => v::intVal()->length(1),
            'amount' => v::intVal()->length(1),
        );

        $this->validate($a_validators, $this->a_json);
    }

    protected function POST() : void {
         $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            if($this->a_json['amount'] == '0')
                $this->a_json['amount'] = null;

            if($this->a_json['type'] == 'menu') {
                $this->setMenu();
            }

            if($this->a_json['type'] == 'extra') {
                $this->setExtra();
            }

            if($this->a_json['type'] == 'specialExtra') {
                $this->setSpecialExtra();
            }

            $o_connection->commit();

            $this->withJson(true);
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }

    private function setMenu() {
        $o_user = Auth::GetCurrentUser();

        $o_menu = MenuQuery::create()
                             ->useMenuGroupQuery()
                                ->useMenuTypeQuery()
                                    ->filterByEventid($o_user->getEventUser()->getEventid())
                                ->endUse()
                             ->endUse()
                             ->filterByMenuid($this->a_json['id'])
                             ->findOne();

        if($o_menu) {
            $o_menu->setAvailabilityAmount($this->a_json['amount']);
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

    private function setExtra() {
        $o_user = Auth::GetCurrentUser();

        $o_menuExtra = MenuExtraQuery::create()
                                        ->filterByEventid($o_user->getEventUser()->getEventid())
                                        ->filterByMenuExtraid($this->a_json['id'])
                                        ->findOne();

        if($o_menuExtra) {
            $o_menuExtra->setAvailabilityAmount($this->a_json['amount']);
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

            foreach($o_orderDetails as $o_orderDetail) {
                StatusCheck::verifyAvailability($o_orderDetail->getOrderDetailid());
            }
        }
    }

    private function setSpecialExtra() {
        $o_user = Auth::GetCurrentUser();

        $o_orderDetail = OrderDetailQuery::create()
                                            ->useOrderQuery()
                                                ->useEventTableQuery()
                                                    ->filterByEventid($o_user->getEventUser()->getEventid())
                                                ->endUse()
                                            ->endUse()
                                            ->filterByOrderDetailid($this->a_json['id'])
                                            ->findOne();
        if($o_orderDetail) {
            $o_orderDetail->setAvailabilityAmount($this->a_json['amount']);
            $o_orderDetail->save();
        }
    }
}