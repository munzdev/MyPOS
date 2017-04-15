<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventBankinformation;
use API\Lib\Interfaces\Models\Event\IEventBankinformationCollection;
use API\Models\Collection;

class EventBankinformationCollection extends Collection implements IEventBankinformationCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IEventBankinformation::class);
    }
}