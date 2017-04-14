<?php

namespace API\Models\User;

use API\Lib\Interfaces\Models\User\IUserRoleCollection;
use API\Lib\Interfaces\Models\User\IUserRoleQuery;
use API\Models\ORM\User\UserRoleQuery;
use API\Models\Query;

/**
 * Skeleton subclass for performing query and update operations on the 'user_role' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserRoleQuery extends Query implements IUserRoleQuery
{
    public function find(): IUserRoleCollection
    {
        $userRoles = UserRoleQuery::create()->find();

        $userRoleCollection = $this->container->get(IUserRoleCollection::class);
        $userRoleCollection->setCollection($userRoles);

        return $userRoleCollection;
    }

}
