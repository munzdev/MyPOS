<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
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
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_ORDER_ADD;
use const API\USER_ROLE_ORDER_OVERVIEW;

class Order extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_ORDER_OVERVIEW,
                             'POST' => USER_ROLE_ORDER_ADD];

        $o_app->getContainer()['db'];
    }

    protected function GET() : void
    {
        $o_user = Auth::GetCurrentUser();

        $str_status = 'open';
        $i_orderid = null;
        $str_tablenr = null;
        $str_from = null;
        $str_to = null;
        $i_userid = $o_user->getUserid();

        if(isset($_REQUEST['search']))
        {
            $a_validators = array(
                'search' => [
                    'status' => v::alnum()->noWhitespace()->length(1),
                    'orderid' => v::optional(v::intVal()->length(1)->positive()),
                    'tableNr' => v::optional(v::alnum()->noWhitespace()->length(1)),
                    'from' => v::optional(v::date('H:i')),
                    'to' => v::optional(v::date('H:i')),
                    'userid' => v::oneOf(v::intVal()->length(1)->positive(),
                                         v::equals('*'))]
            );

            $this->validate($a_validators, $_REQUEST);

            $str_status = $_REQUEST['search']['status'];
            $i_userid = $_REQUEST['search']['userid'];

            if(isset($_REQUEST['search']['orderid']))
                $i_orderid = $_REQUEST['search']['orderid'];

            if(isset($_REQUEST['search']['tableNr']))
                $str_tablenr = $_REQUEST['search']['tableNr'];

            if(isset($_REQUEST['search']['from']))
                $str_from = $_REQUEST['search']['from'];

            if(isset($_REQUEST['search']['to']))
                $str_to = $_REQUEST['search']['to'];
        }

        $o_searchCriteria = OrderQuery::create()
                                        ->useEventTableQuery()
                                            ->filterByEventid($o_user->getEventUser()->getEventid())
                                            ->_if($str_tablenr)
                                                ->filterByName($str_tablenr)
                                            ->_endif()
                                        ->endUse()
                                        ->_if($str_status == 'open')
                                            ->filterByDistributionFinished(null)
                                            ->_or()
                                            ->filterByInvoiceFinished(null)
                                        ->_elseif($str_status == 'completed')
                                            ->filterByDistributionFinished(null, Criteria::NOT_EQUAL)
                                            ->_and()
                                            ->filterByInvoiceFinished(null, Criteria::NOT_EQUAL)
                                        ->_endif()
                                        ->_if($i_orderid)
                                            ->filterByOrderid($i_orderid)
                                        ->_endif()
                                        ->_if($i_userid != '*')
                                            ->filterByUserid($i_userid)
                                        ->_endif()
                                        ->_if($str_from)
                                            ->filterByOrdertime(array('min' => DateTime::createFromFormat('H:i', $str_from)))
                                        ->_endif()
                                        ->_if($str_to)
                                            ->filterByOrdertime(array('max' => DateTime::createFromFormat('H:i', $str_to)))
                                        ->_endif();

        if(isset($_REQUEST['page']) && isset($_REQUEST['elementsPerPage'])) {
            $a_validators = array(
                'page' => v::intVal()->length(1)->positive(),
                'elementsPerPage' => v::intVal()->length(1)->positive()
            );

            $this->validate($a_validators, $_REQUEST);

            $o_ordersList = OrderQuery::create(null, clone $o_searchCriteria)
                                        ->offset(($_REQUEST['elementsPerPage'] * $_REQUEST['page']) - $_REQUEST['elementsPerPage'])
                                        ->limit($_REQUEST['elementsPerPage'])
                                        ->find();
        }

        $o_criteriaData = OrderQuery::create(null, $o_searchCriteria)
                                    ->joinOrderDetail()
                                    ->leftJoinWithOrderInProgress()
                                    ->with(EventTableTableMap::getTableMap()->getPhpName())
                                    ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " * " . OrderDetailTableMap::COL_SINGLE_PRICE . ")", "price")
                                    ->groupByOrderid()
                                    ->orderByPriority();

        $i_order_count = OrderQuery::create(null, clone $o_criteriaData)->count();

        $o_order = OrderQuery::create(null, $o_criteriaData)
                                ->_if(!empty($o_ordersList))
                                    ->where(OrderTableMap::COL_ORDERID . " IN ?", $o_ordersList->getColumnValues())
                                ->_endif()
                                ->find();

        $this->withJson(["Count" => $i_order_count,
                         "Order" => $o_order->toArray()]);
    }

    function POST() : void {
        $o_user = Auth::GetCurrentUser();
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $o_eventTable = EventTableQuery::create()
                                            ->filterByEventid($o_user->getEventUser()->getEventid())
                                            ->filterByName($this->a_json['EventTable']['Name'])
                                            ->findOneOrCreate();

            $o_order_template = new ModelsOrder();
            $this->jsonToPropel($this->a_json, $o_order_template);

            $o_order_detail_priority = OrderQuery::create()
                                                    ->useEventTableQuery()
                                                        ->filterByEventid($o_eventTable->getEventid())
                                                    ->endUse()
                                                    ->addAsColumn('priority', 'MAX(' . OrderTableMap::COL_PRIORITY . ') + 1')
                                                    ->findOne();

            $o_order = new ModelsOrder();
            $o_order->setEventTable($o_eventTable);
            $o_order->setUser($o_user);
            $o_order->setPriority($o_order_detail_priority->getVirtualColumn('priority'));
            $o_order->setOrdertime(new DateTime());
            $o_order->save();

            foreach($o_order_template->getOrderDetails() as $o_order_detail_template)
            {
                if($o_order_detail_template->getAmount() == 0)
                    continue;

                $o_order_detail = new OrderDetail();
                $o_order_detail->fromArray($o_order_detail_template->toArray());
                $o_order_detail->setOrder($o_order);
                $o_order_detail->save();

                foreach($o_order_detail_template->getOrderDetailExtras() as $o_order_detail_extra_template)
                {
                    $o_order_detail_extra = new OrderDetailExtra();
                    $o_order_detail_extra->fromArray($o_order_detail_extra_template->toArray());
                    $o_order_detail_extra->setOrderDetail($o_order_detail);
                    $o_order_detail_extra->save();
                }

                foreach($o_order_detail_template->getOrderDetailMixedWiths() as $o_order_detail_mixed_with_template)
                {
                    $o_order_detail_mixed_with = new OrderDetailMixedWith();
                    $o_order_detail_mixed_with->fromArray($o_order_detail_mixed_with_template->toArray());
                    $o_order_detail_mixed_with->setOrderDetail($o_order_detail);
                    $o_order_detail_mixed_with->save();
                }
            }

            $o_connection->commit();

            $this->withJson($o_order->toArray());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }
}