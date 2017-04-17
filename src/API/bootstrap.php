<?php
// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && filter_input(INPUT_SERVER, 'SCRIPT_FILENAME') !== __FILE__) {
    return false;
}

// Load up composers libs
require __DIR__ . '/../vendor/autoload.php';

// Load Container, constants and functions
require 'Lib/Container.php';
require 'constants.php';
require 'functions.php';

// Load settings and create dynamic constants from it
$settings = include 'settings.php';
define("API\DEBUG", $settings['settings']['debug']);

// Enable Session and set script running time to infinite in Debug mode
session_start();
if (API\DEBUG) {
    set_time_limit(0);
}

// Create container
$container = new API\Lib\Container($settings);

// Instantiate the app
$app = new \Slim\App($container);

// Set up dependencies
require 'serviceLocator.php';

// Register routes
$routeDirectory = new RecursiveDirectoryIterator(
    __DIR__ . "/Routes/",
    FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
);

$routeIterator = new RecursiveIteratorIterator($routeDirectory);

foreach ($routeIterator as $filename => $file) {
    if ($file->isFile()) {
        include $filename;
    }
}

// Load Plugins
$pluginDirectory = new RecursiveDirectoryIterator(
    __DIR__ . "/Plugins/",
    FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
);

$pluginIterator = new RecursiveIteratorIterator($pluginDirectory);
$pluginIterator->setMaxDepth(2);

foreach ($pluginIterator as $filename => $file) {
    if ($file->isFile() && $file->getFilename() == 'Plugin.php') {
        include $filename;
    }
}

// Run!
$app->run();
