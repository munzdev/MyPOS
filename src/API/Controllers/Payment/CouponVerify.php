<?php

namespace API\Controllers\Payment;

use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Models\Payment\CouponQuery;
use API\Models\Payment\Map\CouponTableMap;
use API\Models\Payment\Map\PaymentCouponTableMap;
use Respect\Validation\Validator as v;
use Slim\App;

class CouponVerify extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    public function any() : void
    {
        $validators = array(
            'code' => v::alnum()->length(1),
        );

        $this->validate($validators, $this->args);
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $coupon = CouponQuery::create()
                                ->leftJoinPaymentCoupon()
                                ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
                                ->filterByEventid($user->getEventUser()->getEventid())
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
