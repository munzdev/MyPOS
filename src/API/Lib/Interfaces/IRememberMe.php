<?php

namespace API\Lib\Interfaces;

interface IRememberMe {
    /**
     * Inits class
     *
     * @param string $privateKey
     */
    function __construct(string $privateKey);

    /**
     * Parses and validates existing cookies. Returns cookie datas if valid
     */
    function parseCookie();

    /**
     * Validates the hash saved in the cookie with the hash in the DB to the userid stored in cookie
     *
     * @param string $hash
     */
    function validateHash(string $hash);

    /**
     * Creates cookie information for the given userid
     *
     * @param int $userid
     * @return string The generated Hash for cookie validation
     */
    function remember(int $userid);

    /**
     * Destroy the user cookie and all information
     */
    function destroy();
}