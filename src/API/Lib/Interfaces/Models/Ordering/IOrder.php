<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface IOrder extends IModel {
    /**
     * @return int
     */
    function getOrderid();

    /**
     * @return int
     */
    function getEventTableid();

    /**
     * @return IEventTable
     */
    function getEventTable();

    /**
     * @return int
     */
    function getUserid();

    /**
     * @return IUser
     */
    function getUser();

    /**
     * @return DateTime
     */
    function getOrdertime();

    /**
     * @return int
     */
    function getPriority();

    /**
     * @return DateTime
     */
    function getDistributionFinished();

    /**
     * @return DateTime
     */
    function getInvoiceFinished();

    /**
     * @return DateTime
     */
    function getCancellation();

    /**
     * @return int
     */
    function getCancellationCreatedByUserid();

    /**
     * @return IUser
     */
    function getCancellationCreatedByUser();

    /**
     * @param int $orderid Description
     * @return IOrder Description
     */
    function setOrderid($orderid);

    /**
     * @param int $eventTableid Description
     * @return IOrder Description
     */
    function setEventTableid($eventTableid);

    /**
     * @param IEventTable $eventTable Description
     * @return IOrder Description
     */
    function setEventTable($eventTable);

    /**
     * @param int $userid Description
     * @return IOrder Description
     */
    function setUserid($userid);

    /**
     * @param IUser $user Description
     * @return IOrder Description
     */
    function setUser($user);

    /**
     * @param DateTime $ordertime Description
     * @return IOrder Description
     */
    function setOrdertime($ordertime);

    /**
     * @param int $priority Description
     * @return IOrder Description
     */
    function setPriority($priority);

    /**
     * @param DateTime $distributionFinished Description
     * @return IOrder Description
     */
    function setDistributionFinished($distributionFinished);

    /**
     * @param DateTime $invoiceFinished Description
     * @return IOrder Description
     */
    function setInvoiceFinished($invoiceFinished);

    /**
     * @param DateTime $cancellation Description
     * @return IOrder Description
     */
    function setCancellation($cancellation);

    /**
     * @param int $userid Description
     * @return IOrder Description
     */
    function setCancellationCreatedByUserid($userid);

    /**
     * @param IUser $user Description
     * @return IOrder Description
     */
    function setCancellationCreatedByUser($user);
}