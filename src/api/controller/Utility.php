<?php
namespace Controller;

use Lib\Controller;

class Utility extends Controller
{
    public function KeepSessionAliveAction()
    {
        return true;
    }

    public function ConstantsAction()
    {
        $a_defined_constants = get_defined_constants(TRUE)['user'];

        $a_js_constants = array();

        foreach ($a_defined_constants as $str_name => $value)
        {
            $str_namespace = "MyPOS\\";
            $i_start = strlen($str_namespace);

            if (0 === strpos($str_name, $str_namespace))
            {
                $str_name = substr($str_name, $i_start);

                if(0 === strpos($str_name, "PRINTER_"))
                    continue;

                $a_js_constants[$str_name] = $value;
            }
        }

        return $a_js_constants;
    }

    public function GetConfigAction()
    {
        $a_config = $GLOBALS['a_config'];

        return $a_config['App'];
    }
}