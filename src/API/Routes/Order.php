<?php

$app->group('/Order', function () {
    $this->any('', new API\Controllers\Order\Order($this))
         ->setName('Order');
    $this->any('/{id:[0-9]+}', new API\Controllers\Order\OrderModify($this))
         ->setName('Order-Modify');
    $this->any('/Info/{id:[0-9]+}', new API\Controllers\Order\OrderInfo($this))
         ->setName('Order-Info');
    $this->any('/Unbilled/{id:[0-9]+}/{all}', new API\Controllers\Order\OrderUnbilled($this))
         ->setName('Order-Unbilled');
});