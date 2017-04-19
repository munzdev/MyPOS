<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtra;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtraCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtraQuery;
use API\Models\ORM\Ordering\OrderDetailExtraQuery as OrderDetailExtraQueryORM;
use API\Models\Query;

class OrderDetailExtraQuery extends Query implements IOrderDetailExtraQuery
{
    public function find(): IOrderDetailExtraCollection
    {
        $orderDetailExtras = OrderDetailExtraQueryORM::create()->find();

        $orderDetailExtraCollection = $this->container->get(IOrderDetailExtraCollection::class);
        $orderDetailExtraCollection->setCollection($orderDetailExtras);

        return $orderDetailExtraCollection;
    }

    public function findPk($id): ?IOrderDetailExtra
    {
        $orderDetailExtra = OrderDetailExtraQueryORM::create()->findPk($id);

        if(!$orderDetailExtra) {
            return null;
        }

        $orderDetailExtraModel = $this->container->get(IOrderDetailExtra::class);
        $orderDetailExtraModel->setModel($orderDetailExtra);

        return $orderDetailExtraModel;
    }
}
