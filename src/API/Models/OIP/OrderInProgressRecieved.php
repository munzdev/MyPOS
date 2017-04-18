<?php

namespace API\Models\OIP;

use API\Lib\Container;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecieved;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Models\Model;
use API\Models\ORM\OIP\OrderInProgressRecieved as OrderInProgressRecievedORM;

/**
 * Skeleton subclass for representing a row from the 'order_in_progress_recieved' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderInProgressRecieved extends Model implements IOrderInProgressRecieved
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new OrderInProgressRecievedORM());
    }

    public function getAmount(): int
    {
        return $this->model->getAmount();
    }

    public function getDistributionGivingOut(): IDistributionGivingOut
    {
        $distributionGibingOut = $this->model->getDistributionGivingOut();

        $distributionGibingOutModel = $this->container->get(IDistributionGivingOut::class);
        $distributionGibingOutModel->setModel($distributionGibingOut);

        return $distributionGibingOutModel;
    }

    public function getDistributionGivingOutid(): int
    {
        return $this->model->getDistributionGivingOutid();
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

    public function getOrderInProgress(): IOrderInProgress
    {
        $orderInProgress = $this->model->getOrderInProgress();

        $orderInProgressModel = $this->container->get(IOrderInProgress::class);
        $orderInProgressModel->setModel($orderInProgress);

        return $orderInProgressModel;
    }

    public function getOrderInProgressRecievedid(): int
    {
        return $this->model->getOrderInProgressRecievedid();
    }

    public function getOrderInProgressid(): int
    {
        return $this->model->getOrderInProgressid();
    }

    public function setAmount($amount): IOrderInProgressRecieved
    {
        $this->model->setAmount($amount);
        return $this;
    }

    public function setDistributionGivingOut($distributionGivingOut): IOrderInProgressRecieved
    {
        $this->model->setDistributionGivingOut($distributionGivingOut);
        return $this;
    }

    public function setDistributionGivingOutid($distributionGivingOutid): IOrderInProgressRecieved
    {
        $this->model->setDistributionGivingOutid($distributionGivingOutid);
        return $this;
    }

    public function setOrderDetail(IOrderDetail $orderDetail): IOrderInProgressRecieved
    {
        $this->model->setOrderDetail($orderDetail->getModel());
        return $this;
    }

    public function setOrderDetailid($orderDetailid): IOrderInProgressRecieved
    {
        $this->model->setOrderDetailid($orderDetailid);
        return $this;
    }

    public function setOrderInProgress($orderInProgress): IOrderInProgressRecieved
    {
        $this->model->setOrderInProgress($orderInProgress);
        return $this;
    }

    public function setOrderInProgressRecievedid($orderInProgressRecievedid): IOrderInProgressRecieved
    {
        $this->model->setOrderInProgressRecievedid($orderInProgressRecievedid);
        return $this;
    }

    public function setOrderInProgressid($orderInProgressid): IOrderInProgressRecieved
    {
        $this->model->setOrderInProgressid($orderInProgressid);
        return $this;
    }

}
