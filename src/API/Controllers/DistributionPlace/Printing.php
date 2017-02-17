<?php

namespace API\Controllers\DistributionPlace;

use API\Lib\Auth;
use API\Lib\ReciepPrint;
use API\Lib\SecurityController;
use API\Models\Event\Base\EventPrinterQuery;
use API\Models\OIP\DistributionGivingOutQuery;
use API\Models\Ordering\Base\OrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\ORDER_DEFAULT_SIZEID;

class Printing extends SecurityController
{
    private $b_withPayments;

    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators_json = array(
            'EventPrinterid' => v::alnum()->length(1),
        );

        $a_validators_args = array(
            'DistributionGivingOutid' => v::alnum()->length(1),
        );

        $this->validate($a_validators_args, $this->a_args);
        $this->validate($a_validators_json, $this->a_json);
    }

    protected function POST() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_printer = EventPrinterQuery::create()
                                        ->findOneByEventPrinterid($this->a_json['EventPrinterid']);

        $o_distributionGibingOut = DistributionGivingOutQuery::create()
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
                                                                ->filterByDistributionGivingOutid($this->a_args['DistributionGivingOutid'])
                                                                ->find()
                                                                ->getFirst();

        $i_orderid = $o_distributionGibingOut->getOrderInProgressRecieveds()
                                             ->getFirst()
                                             ->getOrderDetail()
                                             ->getOrderid();

        $o_order = OrderQuery::create()
                                ->joinWithEventTable()
                                ->joinUserRelatedByUserid()
                                ->filterByOrderid($i_orderid)
                                ->find()
                                ->getFirst();

        $o_i18n = $this->o_app->getContainer()['i18n'];

        $o_connector = ReciepPrint::GetConnector($o_printer);
        $o_reciepPrint = new ReciepPrint($o_connector, $o_printer->getCharactersPerRow(), $o_i18n->ReciepPrint);

        $o_reciepPrint->SetOrderNr($i_orderid);
        $o_reciepPrint->SetTableNr($o_order->getEventTable()->getName());
        $o_reciepPrint->SetName($o_order->getUserRelatedByuserid()->getFirstname() . " " . $o_order->getUserRelatedByuserid()->getLastname());
        $o_reciepPrint->SetDate($o_order->getOrdertime());
        $o_reciepPrint->SetDateFooter($o_distributionGibingOut->getDate());

        foreach($o_distributionGibingOut->getOrderInProgressRecieveds() as $o_orderInProgressRecieved) {

            $str_name = "";
            $o_orderDetail = $o_orderInProgressRecieved->getOrderDetail();

            if($o_orderDetail->getMenuid()) {
                $str_name = $o_orderDetail->getMenu()->getName();

                if($o_orderDetail->getMenuSizeid() != ORDER_DEFAULT_SIZEID) {
                    $str_name .= " " . $o_orderDetail->getMenuSize()->getName();
                }

                if($o_orderDetail->getOrderDetailMixedWiths()->count() > 0) {
                    $str_name .= " " . $o_i18n->ReciepPrint->mixedWith . ": ";

                    foreach($o_orderDetail->getOrderDetailMixedWiths as $o_orderDetailMixedWith) {
                        $str_name .= $o_orderDetailMixedWith->getMenu()->getName() . ", ";
                    }

                    $str_name = substr($str_name, 0, -2) . ";";
                }

                foreach($o_orderDetail->getOrderDetailExtras() as $o_orderDetailExtra) {
                    $str_name .= " " . $o_orderDetailExtra->getMenuPossibleExtra()->getMenuExtra()->getName() . ',';
                }

                if(!empty($o_orderDetail->getExtraDetail()))
                    $str_name .= " " . $o_orderDetail->getExtraDetail();

                if(substr($str_name, -1) == ',')
                    $str_name = substr($str_name, 0, -1);

            } else {
                $str_name = $o_orderDetail->getExtraDetail();
            }

            $o_reciepPrint->Add($str_name, $o_orderInProgressRecieved->getAmount());
        }

        try {
            $o_reciepPrint->PrintOrder();

            $this->withJson(true);
        } catch(Exception $o_exception) {
            throw new Exception("Rechnungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Rechnungsnummer: $a_params[invoiceid]", $o_exception->getCode(), $o_exception);
        }
    }
}