<?php

namespace API\Controllers\User;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\UserQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Slim\App;

class User extends SecurityController
{
    protected $o_usersQuery;

    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    protected function GET() : void {
        $o_currentUser = Auth::GetCurrentUser();

        $a_users = UserQuery::create()
                    ->useEventUserQuery()
                        ->filterByEventid($o_currentUser->getEventUser()->getEventid())
                    ->endUse()
                    ->joinWithEventUser()
                    ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                    ->find()
                    ->toArray();

        $a_return = [];

        foreach($a_users as &$a_user)
        {
            $a_user['EventUser'] = $a_user['EventUsers'][0];
            unset($a_user['EventUsers']);
            $a_user = $this->cleanupUserData($a_user);
        }

        $this->withJson($a_users);
    }
}