<?php

$app->group('/Users', function () {
    $this->any('/', new API\Controllers\Users\Users($this))
         ->setName('Users');
});