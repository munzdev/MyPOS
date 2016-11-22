<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventTableQuery;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderDetail;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_ADD;

class OrderModify extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_ADD,
                             'POST' => USER_ROLE_ORDER_ADD,
                             'UPDATE' => USER_ROLE_ORDER_ADD];
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void  {                
        $a_validators = array(
            'id' => v::intVal()->positive()
        );
        
        $this->validate($a_validators, $this->a_args);       
        
        $o_user = Auth::GetCurrentUser();      
    }
    
    function POST() : void {
        $a_validators = array(
            'id' => v::equals(0) // verify new order when using POST
        );
        
        $this->validate($a_validators, $this->a_args);        
        
        $o_user = Auth::GetCurrentUser();           
        
        $o_eventTable = EventTableQuery::create()
                                        ->filterByEventid($o_user->getEventUser()->getEventid())
                                        ->filterByName($this->a_json['EventTable']['Name'])
                                        ->findOneOrCreate();
        
        $o_order = new Order();              
        $o_order->setEventTable($o_eventTable);
        $o_order->setUser($o_user);
                
        $this->jsonToPropel($this->a_json, $o_order);        
        
        $o_connection = Propel::getConnection();
        $o_connection->beginTransaction();
        
        $o_order->save();
        $o_connection->commit();
        
        $this->o_response->withJson($o_order->getOrderid());
    }
}