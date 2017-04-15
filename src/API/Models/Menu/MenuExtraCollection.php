<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuExtra;
use API\Lib\Interfaces\Models\Menu\IMenuExtraCollection;
use API\Models\Collection;

class MenuExtraCollection extends Collection implements IMenuExtraCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IMenuExtra::class);
    }
}