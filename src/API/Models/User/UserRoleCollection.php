<?php

namespace API\Models\User;

use API\Lib\Container;
use API\Lib\Interfaces\Models\User\IUserRole;
use API\Lib\Interfaces\Models\User\IUserRoleCollection;
use API\Models\Collection;

class UserRoleCollection extends Collection implements IUserRoleCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IUserRole::class);
    }
}