<?php
namespace Controller;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Lib\Request;
use Lib\Invoice;
use Exception;
use Model;
use MyPOS;

class Orders extends SecurityController
{
    public function __construct()
    {
        parent::__construct();

        $this->a_security = array('AddOrder' => MyPOS\USER_ROLE_WAITRESS,
                                  'GetOpenList' => MyPOS\USER_ROLE_WAITRESS,
                                  'GetOpenPayments' => MyPOS\USER_ROLE_WAITRESS,
                                  'GetOrder' => MyPOS\USER_ROLE_WAITRESS,
                                  'GetOrderInfo' => MyPOS\USER_ROLE_WAITRESS,
                                  'MakeCancel' => MyPOS\USER_ROLE_WAITRESS,
                                  'MakePayment' => MyPOS\USER_ROLE_WAITRESS,
                                  'ModifyOrder' => MyPOS\USER_ROLE_WAITRESS,
                                  'PrintInvoice' => MyPOS\USER_ROLE_WAITRESS);
    }

    public function GetOpenListAction()
    {
        $o_orders = new Model\Orders(Database::GetConnection());

        $a_user = Login::GetCurrentUser();

        $a_orders_result = $o_orders->GetList($a_user['eventid'],
                                              $a_user['userid'],
                                              false);

        $a_orders = array();

        foreach ($a_orders_result as $a_order)
        {
            $a_order['button_info'] = true;
            $a_order['button_edit'] = $a_order['status'] == MyPOS\ORDER_STATUS_WAITING;
            $a_order['button_pay'] = $a_order['open'] > 0;
            $a_order['button_cancel'] = $a_order['status'] == MyPOS\ORDER_STATUS_WAITING;
            $a_order['finished'] = $a_order['status'] == MyPOS\ORDER_STATUS_FINISHED;

            $a_orders[] = $a_order;
        }

        return $a_orders;
    }

