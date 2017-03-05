<?php

namespace API\Lib\Interfaces\Models\Payment;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface IPaymentRecieved extends IModel {
    /**
     * @return int
     */
    function getPaymentRecievedid();

    /**
     * @return int
     */
    function getInvoiceid();

    /**
     * @return IInvoice
     */
    function getInvoice();

    /**
     * @return int
     */
    function getPaymentTypeid();

    /**
     * @return IPaymentType
     */
    function getPaymentType();

    /**
     * @return int
     */
    function getUserid();

    /**
     * @return IUser
     */
    function getUser();

    /**
     * @return Datetime
     */
    function getDate();

    /**
     * @return float
     */
    function getAmount();

    /**
     *
     * @param int $paymentRecievedid Description
     * @return IPaymentRecieved Description
     */
    function setPaymentRecievedid($paymentRecievedid);

    /**
     *
     * @param int $invoiceid Description
     * @return IPaymentRecieved Description
     */
    function setInvoiceid($invoiceid);

    /**
     *
     * @param IInvoice $invoice Description
     * @return IPaymentRecieved Description
     */
    function setInvoice($invoice);

    /**
     *
     * @param int $paymentTypeid Description
     * @return IPaymentRecieved Description
     */
    function setPaymentTypeid($paymentTypeid);

    /**
     *
     * @param IPaymentType $paymentType Description
     * @return IPaymentRecieved Description
     */
    function setPaymentType($paymentType);

    /**
     *
     * @param int $userid Description
     * @return IPaymentRecieved Description
     */
    function setUserid($userid);

    /**
     *
     * @param IUser $user Description
     * @return IPaymentRecieved Description
     */
    function setUser($user);

    /**
     *
     * @param DateTime $date Description
     * @return IPaymentRecieved Description
     */
    function setDate($date);

    /**
     *
     * @param float $amount Description
     * @return IPaymentRecieved Description
     */
    function setAmount($amount);
}