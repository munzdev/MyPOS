<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Lib\Interfaces\Models\Menu\IMenuSizeCollection;
use API\Lib\Interfaces\Models\Menu\IMenuSizeQuery;
use API\Models\ORM\Menu\MenuSizeQuery as MenuSizeQueryORM;
use API\Models\Query;

class MenuSizeQuery extends Query implements IMenuSizeQuery
{
    public function find(): IMenuSizeCollection
    {
        $menuSizes = MenuSizeQueryORM::create()->find();

        $menuSizeCollection = $this->container->get(IMenuSizeCollection::class);
        $menuSizeCollection->setCollection($menuSizes);

        return $menuSizeCollection;
    }

    public function findPk($id): IMenuSize
    {
        $menuSize = MenuSizeQueryORM::create()->findPk($id);

        $menuSizeModel = $this->container->get(IMenuSize::class);
        $menuSizeModel->setModel($menuSize);

        return $menuSizeModel;
    }
}
