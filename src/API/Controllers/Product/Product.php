<?php

namespace API\Controllers\Product;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Menu\IMenuTypeQuery;
use API\Lib\SecurityController;
use Slim\App;

class Product extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container['db'];
    }

    protected function get() : void
    {
        $menuTypeQuery = $this->container->get(IMenuTypeQuery::class);
        $auth = $this->container->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $menuTypes = $menuTypeQuery->getMenuTypesForEventid($user->getEventUsers()->getFirst()->getEventid());

        $return = [];

        foreach ($menuTypes as $menuType) {
            $menuTypeArray = $menuType->toArray();

            foreach ($menuType->getMenuGroups() as $menuGroup) {
                $menuGroupArray = $menuGroup->toArray();

                foreach ($menuGroup->getMenus() as $menu) {
                    $menuArray = $menu->toArray();

                    foreach ($menu->getMenuPossibleExtras() as $menuPossibleExtra) {
                        $menuPossibleSizeArray = $menuPossibleExtra->toArray();
                        $menuPossibleSizeArray["MenuExtra"] = $menuPossibleExtra->getMenuExtra()->toArray();

                        $menuArray["MenuPossibleExtra"][] = $menuPossibleSizeArray;
                    }

                    foreach ($menu->getMenuPossibleSizes() as $menuPossibleSize) {
                        $menuPossibleSizeArray = $menuPossibleSize->toArray();
                        $menuPossibleSizeArray["MenuSize"] = $menuPossibleSize->getMenuSize()->toArray();

                        $menuArray["MenuPossibleSize"][] = $menuPossibleSizeArray;
                    }

                    $menuGroupArray["Menu"][] = $menuArray;
                }

                $menuTypeArray["MenuGroup"][] = $menuGroupArray;
            }

            $return[] = $menuTypeArray;
        }

        $this->withJson($return);
    }
}
