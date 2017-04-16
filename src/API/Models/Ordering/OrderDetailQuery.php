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

    public function findPk($id): IOrderDetail
    {
        $orderDetail = OrderDetailQueryORM::create()->findPk($id);

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }
}
