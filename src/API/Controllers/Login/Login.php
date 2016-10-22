<?php

namespace API\Controllers\Login;

use Slim\App;
use API\Lib\{Controller, Auth};
use Respect\Validation\Validator;
use API\Models\User\UsersQuery;

class Login extends Controller
{    
    protected $o_auth;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
        
        $this->o_auth = new Auth(UsersQuery::create());
    }
    
    protected function POST() {        
        $a_validators = array(
            'username' => Validator::alnum()->noWhitespace()->length(1),
            'password' => Validator::alnum()->noWhitespace()->length(1),
            'rememberMe' => Validator::boolType()
        );
        
        $this->validate($a_validators);        
        
        $b_result = $this->o_auth->CheckLogin($this->a_json['username'], 
                                              $this->a_json['password'], 
                                              $this->a_json['rememberMe']);
        
        $this->o_response->withJson($b_result);
    }
    
    protected function GET() {
        $a_user = $this->o_auth->GetCurrentUser();

        unset($a_user['password']);
        unset($a_user['autologin_hash']);

        $this->o_response->withJson($a_user);
    }
}