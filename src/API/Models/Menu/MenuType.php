<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Menu\IMenuGroupCollection;
use API\Lib\Interfaces\Models\Menu\IMenuType;
use API\Models\Model;
use API\Models\ORM\Menu\MenuType as MenuTypeORM;

/**
 * Skeleton subclass for representing a row from the 'menu_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuType extends Model implements IMenuType
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new MenuTypeORM());
    }

    public function getAllowMixing(): boolean
    {
        return $this->model->getAllowMixing();
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

    public function getMenuTypeid(): int
    {
        return $this->model->getMenuTypeid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getTax(): int
    {
        return $this->model->getTax();
    }

    public function getMenuGroups() {
        $menuGroups = $this->model->getMenuGroups();

        $menuGroupCollection = $this->container->get(IMenuGroupCollection::class);
        $menuGroupCollection->setCollection($menuGroups);

        return $menuGroupCollection;
    }

    public function getIsDeleted(): ?\DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function setAllowMixing($allowMixing): IMenuType
    {
        $this->model->setAllowMixing($allowMixing);
        return $this;
    }

    public function setEvent($event): IMenuType
    {
        $this->model->setEvent($event->getModel());
        return $this;
    }

    public function setEventid($eventid): IMenuType
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setMenuTypeid($menuTypeid): IMenuType
    {
        $this->model->setMenuTypeid($menuTypeid);
        return $this;
    }

    public function setName($name): IMenuType
    {
        $this->model->setName($name);
        return $this;
    }

    public function setTax($tax): IMenuType
    {
        $this->model->setTax($tax);
        return $this;
    }

    public function setIsDeleted($isDeleted): IMenuType
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }
}
