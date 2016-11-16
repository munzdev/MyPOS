<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\Map\EventTableTableMap;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\OIP\Map\OrderInProgressTableMap;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_OVERVIEW;

class Order extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_OVERVIEW];
        
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
}