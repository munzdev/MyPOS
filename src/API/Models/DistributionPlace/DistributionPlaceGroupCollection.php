<?php

namespace API\Models\DistributionPlace;

use API\Lib\Container;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroup;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupCollection;
use API\Models\Collection;

class DistributionPlaceGroupCollection extends Collection implements IDistributionPlaceGroupCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IDistributionPlaceGroup::class);
    }
}