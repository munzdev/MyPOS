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

$container[IDistributionPlace::class] = $container->factory(function () : IDistributionPlace {
    return new DistributionPlace();
});

$container[IDistributionPlaceCollection::class] = $container->factory(function () : IDistributionPlaceCollection {
    return new DistributionPlaceCollection();
});

$container[IDistributionPlaceGroup::class] = $container->factory(function () : IDistributionPlaceGroup {
    return new DistributionPlaceGroup();
});

$container[IDistributionPlaceGroupCollection::class] = $container->factory(function () : IDistributionPlaceGroupCollection {
    return new DistributionPlaceGroupCollection();
});

$container[IDistributionPlaceGroupQuery::class] = $container->factory(function () : IDistributionPlaceGroupQuery {
    return new DistributionPlaceGroupQuery();
});

$container[IDistributionPlaceQuery::class] = $container->factory(function () : IDistributionPlaceQuery {
    return new DistributionPlaceQuery();
});

$container[IDistributionPlaceTable::class] = $container->factory(function () : IDistributionPlaceTable {
    return new DistributionPlaceTable();
});

$container[IDistributionPlaceTableCollection::class] = $container->factory(function () : IDistributionPlaceTableCollection {
    return new DistributionPlaceTableCollection();
});

$container[IDistributionPlaceTableQuery::class] = $container->factory(function () : IDistributionPlaceTableQuery {
    return new DistributionPlaceTableQuery();
});

$container[IDistributionPlaceUser::class] = $container->factory(function () : IDistributionPlaceUser {
    return new DistributionPlaceUser();
});

$container[IDistributionPlaceUserCollection::class] = $container->factory(function () : IDistributionPlaceUserCollection {
    return new DistributionPlaceUserCollection();
});

$container[IDistributionPlaceUserQuery::class] = $container->factory(function () : IDistributionPlaceUserQuery {
    return new DistributionPlaceUserQuery();
});

$container[IEvent::class] = $container->factory(function () : IEvent {
    return new Event();
});

$container[IEventBankinformation::class] = $container->factory(function () : IEventBankinformation {
    return new EventBankinformation();
});

$container[IEventBankinformationCollection::class] = $container->factory(function () : IEventBankinformationCollection {
    return new EventBankinformationCollection();
});

$container[IEventBankinformationQuery::class] = $container->factory(function () : IEventBankinformationQuery {
    return new EventBankinformationQuery();
});

$container[IEventCollection::class] = $container->factory(function () : IEventCollection {
    return new EventCollection();
});

$container[IEventContact::class] = $container->factory(function () : IEventContact {
    return new EventContact();
});

$container[IEventContactCollection::class] = $container->factory(function () : IEventContactCollection {
    return new EventContactCollection();
});

$container[IEventContactQuery::class] = $container->factory(function () : IEventContactQuery {
    return new EventContactQuery();
});

$container[IEventPrinter::class] = $container->factory(function () : IEventPrinter {
    return new EventPrinter();
});

$container[IEventPrinterCollection::class] = $container->factory(function () : IEventPrinterCollection {
    return new EventPrinterCollection();
});

$container[IEventPrinterQuery::class] = $container->factory(function () : IEventPrinterQuery {
    return new EventPrinterQuery();
});

$container[IEventQuery::class] = $container->factory(function () : IEventQuery {
    return new EventQuery();
});

$container[IEventTable::class] = $container->factory(function () : IEventTable {
    return new EventTable();
});

$container[IEventTableCollection::class] = $container->factory(function () : IEventTableCollection {
    return new EventTableCollection();
});

$container[IEventTableQuery::class] = $container->factory(function () : IEventTableQuery {
    return new EventTableQuery();
});

$container[IEventUser::class] = $container->factory(function () : IEventUser {
    return new EventUser();
});

$container[IEventUserCollection::class] = $container->factory(function () : IEventUserCollection {
    return new EventUserCollection();
});

$container[IEventUserQuery::class] = $container->factory(function () : IEventUserQuery {
    return new EventUserQuery();
});

$container[IInvoice::class] = $container->factory(function () : IInvoice {
    return new Invoice2();
});

$container[IInvoiceCollection::class] = $container->factory(function () : IInvoiceCollection {
    return new InvoiceCollection();
});

$container[IInvoiceItem::class] = $container->factory(function () : IInvoiceItem {
    return new InvoiceItem();
});

$container[IInvoiceItemCollection::class] = $container->factory(function () : IInvoiceItemCollection {
    return new InvoiceItemCollection();
});

$container[IInvoiceItemQuery::class] = $container->factory(function () : IInvoiceItemQuery {
    return new InvoiceItemQuery();
});

$container[IInvoiceQuery::class] = $container->factory(function () : IInvoiceQuery {
    return new InvoiceQuery();
});

$container[IInvoiceType::class] = $container->factory(function () : IInvoiceType {
    return new InvoiceType();
});

