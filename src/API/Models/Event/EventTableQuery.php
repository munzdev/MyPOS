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

    public function findPk($id): ?IEventTable
    {
        $eventTable = EventTableQueryORM::create()->findPk($id);

        if(!$eventTable) {
            return null;
        }

        $eventTableModel = $this->container->get(IEventTable::class);
        $eventTableModel->setModel($eventTable);

        return $eventTableModel;
    }

    public function getByName(int $eventid, string $name) : ?IEventTable
    {
        $eventTable = EventTableQueryORM::create()
            ->filterByEventid($eventid)
            ->filterByName($name)
            ->findOne();

        if(!$eventTable) {
            return null;
        }

        $eventTableModel = $this->container->get(IEventTable::class);
        $eventTableModel->setModel($eventTable);

        return $eventTableModel;

    }

    public function findByOrderid(int $orderid) : IEventTableCollection
    {
        $eventTables = EventTableQueryORM::create()
            ->useOrderQuery()
                ->filterByOrderid($orderid)
            ->endUse()
            ->findOne();

        $eventTableCollection = $this->container->get(IEventTableCollection::class);
        $eventTableCollection->setCollection($eventTables);

        return $eventTableCollection;
    }

    public function findByEventid(int $eventid) : IEventTableCollection
    {
        $eventTables = EventTableQueryORM::create()
            ->findByEventid($eventid);

        $eventTableCollection = $this->container->get(IEventTableCollection::class);
        $eventTableCollection->setCollection($eventTables);

        return $eventTableCollection;
    }
}
