<?php

use API\Lib\Auth;
use API\Lib\Helpers\JsonToModel;
use API\Lib\Helpers\Validate;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\IPrintingInformation;
use API\Lib\Interfaces\IRememberMe;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlace;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroup;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroupQuery;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceQuery;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTable;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTableCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTableQuery;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUser;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUserCollection;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUserQuery;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventBankinformation;
use API\Lib\Interfaces\Models\Event\IEventBankinformationCollection;
use API\Lib\Interfaces\Models\Event\IEventBankinformationQuery;
use API\Lib\Interfaces\Models\Event\IEventCollection;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Event\IEventContactCollection;
use API\Lib\Interfaces\Models\Event\IEventContactQuery;
use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Lib\Interfaces\Models\Event\IEventPrinterCollection;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Lib\Interfaces\Models\Event\IEventQuery;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\Event\IEventTableCollection;
use API\Lib\Interfaces\Models\Event\IEventTableQuery;
use API\Lib\Interfaces\Models\Event\IEventUser;
use API\Lib\Interfaces\Models\Event\IEventUserCollection;
use API\Lib\Interfaces\Models\Event\IEventUserQuery;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemQuery;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Lib\Interfaces\Models\Invoice\IInvoiceType;
use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeQuery;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarning;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningQuery;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningType;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningTypeCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningTypeQuery;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IAvailabilityCollection;
use API\Lib\Interfaces\Models\Menu\IAvailabilityQuery;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuCollection;
use API\Lib\Interfaces\Models\Menu\IMenuExtra;
use API\Lib\Interfaces\Models\Menu\IMenuExtraCollection;
use API\Lib\Interfaces\Models\Menu\IMenuExtraQuery;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuGroupCollection;
use API\Lib\Interfaces\Models\Menu\IMenuGroupQuery;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtra;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtraCollection;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleExtraQuery;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSize;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSizeCollection;
use API\Lib\Interfaces\Models\Menu\IMenuPossibleSizeQuery;
use API\Lib\Interfaces\Models\Menu\IMenuQuery;
use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Lib\Interfaces\Models\Menu\IMenuSizeCollection;
use API\Lib\Interfaces\Models\Menu\IMenuSizeQuery;
use API\Lib\Interfaces\Models\Menu\IMenuType;
use API\Lib\Interfaces\Models\Menu\IMenuTypeCollection;
use API\Lib\Interfaces\Models\Menu\IMenuTypeQuery;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutCollection;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutQuery;
use API\Lib\Interfaces\Models\OIP\IOrderInProgress;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressCollection;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressQuery;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecieved;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecievedCollection;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecievedQuery;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtra;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtraCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtraQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWith;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWithCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWithQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\Payment\ICouponCollection;
use API\Lib\Interfaces\Models\Payment\ICouponQuery;
use API\Lib\Interfaces\Models\Payment\IPaymentCoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentCouponCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentCouponQuery;
use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentRecievedQuery;
use API\Lib\Interfaces\Models\Payment\IPaymentType;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeCollection;
use API\Lib\Interfaces\Models\Payment\IPaymentTypeQuery;
use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\IUserCollection;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Lib\Interfaces\Models\User\IUserRole;
use API\Lib\Interfaces\Models\User\IUserRoleCollection;
use API\Lib\Interfaces\Models\User\IUserRoleQuery;
use API\Lib\Interfaces\Models\User\Message\IUserMessage;
use API\Lib\Interfaces\Models\User\Message\IUserMessageCollection;
use API\Lib\Interfaces\Models\User\Message\IUserMessageQuery;
use API\Lib\Interfaces\Printer\PrinterConnector\IPrinterConnector;
use API\Lib\Interfaces\Printer\PrintingType\IPrintingType;
use API\Lib\Printer\PrinterConnector\PdfPrinter;
use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Invoice;
use API\Lib\Printer\PrintingType\Order;
use API\Lib\Printer\PrintingType\PaymentRecieved;
use API\Lib\PrintingInformation;
use API\Lib\RememberMe;
use API\Models\DistributionPlace\DistributionPlace;
use API\Models\DistributionPlace\DistributionPlaceCollection;
use API\Models\DistributionPlace\DistributionPlaceGroup;
use API\Models\DistributionPlace\DistributionPlaceGroupCollection;
use API\Models\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\DistributionPlace\DistributionPlaceQuery;
use API\Models\DistributionPlace\DistributionPlaceTable;
use API\Models\DistributionPlace\DistributionPlaceTableCollection;
use API\Models\DistributionPlace\DistributionPlaceTableQuery;
use API\Models\DistributionPlace\DistributionPlaceUser;
use API\Models\DistributionPlace\DistributionPlaceUserCollection;
use API\Models\DistributionPlace\DistributionPlaceUserQuery;
use API\Models\Event\Event;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventBankinformationCollection;
use API\Models\Event\EventBankinformationQuery;
use API\Models\Event\EventCollection;
use API\Models\Event\EventContact;
use API\Models\Event\EventContactCollection;
use API\Models\Event\EventContactQuery;
use API\Models\Event\EventPrinter;
use API\Models\Event\EventPrinterCollection;
use API\Models\Event\EventPrinterQuery;
use API\Models\Event\EventQuery;
use API\Models\Event\EventTable;
use API\Models\Event\EventTableCollection;
use API\Models\Event\EventTableQuery;
use API\Models\Event\EventUser;
use API\Models\Event\EventUserCollection;
use API\Models\Event\EventUserQuery;
use API\Models\Invoice\Invoice as Invoice2;
use API\Models\Invoice\InvoiceCollection;
use API\Models\Invoice\InvoiceItem;
use API\Models\Invoice\InvoiceItemCollection;
use API\Models\Invoice\InvoiceItemQuery;
use API\Models\Invoice\InvoiceQuery;
use API\Models\Invoice\InvoiceType;
use API\Models\Invoice\InvoiceTypeCollection;
use API\Models\Invoice\InvoiceTypeQuery;
use API\Models\Invoice\InvoiceWarning;
use API\Models\Invoice\InvoiceWarningCollection;
use API\Models\Invoice\InvoiceWarningQuery;
use API\Models\Invoice\InvoiceWarningType;
use API\Models\Invoice\InvoiceWarningTypeCollection;
use API\Models\Invoice\InvoiceWarningTypeQuery;
use API\Models\Menu\Availability;
use API\Models\Menu\AvailabilityCollection;
use API\Models\Menu\AvailabilityQuery;
use API\Models\Menu\Menu;
use API\Models\Menu\MenuCollection;
use API\Models\Menu\MenuExtra;
use API\Models\Menu\MenuExtraCollection;
use API\Models\Menu\MenuExtraQuery;
use API\Models\Menu\MenuGroup;
use API\Models\Menu\MenuGroupCollection;
use API\Models\Menu\MenuGroupQuery;
use API\Models\Menu\MenuPossibleExtra;
use API\Models\Menu\MenuPossibleExtraCollection;
use API\Models\Menu\MenuPossibleExtraQuery;
use API\Models\Menu\MenuPossibleSize;
use API\Models\Menu\MenuPossibleSizeCollection;
use API\Models\Menu\MenuPossibleSizeQuery;
use API\Models\Menu\MenuQuery;
use API\Models\Menu\MenuSize;
use API\Models\Menu\MenuSizeCollection;
use API\Models\Menu\MenuSizeQuery;
use API\Models\Menu\MenuType;
use API\Models\Menu\MenuTypeCollection;
use API\Models\Menu\MenuTypeQuery;
use API\Models\OIP\DistributionGivingOut;
use API\Models\OIP\DistributionGivingOutCollection;
use API\Models\OIP\DistributionGivingOutQuery;
use API\Models\OIP\OrderInProgress;
use API\Models\OIP\OrderInProgressCollection;
use API\Models\OIP\OrderInProgressQuery;
use API\Models\OIP\OrderInProgressRecieved;
use API\Models\OIP\OrderInProgressRecievedCollection;
use API\Models\OIP\OrderInProgressRecievedQuery;
use API\Models\Ordering\Order as Order2;
use API\Models\Ordering\OrderCollection;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailCollection;
use API\Models\Ordering\OrderDetailExtra;
use API\Models\Ordering\OrderDetailExtraCollection;
use API\Models\Ordering\OrderDetailExtraQuery;
use API\Models\Ordering\OrderDetailMixedWith;
use API\Models\Ordering\OrderDetailMixedWithCollection;
use API\Models\Ordering\OrderDetailMixedWithQuery;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\OrderQuery;
use API\Models\Payment\Coupon;
use API\Models\Payment\CouponCollection;
use API\Models\Payment\CouponQuery;
use API\Models\Payment\PaymentCoupon;
use API\Models\Payment\PaymentCouponCollection;
use API\Models\Payment\PaymentCouponQuery;
use API\Models\Payment\PaymentRecieved as PaymentRecieved2;
use API\Models\Payment\PaymentRecievedCollection;
use API\Models\Payment\PaymentRecievedQuery;
use API\Models\Payment\PaymentType;
use API\Models\Payment\PaymentTypeCollection;
use API\Models\Payment\PaymentTypeQuery;
use API\Models\User\Message\UserMessage;
use API\Models\User\Message\UserMessageCollection;
use API\Models\User\Message\UserMessageQuery;
use API\Models\User\User;
use API\Models\User\UserCollection;
use API\Models\User\UserQuery;
use API\Models\User\UserRole;
use API\Models\User\UserRoleCollection;
use API\Models\User\UserRoleQuery;
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

