<?php

$app->group(
    '/DB/Event',
    function () {
        $this->any('', new API\Controllers\DB\Event\Event($this))
            ->setName('DB-Event');
        $this->any('/{id:[0-9]+}', new API\Controllers\DB\Event\EventModify($this))
            ->setName('DB-Event-Modify');
        $this->any('/EventTable/Eventid/{id:[0-9]+}', new API\Controllers\DB\Event\EventTable($this))
            ->setName('DB-Event-EventTable');
        $this->any('/EventTable/{id:[0-9]+}', new API\Controllers\DB\Event\EventTableModify($this))
            ->setName('DB-Event-EventTable-Modify');
    }
);
