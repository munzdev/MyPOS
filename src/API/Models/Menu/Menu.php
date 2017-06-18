<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtraCollection;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSizeCollection;
use API\Models\Model;
use API\Models\ORM\Menu\Menu as MenuORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'menu' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Menu extends Model implements IMenu
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new MenuORM());
    }

    public function getAvailability(): IAvailability
    {
        $availability = $this->model->getAvailability();

        $availabilityModel = $this->container->get(IAvailability::class);
        $availabilityModel->setModel($availability);

        return $availabilityModel;
    }

    public function getAvailabilityAmount(): ?int
    {
        return $this->model->getAvailabilityAmount();
    }

    public function getAvailabilityid(): int
    {
        return $this->model->getAvailabilityid();
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

    public function getMenuPossibleExtras() {
        $menuPossibleExtras = $this->model->getMenuPossibleExtras();

        $menuPossibleExtraCollection = $this->container->get(IMenuPossibleExtraCollection::class);
        $menuPossibleExtraCollection->setCollection($menuPossibleExtras);

        return $menuPossibleExtraCollection;
    }

    public function getMenuPossibleSizes() {
        $menuPossibleSizes = $this->model->getMenuPossibleSizes();

        $menuPossibleSizeCollection = $this->container->get(IMenuPossibleSizeCollection::class);
        $menuPossibleSizeCollection->setCollection($menuPossibleSizes);

        return $menuPossibleSizeCollection;
    }

    public function getIsDeleted() : ?DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function setAvailability($availability): IMenu
    {
        $this->model->setAvailability($availability->getModel());
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
        $this->model->setMenuGroup($menuGroup->getModel());
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

    public function setIsDeleted($isDeleted): IMenu
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }
}
