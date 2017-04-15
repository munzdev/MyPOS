<?php

namespace API\Lib\Interfaces;

use API\Lib\Interfaces\Models\User\IUser;

interface IAuth {
    /**
     * Logs the user in based on the username. Fetches the IUser object based on username, removes sensible user datas and calls setLogin()
     * Also will login Administrators users, even if current active event doesn't have a eventUser for username
     *
     * @param string $username
     * @return bool
     */
    function doLogin(string $username) : bool;

    /**
     * Verify if the given username and password are valid and user exists
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    function checkLogin(string $username, string $password) : bool;

    /**
     * Sets the IUser object in the session. Identifies the loged in user
     *
     * @param IUser $user
     * @return void
     */
    function setLogin(IUser $user) : void;

    /**
     * Returns the IUser object of the current logged in user. If user is not logged in, null will be returned
     *
     * @return IUser|null
     */
    function getCurrentUser() : ?IUser;

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    function isLoggedIn() : bool;

    /**
     * Logs teh user out
     *
     * @return void
     */
    function logout() : void;
}