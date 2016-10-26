<?php

namespace API\Controllers\Login;

use Slim\App;
use API\Lib\{Controller, Auth};
use API\Models\User\UsersQuery;

class User extends Controller
{    
    protected $o_auth;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
        
        $this->o_auth = new Auth(UsersQuery::class);
    }
    
    protected function GET() : void {
        $o_user = $this->o_auth->GetCurrentUser();        

        $this->o_response->withJson($o_user->toArray());
    }
}