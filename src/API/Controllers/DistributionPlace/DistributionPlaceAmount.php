<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\SecurityController;
use Slim\App;
use const API\USER_ROLE_DISTRIBUTION_SET_AVAILABILITY;

class DistributionPlaceAmount extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['POST' => USER_ROLE_DISTRIBUTION_SET_AVAILABILITY];

        $o_app->getContainer()['db'];
    }
}