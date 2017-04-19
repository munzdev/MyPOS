<?php

namespace API\Models\DistributionPlace;

use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroup;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupQuery;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroupQuery as DistributionPlaceGroupQueryORM;
use API\Models\Query;

class DistributionPlaceGroupQuery extends Query implements IDistributionPlaceGroupQuery
{
    public function find(): IDistributionPlaceGroupCollection
    {
        $distributionPlaceGroups = DistributionPlaceGroupQueryORM::create()->find();

        $distributionPlaceGroupCollection = $this->container->get(IDistributionPlaceGroupCollection::class);
        $distributionPlaceGroupCollection->setCollection($distributionPlaceGroups);

        return $distributionPlaceGroupCollection;
    }

    public function findPk($id): ?IDistributionPlaceGroup
    {
        $distributionPlaceGroup = DistributionPlaceGroupQueryORM::create()->findPk($id);

        if(!$distributionPlaceGroup) {
            return null;
        }

        $distributionPlaceGroupModel = $this->container->get(IDistributionPlaceGroup::class);
        $distributionPlaceGroupModel->setModel($distributionPlaceGroup);

        return $distributionPlaceGroupModel;
    }

    public function getByMenuGroupsAndUser(array $menuGroupids, int $eventid, int $userid) : IDistributionPlaceGroupCollection
    {
        $distributionPlaceGroups = DistributionPlaceGroupQueryORM::create()
                                    ->filterByMenuGroupid($menuGroupids)
                                    ->useDistributionPlaceQuery()
                                        ->filterByEventid($eventid)
                                        ->useDistributionPlaceUserQuery()
                                            ->filterByUserid($userid)
                                        ->endUse()
                                    ->endUse()
                                    ->find();

        $distributionPlaceGroupCollection = $this->container->get(IDistributionPlaceGroupCollection::class);
        $distributionPlaceGroupCollection->setCollection($distributionPlaceGroups);

        return $distributionPlaceGroupCollection;
    }

    public function getUserDistributionPlaceGroups(int $eventid, int $userid) : IDistributionPlaceGroupCollection
    {
        $distributionPlaceGroups = DistributionPlaceGroupQueryORM::create()
                                                                    ->useDistributionPlaceQuery()
                                                                        ->filterByEventid($eventid)
                                                                        ->useDistributionPlaceUserQuery()
                                                                            ->filterByUserid($userid)
                                                                        ->endUse()
                                                                    ->endUse()
                                                                    ->joinWithDistributionPlaceTable()
                                                                    ->find();

        $distributionPlaceGroupCollection = $this->container->get(IDistributionPlaceGroupCollection::class);
        $distributionPlaceGroupCollection->setCollection($distributionPlaceGroups);

        return $distributionPlaceGroupCollection;
    }
}
