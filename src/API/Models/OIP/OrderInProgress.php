<?php

namespace API\Models\OIP;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\OIP\OrderInProgress as OrderInProgressORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'order_in_progress' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderInProgress extends Model implements IOrderInProgress
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new OrderInProgressORM());
    }

    public function getBegin(): DateTime
    {
        return $this->model->getBegin();
    }

    public function getDone(): DateTime
    {
        return $this->model->getDone();
    }

    public function getMenuGroup(): IMenuGroup
    {
        $menuGroup = $this->model->getMenuGroup();

        $menuGroupModel = $this->container->get(IMenuGroup::class);
        $menuGroupModel->setModel($menuGroup);

        return $menuGroupModel;
    }

    public function getMenuGroupid(): int
    {
        return $this->model->getMenuGroupid();
    }

    public function getOrder(): IOrder
    {
        $order = $this->model->getOrder();

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getOrderInProgressid(): int
    {
        return $this->model->getOrderInProgressid();
    }

    public function getOrderid(): int
    {
        return $this->model->getOrderid();
    }

    public function getUser(): IUser
    {
        $user = $this->model->getUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getUserid(): int
    {
        return $this->model->getUserid();
    }

    public function setBegin($begin): IOrderInProgress
    {
        $this->model->setBegin($begin);
        return $this;
    }

    public function setDone($done): IOrderInProgress
    {
        $this->model->setDone($done);
        return $this;
    }

    public function setMenuGroup($menuGroup): IOrderInProgress
    {
        $this->model->setMenuGroup($menuGroup);
        return $this;
    }

    public function setMenuGroupid($menuGroupid): IOrderInProgress
    {
        $this->model->setMenuGroupid($menuGroupid);
        return $this;
    }

    public function setOrder(IOrder $order): IOrderInProgress
    {
        $this->model->setOrder($order->getModel());
        return $this;
    }

    public function setOrderInProgressid($orderInProgressid): IOrderInProgress
    {
        $this->model->setOrderInProgressid($orderInProgressid);
        return $this;
    }

    public function setOrderid($orderid): IOrderInProgress
    {
        $this->model->setOrderid($orderid);
        return $this;
    }

    public function setUser(IUser $user): IOrderInProgress
    {
        $this->model->setUser($user->getModel());
        return $this;
    }

    public function setUserid($userid): IOrderInProgress
    {
        $this->model->setUserid($userid);
        return $this;
    }

}