$container[IInvoiceTypeCollection::class] = $container->factory(function () : IInvoiceTypeCollection {
    return new InvoiceTypeCollection();
});

$container[IInvoiceTypeQuery::class] = $container->factory(function () : IInvoiceTypeQuery {
    return new InvoiceTypeQuery();
});

$container[IInvoiceWarning::class] = $container->factory(function () : IInvoiceWarning {
    return new InvoiceWarning();
});

$container[IInvoiceWarningCollection::class] = $container->factory(function () : IInvoiceWarningCollection {
    return new InvoiceWarningCollection();
});

$container[IInvoiceWarningQuery::class] = $container->factory(function () : IInvoiceWarningQuery {
    return new InvoiceWarningQuery();
});

$container[IInvoiceWarningType::class] = $container->factory(function () : IInvoiceWarningType {
    return new InvoiceWarningType();
});

$container[IInvoiceWarningTypeCollection::class] = $container->factory(function () : IInvoiceWarningTypeCollection {
    return new InvoiceWarningTypeCollection();
});

$container[IInvoiceWarningTypeQuery::class] = $container->factory(function () : IInvoiceWarningTypeQuery {
    return new InvoiceWarningTypeQuery();
});

$container[IAvailability::class] = $container->factory(function () : IAvailability {
    return new Availability();
});

$container[IAvailabilityCollection::class] = $container->factory(function () : IAvailabilityCollection {
    return new AvailabilityCollection();
});

$container[IAvailabilityQuery::class] = $container->factory(function () : IAvailabilityQuery {
    return new AvailabilityQuery();
});

$container[IMenu::class] = $container->factory(function () : IMenu {
    return new Menu();
});

$container[IMenuCollection::class] = $container->factory(function () : IMenuCollection {
    return new MenuCollection();
});

$container[IMenuExtra::class] = $container->factory(function () : IMenuExtra {
    return new MenuExtra();
});

$container[IMenuExtraCollection::class] = $container->factory(function () : IMenuExtraCollection {
    return new MenuExtraCollection();
});

$container[IMenuExtraQuery::class] = $container->factory(function () : IMenuExtraQuery {
    return new MenuExtraQuery();
});

$container[IMenuGroup::class] = $container->factory(function () : IMenuGroup {
    return new MenuGroup();
});

$container[IMenuGroupCollection::class] = $container->factory(function () : IMenuGroupCollection {
    return new MenuGroupCollection();
});

$container[IMenuGroupQuery::class] = $container->factory(function () : IMenuGroupQuery {
    return new MenuGroupQuery();
});

$container[IMenuPossibleExtra::class] = $container->factory(function () : IMenuPossibleExtra {
    return new MenuPossibleExtra();
});

$container[IMenuPossibleExtraCollection::class] = $container->factory(function () : IMenuPossibleExtraCollection {
    return new MenuPossibleExtraCollection();
});

$container[IMenuPossibleExtraQuery::class] = $container->factory(function () : IMenuPossibleExtraQuery {
    return new MenuPossibleExtraQuery();
});

$container[IMenuPossibleSize::class] = $container->factory(function () : IMenuPossibleSize {
    return new MenuPossibleSize();
});

$container[IMenuPossibleSizeCollection::class] = $container->factory(function () : IMenuPossibleSizeCollection {
    return new MenuPossibleSizeCollection();
});

$container[IMenuPossibleSizeQuery::class] = $container->factory(function () : IMenuPossibleSizeQuery {
    return new MenuPossibleSizeQuery();
});

$container[IMenuQuery::class] = $container->factory(function () : IMenuQuery {
    return new MenuQuery();
});

$container[IMenuSize::class] = $container->factory(function () : IMenuSize {
    return new MenuSize();
});

$container[IMenuSizeCollection::class] = $container->factory(function () : IMenuSizeCollection {
    return new MenuSizeCollection();
});

$container[IMenuSizeQuery::class] = $container->factory(function () : IMenuSizeQuery {
    return new MenuSizeQuery();
});

$container[IMenuType::class] = $container->factory(function () : IMenuType {
    return new MenuType();
});

$container[IMenuTypeCollection::class] = $container->factory(function () : IMenuTypeCollection {
    return new MenuTypeCollection();
});

$container[IMenuTypeQuery::class] = $container->factory(function () : IMenuTypeQuery {
    return new MenuTypeQuery();
});

$container[IDistributionGivingOut::class] = $container->factory(function () : IDistributionGivingOut {
    return new DistributionGivingOut();
});

$container[IDistributionGivingOutCollection::class] = $container->factory(function () : IDistributionGivingOutCollection {
    return new DistributionGivingOutCollection();
});

$container[IDistributionGivingOutQuery::class] = $container->factory(function () : IDistributionGivingOutQuery {
    return new DistributionGivingOutQuery();
});

$container[IOrderInProgress::class] = $container->factory(function () : IOrderInProgress {
    return new OrderInProgress();
});

