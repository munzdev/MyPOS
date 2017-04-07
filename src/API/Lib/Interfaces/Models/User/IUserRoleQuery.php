<?php

namespace API\Lib\Interfaces\Models\User;

interface IUserRoleQuery {
    function find() : IUserRoleCollection;
}