<?php

namespace API\Controllers\Utility;

use API\Lib\Controller;
use DateTime;

class MaturityDate extends Controller
{
    protected function any() : void
    {
        $config = $this->container->get('settings');

        $maturityDate = new DateTime($config['Invoice']['MaturityDate']);

        $this->withJson(["MaturityDate" => $maturityDate->format(DateTime::ATOM)]);
    }
}
