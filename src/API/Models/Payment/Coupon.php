<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Payment\Base\Coupon as BaseCoupon;

/**
 * Skeleton subclass for representing a row from the 'coupon' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Coupon extends BaseCoupon implements ICoupon
{
    public function getCreatedByUser(): IUser
    {
        return $this->getUser();
    }

    public function setCreatedByUser($user): ICoupon
    {
        $this->setUser($user);
    }
}
