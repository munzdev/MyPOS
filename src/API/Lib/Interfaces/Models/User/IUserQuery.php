<?php

namespace API\Lib\Interfaces\Models\User;

use API\Lib\Interfaces\Models\IQuery;

interface IUserQuery extends IQuery {
    /**
     *
     * @param string $username
     */
    function getActiveEventUserByUsername($username) : IUserCollection;

    /**
     *
     * @param string $username
     */
    function getActiveAdminUserByUsername($username) : ?IUser;

    /**
     *
     * @param int $eventid
     * @return IUserCollection
     */
    function getUsersByEventid($eventid) : IUserCollection;
}