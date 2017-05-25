<?php

$app->group(
    '/DB/User',
    function () {
        $this->any('', new API\Controllers\DB\User\User($this))
            ->setName('DB-User');
        $this->any('/{id:[0-9]+}', new API\Controllers\DB\User\UserModify($this))
            ->setName('DB-User-Modify');
        $this->any('/UserRole', new API\Controllers\DB\User\UserRole($this))
            ->setName('DB-User-UserRole');
    }
);
