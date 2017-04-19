<?php

namespace API\Models\OIP;

use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecieved;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecievedCollection;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecievedQuery;
use API\Models\ORM\OIP\OrderInProgressRecievedQuery as OrderInProgressRecievedQueryORM;
use API\Models\Query;

class OrderInProgressRecievedQuery extends Query implements IOrderInProgressRecievedQuery
{
    public function find(): IOrderInProgressRecievedCollection
    {
        $orderInProgressRecieveds = OrderInProgressRecievedQueryORM::create()->find();

        $orderInProgressRecievedCollection = $this->container->get(IOrderInProgressRecievedCollection::class);
        $orderInProgressRecievedCollection->setCollection($orderInProgressRecieveds);

        return $orderInProgressRecievedCollection;
    }

    public function findPk($id): ?IOrderInProgressRecieved
    {
        $orderInProgressRecieved = OrderInProgressRecievedQueryORM::create()->findPk($id);

        if(!$orderInProgressRecieved) {
            return null;
        }

        $orderInProgressRecievedModel = $this->container->get(IOrderInProgressRecieved::class);
        $orderInProgressRecievedModel->setModel($orderInProgressRecieved);

        return $orderInProgressRecievedModel;
    }
}
