<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\User\IUser;

interface IEventUser extends IModel {
    /**
     * @return float Description
     */
    function getBeginMoney();

    /**
     * @return int Description
     */
    function getEventUserid();

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
    function getUserid();

    /**
     * @return IUser
     */
    function getUser();

    /**
     * @return int
     */
    function getUserRoles();

    /**
     *
     * @param float $beginMoney Description
     * @return IEventUser Description
     */
    function setBeginMoney($beginMoney);

    /**
     * @param int $eventUserid Description
     * @return IEventUser Description
     */
    function setEventUserid($eventUserid);

    /**
     * @param int $eventid Description
     * @return IEventUser Description
     */
    function setEventid($eventid);

    /**
     * @param IEvent $eventid
     * @return IEventUser Description
     */
    function setEvent($eventid);

    /**
     * @param int $userid Description
     * @return IEventUser Description
     */
    function setUserid($userid);

    /**
     * @param IUser $user Description
     * @return IEventUser Description
     */
    function setUser($user);

    /**
     * @param int $userRoles Description
     * @return IEventUser Description
     */
    function setUserRoles($userRoles);
}