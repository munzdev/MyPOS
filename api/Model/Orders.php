<?php
namespace Model;

class Orders
{
	private $o_db;

	public function __construct(\PDO $o_db)
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
                                                        (SELECT SUM(od2.amount_payed)
                                                            FROM orders_details od2
                                                            WHERE od2.orderid = o.orderid ) AS payed
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

            $a_orders = $o_statement->fetchAll(\PDO::FETCH_ASSOC);

            return $a_orders;
	}

        public function AddOrder($i_eventid, $i_userid, $i_tableId)
        {
            $o_statement = $this->o_db->prepare("INSERT INTO orders(eventid, tableid, userid, ordertime, priority )
                                                 VALUES(:eventid, :tableid, :userid, NOW(), (SELECT MAX(priority) + 1
                                                                                             FROM orders
                                                                                             WHERE eventid = :eventid))");

            $o_statement->bindparam(":eventid", $i_eventid);
            $o_statement->bindparam(":tableid", $i_tableId);
            $o_statement->bindparam(":userid", $i_userid);
            $o_statement->execute();

            return $o_db->lastInsertId();
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

            $o_statement->bindparam(":menuid", $i_menuid);
            $o_statement->bindparam(":extraIds", $a_extraIds);
            $o_statement->bindparam(":sizeid", $i_sizeid);
            $o_statement->execute();

            $a_price = $o_statement->fetch(\PDO::FETCH_ASSOC);

            $i_price = $a_price['basePrice'];

            if(!empty($i_mixingId))
            {
                foreach ($a_mixingIds as $i_mixingId)
                {
                    $o_statement = $this->o_db->prepare("SELECT m.price + mps.price
                                                         FROM menues m
                                                         INNER JOIN menues_possible_sizes mps ON mps.menues_menuid = m.menuid
                                                                                              AND mps.menu_sizeid = :sizeid
                                                         WHERE m.menuid  = :mixingId ");

                    $o_statement->bindparam(":mixingId", $i_mixingId);
                    $o_statement->bindparam(":sizeid", $i_sizeid);
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

                    $o_statement->bindparam(":menuid", $i_mixingId);
                    $o_statement->bindparam(":sizeid", $i_sizeid);
                    $o_statement->execute();

                    $i_price += $o_statement->fetchColumn();
                }

                $i_price = $i_price / (count($a_mixingIds) + 1);
            }

            $i_price += $a_price['extraPrice'];

            $o_statement = $this->o_db->prepare("INSERT INTO orders_details_(orderid, menuid, amount, single_price, extra_detail, extra_detail_verified )
                                                 VALUES (:orderid, :menuid, :amount, :single_price, :extra_detail, :extra_detail_verified)");

            $o_statement->bindparam(":orderid", $i_orderId);
            $o_statement->bindparam(":menuid", $i_menuid);
            $o_statement->bindparam(":amount", $i_amount);
            $o_statement->bindparam(":single_price", $i_price);
            $o_statement->bindparam(":extra_detail", $str_extra_detail);
            $o_statement->bindparam(":extra_detail_verified", $i_menuid != 0);
            $o_statement->execute();

            $i_order_detailid = $this->o_db->lastInsertId();

            $o_statement = $this->o_db->prepare("INSERT INTO orders_detail_sizes(orders_detailid, menues_possible_sizeid)
                                                 VALUES (:orders_detailid, :sizeid)");

            $o_statement->bindparam(":orders_detailid", $i_order_detailid);
            $o_statement->bindparam(":sizeid", $i_sizeid);
            $o_statement->execute();

            $o_statement = $this->o_db->prepare("INSERT INTO orders_detail_extras(orders_detailid, menues_possible_extraid)
                                                 VALUES (:orders_detailid, :extraid)");

            $o_statement->bindparam(":orders_detailid", $i_order_detailid);

            foreach($a_extraIds as $i_extraId)
            {
                $o_statement->bindparam(":extraid", $i_extraId);
                $o_statement->execute();
                $o_statement->closeCursor();
            }

            $o_statement = $this->o_db->prepare("INSERT INTO orders_details_mixed_with(orders_detailid, menues_menuid)
                                                 VALUES (:orders_detailid, :menuid)");

            $o_statement->bindparam(":orders_detailid", $i_order_detailid);

            foreach($a_mixingIds as $i_mixingId)
            {
                $o_statement->bindparam(":menuid", $i_mixingId);
                $o_statement->execute();
                $o_statement->closeCursor();
            }

            return $i_order_detailid;
        }
}