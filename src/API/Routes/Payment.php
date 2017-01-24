<?php

$app->group('/Payment', function () {
    $this->any('', new API\Controllers\Payment\Payment($this))
         ->setName('Payment');
    $this->any('/Coupon/Verify/{code}', new API\Controllers\Payment\CouponVerify($this))
         ->setName('Payment-Coupon-Verify');
});