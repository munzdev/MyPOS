<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\Event\Criteria;

interface IEventContactQuery extends IQuery {
    public function findActiveByNameAndEvent(int $eventid, string $name): IEventContactCollection;
    public function getDefaultForEventid(int $eventid): ?IEventContact;
}