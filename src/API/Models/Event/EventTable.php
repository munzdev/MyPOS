<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Models\Model;
use API\Models\ORM\Event\EventTable as EventTableORM;

/**
 * Skeleton subclass for representing a row from the 'event_table' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class EventTable extends Model implements IEventTable
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new EventTableORM());
    }

    public function getData(): string
    {
        return $$this->model->getData();
    }

    public function getEvent(): IEvent
    {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventTableid(): int
    {
        return $$this->model->getEventTableid();
    }

    public function getEventid(): int
    {
        return $$this->model->getEventid();
    }

    public function getName(): string
    {
        return $$this->model->getName();
    }

    public function setData($data): IEventTable
    {
        $this->model->setData($data);
        return $this;
    }

    public function setEvent($event): IEventTable
    {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventTableid($eventTableid): IEventTable
    {
        $this->model->setEventTableid($eventTableid);
        return $this;
    }

    public function setEventid($eventid): IEventTable
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setName($name): IEventTable
    {
        $this->model->setName($name);
        return $this;
    }

}
