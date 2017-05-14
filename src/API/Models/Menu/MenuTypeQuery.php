<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Menu\IMenuType;
use API\Lib\Interfaces\Models\Menu\IMenuTypeCollection;
use API\Lib\Interfaces\Models\Menu\IMenuTypeQuery;
use API\Models\ORM\Menu\Map\MenuExtraTableMap;
use API\Models\ORM\Menu\Map\MenuGroupTableMap;
use API\Models\ORM\Menu\Map\MenuPossibleExtraTableMap;
use API\Models\ORM\Menu\Map\MenuPossibleSizeTableMap;
use API\Models\ORM\Menu\Map\MenuSizeTableMap;
use API\Models\ORM\Menu\Map\MenuTableMap;
use API\Models\ORM\Menu\MenuTypeQuery as MenuTypeQueryORM;
use API\Models\Query;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;

class MenuTypeQuery extends Query implements IMenuTypeQuery
{
    public function find(): IMenuTypeCollection
    {
        $menuTypes = MenuTypeQueryORM::create()->find();

        $menuTypeCollection = $this->container->get(IMenuTypeCollection::class);
        $menuTypeCollection->setCollection($menuTypes);

        return $menuTypeCollection;
    }

    public function findPk($id): ?IMenuType
    {
        $menuType = MenuTypeQueryORM::create()->findPk($id);

        if(!$menuType) {
            return null;
        }

        $menuTypeModel = $this->container->get(IMenuType::class);
        $menuTypeModel->setModel($menuType);

        return $menuTypeModel;
    }

    function getMenuTypesForEventid($eventid) : IMenuTypeCollection {

        Propel::enableInstancePooling();

        $menuTypes = MenuTypeQueryORM::create()
                        ->useMenuGroupQuery()
                            ->useMenuQuery()
                                ->useMenuPossibleSizeQuery(null, Criteria::LEFT_JOIN)
                                    ->leftJoinMenuSize()
                                ->endUse()
                                ->useMenuPossibleExtraQuery(null, Criteria::LEFT_JOIN)
                                    ->leftJoinMenuExtra()
                                ->endUse()
                            ->endUse()
                        ->endUse()
                        ->with(MenuGroupTableMap::getTableMap()->getPhpName())
                        ->with(MenuTableMap::getTableMap()->getPhpName())
                        ->with(MenuPossibleExtraTableMap::getTableMap()->getPhpName())
                        ->with(MenuPossibleSizeTableMap::getTableMap()->getPhpName())
                        ->with(MenuSizeTableMap::getTableMap()->getPhpName())
                        ->with(MenuExtraTableMap::getTableMap()->getPhpName())
                        ->filterByEventid($eventid)
                        ->find();

        Propel::disableInstancePooling();

        $menuTypeCollection = $this->container->get(IMenuTypeCollection::class);
        $menuTypeCollection->setCollection($menuTypes);

        return $menuTypeCollection;
    }
}
