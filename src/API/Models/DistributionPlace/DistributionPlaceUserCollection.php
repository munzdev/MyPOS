<?php

namespace API\Models\DistributionPlace;

use API\Lib\Container;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUser;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUserCollection;
use API\Models\Collection;

class DistributionPlaceUserCollection extends Collection implements IDistributionPlaceUserCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IDistributionPlaceUser::class);
    }
}