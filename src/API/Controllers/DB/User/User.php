<?php

namespace API\Controllers\DB\User;

use API\Lib\AdminController;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\User\IUserQuery;
use Slim\App;

class User extends AdminController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $userQuery = $this->container->get(IUserQuery::class);
        $users = $userQuery->find();
        $usersArray = $users->toArray();

        foreach ($usersArray as &$user) {
            $isAdmin = $user['IsAdmin'];
            $user = $this->cleanupUserData($user);
            $user['IsAdmin'] = $isAdmin;
        }

        $this->withJson($usersArray);
    }
}
