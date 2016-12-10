<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use const API\PUBLIC_ROOT;
// DIC configuration

$o_container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$o_container['logger'] = function ($o_container) {
    $settings = $o_container->get('settings');
    $logger = new Logger($settings['logger']['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['logger']['path'], Logger::DEBUG));
    return $logger;
};

// i18n multi language support
$o_container['i18n'] = function($o_container) {
    $str_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    
    $str_file = PUBLIC_ROOT . 'js/i18n/' . $str_lang . '.json';
    
    if(!file_exists($str_file)) 
        $str_file = PUBLIC_ROOT . 'js/i18n/en.json';    
    
    $str_json = file_get_contents($str_file);
    return json_decode($str_json);
};

// Propel
$o_container['db'] = function($o_container)
{
    $a_settings = $o_container->get('settings');
    $a_db = $a_settings['propel']['database']['connections']['default'];
    
    registerPropelConnection($a_db);
    
    return true;
};