<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailCollection;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\Ordering\Order as OrderORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'order' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Order extends Model implements IOrder
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new OrderORM());
    }

    public function getCancellation(): DateTime
    {
        return $this->model->getCancellation();
    }

    public function getCancellationCreatedByUser(): IUser
    {
        $user = $this->model->getCancellationCreatedByUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getCancellationCreatedByUserid(): int
    {
        return $this->model->getCancellationCreatedByUserid();
    }

    public function getDistributionFinished(): DateTime
    {
        return $this->model->getDistributionFinished();
    }

    public function getEventTable(): IEventTable
    {
        $eventTable = $this->model->getEventTable();

        $eventTableModel = $this->container->get(IEventTable::class);
        $eventTableModel->setModel($eventTable);

        return $eventTableModel;
    }

    public function getEventTableid(): int
    {
        return $this->model->getEventTableid();
    }

    public function getInvoiceFinished(): DateTime
    {
        return $this->model->getInvoiceFinished();
    }

    public function getOrderid(): int
    {
        return $this->model->getOrderid();
    }

    public function getOrdertime(): DateTime
    {
        return $this->model->getOrdertime();
    }

    public function getPriority(): int
    {
        return $this->model->getPriority();
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

    public function getOrderDetails() : IOrderDetailCollection {
        $orderDetails = $this->model->getOrderDetails();

        $orderDetailCollection = $this->container->get(IOrderDetailCollection::class);
        $orderDetailCollection->setCollection($orderDetails);

        return $orderDetailCollection;
    }

    public function setCancellation($cancellation): IOrder
    {
        $this->model->setCancellation($cancellation);
        return $this;
    }

    public function setCancellationCreatedByUser($user): IOrder
    {
        $this->model->setCancellationCreatedByUser($user);
        return $this;
    }

    public function setCancellationCreatedByUserid($userid): IOrder
    {
        $this->model->setCancellationCreatedByUserid($userid);
        return $this;
    }

    public function setDistributionFinished($distributionFinished): IOrder
    {
        $this->model->setDistributionFinished($distributionFinished);
        return $this;
    }

    public function setEventTable($eventTable): IOrder
    {
        $this->model->setEventTable($eventTable);
        return $this;
    }

    public function setEventTableid($eventTableid): IOrder
    {
        $this->model->setEventTableid($eventTableid);
        return $this;
    }

    public function setInvoiceFinished($invoiceFinished): IOrder
    {
        $this->model->setInvoiceFinished($invoiceFinished);
        return $this;
    }

    public function setOrderid($orderid): IOrder
    {
        $this->model->setOrderid($orderid);
        return $this;
    }

    public function setOrdertime($ordertime): IOrder
    {
        $this->model->setOrdertime($ordertime);
        return $this;
    }

    public function setPriority($priority): IOrder
    {
        $this->model->setPriority($priority);
        return $this;
    }

    public function setUser($user): IOrder
    {
        $this->model->setUser($user);
        return $this;
    }

    public function setUserid($userid): IOrder
    {
        $this->model->setUserid($userid);
        return $this;
    }

}
