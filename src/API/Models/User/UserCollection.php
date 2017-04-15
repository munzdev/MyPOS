<?php

namespace API\Models\User;

use API\Lib\Container;
use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\IUserCollection;
use API\Models\Collection;

class UserCollection extends Collection implements IUserCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IUser::class);
    }
}