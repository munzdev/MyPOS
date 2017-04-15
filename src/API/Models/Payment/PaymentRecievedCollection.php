<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedCollection;
use API\Models\Collection;

class PaymentRecievedCollection extends Collection implements IPaymentRecievedCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IPaymentRecieved::class);
    }
}