<?php

$app->group('/Product', function () {
    $this->any('', new API\Controllers\Product\Product($this))
         ->setName('Product');
});