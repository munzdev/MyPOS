<?php

namespace API\Controllers\Payment;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Payment\ICouponQuery;
use API\Lib\SecurityController;
use Respect\Validation\Validator as v;
use Slim\App;

class CouponVerify extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'code' => v::alnum()->length(1),
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $couponQuery = $this->container->get(ICouponQuery::class);
        $user = $auth->getCurrentUser();

        $coupon = $couponQuery->getValidCoupon($user->getEventUsers()->getFirst()->getEventid(), $this->args['code']);

        if (!$coupon) {
            return;
        }

        $this->withJson($coupon->toArray());
    }
}
