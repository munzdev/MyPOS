<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuCollection;
use API\Lib\Interfaces\Models\Menu\IMenuQuery;
use API\Models\ORM\Menu\MenuQuery as MenuQueryORM;
use API\Models\Query;

class MenuQuery extends Query implements IMenuQuery
{
    public function find(): IMenuCollection
    {
        $menus = MenuQueryORM::create()->find();

        $menuCollection = $this->container->get(IMenuCollection::class);
        $menuCollection->setCollection($menus);

        return $menuCollection;
    }

    public function findPk($id): ?IMenu
    {
        $menu = MenuQueryORM::create()->findPk($id);

        if(!$menu) {
            return null;
        }

        $menuModel = $this->container->get(IMenu::class);
        $menuModel->setModel($menu);

        return $menuModel;
    }
}
