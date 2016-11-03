<?php

namespace API\Controllers\Product;

use API\Lib\Auth;
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
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void {
        $o_user = Auth::GetCurrentUser();        
        
        $o_menuTypes = MenuTypeQuery::create()
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
                        ->filterByEventid($o_user->getEventUser()->getEventid())
                        ->find();
                
        $a_return = [];
        
        foreach($o_menuTypes as $o_menuType)
        {
            $a_menuType = $o_menuType->toArray();            
            
            foreach($o_menuType->getMenuGroups() as $o_menuGroup )
            {
                $a_menuGroup = $o_menuGroup->toArray();
                
                
                
                foreach($o_menuGroup->getMenus() as $o_menu)
                {
                    $a_menu = $o_menu->toArray();
                    
                    foreach($o_menu->getMenuPossibleExtras() as $o_menuPossibleExtra)
                    {
                        $a_menuPossibleSize = $o_menuPossibleExtra->toArray();
                        $a_menuPossibleSize[MenuExtraTableMap::getTableMap()->getPhpName()] = $o_menuPossibleExtra->getMenuExtra()->toArray();
                        
                        $a_menu[MenuPossibleExtraTableMap::getTableMap()->getPhpName()][] = $a_menuPossibleSize;
                    }
                    
                    foreach($o_menu->getMenuPossibleSizes() as $o_menuPossibleSize)
                    {
                        $a_menuPossibleSize = $o_menuPossibleSize->toArray();
                        $a_menuPossibleSize[MenuSizeTableMap::getTableMap()->getPhpName()] = $o_menuPossibleSize->getMenuSize()->toArray();
                        
                        $a_menu[MenuPossibleSizeTableMap::getTableMap()->getPhpName()][] = $a_menuPossibleSize;
                    }
                                            
                    $a_menuGroup[MenuTableMap::getTableMap()->getPhpName()][] = $a_menu;
                }
                
                $a_menuType[MenuGroupTableMap::getTableMap()->getPhpName()][] = $a_menuGroup;
            }
            
            $a_return[] = $a_menuType;
        }
        
        $this->o_response->withJson($a_return);
    }
}