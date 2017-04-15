<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuExtra;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtra;
use API\Models\Model;
use API\Models\ORM\Menu\MenuPossibleExtra as MenuPossibleExtraORM;

/**
 * Skeleton subclass for representing a row from the 'menu_possible_extra' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuPossibleExtra extends Model implements IMenuPossibleExtra
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new MenuPossibleExtraORM());
    }

    public function getMenu(): IMenu
    {
        $menu = $this->model->getMenuType();

        $menuModel = $this->container->get(IMenu::class);
        $menuModel->setModel($menu);

        return $menuModel;
    }

    public function getMenuExtra(): IMenuExtra
    {
        $menuExtra = $this->model->getMenuType();

        $menuExtraModel = $this->container->get(IMenuExtra::class);
        $menuExtraModel->setModel($menuExtra);

        return $menuExtraModel;
    }

    public function getMenuExtraid(): int
    {
        return $this->model->getMenuExtraid();
    }

    public function getMenuPossibleExtraid(): int
    {
        return $this->model->getMenuPossibleExtraid();
    }

    public function getMenuid(): int
    {
        return $this->model->getMenuid();
    }

    public function getPrice(): float
    {
        return $this->model->getPrice();
    }

    public function setMenu($menu): IMenuPossibleExtra
    {
        $this->model->setMenu($menu);
        return $this;
    }

    public function setMenuExtra($menuExtra): IMenuPossibleExtra
    {
        $this->model->setMenuExtra($menuExtra);
        return $this;
    }

    public function setMenuExtraid($menuExtraid): IMenuPossibleExtra
    {
        $this->model->setMenuExtraid($menuExtraid);
        return $this;
    }

    public function setMenuPossibleExtraid($menuPossibleExtraid): IMenuPossibleExtra
    {
        $this->model->setMenuPossibleExtraid($menuPossibleExtraid);
        return $this;
    }

    public function setMenuid($menuid): IMenuPossibleExtra
    {
        $this->model->setMenuid($menuid);
        return $this;
    }

    public function setPrice($price): IMenuPossibleExtra
    {
        $this->model->setPrice($price);
        return $this;
    }

}
