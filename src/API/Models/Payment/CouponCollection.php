<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\Payment\ICouponCollection;
use API\Models\Collection;

class CouponCollection extends Collection implements ICouponCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(ICoupon::class);
    }
}