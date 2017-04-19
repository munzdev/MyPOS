<?php

namespace API\Models\DistributionPlace;

use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTable;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTableCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTableQuery;
use API\Models\ORM\DistributionPlace\DistributionPlaceTableQuery as DistributionPlaceTableQueryORM;
use API\Models\Query;

class DistributionPlaceTableQuery extends Query implements IDistributionPlaceTableQuery
{
    public function find(): IDistributionPlaceTableCollection
    {
        $distributionPlaceTable = DistributionPlaceTableQueryORM::create()->find();

        $distributionPlaceTableCollection = $this->container->get(IDistributionPlaceTableCollection::class);
        $distributionPlaceTableCollection->setCollection($distributionPlaceTable);

        return $distributionPlaceTableCollection;
    }

    public function findPk($id): ?IDistributionPlaceTable
    {
        $distributionPlaceTable = DistributionPlaceTableQueryORM::create()->findPk($id);

        if(!$distributionPlaceTable) {
            return null;
        }

        $distributionPlaceTableModel = $this->container->get(IDistributionPlaceTable::class);
        $distributionPlaceTableModel->setModel($distributionPlaceTable);

        return $distributionPlaceTableModel;
    }
}
