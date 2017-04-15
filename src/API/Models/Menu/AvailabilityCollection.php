<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IAvailabilityCollection;
use API\Models\Collection;

class AvailabilityCollection extends Collection implements IAvailabilityCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IAvailability::class);
    }
}