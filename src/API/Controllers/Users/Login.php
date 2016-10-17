<?php

namespace API\Controllers\Users;

use Slim\App;
use API\Lib\{Controller, Login as LibLogin};
use Slim\Http\Request;
use Slim\Http\Response;
use API\Models\User\UsersQuery;

class Login extends Controller
{    
    protected $o_login;
    
    public function __construct(App $o_app)
    {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
        
        $this->o_login = new LibLogin(new UsersQuery());
    }

    protected function OPTIONS(Request $o_request, Response $o_response, $a_args) {
        $o_response->withJson($this->o_login->IsLoggedIn());
    }
    
    protected function GET(Request $o_request, Response $o_response, $a_args) {
        $a_user = $this->o_login->GetCurrentUser();

        unset($a_user['password']);
        unset($a_user['autologin_hash']);

        $o_response->withJson($a_user);
    }
}