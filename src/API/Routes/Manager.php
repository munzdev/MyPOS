<?php

$app->group(
    '/Manager',
    function () {
        $this->any('/Callback[/{id:[0-9]+}]', new API\Controllers\Manager\Callback($this))
            ->setName('Manager-Callback');
        $this->any('/Check/Verified/{verified:[0-1]}', new API\Controllers\Manager\Check($this))
            ->setName('Manager-Check');
    }
);
