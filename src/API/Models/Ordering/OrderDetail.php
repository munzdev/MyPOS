<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Ordering\Base\OrderDetail as BaseOrderDetail;

/**
 * Skeleton subclass for representing a row from the 'order_detail' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderDetail extends BaseOrderDetail implements IOrderDetail
{
    public function getSinglePriceModifiedByUser(): IUser
    {
        $this->getUser();
    }

    public function setSinglePriceModifiedByUser($user): IOrderDetail
    {
        $this->setUser($user);
        return $this;
    }

}
