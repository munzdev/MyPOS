<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IMenuPossibleSize;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSizeCollection;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSizeQuery;
use API\Models\ORM\Menu\MenuPossibleSizeQuery as MenuPossibleSizeQueryORM;
use API\Models\Query;

class MenuPossibleSizeQuery extends Query implements IMenuPossibleSizeQuery
{
    public function find(): IMenuPossibleSizeCollection
    {
        $menuPossibleSizes = MenuPossibleSizeQueryORM::create()->find();

        $menuPossibleSizeCollection = $this->container->get(IMenuPossibleSizeCollection::class);
        $menuPossibleSizeCollection->setCollection($menuPossibleSizes);

        return $menuPossibleSizeCollection;
    }

    public function findPk($id): ?IMenuPossibleSize
    {
        $menuPossibleSize = MenuPossibleSizeQueryORM::create()->findPk($id);

        if(!$menuPossibleSize) {
            return null;
        }

        $menuPossibleSizeModel = $this->container->get(IMenuPossibleSize::class);
        $menuPossibleSizeModel->setModel($menuPossibleSize);

        return $menuPossibleSizeModel;
    }
}
