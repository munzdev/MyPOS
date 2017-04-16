<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\Event\IEventTableCollection;
use API\Lib\Interfaces\Models\Event\IEventTableQuery;
use API\Models\ORM\Event\EventTableQuery as EventTableQueryORM;
use API\Models\Query;

class EventTableQuery extends Query implements IEventTableQuery
{
    public function find(): IEventTableCollection
    {
        $eventTables = EventTableQueryORM::create()->find();

        $eventTableCollection = $this->container->get(IEventTableCollection::class);
        $eventTableCollection->setCollection($eventTables);

        return $eventTableCollection;
    }

    public function findPk($id): IEventTable
    {
        $eventTable = EventTableQueryORM::create()->findPk($id);

        $eventTableModel = $this->container->get(IEventTable::class);
        $eventTableModel->setModel($eventTable);

        return $eventTableModel;
    }
}
