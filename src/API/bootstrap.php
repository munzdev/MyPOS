<?php
// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && filter_input(INPUT_SERVER, 'SCRIPT_FILENAME') !== __FILE__) {
    return false;
}

// Load up composers libs
require __DIR__ . '/../vendor/autoload.php';

// Load constants and functions
require 'constants.php';
require 'functions.php';

// Enable Session and set script running time to infinite in Debug mode
session_start();
if (API\DEBUG) {
    set_time_limit(0);
}

// Instantiate the app
$settings = include 'settings.php';
$app = new \Slim\App($settings);
$container = $app->getContainer();

// Set up dependencies
require 'dependencies.php';

// Register routes
loadFilesInDirecotry(__DIR__ . "/Routes/", $app);

// Load Plugins
loadFilesInDirecotry(__DIR__ . "/Plugins/", $app);

// Run!
$app->run();
