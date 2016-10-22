<?php

namespace API\Controllers\Utility;

use API\Lib\Controller;

class Constants extends Controller
{    
    protected function ANY()            
    {
        $a_defined_constants = get_defined_constants(TRUE)['user'];
        
        $a_js_constants = array();

        foreach ($a_defined_constants as $str_name => $value)
        {
            $str_namespace = "API\\";
            $i_start = strlen($str_namespace);

            if (0 === strpos($str_name, $str_namespace))
            {
                $str_name = substr($str_name, $i_start);

                if(0 === strpos($str_name, "PRINTER_"))
                    continue;

                $a_js_constants[$str_name] = $value;
            }
        }

        $this->o_response->withJson($a_js_constants);
    }
}