<?php

namespace API\Models\OIP;

use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressCollection;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressQuery;
use API\Models\ORM\OIP\OrderInProgressQuery as OrderInProgressQueryORM;
use API\Models\Query;

class OrderInProgressQuery extends Query implements IOrderInProgressQuery
{
    public function find(): IOrderInProgressCollection
    {
        $orderInProgresss = OrderInProgressQueryORM::create()->find();

        $orderInProgressCollection = $this->container->get(IOrderInProgressCollection::class);
        $orderInProgressCollection->setCollection($orderInProgress);

        return $orderInProgressCollection;
    }

    public function findPk($id): IOrderInProgress
    {
        $orderInProgress = OrderInProgressQueryORM::create()->findPk($id);

        $orderInProgressModel = $this->container->get(IOrderInProgress::class);
        $orderInProgressModel->setModel($orderInProgress);

        return $orderInProgressModel;
    }
}
