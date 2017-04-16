<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IQuery;

interface IMenuTypeQuery extends IQuery {
     function getMenuTypesForEventid($eventid) : IMenuTypeCollection;
}