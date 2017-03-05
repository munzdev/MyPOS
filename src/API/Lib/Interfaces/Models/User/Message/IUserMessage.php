<?php

namespace API\Lib\Interfaces\Models\User\Message;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\User\IUser;
use DateTime;

interface IUserMessage extends IModel {
    /**
     * @return int Description
     */
    function getUserMessageid();

    /**
     * @return int Description
     */
    function getFromEventUserid();

    /**
     * @return IUser Description
     */
    function getFromEventUser();

    /**
     * @return int Description
     */
    function getToEventUserid();

    /**
     * @return IUser Description
     */
    function getToEventUser();

    /**
     * @return string Description
     */
    function getMessage();

    /**
     * @return DateTime Descriptionon
     */
    function getDate();

    /**
     * @return boolean Description
     */
    function getReaded();

    /**
     * @param int $userMessageid
     * @return IUserMessage
     */
    function setUserMessageid($userMessageid);

    /**
     * @param int $userid
     * @return IUserMessage
     */
    function setFromEventUserid($userid);

    /**
     * @param IUser $user
     * @return IUserMessage
     */
    function setFromEventUser($user);

    /**
     * @param int $userid
     * @return IUserMessage
     */
    function setToEventUserid($userid);

    /**
     * @param IUser $user
     * @return IUserMessage
     */
    function setToEventUser($user);

    /**
     * @param string $message
     * @return IUserMessage
     */
    function setMessage($message);

    /**
     * @param DateTime $date
     * @return IUserMessage
     */
    function setDate($date);

    /**
     * @param boolean $readed
     * @return IUserMessage
     */
    function setReaded($readed);
}