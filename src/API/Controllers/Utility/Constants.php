<?php

/* 
 * Copyright (C) 2016 Thomas
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace API\Controllers\Utility;

use API\Lib\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Constants extends Controller
{    
    public function __invoke(Request $request, Response $response, $args)
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

        $response->withJson($a_js_constants);
    }
}