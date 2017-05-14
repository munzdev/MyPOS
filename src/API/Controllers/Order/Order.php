<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IStatusCheck;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\Event\IEventTableQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtra;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWith;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\SecurityController;
use DateTime;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\ORDER_AVAILABILITY_AVAILABLE;
use const API\USER_ROLE_ORDER_ADD;
use const API\USER_ROLE_ORDER_OVERVIEW;

class Order extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_ORDER_OVERVIEW,
                           'POST' => USER_ROLE_ORDER_ADD];

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $orderQuery = $this->container->get(IOrderQuery::class);
        $user = $auth->getCurrentUser();

        $status = 'open';
        $orderid = null;
        $tablenr = null;
        $dateFrom = null;
        $dateTo = null;
        $userid = $user->getUserid();

        $validate = $this->container->get(IValidate::class);

        if (isset($_REQUEST['search'])) {
            $validators = array(
                'search' => [
                    'status' => v::alnum()->noWhitespace()->length(1),
                    'orderid' => v::optional(v::intVal()->length(1)->positive()),
                    'tableNr' => v::optional(v::alnum()->noWhitespace()->length(1)),
                    'from' => v::optional(v::dateTime('H:i')),
                    'to' => v::optional(v::dateTime('H:i')),
                    'userid' => v::oneOf(
                        v::intVal()->length(1)->positive(),
                        v::equals('*')
                    )]
            );

            $validate->assert($validators, $_REQUEST);

            $status = $_REQUEST['search']['status'];
            $userid = $_REQUEST['search']['userid'];

            if (isset($_REQUEST['search']['orderid'])) {
                $orderid = $_REQUEST['search']['orderid'];
            }

            if (isset($_REQUEST['search']['tableNr'])) {
                $tablenr = $_REQUEST['search']['tableNr'];
            }

            if (isset($_REQUEST['search']['from'])) {
                $dateFrom = $_REQUEST['search']['from'];
            }

            if (isset($_REQUEST['search']['to'])) {
                $dateTo = $_REQUEST['search']['to'];
            }
        }

        $orderCount = $orderQuery->getOrderCountBySearch($user->getEventUsers()->getFirst()->getEventid(),
            $status,
            $orderid,
            $tablenr,
            $userid,
            $dateFrom,
            $dateTo);

        if (isset($_REQUEST['page']) && isset($_REQUEST['elementsPerPage'])) {
            $validators = array(
                'page' => v::intVal()->length(1)->positive(),
                'elementsPerPage' => v::intVal()->length(1)->positive()
            );

            $validate->assert($validators, $_REQUEST);

            $orders = $orderQuery->findWithPagingAndSearch(($_REQUEST['elementsPerPage'] * $_REQUEST['page']) - $_REQUEST['elementsPerPage'],
                $_REQUEST['elementsPerPage'],
                $user->getEventUsers()->getFirst()->getEventid(),
                $status,
                $orderid,
                $tablenr,
                $userid,
                $dateFrom,
                $dateTo);

        } else {
            $orders = $orderQuery->findWithPagingAndSearch(null,
                null,
                $user->getEventUsers()->getFirst()->getEventid(),
                $status,
                $orderid,
                $tablenr,
                $userid,
                $dateFrom,
                $dateTo);
        }

        $this->withJson(
            ["Count" => $orderCount,
            "Order" => $orders->toArray()]
        );
    }

    public function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $statusCheck = $this->container->get(IStatusCheck::class);
        $eventTableQuery = $this->container->get(IEventTableQuery::class);
        $orderQuery = $this->container->get(IOrderQuery::class);
        $connection = $this->container->get(IConnectionInterface::class);

        $user = $auth->getCurrentUser();
        $eventid = $user->getEventUsers()->getFirst()->getEventid();
        $name = $this->json['EventTable']['Name'];

        try {
            $connection->beginTransaction();

            $eventTable = $eventTableQuery->getByName($eventid, $name);

            if(!$eventTable) {
                $eventTable = $eventTableQuery = $this->container->get(IEventTable::class);
                $eventTable->setEventid($eventid);
                $eventTable->setName($name);
                $eventTable->save();
            }

            $orderTemplate = $this->container->get(IOrder::class);

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $orderTemplate);

            $priority = $orderQuery->getMaxPriority($eventid);

            $order = $this->container->get(IOrder::class);
            $order->setEventTable($eventTable);
            $order->setUser($user);
            $order->setPriority($priority + 1);
            $order->setOrdertime(new DateTime());
            $order->save();

            foreach ($orderTemplate->getOrderDetails() as $orderDetailTemplate) {
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

                foreach ($orderDetailTemplate->getOrderDetailExtras() as $orderDetailExtraTemplate) {
                    $orderDetailExtra = $this->container->get(IOrderDetailExtra::class);
                    $orderDetailExtra->setMenuPossibleExtraid($orderDetailExtraTemplate->getMenuPossibleExtraid());
                    $orderDetailExtra->setOrderDetail($orderDetail);
                    $orderDetailExtra->save();
                }

                foreach ($orderDetailTemplate->getOrderDetailMixedWiths() as $orderDetailMixedWithTemplate) {
                    $orderDetailMixedWith = $this->container->get(IOrderDetailMixedWith::class);
                    $orderDetailMixedWith->setMenuid($orderDetailMixedWithTemplate->getMenuid());
                    $orderDetailMixedWith->setOrderDetail($orderDetail);
                    $orderDetailMixedWith->save();
                }

                $statusCheck->verifyAvailability($orderDetail->getOrderDetailid());
            }

            $connection->commit();

            $this->withJson($order->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }
}
