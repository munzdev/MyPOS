<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\Payment\ICouponCollection;
use API\Lib\Interfaces\Models\Payment\ICouponQuery;
use API\Models\ORM\Payment\CouponQuery as CouponQueryORM;
use API\Models\ORM\Payment\Map\CouponTableMap;
use API\Models\ORM\Payment\Map\PaymentCouponTableMap;
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

    public function findPk($id): ?ICoupon
    {
        $coupon = CouponQueryORM::create()->findPk($id);

        if(!$coupon) {
            return null;
        }

        $couponModel = $this->container->get(ICoupon::class);
        $couponModel->setModel($coupon);

        return $couponModel;
    }

    public function getValidCoupon(int $eventid, int $code) : ?ICoupon
    {
        $coupons = CouponQueryORM::create()
            ->leftJoinPaymentCoupon()
            ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
            ->filterByEventid($eventid)
            ->filterByCode($code)
            ->groupBy(CouponTableMap::COL_COUPONID)
            ->find();

        if ($coupons->count() == 0
            || $coupons->getFirst()->getVirtualColumn('Value') == 0
        ) {
            return null;
        }

        $couponModel = $this->container->get(ICoupon::class);
        $couponModel->setModel($coupons->getFirst());

        return $couponModel;
    }
}
