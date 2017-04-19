<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuGroupCollection;
use API\Lib\Interfaces\Models\Menu\IMenuGroupQuery;
use API\Models\ORM\Menu\MenuGroupQuery as MenuGroupQueryORM;
use API\Models\Query;

class MenuGroupQuery extends Query implements IMenuGroupQuery
{
    public function find(): IMenuGroupCollection
    {
        $menuGroups = MenuGroupQueryORM::create()->find();

        $menuGroupCollection = $this->container->get(IMenuGroupCollection::class);
        $menuGroupCollection->setCollection($menuGroups);

        return $menuGroupCollection;
    }

    public function findPk($id): ?IMenuGroup
    {
        $menuGroup = MenuGroupQueryORM::create()->findPk($id);

        if(!$menuGroup) {
            return null;
        }

        $menuGroupModel = $this->container->get(IMenuGroup::class);
        $menuGroupModel->setModel($menuGroup);

        return $menuGroupModel;
    }
}
