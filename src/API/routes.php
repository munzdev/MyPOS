<?php
// Routes

$app->group('/Utility', function () {
    $this->map(['GET'], '/Constants', API\Controllers\Utility\Constants::class)->setName('Constants');
});