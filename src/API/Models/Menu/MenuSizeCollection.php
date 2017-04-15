<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Lib\Interfaces\Models\Menu\IMenuSizeCollection;
use API\Models\Collection;

class MenuSizeCollection extends Collection implements IMenuSizeCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IMenuSize::class);
    }
}