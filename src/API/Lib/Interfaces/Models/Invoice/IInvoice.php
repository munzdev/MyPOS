<?php

namespace API\Lib\Interfaces\Models\Invoice;

use API\Lib\Interfaces\Models\Event\IEventBankinformation;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface IInvoice extends IModel {
    /**
     * @return int
     */
    function getInvoiceid();

    /**
     * @return int
     */
    function getInvoiceTypeid();

    /**
     * @return IInvoiceType
     */
    function getInvoiceType();

    /**
     * @return int
     */
    function getEventContactid();

    /**
     * @return IEventContact
     */
    function getEventContact();

    /**
     * @return int
     */
    function getUserid();

    /**
     * @return IUser
     */
    function getUser();

    /**
     * @return int
     */
    function getEventBankinformationid();

    /**
     * @return IEventBankinformation
     */
    function getEventBankinformation();

    /**
     * @return int
     */
    function getCustomerEventContactid();

    /**
     * @return IEventContact
     */
    function getCustomerEventContact();

    /**
     * @return int
     */
    function getCanceledInvoiceid();

    /**
     * @return IInvoice
     */
    function getCanceledInvoice();

    /**
     * @return DateTime
     */
    function getDate();

    /**
     * @return float
     */
    function getAmount();

    /**
     * @return DateTime
     */
    function getMaturityDate();

    /**
     * @return DateTime
     */
    function getPaymentFinished();

    /**
     * @return float
     */
    function getAmountRecieved();

    /**
     *
     * @param int $invoiceid Description
     * @return IInvoice Description
     */
    function setInvoiceid($invoiceid);

    /**
     *
     * @param int $invoiceTypeid Description
     * @return IInvoice Description
     */
    function setInvoiceTypeid($invoiceTypeid);

    /**
     *
     * @param IInvoiceType $invoiceType Description
     * @return IInvoice Description
     */
    function setInvoiceType($invoiceType);

    /**
     *
     * @param int $eventContactid Description
     * @return IInvoice Description
     */
    function setEventContactid($eventContactid);

    /**
     *
     * @param IEventContact $eventContact Description
     * @return IInvoice Description
     */
    function setEventContact($eventContact);

    /**
     *
     * @param int $userid Description
     * @return IInvoice Description
     */
    function setUserid($userid);

    /**
     *
     * @param IUser $user Description
     * @return IInvoice Description
     */
    function setUser($user);

    /**
     *
     * @param int $eventBankinformationid Description
     * @return IInvoice Description
     */
    function setEventBankinformationid($eventBankinformationid);

    /**
     *
     * @param IEventBankinformation $eventBankinformation Description
     * @return IInvoice Description
     */
    function setEventBankinformation($eventBankinformation);

    /**
     *
     * @param int $eventContactid Description
     * @return IInvoice Description
     */
    function setCustomerEventContactid($eventContactid);

    /**
     *
     * @param IEventContact $eventContact Description
     * @return IInvoice Description
     */
    function setCustomerEventContact($eventContact);

    /**
     *
     * @param int $invoiceid Description
     * @return IInvoice Description
     */
    function setCanceledInvoiceid($invoiceid);

    /**
     *
     * @param IInvoice $invoice Description
     * @return IInvoice Description
     */
    function setCanceledInvoice($invoice);

    /**
     *
     * @param DateTime $date Description
     * @return IInvoice Description
     */
    function setDate($date);

    /**
     *
     * @param float $amount Description
     * @return IInvoice Description
     */
    function setAmount($amount);

    /**
     *
     * @param DateTime $maturityDate Description
     * @return IInvoice Description
     */
    function setMaturityDate($maturityDate);

    /**
     *
     * @param DateTime $paymentFinished Description
     * @return IInvoice Description
     */
    function setPaymentFinished($paymentFinished);

     /**
     *
     * @param float $amountRecieved Description
     * @return IInvoice Description
     */
    function setAmountRecieved($amountRecieved);
}