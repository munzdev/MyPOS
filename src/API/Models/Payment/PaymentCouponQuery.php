<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\IPaymentCoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentCouponCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentCouponQuery;
use API\Models\ORM\PaymentCouponing\PaymentCouponQuery as PaymentCouponQueryORM;
use API\Models\Query;

class PaymentCouponQuery extends Query implements IPaymentCouponQuery
{
    public function find(): IPaymentCouponCollection
    {
        $paymentCoupons = PaymentCouponQueryORM::create()->find();

        $paymentCouponCollection = $this->container->get(IPaymentCouponCollection::class);
        $paymentCouponCollection->setCollection($paymentCoupons);

        return $paymentCouponCollection;
    }

    public function findPk($id): ?IPaymentCoupon
    {
        $paymentCoupon = PaymentCouponQueryORM::create()->findPk($id);

        if(!$paymentCoupon) {
            return null;
        }

        $paymentCouponModel = $this->container->get(IPaymentCoupon::class);
        $paymentCouponModel->setModel($paymentCoupon);

        return $paymentCouponModel;
    }
}
