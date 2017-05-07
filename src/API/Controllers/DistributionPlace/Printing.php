<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IPrintingInformation;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOutQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\Printer;
use API\Lib\Printer\PrinterConnector\ThermalPrinter;
use API\Lib\Printer\PrintingType\Order;
use API\Lib\SecurityController;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\ORDER_DEFAULT_SIZEID;

class Printing extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validatorsJson = array(
            'EventPrinterid' => v::alnum()->length(1),
        );

        $validatorsArgs = array(
            'DistributionGivingOutid' => v::alnum()->length(1),
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validatorsArgs, $this->args);
        $validate->assert($validatorsJson, $this->json);
    }

    protected function post() : void
    {
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);
        $distributionGivingOutQuery = $this->container->get(IDistributionGivingOutQuery::class);
        $orderQuery = $this->container->get(IOrderQuery::class);

        $eventPrinter = $eventPrinterQuery->findPk($this->json['EventPrinterid']);

        $distributionGivingOut = $distributionGivingOutQuery->getWithOrderDetails($this->args['DistributionGivingOutid']);

        $orderid = $distributionGivingOut->getOrderInProgressRecieveds()
                                         ->getFirst()
                                         ->getOrderDetail()
                                         ->getOrderid();

        $order = $orderQuery->getWithEventTableAndUser($orderid);

        $i18n = $this->container->get('i18n');

        $printingInformation = $this->container->get(IPrintingInformation::class);
        $printingInformation->setOrderNr($orderid);
        $printingInformation->setTableNr($order->getEventTable()->getName());
        $printingInformation->setName($order->getUser()->getFirstname() . " " . $order->getUser()->getLastname());
        $printingInformation->setDate($order->getOrdertime());
        $printingInformation->setDateFooter($distributionGivingOut->getDate());

        foreach ($distributionGivingOut->getOrderInProgressRecieveds() as $orderInProgressRecieved) {
            $name = "";
            $orderDetail = $orderInProgressRecieved->getOrderDetail();

            if ($orderDetail->getMenuid()) {
                $name = $orderDetail->getMenu()->getName();

                if ($orderDetail->getMenuSizeid() != ORDER_DEFAULT_SIZEID) {
                    $name .= " " . $orderDetail->getMenuSize()->getName();
                }

                if ($orderDetail->getOrderDetailMixedWiths()->count() > 0) {
                    $name .= " " . $i18n->ReciepPrint->mixedWith . ": ";

                    foreach ($orderDetail->getOrderDetailMixedWiths as $orderDetailMixedWith) {
                        $name .= $orderDetailMixedWith->getMenu()->getName() . ", ";
                    }

                    $name = substr($name, 0, -2) . ";";
                }

                foreach ($orderDetail->getOrderDetailExtras() as $orderDetailExtra) {
                    $name .= " " . $orderDetailExtra->getMenuPossibleExtra()->getMenuExtra()->getName() . ',';
                }

                if (!empty($orderDetail->getExtraDetail())) {
                    $name .= " " . $orderDetail->getExtraDetail();
                }

                if (substr($name, -1) == ',') {
                    $name = substr($name, 0, -1);
                }
            } else {
                $name = $orderDetail->getExtraDetail();
            }

            $printingInformation->addRow($name, $orderInProgressRecieved->getAmount());
        }

        try
        {
            $printerConnector = $this->container->get(ThermalPrinter::class);
            $printerConnector->setEventPrinter($eventPrinter);

            $order = $this->container->get(Order::class);
            $order->setPrinterConnector($printerConnector);
            $order->setPrintingInformation($printingInformation);
            $order->printType();

            $this->withJson(true);
        } catch (Exception $exception) {
            throw new Exception("Ausgabenzetteldruck fehlgeschlagen! Ausgabeid: {$this->args['DistributionGivingOutid']}", $exception->getCode(), $exception);
        }
    }
}
