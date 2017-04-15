<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderCollection;
use API\Models\Collection;

class OrderCollection extends Collection implements IOrderCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IOrder::class);
    }
}