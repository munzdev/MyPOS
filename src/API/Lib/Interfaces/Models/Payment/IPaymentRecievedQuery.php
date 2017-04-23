<?php

namespace API\Lib\Interfaces\Models\Payment;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\ORM\Payment\Map\PaymentCouponTableMap;
use API\Models\ORM\Payment\PaymentRecievedQuery as PaymentRecievedQueryORM;
use Propel\Runtime\ActiveQuery\Criteria;

interface IPaymentRecievedQuery extends IQuery {

    public function getDetailsForInvoice(int $invoiceid): IPaymentRecievedCollection;
}