<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\ORM\Event\EventPrinterQuery as EventPrinterQueryORM;

interface IEventPrinterQuery extends IQuery {

    public function findByEventid(int $eventid): IEventPrinterCollection;
}