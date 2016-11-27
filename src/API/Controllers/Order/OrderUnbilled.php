<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\Ordering\OrderDetailQuery;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_ADD;

class OrderUnbilled extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_ADD];
        
        $o_app->getContainer()['db'];
    }
    
    function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive(),
            'all' => v::boolVal()
        );
        
        $this->validate($a_validators, $this->a_args);       
    }
    
    protected function GET() : void  {                
        $o_user = Auth::GetCurrentUser();      
        
        $i_orderid = intval($this->a_args['id']);
        $b_all = filter_var($this->a_args['all'], FILTER_VALIDATE_BOOLEAN);
        
        $o_payments = OrderDetailQuery::create()
                        ->leftJoinWithInvoiceItem()
                        ->where("SUM(" . InvoiceItemTableMap::COL_AMOUNT . ")")
                        ->filterByFinished(null)
                        ->filterByOrderid($i_orderid)
                        ->find();
    }        
    
    /*
    public function GetOpenPayments($i_orderid, $str_tableNr = null, $b_merge = true)
    {
        $a_result = array();

        $str_query = "SELECT odo.*,
                            m.name AS menuName,
                            mt.menu_typeid,
                            mt.name AS typeName,
                            ms.name AS sizeName,
                            GROUP_CONCAT(me.name ORDER BY me.name SEPARATOR ', ') AS selectedExtras,
                            (SELECT GROUP_CONCAT(m2.name ORDER BY m2.name SEPARATOR ', ')
                             FROM orders_details_mixed_with odmw
                             INNER JOIN menues m2 ON m2.menuid = odmw.menuid
                             WHERE odmw.orders_detailid = odo.orders_detailid) AS mixedWith
                     FROM orders_details_open odo
                     INNER JOIN orders o ON o.orderid = odo.orderid
                     INNER JOIN tables t ON t.tableid = o.tableid
                     INNER JOIN menues m ON m.menuid = odo.menuid
                     INNER JOIN menu_groupes mg ON mg.menu_groupid = m.menu_groupid
                     INNER JOIN menu_types mt ON mt.menu_typeid = mg.menu_typeid
                     INNER JOIN orders_detail_sizes ods ON ods.orders_detailid = odo.orders_detailid
                     INNER JOIN menues_possible_sizes mps ON mps.menues_possible_sizeid = ods.menues_possible_sizeid
                     INNER JOIN menu_sizes ms ON ms.menu_sizeid = mps.menu_sizeid
                     LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = odo.orders_detailid
                     LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                     LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                     WHERE __WHERE__
                     GROUP By odo.orders_detailid";

        if(!empty($str_tableNr))
        {
            $str_query = str_replace("__WHERE__", "t.name = :tableNr", $str_query );

            $o_statement = $this->o_db->prepare($str_query);
            $o_statement->bindParam(":tableNr", $str_tableNr);
        }
        else
        {
            $str_query = str_replace("__WHERE__", "odo.orderid = :orderid", $str_query );

            $o_statement = $this->o_db->prepare($str_query);
            $o_statement->bindParam(":orderid", $i_orderid);
        }

        $o_statement->execute();

        $a_result['orders'] = $o_statement->fetchAll();

        if(!empty($str_tableNr && $b_merge))
        {
            $a_order_verify = array();

            foreach($a_result['orders'] as $a_order)
            {
                $str_index = "{$a_order['menuid']}-{$a_order['single_price']}-{$a_order['extra_detail']}-{$a_order['sizeName']}-{$a_order['selectedExtras']}-{$a_order['mixedWith']}";

                if(!isset($a_order_verify[$str_index]))
                {
                    $a_order_verify[$str_index] = $a_order;
                }
                else
                {
                    $a_order_verify[$str_index]['amount'] += $a_order['amount'];
                    $a_order_verify[$str_index]['amount_payed'] += $a_order['amount_payed'];
                }
            }

            $a_result['orders'] = array_values($a_order_verify);
        }

        $str_query = "SELECT odseo.*
                      FROM orders_details_special_extra_open odseo
                      INNER JOIN orders o ON o.orderid = odseo.orderid
                      INNER JOIN tables t ON t.tableid = o.tableid
                      WHERE __WHERE__";

        if(!empty($str_tableNr))
        {
            $str_query = str_replace("__WHERE__", "t.name = :tableNr", $str_query );

            $o_statement = $this->o_db->prepare($str_query);
            $o_statement->bindParam(":tableNr", $str_tableNr);
        }
        else
        {
            $str_query = str_replace("__WHERE__", "odseo.orderid = :orderid", $str_query );

            $o_statement = $this->o_db->prepare($str_query);
            $o_statement->bindParam(":orderid", $i_orderid);
        }

        $o_statement->execute();

        $a_result['extras'] = $o_statement->fetchAll();

        if(!empty($str_tableNr) && $b_merge)
        {
            $a_extra_verify = array();

            foreach($a_result['extras'] as $a_extra)
            {
                $str_index = "{$a_extra['single_price']}-{$a_extra['extra_detail']}-{$a_extra['verified']}";

                if(!isset($a_extra_verify[$str_index]))
                {
                    $a_extra_verify[$str_index] = $a_extra;
                }
                else
                {
                    $a_extra_verify[$str_index]['amount'] += $a_extra['amount'];
                    $a_extra_verify[$str_index]['amount_payed'] += $a_extra['amount_payed'];
                }
            }

            $a_result['extras'] = array_values($a_extra_verify);
        }

        return $a_result;
    }
     */
    
}