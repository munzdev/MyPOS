<?php

namespace API\Models\User;

use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\IUserCollection;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Models\ORM\Event\Map\EventUserTableMap;
use API\Models\ORM\User\UserQuery as UserQueryORM;
use API\Models\Query;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class UserQuery extends Query implements IUserQuery
{
    public function find(): IUserCollection
    {
        $users = UserQueryORM::create()->find();

        $userCollection = $this->container->get(IUserCollection::class);
        $userCollection->setCollection($users);

        return $userCollection;
    }

    public function findPk($id): ?IUser
    {
        $user = UserQueryORM::create()->findPk($id);

        if(!$user) {
            return null;
        }

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getActiveAdminUserByUsername($username) : ?IUser
    {
        $user = UserQueryORM::create()
                    ->filterByUsername($username)
                    ->filterByIsAdmin(true)
                    ->filterByIsDeleted()
                    ->findOne();

        if(!$user) {
            return null;
        }

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getActiveEventUserByUsername($username) : IUserCollection
    {
        $users = UserQueryORM::create()
                    ->useEventUserQuery()
                        ->useEventQuery()
                            ->filterByActive(true)
                        ->endUse()
                    ->endUse()
                    ->filterByUsername($username)
                    ->filterByIsDeleted()
                    ->with(EventUserTableMap::getTableMap()->getPhpName())
                    ->find();

        $userCollection = $this->container->get(IUserCollection::class);
        $userCollection->setCollection($users);

        return $userCollection;
    }

    public function getUsersByEventid($eventid) : IUserCollection {
        $users = UserQueryORM::create()
                            ->useEventUserQuery()
                                ->filterByEventid($eventid)
                            ->endUse()
                            ->joinWithEventUser()
                            ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                            ->find();

        $userCollection = $this->container->get(IUserCollection::class);
        $userCollection->setCollection($users);

        return $userCollection;
    }

    public function getCallbacks(int $eventid) : IUserCollection {
        $users = UserQueryORM::create()
                    ->filterByCallRequest(null, Criteria::NOT_EQUAL)
                    ->useEventUserQuery()
                        ->filterByEventid($eventid)
                    ->endUse()
                    ->find();

        $userCollection = $this->container->get(IUserCollection::class);
        $userCollection->setCollection($users);

        return $userCollection;
    }
}
