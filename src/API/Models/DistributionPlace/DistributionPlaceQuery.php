<?php

namespace API\Models\DistributionPlace;

use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlace;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceQuery;
use API\Models\ORM\DistributionPlace\DistributionPlaceQuery as DistributionPlaceQueryORM;
use API\Models\Query;

class DistributionPlaceQuery extends Query implements IDistributionPlaceQuery
{
    public function find(): IDistributionPlaceCollection
    {
        $distributionPlaceGroups = DistributionPlaceQueryORM::create()->find();

        $distributionPlaceGroupCollection = $this->container->get(IDistributionPlaceCollection::class);
        $distributionPlaceGroupCollection->setCollection($distributionPlaceGroups);

        return $distributionPlaceGroupCollection;
    }

    public function findPk($id): ?IDistributionPlace
    {
        $distributionPlaceGroup = DistributionPlaceQueryORM::create()->findPk($id);

        if(!$distributionPlaceGroup) {
            return null;
        }

        $distributionPlaceGroupModel = $this->container->get(IDistributionPlace::class);
        $distributionPlaceGroupModel->setModel($distributionPlaceGroup);

        return $distributionPlaceGroupModel;
    }
}
