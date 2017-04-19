<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\IPaymentType;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeQuery;
use API\Models\ORM\Payment\PaymentTypeQuery as PaymentTypeQueryORM;
use API\Models\Query;

class PaymentTypeQuery extends Query implements IPaymentTypeQuery
{
    public function find(): IPaymentTypeCollection
    {
        $paymentTypes = PaymentTypeQueryORM::create()->find();

        $paymentTypeCollection = $this->container->get(IPaymentTypeCollection::class);
        $paymentTypeCollection->setCollection($paymentTypes);

        return $paymentTypeCollection;
    }

    public function findPk($id): ?IPaymentType
    {
        $paymentType = PaymentTypeQueryORM::create()->findPk($id);

        if(!$paymentType) {
            return null;
        }

        $paymentTypeModel = $this->container->get(IPaymentType::class);
        $paymentTypeModel->setModel($paymentType);

        return $paymentTypeModel;
    }

}
