<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IQuery;

interface IMenuQuery extends IQuery {
    public function getByEventid(int $menuid, int $eventid): ?IMenu;
}