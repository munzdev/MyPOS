<?php

namespace API\Lib\Interfaces\Models\DistributionPlace;

use API\Lib\Interfaces\Models\IQuery;

interface IDistributionPlaceGroupQuery extends IQuery {
    function getByMenuGroupsAndUser(array $menuGroupids, int $eventid, int $userid) : IDistributionPlaceGroupCollection;
    function getUserDistributionPlaceGroups(int $eventid, int $userid) : IDistributionPlaceGroupCollection;
}