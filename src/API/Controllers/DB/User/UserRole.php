<?php

namespace API\Controllers\DB\User;

use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\User\IUserRoleQuery;
use API\Lib\SecurityController;
use Slim\App;

class UserRole extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $userRoleQuery = $this->container->get(IUserRoleQuery::class);
        $userRoles = $userRoleQuery->find();

        $this->withJson($userRoles->toArray());
    }
}
