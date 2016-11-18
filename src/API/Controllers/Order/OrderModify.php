<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use Slim\App;
use const API\USER_ROLE_ORDER_ADD;

class OrderModify extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_ADD];
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void 
    {
        $o_user = Auth::GetCurrentUser();
        
    }
}