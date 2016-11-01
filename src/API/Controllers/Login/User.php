<?php

namespace API\Controllers\Login;

use API\Lib\Auth;
use API\Lib\Controller;
use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\UserQuery;
use Slim\App;

class User extends Controller
{    
    protected $o_auth;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
        
        $this->o_auth = new Auth(new UserQuery());
    }
    
    protected function GET() : void {
        $o_user = $this->o_auth->GetCurrentUser();        
        
        $a_return = $o_user->toArray();
        $a_return[EventUserTableMap::getTableMap()->getPhpName()] = $o_user->getEventUser()->toArray();
        
        $this->o_response->withJson($a_return);
    }
}