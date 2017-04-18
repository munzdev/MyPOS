<?php

namespace API\Lib\Interfaces\Models\DistributionPlace;

use API\Lib\Interfaces\Models\IQuery;

interface IDistributionPlaceUserQuery extends IQuery {
    function getByUser(int $userid, int $eventid) : IDistributionPlaceUser;
}