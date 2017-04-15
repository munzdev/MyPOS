<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Payment\IPaymentType;
use API\Models\Model;
use API\Models\ORM\Payment\PaymentType as PaymentTypeORM;

/**
 * Skeleton subclass for representing a row from the 'payment_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class PaymentType extends Model implements IPaymentType
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new PaymentTypeORM());
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getPaymentTypeid(): int
    {
        return $this->model->getPaymentTypeid();
    }

    public function setName($name): IPaymentType
    {
        $this->model->setName($name);
        return $this;
    }

    public function setPaymentTypeid($paymentTypeid): IPaymentType
    {
        $this->model->setPaymentTypeid($paymentTypeid);
        return $this;
    }

}
