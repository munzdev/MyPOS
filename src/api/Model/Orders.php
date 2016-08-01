<?php
namespace Model;

use PDO;

class Orders
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function GetList($i_eventid, $i_userid, $b_finished)
    {
        $str_not = $b_finished ? 'NOT' : '';

        $str_query = "SELECT o.orderid,
                             o.tableid,
                             t.name AS table_name,
                             o.ordertime,
                             o.priority,
                             IF(oip.begin IS NULL, 1, IF(oip.done IS NULL, 2, 3)) AS status,
                             (SELECT SUM(od1.amount * od1.single_price)
                                 FROM orders_details od1
                                 WHERE od1.orderid = o.orderid ) AS price,
                             (SELECT count(*)
                                 FROM orders_details_open odo
                                 WHERE odo.orderid = o.orderid) +
                                (SELECT count(*)
                                 FROM orders_details_special_extra_open odseo
                                 WHERE odseo.orderid = o.orderid) AS open
                      FROM orders o
                      INNER JOIN tables t ON t.tableid = o.tableid
                      LEFT JOIN orders_in_progress oip ON oip.orderid = o.orderid
                      WHERE o.eventid = :eventid
                                AND o.userid = :userid
                                AND o.finished IS $str_not NULL
                      ORDER BY o.priority ASC";

        $o_statement = $this->o_db->prepare($str_query);

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindParam(":userid", $i_userid);

        $o_statement->execute();

        $a_orders = $o_statement->fetchAll();

        return $a_orders;
    }

    public function AddOrder($i_eventid, $i_userid, $i_tableId)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO orders(eventid, tableid, userid, ordertime, priority )
                                             VALUES(:eventid, :tableid, :userid, NOW(), (SELECT MAX(o.priority) + 1
                                                                                         FROM orders o
                                                                                         WHERE o.eventid = :eventid))");

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindParam(":tableid", $i_tableId);
        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function AddOrderDetail($i_orderId, $i_menuid, $i_amount, $str_extra_detail, $i_sizeid, $a_extraIds, $a_mixingIds)
    {
        $o_statement = $this->o_db->prepare("SELECT (SELECT m.price + mps.price
                                              FROM menues m
                                              INNER JOIN menues_possible_sizes mps ON mps.menu_sizeid = :sizeid AND mps.menuid = :menuid
                                              WHERE m.menuid = :menuid) AS basePrice,
                                             (SELECT SUM(mpe.price)
                                              FROM menues_possible_extras mpe
                                              WHERE mpe.menu_extraid IN(:extraIds)
                                                    AND mpe.menuid = :menuid) AS extraPrice");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindValue(":extraIds", implode(',', $a_extraIds));
        $o_statement->bindParam(":sizeid", $i_sizeid);
        $o_statement->execute();

        $a_price = $o_statement->fetch();

        $i_price = $a_price['basePrice'];

        if(!empty($a_mixingIds))
        {
            foreach ($a_mixingIds as $i_mixingId)
            {
                $o_statement = $this->o_db->prepare("SELECT m.price + mps.price
                                                     FROM menues m
                                                     INNER JOIN menues_possible_sizes mps ON mps.menuid = m.menuid
                                                                                          AND mps.menu_sizeid = :sizeid
                                                     WHERE m.menuid  = :mixingId ");

                $o_statement->bindParam(":mixingId", $i_mixingId);
                $o_statement->bindParam(":sizeid", $i_sizeid);
                $o_statement->execute();

                $i_mixingPrice = $o_statement->fetchColumn();

                if($i_mixingPrice !== false)
                {
                    $i_price += $i_mixingPrice;
                    continue;
                }

                $o_statement = $this->o_db->prepare("SELECT (mps.price + m.price) * (ms2.factor / ms.factor) AS price,
                                                             ms2.factor AS factorTo,
                                                             ms.factor AS factorFrom,
                                                             mps.price AS sizePrice,
                                                             m.price AS basePrice
                                                     FROM menu_sizes ms
                                                     INNER JOIN menu_sizes ms2 ON ms2.menu_sizeid = :sizeid
                                                     INNER JOIN menues_possible_sizes mps ON mps.menuid = :menuid
                                                                                          AND mps.menu_sizeid = ms.menu_sizeid
                                                     INNER JOIN menues m ON m.menuid = mps.menuid
                                                     LIMIT 1");

                $o_statement->bindParam(":menuid", $i_mixingId);
                $o_statement->bindParam(":sizeid", $i_sizeid);
                $o_statement->execute();

                $i_price += $o_statement->fetchColumn();
            }

            $i_price = $i_price / (count($a_mixingIds) + 1);
            $i_price = round($i_price, 1); //-- avoid cents
        }

        $i_price += $a_price['extraPrice'];

        if($i_menuid != 0)
        {
            $o_statement = $this->o_db->prepare("INSERT INTO orders_details(orderid, menuid, amount, single_price, extra_detail )
                                                 VALUES (:orderid, :menuid, :amount, :single_price, :extra_detail)");

            $o_statement->bindParam(":orderid", $i_orderId);
            $o_statement->bindParam(":menuid", $i_menuid);
            $o_statement->bindParam(":amount", $i_amount);
            $o_statement->bindParam(":single_price", $i_price);
            $o_statement->bindParam(":extra_detail", $str_extra_detail);
            $o_statement->execute();

            $i_order_detailid = $this->o_db->lastInsertId();

            $o_statement = $this->o_db->prepare("INSERT INTO orders_detail_sizes(orders_detailid, menues_possible_sizeid)
                                                 VALUES (:orders_detailid, (SELECT menues_possible_sizeid
                                                                            FROM menues_possible_sizes
                                                                            WHERE menu_sizeid = :sizeid
                                                                                  AND menuid = :menuid))");

            $o_statement->bindParam(":orders_detailid", $i_order_detailid);
            $o_statement->bindParam(":sizeid", $i_sizeid);
            $o_statement->bindParam(":menuid", $i_menuid);
            $o_statement->execute();

            $o_statement = $this->o_db->prepare("INSERT INTO orders_detail_extras(orders_detailid, menues_possible_extraid)
                                                 VALUES (:orders_detailid, (SELECT menues_possible_extraid
                                                                            FROM menues_possible_extras
                                                                            WHERE menu_extraid = :extraid
                                                                                  AND menuid = :menuid))");

            $o_statement->bindParam(":orders_detailid", $i_order_detailid);
            $o_statement->bindParam(":menuid", $i_menuid);

            foreach($a_extraIds as $i_extraId)
            {
                $o_statement->bindParam(":extraid", $i_extraId);
                $o_statement->execute();
                $o_statement->closeCursor();
            }

            $o_statement = $this->o_db->prepare("INSERT INTO orders_details_mixed_with(orders_detailid, menuid)
                                                 VALUES (:orders_detailid, :menuid)");

            $o_statement->bindParam(":orders_detailid", $i_order_detailid);

            foreach($a_mixingIds as $i_mixingId)
            {
                $o_statement->bindParam(":menuid", $i_mixingId);
                $o_statement->execute();
                $o_statement->closeCursor();
            }

            return $i_order_detailid;
        }
        else
        {
            $o_statement = $this->o_db->prepare("INSERT INTO orders_details_special_extra(orderid, amount, single_price, extra_detail, verified )
                                                 VALUES (:orderid, :amount, 0, :extra_detail, FALSE)");

            $o_statement->bindParam(":orderid", $i_orderId);
            $o_statement->bindParam(":amount", $i_amount);
            $o_statement->bindParam(":extra_detail", $str_extra_detail);
            $o_statement->execute();

            $i_orders_details_special_extraid = $this->o_db->lastInsertId();

            return $i_orders_details_special_extraid;
        }
    }

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

    public function GetOrder($i_orderId)
    {
        $o_statement = $this->o_db->prepare("SELECT eventid,
                                                    tableid,
                                                    userid,
                                                    ordertime,
                                                    priority,
                                                    finished
                                             FROM orders
                                             WHERE orderid = :orderid");

        $o_statement->bindParam(":orderid", $i_orderId);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function GetOrderDetails($i_orderId)
    {
        $o_statement = $this->o_db->prepare("SELECT od.orders_detailid,
                                                    od.menuid,
                                                    od.amount,
                                                    od.single_price,
                                                    od.extra_detail,
                                                    od.finished,
                                                    m.name AS nameMenu,
                                                    m.price AS menuPrice,
                                                    m.availability,
                                                    mg.name AS nameGroup,
                                                    mg.menu_groupid,
                                                    mt.menu_typeid,
                                                    mt.name AS nameType,
                                                    mt.tax,
                                                    mt.allowMixing,
                                                    mps.price AS sizePrice,
                                                    ms.menu_sizeid,
                                                    ms.name AS nameSize,
                                                    ms.factor
                                             FROM orders_details od
                                             INNER JOIN menues m ON m.menuid = od.menuid
                                             INNER JOIN menu_groupes mg ON mg.menu_groupid = m.menu_groupid
                                             INNER JOIN menu_types mt ON mg.menu_typeid = mt.menu_typeid
                                             INNER JOIN orders_detail_sizes ods ON ods.orders_detailid = od.orders_detailid
                                             INNER JOIN menues_possible_sizes mps ON mps.menues_possible_sizeid = ods.menues_possible_sizeid
                                             INNER JOIN menu_sizes ms ON ms.menu_sizeid = mps.menu_sizeid
                                             WHERE od.orderid = :orderid
                                                    AND od.amount > 0");

        $o_statement->bindParam(":orderid", $i_orderId);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetExtrasOfOrderDetail($i_orders_detailId)
    {
        $o_statement = $this->o_db->prepare("SELECT me.menu_extraid,
                                                    me.eventid,
                                                    mpe.price,
                                                    mpe.menuid,
                                                    me.name,
                                                    me.availability
                                             FROM orders_detail_extras ode
                                             INNER JOIN orders_details od ON od.orders_detailid = ode.orders_detailid AND od.amount > 0
                                             INNER JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                             INNER JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                             WHERE ode.orders_detailid = :orders_detailid");

        $o_statement->bindParam(":orders_detailid", $i_orders_detailId);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetExtrasOfOrder($i_orderId)
    {
        $o_statement = $this->o_db->prepare("SELECT od.orders_detailid,
                                                    me.menu_extraid,
                                                    me.eventid,
                                                    mpe.price,
                                                    mpe.menuid,
                                                    me.name,
                                                    me.availability
                                             FROM orders_details od
                                             INNER JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                             INNER JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                             INNER JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                             WHERE od.orderid = :orderid
                                                   AND od.amount > 0");

        $o_statement->bindParam(":orderid", $i_orderId);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetMixedWithFromOrderDetail($i_orders_detailid)
    {
        $o_statement = $this->o_db->prepare("SELECT od.orders_detailid,
                                                    m.menuid,
                                                    m.menu_groupid,
                                                    m.name,
                                                    m.price,
                                                    m.availability
                                             FROM menues m
                                             INNER JOIN orders_details_mixed_with odmw ON odmw.menuid = m.menuid
                                             WHERE odmw.orders_detailid = :orders_detailid");

        $o_statement->bindParam(":orders_detailid", $i_orders_detailid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetMixedWithFromOrder($i_orderId)
    {
        $o_statement = $this->o_db->prepare("SELECT od.orders_detailid,
                                                    m.menuid,
                                                    m.menu_groupid,
                                                    m.name,
                                                    m.price,
                                                    m.availability
                                             FROM menues m
                                             INNER JOIN orders_details_mixed_with odmw ON odmw.menuid = m.menuid
                                             INNER JOIN orders_details od ON od.orders_detailid = odmw.orders_detailid
                                             WHERE od.orderid = :orderid");

        $o_statement->bindParam(":orderid", $i_orderId);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetSpecialExtrasOfOrder($i_orderId)
    {
        $o_statement = $this->o_db->prepare("SELECT odse.orders_details_special_extraid,
                                                    odse.amount,
                                                    odse.single_price,
                                                    odse.single_price_modified_by_userid,
                                                    odse.extra_detail,
                                                    odse.verified,
                                                    odse.finished
                                             FROM orders_details_special_extra odse
                                             WHERE odse.orderid = :orderid
                                                   AND odse.amount > 0");

        $o_statement->bindParam(":orderid", $i_orderId);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetSpecialExtra($i_orders_details_special_extraId)
    {
        $o_statement = $this->o_db->prepare("SELECT odse.orders_details_special_extraid,
                                                    odse.amount,
                                                    odse.single_price,
                                                    odse.single_price_modified_by_userid,
                                                    odse.extra_detail,
                                                    odse.verified,
                                                    odse.finished
                                             FROM orders_details_special_extra odse
                                             WHERE odse.orders_details_special_extraid = :orders_details_special_extraid
                                                   AND odse.amount > 0");

        $o_statement->bindParam(":orders_details_special_extraid", $i_orders_details_special_extraId);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetOrderDetailAmount($i_orders_detailId, $i_amount)
    {
        $o_statement = $this->o_db->prepare("UPDATE orders_details
                                             SET amount = :amount
                                             WHERE orders_detailid = :orders_detailid");

        $o_statement->bindParam(":orders_detailid", $i_orders_detailId);
        $o_statement->bindParam(":amount", $i_amount);
        $o_statement->execute();

        return true;
    }

    public function DeleteOrderDetail($i_orders_detailId)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM orders_details
                                             WHERE orders_detailid = :orders_detailid");

        $o_statement->bindParam(":orders_detailid", $i_orders_detailId);
        $o_statement->execute();

        return true;
    }

    public function DeleteSpecialExtra($i_orders_details_special_extraId)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM orders_details_special_extra
                                             WHERE orders_details_special_extraid = :orders_details_special_extra");

        $o_statement->bindParam(":orders_details_special_extra", $i_orders_details_special_extraId);
        $o_statement->execute();

        return true;
    }

    public function SetSpecialExtraAmount($i_orders_details_special_extraId, $i_amount)
    {
        $o_statement = $this->o_db->prepare("UPDATE orders_details_special_extra
                                             SET amount = :amount
                                             WHERE orders_details_special_extraid = :orders_details_special_extraid");

        $o_statement->bindParam(":orders_details_special_extraid", $i_orders_details_special_extraId);
        $o_statement->bindParam(":amount", $i_amount);
        $o_statement->execute();

        return true;
    }

    public function GetFullOrder($i_orderId, $b_add_status = false)
    {
        $a_order_details = $this->GetOrderDetails($i_orderId);

        $a_order_details_extras = $this->GetExtrasOfOrder($i_orderId);

        $a_order_details_mixed_with = $this->GetMixedWithFromOrder($i_orderId);

        $a_order_details_special_extras = $this->GetSpecialExtrasOfOrder($i_orderId);

        if($b_add_status)
        {
            $a_order_statuses = $this->GetOrderStatus($i_orderId);
        }

        $a_return = array();

        foreach($a_order_details as $a_order_detail)
        {
            if(!isset($a_return[$a_order_detail['menu_typeid']]))
            {
                $a_return[$a_order_detail['menu_typeid']] = array(
                    'menu_typeid' => $a_order_detail['menu_typeid'],
                    'name' => $a_order_detail['nameType'],
                    'orders' => array()
                );
            }

            $a_to_add = array(
                'backendID' => $a_order_detail['orders_detailid'],
                'menuid' => $a_order_detail['menuid'],
                'menu_groupid' => $a_order_detail['menu_groupid'],
                'name' => $a_order_detail['nameMenu'],
                'price' => $a_order_detail['single_price'],
                'availability' => $a_order_detail['availability'],
                'amount' => intval($a_order_detail['amount']),
                'open' => 0,
                'extra' => $a_order_detail['extra_detail'] ?: "",
                'sizes' => array(
                    array(
                        'menu_sizeid' => $a_order_detail['menu_sizeid'],
                        'menuid' => $a_order_detail['menuid'],
                        'name' => $a_order_detail['nameSize'],
                        'factor' => $a_order_detail['factor'],
                        'price' => $a_order_detail['sizePrice']
                    )
                ),
                'extras' => array(),
                'mixing' => array()
            );

            if($b_add_status)
            {
                $a_to_add['finished'] = $a_order_detail['finished'];

                foreach($a_order_statuses as $a_order_status)
                {
                    if($a_order_status['orders_detailid'] == $a_order_detail['orders_detailid'])
                    {
                        $a_to_add['rank'] = $a_order_status['rank'];
                        $a_to_add['handled_by_name'] = $a_order_status['handled_by_name'];
                        $a_to_add['in_progress_begin'] = $a_order_status['in_progress_begin'];
                        $a_to_add['in_progress_done'] = $a_order_status['in_progress_done'];

                        if(!isset($a_to_add['amount_recieved']))
                        {
                            $a_to_add['amount_recieved_total'] = 0;
                            $a_to_add['amount_recieved'] = array();
                        }

                        if($a_order_status['amount'] != null)
                        {
                            $a_to_add['amount_recieved_total'] += $a_order_status['amount'];
                            $a_to_add['amount_recieved'][$a_order_status['recieved']] = $a_order_status['amount'];
                        }
                    }
                }
            }

            foreach($a_order_details_extras as $a_order_detail_extra)
            {
                if($a_order_detail['orders_detailid'] == $a_order_detail_extra['orders_detailid'])
                {
                    $a_to_add['extras'][] = array(
                        'menu_extraid' => $a_order_detail_extra['menu_extraid'],
                        'menuid' => $a_order_detail_extra['menuid'],
                        'name' => $a_order_detail_extra['name'],
                        'price' => $a_order_detail_extra['price'],
                        'availability' => $a_order_detail_extra['availability']
                    );
                }
            }

            foreach($a_order_details_mixed_with as $a_order_detail_mixed_with)
            {
                if($a_order_detail['orders_detailid'] == $a_order_detail_mixed_with['orders_detailid'])
                {
                    unset($a_order_detail_mixed_with['orders_detailid']);
                    $a_to_add['mixing'][] = $a_order_detail_mixed_with;
                }
            }

            $a_return[$a_order_detail['menu_typeid']]['orders'][] = $a_to_add;
        }

        if($a_order_details_special_extras)
        {
            $a_return[0] = array(
                    'menu_typeid' => "0",
                    'name' => "Sonderwünsche",
                    'orders' => array()
                );

            foreach($a_order_details_special_extras as $a_order_detail_special_extra)
            {
                $a_to_add = array(
                    'backendID' => $a_order_detail_special_extra['orders_details_special_extraid'],
                    'menuid' => 0,
                    'menu_groupid' => 0,
                    'name' => "Sonderwunsch",
                    'price' => $a_order_detail_special_extra['single_price'] ?: 0,
                    'verified' => intval($a_order_detail_special_extra['verified']),
                    'amount' => intval($a_order_detail_special_extra['amount']),
                    'open' => 0,
                    'extra' => $a_order_detail_special_extra['extra_detail'],
                    'sizes' => array(
                        array() //-- gets filled up by Backbone defaults
                    ),
                    'extras' => array(),
                    'mixing' => array()
                );

                if($b_add_status)
                {
                    $a_to_add['finished'] = $a_order_detail_special_extra['finished'];

                    foreach($a_order_statuses as $a_order_status)
                    {
                        if($a_order_status['orders_details_special_extraid'] == $a_order_detail_special_extra['orders_details_special_extraid'])
                        {
                            $a_to_add['rank'] = $a_order_status['rank'];
                            $a_to_add['handled_by_name'] = $a_order_status['handled_by_name'];
                            $a_to_add['in_progress_begin'] = $a_order_status['in_progress_begin'];
                            $a_to_add['in_progress_done'] = $a_order_status['in_progress_done'];

                            if(!isset($a_to_add['amount_recieved']))
                            {
                                $a_to_add['amount_recieved_total'] = 0;
                                $a_to_add['amount_recieved'] = array();
                            }

                            if($a_order_status['amount'] != null)
                            {
                                $a_to_add['amount_recieved_total'] += $a_order_status['amount'];
                                $a_to_add['amount_recieved'][$a_order_status['recieved']] = $a_order_status['amount'];
                            }
                        }
                    }
                }

                $a_return[0]['orders'][] = $a_to_add;
            }
        }

        return array_values($a_return);
    }

    public function CancelOrder($i_orderid)
    {
        $o_statement = $this->o_db->prepare("UPDATE orders_details
                                             SET amount = 0,
                                                 finished = NOW()
                                             WHERE orderid = :orderid");

        $o_statement->bindParam(":orderid", $i_orderid);
        $o_statement->execute();

        return true;
    }

    public function CheckIfOrderDone($i_orderid)
    {
        $o_statement = $this->o_db->prepare("SELECT
                                                (SELECT count(*)
                                                    FROM orders_details od
                                                    WHERE od.orderid = :orderid
                                                          AND od.finished IS NULL) AS count_unfinished,
                                                (SELECT count(*)
                                                    FROM orders_details_open odo
                                                    WHERE odo.orderid = :orderid) AS count_unpayed");

        $o_statement->bindParam(":orderid", $i_orderid);
        $o_statement->execute();

        $a_status = $o_statement->fetch();

        if($a_status['count_unfinished'] == 0 &&
           $a_status['count_unpayed'] == 0 )
        {
            $o_statement = $this->o_db->prepare("UPDATE orders
                                                 SET finished = NOW(),
                                                     priority = 0
                                                 WHERE orderid = :orderid");

            $o_statement->bindParam(":orderid", $i_orderid);
            $o_statement->execute();

            $o_statement = $this->o_db->prepare("UPDATE orders
                                                 SET priority = priority - 1
                                                 WHERE finished IS NULL");

            $o_statement->execute();
        }
    }

    public function GetOrderInfo($i_orderid)
    {
        $str_query = "SELECT o.orderid,
                             o.tableid,
                             t.name AS table_name,
                             o.ordertime,
                             CONCAT(u.firstname, ' ', u.lastname) AS user,
                             o.priority,
                             o.finished,
                             IF(oip.begin IS NULL, 1, IF(oip.done IS NULL, 2, 3)) AS status,

                             (SELECT count(*)
                                 FROM orders_details_open odo
                                 WHERE odo.orderid = o.orderid) +
                                (SELECT count(*)
                                 FROM orders_details_special_extra_open odseo
                                 WHERE odseo.orderid = o.orderid) AS open,

                             ((SELECT COALESCE(SUM(iod.amount * od.single_price), 0)
                               FROM orders_details od
                               INNER JOIN invoices_orders_details iod ON od.orders_detailid = iod.orders_detailid
                               WHERE od.orderid = o.orderid)
                               +
                               (SELECT COALESCE(SUM(iodse.amount * odse.single_price), 0)
                               FROM orders_details_special_extra odse
                               INNER JOIN invoices_orders_details_special_extra iodse ON iodse.orders_details_special_extraid = iodse.orders_details_special_extraid
                               WHERE odse.orderid = o.orderid)) AS amountPayed,

                             ( SELECT `date`
                               FROM (SELECT i.`date`
                                   FROM invoices i
                                   INNER JOIN invoices_orders_details iod ON iod.invoiceid = i.invoiceid
                                   INNER JOIN orders_details od2 ON od2.orders_detailid = iod.orders_detailid
                                   WHERE od2.orderid = :orderid
                                   UNION
                                   SELECT i.`date`
                                   FROM invoices i
                                   INNER JOIN invoices_orders_details_special_extra iodse ON iodse.invoiceid = i.invoiceid
                                   INNER JOIN orders_details_special_extra odse2 ON odse2.orders_details_special_extraid = iodse.orders_details_special_extraid
                                   WHERE odse2.orderid = :orderid) AS result
                               ORDER BY `date` DESC LIMIT 1)  AS last_paydate

                      FROM orders o
                      INNER JOIN tables t ON t.tableid = o.tableid
                      INNER JOIN users u ON u.userid = o.userid
                      LEFT JOIN orders_in_progress oip ON oip.orderid = o.orderid
                      WHERE o.orderid = :orderid";

        $o_statement = $this->o_db->prepare($str_query);
        $o_statement->bindParam(":orderid", $i_orderid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function GetOrderStatus($i_orderid)
    {
        $this->o_db->query("CALL open_orders_priority();");
        $o_statement = $this->o_db->prepare("SELECT toop.orders_detailid,
                                                    toop.orders_details_special_extraid,
                                                    toop.rank,
                                                    oip.begin AS in_progress_begin,
                                                    oip.done AS in_progress_done,
                                                    CONCAT(u.firstname, ' ', u.lastname) AS handled_by_name,
                                                    IF(toop.orders_detailid, oipr.amount, oeipr.amount) AS amount,
                                                    IF(toop.orders_detailid, dgo1.date, dgo2.date) AS recieved
                                             FROM tmp_open_orders_priority toop
                                             LEFT JOIN orders_in_progress oip ON oip.orderid = toop.orderid
                                             LEFT JOIN users u ON u.userid = oip.userid
                                             LEFT JOIN orders_in_progress_recieved oipr ON oipr.orders_in_progressid = oip.orders_in_progressid AND oipr.orders_detailid = toop.orders_detailid
                                             LEFT JOIN distribution_giving_out dgo1 ON dgo1.distribution_giving_outid = oipr.distribution_giving_outid
                                             LEFT JOIN orders_extras_in_progress_recieved oeipr ON oeipr.orders_in_progressid = oip.orders_in_progressid AND oeipr.orders_details_special_extraid = toop.orders_details_special_extraid
                                             LEFT JOIN distribution_giving_out dgo2 ON dgo2.distribution_giving_outid = oeipr.distribution_giving_outid
                                             WHERE toop.orderid = :orderid");

        $o_statement->bindParam(":orderid", $i_orderid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }
}