    public function AddOrderAction()
    {
        $a_params = Request::ValidateParams(array('order' => 'json',
                                                  'options' => 'array'));

        $o_db = Database::GetConnection();

        $o_orders = new Model\Orders($o_db);
        $o_tables = new Model\Tables($o_db);

        $a_user = Login::GetCurrentUser();

        $a_orders = json_decode($a_params['order'], true);

        try
        {
            $o_db->beginTransaction();

            $i_tableId = $o_tables->GetTableID($a_params['options']['tableNr']);
            $i_orderId = $o_orders->AddOrder($a_user['eventid'], $a_user['userid'], $i_tableId);

            foreach ($a_orders as $a_category)
            {
                foreach($a_category['orders'] as $a_order)
                {
                    $a_extraIds_only = array();
                    $a_mixingIds_only = array();

                    foreach ($a_order['extras'] as $a_extra)
                    {
                        $a_extraIds_only[] = $a_extra['menu_extraid'];
                    }

                    foreach ($a_order['mixing'] as $a_mixing)
                    {
                        $a_mixingIds_only[] = $a_mixing['menuid'];
                    }

                    $o_orders->AddOrderDetail($i_orderId,
                                              $a_order['menuid'],
                                              $a_order['amount'],
                                              $a_order['extra'],
                                              $a_order['sizes'][0]['menu_sizeid'],
                                              $a_extraIds_only,
                                              $a_mixingIds_only);
                }
            }

            $o_orders->CheckIfOrderDone($i_orderId);

            $o_db->commit();

            return $i_orderId;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function ModifyOrderAction()
    {
        $a_params = Request::ValidateParams(array('order' => 'json',
                                                  'options' => 'array'));

        $i_orderId = $a_params['options']['id'];

        $o_db = Database::GetConnection();

        $o_orders = new Model\Orders($o_db);

        $a_orders = json_decode($a_params['order'], true);

        try
        {
            $o_db->beginTransaction();

            foreach ($a_orders as $a_category)
            {
                foreach($a_category['orders'] as $a_order)
                {
                    $i_backendId = $a_order['backendID'];
                    if($i_backendId == 0)
                    {
                        $a_extraIds_only = array();
                        $a_mixingIds_only = array();

                        foreach ($a_order['extras'] as $a_extra)
                        {
                            $a_extraIds_only[] = $a_extra['menu_extraid'];
                        }

                        foreach ($a_order['mixing'] as $a_mixing)
                        {
                            $a_mixingIds_only[] = $a_mixing['menuid'];
                        }

                        $o_orders->AddOrderDetail($i_orderId,
                                                  $a_order['menuid'],
                                                  $a_order['amount'],
                                                  $a_order['extra'],
                                                  $a_order['sizes'][0]['menu_sizeid'],
                                                  $a_extraIds_only,
                                                  $a_mixingIds_only);
                    }

                    if($a_category['menu_typeid'] == 0) //-- Special Extra
                    {
                        $o_orders->SetSpecialExtraAmount($i_backendId, $a_order['amount']);
                    }
                    else
                    {
                        $o_orders->SetOrderDetailAmount($i_backendId, $a_order['amount']);
                    }
                }
            }

            $o_orders->CheckIfOrderDone($i_orderId);

            $o_db->commit();

            return $i_orderId;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function GetOpenPaymentsAction()
    {
        $a_params = Request::ValidateParams(array('orderid' => 'numeric',
                                                  'tableNr' => 'optional!string'));

        $o_orders = new Model\Orders(Database::GetConnection());

        if(isset($a_params['tableNr']))
        {
            return $o_orders->GetOpenPayments(null, $a_params['tableNr']);
        }
        else
            return $o_orders->GetOpenPayments($a_params['orderid']);
    }

    public function MakePaymentAction()
    {
        $a_params = Request::ValidateParams(array('orderid' => 'numeric',
                                                  'tableNr' => 'optional!string',
                                                  'mode' => 'string',
                                                  'payments' => 'json'));

        $o_db = Database::GetConnection();

        $o_invoices = new Model\Invoices($o_db);
        $o_orders = new Model\Orders($o_db);

        $a_user = Login::GetCurrentUser();

        $a_payments = json_decode($a_params['payments'], true);

        try
        {
            $o_db->beginTransaction();

            $i_invoiceId = $o_invoices->Add($a_user['userid']);

            $a_open_orders = $o_orders->GetOpenPayments($a_params['orderid'], $a_params['tableNr'], false);

            $a_update = array('orders' => array(),
                              'extras' => array());

            foreach ($a_payments['orders'] as $a_order)
            {
                $i_amount_in_invoice = $a_order['currentInvoiceAmount'];

                foreach($a_open_orders['orders'] as $a_open_order)
                {

                    if($a_open_order['menuid'] == $a_order['menuid'] &&
                        $a_open_order['single_price'] == $a_order['single_price'] &&
                        $a_open_order['extra_detail'] == $a_order['extra_detail'] &&
                        $a_open_order['sizeName'] == $a_order['sizeName'] &&
                        $a_open_order['selectedExtras'] == $a_order['selectedExtras'])
                    {
                        $i_allready_payed = $a_open_order['amount_payed'];
                        $i_amount_ordered = $a_open_order['amount'];
                        $i_amount_open = $i_amount_ordered - $i_allready_payed;
                        $i_amount_part = $i_amount_in_invoice;

                        if($i_amount_part > $i_amount_open)
                            $i_amount_part = $i_amount_open;

                        $i_amount_in_invoice -= $i_amount_part;

                        $a_update['orders'][$a_open_order['orders_detailid']] = $i_amount_part;

                        if($i_amount_in_invoice == 0)
                            continue 2;
                    }
                }
            }

            foreach ($a_payments['extras'] as $a_extra)
            {
                $i_amount_in_invoice = $a_extra['currentInvoiceAmount'];

                foreach($a_open_orders['extras'] as $a_open_extra)
                {
                    if(!$a_open_extra['verified'])
                        continue;

                    if($a_open_extra['single_price'] == $a_extra['single_price'] &&
                        $a_open_extra['extra_detail'] == $a_extra['extra_detail'] &&
                        $a_open_extra['verified'] == $a_extra['verified'])
                    {
                        $i_allready_payed = $a_open_extra['amount_payed'];
                        $i_amount_ordered = $a_open_extra['amount'];
                        $i_amount_open = $i_amount_ordered - $i_allready_payed;
                        $i_amount_part = $i_amount_in_invoice;

                        if($i_amount_part > $i_amount_open)
                            $i_amount_part = $i_amount_open;

                        $i_amount_in_invoice -= $i_amount_part;

                        $a_update['extras'][$a_open_extra['orders_details_special_extraid']] = $i_amount_part;

                        if($i_amount_in_invoice == 0)
                            continue 2;
                    }
                }
            }

            foreach($a_update['orders'] as $i_orderid => $i_amount)
            {
                $o_invoices->AddOrder($i_invoiceId, $i_orderid, $i_amount);
            }

            foreach($a_update['extras'] as $i_orders_details_special_extraid => $i_amount)
            {
                $o_invoices->AddExtra($i_invoiceId, $i_orders_details_special_extraid, $i_amount);
            }

            $o_orders->CheckIfOrderDone($a_params['orderid']);

            $o_db->commit();

            return $i_invoiceId;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function GetOrderAction()
    {
        $a_params = Request::ValidateParams(array('orderid' => 'numeric'));

        $o_orders = new Model\Orders(Database::GetConnection());

        return $o_orders->GetFullOrder($a_params['orderid']);
    }

    public function MakeCancelAction()
    {
        $a_params = Request::ValidateParams(array('orderid' => 'numeric'));

        $o_db = Database::GetConnection();

        $o_orders = new Model\Orders($o_db);

        try
        {
            $o_db->beginTransaction();

            $o_orders->CancelOrder($a_params['orderid']);
            $o_orders->CheckIfOrderDone($a_params['orderid']);

            $o_db->commit();
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function GetOrderInfoAction()
    {
        $a_params = Request::ValidateParams(array('orderid' => 'numeric'));

        $o_orders = new Model\Orders(Database::GetConnection());

        $a_order = $o_orders->GetOrderInfo($a_params['orderid']);

        $a_order['orders'] = $o_orders->GetFullOrder($a_params['orderid'], true);

        return $a_order;
    }

    public function PrintInvoiceAction()
    {
        $a_params = Request::ValidateParams(array('invoiceid' => 'numberic',
                                                  'printerid' => 'numberic'));

        $o_db = Database::GetConnection();

        $o_invoices = new Model\Invoices($o_db);
        $o_events = new Model\Events($o_db);

        $a_printer = $o_events->GetPrinter($a_params['printerid']);
        $a_invoice = $o_invoices->GetInvoice($a_params['invoiceid']);
        $a_config = $GLOBALS['a_config']['Organisation']['Invoice'];

        $o_connector = new NetworkPrintConnector($a_printer['ip'], $a_printer['port']);

        $o_invoice = new Invoice($o_connector, $a_printer['characters_per_row']);

        if($a_config['Logo']['Use'])
            $o_invoice->SetLogo($a_config['Logo']['Path'], $a_config['Logo']['Type']);

        $o_invoice->SetHeader($a_config['Header']);
        $o_invoice->SetNr($a_params['invoiceid']);
        $o_invoice->SetTableNr($a_invoice['name']);
        $o_invoice->SetName($a_invoice['cashier']);
        $o_invoice->SetDate(date(MyPOS\DATE_PHP_TIMEFORMAT, strtotime($a_invoice['date']) ));

        foreach($a_invoice['rows'] as $a_row)
        {
            $o_invoice->Add($a_row['name'],
                            $a_row['amount'],
                            $a_row['price'],
                            $a_row['tax']);
        }

        try
        {
            $o_invoice->PrintInvoice();

            return true;
        }
        catch(Exception $o_exception)
        {
            throw new Exception("Rechnungsdruck fehlgeschlagen! Bitte Vorgang wiederhollen! Rechnungsnummer: $a_params[invoiceid]", $o_exception->getCode(), $o_exception);
        }
    }
}