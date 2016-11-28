<?php

namespace API\Models\Ordering\Map;

use API\Models\Ordering\Order;
use API\Models\Ordering\OrderQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'order' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OrderTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Ordering.Map.OrderTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'order';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Ordering\\Order';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Ordering.Order';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the orderid field
     */
    const COL_ORDERID = 'order.orderid';

    /**
     * the column name for the event_tableid field
     */
    const COL_EVENT_TABLEID = 'order.event_tableid';

    /**
     * the column name for the userid field
     */
    const COL_USERID = 'order.userid';

    /**
     * the column name for the ordertime field
     */
    const COL_ORDERTIME = 'order.ordertime';

    /**
     * the column name for the priority field
     */
    const COL_PRIORITY = 'order.priority';

    /**
     * the column name for the distribution_finished field
     */
    const COL_DISTRIBUTION_FINISHED = 'order.distribution_finished';

    /**
     * the column name for the invoice_finished field
     */
    const COL_INVOICE_FINISHED = 'order.invoice_finished';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Orderid', 'EventTableid', 'Userid', 'Ordertime', 'Priority', 'DistributionFinished', 'InvoiceFinished', ),
        self::TYPE_CAMELNAME     => array('orderid', 'eventTableid', 'userid', 'ordertime', 'priority', 'distributionFinished', 'invoiceFinished', ),
        self::TYPE_COLNAME       => array(OrderTableMap::COL_ORDERID, OrderTableMap::COL_EVENT_TABLEID, OrderTableMap::COL_USERID, OrderTableMap::COL_ORDERTIME, OrderTableMap::COL_PRIORITY, OrderTableMap::COL_DISTRIBUTION_FINISHED, OrderTableMap::COL_INVOICE_FINISHED, ),
        self::TYPE_FIELDNAME     => array('orderid', 'event_tableid', 'userid', 'ordertime', 'priority', 'distribution_finished', 'invoice_finished', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Orderid' => 0, 'EventTableid' => 1, 'Userid' => 2, 'Ordertime' => 3, 'Priority' => 4, 'DistributionFinished' => 5, 'InvoiceFinished' => 6, ),
        self::TYPE_CAMELNAME     => array('orderid' => 0, 'eventTableid' => 1, 'userid' => 2, 'ordertime' => 3, 'priority' => 4, 'distributionFinished' => 5, 'invoiceFinished' => 6, ),
        self::TYPE_COLNAME       => array(OrderTableMap::COL_ORDERID => 0, OrderTableMap::COL_EVENT_TABLEID => 1, OrderTableMap::COL_USERID => 2, OrderTableMap::COL_ORDERTIME => 3, OrderTableMap::COL_PRIORITY => 4, OrderTableMap::COL_DISTRIBUTION_FINISHED => 5, OrderTableMap::COL_INVOICE_FINISHED => 6, ),
        self::TYPE_FIELDNAME     => array('orderid' => 0, 'event_tableid' => 1, 'userid' => 2, 'ordertime' => 3, 'priority' => 4, 'distribution_finished' => 5, 'invoice_finished' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('order');
        $this->setPhpName('Order');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\API\\Models\\Ordering\\Order');
        $this->setPackage('API.Models.Ordering');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('orderid', 'Orderid', 'INTEGER', true, null, null);
        $this->addForeignKey('event_tableid', 'EventTableid', 'INTEGER', 'event_table', 'event_tableid', true, null, null);
        $this->addForeignKey('userid', 'Userid', 'INTEGER', 'user', 'userid', true, null, null);
        $this->addColumn('ordertime', 'Ordertime', 'TIMESTAMP', true, null, null);
        $this->addColumn('priority', 'Priority', 'INTEGER', true, null, null);
        $this->addColumn('distribution_finished', 'DistributionFinished', 'TIMESTAMP', false, null, null);
        $this->addColumn('invoice_finished', 'InvoiceFinished', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EventTable', '\\API\\Models\\Event\\EventTable', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':event_tableid',
    1 => ':event_tableid',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\API\\Models\\User\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), null, null, null, false);
        $this->addRelation('OrderDetail', '\\API\\Models\\Ordering\\OrderDetail', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':orderid',
    1 => ':orderid',
  ),
), null, null, 'OrderDetails', false);
        $this->addRelation('OrderInProgress', '\\API\\Models\\OIP\\OrderInProgress', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':orderid',
    1 => ':orderid',
  ),
), null, null, 'OrderInProgresses', false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? OrderTableMap::CLASS_DEFAULT : OrderTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Order object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OrderTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OrderTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OrderTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrderTableMap::OM_CLASS;
            /** @var Order $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OrderTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = OrderTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OrderTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Order $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrderTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(OrderTableMap::COL_ORDERID);
            $criteria->addSelectColumn(OrderTableMap::COL_EVENT_TABLEID);
            $criteria->addSelectColumn(OrderTableMap::COL_USERID);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDERTIME);
            $criteria->addSelectColumn(OrderTableMap::COL_PRIORITY);
            $criteria->addSelectColumn(OrderTableMap::COL_DISTRIBUTION_FINISHED);
            $criteria->addSelectColumn(OrderTableMap::COL_INVOICE_FINISHED);
        } else {
            $criteria->addSelectColumn($alias . '.orderid');
            $criteria->addSelectColumn($alias . '.event_tableid');
            $criteria->addSelectColumn($alias . '.userid');
            $criteria->addSelectColumn($alias . '.ordertime');
            $criteria->addSelectColumn($alias . '.priority');
            $criteria->addSelectColumn($alias . '.distribution_finished');
            $criteria->addSelectColumn($alias . '.invoice_finished');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(OrderTableMap::DATABASE_NAME)->getTable(OrderTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OrderTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OrderTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OrderTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Order or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Order object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Ordering\Order) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrderTableMap::DATABASE_NAME);
            $criteria->add(OrderTableMap::COL_ORDERID, (array) $values, Criteria::IN);
        }

        $query = OrderQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OrderTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OrderTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the order table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OrderQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Order or Criteria object.
     *
     * @param mixed               $criteria Criteria or Order object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Order object
        }

        if ($criteria->containsKey(OrderTableMap::COL_ORDERID) && $criteria->keyContainsValue(OrderTableMap::COL_ORDERID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OrderTableMap::COL_ORDERID.')');
        }


        // Set the correct dbName
        $query = OrderQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OrderTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OrderTableMap::buildTableMap();
