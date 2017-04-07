<?php

namespace API\Controllers\Login;

use API\Lib\Controller;
use API\Lib\Exceptions\GeneralException;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\RememberMe;
use API\Models\ORM\User\UserQuery;
use Propel\Runtime\Propel;
use Respect\Validation\Validator;
use Slim\App;

class Login extends Controller
{
    protected $auth;
    protected $privateKey;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];

        $this->auth = $this->app->getContainer()->get(IAuth::class);
        $this->privateKey = $this->app->getContainer()['settings']['Auth']['RememberMe_PrivateKey'];
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

            $rememberMe = new RememberMe($this->privateKey);
            $hash = $rememberMe->remember($userid);

            Propel::disableInstancePooling();

            UserQuery::create()->findPk($userid)
                               ->setAutologinHash($hash)
                               ->save();

            Propel::enableInstancePooling();
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

        $rememberMe = new RememberMe($this->privateKey);
        $userid = $rememberMe->parseCookie();

        if ($userid !== false) {
            $user = UserQuery::create()->findPk($userid);
            $newHash = $rememberMe->validateHash((string)$user->getAutologinHash());

            if ($newHash === false) {
                throw new GeneralException("Autologin Failed");
            }

            $user->setAutologinHash($newHash)->save();

            $this->auth->doLogin($user->getUsername());

            $this->withJson(array('username' => $user->getUsername(),
                                  'userid' => $user->getUserid(),
                                  'rememberMe' => true));
        }
    }

    protected function delete() : void
    {
        $userid = $this->auth->getCurrentUser()->getUserid();

        UserQuery::create()->findPk($userid)
                           ->setAutologinHash(null)
                           ->save();

        $this->auth->logout();
        RememberMe::destroy();

        $this->withJson(true);
    }
}
