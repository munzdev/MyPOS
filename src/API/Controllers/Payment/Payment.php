<?php

namespace API\Controllers\Payment;;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Payment\PaymentCoupon;
use API\Models\Payment\PaymentRecieved;
use DateTime;
use Propel\Runtime\Propel;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_PAYMENT_ADD;

class Payment extends SecurityController
{
    protected $o_auth;

    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['POST' => USER_ROLE_PAYMENT_ADD];

        $o_app->getContainer()['db'];
    }

    protected function POST() : void {
        $o_user = Auth::GetCurrentUser();
        $o_connection = Propel::getConnection();

        try {
            $o_connection->beginTransaction();

            $o_paymentRecieved_template = new PaymentRecieved();
            $this->jsonToPropel($this->a_json, $o_paymentRecieved_template);


            $o_paymentRecieved = new PaymentRecieved();
            $o_paymentRecieved->setInvoiceid($o_paymentRecieved_template->getInvoiceid());
            $o_paymentRecieved->setPaymentTypeid($o_paymentRecieved_template->getPaymentTypeid());
            $o_paymentRecieved->setUser($o_user);
            $o_paymentRecieved->setDate(new DateTime());
            $o_paymentRecieved->setAmount($o_paymentRecieved_template->getAmount());
            $o_paymentRecieved->save();

            $i_amount = 0;

            foreach($o_paymentRecieved_template->getPaymentCoupons() as $o_paymentCoupon_template) {
                $o_paymentCoupon = new PaymentCoupon();
                $o_paymentCoupon->setPaymentRecieved($o_paymentRecieved);
                $o_paymentCoupon->setCouponid($o_paymentCoupon_template->getCouponid());
                $o_paymentCoupon->setValueUsed($o_paymentCoupon_template->getValueUsed());
                $o_paymentCoupon->save();
            }

            $o_connection->commit();

            $this->withJson($o_paymentRecieved_template->toArray());
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }

}