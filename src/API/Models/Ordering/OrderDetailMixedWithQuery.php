<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWith;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWithCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWithQuery;
use API\Models\ORM\Ordering\OrderDetailMixedWithQuery as OrderDetailMixedWithQueryORM;
use API\Models\Query;

class OrderDetailMixedWithQuery extends Query implements IOrderDetailMixedWithQuery
{
    public function find(): IOrderDetailMixedWithCollection
    {
        $orderDetailMixedWiths = OrderDetailMixedWithQueryORM::create()->find();

        $orderDetailMixedWithCollection = $this->container->get(IOrderDetailMixedWithCollection::class);
        $orderDetailMixedWithCollection->setCollection($orderDetailMixedWiths);

        return $orderDetailMixedWithCollection;
    }

    public function findPk($id): IOrderDetailMixedWith
    {
        $orderDetailMixedWith = OrderDetailMixedWithQueryORM::create()->findPk($id);

        $orderDetailMixedWithModel = $this->container->get(IOrderDetailMixedWith::class);
        $orderDetailMixedWithModel->setModel($orderDetailMixedWith);

        return $orderDetailMixedWithModel;
    }
}
