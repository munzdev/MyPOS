<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Event\IEventContactCollection;
use API\Lib\Interfaces\Models\Event\IEventContactQuery;
use API\Models\ORM\Event\EventContactQuery as EventContactQueryORM;
use API\Models\Query;

class EventContactQuery extends Query implements IEventContactQuery
{
    public function find(): IEventContactCollection
    {
        $eventContacts = EventContactQueryORM::create()->find();

        $eventContactCollection = $this->container->get(IEventContactCollection::class);
        $eventContactCollection->setCollection($eventContacts);

        return $eventContactCollection;
    }

    public function findPk($id): IEventContact
    {
        $eventContact = EventContactQueryORM::create()->findPk($id);

        $eventContactModel = $this->container->get(IEventContact::class);
        $eventContactModel->setModel($eventContact);

        return $eventContactModel;
    }
}
