<?php

namespace API\Lib\Interfaces\Models\Payment;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface ICoupon extends IModel {
    /**
     * @return int
     */
    function getCouponid();

    /**
     * @return int
     */
    function getEventid();

    /**
     * @return IEvent
     */
    function getEvent();

    /**
     * @return int
     */
    function getCreatedByUserid();

    /**
     * @return IUser
     */
    function getCreatedByUser();

    /**
     * @return string
     */
    function getCode();

    /**
     * @return DateTime
     */
    function getCreated();

    /**
     * @return float
     */
    function getValue();

    /**
     * @return DateTime
     */
    function getIsDeleted();

    /**
     *
     * @param int $couponid Description
     * @return ICoupon Description
     */
    function setCouponid($couponid);

    /**
     *
     * @param int $eventid Description
     * @return ICoupon Description
     */
    function setEventid($eventid);

    /**
     *
     * @param IEvent $event Description
     * @return ICoupon Description
     */
    function setEvent($event);

    /**
     *
     * @param int $userid Description
     * @return ICoupon Description
     */
    function setCreatedByUserid($userid);

    /**
     *
     * @param IUser $user Description
     * @return ICoupon Description
     */
    function setCreatedByUser($user);

    /**
     *
     * @param string $code Description
     * @return ICoupon Description
     */
    function setCode($code);

    /**
     *
     * @param DateTime $created Description
     * @return ICoupon Description
     */
    function setCreated($created);

    /**
     *
     * @param float $value Description
     * @return ICoupon Description
     */
    function setValue($value);

    /**
     *
     * @param DateTime $deleted Description
     * @return ICoupon Description
     */
    function setIsDeleted($isDeleted);
}