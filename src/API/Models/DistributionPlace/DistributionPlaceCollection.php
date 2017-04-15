<?php

namespace API\Models\DistributionPlace;

use API\Lib\Container;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlace;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceCollection;
use API\Models\Collection;

class DistributionPlaceCollection extends Collection implements IDistributionPlaceCollection {
     function __construct(Container $container)
     {
         parent::__construct($container);
         $this->setModelServiceName(IDistributionPlace::class);
     }
}