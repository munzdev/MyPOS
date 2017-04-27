<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailUnbilled;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailUnbilledCollection;
use API\Models\Collection;

// TODO Remove this file!!!
class OrderDetailUnbilledCollection extends Collection implements IOrderDetailUnbilledCollection
{
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IOrderDetailUnbilled::class);
    }
}