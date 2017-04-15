<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Lib\Interfaces\Models\Event\IEventPrinterCollection;
use API\Models\Collection;

class EventPrinterCollection extends Collection implements IEventPrinterCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IEventPrinter::class);
    }
}