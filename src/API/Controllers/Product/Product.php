<?php

namespace API\Controllers\Product;

use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Models\Menu\Map\MenuExtraTableMap;
use API\Models\Menu\Map\MenuGroupTableMap;
use API\Models\Menu\Map\MenuPossibleExtraTableMap;
use API\Models\Menu\Map\MenuPossibleSizeTableMap;
use API\Models\Menu\Map\MenuSizeTableMap;
use API\Models\Menu\Map\MenuTableMap;
use API\Models\Menu\MenuTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Slim\App;

class Product extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menuTypes = MenuTypeQuery::create()
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
                        ->filterByEventid($user->getEventUser()->getEventid())
                        ->find();

        $return = [];

        foreach ($menuTypes as $menuType) {
            $menuTypeArray = $menuType->toArray();

            foreach ($menuType->getMenuGroups() as $menuGroup) {
                $menuGroupArray = $menuGroup->toArray();

                foreach ($menuGroup->getMenus() as $menu) {
                    $menuArray = $menu->toArray();

                    foreach ($menu->getMenuPossibleExtras() as $menuPossibleExtra) {
                        $menuPossibleSizeArray = $menuPossibleExtra->toArray();
                        $menuPossibleSizeArray[MenuExtraTableMap::getTableMap()->getPhpName()] = $menuPossibleExtra->getMenuExtra()->toArray();

                        $menuArray[MenuPossibleExtraTableMap::getTableMap()->getPhpName()][] = $menuPossibleSizeArray;
                    }

                    foreach ($menu->getMenuPossibleSizes() as $menuPossibleSize) {
                        $menuPossibleSizeArray = $menuPossibleSize->toArray();
                        $menuPossibleSizeArray[MenuSizeTableMap::getTableMap()->getPhpName()] = $menuPossibleSize->getMenuSize()->toArray();

                        $menuArray[MenuPossibleSizeTableMap::getTableMap()->getPhpName()][] = $menuPossibleSizeArray;
                    }

                    $menuGroupArray[MenuTableMap::getTableMap()->getPhpName()][] = $menuArray;
                }

                $menuTypeArray[MenuGroupTableMap::getTableMap()->getPhpName()][] = $menuGroupArray;
            }

            $return[] = $menuTypeArray;
        }

        $this->withJson($return);
    }
}
