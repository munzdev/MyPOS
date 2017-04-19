<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Models\ORM\Ordering\OrderDetailQuery as OrderDetailQueryORM;
use API\Models\Query;

class OrderDetailQuery extends Query implements IOrderDetailQuery
{
    public function find(): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function findPk($id): ?IOrderDetail
    {
        $orderDetail = OrderDetailQueryORM::create()->findPk($id);

        if(!$orderDetail) {
            return null;
        }

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getDistributionUnfinishedByMenuid($menuid): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
                                ->filterByDistributionFinished()
                                ->useMenuQuery()
                                    ->filterByMenuid($menu->getMenuid())
                                ->endUse()
                                ->_or()
                                ->useOrderDetailMixedWithQuery(null, Criteria::LEFT_JOIN)
                                    ->useMenuQuery('re', Criteria::LEFT_JOIN)
                                        ->filterByMenuid($menu->getMenuid())
                                    ->endUse()
                                ->endUse()
                                ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function getDistributionUnfinishedByMenuExtraid($menuExtraid): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
                            ->filterByDistributionFinished()
                            ->useOrderDetailExtraQuery()
                                ->useMenuPossibleExtraQuery()
                                    ->filterByMenuExtraid($menuExtraid)
                                ->endUse()
                            ->endUse()
                            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function setAvailabilityidByOrderDetailIds(int $availabilityid, array $ids): int
    {
        return OrderDetailQueryORM::create()
                                    ->filterByOrderDetailid($ids)
                                    ->update(['Availabilityid' => $availabilityid]);
    }

    public function getVerifiedDistributionUnfinishedWithSpecialExtras(): IOrderDetailCollection
    {
        $orderDetails = OrderDetailQueryORM::create()
                            ->filterByMenuid()
                            ->filterByDistributionFinished()
                            ->filterByVerified(true)
                            //->setFormatter(ModelCriteria::FORMAT_ARRAY)
                            ->find();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }
}
