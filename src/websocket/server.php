<?php


use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8');

define('DEBUG', true);
define('PROJECT_ROOT', __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define('API_ROOT', PROJECT_ROOT . 'api' . DIRECTORY_SEPARATOR);
define('WWW_ROOT', PROJECT_ROOT . "public" . DIRECTORY_SEPARATOR);

spl_autoload_register('mypos_server_autoloader');

require dirname(__DIR__) . '/vendor/autoload.php';
require API_ROOT . 'constants.php';
require PROJECT_ROOT . 'config.php';
require 'routes\Chat.php';
require 'routes\API.php';

$o_db = Lib\Database::GetConnection();

$o_users_messages = new Model\Users_Messages($o_db);

$app = new Ratchet\App($a_config['App']['Domain'], 8080, '0.0.0.0');
$app->route('/chat', new Websocket\Chat($o_users_messages), array('*'));
$app->route('/api', new Websocket\API(), array('*'));

$app->run();

function mypos_server_autoloader($str_class_name)
{
    $str_filename = API_ROOT . strtolower(str_replace('\\','/',$str_class_name)).'.php';

    require_once $str_filename;
}