$container[IOrderInProgressCollection::class] = $container->factory(function () : IOrderInProgressCollection {
    return new OrderInProgressCollection();
});

$container[IOrderInProgressQuery::class] = $container->factory(function () : IOrderInProgressQuery {
    return new OrderInProgressQuery();
});

$container[IOrderInProgressRecieved::class] = $container->factory(function () : IOrderInProgressRecieved {
    return new OrderInProgressRecieved();
});

$container[IOrderInProgressRecievedCollection::class] = $container->factory(function () : IOrderInProgressRecievedCollection {
    return new OrderInProgressRecievedCollection();
});

$container[IOrderInProgressRecievedQuery::class] = $container->factory(function () : IOrderInProgressRecievedQuery {
    return new OrderInProgressRecievedQuery();
});

$container[IOrder::class] = $container->factory(function () : IOrder {
    return new Order2();
});

$container[IOrderCollection::class] = $container->factory(function () : IOrderCollection {
    return new OrderCollection();
});

$container[IOrderDetail::class] = $container->factory(function () : IOrderDetail {
    return new OrderDetail();
});

$container[IOrderDetailCollection::class] = $container->factory(function () : IOrderDetailCollection {
    return new OrderDetailCollection();
});

$container[IOrderDetailExtra::class] = $container->factory(function () : IOrderDetailExtra {
    return new OrderDetailExtra();
});

$container[IOrderDetailExtraCollection::class] = $container->factory(function () : IOrderDetailExtraCollection {
    return new OrderDetailExtraCollection();
});

$container[IOrderDetailExtraQuery::class] = $container->factory(function () : IOrderDetailExtraQuery {
    return new OrderDetailExtraQuery();
});

$container[IOrderDetailMixedWith::class] = $container->factory(function () : IOrderDetailMixedWith {
    return new OrderDetailMixedWith();
});

$container[IOrderDetailMixedWithCollection::class] = $container->factory(function () : IOrderDetailMixedWithCollection {
    return new OrderDetailMixedWithCollection();
});

$container[IOrderDetailMixedWithQuery::class] = $container->factory(function () : IOrderDetailMixedWithQuery {
    return new OrderDetailMixedWithQuery();
});

$container[IOrderDetailQuery::class] = $container->factory(function () : IOrderDetailQuery {
    return new OrderDetailQuery();
});

$container[IOrderQuery::class] = $container->factory(function () : IOrderQuery {
    return new OrderQuery();
});

$container[ICoupon::class] = $container->factory(function () : ICoupon {
    return new Coupon();
});

$container[ICouponCollection::class] = $container->factory(function () : ICouponCollection {
    return new CouponCollection();
});

$container[ICouponQuery::class] = $container->factory(function () : ICouponQuery {
    return new CouponQuery();
});

$container[IPaymentCoupon::class] = $container->factory(function () : IPaymentCoupon {
    return new PaymentCoupon();
});

$container[IPaymentCouponCollection::class] = $container->factory(function () : IPaymentCouponCollection {
    return new PaymentCouponCollection();
});

$container[IPaymentCouponQuery::class] = $container->factory(function () : IPaymentCouponQuery {
    return new PaymentCouponQuery();
});

$container[IPaymentRecieved::class] = $container->factory(function () : IPaymentRecieved {
    return new PaymentRecieved2();
});

$container[IPaymentRecievedCollection::class] = $container->factory(function () : IPaymentRecievedCollection {
    return new PaymentRecievedCollection();
});

$container[IPaymentRecievedQuery::class] = $container->factory(function () : IPaymentRecievedQuery {
    return new PaymentRecievedQuery();
});

$container[IPaymentType::class] = $container->factory(function () : IPaymentType {
    return new PaymentType();
});

$container[IPaymentTypeCollection::class] = $container->factory(function () : IPaymentTypeCollection {
    return new PaymentTypeCollection();
});

$container[IPaymentTypeQuery::class] = $container->factory(function () : IPaymentTypeQuery {
    return new PaymentTypeQuery();
});

$container[IUserMessage::class] = $container->factory(function () : IUserMessage {
    return new UserMessage();
});

$container[IUserMessageCollection::class] = $container->factory(function () : IUserMessageCollection {
    return new UserMessageCollection();
});

$container[IUserMessageQuery::class] = $container->factory(function () : IUserMessageQuery {
    return new UserMessageQuery();
});

$container[IUser::class] = $container->factory(function () : IUser {
    return new User();
});

$container[IUserCollection::class] = $container->factory(function () : IUserCollection {
    return new UserCollection();
});

$container[IUserQuery::class] = $container->factory(function () : IUserQuery {
    return new UserQuery();
});

$container[IUserRole::class] = $container->factory(function () : IUserRole {
    return new UserRole();
});

$container[IUserRoleCollection::class] = $container->factory(function () : IUserRoleCollection {
    return new UserRoleCollection();
});

$container[IUserRoleQuery::class] = $container->factory(function () : IUserRoleQuery {
    return new UserRoleQuery();
});
