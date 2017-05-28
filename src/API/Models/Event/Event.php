<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Models\Model;
use API\Models\ORM\Event\Event as EventORM;
use DateTime;
use Slim\Container;

/**
 * Skeleton subclass for representing a row from the 'event' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Event extends Model implements IEvent
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new EventORM());
    }

    public function getActive(): boolean {
        return $this->model->getActive();
    }

    public function getDate(): DateTime {
        return $this->model->getDate();
    }

    public function getEventid(): int {
        return $this->model->getEventid();
    }

    public function getName(): string {
        return $this->model->getName();
    }

    public function setActive($active): IEvent {
        $this->model->setActive($active);
        return $this;
    }

    public function setDate($date): IEvent {
        $this->model->setDate($date);
        return $this;
    }

    public function setEventid($eventid): IEvent {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setName($name): IEvent {
        $this->model->setName($name);
        return $this;
    }

}
