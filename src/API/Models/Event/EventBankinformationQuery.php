<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEventBankinformation;
use API\Lib\Interfaces\Models\Event\IEventBankinformationCollection;
use API\Lib\Interfaces\Models\Event\IEventBankinformationQuery;
use API\Models\ORM\Event\EventBankinformationQuery as EventBankinformationQueryORM;
use API\Models\Query;

class EventBankinformationQuery extends Query implements IEventBankinformationQuery
{
    public function find(): IEventBankinformationCollection
    {
        $eventBankinformations = EventBankinformationQueryORM::create()->find();

        $eventBankinformationCollection = $this->container->get(IEventBankinformationCollection::class);
        $eventBankinformationCollection->setCollection($eventBankinformations);

        return $eventBankinformationCollection;
    }

    public function findPk($id): IEventBankinformation
    {
        $eventBankinformation = EventBankinformationQueryORM::create()->findPk($id);

        $eventBankinformationModel = $this->container->get(IEventBankinformation::class);
        $eventBankinformationModel->setModel($eventBankinformation);

        return $eventBankinformationModel;
    }
}
