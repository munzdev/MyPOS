<?php

namespace API\Controllers\Login;

use API\Lib\Auth;
use API\Lib\Controller;
use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\UserQuery;
use Slim\App;

class User extends Controller
{
    protected $auth;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];

        $this->auth = $this->app->getContainer()->get('Auth');
    }

    protected function get() : void
    {
        $user = $this->auth->getCurrentUser();

        $return = $user->toArray();
        $return[EventUserTableMap::getTableMap()->getPhpName()] = $user->getEventUser()->toArray();

        $this->withJson($return);
    }
}
