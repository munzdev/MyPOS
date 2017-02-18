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
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderQuery extends BaseOrderQuery
{
    /**
     * Modifys all orders of the given eventid. Fixes priority order sorting and let them start by 2 and
     * sets the given orderid to priority 1
     *
     * @param int                 $orderid
     * @param int                 $eventid
     * @param ConnectionInterface $connection
     */
    public function setFirstPriority(int $orderid, int $eventid, ConnectionInterface $connection = null)
    {
        if ($connection === null) {
            $connection = Propel::getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $statement = $connection->prepare(
            "SET @pos:=1;
            UPDATE `order` o
            SET o.priority = (SELECT @pos:=@pos + 1)
            WHERE o.orderid <> :orderid
                  AND o.event_tableid IN (SELECT et.event_tableid
                                          FROM event_table et
                                          WHERE et.eventid = :eventid)
                  AND o.distribution_finished IS NULL
            ORDER BY o.priority ASC"
        );

        $statement->bindParam(":orderid", $orderid);
        $statement->bindParam(":eventid", $eventid);
        $statement->execute();
        $statement->closeCursor();

        $order = $this->create()->findOneByOrderid($orderid);
        $order->setPriority(1);
        $order->save();

        return $order;
    }

    public function getNextForDistribution(int $userid, int $eventid, bool $filterUserTable, int $limit = 1, ConnectionInterface $connection = null) : OrderQuery
    {
        if ($connection === null) {
            $connection = Propel::getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $sql = "SELECT o.orderid
                FROM `order` o
                INNER JOIN order_detail od ON od.orderid = o.orderid
                INNER JOIN event_table et ON et.event_tableid = o.event_tableid
                                             AND et.eventid = :eventid
                LEFT JOIN menu m ON m.menuid = od.menuid
                INNER JOIN menu_group mg ON mg.menu_groupid = m.menu_groupid
                                            OR mg.menu_groupid = od.menu_groupid
                LEFT JOIN order_in_progress oip ON oip.orderid = o.orderid AND oip.menu_groupid = mg.menu_groupid
                INNER JOIN distribution_place_group dpg ON dpg.menu_groupid = mg.menu_groupid
                INNER JOIN distribution_place_user dpu ON dpu.distribution_placeid = dpg.distribution_placeid
                                                          AND dpu.userid = :userid\n";

        if ($filterUserTable) {
            $sql .= "INNER JOIN distribution_place_table dpt ON dpt.event_tableid = et.event_tableid
                                                                    AND dpt.distribution_place_groupid = dpg.distribution_place_groupid\n";
        }

        $sql .= "WHERE od.distribution_finished IS NULL
                           AND od.verified = 1
                           AND od.availabilityid <> :availbilityid
                           AND oip.order_in_progressid IS NULL
                     ORDER BY o.priority ASC
                     LIMIT $limit";

        $statement = $connection->prepare($sql);
        $statement->bindValue(":userid", $userid);
        $statement->bindValue(":eventid", $eventid);
        $statement->bindValue(":availbilityid", ORDER_AVAILABILITY_OUT_OF_ORDER);
        $statement->execute();

        $orderids = $statement->fetchAll(\PDO::FETCH_COLUMN);

        if ($orderids) {
            $this->filterByOrderid($orderids);
        } else {
            $this->filterByOrderid(0);
        }

        return $this;
    }
}
