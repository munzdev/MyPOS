<?php
namespace API\Lib\Interfaces;

use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Payment\PaymentRecieved;

interface IPrintingInformation
{
    function addRow($name, $amount, $price = null, $tax = null);
    function getRows();

    function setDate($date);
    function getDate();

    function setDateFooter($date);
    function getDateFooter();

    function setInvoiceid($invoiceid);
    function getInvoiceid();

    function setPaymentid($paymentid);
    function getPaymentid();

    function setOrderNr($orderNr);
    function getOrderNr();

    function setTableNr($tableNr);
    function getTableNr();

    function setHeader($text);
    function getHeader();

    function setContact(EventContact $eventContact);
    function getContact();

    function setCustomer(EventContact $eventContact);
    function getCustomer();

    function setCashier($cashier);
    function getCashier();

    function addPaymentRecieved(PaymentRecieved $paymentRecieved);
    function getPaymentRecieveds();

    function setBankinformation(EventBankinformation $eventBankinformation);

    /**
     *  @return EventBankinformation|null
     */
    function getBankinformation();

    function setMaturityDate($maturityDate);
    function getMaturityDate();

    function setName($name);
    function getName();

    function setLogoType($type);
    function getLogoType();

    function setLogoFile($file);
    function getLogoFile();
}
