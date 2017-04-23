<?php

namespace API\Models\OIP;

use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutCollection;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutQuery;
use API\Models\ORM\OIP\DistributionGivingOutQuery as DistributionGivingOutQueryORM;
use API\Models\Query;
use DateTime;

class DistributionGivingOutQuery extends Query implements IDistributionGivingOutQuery
{
    public function find(): IDistributionGivingOutCollection
    {
        $distributionGivingOuts = DistributionGivingOutQueryORM::create()->find();

        $distributionGivingOutCollection = $this->container->get(IDistributionGivingOutCollection::class);
        $distributionGivingOutCollection->setCollection($distributionGivingOuts);

        return $distributionGivingOutCollection;
    }

    public function findPk($id): ?IDistributionGivingOut
    {
        $distributionGivingOut = DistributionGivingOutQueryORM::create()->findPk($id);

        if(!$distributionGivingOut) {
            return null;
        }

        $distributionGivingOutModel = $this->container->get(IDistributionGivingOut::class);
        $distributionGivingOutModel->setModel($distributionGivingOut);

        return $distributionGivingOutModel;
    }


    public function getDoneOrdersCount(int $userid, int $minutes) : int
    {
        return DistributionGivingOutQueryORM::create()
                ->useOrderInProgressRecievedQuery()
                    ->useOrderInProgressQuery()
                        ->filterByUserid($userid)
                    ->endUse()
                ->endUse()
                ->filterByDate(['min' => new DateTime("-$minutes minutes")])
                ->count();
    }

    public function getWithOrderDetails(int $distributionGivingOutid) : ?IDistributionGivingOut
    {
        $distributionGivingOut = DistributionGivingOutQueryORM::create()
                                    ->joinWithOrderInProgressRecieved()
                                    ->useOrderInProgressRecievedQuery()
                                        ->joinWithOrderDetail()
                                        ->useOrderDetailQuery()
                                            ->leftJoinWithMenu()
                                            ->leftJoinWithMenuSize()
                                            ->leftJoinWithOrderDetailExtra()
                                            ->useOrderDetailExtraQuery(null, Criteria::LEFT_JOIN)
                                                ->leftJoinWithMenuPossibleExtra()
                                                ->useMenuPossibleExtraQuery(null, Criteria::LEFT_JOIN)
                                                    ->leftJoinWithMenuExtra()
                                                ->endUse()
                                            ->endUse()
                                            ->leftJoinWithOrderDetailMixedWith()
                                        ->endUse()
                                    ->endUse()
                                    ->filterByDistributionGivingOutid($distributionGivingOutid)
                                    ->find()
                                    ->getFirst();

        if(!$distributionGivingOut) {
            return null;
        }

        $distributionGivingOutModel = $this->container->get(IDistributionGivingOut::class);
        $distributionGivingOutModel->setModel($distributionGivingOut);

        return $distributionGivingOutModel;
    }

}
