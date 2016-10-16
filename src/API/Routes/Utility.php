<?php

$app->group('/Utility', function () {
    $this->map(['GET'], '/Constants', new API\Controllers\Utility\Constants($this))
         ->setName('Utility-Constants');
    
    $this->map(['GET'], '/KeepSessionAlive', new API\Controllers\Utility\KeepSessionAlive($this))
         ->setName('Utility-KeepSessionAlive');
});