<?php

namespace API\Models\DistributionPlace;

use API\Lib\Container;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlace;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Models\Model;
use API\Models\ORM\DistributionPlace\DistributionPlace as DistributionPlaceORM;

/**
 * Skeleton subclass for representing a row from the 'distribution_place' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class DistributionPlace extends Model implements IDistributionPlace
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new DistributionPlaceORM());
    }

    public function getDistributionPlaceid(): int {
        return $this->model->getDistributionPlaceid();
    }

    public function getEvent(): IEvent {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventid(): int {
        return $this->model->getEventid();
    }

    public function getName(): string {
        return $this->model->getName();
    }

    public function setDistributionPlaceid($distributionPlaceid): IDistributionPlace {
        $this->model->setDistributionPlaceid($distributionPlaceid);
        return $this;
    }

    public function setEvent($event): IDistributionPlace {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventid($eventid): IDistributionPlace {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setName($name): IDistributionPlace {
        $this->model->setName($name);
        return $this;
    }

}
