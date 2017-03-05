<?php

namespace API\Lib\Interfaces\Models\Payment;

use API\Lib\Interfaces\Models\IModel;

interface IPaymentCoupon extends IModel {
    /**
     * @return int
     */
    function getCouponid();

    /**
     * @return ICoupon
     */
    function getCoupon();

    /**
     * @return int
     */
    function getPaymentRecievedid();

    /**
     * @return IPaymentRecieved
     */
    function getPaymentRecieved();

    /**
     * @return float
     */
    function getValueUsed();

    /**
     *
     * @param int $couponid Description
     * @return IPaymentCoupon Description
     */
    function setCouponid($couponid);

    /**
     *
     * @param ICoupon $coupon Description
     * @return IPaymentCoupon Description
     */
    function setCoupon($coupon);

    /**
     *
     * @param int $paymentRecievedid Description
     * @return IPaymentCoupon Description
     */
    function setPaymentRecievedid($paymentRecievedid);

    /**
     *
     * @param IPaymentRecieved $paymentRecieved Description
     * @return IPaymentCoupon Description
     */
    function setPaymentRecieved($paymentRecieved);

    /**
     *
     * @param float $valueUsed Description
     * @return IPaymentCoupon Description
     */
    function setValueUsed($valueUsed);
}