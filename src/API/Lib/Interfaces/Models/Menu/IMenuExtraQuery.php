<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IQuery;

interface IMenuExtraQuery extends IQuery {
    function findByEventid(int $eventid) : IMenuExtraCollection;

    public function getByEventid(int $menuExtraid, int $eventid): ?IMenuExtra;
}