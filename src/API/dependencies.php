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
    $settings = $o_container->get('settings');
    $db = $settings['propel']['database']['connections']['default'];
    
    $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
    $serviceContainer->checkVersion('2.0.0-dev');
    $serviceContainer->setAdapterClass('default', $db['adapter']);
    $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
    $manager->setConfiguration(array (
      'dsn' => $db['dsn'],
      'user' => $db['user'],
      'password' => $db['password'],
      'settings' => $db['settings'],
      'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
      'model_paths' =>
      array (
        0 => 'src',
        1 => 'vendor',
      ),
    ));
    $manager->setName('default');
    $serviceContainer->setConnectionManager('default', $manager);
    $serviceContainer->setDefaultDatasource('default');
    
    return true;
};