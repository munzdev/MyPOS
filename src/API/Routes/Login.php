<?php

$app->group(
    '/Login',
    function () {
        $this->any('', new API\Controllers\Login\Login($this))
            ->setName('Login');
        $this->any('/User', new API\Controllers\Login\User($this))
            ->setName('Login-User');
    }
);
