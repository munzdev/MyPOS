<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IQuery;

interface IEventBankinformationQuery extends IQuery {
    public function getDefaultForEventid(int $eventid) : ?IEventBankinformation;
}