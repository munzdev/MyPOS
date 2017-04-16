<?php

namespace API\Models\OIP;

use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutCollection;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutQuery;
use API\Models\ORM\OIP\DistributionGivingOutQuery as DistributionGivingOutQueryORM;
use API\Models\Query;

class DistributionGivingOutQuery extends Query implements IDistributionGivingOutQuery
{
    public function find(): IDistributionGivingOutCollection
    {
        $distributionGivingOuts = DistributionGivingOutQueryORM::create()->find();

        $distributionGivingOutCollection = $this->container->get(IDistributionGivingOutCollection::class);
        $distributionGivingOutCollection->setCollection($distributionGivingOut);

        return $distributionGivingOutCollection;
    }

    public function findPk($id): IDistributionGivingOut
    {
        $distributionGivingOut = DistributionGivingOutQueryORM::create()->findPk($id);

        $distributionGivingOutModel = $this->container->get(IDistributionGivingOut::class);
        $distributionGivingOutModel->setModel($distributionGivingOut);

        return $distributionGivingOutModel;
    }
}
