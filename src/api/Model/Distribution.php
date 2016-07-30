<?php
namespace Model;

use PDO;
use MyPOS;

class Distribution
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function GetOrder($i_eventid, $i_userid, $b_assist)
    {
        //-- Fetch allready started order to handle, whoes not finished yet (like page reloaded or the status of a product has changed back to available)
        $a_orders_in_progressid = $this->GetUsersOpenInProgressOrders($i_userid, $i_eventid);

        if(empty($a_orders_in_progressid)) //-- if no existing progress order found, take a new order from priority list
        {
            $this->o_db->query("CALL open_orders_priority()");

            $a_orders_to_handle = $this->GetDistributionPlaceNextOrders($i_userid, $i_eventid);

            //-- Secondly try to find an order, which belongs to an other distribution place but has the same menu_groupid and can also be handeled by the users one
            //-- this lets ordes be done faster
            if(!$a_orders_to_handle && $b_assist)
            {
                $a_orders_to_handle = $this->GetAnyDistributionPlaceNextOrders($i_userid, $i_eventid);
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

                $a_orders_in_progressid[] = $this->AddInProgress($i_userid, $i_orderid, $a_order_to_handle['menu_groupid']);
            }
        }

        return $this->GetOrderDetailsOfProgessIds($a_orders_in_progressid);
    }

    public function GetOrderDetailsOfProgessIds($a_orders_in_progressid)
    {
        if(empty($a_orders_in_progressid))
            return;

        $o_statement = $this->o_db->prepare("SELECT t.orders_detailid,
                                                    t.amount - IFNULL(t.amount_recieved, 0) AS amount,
                                                    t.name,
                                                    t.menu_sizeid,
                                                    t.sizeName,
                                                    t.extra_detail,
                                                    t.extrasName,
                                                    t.mixedWithName
                                             FROM ( SELECT od.orders_detailid,
                                                            od.amount,
                                                            m.name,
                                                            ms.menu_sizeid,
                                                            ms.name AS sizeName,
                                                            od.extra_detail,
                                                            SUM(oipr.amount) AS amount_recieved,
                                                            GROUP_CONCAT(me.name SEPARATOR ', ') AS extrasName,
                                                            GROUP_CONCAT(m2.name SEPARATOR ', ') AS mixedWithName,
                                                            GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                            GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                     FROM orders_in_progress oip
                                                     INNER JOIN orders o ON o.orderid = oip.orderid
                                                     INNER JOIN orders_details od ON od.orderid = o.orderid AND od.finished IS NULL
                                                     INNER JOIN menues m ON m.menuid = od.menuid AND m.menu_groupid = oip.menu_groupid
                                                     INNER JOIN orders_detail_sizes ods ON ods.orders_detailid = od.orders_detailid
                                                     INNER JOIN menues_possible_sizes mps ON mps.menues_possible_sizeid = ods.menues_possible_sizeid
                                                     INNER JOIN menu_sizes ms ON ms.menu_sizeid = mps.menu_sizeid
                                                     LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                                     LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                                     LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                                     LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                                     LEFT JOIN menues m2 ON m2.menuid = odmw.menuid
                                                     LEFT JOIN orders_in_progress_recieved oipr ON oipr.orders_detailid = od.orders_detailid
                                                     WHERE oip.orders_in_progressid IN (:orders_in_progressids)
                                                           AND m.availability = :availability
                                                     GROUP BY od.orders_detailid) t
                                             WHERE (t.extrasAvailability IS NULL
                                                    OR (
                                                        t.extrasAvailability NOT LIKE :delayed
                                                        AND t.extrasAvailability NOT LIKE :out_of_order
                                                        )
                                                    )
                                                   AND
                                                   (t.mixedWithAvailability IS NULL
                                                    OR (
                                                        t.mixedWithAvailability NOT LIKE :delayed
                                                        AND t.mixedWithAvailability NOT LIKE :out_of_order
                                                        )
                                                    )");

        $o_statement->bindValue(":orders_in_progressids", implode(",", $a_orders_in_progressid), PDO::PARAM_INT);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        $a_order_details = $o_statement->fetchAll();

        $o_statement = $this->o_db->prepare("SELECT odse.orders_details_special_extraid,
                                                    odse.amount,
                                                    odse.extra_detail,
                                                    SUM(oeipr.amount) AS amount_recieved
                                              FROM orders_in_progress oip
                                              INNER JOIN orders o ON o.orderid = oip.orderid
                                              INNER JOIN orders_details_special_extra odse ON odse.orderid = o.orderid
                                                                                              AND odse.finished IS NULL
                                                                                              AND odse.menu_groupid = oip.menu_groupid
                                              LEFT JOIN orders_extras_in_progress_recieved oeipr ON oeipr.orders_details_special_extraid = odse.orders_details_special_extraid
                                              WHERE oip.orders_in_progressid IN (:orders_in_progressids)
                                                    AND odse.availability = :availability
                                              GROUP BY odse.orders_details_special_extraid");

        $o_statement->bindValue(":orders_in_progressids", implode(",", $a_orders_in_progressid), PDO::PARAM_INT);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->execute();

        $a_orders_details_special_extra = $o_statement->fetchAll();
        $a_tmp = array();

        foreach($a_orders_details_special_extra as $a_order_detail_special_extra)
        {
            $a_tmp[] = array('orders_details_special_extraid' => $a_order_detail_special_extra['orders_details_special_extraid'],
                             'amount' => $a_order_detail_special_extra['amount'] - $a_order_detail_special_extra['amount_recieved'],
                             'extra_detail' => $a_order_detail_special_extra['extra_detail']);

        }

        return array('orders_details' => $a_order_details,
                     'order_details_special_extra' => $a_tmp);
    }

    public function AddInProgress($i_userid, $i_orderid, $i_menu_groupid)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO orders_in_progress (orderid, userid, menu_groupid, begin)
                                             VALUES (:orderid, :userid, :menu_groupid, NOW())");

        $o_statement->bindParam(":orderid", $i_orderid);
        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":menu_groupid", $i_menu_groupid);
        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function GetUsersOpenInProgressOrders($i_userid, $i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT t.orders_in_progressid,
                                                    t.orderid
                                             FROM ( SELECT oip.orders_in_progressid,
                                                           oip.orderid,
                                                           o.priority,
                                                           GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                           GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                    FROM orders_in_progress oip
                                                    INNER JOIN orders o ON o.orderid = oip.orderid
                                                    LEFT JOIN orders_details od ON od.orderid = o.orderid
                                                    LEFT JOIN menues m ON m.menuid = od.menuid
                                                    LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                                    LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                                    LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                                    LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                                    LEFT JOIN menues m2 ON m2.menuid = odmw.menuid
                                                    LEFT JOIN orders_details_special_extra odse ON (odse.orderid = o.orderid
                                                                                                    AND odse.verified = 1
                                                                                                    AND odse.menu_groupid IS NOT NULL
                                                                                                    AND odse.availability = :availability)
                                                    WHERE oip.userid = :userid
                                                          AND oip.done IS NULL
                                                          AND o.eventid = :eventid
                                                          AND (m.availability = :availability OR odse.availability = :availability)
                                                    GROUP BY od.orders_detailid) t
                                             WHERE (t.extrasAvailability IS NULL
                                                    OR (
                                                        t.extrasAvailability NOT LIKE :delayed
                                                        AND t.extrasAvailability NOT LIKE :out_of_order
                                                        )
                                                    )
                                                   AND
                                                   (t.mixedWithAvailability IS NULL
                                                    OR (
                                                        t.mixedWithAvailability NOT LIKE :delayed
                                                        AND t.mixedWithAvailability NOT LIKE :out_of_order
                                                        )
                                                    )
                                             ORDER BY t.priority ASC");

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        $a_in_progress_orders = $o_statement->fetchAll();

        $a_tmp = array();

        if($a_in_progress_orders)
        {
            $i_orderid = null;
            foreach($a_in_progress_orders as $a_in_progress_order)
            {
                if($i_orderid === null)
                    $i_orderid = $a_in_progress_order['orderid'];

                if($i_orderid != $a_in_progress_order['orderid'])
                    break;

                $a_tmp[] = $a_in_progress_order['orders_in_progressid'];
            }
        }

        return $a_tmp;
    }

    public function GetAnyDistributionPlaceNextOrders($i_userid, $i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT t.orderid,
                                                    t.menu_groupid
                                             FROM (SELECT toop.orderid,
                                                          toop.menu_groupid,
                                                          toop.rank,
                                                          GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                          GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                   FROM tmp_open_orders_priority toop
                                                   LEFT OUTER JOIN orders_in_progress oip ON oip.orderid = toop.orderid
                                                                                             AND toop.menu_groupid = oip.menu_groupid
                                                   INNER JOIN distributions_places_groupes dpg ON dpg.menu_groupid = toop.menu_groupid
                                                   INNER JOIN distributions_places dp ON dp.distributions_placeid = dpg.distributions_placeid
                                                   INNER JOIN distributions_places_users dpu ON dpu.distributions_placeid = dpg.distributions_placeid
                                                   INNER JOIN orders o ON o.orderid = toop.orderid
                                                   LEFT JOIN orders_details od ON od.orders_detailid = toop.orders_detailid
                                                   LEFT JOIN menues m ON m.menuid = od.menuid
                                                   LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                                   LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                                   LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                                   LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                                   LEFT JOIN menues m2 ON m2.menuid = odmw.menuid
                                                   LEFT JOIN orders_details_special_extra odse ON (odse.orderid = o.orderid
                                                                                                   AND odse.verified = 1
                                                                                                   AND odse.menu_groupid = toop.menu_groupid
                                                                                                   AND odse.availability = :availability)
                                                   WHERE dpu.userid = :userid
                                                         AND
                                                         dp.eventid = :eventid
                                                         AND (m.availability = :availability OR odse.availability = :availability)
                                                   GROUP BY od.orders_detailid) t
                                             WHERE (t.extrasAvailability IS NULL
                                                OR (
                                                    t.extrasAvailability NOT LIKE :delayed
                                                    AND t.extrasAvailability NOT LIKE :out_of_order
                                                    )
                                                )
                                               AND
                                               (t.mixedWithAvailability IS NULL
                                                OR (
                                                    t.mixedWithAvailability NOT LIKE :delayed
                                                    AND t.mixedWithAvailability NOT LIKE :out_of_order
                                                    )
                                                )
                                             ORDER BY t.rank ASC");

        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        $a_orders_to_handle = $o_statement->fetchAll();
    }

    public function GetDistributionPlaceNextOrders($i_userid, $i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT t.orderid,
                                                    t.menu_groupid
                                             FROM (SELECT toop.orderid,
                                                          toop.menu_groupid,
                                                          toop.rank,
                                                          GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                          GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                   FROM tmp_open_orders_priority toop
                                                   LEFT OUTER JOIN orders_in_progress oip ON oip.orderid = toop.orderid
                                                                                     AND toop.menu_groupid = oip.menu_groupid
                                                   INNER JOIN distributions_places_groupes dpg ON dpg.menu_groupid = toop.menu_groupid
                                                   INNER JOIN distributions_places dp ON dp.distributions_placeid = dpg.distributions_placeid
                                                   INNER JOIN distributions_places_users dpu ON dpu.distributions_placeid = dpg.distributions_placeid
                                                   INNER JOIN distributions_places_tables dpt ON dpt.distributions_placeid = dpg.distributions_placeid
                                                                                                 AND dpt.menu_groupid = toop.menu_groupid
                                                   INNER JOIN orders o ON o.orderid = toop.orderid
                                                                          AND o.tableid = dpt.tableid
                                                   LEFT JOIN orders_details od ON od.orders_detailid = toop.orders_detailid
                                                   LEFT JOIN menues m ON m.menuid = od.menuid
                                                   LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                                   LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                                   LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                                   LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                                   LEFT JOIN menues m2 ON m2.menuid = odmw.menuid
                                                   LEFT JOIN orders_details_special_extra odse ON (odse.orderid = o.orderid
                                                                                                   AND odse.verified = 1
                                                                                                   AND odse.menu_groupid = toop.menu_groupid)
                                                   WHERE dpu.userid = :userid
                                                         AND
                                                         dp.eventid = :eventid
                                                         AND (m.availability = :availability OR odse.availability = :availability)
                                                   GROUP BY od.orders_detailid) t
                                             WHERE (t.extrasAvailability IS NULL
                                                OR (
                                                    t.extrasAvailability NOT LIKE :delayed
                                                    AND t.extrasAvailability NOT LIKE :out_of_order
                                                    )
                                                )
                                               AND
                                               (t.mixedWithAvailability IS NULL
                                                OR (
                                                    t.mixedWithAvailability NOT LIKE :delayed
                                                    AND t.mixedWithAvailability NOT LIKE :out_of_order
                                                    )
                                                )
                                             ORDER BY t.rank ASC");

        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }
}