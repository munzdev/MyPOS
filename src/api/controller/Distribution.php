<?php
namespace Controller;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Lib\Request;
use Lib\Invoice;
use Model;
use MyPOS;
use Exception;

class Distribution extends SecurityController
{
    public function __construct()
    {
        parent::__construct();

        $this->a_security = array('GetDistributionOrderDatas' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'GetOrder' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'GetOrdersInTodoList' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'GetOrderDoneInformation' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'GetProductsAvailability' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'SetAvailabilityAmount' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'SetAvailabilityStatus' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'FinishOrder' => MyPOS\USER_ROLE_DISTRIBUTION,
                                  'PrintOrder' => MyPOS\USER_ROLE_DISTRIBUTION);
    }

    public function GetDistributionOrderDatasAction()
    {
        $a_return = array();
        $a_return['GetOrder'] = $this->GetOrderAction();
        $a_return['GetOrdersInTodoList'] = $this->GetOrdersInTodoListAction();
        $a_return['GetOrderDoneInformation'] = $this->GetOrderDoneInformationAction();
        $a_return['GetProductsAvailability'] = $this->GetProductsAvailabilityAction();

        return $a_return;
    }

    public function GetOrderAction()
    {
        $a_user = Login::GetCurrentUser();
        $a_config = $GLOBALS['a_config'];

        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);

        try
        {
            $o_db->beginTransaction();

            //-- Fetch allready started order to handle, whoes not finished yet (like page reloaded or the status of a product has changed back to available)
            $a_orders_in_progressid = $o_distribution->GetUsersOpenInProgressOrders($a_user['userid'], $a_user['eventid']);

            if(empty($a_orders_in_progressid)) //-- if no existing progress order found, take a new order from priority list
            {
                $a_orders_to_handle = $o_distribution->GetDistributionPlaceNextOrders($a_user['userid'], $a_user['eventid']);

                //-- Secondly try to find an order, which belongs to an other distribution place but has the same menu_groupid and can also be handeled by the users one
                //-- this lets ordes be done faster
                if(!$a_orders_to_handle && $a_config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces'])
                {
                    $a_orders_to_handle = $o_distribution->GetAnyDistributionPlaceNextOrders($a_user['userid'], $a_user['eventid']);
                }

                if(!$a_orders_to_handle)
                {
                    return null;
                }

                $a_orders_in_progressid = array();
                $i_orderid = null;
                foreach($a_orders_to_handle as $a_order_to_handle)
                {
                    if($i_orderid === null)
                        $i_orderid = $a_order_to_handle['orderid'];

                    if($i_orderid != $a_order_to_handle['orderid'])
                        break;

                    $a_orders_in_progressid[] = $o_distribution->AddInProgress($a_user['userid'], $i_orderid, $a_order_to_handle['menu_groupid']);
                }
            }

            $a_order = $o_distribution->GetOrderDetailsOfProgessIds($a_orders_in_progressid);

            $a_order_info = $o_distribution->GetOrderInfoFromProgressIDs($a_orders_in_progressid);

            $a_order = array_merge($a_order, $a_order_info);
            $a_order['orders_in_progressids'] = $a_orders_in_progressid;

            $o_db->commit();

            return $a_order;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function GetOrdersInTodoListAction()
    {
        $a_user = Login::GetCurrentUser();
        $a_config = $GLOBALS['a_config'];

        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);

        $a_orders_list = $o_distribution->GetDistributionPlaceNextOrders($a_user['userid'], $a_user['eventid']);

        $a_order_ids = array();

        foreach($a_orders_list as $a_order)
        {
            if(!in_array($a_order['orderid'], $a_order_ids))
            {
                $a_order_ids[] = $a_order['orderid'];
            }

            if(count($a_order_ids) == $a_config['App']['Distribution']['AmountDisplayedInTodoList'])
                break;
        }

        $a_infos = $o_distribution->GetOrderInfo($a_order_ids);

        return $a_infos;
    }

    public function GetOrderDoneInformationAction()
    {
        $a_user = Login::GetCurrentUser();
        $a_config = $GLOBALS['a_config'];

        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);

        $a_orders_list = $o_distribution->GetDistributionPlaceNextOrders($a_user['userid'], $a_user['eventid']);

        $a_order_ids = array();

        foreach($a_orders_list as $a_order)
        {
            if(!in_array($a_order['orderid'], $a_order_ids))
            {
                $a_order_ids[] = $a_order['orderid'];
            }
        }

        $a_return['open_orders'] = count($a_order_ids);
        $a_return['done_orders'] = $o_distribution->GetOrdersDone($a_user['userid'], $a_user['eventid'], $a_config['App']['Distribution']['OrderProgressTimeRangeMinutes']);
        $a_return['new_orders'] = $o_distribution->GetOrdersNew($a_order_ids, $a_config['App']['Distribution']['OrderProgressTimeRangeMinutes']);

        return $a_return;
    }

