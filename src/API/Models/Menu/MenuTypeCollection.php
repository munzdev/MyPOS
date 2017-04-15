<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuType;
use API\Lib\Interfaces\Models\Menu\IMenuTypeCollection;
use API\Models\Collection;

class MenuTypeCollection extends Collection implements IMenuTypeCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IMenuType::class);
    }
}