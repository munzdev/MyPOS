<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtra;

interface IOrderDetailExtra extends IModel {
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
    function getMenuPossibleExtraid();

    /**
     * @return IMenuPossibleExtra
     */
    function getMenuPossibleExtra();



    /**
     * @param int $orderDetailid Description
     * @return IOrderDetailExtra Description
     */
    function setOrderDetailid($orderDetailid);

    /**
     * @param IOrderDetail $orderDetail Description
     * @return IOrderDetailExtra Description
     */
    function setOrderDetail($orderDetail);

    /**
     * @param int $menuPossibleExtraid Description
     * @return IOrderDetailExtra Description
     */
    function setMenuPossibleExtraid($menuPossibleExtraid);

    /**
     * @param IMenuPossibleExtra $menuPossibleExtra Description
     * @return IOrderDetailExtra Description
     */
    function setMenuPossibleExtra($menuPossibleExtra);
}