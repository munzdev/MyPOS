<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\ICouponValue;

// TODO Refactore to remove this file!!!
class CouponValue extends Coupon implements ICouponValue
{
    /**
     * @return float
     */
    function getUsedValue()
    {
        return $this->model->getVirtualColumn('Value');
    }

    /**
     *
     * @param float $value Description
     * @return ICouponValue Description
     */
    function setUsedValue(float $value)
    {
        $this->model->setVirtualColumn('Value', $value);
        return $this;
    }
}
