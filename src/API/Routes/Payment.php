<?php

$app->group('/Payment', function () {
    $this->any('/Coupon/Verify/{code}', new API\Controllers\Payment\CouponVerify($this))
         ->setName('Payment-Coupon-Verify');
});