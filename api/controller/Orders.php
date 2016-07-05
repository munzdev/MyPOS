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

        $o_orders = new Model\Orders(Database::GetConnection());

        $a_user = Login::GetCurrentUser();

        $a_order = json_decode($a_params['order'], true);


        $i_newId = $o_orders->Add($a_order);

        return $i_newId;
    }

    public function ModifyOrderAction()
    {
        $o_orders = new Model\Orders(Database::GetConnection());

        $a_user = Login::GetCurrentUser();
    }

}