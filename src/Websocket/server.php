<?php

use Ratchet\App;
use Websocket\Routes\API;
use Websocket\Routes\Chat;

define('PROJECT_ROOT', __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR);
define('API_ROOT', PROJECT_ROOT . 'API' . DIRECTORY_SEPARATOR);

// Load up composers libs
require PROJECT_ROOT . 'vendor/autoload.php';

// Load constants and functions
require API_ROOT . 'constants.php';
require API_ROOT . 'functions.php';

// Load settings and create dynamic constants from it
$settings = include API_ROOT . 'settings.php';
define("API\DEBUG", $settings['settings']['debug']);

// Init Slim3 DI Container
$container = new \API\Lib\Container($settings);

// Set up dependencies
require API_ROOT . 'serviceLocator.php';

// Instantiate the server
$app = new App($settings['settings']['App']['Domain'], 8080, '0.0.0.0');
$app->route('/Chat', new Chat($container), array('*'));
$app->route('/API', new API($container), array('*'));

echo "Starting Websocket Server...\n";
$app->run();
