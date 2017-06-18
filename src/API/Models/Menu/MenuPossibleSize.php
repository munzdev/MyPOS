<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSize;
use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Models\Model;
use API\Models\ORM\Menu\MenuPossibleSize as MenuPossibleSizeORM;

/**
 * Skeleton subclass for representing a row from the 'menu_possible_size' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuPossibleSize extends Model implements IMenuPossibleSize
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new MenuPossibleSizeORM());
    }

    public function getMenu(): IMenu
    {
        $menu = $this->model->getMenuType();

        $menuModel = $this->container->get(IMenu::class);
        $menuModel->setModel($menu);

        return $menuModel;
    }

    public function getMenuPossibleSizeid(): int
    {
        return $this->model->getMenuPossibleSizeid();
    }

    public function getMenuSize(): IMenuSize
    {
        $menuSize = $this->model->getMenuSize();

        $menuSizeModel = $this->container->get(IMenuSize::class);
        $menuSizeModel->setModel($menuSize);

        return $menuSizeModel;
    }

    public function getMenuSizeid(): int
    {
        return $this->model->getMenuSizeid();
    }

    public function getMenuid(): int
    {
        return $this->model->getMenuid();
    }

    public function getPrice(): float
    {
        return $this->model->getPrice();
    }

    public function getIsDeleted(): ?\DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function setMenu($menu): IMenuPossibleSize
    {
        $this->model->setMenu($menu->getModel());
        return $this;
    }

    public function setMenuPossibleSizeid($menuPossibleSizeid): IMenuPossibleSize
    {
        $this->model->setMenuPossibleSizeid($menuPossibleSizeid);
        return $this;
    }

    public function setMenuSize($menuSize): IMenuPossibleSize
    {
        $this->model->setMenuSize($menuSize->getModel());
        return $this;
    }

    public function setMenuSizeid($menuSizeid): IMenuPossibleSize
    {
        $this->model->setMenuSizeid($menuSizeid);
        return $this;
    }

    public function setMenuid($menuid): IMenuPossibleSize
    {
        $this->model->setMenuid($menuid);
        return $this;
    }

    public function setPrice($price): IMenuPossibleSize
    {
        $this->model->setPrice($price);
        return $this;
    }

    public function setIsDeleted($isDeleted): IMenuPossibleSize
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }
}
