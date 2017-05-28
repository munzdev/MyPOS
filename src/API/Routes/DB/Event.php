<?php

$app->group(
    '/DB/Event',
    function () {
        $this->any('', new API\Controllers\DB\Event\Event($this))
            ->setName('DB-Event');
        $this->any('/{id:[0-9]+}', new API\Controllers\DB\Event\EventModify($this))
            ->setName('DB-Event-Modify');
    }
);
