<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventTableQuery;
use API\Models\Ordering\OrderDetailQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_ADD;

class OrderUnbilled extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_ADD];
        
        $o_app->getContainer()['db'];
    }
    
    function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive(),
            'all' => v::boolVal()
        );
        
        $this->validate($a_validators, $this->a_args);       
    }
    
    protected function GET() : void  {                
        $o_user = Auth::GetCurrentUser();      
        
        $i_orderid = intval($this->a_args['id']);
        $b_all = filter_var($this->a_args['all'], FILTER_VALIDATE_BOOLEAN);
        
        $o_eventTable = null;
        if($b_all) {
            $o_eventTable = EventTableQuery::create()
                                            ->useOrderQuery()
                                               ->filterByOrderid($i_orderid)
                                            ->endUse()
                                            ->findOne();
        }
        
        $o_payments = OrderDetailQuery::create()
                                        ->_if($b_all)
                                            ->useOrderQuery()
                                                ->filterByEventTable($o_eventTable)
                                            ->endUse()
                                        ->_else()
                                            ->filterByOrderid($i_orderid)
                                        ->_endIf()
                                        ->leftJoinWithMenuSize()
                                        ->leftJoinWithOrderDetailExtra()                                        
                                        ->leftJoinWithOrderDetailMixedWith()                                       
                                        ->leftJoinWithInvoiceItem()
                                        ->filterByInvoiceFinished(null)        
                                        ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                        ->find();
        $a_return = array();
        
        // if all order from table are returned, merge same order types
        if($o_payments->count() > 0) {
            foreach($o_payments as $a_order_detail) {
                $str_index = $a_order_detail['Menuid'] . '-' .
                             $a_order_detail['SinglePrice'] . '-' .
                             $a_order_detail['ExtraDetail'] . '-' .
                             $a_order_detail['MenuSizeid'] . '-';
                             
                foreach($a_order_detail['OrderDetailExtras'] as $i_key => $a_order_detail_extra) {
                    if(empty($a_order_detail_extra)) {
                        unset($a_order_detail['OrderDetailExtras'][$i_key]);
                        continue;
                    }
                    
                    $str_index .= $a_order_detail_extra['MenuPossibleExtraid'];
                }
                
                $str_index .= '-';
                
                foreach($a_order_detail['OrderDetailMixedWiths'] as $i_key => $a_order_detail_mixed_with) {
                    if(empty($a_order_detail_mixed_with)) {
                        unset($a_order_detail['OrderDetailMixedWiths'][$i_key]);
                        continue;
                    }
                    $str_index .= $a_order_detail_mixed_with['Menuid'];
                }
                
                $i_allready_in_invoice = 0;
                
                foreach($a_order_detail['InvoiceItems'] as $i_key => $a_invoice_item) {
                    if(empty($a_invoice_item)) {
                        unset($a_order_detail['InvoiceItems'][$i_key]);
                        continue;
                    }
                    $i_allready_in_invoice += $a_invoice_item['Amount'];
                }
                
                $a_order_detail['AmountLeft'] = $a_order_detail['Amount'] - $i_allready_in_invoice;

                if(!isset($a_return[$str_index]))
                {
                    $a_return[$str_index] = $a_order_detail;
                }
                else
                {
                    $a_return[$str_index]['Amount'] += $a_order_detail['Amount'];
                    $a_return[$str_index]['AmountLeft'] += $a_order_detail['AmountLeft'];
                }
            }
            
            $a_return = array_values($a_return);
        }
        
        $this->o_response->withJson($a_return);
    }
    
}