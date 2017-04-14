<?php

namespace API\Models\User;

use API\Lib\Container;
use API\Lib\Interfaces\Models\User\IUserRole;
use API\Models\Model;

/**
 * Skeleton subclass for representing a row from the 'user_role' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserRole extends Model implements IUserRole
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new UserORM());
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getUserRoleid(): int
    {
        return $this->model->getUserRoleid();
    }

    public function setName($name): IUserRole
    {
        $this->model->setName($name);
        return $this;
    }

    public function setUserRoleid($userRoleid): IUserRole
    {
        $this->model->setUserRoleid($userRoleid);
        return $this;
    }

}
