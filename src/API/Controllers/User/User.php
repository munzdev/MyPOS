<?php

namespace API\Controllers\User;

use API\Lib\Interfaces\IAuth;
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

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $currentUser = $auth->getCurrentUser();

        $users = UserQuery::create()
                            ->useEventUserQuery()
                                ->filterByEventid($currentUser->getEventUser()->getEventid())
                            ->endUse()
                            ->joinWithEventUser()
                            ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                            ->find()
                            ->toArray();

        foreach ($users as &$user) {
            $user['EventUser'] = $user['EventUsers'][0];
            unset($user['EventUsers']);
            $user = $this->cleanupUserData($user);
        }

        $this->withJson($users);
    }
}
