<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\Ordering\Base\OrderDetailQuery;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailExtra;
use API\Models\Ordering\OrderDetailMixedWith;
use API\Models\Ordering\OrderQuery;
use DateTime;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_ORDER_MODIFY;
use const API\USER_ROLE_ORDER_MODIFY_PRICE;
use const API\USER_ROLE_ORDER_MODIFY_PRIORITY;

class OrderModify extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_ORDER_MODIFY,
                            'PUT' => USER_ROLE_ORDER_MODIFY,
                            'PATCH' => USER_ROLE_ORDER_MODIFY_PRICE | USER_ROLE_ORDER_MODIFY_PRIORITY];

        $app->getContainer()['db'];
    }

    public function any() : void
    {
        $validators = array(
            'id' => v::intVal()->positive()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {
        $order = OrderQuery::create()
                                ->joinWithOrderDetail()
                                ->useOrderDetailQuery()
                                    ->leftJoinWithMenuSize()
                                    ->leftJoinWithOrderDetailExtra()
                                    ->leftJoinWithOrderDetailMixedWith()
                                ->endUse()
                                ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                ->findByOrderid($this->args['id']);

        $this->withJson($order->getFirst());
    }

    public function patch() : void
    {
        if (isset($this->json['Priority'])) {
            $this->setPriority($this->args['id']);
        }

        if (isset($this->json['Cancellation'])) {
            $this->cancelOrder($this->args['id']);
        }

        if (isset($this->json['PriceModifications']) && !empty($this->json['Modifications'])) {
            $this->setPriceModifications($this->json['Modifications']);
        }
    }

    public function put() : void
    {
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $order = OrderQuery::create()
                                ->joinWithOrderDetail()
                                ->useOrderDetailQuery()
                                    ->leftJoinWithOrderDetailExtra()
                                    ->leftJoinWithOrderDetailMixedWith()
                                ->endUse()
                                ->findByOrderid($this->args['id'])
                                ->getFirst();

            $orderTemplate = new Order();

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $orderTemplate);

            foreach ($orderTemplate->getOrderDetails() as $orderDetailTemplate) {
                $orderDetailid = $orderDetailTemplate->getOrderDetailid();

                if ($orderDetailid) {
                    foreach ($order->getOrderDetails() as $orderDetail) {
                        if ($orderDetail->getOrderDetailid() == $orderDetailid) {
                            if ($orderDetailTemplate->getAmount() != $orderDetail->getAmount()) {
                                $orderDetail->setAmount($orderDetailTemplate->getAmount());
                                $orderDetail->save();
                            }
                            break;
                        }
                    }
                } else {
                    if ($orderDetailTemplate->getAmount() == 0) {
                        continue;
                    }

                    $orderDetail = new OrderDetail();
                    $orderDetail->fromArray($orderDetailTemplate->toArray());
                    $orderDetail->setOrder($order);
                    $orderDetail->save();
                }

                foreach ($orderDetailTemplate->getOrderDetailExtras() as $orderDetailExtraTemplate) {
                    $extraOrderDetailid = $orderDetailExtraTemplate->getOrderDetailid();

                    if (!$extraOrderDetailid) {
                        $orderDetailExtra = new OrderDetailExtra();
                        $orderDetailExtra->fromArray($orderDetailExtraTemplate->toArray());
                        $orderDetailExtra->setOrderDetail($orderDetail);
                        $orderDetailExtra->save();
                    }
                }

                foreach ($orderDetailTemplate->getOrderDetailMixedWiths() as $orderDetailMixedWithTemplate) {
                    $extraOrderDetailid = $orderDetailMixedWithTemplate->getOrderDetailid();

                    if (!$extraOrderDetailid) {
                        $orderDetailMixedWith = new OrderDetailMixedWith();
                        $orderDetailMixedWith->fromArray($orderDetailMixedWithTemplate->toArray());
                        $orderDetailMixedWith->setOrderDetail($orderDetail);
                        $orderDetailMixedWith->save();
                    }
                }
            }

            StatusCheck::verifyOrder($order->getOrderid());

            $connection->commit();

            $this->withJson($order->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function cancelOrder($orderid)
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $order = OrderQuery::create()->findPk($orderid);

            if ($order->getCancellation()) {
                return;
            }

            $order->setCancellation(new DateTime());
            $order->setCancellationCreatedByUserid($user->getUserid());
            $order->save();

            $orderDetails = OrderDetailQuery::create()
                                            ->filterByOrderid($orderid)
                                            ->filterByDistributionFinished(null)
                                            ->leftJoinWithOrderInProgressRecieved()
                                            ->find();

            foreach ($orderDetails as $orderDetail) {
                $amountRecieved = 0;

                foreach ($orderDetail->getOrderInProgressRecieveds() as $orderInProgressRecieved) {
                    $amountRecieved += $orderInProgressRecieved->getAmount();
                }

                $orderDetail->setAmount($amountRecieved);
                $orderDetail->save();
            }

            StatusCheck::verifyOrder($orderid);

            $modifiedOrder = OrderQuery::create()->findPk($orderid);

            $connection->commit();

            $this->withJson(['OpenInvoice' => $modifiedOrder->getInvoiceFinished() == null]);
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function setPriority($orderid)
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $order = OrderQuery::create()
                                ->setFirstPriority(
                                    $orderid,
                                    $user->getEventUser()->getEventid()
                                );

            $connection->commit();

            $this->withJson($order->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function setPriceModifications($orderDetails)
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            foreach ($orderDetails as $orderDetail) {
                $orderDetailTemplate = new OrderDetail();

                $jsonToModel = $this->container->get(IJsonToModel::class);
                $jsonToModel->convert($orderDetail, $orderDetailTemplate);

                $orderDetail = OrderDetailQuery::create()
                                                ->findPk($orderDetailTemplate->getOrderDetailid());

                $orderDetail->setSinglePrice($orderDetailTemplate->getSinglePrice())
                    ->setSinglePriceModifiedByUserid($user->getUserid())
                    ->save();
            }

            $this->withJson($orderDetail->getOrder()->toArray());
            $connection->commit();
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }
}
