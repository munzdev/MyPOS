<?php

namespace API\Models\DistributionPlace;

use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUser;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUserCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUserQuery;
use API\Models\ORM\DistributionPlace\DistributionPlaceUserQuery as DistributionPlaceUserQueryORM;
use API\Models\Query;

class DistributionPlaceUserQuery extends Query implements IDistributionPlaceUserQuery
{
    public function find(): IDistributionPlaceUserCollection
    {
        $distributionPlaceUser = DistributionPlaceUserQueryORM::create()->find();

        $distributionPlaceUserCollection = $this->container->get(IDistributionPlaceUserCollection::class);
        $distributionPlaceUserCollection->setCollection($distributionPlaceUser);

        return $distributionPlaceUserCollection;
    }

    public function findPk($id): ?IDistributionPlaceUser
    {
        $distributionPlaceUser = DistributionPlaceUserQueryORM::create()->findPk($id);

        if($distributionPlaceUser) {
            return null;
        }

        $distributionPlaceUserModel = $this->container->get(IDistributionPlaceUser::class);
        $distributionPlaceUserModel->setModel($distributionPlaceUser);

        return $distributionPlaceUserModel;
    }

    public function getByUser(int $userid, int $eventid) : ?IDistributionPlaceUser
    {
        $distributionPlaceUser = DistributionPlaceUserQueryORM::create()
                                    ->filterByUserid($userid)
                                    ->useDistributionPlaceQuery()
                                        ->filterByEventid($eventid)
                                    ->endUse()
                                    ->findOne();

        if(!$distributionPlaceUser) {
            return null;
        }

        $distributionPlaceUserModel = $this->container->get(IDistributionPlaceUser::class);
        $distributionPlaceUserModel->setModel($distributionPlaceUser);

        return $distributionPlaceUserModel;
    }
}
