<?php

namespace API\Lib\Interfaces;

interface IRememberMe {
    function __construct(string $privateKey);
    function parseCookie();
    function validateHash(string $hash);
    function remember(int $userid);
}