$container->registerService(IAuth::class, function ($c) {
    return new Auth($c->get(IUserQuery::class));
});

$container->registerService(IAuth::class, function ($c) {
    return new Auth($c->get(IUserQuery::class));
});

$container->registerService(IRememberMe::class, function ($c) {
    $settings = $c->get('settings');
    return new RememberMe($settings['Auth']['RememberMe_PrivateKey']);
});

$container->registerService(IUserQuery::class, $container->factory(function () {
    return new UserQuery();
}));

$container->registerService(Order::class, $container->factory(function ($c) {
    return new Order($c);
}), IPrintingType::class);

$container->registerService(Invoice::class, $container->factory(function ($c) {
    return new Invoice($c);
}), IPrintingType::class);

$container->registerService(PaymentRecieved::class, $container->factory(function ($c) {
    return new PaymentRecieved($c);
}), IPrintingType::class);

$container->registerService(IPrintingInformation::class, $container->factory(function () {
    return new PrintingInformation();
}));

$container->registerService(ThermalPrinter::class, $container->factory(function () {
    return new ThermalPrinter();
}), IPrinterConnector::class);

$container->registerService(PdfPrinter::class, $container->factory(function () {
    return new PdfPrinter();
}), IPrinterConnector::class);

$container->registerService(IJsonToModel::class, function () {
    return new JsonToModel();
});

