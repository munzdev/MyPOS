<?php

namespace API\Controllers\Login;

use API\Lib\{Auth, Controller, RememberMe};
use API\Lib\Exceptions\GeneralException;
use API\Models\User\UsersQuery;
use Respect\Validation\Validator;
use Slim\App;

class Login extends Controller
{    
    protected $o_auth;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
        
        $this->o_auth = new Auth(UsersQuery::create(), 
                                 $this->o_app->getContainer()['settings']['Auth']['RememberMe_PrivateKey']);
    }
    
    protected function POST() : void {        
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
    
    protected function GET() : void {
        
        if($this->o_auth->IsLoggedIn())
        {
            $this->o_response->withJson(array('username' => $this->o_auth->GetCurrentUser()['Username']));
            return;
        }
        
        $o_rememberMe = new RememberMe($this->o_auth->GetPrivateKey());
        $i_userid = $o_rememberMe->parseCookie();
        
        if($i_userid !== false)
        {
            $o_user = UsersQuery::create()->findPk($i_userid);
            $b_result = $o_rememberMe->validateHash($o_user->getAutologinHash());
            
            $this->o_auth->DoLogin($o_user->getUsername());
            
            $this->o_response->withJson(array('username' => $o_user->getUsername(),
                                              'rememberMe' => true));
        }
        else
        {
            throw new GeneralException("Autologin Failed");
        }
    }
}