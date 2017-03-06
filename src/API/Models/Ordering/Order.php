<?php

namespace API\Models\Ordering;

use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Ordering\Base\Order as BaseOrder;

/**
 * Skeleton subclass for representing a row from the 'order' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Order extends BaseOrder implements IOrder
{
    public function getCancellationCreatedByUser(): IUser
    {
        return $this->getUserRelatedByCancellationCreatedByUserid();
    }

    public function getUser(): IUser
    {
        return $this->getUserRelatedByUserid();
    }

    public function setCancellationCreatedByUser($user): IOrder
    {
        $this->setUserRelatedByCancellationCreatedByUserid($user);
        return $this;
    }

    public function setUser($user): IOrder
    {
        $this->setUserRelatedByUserid($user);
        return $this;
    }

}
