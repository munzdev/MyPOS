<?php

$app->group(
    '/User',
    function () {
        $this->any('', new API\Controllers\User\User($this))
            ->setName('User');
        $this->any('/CallRequest', new API\Controllers\User\CallRequest($this))
            ->setName('User-CallRequest');
    }
);
