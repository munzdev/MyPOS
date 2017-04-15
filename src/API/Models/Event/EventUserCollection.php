<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventUser;
use API\Lib\Interfaces\Models\Event\IEventUserCollection;
use API\Models\Collection;

class EventUserCollection extends Collection implements IEventUserCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IEventUser::class);
    }
}