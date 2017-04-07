<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Printer;
use API\Lib\SecurityController;
use API\Models\ORM\Event\Base\EventPrinterQuery;
use API\Models\ORM\OIP\DistributionGivingOutQuery;
use API\Models\ORM\Ordering\Base\OrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\ORDER_DEFAULT_SIZEID;

class Printing extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
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
        $printer = EventPrinterQuery::create()
                                        ->findOneByEventPrinterid($this->json['EventPrinterid']);

        $distributionGivingOut = DistributionGivingOutQuery::create()
                                                            ->joinWithOrderInProgressRecieved()
                                                            ->useOrderInProgressRecievedQuery()
                                                                ->joinWithOrderDetail()
                                                                ->useOrderDetailQuery()
                                                                    ->leftJoinWithMenu()
                                                                    ->leftJoinWithMenuSize()
                                                                    ->leftJoinWithOrderDetailExtra()
                                                                    ->useOrderDetailExtraQuery(null, Criteria::LEFT_JOIN)
                                                                        ->leftJoinWithMenuPossibleExtra()
                                                                        ->useMenuPossibleExtraQuery(null, Criteria::LEFT_JOIN)
                                                                            ->leftJoinWithMenuExtra()
                                                                        ->endUse()
                                                                    ->endUse()
                                                                    ->leftJoinWithOrderDetailMixedWith()
                                                                ->endUse()
                                                            ->endUse()
                                                            ->filterByDistributionGivingOutid($this->args['DistributionGivingOutid'])
                                                            ->find()
                                                            ->getFirst();

        $orderid = $distributionGivingOut->getOrderInProgressRecieveds()
                                        ->getFirst()
                                        ->getOrderDetail()
                                        ->getOrderid();

        $order = OrderQuery::create()
                            ->joinWithEventTable()
                            ->joinUserRelatedByUserid()
                            ->filterByOrderid($orderid)
                            ->find()
                            ->getFirst();

        $i18n = $this->app->getContainer()['i18n'];

        $connector = Printer::getConnector($printer);
        $reciepPrint = new Printer($connector, $printer->getCharactersPerRow(), $i18n->ReciepPrint);

        $reciepPrint->setOrderNr($orderid);
        $reciepPrint->setTableNr($order->getEventTable()->getName());
        $reciepPrint->setName($order->getUserRelatedByuserid()->getFirstname() . " " . $order->getUserRelatedByuserid()->getLastname());
        $reciepPrint->setDate($order->getOrdertime());
        $reciepPrint->setDateFooter($distributionGivingOut->getDate());

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

            $reciepPrint->add($name, $orderInProgressRecieved->getAmount());
        }

        try {
            $reciepPrint->printOrder();

            $this->withJson(true);
        } catch (Exception $exception) {
            throw new Exception("Ausgabenzetteldruck fehlgeschlagen! Ausgabeid: {$this->args['DistributionGivingOutid']}", $exception->getCode(), $exception);
        }
    }
}
