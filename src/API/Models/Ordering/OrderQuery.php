<?php

namespace API\Models\Ordering;

use API\Models\Ordering\Base\OrderQuery as BaseOrderQuery;
use Propel\Runtime\Propel;
use React\Socket\ConnectionInterface;

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
            $o_connection = Propel::getServiceContainer()->getReadConnection($this->getDbName());
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
}
