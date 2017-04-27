<?php

namespace API\Lib\Interfaces\Models\Payment;

// TODO Refactore to remove this file!!!
interface ICouponValue extends ICoupon
{
    /**
     * @return float
     */
    function getUsedValue();

    /**
     *
     * @param float $value Description
     * @return ICouponValue Description
     */
    function setUsedValue(float $value);
}