    public function GetProductsAvailabilityAction()
    {
        $a_user = Login::GetCurrentUser();

        $o_db = Database::GetConnection();

        $o_products = new Model\Products($o_db);
        $o_distribution = new Model\Distribution($o_db);

        $a_return = array();
        $a_return['menues'] = $o_products->GetMenues($a_user['eventid']);
        $a_return['extras'] = $o_products->GetExtras($a_user['eventid']);
        $a_return['special_extras'] = $o_distribution->GetAvailabilitySpecialExtras($a_user['eventid']);

        return $a_return;
    }

    public function SetAvailabilityAmountAction()
    {
        $a_params = Request::ValidateParams(array('type' => 'string',
                                                  'id' => 'numeric',
                                                  'amount' => 'numeric'));

        $o_db = Database::GetConnection();

        if($a_params['amount'] == 0)
            $a_params['amount'] = null;

        if($a_params['type'] == 'menu')
        {
            $o_products = new Model\Products($o_db);

            return $o_products->SetMenuAvailabilityAmount($a_params['id'], $a_params['amount']);
        }
        else if($a_params['type'] == 'extra')
        {
            $o_products = new Model\Products($o_db);

            return $o_products->SetExtraAvailabilityAmount($a_params['id'], $a_params['amount']);
        }
        else if($a_params['type'] == 'special-extra')
        {
            $o_orders = new Model\Orders($o_db);

            return $o_orders->SetSpecialExtraAvailabilityAmount($a_params['id'], $a_params['amount']);
        }

        return false;
    }

    public function SetAvailabilityStatusAction()
    {
        $a_params = Request::ValidateParams(array('type' => 'string',
                                                  'id' => 'numeric',
                                                  'status' => 'string'));

        $o_db = Database::GetConnection();

        if($a_params['type'] == 'menu')
        {
            $o_products = new Model\Products($o_db);

            return $o_products->SetMenuAvailabilityStatus($a_params['id'], $a_params['status']);
        }
        else if($a_params['type'] == 'extra')
        {
            $o_products = new Model\Products($o_db);

            return $o_products->SetExtraAvailabilityStatus($a_params['id'], $a_params['status']);
        }
        else if($a_params['type'] == 'special-extra')
        {
            $o_orders = new Model\Orders($o_db);

            return $o_orders->SetSpecialExtraAvailabilityStatus($a_params['id'], $a_params['status']);
        }

        return false;
    }

    public function FinishOrderAction()
    {
        $a_params = Request::ValidateParams(array('order_in_progressids' => 'array',
                                                  'order_details' => 'optional!array',
                                                  'order_details_special_extras' => 'optional!array'));

        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);
        $o_orders = new Model\Orders($o_db);

        try
        {
            $o_db->beginTransaction();

            $i_distributions_giving_outid = $o_distribution->AddGivingOut();

            if(isset($a_params['order_details']))
                $o_distribution->AddOrdersInProgressRecieved($i_distributions_giving_outid,
                                                             $a_params['order_details']);

            if(isset($a_params['order_details_special_extras']))
                $o_distribution->AddOrdersExtrasInProgressRecieved( $i_distributions_giving_outid,
                                                                    $a_params['order_details_special_extras']);

            $i_orderid = $o_distribution->GetOrderIDFromProgressID($a_params['order_in_progressids'][0]);

            $o_distribution->CheckIfInProgressOrderDone($a_params['order_in_progressids']);

            $o_orders->CheckIfOrderDone($i_orderid);

            $o_db->commit();

            return $i_distributions_giving_outid;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function PrintOrderAction()
    {
        $a_params = Request::ValidateParams(array('distributions_giving_outid' => 'numeric',
                                                  'events_printerid' => 'numeric'));

        $o_db = Database::GetConnection();


        $o_distribution = new Model\Distribution($o_db);
        $o_events = new Model\Events($o_db);
        $o_orders = new Model\Orders($o_db);


        $a_printer = $o_events->GetPrinter($a_params['events_printerid']);
        $a_giving_out = $o_distribution->GetGivingOut($a_params['distributions_giving_outid']);
        $a_order_info = $o_orders->GetOrderInfo($a_giving_out['orderid']);

        $o_connector = new NetworkPrintConnector($a_printer['ip'], $a_printer['port']);

        $o_recipe = new Invoice($o_connector, $a_printer['characters_per_row']);

        $o_recipe->SetNr($a_giving_out['orderid']);
        $o_recipe->SetTableNr($a_order_info['table_name']);
        $o_recipe->SetName($a_order_info['user']);
        $o_recipe->SetDate(date(MyPOS\DATE_PHP_TIMEFORMAT, strtotime($a_order_info['ordertime']) ));
        $o_recipe->SetDateFooter(date(MyPOS\DATE_PHP_TIMEFORMAT, strtotime($a_giving_out['date']) ));

        foreach($a_giving_out['giving_outs'] as $a_row)
        {
            $o_recipe->Add($a_row['name'],
                            $a_row['amount']);
        }

        try
        {
            $o_recipe->PrintOrder();

            return true;
        }
        catch(Exception $o_exception)
        {
            throw new Exception("Bestellungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Bestellungsdrucknummer: $a_params[distributions_giving_outid]", $o_exception->getCode(), $o_exception);
        }
    }
}