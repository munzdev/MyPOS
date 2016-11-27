<?php

$app->group('/Event', function () {
    $this->any('/Printer', new API\Controllers\Event\Printer($this))
         ->setName('Event-Printer');
});