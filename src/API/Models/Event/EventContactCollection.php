<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Event\IEventContactCollection;
use API\Models\Collection;

class EventContactCollection extends Collection implements IEventContactCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IEventContact::class);
    }
}