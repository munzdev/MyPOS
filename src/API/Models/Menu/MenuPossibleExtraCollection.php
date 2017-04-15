<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtra;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtraCollection;
use API\Models\Collection;

class MenuPossibleExtraCollection extends Collection implements IMenuPossibleExtraCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IMenuPossibleExtra::class);
    }
}