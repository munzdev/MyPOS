<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtra;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtra;
use API\Models\Model;
use API\Models\ORM\Ordering\OrderDetailExtra as OrderDetailExtraORM;

/**
 * Skeleton subclass for representing a row from the 'order_detail_extra' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderDetailExtra extends Model implements IOrderDetailExtra
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new OrderDetailExtraORM());
    }

    public function getMenuPossibleExtra(): IMenuPossibleExtra
    {
        $menuPossibleExtra = $this->model->getMenuPossibleExtra();

        $menuPossibleExtraModel = $this->container->get(IMenuPossibleExtra::class);
        $menuPossibleExtraModel->setModel($menuPossibleExtra);

        return $menuPossibleExtraModel;
    }

    public function getMenuPossibleExtraid(): int
    {
        return $this->model->getMenuPossibleExtraid();
    }

    public function getOrderDetail(): IOrderDetail
    {
        $orderDetail = $this->model->getOrderDetail();

        $orderDetailModel = $this->container->get(IOrderDetail::class);
        $orderDetailModel->setModel($orderDetail);

        return $orderDetailModel;
    }

    public function getOrderDetailid(): int
    {
        return $this->model->getOrderDetailid();
    }

    public function setMenuPossibleExtra($menuPossibleExtra): IOrderDetailExtra
    {
        $this->model->setMenuPossibleExtra($menuPossibleExtra);
        return $this;
    }

    public function setMenuPossibleExtraid($menuPossibleExtraid): IOrderDetailExtra
    {
        $this->model->setMenuPossibleExtraid($menuPossibleExtraid);
        return $this;
    }

    public function setOrderDetail($orderDetail): IOrderDetailExtra
    {
        $this->model->setOrderDetail($orderDetail);
        return $this;
    }

    public function setOrderDetailid($orderDetailid): IOrderDetailExtra
    {
        $this->model->setOrderDetailid($orderDetailid);
        return $this;
    }

}
