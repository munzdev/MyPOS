<?php

namespace API\Models\DistributionPlace;

use API\Lib\Container;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTable;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTableCollection;
use API\Models\Collection;

class DistributionPlaceTableCollection extends Collection implements IDistributionPlaceTableCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IDistributionPlaceTable::class);
    }
}