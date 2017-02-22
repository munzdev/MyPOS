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
}