<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemCollection;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecievedCollection;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface IOrderDetail extends IModel {
    /**
     * @return int
     */
    function getOrderDetailid();

    /**
     * @return int
     */
    function getOrderid();

    /**
     * @return IOrder
     */
    function getOrder();

    /**
     * @return ?int
     */
    function getMenuid();

    /**
     * @return IMenu
     */
    function getMenu();

    /**
     * @return int
     */
    function getMenuSizeid();

    /**
     * @return IMenuSize
     */
    function getMenuSize();

    /**
     * @return int
     */
    function getMenuGroupid();

    /**
     * @return IMenuGroup
     */
    function getMenuGroup();

    /**
     * @return int
     */
    function getAmount();

    /**
     * @return float
     */
    function getSinglePrice();

    /**
     * @return int
     */
    function getSinglePriceModifiedByUserid();

    /**
     * @return IUser
     */
    function getSinglePriceModifiedByUser();

    /**
     * @return ?string
     */
    function getExtraDetail();

    /**
     * @return int
     */
    function getAvailabilityid();

    /**
     * @return IAvailability
     */
    function getAvailability();

    /**
     * @return ?int
     */
    function getAvailabilityAmount();

    /**
     * @return boolean
     */
    function getVerified();

    /**
     * @return ?DateTime
     */
    function getDistributionFinished();

    /**
     * @return ?DateTime
     */
    function getInvoiceFinished();

    /**
     *
     * @return IOrderDetailExtraCollection
     */
    function getOrderDetailExtras() : IOrderDetailExtraCollection;

    /**
     *
     * @return IOrderDetailMixedWithCollection
     */
    function getOrderDetailMixedWiths() : IOrderDetailMixedWithCollection;

    /**
     * @return IOrderInProgressRecievedCollection
     */
    function getOrderInProgressRecieveds() : IOrderInProgressRecievedCollection;

    /**
     * @return IInvoiceItemCollection
     */
    function getInvoiceItems() : IInvoiceItemCollection;

    /**
     * @param int $orderDetailid Description
     * @return IOrderDetail Description
     */
    function setOrderDetailid($orderDetailid);

    /**
     * @param int $orderid Description
     * @return IOrderDetail Description
     */
    function setOrderid($orderid);

    /**
     * @param IOrder $order Description
     * @return IOrderDetail Description
     */
    function setOrder($order);

    /**
     * @param int $menuid Description
     * @return IOrderDetail Description
     */
    function setMenuid($menuid);

    /**
     * @param IMenu $menu Description
     * @return IOrderDetail Description
     */
    function setMenu($menu);

    /**
     * @param int $menuSizeid Description
     * @return IOrderDetail Description
     */
    function setMenuSizeid($menuSizeid);

    /**
     * @param IMenuSize $menuSize Description
     * @return IOrderDetail Description
     */
    function setMenuSize($menuSize);

    /**
     * @param int $menuGroupid Description
     * @return IOrderDetail Description
     */
    function setMenuGroupid($menuGroupid);

    /**
     * @param IMenuGroup $menuGroup Description
     * @return IOrderDetail Description
     */
    function setMenuGroup($menuGroup);

    /**
     * @param int $amount Description
     * @return IOrderDetail Description
     */
    function setAmount($amount);

    /**
     * @param float $singlePrice Description
     * @return IOrderDetail Description
     */
    function setSinglePrice($singlePrice);

    /**
     * @param int $userid Description
     * @return IOrderDetail Description
     */
    function setSinglePriceModifiedByUserid($userid);

    /**
     * @param IUser $user Description
     * @return IOrderDetail Description
     */
    function setSinglePriceModifiedByUser($user);

    /**
     * @param string $extraDetail Description
     * @return IOrderDetail Description
     */
    function setExtraDetail($extraDetail);

    /**
     * @param int $availabilityid Description
     * @return IOrderDetail Description
     */
    function setAvailabilityid($availabilityid);

    /**
     * @param IAvailability $availability Description
     * @return IOrderDetail Description
     */
    function setAvailability($availability);

    /**
     * @param int $availabilityAmount Description
     * @return IOrderDetail Description
     */
    function setAvailabilityAmount($availabilityAmount);

    /**
     * @param boolean $verified Description
     * @return IOrderDetail Description
     */
    function setVerified($verified);

    /**
     * @param DateTime $distributionFinished Description
     * @return IOrderDetail Description
     */
    function setDistributionFinished($distributionFinished);

    /**
     * @param DateTime $invoiceFinished Description
     * @return IOrderDetail Description
     */
    function setInvoiceFinished($invoiceFinished);
}