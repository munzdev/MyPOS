<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentCoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Models\Model;
use API\Models\ORM\Payment\PaymentCoupon as PaymentCouponORM;

/**
 * Skeleton subclass for representing a row from the 'payment_coupon' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class PaymentCoupon extends Model implements IPaymentCoupon
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new PaymentCouponORM());
    }

    public function getCoupon(): ICoupon
    {
        $coupon = $this->model->getCoupon();

        $couponModel = $this->container->get(ICoupon::class);
        $couponModel->setModel($coupon);

        return $couponModel;
    }

    public function getCouponid(): int
    {
        return $this->model->getCouponid();
    }

    public function getPaymentRecieved(): IPaymentRecieved
    {
        $paymentRecieved = $this->model->getPaymentRecieved();

        $paymentRecievedModel = $this->container->get(IPaymentRecieved::class);
        $paymentRecievedModel->setModel($coupon);

        return $paymentRecievedModel;
    }

    public function getPaymentRecievedid(): int
    {
        return $this->model->getPaymentRecievedid();
    }

    public function getValueUsed(): float
    {
        return $this->model->getValueUsed();
    }

    public function setCoupon($coupon): IPaymentCoupon
    {
        $this->model->setCoupon($coupon->getModel());
        return $this;
    }

    public function setCouponid($couponid): IPaymentCoupon
    {
        $this->model->setCouponid($couponid);
        return $this;
    }

    public function setPaymentRecieved($paymentRecieved): IPaymentCoupon
    {
        $this->model->setPaymentRecieved($paymentRecieved->getModel());
        return $this;
    }

    public function setPaymentRecievedid($paymentRecievedid): IPaymentCoupon
    {
        $this->model->setPaymentRecievedid($paymentRecievedid);
        return $this;
    }

    public function setValueUsed($valueUsed): IPaymentCoupon
    {
        $this->model->setValueUsed($valueUsed);
        return $this;
    }

}
