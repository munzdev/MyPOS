<?php

$app->group('/User', function () {
    $this->any('', new API\Controllers\User\User($this))
         ->setName('User');
});