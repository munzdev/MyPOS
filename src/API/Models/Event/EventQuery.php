<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventCollection;
use API\Lib\Interfaces\Models\Event\IEventQuery;
use API\Models\ORM\Event\EventQuery as EventQueryORM;
use API\Models\Query;

class EventQuery extends Query implements IEventQuery
{
    public function find(): IEventCollection
    {
        $events = EventQueryORM::create()->find();

        $eventCollection = $this->container->get(IEventCollection::class);
        $eventCollection->setCollection($events);

        return $eventCollection;
    }

    public function findPk($id): ?IEvent
    {
        $event = EventQueryORM::create()->findPk($id);

        if(!$event) {
            return null;
        }

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }
}
