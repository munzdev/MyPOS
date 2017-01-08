<?php

namespace API\Controllers\User;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\UserQuery;
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

        $o_users = UserQuery::create()
                    ->useEventUserQuery()
                        ->filterByEventid($o_currentUser->getEventUser()->getEventid())
                    ->endUse()
                    ->with(EventUserTableMap::getTableMap()->getPhpName())
                    ->find();

        $a_return = [];

        foreach($o_users as $o_user)
        {
            $o_user->setPassword(null)
                   ->setAutologinHash(null)
                   ->setIsAdmin(null)
                   ->setCallRequest(null);

            $a_user = $o_user->toArray();
            $a_user[EventUserTableMap::getTableMap()->getPhpName()] = $o_user->getEventUsers()
                                                                             ->getFirst()
                                                                             ->setBeginMoney(null)
                                                                             ->toArray();

            $a_return[] = $a_user;
        }

        $this->withJson($a_return);
    }
}