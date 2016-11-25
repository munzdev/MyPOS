<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_ADD;

class OrderModify extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_ADD];
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void  {                
        $a_validators = array(
            'id' => v::intVal()->positive()
        );
        
        $this->validate($a_validators, $this->a_args);       
        
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
}