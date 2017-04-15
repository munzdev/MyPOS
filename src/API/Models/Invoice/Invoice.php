<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventBankinformation;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceType;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\Invoice\Invoice as InvoiceORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'invoice' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Invoice extends Model implements IInvoice
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new InvoiceORM());
    }

    public function getAmount(): float
    {
        return $this->model->getAmount();
    }

    public function getAmountRecieved(): float
    {
        return $this->model->getAmountRecieved();
    }

    public function getCanceledInvoice(): IInvoice
    {
        $canceledInvoice = $this->model->getCanceledInvoice();

        $canceledInvoiceModel = $this->container->get(IInvoice::class);
        $canceledInvoiceModel->setModel($canceledInvoice);

        return $canceledInvoiceModel;
    }

    public function getCanceledInvoiceid(): int
    {
        return $this->model->getCanceledInvoiceid();
    }

    public function getCustomerEventContact(): IEventContact
    {
        $customerEventContact = $this->model->getCustomerEventContact();

        $customerEventContactModel = $this->container->get(IEventContact::class);
        $customerEventContactModel->setModel($customerEventContact);

        return $customerEventContactModel;
    }

    public function getCustomerEventContactid(): int
    {
        return $this->model->getCustomerEventContactid();
    }

    public function getDate(): DateTime
    {
        return $this->model->getDate();
    }

    public function getEventBankinformation(): IEventBankinformation
    {
        $eventBankinformation = $this->model->getEventBankinformation();

        $eventBankinformationModel = $this->container->get(IEventBankinformation::class);
        $eventBankinformationModel->setModel($eventBankinformation);

        return $eventBankinformationModel;
    }

    public function getEventBankinformationid(): int
    {
        return $this->model->getEventBankinformationid();
    }

    public function getEventContact(): IEventContact
    {
        $eventContact = $this->model->getEventContact();

        $eventContactModel = $this->container->get(IEventContact::class);
        $eventContactModel->setModel($eventContact);

        return $eventContactModel;
    }

    public function getEventContactid(): int
    {
        return $this->model->getEventContactid();
    }

    public function getInvoiceType(): IInvoiceType
    {
        $invoiceType = $this->model->getInvoiceType();

        $invoiceTypeModel = $this->container->get(IInvoiceType::class);
        $invoiceTypeModel->setModel($invoiceType);

        return $invoiceTypeModel;
    }

    public function getInvoiceTypeid(): int
    {
        return $this->model->getInvoiceTypeid();
    }

    public function getInvoiceid(): int
    {
        return $this->model->getInvoiceid();
    }

    public function getMaturityDate(): DateTime
    {
        return $this->model->getMaturityDate();
    }

    public function getPaymentFinished(): DateTime
    {
        return $this->model->getPaymentFinished();
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

    public function setAmount($amount): IInvoice
    {
        $this->model->setAmount($amount);
        return $this;
    }

    public function setAmountRecieved($amountRecieved): IInvoice
    {
        $this->model->setAmountRecieved($amountRecieved);
        return $this;
    }

    public function setCanceledInvoice($invoice): IInvoice
    {
        $this->model->setCanceledInvoice($invoice);
        return $this;
    }

    public function setCanceledInvoiceid($invoiceid): IInvoice
    {
        $this->model->setCanceledInvoiceid($invoiceid);
        return $this;
    }

    public function setCustomerEventContact($eventContact): IInvoice
    {
        $this->model->setCustomerEventContact($eventContact);
        return $this;
    }

    public function setCustomerEventContactid($eventContactid): IInvoice
    {
        $this->model->setCustomerEventContactid($eventContactid);
        return $this;
    }

    public function setDate($date): IInvoice
    {
        $this->model->setDate($date);
        return $this;
    }

    public function setEventBankinformation($eventBankinformation): IInvoice
    {
        $this->model->setEventBankinformation($eventBankinformation);
        return $this;
    }

    public function setEventBankinformationid($eventBankinformationid): IInvoice
    {
        $this->model->setEventBankinformationid($eventBankinformationid);
        return $this;
    }

    public function setEventContact($eventContact): IInvoice
    {
        $this->model->setEventContact($eventContact);
        return $this;
    }

    public function setEventContactid($eventContactid): IInvoice
    {
        $this->model->setEventContactid($eventContactid);
        return $this;
    }

    public function setInvoiceType($invoiceType): IInvoice
    {
        $this->model->setInvoiceType($invoiceType);
        return $this;
    }

    public function setInvoiceTypeid($invoiceTypeid): IInvoice
    {
        $this->model->setInvoiceTypeid($invoiceTypeid);
        return $this;
    }

    public function setInvoiceid($invoiceid): IInvoice
    {
        $this->model->setInvoiceid($invoiceid);
        return $this;
    }

    public function setMaturityDate($maturityDate): IInvoice
    {
        $this->model->setMaturityDate($maturityDate);
        return $this;
    }

    public function setPaymentFinished($paymentFinished): IInvoice
    {
        $this->model->setPaymentFinished($paymentFinished);
        return $this;
    }

    public function setUser($user): IInvoice
    {
        $this->model->setUser($user);
        return $this;
    }

    public function setUserid($userid): IInvoice
    {
        $this->model->setUserid($userid);
        return $this;
    }

}
