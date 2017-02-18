<?php

$app->group(
    '/DB/User',
    function () {
        $this->any('/UserRole', new API\Controllers\DB\User\UserRole($this))
            ->setName('DB-User-UserRole');
    }
);
