<?php

namespace API\Controllers\Login;

use Slim\App;
use API\Lib\{Controller, Auth};
use API\Models\User\UsersQuery;

class User extends Controller
{    
    protected $o_auth;
    protected $o_usersQuery;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
        
        $this->o_usersQuery = UsersQuery::create();
        $this->o_auth = new Auth($this->o_usersQuery);
    }
    
    protected function GET() {
    }
}