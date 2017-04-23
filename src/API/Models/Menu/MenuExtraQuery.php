<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IMenuExtra;
use API\Lib\Interfaces\Models\Menu\IMenuExtraCollection;
use API\Lib\Interfaces\Models\Menu\IMenuExtraQuery;
use API\Models\ORM\Menu\MenuExtraQuery as MenuExtraQueryORM;
use API\Models\Query;

class MenuExtraQuery extends Query implements IMenuExtraQuery
{
    public function find(): IMenuExtraCollection
    {
        $menuExtras = MenuExtraQueryORM::create()->find();

        $menuExtraCollection = $this->container->get(IMenuExtraCollection::class);
        $menuExtraCollection->setCollection($menuExtras);

        return $menuExtraCollection;
    }

    public function findPk($id): ?IMenuExtra
    {
        $menuExtra = MenuExtraQueryORM::create()->findPk($id);

        if(!$menuExtra) {
            return null;
        }

        $menuExtraModel = $this->container->get(IMenuExtra::class);
        $menuExtraModel->setModel($menuExtra);

        return $menuExtraModel;
    }

    public function getByEventid(int $menuExtraid, int $eventid) : ?IMenuExtra
    {
        $menuExtra = MenuExtraQueryORM::create()
            ->filterByEventid($eventid)
            ->filterByMenuExtraid($menuExtraid)
            ->findOne();

        if(!$menuExtra) {
            return null;
        }

        $menuExtraModel = $this->container->get(IMenuExtra::class);
        $menuExtraModel->setModel($menuExtra);

        return $menuExtraModel;
    }

    public function findByEventid(int $eventid) : IMenuExtraCollection
    {
        $menuExtras = MenuExtraQueryORM::create()
                        ->filterByEventid($eventid)
                        ->find();

        $menuExtraCollection = $this->container->get(IMenuExtraCollection::class);
        $menuExtraCollection->setCollection($menuExtras);

        return $menuExtraCollection;
    }
}