$container->registerService(IValidate::class, function () {
    return new Validate();
});

$container->registerService(IDistributionPlace::class, $container->factory(function ($c) {
    return new DistributionPlace($c);
}));

$container->registerService(IDistributionPlaceCollection::class, $container->factory(function () {
    return new DistributionPlaceCollection();
}));

$container->registerService(IDistributionPlaceGroup::class, $container->factory(function ($c) {
    return new DistributionPlaceGroup($c);
}));

$container->registerService(IDistributionPlaceGroupCollection::class, $container->factory(function () {
    return new DistributionPlaceGroupCollection();
}));

$container->registerService(IDistributionPlaceGroupQuery::class, $container->factory(function () {
    return new DistributionPlaceGroupQuery();
}));

$container->registerService(IDistributionPlaceQuery::class, $container->factory(function () {
    return new DistributionPlaceQuery();
}));

$container->registerService(IDistributionPlaceTable::class, $container->factory(function ($c) {
    return new DistributionPlaceTable($c);
}));

$container->registerService(IDistributionPlaceTableCollection::class, $container->factory(function () {
    return new DistributionPlaceTableCollection();
}));

$container->registerService(IDistributionPlaceTableQuery::class, $container->factory(function () {
    return new DistributionPlaceTableQuery();
}));

