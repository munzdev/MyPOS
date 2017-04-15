<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Models\Model;
use API\Models\ORM\Event\EventPrinter as EventPrinterORM;
use Slim\Container;

/**
 * Skeleton subclass for representing a row from the 'event_printer' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class EventPrinter extends Model implements IEventPrinter
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new EventPrinterORM());
    }

    public function getAttr1(): string
    {
        return $this->model->getAttr1();
    }

    public function getAttr2(): string
    {
        return $this->model->getAttr2();
    }

    public function getCharactersPerRow(): int
    {
        return $this->model->getCharactersPerRow();
    }

    public function getDefault(): boolean
    {
        return $this->model->getDefault();
    }

    public function getEvent(): IEvent
    {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventPrinterid(): int
    {
        return $this->model->getEventPrinterid();
    }

    public function getEventid(): int
    {
        return $this->model->getEventid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getType(): int
    {
        return $this->model->getType();
    }

    public function setAttr1($attr1): IEventPrinter
    {
        $this->model->setAttr1($attr1);
        return $this;
    }

    public function setAttr2($attr2): IEventPrinter
    {
        $this->model->setAttr2($attr2);
        return $this;
    }

    public function setCharactersPerRow($charactersPerRow): IEventPrinter
    {
        $this->model->setCharactersPerRow($charactersPerRow);
        return $this;
    }

    public function setDefault($default): IEventPrinter
    {
        $this->model->setDefault($default);
        return $this;
    }

    public function setEvent($event): IEventPrinter
    {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventPrinterid($eventPrinterid): IEventPrinter
    {
        $this->model->setEventPrinterid($eventPrinterid);
        return $this;
    }

    public function setEventid($eventid): IEventPrinter
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setName($name): IEventPrinter
    {
        $this->model->setName($name);
        return $this;
    }

    public function setType($type): IEventPrinter
    {
        $this->model->setType($type);
        return $this;
    }

}
