<?php

namespace API\Controllers\Payment;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Payment\CouponQuery;
use API\Models\Payment\Map\CouponTableMap;
use API\Models\Payment\Map\PaymentCouponTableMap;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;

class CouponVerify extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators = array(
            'code' => v::alnum()->length(1),
        );

        $this->validate($a_validators, $this->a_args);
    }

    protected function GET() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_coupon = CouponQuery::create()
                                ->leftJoinPaymentCoupon()
                                ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
                                ->filterByEventid($o_user->getEventUser()->getEventid())
                                ->filterByCode($this->a_args['code'])
                                ->groupBy(CouponTableMap::COL_COUPONID)
                                ->find();

        if($o_coupon->count() == 0 ||
           $o_coupon->getFirst()->getVirtualColumn('Value') == 0)
            return;

        $this->withJson($o_coupon->getFirst()->toArray());
    }

}