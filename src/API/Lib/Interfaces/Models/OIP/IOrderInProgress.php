<?php

namespace API\Lib\Interfaces\Models\OIP;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface IOrderInProgress extends IModel {
    /**
     * @return int
     */
    function getOrderInProgressid();

    /**
     * @return int
     */
    function getOrderid();

    /**
     * @return IOrder
     */
    function getOrder();

    /**
     * @return int
     */
    function getUserid();

    /**
     * @return IUser
     */
    function getUser();

    /**
     * @return int
     */
    function getMenuGroupid();

    /**
     * @return IMenuGroup
     */
    function getMenuGroup();

    /**
     * @return DateTime
     */
    function getBegin();

    /**
     * @return DateTime
     */
    function getDone();

    /**
     * @param int $orderInProgressid Description
     * @return IOrderInProgress Description
     */
    function setOrderInProgressid($orderInProgressid);

    /**
     * @param int $orderid Description
     * @return IOrderInProgress Description
     */
    function setOrderid($orderid);

    /**
     * @param IOrder $order Description
     * @return IOrderInProgress Description
     */
    function setOrder($order);

    /**
     * @param int $userid Description
     * @return IOrderInProgress Description
     */
    function setUserid($userid);

    /**
     * @param IUser $user Description
     * @return IOrderInProgress Description
     */
    function setUser($user);

    /**
     * @param int $menuGroupid Description
     * @return IOrderInProgress Description
     */
    function setMenuGroupid($menuGroupid);

    /**
     * @param IMenuGroup $menuGroup Description
     * @return IOrderInProgress Description
     */
    function setMenuGroup($menuGroup);

    /**
     * @param DateTime $begin Description
     * @return IOrderInProgress Description
     */
    function setBegin($begin);

    /**
     * @param DateTime $done Description
     * @return IOrderInProgress Description
     */
    function setDone($done);
}