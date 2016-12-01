<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use \API\Models\Invoice\Customer AS CustomerModel;
use Slim\App;

class Customer extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);        
        
        $o_app->getContainer()['db'];
    }
    
    protected function POST() : void  {            
        $o_user = Auth::GetCurrentUser();     
        
        $o_customer = new CustomerModel();
        $o_customer->fromArray($this->a_json);
        $o_customer->setActive(true)
                   ->setEventid($o_user->getEventUser()->getEventid())
                   ->save();
        
        $this->o_response->withJson($o_customer->toArray());
    }        
    
}