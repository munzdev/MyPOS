<?php

$app->group('/Invoice', function () {
    $this->any('', new API\Controllers\Invoice\Invoice($this))
         ->setName('Invoice');
    $this->any('/Customer', new API\Controllers\Invoice\Customer($this))
         ->setName('Invoice-Customer');
    $this->any('/Customer/{name}', new API\Controllers\Invoice\CustomerSearch($this))
         ->setName('Invoice-Customer-Search');
    $this->any('/Printing/{Invoiceid}', new API\Controllers\Invoice\Printing($this, false))
         ->setName('Invoice-Printing');
    $this->any('/Printing/WithPayments/{Invoiceid}', new API\Controllers\Invoice\Printing($this, true))
         ->setName('Invoice-WithPayments-Printing');
});