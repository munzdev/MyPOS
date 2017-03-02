<?php
namespace API\Lib;

use API\Lib\Interfaces\IPrintingInformation;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Payment\PaymentRecieved;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PrintingInformation implements IPrintingInformation
{
    private $entries = array();

    private $cashier;

    private $date;

    private $dateFooter;

    private $invoiceid;

    private $orderNr;

    private $paymentid;

    private $tableNr;

    private $header;

    private $contact;

    private $customer;

    private $paymentRecieved = array();

    private $eventBankinformation;

    private $name;

    private $maturityDate;

    private $logoFile;

    private $logoType;

    public function addRow($name, $amount, $price = null, $tax = null)
    {
        if (!isset($this->entries[$tax])) {
            $this->entries[$tax] = array();
        }

        foreach ($this->entries[$tax] as $key => $entrie) {
            if ($entrie['name'] == $name && $entrie['price'] == $price) {
                $this->entries[$tax][$key]['amount'] += $amount;
                return;
            }
        }

        $entrie = array('name' => $name,
                        'amount' => $amount,
                        'price' => $price);

        $this->entries[$tax][] = $entrie;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setDateFooter($date)
    {
        $this->dateFooter = $date;
    }

    public function setInvoiceid($invoiceid)
    {
        $this->invoiceid = $invoiceid;
    }

    public function setPaymentid($paymentid)
    {
        $this->paymentid = $paymentid;
    }

    public function setOrderNr($orderNr)
    {
        $this->orderNr = $orderNr;
    }

    public function setTableNr($tableNr)
    {
        $this->tableNr = $tableNr;
    }

    public function setHeader($text)
    {
        $this->header = $text;
    }

    public function setContact(EventContact $eventContact)
    {
        $this->contact = $eventContact;
    }

    public function setCustomer(EventContact $eventContact)
    {
        $this->customer = $eventContact;
    }

    public function addPaymentRecieved(PaymentRecieved $paymentRecieved)
    {
        $this->paymentRecieved[] = $paymentRecieved;
    }

    public function setBankinformation(EventBankinformation $eventBankinformation)
    {
        $this->eventBankinformation = $eventBankinformation;
    }

    public function setMaturityDate($maturityDate)
    {
        $this->maturityDate = $maturityDate;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setLogoType($type) {
        $this->logoType = $type;
    }

    public function setLogoFile($file)
    {
        $this->logoFile = $file;
    }

    public function getBankinformation()
    {
        return $this->eventBankinformation;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDateFooter()
    {
        return $this->dateFooter;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getInvoiceid()
    {
        return $this->invoiceid;
    }

    public function getLogoType()
    {
        return $this->logoType;
    }

    public function getLogoFile()
    {
        return $this->logoFile;
    }

    public function getMaturityDate()
    {
        return $this->maturityDate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOrderNr()
    {
        return $this->orderNr;
    }

    public function getPaymentRecieveds()
    {
        return $this->paymentRecieved;
    }

    public function getPaymentid()
    {
        return $this->paymentid;
    }

    public function getRows()
    {
        return $this->entries;
    }

    public function getTableNr()
    {
        return $this->tableNr;
    }

    public function getCashier()
    {
        return $this->cashier;
    }

    public function setCashier($cashier)
    {
        $this->cashier = $cashier;
    }

}
