<?php

namespace API\Lib\Interfaces;

use API\Lib\Interfaces\Models\User\IUser;

interface IAuth {
    function doLogin(string $username) : bool;
    function checkLogin(string $username, string $password) : bool;
    function setLogin(IUser $user) : void;
    function getCurrentUser();
    function isLoggedIn() : bool;
    function logout() : void;
}