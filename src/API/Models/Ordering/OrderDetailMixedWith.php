<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWith;
use API\Models\Model;
use API\Models\ORM\Ordering\OrderDetailMixedWith as OrderDetailMixedWithORM;

/**
 * Skeleton subclass for representing a row from the 'order_detail_mixed_with' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderDetailMixedWith extends Model implements IOrderDetailMixedWith
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new OrderDetailMixedWithORM());
    }

    public function getMenu(): IMenu
    {
        $menu = $this->model->getMenu();

        $menuModel = $this->container->get(IMenu::class);
        $menuModel->setModel($menu);

        return $menuModel;
    }

    public function getMenuid(): int
    {
        return $this->model->getMenuid();
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

    public function setMenu($menu): IOrderDetailMixedWith
    {
        $this->model->setMenu($menu);
        return $this;
    }

    public function setMenuid($menuid): IOrderDetailMixedWith
    {
        $this->model->setMenuid($menuid);
        return $this;
    }

    public function setOrderDetail($orderDetail): IOrderDetailMixedWith
    {
        $this->model->setOrderDetail($orderDetail);
        return $this;
    }

    public function setOrderDetailid($orderDetailid): IOrderDetailMixedWith
    {
        $this->model->setOrderDetailid($orderDetailid);
        return $this;
    }

}
