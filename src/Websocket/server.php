<?php

use Websocket\Routes\API;
use Websocket\Routes\Chat;
use \Ratchet\App;

define('PROJECT_ROOT', __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define('API_ROOT', PROJECT_ROOT . 'API' . DIRECTORY_SEPARATOR);

require PROJECT_ROOT . 'vendor/autoload.php';
require API_ROOT . 'constants.php';
require API_ROOT . 'functions.php';

$settings = include API_ROOT . 'settings.php';
$db = $settings['settings']['database'];

registerPropelConnection($db);

$app = new App($settings['settings']['App']['Domain'], 8080, '0.0.0.0');
$app->route('/Chat', new Chat(), array('*'));
$app->route('/API', new API(), array('*'));

echo "Starting Websocket Server...\n";
$app->run();
