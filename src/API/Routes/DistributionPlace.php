<?php

$app->group('/DistributionPlace', function () {
    $this->any('', new API\Controllers\DistributionPlace\DistributionPlace($this))
         ->setName('DistributionPlace');
    $this->any('/Availability', new API\Controllers\DistributionPlace\DistributionPlaceAvailability($this))
         ->setName('DistributionPlace-Availability');
    $this->any('/Amount', new API\Controllers\DistributionPlace\DistributionPlaceAmount($this))
         ->setName('DistributionPlace-Amount');
    $this->any('/Printing/{DistributionGivingOutid}', new API\Controllers\DistributionPlace\Printing($this))
         ->setName('DistributionPlace-Printing');
});