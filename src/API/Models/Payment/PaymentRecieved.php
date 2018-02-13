<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Payment\IPaymentCouponCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Lib\Interfaces\Models\Payment\IPaymentType;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\Payment\PaymentRecieved as PaymentRecievedORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'payment_recieved' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class PaymentRecieved extends Model implements IPaymentRecieved
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new PaymentRecievedORM());
    }

    public function getAmount(): float
    {
        return $this->model->getAmount();
    }

    public function getDate(): Datetime
    {
        return $this->model->getDate();
    }

    public function getInvoice(): IInvoice
    {
        $invoice = $this->model->getInvoice();

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }

    public function getInvoiceid(): int
    {
        return $this->model->getInvoiceid();
    }

    public function getPaymentRecievedid(): int
    {
        return $this->model->getPaymentRecievedid();
    }

    public function getPaymentType(): IPaymentType
    {
        $invoiceType = $this->model->getPaymentType();

        $invoiceTypeModel = $this->container->get(IPaymentType::class);
        $invoiceTypeModel->setModel($invoiceType);

        return $invoiceTypeModel;
    }

    public function getPaymentTypeid(): int
    {
        return $this->model->getPaymentTypeid();
    }

    public function getUser(): IUser
    {
        $user = $this->model->getUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getUserid(): int
    {
        return $this->model->getUserid();
    }

    public function addPaymentCoupon(PaymentCoupon $paymentCoupon)
    {
		$this->model->addPaymentCoupon($paymentCoupon->getModel());
    }

    public function getPaymentCoupons() : IPaymentCouponCollection
    {
        $paymentCoupons = $this->model->getPaymentCoupons();

        $paymentCouponCollection = $this->container->get(IPaymentCouponCollection::class);
        $paymentCouponCollection->setCollection($paymentCoupons);

        return $paymentCouponCollection;
    }

    public function setAmount($amount): IPaymentRecieved
    {
        $this->model->setAmount($amount);
        return $this;
    }

    public function setDate($date): IPaymentRecieved
    {
        $this->model->setDate($date);
        return $this;
    }

    public function setInvoice($invoice): IPaymentRecieved
    {
        $this->model->setInvoice($invoice->getModel());
        return $this;
    }

    public function setInvoiceid($invoiceid): IPaymentRecieved
    {
        $this->model->setInvoiceid($invoiceid);
        return $this;
    }

    public function setPaymentRecievedid($paymentRecievedid): IPaymentRecieved
    {
        $this->model->setPaymentRecievedid($paymentRecievedid);
        return $this;
    }

    public function setPaymentType($paymentType): IPaymentRecieved
    {
        $this->model->setPaymentType($paymentType->getModel());
        return $this;
    }

    public function setPaymentTypeid($paymentTypeid): IPaymentRecieved
    {
        $this->model->setPaymentTypeid($paymentTypeid);
        return $this;
    }

    public function setUser($user): IPaymentRecieved
    {
        $this->model->setUser($user->getModel());
        return $this;
    }

    public function setUserid($userid): IPaymentRecieved
    {
        $this->model->setUserid($userid);
        return $this;
    }

}
