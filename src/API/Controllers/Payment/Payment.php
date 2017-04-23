<?php

namespace API\Controllers\Payment;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Payment\IPaymentCoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedQuery;
use API\Lib\SecurityController;
use API\Models\ORM\Payment\PaymentCoupon;
use API\Models\ORM\Payment\PaymentRecieved;
use DateTime;
use Propel\Runtime\Propel;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_PAYMENT_ADD;

class Payment extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['POST' => USER_ROLE_PAYMENT_ADD];

        $this->container->get(IConnectionInterface::class);
    }

    protected function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = $this->container->get(IConnectionInterface::class);

        try {
            $connection->beginTransaction();

            $paymentRecievedTemplate = $this->container->get(IPaymentRecieved::class);

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $paymentRecievedTemplate);

            $paymentRecieved = $this->container->get(IPaymentRecieved::class);
            $paymentRecieved->setInvoiceid($paymentRecievedTemplate->getInvoiceid());
            $paymentRecieved->setPaymentTypeid($paymentRecievedTemplate->getPaymentTypeid());
            $paymentRecieved->setUser($user);
            $paymentRecieved->setDate(new DateTime());
            $paymentRecieved->setAmount($paymentRecievedTemplate->getAmount());
            $paymentRecieved->save();

            foreach ($paymentRecievedTemplate->getPaymentCoupons() as $paymentCouponTemplate) {
                $paymentCoupon = $this->container->get(IPaymentCoupon::class);
                $paymentCoupon->setPaymentRecieved($paymentRecieved);
                $paymentCoupon->setCouponid($paymentCouponTemplate->getCouponid());
                $paymentCoupon->setValueUsed($paymentCouponTemplate->getValueUsed());
                $paymentCoupon->save();
            }

            $connection->commit();

            $this->withJson($paymentRecievedTemplate->toArray());
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }
}
