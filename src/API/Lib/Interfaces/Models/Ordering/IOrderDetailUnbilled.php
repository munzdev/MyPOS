<?php

namespace API\Lib\Interfaces\Models\Ordering;

// TODO Remove this file!!!
interface IOrderDetailUnbilled extends IOrderDetail
{
    /**
     * @return int
     */
    function getAmountSelected();

    /**
     * @return int
     */
    function getAmountLeft();

    /**
     * @param int $amount Description
     * @return IOrderDetailUnbilled Description
     */
    function setAmountSelected($amount);

    /**
     * @param int $order Description
     * @return IOrderDetailUnbilled Description
     */
    function setAmountLeft($amount);
}