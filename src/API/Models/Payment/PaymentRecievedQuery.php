<?php

namespace API\Models\Payment;

use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedQuery;
use API\Models\ORM\Payment\Map\PaymentCouponTableMap;
use API\Models\ORM\Payment\PaymentRecievedQuery as PaymentRecievedQueryORM;
use API\Models\Query;
use Propel\Runtime\ActiveQuery\Criteria;

class PaymentRecievedQuery extends Query implements IPaymentRecievedQuery
{
    public function find(): IPaymentRecievedCollection
    {
        $paymentRecieveds = PaymentRecievedQueryORM::create()->find();

        $paymentRecievedCollection = $this->container->get(IPaymentRecievedCollection::class);
        $paymentRecievedCollection->setCollection($paymentRecieveds);

        return $paymentRecievedCollection;
    }

    public function findPk($id): ?IPaymentRecieved
    {
        $paymentRecieved = PaymentRecievedQueryORM::create()->findPk($id);

        if(!$paymentRecieved) {
            return null;
        }

        $paymentRecievedModel = $this->container->get(IPaymentRecieved::class);
        $paymentRecievedModel->setModel($paymentRecieved);

        return $paymentRecievedModel;
    }

    public function getDetailsForInvoice(int $invoiceid) : IPaymentRecievedCollection
    {
        $paymentRecieveds = PaymentRecievedQueryORM::create()
            ->joinWithUser()
            ->leftJoinWithPaymentCoupon()
            ->usePaymentCouponQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinCoupon()
            ->endUse()
            ->with(PaymentCouponTableMap::getTableMap()->getPhpName())
            ->filterByInvoiceid($invoiceid)
            ->find();

        $paymentRecievedCollection = $this->container->get(IPaymentRecievedCollection::class);
        $paymentRecievedCollection->setCollection($paymentRecieveds);

        return $paymentRecievedCollection;
    }
}
