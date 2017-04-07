<?php

namespace API\Controllers\Login;

use API\Lib\Controller;
use API\Lib\Interfaces\IAuth;
use API\Models\ORM\Event\Map\EventUserTableMap;
use Slim\App;

class User extends Controller
{
    protected $auth;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];

        $this->auth = $this->app->getContainer()->get(IAuth::class);
    }

    protected function get() : void
    {
        $user = $this->auth->getCurrentUser();

        $return = $user->toArray();
        $return[EventUserTableMap::getTableMap()->getPhpName()] = $user->getEventUser()->toArray();

        $this->withJson($return);
    }
}
