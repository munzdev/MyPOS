<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\Menu\MenuExtraQuery;
use API\Models\Menu\MenuQuery;
use API\Models\Ordering\OrderDetailQuery;
use Exception;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;
use const API\USER_ROLE_DISTRIBUTION_SET_AVAILABILITY;

class DistributionPlaceAvailability extends SecurityController
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
            'status' => v::intVal()->length(1),
        );

        $this->validate($a_validators, $this->a_json);
    }

    protected function POST() : void {
         $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

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
            $o_menu->setAvailabilityid($this->a_json['status']);
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

            if($o_menu->getAvailabilityid() == ORDER_AVAILABILITY_OUT_OF_ORDER) {

                $a_ids = [];
                foreach($o_orderDetails as $o_orderDetail) {
                    $a_ids[] = $o_orderDetail->getOrderDetailid();
                }

                if(!empty($a_ids))
                    OrderDetailQuery::create()
                                        ->filterByOrderDetailid($a_ids)
                                        ->update(['Availabilityid' => $this->a_json['status']]);
            } else {

                foreach($o_orderDetails as $o_orderDetail) {
                    StatusCheck::verifyAvailability($o_orderDetail->getOrderDetailid());
                }
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
            $o_menuExtra->setAvailabilityid($this->a_json['status']);
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
                                        ->update(['Availabilityid' => $this->a_json['status']]);
            } else {
                foreach($o_orderDetails as $o_orderDetail) {
                    StatusCheck::verifyAvailability($o_orderDetail->getOrderDetailid());
                }
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
            $o_orderDetail->setAvailabilityid($this->a_json['status']);
            $o_orderDetail->save();
        }
    }
}