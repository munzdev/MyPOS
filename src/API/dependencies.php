<?php

use API\Lib\Auth;
use API\Lib\Helpers\JsonToModel;
use API\Lib\Helpers\Validate;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IPrintingInformation;
use API\Lib\Interfaces\IRememberMe;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Lib\Interfaces\Printer\PrinterConnector\IPrinterConnector;
use API\Lib\Interfaces\Printer\PrintingType\IPrintingType;
use API\Lib\Printer\PrinterConnector\PdfPrinter;
use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Invoice;
use API\Lib\Printer\PrintingType\Order;
use API\Lib\Printer\PrintingType\PaymentRecieved;
use API\Lib\PrintingInformation;
use API\Lib\RememberMe;
use API\Models\User\UserQuery;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Connection\ConnectionWrapper;
use Propel\Runtime\Connection\DebugPDO;
use Propel\Runtime\Propel;
use const API\DEBUG;
use const API\PUBLIC_ROOT;

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Logger($settings['logger']['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['logger']['path'], Logger::DEBUG));
    return $logger;
};

// i18n multi language support
$container['i18n'] = function () {
    $lang = substr(filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE'), 0, 2);

    $file = PUBLIC_ROOT . 'js/i18n/' . $lang . '.json';

    if (!file_exists($file)) {
        $file = PUBLIC_ROOT . 'js/i18n/en.json';
    }

    $json = file_get_contents($file);
    return json_decode($json);
};

// Propel
$container['db'] = function ($c) {
    $settings = $c->get('settings');
    $dbConfig = $settings['database'];

    $extraConfig = array('classname' => (DEBUG) ? DebugPDO::class : ConnectionWrapper::class,
                         'model_paths' => array('src','vendor'));

    $manager = new ConnectionManagerSingle();
    $manager->setConfiguration(array_merge($dbConfig, $extraConfig));
    $manager->setName('default');

    $serviceContainer = Propel::getServiceContainer();
    $serviceContainer->checkVersion('2.0.0-dev');
    $serviceContainer->setAdapterClass('default', $dbConfig['adapter']);
    $serviceContainer->setConnectionManager('default', $manager);
    $serviceContainer->setDefaultDatasource('default');

    return $serviceContainer->getConnection();
};

$container[IAuth::class] = function ($c) : IAuth {
    return new Auth($c->get(IUserQuery::class));
};

$container[IRememberMe::class] = function ($c) : IRememberMe {
    $settings = $c->get('settings');
    return new RememberMe($settings['Auth']['RememberMe_PrivateKey']);
};

$container[IUserQuery::class] = $container->factory(function () : IUserQuery {
    return new UserQuery();
});

$container[Order::class] = $container->factory(function () : IPrintingType {
    return new Order();
});

$container[Invoice::class] = $container->factory(function () : IPrintingType {
    return new Invoice();
});

$container[PaymentRecieved::class] = $container->factory(function () : IPrintingType {
    return new PaymentRecieved();
});

$container[IPrintingInformation::class] = $container->factory(function () : IPrintingInformation {
    return new PrintingInformation();
});

$container[ThermalPrinter::class] = $container->factory(function () : IPrinterConnector {
    return new ThermalPrinter();
});

$container[PdfPrinter::class] = $container->factory(function () : IPrinterConnector {
    return new PdfPrinter();
});

$container[IJsonToModel::class] = function () : IJsonToModel {
    return new JsonToModel();
};

$container[IValidate::class] = function () : IValidate {
    return new Validate();
};
