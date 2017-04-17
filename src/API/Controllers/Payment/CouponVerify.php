<?php

namespace API\Controllers\Payment;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Models\ORM\Payment\CouponQuery;
use API\Models\ORM\Payment\Map\CouponTableMap;
use API\Models\ORM\Payment\Map\PaymentCouponTableMap;
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
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $coupon = CouponQuery::create()
                                ->leftJoinPaymentCoupon()
                                ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
                                ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                ->filterByCode($this->args['code'])
                                ->groupBy(CouponTableMap::COL_COUPONID)
                                ->find();

        if ($coupon->count() == 0
            || $coupon->getFirst()->getVirtualColumn('Value') == 0
        ) {
            return;
        }

        $this->withJson($coupon->getFirst()->toArray());
    }
}
