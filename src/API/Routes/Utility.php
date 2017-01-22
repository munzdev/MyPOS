<?php

$app->group('/Utility', function () {
    $this->any('/Constants', new API\Controllers\Utility\Constants($this))
         ->setName('Utility-Constants');

    $this->any('/MaturityDate', new API\Controllers\Utility\MaturityDate($this))
         ->setName('Utility-MaturityDate');

    $this->any('/KeepSessionAlive', new API\Controllers\Utility\KeepSessionAlive($this))
         ->setName('Utility-KeepSessionAlive');
});