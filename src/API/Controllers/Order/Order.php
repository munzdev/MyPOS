<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventTableQuery;
use API\Models\Event\Map\EventTableTableMap;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\OIP\Map\OrderInProgressTableMap;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\Map\OrderTableMap;
use API\Models\Ordering\Order as ModelsOrder;
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
        
        if(isset($this->a_json['search']))
        {
            $a_validators = array(
                ['search'] => [
                    'status' => v::alnum()->noWhitespace()->length(1),
                    'orderid' => v::intVal()->length(1)->positive(),
                    'tablenr' => v::alnum()->noWhitespace()->length(1),            
                    'from' => v::date('H:i'),
                    'to' => v::date('H:i'),
                    'userid' => v::intVal()->length(1)->positive()]
            );        

            $this->validate($a_validators);             
            
            $str_status = $this->a_json['search']['status'];
            $i_orderid = $this->a_json['search']['orderid'];
            $str_tablenr = $this->a_json['search']['tablenr'];
            $str_from = $this->a_json['search']['from'];
            $str_to = $this->a_json['search']['to'];
            $i_userid = $this->a_json['search']['Userid'];
        }
                
        $o_order = OrderQuery::create()
                ->useEventTableQuery()
                    ->filterByEventid($o_user->getEventUser()->getEventid())
                ->endUse()
                ->useOrderDetailQuery()
                    ->useInvoiceItemQuery(null, ModelCriteria::LEFT_JOIN)
                        ->useInvoiceQuery(null, ModelCriteria::LEFT_JOIN)
                            ->filterByCanceled(null)
                        ->endUse()
                    ->endUse()
                ->endUse()
                ->joinWithOrderInProgress(ModelCriteria::LEFT_JOIN)
                ->with(EventTableTableMap::getTableMap()->getPhpName())
                ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " * " . OrderDetailTableMap::COL_SINGLE_PRICE . ")", "price")
                ->withColumn("COUNT(" . OrderDetailTableMap::COL_ORDER_DETAILID . ") - COUNT(" . InvoiceItemTableMap::COL_INVOICE_ITEMID . ")", "open")
                ->filterByUserid($i_userid)
                ->groupByOrderid()
                ->condition("open", "open > 0")
                ->condition("oip", OrderInProgressTableMap::COL_DONE . ModelCriteria::ISNULL)
                ->having(array("open", "oip"), ModelCriteria::LOGICAL_OR )
                ->orderByPriority()                    
                ->find();
        
        $this->o_response->withJson($o_order->toArray());
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

            $this->o_response->withJson($o_order->toArray());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }               
    }
}