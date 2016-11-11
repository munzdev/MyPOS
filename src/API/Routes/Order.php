<?php

$app->group('/Order', function () {
    $this->any('', new API\Controllers\Order\Order($this))
         ->setName('Order');
});