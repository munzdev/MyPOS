<?php

namespace API\Models\OIP;

use API\Lib\Container;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutCollection;
use API\Models\Collection;

class DistributionGivingOutCollection extends Collection implements IDistributionGivingOutCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IDistributionGivingOut::class);
    }
}