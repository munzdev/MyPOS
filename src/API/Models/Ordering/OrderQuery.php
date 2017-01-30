<?php

namespace API\Models\Ordering;

use API\Models\Ordering\Base\OrderQuery as BaseOrderQuery;
use API\Models\Ordering\Map\OrderTableMap;
use Propel\Runtime\Propel;
use React\Socket\ConnectionInterface;
use const API\ORDER_AVAILABILITY_OUT_OF_ORDER;

/**
 * Skeleton subclass for performing query and update operations on the 'order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class OrderQuery extends BaseOrderQuery
{
    /**
     * Modifys all orders of the given eventid. Fixes priority order sorting and let them start by 2 and
     * sets the given orderid to priority 1
     *
     * @param int $i_orderid
     * @param int $i_eventid
     * @param ConnectionInterface $o_connection
     */
    public function setFirstPriority(int $i_orderid, int $i_eventid, ConnectionInterface $o_connection = null) {
        if ($o_connection === null) {
            $o_connection = Propel::getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $o_statement = $o_connection->prepare("SET @pos:=1;
                                               UPDATE `order` o
                                               SET o.priority = (SELECT @pos:=@pos + 1)
                                               WHERE o.orderid <> :orderid
                                                     AND o.event_tableid IN (SELECT et.event_tableid
                                                                             FROM event_table et
                                                                             WHERE et.eventid = :eventid)
                                                     AND o.distribution_finished IS NULL
                                               ORDER BY o.priority ASC");

        $o_statement->bindParam(":orderid", $i_orderid);
        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->execute();
        $o_statement->closeCursor();

        $o_order = $this->create()->findOneByOrderid($i_orderid);
        $o_order->setPriority(1);
        $o_order->save();

        return $o_order;
    }

    public function getNextForDistribution(int $i_userid, int $i_eventid, bool $b_filterUserTable, int $i_limit = 1, ConnectionInterface $o_connection = null) : OrderQuery  {
        if ($o_connection === null) {
            $o_connection = Propel::getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $str_sql = "SELECT o.orderid
                    FROM `order` o
                    INNER JOIN order_detail od ON od.orderid = o.orderid
                    INNER JOIN event_table et ON et.event_tableid = o.event_tableid
                                                 AND et.eventid = :eventid

                    LEFT JOIN menu m ON m.menuid = od.menuid
                    INNER JOIN menu_group mg ON mg.menu_groupid = m.menu_groupid
                                                OR mg.menu_groupid = od.menu_groupid
                    INNER JOIN distribution_place_group dpg ON dpg.menu_groupid = mg.menu_groupid
                    INNER JOIN distribution_place_user dpu ON dpu.distribution_placeid = dpg.distribution_placeid
                                                              AND dpu.userid = :userid\n";

        if($b_filterUserTable)
            $str_sql .= "INNER JOIN distribution_place_table dpt ON dpt.event_tableid = et.event_tableid
                                                                    AND dpt.distribution_place_groupid = dpg.distribution_place_groupid\n";

        $str_sql .= "WHERE od.distribution_finished IS NULL
                           AND od.verified = 1
                           AND od.availabilityid <> :availbilityid
                     ORDER BY o.priority ASC
                     LIMIT $i_limit";

        $o_statement = $o_connection->prepare($str_sql);
        $o_statement->bindValue(":userid", $i_userid);
        $o_statement->bindValue(":eventid", $i_eventid);
        $o_statement->bindValue(":availbilityid", ORDER_AVAILABILITY_OUT_OF_ORDER);
        $o_statement->execute();

        $a_orderids = $o_statement->fetchAll(\PDO::FETCH_COLUMN);

        if($a_orderids) {
            $this->filterByOrderid($a_orderids);
        } else {
            $this->filterByOrderid(0);
        }

        return $this;
    }
}