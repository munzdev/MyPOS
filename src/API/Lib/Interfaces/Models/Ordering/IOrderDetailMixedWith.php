<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Menu\IMenu;

interface IOrderDetailMixedWith extends IModel {
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
    function getMenuid();

    /**
     * @return IMenu
     */
    function getMenu();

    /**
     * @param int $orderDetailid Description
     * @return IOrderDetailMixedWith Description
     */
    function setOrderDetailid($orderDetailid);

    /**
     * @param IOrderDetail $orderDetail Description
     * @return IOrderDetailMixedWith Description
     */
    function setOrderDetail($orderDetail);

    /**
     * @param int $menuid Description
     * @return IOrderDetailMixedWith Description
     */
    function setMenuid($menuid);

    /**
     * @param IMenu $menu Description
     * @return IOrderDetailMixedWith Description
     */
    function setMenu($menu);
}