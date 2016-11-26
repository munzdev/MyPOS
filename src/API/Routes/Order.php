<?php

$app->group('/Order', function () {
    $this->any('', new API\Controllers\Order\Order($this))
         ->setName('Order');
    $this->any('/{id:[0-9]+}', new API\Controllers\Order\OrderModify($this))
         ->setName('OrderModify');
    $this->any('/Info/{id:[0-9]+}', new API\Controllers\Order\OrderInfo($this))
         ->setName('OrderInfo');
});