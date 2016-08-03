<?php
namespace Model;

use PDO;
use MyPOS;

class Distribution
{
    private $o_db;

    private static $b_tmp_table_created = false;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function CallOpenOrdersPriority()
    {
        if(!self::$b_tmp_table_created)
        {
            $this->o_db->query("CALL open_orders_priority()");
            self::$b_tmp_table_created = true;
        }
    }

    public function GetOrderInfoFromProgressIDs($a_orders_in_progressids)
    {
        if(empty($a_orders_in_progressids))
            return;

        $str_orders_in_progressids = implode(',', array_filter( $a_orders_in_progressids, 'ctype_digit'));

        $o_statement = $this->o_db->prepare("SELECT o.orderid,
                                                    t.name AS tableNr,
                                                    o.ordertime,
                                                    CONCAT(u.firstname, ' ', u.lastname) AS waitress,
                                                    dpu.events_printerid
                                             FROM orders_in_progress oip
                                             INNER JOIN orders o ON o.orderid = oip.orderid
                                             INNER JOIN tables t ON t.tableid = o.tableid
                                             INNER JOIN distributions_places_users dpu ON dpu.userid = oip.userid
                                             INNER JOIN users u ON u.userid = o.userid
                                             WHERE oip.orders_in_progressid IN ($str_orders_in_progressids)
                                             LIMIT 1");

        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function GetOrderDetailsOfProgessIds($a_orders_in_progressids)
    {
        if(empty($a_orders_in_progressids))
            return;

        $str_orders_in_progressids = implode(',', array_filter( $a_orders_in_progressids, 'ctype_digit'));

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
                                                            (SELECT SUM(oipr.amount)
                                                             FROM orders_in_progress_recieved oipr
                                                             WHERE oipr.orders_detailid = od.orders_detailid )AS amount_recieved,
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
                                                     WHERE oip.orders_in_progressid IN ($str_orders_in_progressids)
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

        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        $a_order_details = $o_statement->fetchAll();

        $o_statement = $this->o_db->prepare("SELECT odse.orders_details_special_extraid,
                                                    odse.amount,
                                                    odse.extra_detail,
                                                    SUM(oeipr.amount) AS amount_recieved,
                                                    odse.availability_amount
                                              FROM orders_in_progress oip
                                              INNER JOIN orders o ON o.orderid = oip.orderid
                                              INNER JOIN orders_details_special_extra odse ON odse.orderid = o.orderid
                                                                                              AND odse.finished IS NULL
                                                                                              AND odse.menu_groupid = oip.menu_groupid
                                              LEFT JOIN orders_extras_in_progress_recieved oeipr ON oeipr.orders_details_special_extraid = odse.orders_details_special_extraid
                                              WHERE oip.orders_in_progressid IN ($str_orders_in_progressids)
                                                    AND odse.availability = :availability
                                              GROUP BY odse.orders_details_special_extraid");

        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->execute();

        $a_orders_details_special_extra = $o_statement->fetchAll();
        $a_tmp = array();

        foreach($a_orders_details_special_extra as $a_order_detail_special_extra)
        {
            $a_tmp[] = array('orders_details_special_extraid' => $a_order_detail_special_extra['orders_details_special_extraid'],
                             'amount' => $a_order_detail_special_extra['amount'] - $a_order_detail_special_extra['amount_recieved'],
                             'extra_detail' => $a_order_detail_special_extra['extra_detail'],
                             'availability_amount' => $a_order_detail_special_extra['availability_amount']);
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
        $o_statement = $this->o_db->prepare("SELECT DISTINCT t.orders_in_progressid,
                                                    t.orderid
                                             FROM ( SELECT oip.orders_in_progressid,
                                                           oip.orderid,
                                                           o.priority,
                                                           GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                           GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                    FROM orders_in_progress oip
                                                    INNER JOIN orders o ON o.orderid = oip.orderid
                                                    INNER JOIN orders_details od ON od.orderid = o.orderid
                                                    INNER JOIN menues m ON m.menuid = od.menuid AND m.menu_groupid = oip.menu_groupid
                                                    LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                                    LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                                    LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                                    LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                                    LEFT JOIN menues m2 ON m2.menuid = odmw.menuid
                                                    WHERE oip.userid = :userid
                                                          AND oip.done IS NULL
                                                          AND o.eventid = :eventid
                                                          AND m.availability = :availability
                                                    GROUP BY oip.orders_in_progressid, od.orders_detailid
                                                    UNION
                                                    SELECT oip.orders_in_progressid,
                                                           oip.orderid,
                                                           o.priority,
                                                           null as extrasAvailability,
                                                           null as mixedWithAvailability
                                                    FROM orders_in_progress oip
                                                    INNER JOIN orders o ON o.orderid = oip.orderid
                                                    INNER JOIN orders_details_special_extra odse ON (odse.orderid = o.orderid
                                                                                                    AND odse.verified = 1
                                                                                                    AND odse.menu_groupid = oip.menu_groupid)
                                                    WHERE oip.userid = :userid
                                                          AND oip.done IS NULL
                                                          AND o.eventid = :eventid
                                                          AND odse.availability = :availability) t
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
        $this->CallOpenOrdersPriority();

        $o_statement = $this->o_db->prepare("SELECT t.orderid,
                                                    t.menu_groupid
                                             FROM (SELECT toop.orderid,
                                                          toop.menu_groupid,
                                                          toop.rank,
                                                          GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                          GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                   FROM tmp_open_orders_priority toop
                                                   LEFT JOIN orders_in_progress oip ON oip.orderid = toop.orderid
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
                                                   WHERE oip.orders_in_progressid IS NULL
                                                         AND
                                                         dpu.userid = :userid
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
                                             GROUP BY t.orderid, t.menu_groupid
                                             ORDER BY t.rank ASC");

        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetDistributionPlaceNextOrders($i_userid, $i_eventid)
    {
        $this->CallOpenOrdersPriority();

        $o_statement = $this->o_db->prepare("SELECT t.orderid,
                                                    t.menu_groupid
                                             FROM (SELECT toop.orderid,
                                                          toop.menu_groupid,
                                                          toop.rank,
                                                          GROUP_CONCAT(me.availability SEPARATOR ',') AS extrasAvailability,
                                                          GROUP_CONCAT(m2.availability SEPARATOR ',') AS mixedWithAvailability
                                                   FROM tmp_open_orders_priority toop
                                                   LEFT JOIN orders_in_progress oip ON oip.orderid = toop.orderid
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
                                                   WHERE oip.orders_in_progressid IS NULL
                                                         AND
                                                         dpu.userid = :userid
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
                                             GROUP BY t.orderid, t.menu_groupid
                                             ORDER BY t.rank ASC");

        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindValue(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindValue(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetOrderInfo($a_order_ids)
    {
        if(empty($a_order_ids))
            return;

        $str_orderids = implode(',', array_filter( $a_order_ids, 'ctype_digit'));

        $o_statement = $this->o_db->prepare("SELECT t.name AS tableNr,
                                                    o.ordertime,
                                                    IFNULL(SUM(od.amount), 0) + IFNULL(SUM(odse.amount), 0) AS amount
                                             FROM orders o
                                             INNER JOIN tables t ON t.tableid = o.tableid
                                             LEFT JOIN orders_details od ON od.orderid = o.orderid
                                             LEFT JOIN orders_details_special_extra odse ON odse.orderid = o.orderid
                                             WHERE o.orderid IN ($str_orderids)
                                             GROUP BY o.orderid");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetOrdersDone($i_userid, $i_eventid, $i_minutes)
    {
        $o_statement = $this->o_db->prepare("SELECT COUNT(*)
                                             FROM orders_in_progress oip
                                             INNER JOIN orders o ON o.orderid = oip.orderid
                                             LEFT JOIN orders_in_progress_recieved oipr ON oipr.orders_in_progressid = oip.orders_in_progressid
                                             LEFT JOIN distributions_giving_outs dgo1 ON dgo1.distributions_giving_outid = oipr.distributions_giving_outid
                                             LEFT JOIN orders_extras_in_progress_recieved oeipr ON oeipr.orders_in_progressid = oip.orders_in_progressid
                                             LEFT JOIN distributions_giving_outs dgo2 ON dgo2.distributions_giving_outid = oeipr.distributions_giving_outid
                                             WHERE oip.userid = :userid
                                                   AND o.eventid = :eventid
                                                   AND
                                                   (
                                                        dgo1.date >= (NOW() - INTERVAL :minutes MINUTE)
                                                        OR
                                                        dgo2.date >= (NOW() - INTERVAL :minutes MINUTE)
                                                   )
                                             GROUP BY oip.orderid");

        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindParam(":minutes", $i_minutes);
        $o_statement->execute();

        $i_amount = $o_statement->fetchColumn();

        if(!$i_amount)
            $i_amount = 0;

        return $i_amount;
    }

    public function GetOrdersNew($a_order_ids, $i_minutes)
    {
        if(empty($a_order_ids))
            return 0;

        $str_orderids = implode(',', array_filter( $a_order_ids, 'ctype_digit'));

        $o_statement = $this->o_db->prepare("SELECT COUNT(*)
                                             FROM orders
                                             WHERE orderid IN ($str_orderids)
                                                   AND ordertime >= (NOW() - INTERVAL :minutes MINUTE)");

        $o_statement->bindParam(":minutes", $i_minutes);
        $o_statement->execute();

        $i_amount = $o_statement->fetchColumn();

        if(!$i_amount)
            $i_amount = 0;

        return $i_amount;
    }

    public function GetAvailabilitySpecialExtras($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT odse.orders_details_special_extraid,
                                                    odse.extra_detail,
                                                    odse.availability,
                                                    odse.availability_amount
                                             FROM orders_details_special_extra odse
                                             INNER JOIN orders o ON o.orderid = odse.orderid
                                             WHERE o.eventid = :eventid
                                                   AND odse.finished IS NULL
                                                   AND odse.verified = 1
                                                   AND
                                                   (
                                                        odse.availability <> :availability
                                                        OR odse.availability_amount IS NOT NULL
                                                   )");

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindValue(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function AddGivingOut()
    {
        $o_statement = $this->o_db->prepare("INSERT INTO distributions_giving_outs(`date`)
                                                         VALUES (NOW())");

        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function AddOrdersInProgressRecieved($i_distributions_giving_outid, $a_orders_details)
    {
        if(empty($i_distributions_giving_outid) || empty($a_orders_details))
            return;

        foreach($a_orders_details as $a_order_detail)
        {
            $o_statement = $this->o_db->prepare("SELECT oip.orders_in_progressid,
                                                        m.menuid,
                                                        m.availability_amount,
                                                        GROUP_CONCAT(m2.menuid SEPARATOR ',') AS mixedWithAvailabilityAmount,
                                                        GROUP_CONCAT(me.menu_extraid SEPARATOR ',') AS extrasAvailabilityAmount
                                                 FROM orders_details od
                                                 INNER JOIN menues m ON od.menuid = m.menuid
                                                 INNER JOIN orders_in_progress oip ON oip.orderid = od.orderid
                                                                                      AND oip.menu_groupid = m.menu_groupid
                                                 LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                                 LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                                 LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid AND me.availability_amount IS NOT NULL
                                                 LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                                 LEFT JOIN menues m2 ON m2.menuid = odmw.menuid AND m2.availability_amount IS NOT NULL
                                                 WHERE od.orders_detailid = :orders_detailid
                                                 GROUP BY od.orders_detailid");

            $o_statement->bindParam(':orders_detailid', $a_order_detail['id']);
            $o_statement->execute();

            $a_orders_details_data = $o_statement->fetch();

            $str_menuids = "";

            if(!empty($a_orders_details_data['availability_amount']))
            {
                $str_menuids = $a_orders_details_data['menuid'] . ',';
            }

            $str_menuids .= $a_orders_details_data['mixedWithAvailabilityAmount'];

            if($str_menuids && $str_menuids[strlen($str_menuids)-1] == ',')
                $str_menuids = substr($str_menuids, 0, -1);

            if(!empty($str_menuids))
            {
                $o_statement = $this->o_db->prepare("UPDATE menues
                                                    SET availability = IF(availability_amount - :amount <= 0, :delayed, availability),
                                                        availability_amount = IF(availability_amount - :amount <= 0, NULL, availability_amount - :amount)
                                                    WHERE menuid IN ($str_menuids)");

                $o_statement->bindParam(':amount', $a_order_detail['amount']);
                $o_statement->bindValue(':delayed', MyPOS\ORDER_AVAILABILITY_DELAYED);
                $o_statement->execute();
            }

            if(!empty($a_orders_details_data['extrasAvailabilityAmount']))
            {
                $o_statement = $this->o_db->prepare("UPDATE extras
                                                    SET availability = IF(availability_amount - :amount <= 0, :delayed, availability),
                                                        availability_amount = IF(availability_amount - :amount <= 0, NULL, availability_amount - :amount)
                                                    WHERE menu_extraid IN ($a_orders_details_data[extrasAvailabilityAmount])");

                $o_statement->bindParam(':amount', $a_order_detail['amount']);
                $o_statement->bindValue(':delayed', MyPOS\ORDER_AVAILABILITY_DELAYED);
                $o_statement->execute();
            }

            $o_statement = $this->o_db->prepare("INSERT INTO orders_in_progress_recieved (orders_detailid,
                                                                                          orders_in_progressid,
                                                                                          distributions_giving_outid,
                                                                                          amount)
                                                                                  VALUES (:orders_detailid,
                                                                                          :orders_in_progressid,
                                                                                          :distributions_giving_outid,
                                                                                          :amount)");

            $o_statement->bindParam(':distributions_giving_outid', $i_distributions_giving_outid);
            $o_statement->bindParam(':orders_in_progressid', $a_orders_details_data['orders_in_progressid']);
            $o_statement->bindParam(':amount', $a_order_detail['amount']);
            $o_statement->bindParam(':orders_detailid', $a_order_detail['id']);
            $o_statement->execute();
        }
    }

    public function AddOrdersExtrasInProgressRecieved($i_distributions_giving_outid, $a_order_details_special_extras)
    {
        if(empty($i_distributions_giving_outid) || empty($a_order_details_special_extras))
            return;

        foreach($a_order_details_special_extras as $a_order_details_special_extra)
        {
            $o_statement = $this->o_db->prepare("SELECT oip.orders_in_progressid,
                                                        odse.availability_amount
                                                 FROM orders_details_special_extra odse
                                                 INNER JOIN orders_in_progress oip ON oip.orderid = odse.orderid
                                                                                      AND oip.menu_groupid = odse.menu_groupid
                                                 WHERE odse.orders_details_special_extraid = :orders_details_special_extraid");

            $o_statement->bindParam(':orders_details_special_extraid', $a_order_details_special_extra['id']);
            $o_statement->execute();

            $a_order_details_special_extra_data = $o_statement->fetch();

            if(!empty($a_order_details_special_extra_data['availability_amount']))
            {
                $o_statement = $this->o_db->prepare("UPDATE orders_details_special_extra
                                                    SET availability = IF(availability_amount - :amount <= 0, :delayed, availability),
                                                        availability_amount = IF(availability_amount - :amount <= 0, NULL, availability_amount - :amount)
                                                    WHERE orders_details_special_extraid = :orders_details_special_extraid");

                $o_statement->bindParam(':amount', $a_order_detail['amount']);
                $o_statement->bindParam(':orders_details_special_extraid', $a_order_details_special_extra['id']);
                $o_statement->bindValue(':delayed', MyPOS\ORDER_AVAILABILITY_DELAYED);
                $o_statement->execute();
            }


            $o_statement = $this->o_db->prepare("INSERT INTO orders_extras_in_progress_recieved (orders_details_special_extraid,
                                                                                                orders_in_progressid,
                                                                                                distributions_giving_outid,
                                                                                                amount)
                                                                                            VALUES (:orders_details_special_extraid,
                                                                                                    :orders_in_progressid,
                                                                                                    :distributions_giving_outid,
                                                                                                    :amount)");

            $o_statement->bindParam(':distributions_giving_outid', $i_distributions_giving_outid);
            $o_statement->bindParam(':orders_in_progressid', $a_order_details_special_extra_data['orders_in_progressid']);
            $o_statement->bindParam(':amount', $a_order_details_special_extra['amount']);
            $o_statement->bindParam(':orders_details_special_extraid', $a_order_details_special_extra['id']);
            $o_statement->execute();
        }
    }

    public function GetOrderIDFromProgressID($i_orders_in_progressid)
    {
        $o_statement = $this->o_db->prepare("SELECT orderid
                                             FROM orders_in_progress
                                             WHERE orders_in_progressid = :orders_in_progressid");

        $o_statement->bindParam(':orders_in_progressid', $i_orders_in_progressid);
        $o_statement->execute();

        return $o_statement->fetchColumn();
    }



    public function CheckIfInProgressOrderDone($a_orders_in_progressids)
    {
        if(empty($a_orders_in_progressids))
            return;

        $str_orders_in_progressids = implode(',', array_filter( $a_orders_in_progressids, 'ctype_digit'));

        $o_statement = $this->o_db->prepare("UPDATE orders_details
                                             SET finished = NOW()
                                             WHERE orders_detailid IN
                                             (
                                                SELECT orders_detailid
                                                FROM (
                                                    SELECT od.orders_detailid
                                                    FROM orders_in_progress oip
                                                    INNER JOIN orders_details od ON od.orderid = oip.orderid
                                                                                    AND od.finished IS NULL
                                                    INNER JOIN menues m ON m.menuid = od.menuid
                                                                           AND m.menu_groupid = oip.menu_groupid
                                                    LEFT JOIN orders_in_progress_recieved oipr ON oipr.orders_in_progressid = oip.orders_in_progressid
                                                                                                  AND oipr.orders_detailid = od.orders_detailid
                                                    WHERE oip.orders_in_progressid IN ($str_orders_in_progressids)
                                                    GROUP BY od.orders_detailid
                                                    HAVING SUM(od.amount - oipr.amount ) = 0
                                                ) t
                                             )");

        $o_statement->execute();

        $o_statement = $this->o_db->prepare("UPDATE orders_details_special_extra
                                             SET finished = NOW()
                                             WHERE orders_details_special_extraid IN
                                             (
                                                SELECT orders_details_special_extraid
                                                FROM (
                                                    SELECT odse.orders_details_special_extraid
                                                    FROM orders_in_progress oip
                                                    INNER JOIN orders_details_special_extra odse ON odse.orderid = oip.orderid
                                                                                                    AND odse.finished IS NULL
                                                                                                    AND odse.menu_groupid = oip.menu_groupid
                                                    LEFT JOIN orders_extras_in_progress_recieved oeipr ON oeipr.orders_in_progressid = oip.orders_in_progressid
                                                                                                          AND oeipr.orders_details_special_extraid = odse.orders_details_special_extraid
                                                    WHERE oip.orders_in_progressid IN ($str_orders_in_progressids)
                                                    GROUP BY odse.orders_details_special_extraid
                                                    HAVING SUM(odse.amount - oeipr.amount ) = 0
                                                ) t
                                             )");

        $o_statement->execute();

        $o_statement = $this->o_db->prepare("UPDATE orders_in_progress
                                             SET done = NOW()
                                             WHERE orders_in_progressid IN
                                             (
                                                SELECT orders_in_progressid
                                                FROM
                                                (
                                                    SELECT oip.orders_in_progressid,
                                                        (
                                                             SELECT COUNT(*)
                                                             FROM orders_details od
                                                             INNER JOIN menues m ON m.menuid = od.menuid
                                                             WHERE od.orderid = oip.orderid
                                                                   AND m.menu_groupid = oip.menu_groupid
                                                                   AND od.finished IS NULL
                                                        ) AS countDetails,
                                                        (
                                                             SELECT COUNT(*)
                                                             FROM orders_details_special_extra odse
                                                             WHERE odse.orderid = oip.orderid
                                                                   AND odse.menu_groupid = oip.menu_groupid
                                                                   AND odse.finished IS NULL
                                                        ) AS countDetailsExtras
                                                    FROM orders_in_progress oip
                                                    WHERE oip.orders_in_progressid IN ($str_orders_in_progressids)
                                                ) t
                                                WHERE t.countDetails + t.countDetailsExtras = 0
                                             )");

        $o_statement->execute();


    }

    public function GetGivingOut($i_distributions_giving_outid)
    {
        $o_statement = $this->o_db->prepare("SELECT dgo.date,
                                                    IFNULL(od.orderid, odse.orderid) AS orderid
                                             FROM distributions_giving_outs dgo
                                             LEFT JOIN orders_in_progress_recieved oipr ON oipr.distributions_giving_outid = dgo.distributions_giving_outid
                                             LEFT JOIN orders_details od ON od.orders_detailid = oipr.orders_detailid
                                             LEFT JOIN orders_extras_in_progress_recieved oeipr ON oeipr.distributions_giving_outid = dgo.distributions_giving_outid
                                             LEFT JOIN orders_details_special_extra odse ON odse.orders_details_special_extraid = oeipr.orders_details_special_extraid
                                             WHERE dgo.distributions_giving_outid = :distributions_giving_outid
                                             LIMIT 1");

        $o_statement->bindParam(':distributions_giving_outid', $i_distributions_giving_outid);
        $o_statement->execute();

        $a_info = $o_statement->fetch();

        $o_statement = $this->o_db->prepare("SELECT oipr.amount,
                                                    od.extra_detail,
                                                    m.name,
                                                    ms.menu_sizeid,
                                                    ms.name AS sizeName,
                                                    GROUP_CONCAT(me.name SEPARATOR ', ') AS extras,
                                                    GROUP_CONCAT(m2.name SEPARATOR ' - ') AS mixedWith
                                             FROM orders_in_progress_recieved oipr
                                             INNER JOIN orders_details od ON od.orders_detailid = oipr.orders_detailid
                                             INNER JOIN menues m ON m.menuid = od.menuid
                                             INNER JOIN orders_detail_sizes ods ON ods.orders_detailid = od.orders_detailid
                                             INNER JOIN menues_possible_sizes mps ON mps.menues_possible_sizeid = ods.menues_possible_sizeid
                                             INNER JOIN menu_sizes ms ON ms.menu_sizeid = mps.menu_sizeid
                                             LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                             LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                             LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                             LEFT JOIN orders_details_mixed_with odmw ON odmw.orders_detailid = od.orders_detailid
                                             LEFT JOIN menues m2 ON m2.menuid = odmw.menuid
                                             WHERE oipr.distributions_giving_outid = :distributions_giving_outid
                                             GROUP BY od.orders_detailid");

        $o_statement->bindParam(':distributions_giving_outid', $i_distributions_giving_outid);
        $o_statement->execute();

        $a_order_details = $o_statement->fetchAll();

        $o_statement = $this->o_db->prepare("SELECT oeipr.amount,
                                                    odse.extra_detail AS name
                                             FROM orders_extras_in_progress_recieved oeipr
                                             INNER JOIN orders_details_special_extra odse ON odse.orders_details_special_extraid = oeipr.orders_details_special_extraid
                                             WHERE oeipr.distributions_giving_outid = :distributions_giving_outid");

        $o_statement->bindParam(':distributions_giving_outid', $i_distributions_giving_outid);
        $o_statement->execute();

        $a_order_details_special_extras = $o_statement->fetchAll();

        $a_return = array('date' => $a_info['date'],
                          'orderid' => $a_info['orderid'],
                          'giving_outs' => array());

        foreach($a_order_details as $a_order_detail)
        {
            $str_name =  $a_order_detail['name'];

            if($a_order_detail['menu_sizeid'] != MyPOS\ORDER_DEFAULT_SIZEID)
                $str_name .= " " . $a_order_detail['sizeName'];

            if(!empty($a_order_detail['mixedWith']))
                $str_name .= " Gemischt mit: " . $a_order_detail['mixedWith'] . ',';

            if(!empty($a_order_detail['extras']))
                $str_name .= " " . $a_order_detail['extras'] . ',';

            if(!empty($a_order_detail['extra_detail']))
                $str_name .= " " . $a_order_detail['extra_detail'];

            if(substr($str_name, -1) == ',')
                $str_name = substr($str_name, 0, -1);

            $a_return['giving_outs'][] = array('amount' => $a_order_detail['amount'],
                                               'name' => $str_name);
        }

        $a_return['giving_outs'] = array_merge($a_return['giving_outs'], $a_order_details_special_extras);

        return $a_return;
    }
}