$container->registerService(IDistributionPlaceUser::class, $container->factory(function ($c) {
    return new DistributionPlaceUser($c);
}));

$container->registerService(IDistributionPlaceUserCollection::class, $container->factory(function () {
    return new DistributionPlaceUserCollection();
}));

$container->registerService(IDistributionPlaceUserQuery::class, $container->factory(function () {
    return new DistributionPlaceUserQuery();
}));

$container->registerService(IEvent::class, $container->factory(function ($c) {
    return new Event($c);
}));

$container->registerService(IEventBankinformation::class, $container->factory(function ($c) {
    return new EventBankinformation($c);
}));

$container->registerService(IEventBankinformationCollection::class, $container->factory(function () {
    return new EventBankinformationCollection();
}));

$container->registerService(IEventBankinformationQuery::class, $container->factory(function () {
    return new EventBankinformationQuery();
}));

$container->registerService(IEventCollection::class, $container->factory(function () {
    return new EventCollection();
}));

$container->registerService(IEventContact::class, $container->factory(function ($c) {
    return new EventContact($c);
}));

$container->registerService(IEventContactCollection::class, $container->factory(function () {
    return new EventContactCollection();
}));

$container->registerService(IEventContactQuery::class, $container->factory(function () {
    return new EventContactQuery();
}));

$container->registerService(IEventPrinter::class, $container->factory(function ($c) {
    return new EventPrinter($c);
}));

$container->registerService(IEventPrinterCollection::class, $container->factory(function () {
    return new EventPrinterCollection();
}));

$container->registerService(IEventPrinterQuery::class, $container->factory(function () {
    return new EventPrinterQuery();
}));

$container->registerService(IEventQuery::class, $container->factory(function () {
    return new EventQuery();
}));

$container->registerService(IEventTable::class, $container->factory(function ($c) {
    return new EventTable($c);
}));

$container->registerService(IEventTableCollection::class, $container->factory(function () {
    return new EventTableCollection();
}));

$container->registerService(IEventTableQuery::class, $container->factory(function () {
    return new EventTableQuery();
}));

$container->registerService(IEventUser::class, $container->factory(function ($c) {
    return new EventUser($c);
}));

$container->registerService(IEventUserCollection::class, $container->factory(function () {
    return new EventUserCollection();
}));

$container->registerService(IEventUserQuery::class, $container->factory(function () {
    return new EventUserQuery();
}));

$container->registerService(IInvoice::class, $container->factory(function ($c) {
    return new Invoice2($c);
}));

$container->registerService(IInvoiceCollection::class, $container->factory(function () {
    return new InvoiceCollection();
}));

$container->registerService(IInvoiceItem::class, $container->factory(function ($c) {
    return new InvoiceItem($c);
}));

$container->registerService(IInvoiceItemCollection::class, $container->factory(function () {
    return new InvoiceItemCollection();
}));

$container->registerService(IInvoiceItemQuery::class, $container->factory(function () {
    return new InvoiceItemQuery();
}));

$container->registerService(IInvoiceQuery::class, $container->factory(function () {
    return new InvoiceQuery();
}));

$container->registerService(IInvoiceType::class, $container->factory(function ($c) {
    return new InvoiceType($c);
}));

$container->registerService(IInvoiceTypeCollection::class, $container->factory(function () {
    return new InvoiceTypeCollection();
}));

$container->registerService(IInvoiceTypeQuery::class, $container->factory(function () {
    return new InvoiceTypeQuery();
}));

$container->registerService(IInvoiceWarning::class, $container->factory(function ($c) {
    return new InvoiceWarning($c);
}));

$container->registerService(IInvoiceWarningCollection::class, $container->factory(function () {
    return new InvoiceWarningCollection();
}));

$container->registerService(IInvoiceWarningQuery::class, $container->factory(function () {
    return new InvoiceWarningQuery();
}));

$container->registerService(IInvoiceWarningType::class, $container->factory(function ($c) {
    return new InvoiceWarningType($c);
}));

