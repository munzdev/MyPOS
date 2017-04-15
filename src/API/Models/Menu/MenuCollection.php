<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuCollection;
use API\Models\Collection;

class MenuCollection extends Collection implements IMenuCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IMenu::class);
    }
}