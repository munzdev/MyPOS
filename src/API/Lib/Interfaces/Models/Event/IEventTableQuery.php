<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IQuery;

interface IEventTableQuery extends IQuery {
    public function getByName(int $eventid, string $name) : ?IEventTable;
    public function findByOrderid(int $orderid) : ?IEventTable;
    public function findByEventid(int $eventid) : IEventTableCollection;
}