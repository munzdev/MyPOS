<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IMenuExtra;
use API\Models\Model;
use API\Models\ORM\Menu\MenuExtra as MenuExtraORM;

/**
 * Skeleton subclass for representing a row from the 'menu_extra' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuExtra extends Model implements IMenuExtra
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new MenuExtraORM());
    }

    public function getAvailability(): IAvailability
    {
        $availability = $this->model->getAvailability();

        $availabilityModel = $this->container->get(IAvailability::class);
        $availabilityModel->setModel($availability);

        return $availabilityModel;
    }

    public function getAvailabilityAmount(): int
    {
        return $this->model->getAvailabilityAmount();
    }

    public function getAvailabilityid(): int
    {
        return $this->model->getAvailabilityid();
    }

    public function getEvent(): IEvent
    {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventid(): int
    {
        return $this->model->getEventid();
    }

    public function getMenuExtraid(): int
    {
        return $this->model->getMenuExtraid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function setAvailability($availability): IMenuExtra
    {
        $this->model->setAvailability($availability);
        return $this;
    }

    public function setAvailabilityAmount($availabilityAmount): IMenuExtra
    {
        $this->model->setAvailabilityAmount($availabilityAmount);
        return $this;
    }

    public function setAvailabilityid($availabilityid): IMenuExtra
    {
        $this->model->setAvailabilityid($availabilityid);
        return $this;
    }

    public function setEvent($event): IMenuExtra
    {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventid($eventid): IMenuExtra
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setMenuExtraid($menuExtraid): IMenuExtra
    {
        $this->model->setMenuExtraid($menuExtraid);
        return $this;
    }

    public function setName($name): IMenuExtra
    {
        $this->model->setName($name);
        return $this;
    }
}
