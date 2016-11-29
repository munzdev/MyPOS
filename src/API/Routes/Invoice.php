<?php

$app->group('/Invoice', function () {
    $this->any('/Customer', new API\Controllers\Invoice\Customer($this))
         ->setName('Payment-Customer');
    $this->any('/Customer/{name}', new API\Controllers\Invoice\CustomerSearch($this))
         ->setName('Payment-Customer-Search');
});