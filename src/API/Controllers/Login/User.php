<?php

namespace API\Controllers\Login;

use API\Lib\Controller;
use API\Lib\Interfaces\IAuth;
use Slim\App;

class User extends Controller
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
        $this->auth = $this->container->get(IAuth::class);
    }

    protected function get() : void
    {
        $user = $this->auth->getCurrentUser();

        $return = $user->toArray();
        $return["EventUser"] = $user->getEventUsers()->getFirst()->toArray();

        $this->withJson($return);
    }
}
