<?php

namespace API\Models\Menu;

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

/**
 * Skeleton subclass for performing query and update operations on the 'menu_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuTypeQuery extends Query implements IMenuTypeQuery
{
    function getMenuTypesForEventid($eventid) : IMenuTypeCollection {
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

        $menuTypeCollection = $this->container->get(IMenuTypeCollection::class);
        $menuTypeCollection->setCollection($menuTypes);

        return $menuTypeCollection;
    }
}
