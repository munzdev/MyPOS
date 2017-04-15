<?php

namespace API\Controllers\Login;

use API\Lib\Controller;
use API\Lib\Exceptions\GeneralException;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IRememberMe;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Lib\RememberMe;
use API\Models\ORM\User\UserQuery;
use Propel\Runtime\Propel;
use Respect\Validation\Validator;
use Slim\App;

class Login extends Controller
{
    /**
     *
     * @var IAuth
     */
    protected $auth;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container['db'];
        $this->auth =$this->container->get(IAuth::class);
    }

    protected function post() : void
    {
        $validators = array(
            'username' => Validator::alnum()->noWhitespace()->length(1),
            'password' => Validator::alnum()->noWhitespace()->length(1),
            'rememberMe' => Validator::boolType()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->json);

        $result = $this->auth->checkLogin($this->json['username'], $this->json['password']);

        if (!$result) {
            throw new GeneralException("Login failed");
        }

        if ($this->json['rememberMe']) {
            $userid = $this->auth->getCurrentUser()->getUserid();

            $rememberMe = $this->container->get(IRememberMe::class);
            $hash = $rememberMe->remember($userid);

            $userQuery = $this->container->get(IUserQuery::class);

            $user = $userQuery->findPk($userid);
            $user->setAutologinHash($hash)
                 ->save();
        }

        $this->withJson(true);
    }

    protected function get() : void
    {
        if ($this->auth->isLoggedIn()) {
            $this->withJson(array('username' => $this->auth->getCurrentUser()->getUsername(),
                                  'userid' => $this->auth->getCurrentUser()->getUserid()));
            return;
        }

        $rememberMe = $this->container->get(IRememberMe::class);
        $userid = $rememberMe->parseCookie();

        if ($userid !== false) {
            $userQuery = $this->container->get(IUserQuery::class);

            $user = $userQuery->findPk($userid);

            $newHash = $rememberMe->validateHash((string)$user->getAutologinHash());

            if ($newHash === false) {
                throw new GeneralException("Autologin Failed");
            }

            $user->setAutologinHash($newHash)
                 ->save();

            $this->auth->doLogin($user->getUsername());

            $this->withJson(array('username' => $user->getUsername(),
                                  'userid' => $user->getUserid(),
                                  'rememberMe' => true));
        }
    }

    protected function delete() : void
    {
        $userQuery = $this->container->get(IUserQuery::class);
        $rememberMe = $this->container->get(IRememberMe::class);

        $userid = $this->auth->getCurrentUser()->getUserid();

        $user = $userQuery->findPk($userid);
        $user->setAutologinHash(null)
             ->save();

        $this->auth->logout();
        $rememberMe->destroy();

        $this->withJson(true);
    }
}
