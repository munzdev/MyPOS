<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Lib\Interfaces\Models\Event\IEventPrinterCollection;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Models\ORM\Event\EventPrinterQuery as EventPrinterQueryORM;
use API\Models\Query;

class EventPrinterQuery extends Query implements IEventPrinterQuery
{
    public function find(): IEventPrinterCollection
    {
        $eventPrinters = EventPrinterQueryORM::create()->find();

        $eventPrinterCollection = $this->container->get(IEventPrinterCollection::class);
        $eventPrinterCollection->setCollection($eventPrinters);

        return $eventPrinterCollection;
    }

    public function findPk($id): ?IEventPrinter
    {
        $eventPrinter = EventPrinterQueryORM::create()->findPk($id);

        if(!$eventPrinter) {
            return null;
        }

        $eventPrinterModel = $this->container->get(IEventPrinter::class);
        $eventPrinterModel->setModel($eventPrinter);

        return $eventPrinterModel;
    }
}
