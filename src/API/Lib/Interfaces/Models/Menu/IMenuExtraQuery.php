<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\ORM\Menu\MenuExtraQuery as MenuExtraQueryORM;

interface IMenuExtraQuery extends IQuery {
    function findByEventid(int $eventid) : IMenuExtraCollection;

    public function getByEventid(int $menuExtraid, int $eventid): ?IMenuExtra;
}