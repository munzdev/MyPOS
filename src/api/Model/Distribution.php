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

    public function GetOrder($i_eventid, $i_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT t.orders_in_progressid
                                             FROM ( SELECT oip.orders_in_progressid,
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
                                                                                                    AND odse.menu_groupid IS NOT NULL)
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
                                             ORDER BY t.priority ASC
                                             LIMIT 1");

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->bindParam(":availability", MyPOS\ORDER_AVAILABILITY_AVAILABLE, PDO::PARAM_STR);
        $o_statement->bindParam(":delayed", '%' . MyPOS\ORDER_AVAILABILITY_DELAYED . '%', PDO::PARAM_STR);
        $o_statement->bindParam(":out_of_order", '%' . MyPOS\ORDER_AVAILABILITY_OUT_OF_ORDER . '%', PDO::PARAM_STR);
        $o_statement->execute();

        //-- Fetch allready started order to handle, whoes not finished yet (like page reloaded or the status of a product has changed back to available)
        $i_orders_in_progressid = $o_statement->fetchColumn();

        if(!$i_order_in_progressid) //-- if no existing progress order found, take a new order from priority list
        {
            $this->o_db->query("CALL open_orders_priority()");

            //-- First try to find an order, where the table is associated to the users distributions places
            $o_statement = $this->o_db->prepare("SELECT toop.orderid
                                                 FROM tmp_open_orders_priority toop
                                                 RIGHT JOIN orders_in_progress oip ON oip.orderid = toop.orderid
                                                 INNER JOIN distributions_places_groupes dpg ON dpg.menu_groupid = toop.menu_groupid
                                                 INNER JOIN distributions_places dp ON dp.distributions_placeid = dpg.distributions_placeid
                                                 INNER JOIN distributions_places_users dpu ON dpu.distributions_placeid = dpg.distributions_placeid
                                                 INNER JOIN distributions_places_tables dpt ON dpt.distributions_placeid = dpg.distributions_placeid
                                                 INNER JOIN orders o ON (o.orderid = toop.orderid AND o.tableid = dpt.tableid)
                                                 WHERE dpu.userid = :userid
                                                       AND
                                                       dp.eventid = :eventid
                                                 ORDER BY toop.rank ASC
                                                 LIMIT 1");

            $o_statement->bindParam(":userid", $i_userid);
            $o_statement->bindParam(":eventid", $i_eventid);
            $o_statement->execute();

            $i_orders_in_progressid = $o_statement->fetchColumn();

            //-- Secondly try to find an order, which belongs to an other distribution place but has the same menu_groupid and can also be handeled by the users one
            //-- this lets ordes be done faster
            if(!$i_orders_in_progressid)
            {

            }

            if(!$i_orders_in_progressid)
            {
                return null;
            }
        }
    }
}