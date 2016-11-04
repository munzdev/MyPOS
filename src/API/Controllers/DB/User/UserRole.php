<?php

namespace API\Controllers\DB\User;

use API\Lib\SecurityController;
use API\Models\User\UserRoleQuery;
use Slim\App;

class UserRole extends SecurityController
{    
    protected $o_auth;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void {
        $o_userRoles = UserRoleQuery::create()->find();
        
        $this->o_response->withJson($o_userRoles->toArray());
    }
    
}