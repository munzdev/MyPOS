<?php

abstract class Request
{
	public static function ValidateParams($a_fields)
	{
		$a_vars = $GLOBALS['a_vars'];
		$a_params = array();
		foreach ($a_fields as $str_field => $str_type)
		{
                    $b_optional = false;

                    if(strpos($str_type, 'optional!') === 0)
                    {
                        $str_type = substr($str_type, 9);
                        $b_optional = true;
                    }

                    if(!isset($a_vars[$str_field]) && $b_optional)
                    {
                        continue;
                    }
                    else if(!isset($a_vars[$str_field]))
                    {
                        throw new Exception("Missing Parameter: " . $str_field);
                    }

                    $b_throw_exception = false;
                    switch($str_type)
                    {
                        case 'string':
                                if(!is_string($a_vars[$str_field]))
                                        $b_throw_exception = true;
                                break;

                        case 'numeric':
                                if(!is_numeric($a_vars[$str_field]))
                                        $b_throw_exception = true;
                                break;

                        case 'bool':
                                if(strcasecmp($a_vars[$str_field], "false") != 0 &&
                                   strcasecmp($a_vars[$str_field], "true") != 0 &&
                                   !is_bool($a_vars[$str_field]))
                                        $b_throw_exception = true;
                                break;

                        case 'float':
                                if(!is_float($a_vars[$str_field]))
                                        $b_throw_exception = true;
                                break;

                        case 'double':
                                if(!is_double($a_vars[$str_field]))
                                        $b_throw_exception = true;
                                break;

                        case 'array':
                                if(!is_array($a_vars[$str_field]))
                                        $b_throw_exception = true;
                                break;

                        case 'json':
                            json_decode($a_vars[$str_field]);
                            if(json_last_error() !== JSON_ERROR_NONE)
                                $b_throw_exception = true;
                            break;
                    }

                    if($b_throw_exception)
                        throw new Exception("Invalid Value for Parameter: " . $str_field);
                    else
                        $a_params[$str_field] = $a_vars[$str_field];
		}

		return $a_params;
	}
}