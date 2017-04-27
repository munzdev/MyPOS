<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrderDetailUnbilled;

// TODO Remove this file!!!
class OrderDetailUnbilled extends OrderDetail implements IOrderDetailUnbilled
{
    /**
     * @return int
     */
    function getAmountSelected()
    {
        return $this->model->getVirtualColumn('AmountSelected');
    }

    /**
     * @return int
     */
    function getAmountLeft()
    {
        return $this->model->getVirtualColumn('AmountLeft');
    }

    /**
     * @param int $amount Description
     * @return IOrderDetailUnbilled Description
     */
    function setAmountSelected($amount)
    {
        $this->model->setVirtualColumn('AmountSelected', $amount);
        return $this;
    }

    /**
     * @param int $order Description
     * @return IOrderDetailUnbilled Description
     */
    function setAmountLeft($amount)
    {
        $this->model->setVirtualColumn('AmountLeft', $amount);
        return $this;
    }
}
