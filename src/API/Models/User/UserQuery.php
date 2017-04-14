<?php

namespace API\Models\User;

use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\IUserCollection;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Models\Event\Map\EventUserTableMap;
use API\Models\ORM\User\UserQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserQuery implements IUserQuery
{
    public function getActiveAdminUserByUsername($username) : IUser
    {
        $user = UserQuery::create()
                    ->filterByUsername($username)
                    ->filterByIsAdmin(true)
                    ->filterByActive(true)
                    ->findOne();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getActiveEventUserByUsername($username) : IUserCollection
    {
        $users = UserQuery::create()
                    ->useEventUserQuery()
                        ->useEventQuery()
                            ->filterByActive(true)
                        ->endUse()
                    ->endUse()
                    ->filterByUsername($username)
                    ->filterByActive(true)
                    ->with(EventUserTableMap::getTableMap()->getPhpName())
                    ->find();

        $userCollection = $this->container->get(IUserCollection::class);
        $userCollection->setCollection($users);

        return $userCollection;
    }

}
