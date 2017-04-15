<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Payment\IPaymentType;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeCollection;
use API\Models\Collection;

class PaymentTypeCollection extends Collection implements IPaymentTypeCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IPaymentType::class);
    }
}