$container->registerService(IInvoiceWarningTypeCollection::class, $container->factory(function () {
    return new InvoiceWarningTypeCollection();
}));

$container->registerService(IInvoiceWarningTypeQuery::class, $container->factory(function () {
    return new InvoiceWarningTypeQuery();
}));

$container->registerService(IAvailability::class, $container->factory(function ($c) {
    return new Availability($c);
}));

$container->registerService(IAvailabilityCollection::class, $container->factory(function () {
    return new AvailabilityCollection();
}));

$container->registerService(IAvailabilityQuery::class, $container->factory(function () {
    return new AvailabilityQuery();
}));

$container->registerService(IMenu::class, $container->factory(function ($c) {
    return new Menu($c);
}));

$container->registerService(IMenuCollection::class, $container->factory(function () {
    return new MenuCollection();
}));

$container->registerService(IMenuExtra::class, $container->factory(function ($c) {
    return new MenuExtra($c);
}));

$container->registerService(IMenuExtraCollection::class, $container->factory(function () {
    return new MenuExtraCollection();
}));

$container->registerService(IMenuExtraQuery::class, $container->factory(function () {
    return new MenuExtraQuery();
}));

$container->registerService(IMenuGroup::class, $container->factory(function ($c) {
    return new MenuGroup($c);
}));

$container->registerService(IMenuGroupCollection::class, $container->factory(function () {
    return new MenuGroupCollection();
}));

$container->registerService(IMenuGroupQuery::class, $container->factory(function () {
    return new MenuGroupQuery();
}));

$container->registerService(IMenuPossibleExtra::class, $container->factory(function ($c) {
    return new MenuPossibleExtra($c);
}));

$container->registerService(IMenuPossibleExtraCollection::class, $container->factory(function () {
    return new MenuPossibleExtraCollection();
}));

$container->registerService(IMenuPossibleExtraQuery::class, $container->factory(function () {
    return new MenuPossibleExtraQuery();
}));

$container->registerService(IMenuPossibleSize::class, $container->factory(function ($c) {
    return new MenuPossibleSize($c);
}));

$container->registerService(IMenuPossibleSizeCollection::class, $container->factory(function () {
    return new MenuPossibleSizeCollection();
}));

$container->registerService(IMenuPossibleSizeQuery::class, $container->factory(function () {
    return new MenuPossibleSizeQuery();
}));

$container->registerService(IMenuQuery::class, $container->factory(function () {
    return new MenuQuery();
}));

$container->registerService(IMenuSize::class, $container->factory(function ($c) {
    return new MenuSize($c);
}));

$container->registerService(IMenuSizeCollection::class, $container->factory(function () {
    return new MenuSizeCollection();
}));

$container->registerService(IMenuSizeQuery::class, $container->factory(function () {
    return new MenuSizeQuery();
}));

$container->registerService(IMenuType::class, $container->factory(function ($c) {
    return new MenuType($c);
}));

$container->registerService(IMenuTypeCollection::class, $container->factory(function () {
    return new MenuTypeCollection();
}));

$container->registerService(IMenuTypeQuery::class, $container->factory(function () {
    return new MenuTypeQuery();
}));

$container->registerService(IDistributionGivingOut::class, $container->factory(function ($c) {
    return new DistributionGivingOut($c);
}));

$container->registerService(IDistributionGivingOutCollection::class, $container->factory(function () {
    return new DistributionGivingOutCollection();
}));

$container->registerService(IDistributionGivingOutQuery::class, $container->factory(function () {
    return new DistributionGivingOutQuery();
}));

$container->registerService(IOrderInProgress::class, $container->factory(function ($c) {
    return new OrderInProgress($c);
}));

$container->registerService(IOrderInProgressCollection::class, $container->factory(function () {
    return new OrderInProgressCollection();
}));

$container->registerService(IOrderInProgressQuery::class, $container->factory(function () {
    return new OrderInProgressQuery();
}));

$container->registerService(IOrderInProgressRecieved::class, $container->factory(function ($c) {
    return new OrderInProgressRecieved($c);
}));

$container->registerService(IOrderInProgressRecievedCollection::class, $container->factory(function () {
    return new OrderInProgressRecievedCollection();
}));

