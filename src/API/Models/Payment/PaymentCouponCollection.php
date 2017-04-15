<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Payment\IPaymentCoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentCouponCollection;
use API\Models\Collection;

class PaymentCouponCollection extends Collection implements IPaymentCouponCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IPaymentCoupon::class);
    }
}