<?php

namespace API\Models\Menu;

use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IAvailabilityCollection;
use API\Lib\Interfaces\Models\Menu\IAvailabilityQuery;
use API\Models\ORM\Menu\AvailabilityQuery as AvailabilityQueryORM;
use API\Models\Query;

class AvailabilityQuery extends Query implements IAvailabilityQuery
{
    public function find(): IAvailabilityCollection
    {
        $availbilitys = AvailabilityQueryORM::create()->find();

        $availbilityCollection = $this->container->get(IAvailabilityCollection::class);
        $availbilityCollection->setCollection($availbilitys);

        return $availbilityCollection;
    }

    public function findPk($id): ?IAvailability
    {
        $availbility = AvailabilityQueryORM::create()->findPk($id);

        if(!$availbility) {
            return null;
        }

        $availbilityModel = $this->container->get(IAvailability::class);
        $availbilityModel->setModel($availbility);

        return $availbilityModel;
    }
}
