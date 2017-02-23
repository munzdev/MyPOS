<?php

use Ratchet\App;
use Slim\Container;
use Websocket\Routes\API;
use Websocket\Routes\Chat;

define('PROJECT_ROOT', __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define('API_ROOT', PROJECT_ROOT . 'API' . DIRECTORY_SEPARATOR);

require PROJECT_ROOT . 'vendor/autoload.php';
require API_ROOT . 'constants.php';
require API_ROOT . 'functions.php';

$settings = include API_ROOT . 'settings.php';
$container = new Container($settings);

require API_ROOT . 'dependencies.php';

$app = new App($settings['settings']['App']['Domain'], 8080, '0.0.0.0');
$app->route('/Chat', new Chat($container), array('*'));
$app->route('/API', new API($container), array('*'));

echo "Starting Websocket Server...\n";
$app->run();
