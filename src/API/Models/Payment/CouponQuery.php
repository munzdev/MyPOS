<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\Payment\ICouponCollection;
use API\Lib\Interfaces\Models\Payment\ICouponQuery;
use API\Models\ORM\Couponing\CouponQuery as CouponQueryORM;
use API\Models\Query;

class CouponQuery extends Query implements ICouponQuery
{
    public function find(): ICouponCollection
    {
        $coupons = CouponQueryORM::create()->find();

        $couponCollection = $this->container->get(ICouponCollection::class);
        $couponCollection->setCollection($coupons);

        return $couponCollection;
    }

    public function findPk($id): ICoupon
    {
        $coupon = CouponQueryORM::create()->findPk($id);

        $couponModel = $this->container->get(ICoupon::class);
        $couponModel->setModel($coupon);

        return $couponModel;
    }
}