$container->registerService(IOrderInProgressRecievedQuery::class, $container->factory(function () {
    return new OrderInProgressRecievedQuery();
}));

$container->registerService(IOrder::class, $container->factory(function ($c) {
    return new Order2($c);
}));

$container->registerService(IOrderCollection::class, $container->factory(function () {
    return new OrderCollection();
}));

$container->registerService(IOrderDetail::class, $container->factory(function ($c) {
    return new OrderDetail($c);
}));

$container->registerService(IOrderDetailCollection::class, $container->factory(function () {
    return new OrderDetailCollection();
}));

$container->registerService(IOrderDetailExtra::class, $container->factory(function ($c) {
    return new OrderDetailExtra($c);
}));

$container->registerService(IOrderDetailExtraCollection::class, $container->factory(function () {
    return new OrderDetailExtraCollection();
}));

$container->registerService(IOrderDetailExtraQuery::class, $container->factory(function () {
    return new OrderDetailExtraQuery();
}));

$container->registerService(IOrderDetailMixedWith::class, $container->factory(function ($c) {
    return new OrderDetailMixedWith($c);
}));

$container->registerService(IOrderDetailMixedWithCollection::class, $container->factory(function () {
    return new OrderDetailMixedWithCollection();
}));

$container->registerService(IOrderDetailMixedWithQuery::class, $container->factory(function () {
    return new OrderDetailMixedWithQuery();
}));

$container->registerService(IOrderDetailQuery::class, $container->factory(function () {
    return new OrderDetailQuery();
}));

$container->registerService(IOrderQuery::class, $container->factory(function () {
    return new OrderQuery();
}));

$container->registerService(ICoupon::class, $container->factory(function ($c) {
    return new Coupon($c);
}));

$container->registerService(ICouponCollection::class, $container->factory(function () {
    return new CouponCollection();
}));

$container->registerService(ICouponQuery::class, $container->factory(function () {
    return new CouponQuery();
}));

$container->registerService(IPaymentCoupon::class, $container->factory(function ($c) {
    return new PaymentCoupon($c);
}));

$container->registerService(IPaymentCouponCollection::class, $container->factory(function () {
    return new PaymentCouponCollection();
}));

$container->registerService(IPaymentCouponQuery::class, $container->factory(function () {
    return new PaymentCouponQuery();
}));

$container->registerService(IPaymentRecieved::class, $container->factory(function ($c) {
    return new PaymentRecieved2($c);
}));

$container->registerService(IPaymentRecievedCollection::class, $container->factory(function () {
    return new PaymentRecievedCollection();
}));

$container->registerService(IPaymentRecievedQuery::class, $container->factory(function () {
    return new PaymentRecievedQuery();
}));

$container->registerService(IPaymentType::class, $container->factory(function ($c) {
    return new PaymentType($c);
}));

$container->registerService(IPaymentTypeCollection::class, $container->factory(function () {
    return new PaymentTypeCollection();
}));

$container->registerService(IPaymentTypeQuery::class, $container->factory(function () {
    return new PaymentTypeQuery();
}));

$container->registerService(IUserMessage::class, $container->factory(function ($c) {
    return new UserMessage($c);
}));

$container->registerService(IUserMessageCollection::class, $container->factory(function () {
    return new UserMessageCollection();
}));

$container->registerService(IUserMessageQuery::class, $container->factory(function () {
    return new UserMessageQuery();
}));

$container->registerService(IUser::class, $container->factory(function ($c) {
    return new User($c);
}));

$container->registerService(IUserCollection::class, $container->factory(function () {
    return new UserCollection();
}));

$container->registerService(IUserQuery::class, $container->factory(function () {
    return new UserQuery();
}));

$container->registerService(IUserRole::class, $container->factory(function ($c) {
    return new UserRole($c);
}));

$container->registerService(IUserRoleCollection::class, $container->factory(function () {
    return new UserRoleCollection();
}));

$container->registerService(IUserRoleQuery::class, $container->factory(function () {
    return new UserRoleQuery();
}));
