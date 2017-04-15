<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtra;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtraCollection;
use API\Models\Collection;

class OrderDetailExtraCollection extends Collection implements IOrderDetailExtraCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IOrderDetailExtra::class);
    }
}