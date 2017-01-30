<?php

$app->group('/DistributionPlace', function () {
    $this->any('', new API\Controllers\DistributionPlace\DistributionPlace($this))
         ->setName('DistributionPlace');
});