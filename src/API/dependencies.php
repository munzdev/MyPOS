<?php
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
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Propel
$o_container['db'] = function($o_container)
{
    $a_settings = $o_container->get('settings');
    $a_db = $a_settings['propel']['database']['connections']['default'];
    
    registerPropelConnection($a_db);
    
    return true;
};