<?php

namespace API\Controllers\DB\Payment;

use API\Lib\SecurityController;
use API\Models\Payment\PaymentTypeQuery;
use Slim\App;

class PaymentType extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $paymentTypes = PaymentTypeQuery::create()->find();

        $this->withJson($paymentTypes->toArray());
    }
}
