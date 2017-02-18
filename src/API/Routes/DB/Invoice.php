<?php

$app->group(
    '/DB/Invoice',
    function () {
        $this->any('/InvoiceType', new API\Controllers\DB\Invoice\InvoiceType($this))
            ->setName('DB-Invoice-InvoiceType');
    }
);
