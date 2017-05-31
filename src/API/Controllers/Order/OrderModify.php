<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IStatusCheck;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\SecurityController;
use API\Models\ORM\Ordering\OrderDetailExtra;
use API\Models\ORM\Ordering\OrderDetailMixedWith;
use DateTime;
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

        $this->container->get(IConnectionInterface::class);
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
        $orderQuery = $this->container->get(IOrderQuery::class);
        $order = $orderQuery->getWithModifyDetails($this->args['id']);

        $this->withJson($order->toArray(true));
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
        $statusCheck = $this->container->get(IStatusCheck::class);
        $connection = $this->container->get(IConnectionInterface::class);
        $orderQuery = $this->container->get(IOrderQuery::class);

        try {
            $connection->beginTransaction();

            $order = $orderQuery->getWithModifyDetails($this->args['id']);

            $orderTemplate = $this->container->get(IOrder::class);

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

                    $orderDetail = $this->container->get(IOrderDetail::class);
                    $orderDetail->setAmount($orderDetailTemplate->getAmount());
                    $orderDetail->setExtraDetail($orderDetailTemplate->getExtraDetail());
                    $orderDetail->setMenuid($orderDetailTemplate->getMenuid());
                    $orderDetail->setMenuSizeid($orderDetailTemplate->getMenuSizeid());
                    $orderDetail->setOrderid($orderDetailTemplate->getOrderid());
                    $orderDetail->setSinglePrice($orderDetailTemplate->getSinglePrice());
                    $orderDetail->setOrder($order);

                    if ($orderDetail->getMenuid()) {
                        $orderDetail->setVerified(true);
                        $orderDetail->setAvailabilityid(ORDER_AVAILABILITY_AVAILABLE);
                    }

                    $orderDetail->save();
                }

                foreach ($orderDetailTemplate->getOrderDetailExtras() as $orderDetailExtraTemplate) {
                    $extraOrderDetailid = $orderDetailExtraTemplate->getOrderDetailid();

                    if (!$extraOrderDetailid) {
                        $orderDetailExtra = new OrderDetailExtra();
                        $orderDetailExtra->setMenuPossibleExtraid($orderDetailExtraTemplate->getMenuPossibleExtraid());
                        $orderDetailExtra->setOrderDetail($orderDetail);
                        $orderDetailExtra->save();
                    }
                }

                foreach ($orderDetailTemplate->getOrderDetailMixedWiths() as $orderDetailMixedWithTemplate) {
                    $extraOrderDetailid = $orderDetailMixedWithTemplate->getOrderDetailid();

                    if (!$extraOrderDetailid) {
                        $orderDetailMixedWith = new OrderDetailMixedWith();
                        $orderDetailMixedWith->setMenuid($orderDetailMixedWithTemplate->getMenuid());
                        $orderDetailMixedWith->setOrderDetail($orderDetail);
                        $orderDetailMixedWith->save();
                    }
                }
            }

            $statusCheck->verifyOrder($order->getOrderid());

            $connection->commit();

            $this->withJson($order->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function cancelOrder($orderid)
    {
        $auth = $this->container->get(IAuth::class);
        $statusCheck = $this->container->get(IStatusCheck::class);
        $orderQuery = $this->container->get(IOrderQuery::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);
        $connection = $this->container->get(IConnectionInterface::class);

        $user = $auth->getCurrentUser();

        try {
            $connection->beginTransaction();

            $order = $orderQuery->findPk($orderid);

            if ($order->getCancellation()) {
                return;
            }

            $order->setCancellation(new DateTime());
            $order->setCancellationCreatedByUserid($user->getUserid());
            $order->save();

            $orderDetails = $orderDetailQuery->getUnrecievedOfOrder($orderid);

            foreach ($orderDetails as $orderDetail) {
                $amountRecieved = 0;

                foreach ($orderDetail->getOrderInProgressRecieveds() as $orderInProgressRecieved) {
                    $amountRecieved += $orderInProgressRecieved->getAmount();
                }

                $orderDetail->setAmount($amountRecieved);
                $orderDetail->save();
            }

            $statusCheck->verifyOrder($orderid);

            $modifiedOrder = $orderQuery->findPk($orderid);

            $connection->commit();

            $this->withJson(['OpenInvoice' => $modifiedOrder->getInvoiceFinished() == null]);
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function setPriority($orderid)
    {
        $auth = $this->container->get(IAuth::class);
        $orderQuery = $this->container->get(IOrderQuery::class);
        $connection = $this->container->get(IConnectionInterface::class);

        $user = $auth->getCurrentUser();

        try {
            $connection->beginTransaction();

            $order = $orderQuery->setFirstPriority($user->getEventUsers()->getFirst()->getEventid(), $orderid);

            $connection->commit();

            $this->withJson($order->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function setPriceModifications($orderDetails)
    {
        $auth = $this->container->get(IAuth::class);
        $connection = $this->container->get(IConnectionInterface::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);

        $user = $auth->getCurrentUser();

        try {
            $connection->beginTransaction();

            foreach ($orderDetails as $orderDetail) {
                $orderDetailTemplate = $this->container->get(IOrderDetail::class);

                $jsonToModel = $this->container->get(IJsonToModel::class);
                $jsonToModel->convert($orderDetail, $orderDetailTemplate);

                $orderDetail = $orderDetailQuery->findPk($orderDetailTemplate->getOrderDetailid());

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
