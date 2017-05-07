<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IQuery;

interface IEventPrinterQuery extends IQuery {

    public function findByEventid(int $eventid): IEventPrinterCollection;
}