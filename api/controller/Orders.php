<?php

class Orders extends SecurityController
{
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
            $a_order['button_pay'] = $a_order['price'] != $a_order['payed'];
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
                foreach($a_category['order'] as $a_order)
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

            $o_db->commit();

            return $i_orderId;
        }
        catch (PDOException $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function ModifyOrderAction()
    {
        $o_orders = new Model\Orders(Database::GetConnection());

        $a_user = Login::GetCurrentUser();
    }

}