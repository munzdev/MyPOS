<?php

namespace API\Lib\Interfaces\Models\User;

use API\Lib\Interfaces\Models\Event\IEventUserCollection;
use API\Lib\Interfaces\Models\IModel;
use DateTime;

interface IUser extends IModel {
    /**
     * @return int Description
     */
    function getUserid();

    /**
     * @return string Description
     */
    function getUsername();

    /**
     * @return string
     */
    function getPassword();

    /**
     * @return string
     */
    function getFirstname();

    /**
     * @return string
     */
    function getLastname();

    /**
     * @return string
     */
    function getAutologinHash();

    /**
     * @return boolean Description
     */
    function getActive();

    /**
     * @return string Description
     */
    function getPhonenumber();

    /**
     * @return DateTime|null Description
     */
    function getCallRequest();

    /**
     * @return boolean Description
     */
    function getIsAdmin();

    /**
     * @return IEventUserCollection
     */
    function getEventUsers();

    /**
     *
     * @param int $userid Description
     * @return IUser Description
     */
    function setUserid($userid);

    /**
     * @param stirng $username Description
     * @return IUser Description
     */
    function setUsername($username);

    /**
     * @param string $password Description
     * @return IUser Description
     */
    function setPassword($password);

    /**
     * @param string $firstname Description
     * @return IUser Description
     */
    function setFirstname($firstname);

    /**
     * @param string $lastname Description
     * @return IUser Description
     */
    function setLastname($lastname);

    /**
     * @param string $autologinHash Description
     * @return IUser Description
     */
    function setAutologinHash($autologinHash);

    /**
     * @param boolean $active Description
     * @return IUser Description
     */
    function setActive($active);

    /**
     * @param string $phonenumber Description
     * @return IUser Description
     */
    function setPhonenumber($phonenumber);

    /**
     * @param DateTime|null callRequest Description
     * @return IUser Description
     */
    function setCallRequest($callRequest);

    /**
     * @param boolean $isAdmin Description
     * @return IUser Description
     */
    function setIsAdmin($isAdmin);
}