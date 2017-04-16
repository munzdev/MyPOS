<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtra;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtraCollection;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtraQuery;
use API\Models\ORM\Menu\MenuPossibleExtraQuery as MenuPossibleExtraQueryORM;
use API\Models\Query;

class MenuPossibleExtraQuery extends Query implements IMenuPossibleExtraQuery
{
    public function find(): IMenuPossibleExtraCollection
    {
        $menuPossibleExtras = MenuPossibleExtraQueryORM::create()->find();

        $menuPossibleExtraCollection = $this->container->get(IMenuPossibleExtraCollection::class);
        $menuPossibleExtraCollection->setCollection($menuPossibleExtras);

        return $menuPossibleExtraCollection;
    }

    public function findPk($id): IMenuPossibleExtra
    {
        $menuPossibleExtra = MenuPossibleExtraQueryORM::create()->findPk($id);

        $menuPossibleExtraModel = $this->container->get(IMenuPossibleExtra::class);
        $menuPossibleExtraModel->setModel($menuPossibleExtra);

        return $menuPossibleExtraModel;
    }
}
