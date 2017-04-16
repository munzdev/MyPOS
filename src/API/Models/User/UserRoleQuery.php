<?php

namespace API\Models\User;

use API\Lib\Interfaces\Models\User\IUserRole;
use API\Lib\Interfaces\Models\User\IUserRoleCollection;
use API\Lib\Interfaces\Models\User\IUserRoleQuery;
use API\Models\ORM\User\UserRoleQuery as UserRoleQueryORM;
use API\Models\Query;

class UserRoleQuery extends Query implements IUserRoleQuery
{
    public function find(): IUserRoleCollection
    {
        $userRoles = UserRoleQueryORM::create()->find();

        $userRoleCollection = $this->container->get(IUserRoleCollection::class);
        $userRoleCollection->setCollection($userRoles);

        return $userRoleCollection;
    }

    public function findPk($id): IUserRole
    {
        $userRole = UserRoleQueryORM::create()->findPk($id);

        $userRoleModel = $this->container->get(IUserRole::class);
        $userRoleModel->setModel($userRole);

        return $userRoleModel;
    }
}
