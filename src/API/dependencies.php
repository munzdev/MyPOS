<?php

use API\Lib\Auth;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Models\User\UserQuery;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Propel\Runtime\Propel;
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
    $dbConfig = $settings['database'];
    
    registerPropelConnection($dbConfig);
    
    return Propel::getConnection();
};

$container[IAuth::class] = function ($container) : IAuth {
    return new Auth($container->get(IUserQuery::class));
};

$container[IUserQuery::class] = function ($container) : IUserQuery {
    return new UserQuery();
};