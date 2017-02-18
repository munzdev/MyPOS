<?php

$app->group(
    '/DB/Payment',
    function () {
        $this->any('/PaymentType', new API\Controllers\DB\Payment\PaymentType($this))
            ->setName('DB-Payment-PaymentType');
    }
);
