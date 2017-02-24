<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\Event\EventTableQuery;
use API\Models\Event\Map\EventTableTableMap;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\Map\OrderTableMap;
use API\Models\Ordering\Order as ModelsOrder;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailExtra;
use API\Models\Ordering\OrderDetailMixedWith;
use API\Models\Ordering\OrderQuery;
use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Propel;
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

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
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
                    'from' => v::optional(v::date('H:i')),
                    'to' => v::optional(v::date('H:i')),
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

        $searchCriteria = OrderQuery::create()
                                        ->useEventTableQuery()
                                            ->filterByEventid($user->getEventUser()->getEventid())
                                            ->_if($tablenr)
                                                ->filterByName($tablenr)
                                            ->_endif()
                                        ->endUse()
                                        ->_if($status == 'open')
                                            ->filterByDistributionFinished(null)
                                            ->_or()
                                            ->filterByInvoiceFinished(null)
                                        ->_elseif($status == 'completed')
                                            ->filterByDistributionFinished(null, Criteria::NOT_EQUAL)
                                            ->_and()
                                            ->filterByInvoiceFinished(null, Criteria::NOT_EQUAL)
                                        ->_endif()
                                        ->_if($orderid)
                                            ->filterByOrderid($orderid)
                                        ->_endif()
                                        ->_if($userid != '*')
                                            ->filterByUserid($userid)
                                        ->_endif()
                                        ->_if($dateFrom)
                                            ->filterByOrdertime(array('min' => DateTime::createFromFormat('H:i', $dateFrom)))
                                        ->_endif()
                                        ->_if($dateTo)
                                            ->filterByOrdertime(array('max' => DateTime::createFromFormat('H:i', $dateTo)))
                                        ->_endif();

        if (isset($_REQUEST['page']) && isset($_REQUEST['elementsPerPage'])) {
            $validators = array(
                'page' => v::intVal()->length(1)->positive(),
                'elementsPerPage' => v::intVal()->length(1)->positive()
            );

            $validate->assert($validators, $_REQUEST);

            $ordersList = OrderQuery::create(null, clone $searchCriteria)
                                        ->offset(($_REQUEST['elementsPerPage'] * $_REQUEST['page']) - $_REQUEST['elementsPerPage'])
                                        ->limit($_REQUEST['elementsPerPage'])
                                        ->find();
        }

        $criteriaData = OrderQuery::create(null, $searchCriteria)
                                    ->joinOrderDetail()
                                    ->leftJoinWithOrderInProgress()
                                    ->with(EventTableTableMap::getTableMap()->getPhpName())
                                    ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " * " . OrderDetailTableMap::COL_SINGLE_PRICE . ")", "price")
                                    ->groupByOrderid()
                                    ->orderByPriority();

        $orderCount = OrderQuery::create(null, clone $criteriaData)->count();

        $order = OrderQuery::create(null, $criteriaData)
                            ->_if(!empty($ordersList))
                                ->where(OrderTableMap::COL_ORDERID . " IN ?", $ordersList->getColumnValues())
                            ->_endif()
                            ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                            ->find();

        $this->withJson(
            ["Count" => $orderCount,
            "Order" => $order->toArray()]
        );
    }

    public function post() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $eventTable = EventTableQuery::create()
                                            ->filterByEventid($user->getEventUser()->getEventid())
                                            ->filterByName($this->json['EventTable']['Name'])
                                            ->findOneOrCreate();

            $orderTemplate = new ModelsOrder();

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $orderTemplate);

            $orderDetailPriority = OrderQuery::create()
                                                    ->useEventTableQuery()
                                                        ->filterByEventid($eventTable->getEventid())
                                                    ->endUse()
                                                    ->addAsColumn('priority', 'MAX(' . OrderTableMap::COL_PRIORITY . ') + 1')
                                                    ->findOne();

            $order = new ModelsOrder();
            $order->setEventTable($eventTable);
            $order->setUser($user);
            $order->setPriority($orderDetailPriority->getVirtualColumn('priority'));
            $order->setOrdertime(new DateTime());
            $order->save();

            foreach ($orderTemplate->getOrderDetails() as $orderDetailTemplate) {
                if ($orderDetailTemplate->getAmount() == 0) {
                    continue;
                }

                $orderDetail = new OrderDetail();
                $orderDetail->fromArray($orderDetailTemplate->toArray());
                $orderDetail->setOrder($order);

                if ($orderDetail->getMenuid()) {
                    $orderDetail->setVerified(true);
                    $orderDetail->setAvailabilityid(ORDER_AVAILABILITY_AVAILABLE);
                }

                $orderDetail->save();

                foreach ($orderDetailTemplate->getOrderDetailExtras() as $orderDetailExtraTemplate) {
                    $orderDetailExtra = new OrderDetailExtra();
                    $orderDetailExtra->fromArray($orderDetailExtraTemplate->toArray());
                    $orderDetailExtra->setOrderDetail($orderDetail);
                    $orderDetailExtra->save();
                }

                foreach ($orderDetailTemplate->getOrderDetailMixedWiths() as $orderDetailMixedWithTemplate) {
                    $orderDetailMixedWith = new OrderDetailMixedWith();
                    $orderDetailMixedWith->fromArray($orderDetailMixedWithTemplate->toArray());
                    $orderDetailMixedWith->setOrderDetail($orderDetail);
                    $orderDetailMixedWith->save();
                }

                StatusCheck::verifyAvailability($orderDetail->getOrderDetailid());
            }

            $connection->commit();

            $this->withJson($order->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }
}
