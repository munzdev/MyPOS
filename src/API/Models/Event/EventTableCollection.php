<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\Event\IEventTableCollection;
use API\Models\Collection;

class EventTableCollection extends Collection implements IEventTableCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IEventTable::class);
    }
}