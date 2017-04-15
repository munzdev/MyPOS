<?php

namespace API\Lib\Interfaces\Models\User;

interface IUserQuery {
    /**
     *
     * @param string $username
     */
    function getActiveEventUserByUsername($username) : IUserCollection;

    /**
     *
     * @param string $username
     */
    function getActiveAdminUserByUsername($username) : IUser;

    /**
     *
     * @param type $userid
     * @return IUser
     */
    function findPk($userid) : IUser;

    /**
     *
     * @param type $eventid
     * @return IUserCollection
     */
    function getUsersByEventid($eventid) : IUserCollection;
}