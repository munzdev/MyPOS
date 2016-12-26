<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailExtra;
use API\Models\Ordering\OrderDetailMixedWith;
use API\Models\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_ORDER_MODIFY;

class OrderModify extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_ORDER_MODIFY];

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive()
        );

        $this->validate($a_validators, $this->a_args);
    }

    protected function GET() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_order = OrderQuery::create()
                                ->joinWithOrderDetail()
                                ->useOrderDetailQuery()
                                    ->leftJoinWithMenuSize()
                                    ->leftJoinWithOrderDetailExtra()
                                    ->leftJoinWithOrderDetailMixedWith()
                                ->endUse()
                                ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                ->findByOrderid($this->a_args['id']);

        $this->o_response->withJson($o_order->getFirst());
    }

    function PUT() : void {
        $o_user = Auth::GetCurrentUser();
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $o_order = OrderQuery::create()
                                ->joinWithOrderDetail()
                                ->useOrderDetailQuery()
                                    ->leftJoinWithOrderDetailExtra()
                                    ->leftJoinWithOrderDetailMixedWith()
                                ->endUse()
                                ->findByOrderid($this->a_args['id'])
                                ->getFirst();

            $o_order_template = new Order();
            $this->jsonToPropel($this->a_json, $o_order_template);

            foreach($o_order_template->getOrderDetails() as $o_order_detail_template)
            {
                $i_orderDetailid = $o_order_detail_template->getOrderDetailid();

                if($i_orderDetailid) {
                    foreach($o_order->getOrderDetails() as $o_order_detail) {
                        if($o_order_detail->getOrderDetailid() == $i_orderDetailid) {
                            if($o_order_detail_template->getAmount() != $o_order_detail->getAmount()) {
                                $o_order_detail->setAmount($o_order_detail_template->getAmount());
                                $o_order_detail->save();
                            }
                            break;
                        }
                    }

                } else {
                    if($o_order_detail_template->getAmount() == 0)
                        continue;

                    $o_order_detail = new OrderDetail();
                    $o_order_detail->fromArray($o_order_detail_template->toArray());
                    $o_order_detail->setOrder($o_order);
                    $o_order_detail->save();
                }

                foreach($o_order_detail_template->getOrderDetailExtras() as $o_order_detail_extra_template)
                {
                    $i_extra_orderDetailid = $o_order_detail_extra_template->getOrderDetailid();

                    if(!$i_extra_orderDetailid) {
                        $o_order_detail_extra = new OrderDetailExtra();
                        $o_order_detail_extra->fromArray($o_order_detail_extra_template->toArray());
                        $o_order_detail_extra->setOrderDetail($o_order_detail);
                        $o_order_detail_extra->save();
                    }
                }

                foreach($o_order_detail_template->getOrderDetailMixedWiths() as $o_order_detail_mixed_with_template)
                {
                    $i_extra_orderDetailid = $o_order_detail_mixed_with_template->getOrderDetailid();

                    if(!$i_extra_orderDetailid) {
                        $o_order_detail_mixed_with = new OrderDetailMixedWith();
                        $o_order_detail_mixed_with->fromArray($o_order_detail_mixed_with_template->toArray());
                        $o_order_detail_mixed_with->setOrderDetail($o_order_detail);
                        $o_order_detail_mixed_with->save();
                    }
                }
            }

            StatusCheck::verifyOrder($o_order->getOrderid());

            $o_connection->commit();

            $this->o_response->withJson($o_order->toArray());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }

    }
}