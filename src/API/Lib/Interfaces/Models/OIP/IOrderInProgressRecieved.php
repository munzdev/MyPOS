<?php

namespace API\Lib\Interfaces\Models\OIP;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;

interface IOrderInProgressRecieved extends IModel {
    /**
     * @return int
     */
    function getOrderInProgressRecievedid();

    /**
     * @return int
     */
    function getOrderDetailid();

    /**
     * @return IOrderDetail
     */
    function getOrderDetail();

    /**
     * @return int
     */
    function getOrderInProgressid();

    /**
     * @return IOrderInProgress
     */
    function getOrderInProgress();

    /**
     * @return int
     */
    function getDistributionGivingOutid();

    /**
     * @return IDistributionGivingOut
     */
    function getDistributionGivingOut();

    /**
     * @return int
     */
    function getAmount();

    /**
     * @param int $orderInProgressRecievedid Description
     * @return IOrderInProgressRecieved Description
     */
    function setOrderInProgressRecievedid($orderInProgressRecievedid);

    /**
     * @param int $orderDetailid Description
     * @return IOrderInProgressRecieved Description
     */
    function setOrderDetailid($orderDetailid);

    /**
     * @param IOrderDetail $orderDetail Description
     * @return IOrderInProgressRecieved Description
     */
    function setOrderDetail($orderDetail);

    /**
     * @param int $orderInProgressid Description
     * @return IOrderInProgressRecieved Description
     */
    function setOrderInProgressid($orderInProgressid);

    /**
     * @param IOrderInProgress $orderInProgress Description
     * @return IOrderInProgressRecieved Description
     */
    function setOrderInProgress($orderInProgress);

    /**
     * @param int $distributionGivingOutid Description
     * @return IOrderInProgressRecieved Description
     */
    function setDistributionGivingOutid($distributionGivingOutid);

    /**
     * @param IDistributionGivingOut $distributionGivingOut Description
     * @return IOrderInProgressRecieved Description
     */
    function setDistributionGivingOut($distributionGivingOut);

    /**
     * @param int $amount Description
     * @return IOrderInProgressRecieved Description
     */
    function setAmount($amount);
}