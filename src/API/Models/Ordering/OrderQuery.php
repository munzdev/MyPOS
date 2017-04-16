<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Models\ORM\Ordering\OrderQuery as OrderQueryORM;
use API\Models\Query;

class OrderQuery extends Query implements IOrderQuery
{
    public function find(): IOrderCollection
    {
        $orders = OrderQueryORM::create()->find();

        $orderCollection = $this->container->get(IOrderCollection::class);
        $orderCollection->setCollection($orders);

        return $orderCollection;
    }

    public function findPk($id): IOrder
    {
        $order = OrderQueryORM::create()->findPk($id);

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }
}
