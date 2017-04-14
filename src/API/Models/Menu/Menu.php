<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Models\Model;
use API\Models\ORM\Menu\Menu as MenuORM;

/**
 * Skeleton subclass for representing a row from the 'menu' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Menu extends Model implements IMenu
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new MenuORM());
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
        return $this->model->getAvailabilityAmount();
    }

    public function getMenuGroup(): IMenuGroup
    {
        $menuGroup = $this->model->getMenuGroup();

        $menuGroupModel = $this->container->get(IMenuGroup::class);
        $menuGroupModel->setModel($menuGroup);

        return $menuGroupModel;
    }

    public function getMenuGroupid(): int
    {
        return $this->model->getMenuGroupid();
    }

    public function getMenuid(): int
    {
        return $this->model->getMenuid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getPrice(): float
    {
        return $this->model->getPrice();
    }

    public function setAvailability($availability): IMenu
    {
        $this->model->setAvailability($availability);
        return $this;
    }

    public function setAvailabilityAmount($availabilityAmount): IMenu
    {
        $this->model->setAvailabilityAmount($availabilityAmount);
        return $this;
    }

    public function setAvailabilityid($availabilityid): IMenu
    {
        $this->model->setAvailabilityid($availabilityid);
        return $this;
    }

    public function setMenuGroup($menuGroup): IMenu
    {
        $this->model->setMenuGroup($menuGroup);
        return $this;
    }

    public function setMenuGroupid($menuGroupid): IMenu
    {
        $this->model->setMenuGroupid($menuGroupid);
        return $this;
    }

    public function setMenuid($menuid): IMenu
    {
        $this->model->setMenuid($menuid);
        return $this;
    }

    public function setName($name): IMenu
    {
        $this->model->setName($name);
        return $this;
    }

    public function setPrice($price): IMenu
    {
        $this->model->setPrice($price);
        return $this;
    }

}
