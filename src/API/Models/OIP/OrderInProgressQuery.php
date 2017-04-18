<?php

namespace API\Models\OIP;

use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressCollection;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressQuery;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\ORM\OIP\OrderInProgressQuery as OrderInProgressQueryORM;
use API\Models\Query;

class OrderInProgressQuery extends Query implements IOrderInProgressQuery
{
    public function find(): IOrderInProgressCollection
    {
        $orderInProgresses = OrderInProgressQueryORM::create()->find();

        $orderInProgressCollection = $this->container->get(IOrderInProgressCollection::class);
        $orderInProgressCollection->setCollection($orderInProgresses);

        return $orderInProgressCollection;
    }

    public function findPk($id): IOrderInProgress
    {
        $orderInProgress = OrderInProgressQueryORM::create()->findPk($id);

        $orderInProgressModel = $this->container->get(IOrderInProgress::class);
        $orderInProgressModel->setModel($orderInProgress);

        return $orderInProgressModel;
    }

    public function getActiveByUserAndOrder(IUser $user, IOrder $order)
    {
        $orderInProgresses = OrderInProgressQueryORM::create()
                                ->filterByUserid($user->getUserid())
                                ->filterByOrderid($order->getOrderid())
                                ->filterByDone()
                                ->find();

        $orderInProgressCollection = $this->container->get(IOrderInProgressCollection::class);
        $orderInProgressCollection->setCollection($orderInProgresses);

        return $orderInProgressCollection;
    }

    public function getOpenOrderInProgress(int $userid, int $eventid): IOrderInProgress
    {
        $orderInProgress = OrderInProgressQueryORM::create()
                                ->filterByUserid($userid)
                                ->filterByDone()
                                ->useOrderQuery()
                                    ->useEventTableQuery()
                                        ->filterByEventid($eventid)
                                    ->endUse()
                                    ->useOrderDetailQuery()
                                        ->filterByAvailabilityid(ORDER_AVAILABILITY_AVAILABLE)
                                    ->endUse()
                                    ->orderByPriority()
                                ->endUse()
                                ->joinWithOrder()
                                ->find()
                                ->getFirst();

        $orderInProgressModel = $this->container->get(IOrderInProgress::class);
        $orderInProgressModel->setModel($orderInProgress);

        return $orderInProgressModel;
    }
}
