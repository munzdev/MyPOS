<?php

$app->group('/Users', function () {
    $this->any('/', new API\Controllers\Users\Users($this))
         ->setName('Users');
    $this->any('/Current', new API\Controllers\Users\Users($this))
         ->setName('Users-Current');
});