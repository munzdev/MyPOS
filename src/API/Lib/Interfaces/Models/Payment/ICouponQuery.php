<?php

namespace API\Lib\Interfaces\Models\Payment;

use API\Lib\Interfaces\Models\IQuery;

interface ICouponQuery extends IQuery {
    public function getValidCoupon(int $eventid, int $code): ?ICouponValue;
}