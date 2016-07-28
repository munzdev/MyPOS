<?php

$a_vars = $_REQUEST;

$a_return = array();
$a_return['error'] = false;
$a_return['errorMessage'] = null;

define('PROJECT_ROOT', __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define('API_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('WWW_ROOT', PROJECT_ROOT . "public" . DIRECTORY_SEPARATOR);

set_error_handler("errorHandler");
spl_autoload_register('mypos_autoloader');

try
{
    require 'constants.php';
    require 'functions.php';
    require PROJECT_ROOT . "config.php";
    require PROJECT_ROOT . "vendor/autoload.php";

    session_start();

    if (!key_exists('controller', $a_vars) || !key_exists('action', $a_vars))
    {
        throw new Exception("Invalid Request!");
    }

    if(!Lib\Login::IsLoggedIn())
    {
        $o_users = new Model\Users(Lib\Database::GetConnection());

        $o_rememberMe = new Lib\RememberMe($o_users, $a_config['Auth']['RememberMe_PrivateKey']);

        $a_user = $o_rememberMe->auth();

        // Check if remember me is present
        if ($a_user)
        {
            $o_login = new Lib\Login($o_users);
            $o_login->DoLogin($a_user['username']);
        }
    }

    $str_controller = $a_vars['controller'];
    $str_action = $a_vars['action'];

    $str_controller_file = API_ROOT . "controller/$str_controller.php";

    if(!file_exists($str_controller_file))
        throw new Exception("Invalid Controller");

    require_once $str_controller_file;

    $str_class_name = "\\Controller\\$str_controller";

    $o_controller = new $str_class_name;

    $str_action_method = $str_action . "Action";

    if(!method_exists($o_controller, $str_action_method))
        throw new Exception("Invalid Action");

    if(is_subclass_of($o_controller, 'Lib\SecurityController'))
        $o_controller->CheckAccess($str_action);

    $a_return['result'] = $o_controller->$str_action_method();

    if($o_controller->GetRawData())
    {
        echo $a_return['result'];
        exit;
    }
}
catch(Exception $o_exception)
{
    $a_return['error'] = true;
    $a_return['errorMessage'] = $o_exception->getMessage();
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($a_return);

function mypos_autoloader($str_class_name)
{
    $str_filename = API_ROOT . strtolower(str_replace('\\','/',$str_class_name)).'.php';

    require_once $str_filename;
}

function errorHandler($errno, $errstr, $errfile, $errline)
{
    throw new Exception("$errno: $errstr in file $errfile:$errline");
}