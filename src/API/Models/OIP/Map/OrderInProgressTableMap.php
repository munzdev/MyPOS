<?php

namespace API\Models\OIP\Map;

use API\Models\OIP\OrderInProgress;
use API\Models\OIP\OrderInProgressQuery;
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
 * This class defines the structure of the 'order_in_progress' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OrderInProgressTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.OIP.Map.OrderInProgressTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'order_in_progress';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\OIP\\OrderInProgress';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.OIP.OrderInProgress';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the order_in_progressid field
     */
    const COL_ORDER_IN_PROGRESSID = 'order_in_progress.order_in_progressid';

    /**
     * the column name for the orderid field
     */
    const COL_ORDERID = 'order_in_progress.orderid';

    /**
     * the column name for the userid field
     */
    const COL_USERID = 'order_in_progress.userid';

    /**
     * the column name for the menu_groupid field
     */
    const COL_MENU_GROUPID = 'order_in_progress.menu_groupid';

    /**
     * the column name for the begin field
     */
    const COL_BEGIN = 'order_in_progress.begin';

    /**
     * the column name for the done field
     */
    const COL_DONE = 'order_in_progress.done';

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
        self::TYPE_PHPNAME       => array('OrderInProgressid', 'Orderid', 'Userid', 'MenuGroupid', 'Begin', 'Done', ),
        self::TYPE_CAMELNAME     => array('orderInProgressid', 'orderid', 'userid', 'menuGroupid', 'begin', 'done', ),
        self::TYPE_COLNAME       => array(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, OrderInProgressTableMap::COL_ORDERID, OrderInProgressTableMap::COL_USERID, OrderInProgressTableMap::COL_MENU_GROUPID, OrderInProgressTableMap::COL_BEGIN, OrderInProgressTableMap::COL_DONE, ),
        self::TYPE_FIELDNAME     => array('order_in_progressid', 'orderid', 'userid', 'menu_groupid', 'begin', 'done', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('OrderInProgressid' => 0, 'Orderid' => 1, 'Userid' => 2, 'MenuGroupid' => 3, 'Begin' => 4, 'Done' => 5, ),
        self::TYPE_CAMELNAME     => array('orderInProgressid' => 0, 'orderid' => 1, 'userid' => 2, 'menuGroupid' => 3, 'begin' => 4, 'done' => 5, ),
        self::TYPE_COLNAME       => array(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID => 0, OrderInProgressTableMap::COL_ORDERID => 1, OrderInProgressTableMap::COL_USERID => 2, OrderInProgressTableMap::COL_MENU_GROUPID => 3, OrderInProgressTableMap::COL_BEGIN => 4, OrderInProgressTableMap::COL_DONE => 5, ),
        self::TYPE_FIELDNAME     => array('order_in_progressid' => 0, 'orderid' => 1, 'userid' => 2, 'menu_groupid' => 3, 'begin' => 4, 'done' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('order_in_progress');
        $this->setPhpName('OrderInProgress');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\OIP\\OrderInProgress');
        $this->setPackage('API.Models.OIP');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('order_in_progressid', 'OrderInProgressid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('orderid', 'Orderid', 'INTEGER' , 'order', 'orderid', true, null, null);
        $this->addForeignPrimaryKey('userid', 'Userid', 'INTEGER' , 'user', 'userid', true, null, null);
        $this->addForeignPrimaryKey('menu_groupid', 'MenuGroupid', 'INTEGER' , 'menu_group', 'menu_groupid', true, null, null);
        $this->addColumn('begin', 'Begin', 'TIMESTAMP', true, null, null);
        $this->addColumn('done', 'Done', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('MenuGroup', '\\API\\Models\\Menu\\MenuGroup', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_groupid',
    1 => ':menu_groupid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Order', '\\API\\Models\\Ordering\\Order', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':orderid',
    1 => ':orderid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('User', '\\API\\Models\\User\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('OrderInProgressRecieved', '\\API\\Models\\OIP\\OrderInProgressRecieved', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_in_progressid',
    1 => ':order_in_progressid',
  ),
), 'CASCADE', null, 'OrderInProgressRecieveds', false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \API\Models\OIP\OrderInProgress $obj A \API\Models\OIP\OrderInProgress object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getOrderInProgressid() || is_scalar($obj->getOrderInProgressid()) || is_callable([$obj->getOrderInProgressid(), '__toString']) ? (string) $obj->getOrderInProgressid() : $obj->getOrderInProgressid()), (null === $obj->getOrderid() || is_scalar($obj->getOrderid()) || is_callable([$obj->getOrderid(), '__toString']) ? (string) $obj->getOrderid() : $obj->getOrderid()), (null === $obj->getUserid() || is_scalar($obj->getUserid()) || is_callable([$obj->getUserid(), '__toString']) ? (string) $obj->getUserid() : $obj->getUserid()), (null === $obj->getMenuGroupid() || is_scalar($obj->getMenuGroupid()) || is_callable([$obj->getMenuGroupid(), '__toString']) ? (string) $obj->getMenuGroupid() : $obj->getMenuGroupid())]);
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \API\Models\OIP\OrderInProgress object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\OIP\OrderInProgress) {
                $key = serialize([(null === $value->getOrderInProgressid() || is_scalar($value->getOrderInProgressid()) || is_callable([$value->getOrderInProgressid(), '__toString']) ? (string) $value->getOrderInProgressid() : $value->getOrderInProgressid()), (null === $value->getOrderid() || is_scalar($value->getOrderid()) || is_callable([$value->getOrderid(), '__toString']) ? (string) $value->getOrderid() : $value->getOrderid()), (null === $value->getUserid() || is_scalar($value->getUserid()) || is_callable([$value->getUserid(), '__toString']) ? (string) $value->getUserid() : $value->getUserid()), (null === $value->getMenuGroupid() || is_scalar($value->getMenuGroupid()) || is_callable([$value->getMenuGroupid(), '__toString']) ? (string) $value->getMenuGroupid() : $value->getMenuGroupid())]);

            } elseif (is_array($value) && count($value) === 4) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1]), (null === $value[2] || is_scalar($value[2]) || is_callable([$value[2], '__toString']) ? (string) $value[2] : $value[2]), (null === $value[3] || is_scalar($value[3]) || is_callable([$value[3], '__toString']) ? (string) $value[3] : $value[3])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\OIP\OrderInProgress object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }
    /**
     * Method to invalidate the instance pool of all tables related to order_in_progress     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        OrderInProgressRecievedTableMap::clearInstancePool();
    }

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 3 + $offset
                : self::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
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
        return $withPrefix ? OrderInProgressTableMap::CLASS_DEFAULT : OrderInProgressTableMap::OM_CLASS;
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
     * @return array           (OrderInProgress object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OrderInProgressTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OrderInProgressTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OrderInProgressTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrderInProgressTableMap::OM_CLASS;
            /** @var OrderInProgress $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OrderInProgressTableMap::addInstanceToPool($obj, $key);
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
            $key = OrderInProgressTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OrderInProgressTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var OrderInProgress $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrderInProgressTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID);
            $criteria->addSelectColumn(OrderInProgressTableMap::COL_ORDERID);
            $criteria->addSelectColumn(OrderInProgressTableMap::COL_USERID);
            $criteria->addSelectColumn(OrderInProgressTableMap::COL_MENU_GROUPID);
            $criteria->addSelectColumn(OrderInProgressTableMap::COL_BEGIN);
            $criteria->addSelectColumn(OrderInProgressTableMap::COL_DONE);
        } else {
            $criteria->addSelectColumn($alias . '.order_in_progressid');
            $criteria->addSelectColumn($alias . '.orderid');
            $criteria->addSelectColumn($alias . '.userid');
            $criteria->addSelectColumn($alias . '.menu_groupid');
            $criteria->addSelectColumn($alias . '.begin');
            $criteria->addSelectColumn($alias . '.done');
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
        return Propel::getServiceContainer()->getDatabaseMap(OrderInProgressTableMap::DATABASE_NAME)->getTable(OrderInProgressTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OrderInProgressTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OrderInProgressTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OrderInProgressTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a OrderInProgress or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or OrderInProgress object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\OIP\OrderInProgress) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrderInProgressTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(OrderInProgressTableMap::COL_ORDERID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(OrderInProgressTableMap::COL_USERID, $value[2]));
                $criterion->addAnd($criteria->getNewCriterion(OrderInProgressTableMap::COL_MENU_GROUPID, $value[3]));
                $criteria->addOr($criterion);
            }
        }

        $query = OrderInProgressQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OrderInProgressTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OrderInProgressTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the order_in_progress table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OrderInProgressQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a OrderInProgress or Criteria object.
     *
     * @param mixed               $criteria Criteria or OrderInProgress object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OrderInProgress object
        }


        // Set the correct dbName
        $query = OrderInProgressQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OrderInProgressTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OrderInProgressTableMap::buildTableMap();
