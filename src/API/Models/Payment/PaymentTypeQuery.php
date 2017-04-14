<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\IPaymentTypeCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeQuery;
use API\Models\ORM\Payment\PaymentTypeQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'payment_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class PaymentTypeQuery implements IPaymentTypeQuery
{
    public function find(): IPaymentTypeCollection
    {
        $paymentType = PaymentTypeQuery::create()->find();

        $paymentTypeCollection = $this->container->get(IPaymentTypeCollection::class);
        $paymentTypeCollection->setCollection($paymentType);

        return $paymentTypeCollection;
    }

}
