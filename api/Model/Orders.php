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
        $o_statement = $this->o_db->prepare("SELECT o.orderid,
                                                    o.tableid,
                                                    t.name AS table_name,
                                                    o.ordertime,
                                                    o.priority,
                                                    IF(oip.done IS NULL, 1, IF(oip.done = FALSE, 2, 3)) AS status,
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
                                                       AND o.finished = :finished
                                             ORDER BY o.priority ASC");

        $o_statement->execute(array(':eventid' => $i_eventid,
                                    ':userid' => $i_userid,
                                    ':finished' => $b_finished
        ));

        $a_orders = $o_statement->fetchAll(PDO::FETCH_ASSOC);

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
                                              INNER JOIN menues_possible_sizes mps ON mps.menu_sizeid = :sizeid AND mps.menues_menuid = :menuid
                                              WHERE m.menuid = :menuid) AS basePrice,
                                             (SELECT SUM(mpe.price)
                                              FROM menues_possible_extras mpe
                                              WHERE mpe.menu_extraid IN(:extraIds)
                                                    AND mpe.menues_menuid = :menuid) AS extraPrice");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindValue(":extraIds", implode(',', $a_extraIds));
        $o_statement->bindParam(":sizeid", $i_sizeid);
        $o_statement->execute();

        $a_price = $o_statement->fetch(PDO::FETCH_ASSOC);

        $i_price = $a_price['basePrice'];

        if(!empty($a_mixingIds))
        {
            foreach ($a_mixingIds as $i_mixingId)
            {
                $o_statement = $this->o_db->prepare("SELECT m.price + mps.price
                                                     FROM menues m
                                                     INNER JOIN menues_possible_sizes mps ON mps.menues_menuid = m.menuid
                                                                                          AND mps.menu_sizeid = :sizeid
                                                     WHERE m.menuid  = :mixingId ");

                $o_statement->bindParam(":mixingId", $i_mixingId);
                $o_statement->bindParam(":sizeid", $i_sizeid);
                $o_statement->execute();

                $i_mixingPrice = $o_statement->fetchColumn();

                if($i_mixingPrice !== null)
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
                                                     INNER JOIN menues_possible_sizes mps ON mps.menues_menuid = :menuid
                                                                                          AND mps.menu_sizeid = ms.menu_sizeid
                                                     INNER JOIN menues m ON m.menuid = mps.menues_menuid
                                                     LIMIT 1");

                $o_statement->bindParam(":menuid", $i_mixingId);
                $o_statement->bindParam(":sizeid", $i_sizeid);
                $o_statement->execute();

                $i_price += $o_statement->fetchColumn();
            }

            $i_price = $i_price / (count($a_mixingIds) + 1);
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
                                                 VALUES (:orders_detailid, :sizeid)");

            $o_statement->bindParam(":orders_detailid", $i_order_detailid);
            $o_statement->bindParam(":sizeid", $i_sizeid);
            $o_statement->execute();

            $o_statement = $this->o_db->prepare("INSERT INTO orders_detail_extras(orders_detailid, menues_possible_extraid)
                                                 VALUES (:orders_detailid, :extraid)");

            $o_statement->bindParam(":orders_detailid", $i_order_detailid);

            foreach($a_extraIds as $i_extraId)
            {
                $o_statement->bindParam(":extraid", $i_extraId);
                $o_statement->execute();
                $o_statement->closeCursor();
            }

            $o_statement = $this->o_db->prepare("INSERT INTO orders_details_mixed_with(orders_detailid, menues_menuid)
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
                            GROUP_CONCAT(me.name ORDER BY me.name SEPARATOR ', ') AS selectedExtras
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

        $a_result['orders'] = $o_statement->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($str_tableNr && $b_merge))
        {
            $a_order_verify = array();

            foreach($a_result['orders'] as $a_order)
            {
                $str_index = "{$a_order['menuid']}-{$a_order['single_price']}-{$a_order['extra_detail']}-{$a_order['sizeName']}-{$a_order['selectedExtras']}";

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

        $a_result['extras'] = $o_statement->fetchAll(PDO::FETCH_ASSOC);

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
}