<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWith;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWithCollection;
use API\Models\Collection;

class OrderDetailMixedWithCollection extends Collection implements IOrderDetailMixedWithCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IOrderDetailMixedWith::class);
    }
}