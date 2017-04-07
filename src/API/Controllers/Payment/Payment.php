<?php

namespace API\Controllers\Payment;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\IAuth;
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

        $app->getContainer()['db'];
    }

    protected function post() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();
        $connection = Propel::getConnection();

        try {
            $connection->beginTransaction();

            $paymentRecievedTemplate = new PaymentRecieved();

            $jsonToModel = $this->container->get(IJsonToModel::class);
            $jsonToModel->convert($this->json, $paymentRecievedTemplate);

            $paymentRecieved = new PaymentRecieved();
            $paymentRecieved->setInvoiceid($paymentRecievedTemplate->getInvoiceid());
            $paymentRecieved->setPaymentTypeid($paymentRecievedTemplate->getPaymentTypeid());
            $paymentRecieved->setUser($user);
            $paymentRecieved->setDate(new DateTime());
            $paymentRecieved->setAmount($paymentRecievedTemplate->getAmount());
            $paymentRecieved->save();

            foreach ($paymentRecievedTemplate->getPaymentCoupons() as $paymentCouponTemplate) {
                $paymentCoupon = new PaymentCoupon();
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
