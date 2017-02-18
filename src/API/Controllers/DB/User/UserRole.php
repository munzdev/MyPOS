<?php

namespace API\Controllers\DB\User;

use API\Lib\SecurityController;
use API\Models\User\UserRoleQuery;
use Slim\App;

class UserRole extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $userRoles = UserRoleQuery::create()->find();

        $this->withJson($userRoles->toArray());
    }
}
