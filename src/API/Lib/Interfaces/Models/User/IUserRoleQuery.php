<?php

namespace API\Lib\Interfaces\Models\User;

interface IUserRoleQuery {
    /**
     * @return IUserRoleCollection
     */
    function getRoles() : IUserRoleCollection;
}