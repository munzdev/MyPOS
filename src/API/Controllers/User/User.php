<?php

namespace API\Controllers\User;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Lib\SecurityController;
use API\Models\ORM\User\UserQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Slim\App;

class User extends SecurityController
{
    protected $usersQuery;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container['db'];
    }

    protected function get() : void
    {
        $userQuery = $this->container->get(IUserQuery::class);
        $auth = $this->container->get(IAuth::class);
        $currentUser = $auth->getCurrentUser();

        $users = $userQuery->getUsersByEventid($currentUser->getEventUsers()->getFirst()->getEventid());
        $usersArray = $users->toArray();

        foreach ($usersArray as &$user) {
            $user['EventUser'] = $user['EventUsers'][0];
            unset($user['EventUsers']);
            $user = $this->cleanupUserData($user);
        }

        $this->withJson($usersArray);
    }
}
