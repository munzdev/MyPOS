<?php

namespace API\Controllers\DB\Payment;

use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeQuery;
use API\Lib\SecurityController;
use Slim\App;

class PaymentType extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $paymentTypeQuery = $this->container->get(IPaymentTypeQuery::class);
        $paymentTypes = $paymentTypeQuery->find();

        $this->withJson($paymentTypes->toArray());
    }
}
