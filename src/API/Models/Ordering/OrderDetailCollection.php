<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailCollection;
use API\Models\Collection;

class OrderDetailCollection extends Collection implements IOrderDetailCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IOrderDetail::class);
    }
}