<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;

interface IEventContact extends IModel {
    /**
     * @return int
     */
    function getEventContactid();

    /**
     * @return int
     */
    function getEventid();

    /**
     * @return IEvent
     */
    function getEvent();

    /**
     * @return string
     */
    function getTitle();

    /**
     * @return string
     */
    function getName();

    /**
     * @return string
     */
    function getContactPerson();

    /**
     * @return string
     */
    function getAddress();

    /**
     * @return string
     */
    function getAddress2();

    /**
     * @return string
     */
    function getCity();

    /**
     * @return string
     */
    function getZip();

    /**
     * @return string
     */
    function getTaxIdentificationNr();

    /**
     * @return string
     */
    function getTelephon();

    /**
     * @return string
     */
    function getFax();

    /**
     * @return string
     */
    function getEmail();

    /**
     * @return \DateTime
     */
    function getIsDeleted();

    /**
     * @return boolean
     */
    function getDefault();

    /**
     * @param int $eventContactid Description
     * @return IEventContact Description
     */
    function setEventContactid($eventContactid);

    /**
     * @param int $eventid Description
     * @return IEventContact Description
     */
    function setEventid($eventid);

    /**
     * @param IEvent $event Description
     * @return IEventContact Description
     */
    function setEvent($event);

    /**
     * @param string $title Description
     * @return IEventContact Description
     */
    function setTitle($title);

    /**
     * @param string $name Description
     * @return IEventContact Description
     */
    function setName($name);

    /**
     * @param string $contactPerson Description
     * @return IEventContact Description
     */
    function setContactPerson($contactPerson);

    /**
     * @param string $address Description
     * @return IEventContact Description
     */
    function setAddress($address);

    /**
     * @param string $address2 Description
     * @return IEventContact Description
     */
    function setAddress2($address2);

    /**
     * @param string $city Description
     * @return IEventContact Description
     */
    function setCity($city);

    /**
     * @param string $zip Description
     * @return IEventContact Description
     */
    function setZip($zip);

    /**
     * @param string $taxIdentificationNr Description
     * @return IEventContact Description
     */
    function setTaxIdentificationNr($taxIdentificationNr);

    /**
     * @param string $telephon Description
     * @return IEventContact Description
     */
    function setTelephon($telephon);

    /**
     * @param string $fax Description
     * @return IEventContact Description
     */
    function setFax($fax);

    /**
     * @param string $email Description
     * @return IEventContact Description
     */
    function setEmail($email);

    /**
     * @param \DateTime $deleted Description
     * @return IEventContact Description
     */
    function setIsDeleted($isDeleted);

    /**
     * @param boolean $default Description
     * @return IEventContact Description
     */
    function setDefault($default);
}