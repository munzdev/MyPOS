<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEventUser;
use API\Lib\Interfaces\Models\Event\IEventUserCollection;
use API\Lib\Interfaces\Models\Event\IEventUserQuery;
use API\Models\ORM\Event\EventUserQuery as EventUserQueryORM;
use API\Models\Query;

class EventUserQuery extends Query implements IEventUserQuery
{
    public function find(): IEventUserCollection
    {
        $eventUsers = EventUserQueryORM::create()->find();

        $eventUserCollection = $this->container->get(IEventUserCollection::class);
        $eventUserCollection->setCollection($eventUsers);

        return $eventUserCollection;
    }

    public function findPk($id): IEventUser
    {
        $eventUser = EventUserQueryORM::create()->findPk($id);

        $eventUserModel = $this->container->get(IEventUser::class);
        $eventUserModel->setModel($eventUser);

        return $eventUserModel;
    }
}
