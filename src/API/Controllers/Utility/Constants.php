<?php

namespace API\Controllers\Utility;

use API\Lib\Controller;

class Constants extends Controller
{
    protected function any() : void
    {
        $definedConstants = get_defined_constants(true)['user'];

        $jsConstants = array();

        foreach ($definedConstants as $name => $value) {
            $namespace = "API\\";
            $start = strlen($namespace);

            if (0 === strpos($name, $namespace)) {
                $name = substr($name, $start);

                if (0 === strpos($name, "PRINTER_")) {
                    continue;
                }

                $jsConstants[$name] = $value;
            }
        }

        $this->withJson($jsConstants);
    }
}
