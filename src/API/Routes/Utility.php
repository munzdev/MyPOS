<?php

$app->group('/Utility', function () {
    $this->map(['GET'], '/Constants', API\Controllers\Utility\Constants::class)
         ->setName('Utility-Constants');
    
    $this->map(['GET'], '/KeepSessionAlive', API\Controllers\Utility\KeepSessionAlive::class)
         ->setName('Utility-KeepSessionAlive');
});