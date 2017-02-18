<?php

use API\Lib\Auth;
use API\Models\User\UserQuery;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use const API\PUBLIC_ROOT;

// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings');
    $logger = new Logger($settings['logger']['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['logger']['path'], Logger::DEBUG));
    return $logger;
};

// i18n multi language support
$container['i18n'] = function () {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    
    $file = PUBLIC_ROOT . 'js/i18n/' . $lang . '.json';
    
    if (!file_exists($file)) {
        $file = PUBLIC_ROOT . 'js/i18n/en.json';
    }
    
    $json = file_get_contents($file);
    return json_decode($json);
};

// Propel
$container['db'] = function ($container) {
    $settings = $container->get('settings');
    $dbConfig = $settings['propel']['database']['connections']['default'];
    
    registerPropelConnection($dbConfig);
    
    return true;
};

$container['Auth'] = function ($container) {
    return new Auth(new UserQuery());
};