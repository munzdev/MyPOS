<?php

$app->group('/Users', function () {
    $this->any('/Login', new API\Controllers\Users\Login($this))
         ->setName('Users-Login');
});