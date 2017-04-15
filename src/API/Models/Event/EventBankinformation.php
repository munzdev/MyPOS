<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventBankinformation;
use API\Models\Model;
use API\Models\ORM\Event\EventBankinformation as EventBankinformationORM;

/**
 * Skeleton subclass for representing a row from the 'event_bankinformation' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class EventBankinformation extends Model implements IEventBankinformation
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new EventBankinformationORM());
    }

    public function getActive(): boolean {
        return $this->model->getActive();
    }

    public function getBic(): string {
        return $this->model->getBic();
    }

    public function getEvent(): IEvent {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventBankinformationid(): int {
        return $this->model->getEventBankinformationid();
    }

    public function getEventid(): int {
        return $this->model->getEventid();
    }

    public function getIban(): string {
        return $this->model->getIban();
    }

    public function getName(): string {
        return $this->model->getName();
    }

    public function setActive($active): IEventBankinformation {
        $this->model->setActive($active);
        return $this;
    }

    public function setBic($bic): IEventBankinformation {
        $this->model->setBic($bic);
        return $this;
    }

    public function setEvent($event): IEventBankinformation {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventBankinformationid($eventBankinformationid): IEventBankinformation {
        $this->model->setEventBankinformationid($eventBankinformationid);
        return $this;
    }

    public function setEventid($eventid): IEventBankinformation {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setIban($iban): IEventBankinformation {
        $this->model->setIban($iban);
        return $this;
    }

    public function setName($name): IEventBankinformation {
        $this->model->setName($name);
        return $this;
    }

}
