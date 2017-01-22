<?php

namespace API\Controllers\Utility;

use API\Lib\Controller;
use DateTime;

class MaturityDate extends Controller
{
    protected function ANY() : void
    {
        $a_config = $this->o_app->getContainer()['settings'];

        $o_maturityDate = new DateTime($a_config['Invoice']['MaturityDate']);

        $this->withJson(["MaturityDate" => $o_maturityDate->format(DateTime::ATOM)]);
    }
}