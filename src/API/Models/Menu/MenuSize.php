<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Models\Model;
use API\Models\ORM\Menu\MenuSize as MenuSizeORM;

/**
 * Skeleton subclass for representing a row from the 'menu_size' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuSize extends Model implements IMenuSize
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new MenuSizeORM());
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

    public function getFactor(): float
    {
        return $this->model->getFactor();
    }

    public function getMenuSizeid(): int
    {
        return $this->model->getMenuSizeid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getIsDeleted(): ?\DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function setEvent($event): IMenuSize
    {
        $this->model->setEvent($event->getModel());
        return $this;
    }

    public function setEventid($eventid): IMenuSize
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setFactor($factor): IMenuSize
    {
        $this->model->setFactor($factor);
        return $this;
    }

    public function setMenuSizeid($menuSizeid): IMenuSize
    {
        $this->model->setMenuSizeid($menuSizeid);
        return $this;
    }

    public function setName($name): IMenuSize
    {
        $this->model->setName($name);
        return $this;
    }

    public function setIsDeleted($isDeleted): IMenuSize
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }
}
