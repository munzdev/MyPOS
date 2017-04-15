<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuGroupCollection;
use API\Models\Collection;

class MenuGroupCollection extends Collection implements IMenuGroupCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IMenuGroup::class);
    }
}