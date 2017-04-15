<?php

namespace API\Models\OIP;

use API\Lib\Container;
use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressCollection;
use API\Models\Collection;

class OrderInProgressCollection extends Collection implements IOrderInProgressCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IOrderInProgress::class);
    }
}