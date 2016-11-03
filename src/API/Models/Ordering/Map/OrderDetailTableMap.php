<?php

namespace API\Models\Ordering\Map;

use API\Models\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailQuery;
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
 * This class defines the structure of the 'order_detail' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OrderDetailTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Ordering.Map.OrderDetailTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'order_detail';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Ordering\\OrderDetail';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Ordering.OrderDetail';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the order_detailid field
     */
    const COL_ORDER_DETAILID = 'order_detail.order_detailid';

    /**
     * the column name for the orderid field
     */
    const COL_ORDERID = 'order_detail.orderid';

    /**
     * the column name for the menuid field
     */
    const COL_MENUID = 'order_detail.menuid';

    /**
     * the column name for the menu_sizeid field
     */
    const COL_MENU_SIZEID = 'order_detail.menu_sizeid';

    /**
     * the column name for the menu_groupid field
     */
    const COL_MENU_GROUPID = 'order_detail.menu_groupid';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'order_detail.amount';

    /**
     * the column name for the single_price field
     */
    const COL_SINGLE_PRICE = 'order_detail.single_price';

    /**
     * the column name for the single_price_modified_by_userid field
     */
    const COL_SINGLE_PRICE_MODIFIED_BY_USERID = 'order_detail.single_price_modified_by_userid';

    /**
     * the column name for the extra_detail field
     */
    const COL_EXTRA_DETAIL = 'order_detail.extra_detail';

    /**
     * the column name for the finished field
     */
    const COL_FINISHED = 'order_detail.finished';

    /**
     * the column name for the availabilityid field
     */
    const COL_AVAILABILITYID = 'order_detail.availabilityid';

    /**
     * the column name for the availability_amount field
     */
    const COL_AVAILABILITY_AMOUNT = 'order_detail.availability_amount';

    /**
     * the column name for the verified field
     */
    const COL_VERIFIED = 'order_detail.verified';

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
        self::TYPE_PHPNAME       => array('OrderDetailid', 'Orderid', 'Menuid', 'MenuSizeid', 'MenuGroupid', 'Amount', 'SinglePrice', 'SinglePriceModifiedByUserid', 'ExtraDetail', 'Finished', 'Availabilityid', 'AvailabilityAmount', 'Verified', ),
        self::TYPE_CAMELNAME     => array('orderDetailid', 'orderid', 'menuid', 'menuSizeid', 'menuGroupid', 'amount', 'singlePrice', 'singlePriceModifiedByUserid', 'extraDetail', 'finished', 'availabilityid', 'availabilityAmount', 'verified', ),
        self::TYPE_COLNAME       => array(OrderDetailTableMap::COL_ORDER_DETAILID, OrderDetailTableMap::COL_ORDERID, OrderDetailTableMap::COL_MENUID, OrderDetailTableMap::COL_MENU_SIZEID, OrderDetailTableMap::COL_MENU_GROUPID, OrderDetailTableMap::COL_AMOUNT, OrderDetailTableMap::COL_SINGLE_PRICE, OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, OrderDetailTableMap::COL_EXTRA_DETAIL, OrderDetailTableMap::COL_FINISHED, OrderDetailTableMap::COL_AVAILABILITYID, OrderDetailTableMap::COL_AVAILABILITY_AMOUNT, OrderDetailTableMap::COL_VERIFIED, ),
        self::TYPE_FIELDNAME     => array('order_detailid', 'orderid', 'menuid', 'menu_sizeid', 'menu_groupid', 'amount', 'single_price', 'single_price_modified_by_userid', 'extra_detail', 'finished', 'availabilityid', 'availability_amount', 'verified', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('OrderDetailid' => 0, 'Orderid' => 1, 'Menuid' => 2, 'MenuSizeid' => 3, 'MenuGroupid' => 4, 'Amount' => 5, 'SinglePrice' => 6, 'SinglePriceModifiedByUserid' => 7, 'ExtraDetail' => 8, 'Finished' => 9, 'Availabilityid' => 10, 'AvailabilityAmount' => 11, 'Verified' => 12, ),
        self::TYPE_CAMELNAME     => array('orderDetailid' => 0, 'orderid' => 1, 'menuid' => 2, 'menuSizeid' => 3, 'menuGroupid' => 4, 'amount' => 5, 'singlePrice' => 6, 'singlePriceModifiedByUserid' => 7, 'extraDetail' => 8, 'finished' => 9, 'availabilityid' => 10, 'availabilityAmount' => 11, 'verified' => 12, ),
        self::TYPE_COLNAME       => array(OrderDetailTableMap::COL_ORDER_DETAILID => 0, OrderDetailTableMap::COL_ORDERID => 1, OrderDetailTableMap::COL_MENUID => 2, OrderDetailTableMap::COL_MENU_SIZEID => 3, OrderDetailTableMap::COL_MENU_GROUPID => 4, OrderDetailTableMap::COL_AMOUNT => 5, OrderDetailTableMap::COL_SINGLE_PRICE => 6, OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID => 7, OrderDetailTableMap::COL_EXTRA_DETAIL => 8, OrderDetailTableMap::COL_FINISHED => 9, OrderDetailTableMap::COL_AVAILABILITYID => 10, OrderDetailTableMap::COL_AVAILABILITY_AMOUNT => 11, OrderDetailTableMap::COL_VERIFIED => 12, ),
        self::TYPE_FIELDNAME     => array('order_detailid' => 0, 'orderid' => 1, 'menuid' => 2, 'menu_sizeid' => 3, 'menu_groupid' => 4, 'amount' => 5, 'single_price' => 6, 'single_price_modified_by_userid' => 7, 'extra_detail' => 8, 'finished' => 9, 'availabilityid' => 10, 'availability_amount' => 11, 'verified' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $this->setName('order_detail');
        $this->setPhpName('OrderDetail');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Ordering\\OrderDetail');
        $this->setPackage('API.Models.Ordering');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('order_detailid', 'OrderDetailid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('orderid', 'Orderid', 'INTEGER' , 'order', 'orderid', true, null, null);
        $this->addForeignKey('menuid', 'Menuid', 'INTEGER', 'menu', 'menuid', false, null, null);
        $this->addForeignKey('menu_sizeid', 'MenuSizeid', 'INTEGER', 'menu_size', 'menu_sizeid', false, null, null);
        $this->addForeignKey('menu_groupid', 'MenuGroupid', 'INTEGER', 'menu_group', 'menu_groupid', false, null, null);
        $this->addColumn('amount', 'Amount', 'TINYINT', true, null, null);
        $this->addColumn('single_price', 'SinglePrice', 'DECIMAL', true, 7, null);
        $this->addForeignKey('single_price_modified_by_userid', 'SinglePriceModifiedByUserid', 'INTEGER', 'user', 'userid', false, null, null);
        $this->addColumn('extra_detail', 'ExtraDetail', 'VARCHAR', false, 255, null);
        $this->addColumn('finished', 'Finished', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('availabilityid', 'Availabilityid', 'INTEGER', 'availability', 'availabilityid', false, null, null);
        $this->addColumn('availability_amount', 'AvailabilityAmount', 'SMALLINT', false, null, null);
        $this->addColumn('verified', 'Verified', 'BOOLEAN', true, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Availability', '\\API\\Models\\Menu\\Availability', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':availabilityid',
    1 => ':availabilityid',
  ),
), null, null, null, false);
        $this->addRelation('MenuGroup', '\\API\\Models\\Menu\\MenuGroup', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_groupid',
    1 => ':menu_groupid',
  ),
), null, null, null, false);
        $this->addRelation('MenuSize', '\\API\\Models\\Menu\\MenuSize', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_sizeid',
    1 => ':menu_sizeid',
  ),
), null, null, null, false);
        $this->addRelation('Menu', '\\API\\Models\\Menu\\Menu', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
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
    0 => ':single_price_modified_by_userid',
    1 => ':userid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('InvoiceItem', '\\API\\Models\\Invoice\\InvoiceItem', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_detailid',
    1 => ':order_detailid',
  ),
), null, null, 'InvoiceItems', false);
        $this->addRelation('OrderDetailExtra', '\\API\\Models\\Ordering\\OrderDetailExtra', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_detailid',
    1 => ':order_detailid',
  ),
), 'CASCADE', null, 'OrderDetailExtras', false);
        $this->addRelation('OrderDetailMixedWith', '\\API\\Models\\Ordering\\OrderDetailMixedWith', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_detailid',
    1 => ':order_detailid',
  ),
), 'CASCADE', null, 'OrderDetailMixedWiths', false);
        $this->addRelation('OrderInProgressRecieved', '\\API\\Models\\OIP\\OrderInProgressRecieved', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_detailid',
    1 => ':order_detailid',
  ),
), 'CASCADE', null, 'OrderInProgressRecieveds', false);
        $this->addRelation('MenuPossibleExtra', '\\API\\Models\\Menu\\MenuPossibleExtra', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'MenuPossibleExtras');
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \API\Models\Ordering\OrderDetail $obj A \API\Models\Ordering\OrderDetail object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getOrderDetailid() || is_scalar($obj->getOrderDetailid()) || is_callable([$obj->getOrderDetailid(), '__toString']) ? (string) $obj->getOrderDetailid() : $obj->getOrderDetailid()), (null === $obj->getOrderid() || is_scalar($obj->getOrderid()) || is_callable([$obj->getOrderid(), '__toString']) ? (string) $obj->getOrderid() : $obj->getOrderid())]);
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
     * @param mixed $value A \API\Models\Ordering\OrderDetail object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\Ordering\OrderDetail) {
                $key = serialize([(null === $value->getOrderDetailid() || is_scalar($value->getOrderDetailid()) || is_callable([$value->getOrderDetailid(), '__toString']) ? (string) $value->getOrderDetailid() : $value->getOrderDetailid()), (null === $value->getOrderid() || is_scalar($value->getOrderid()) || is_callable([$value->getOrderid(), '__toString']) ? (string) $value->getOrderid() : $value->getOrderid())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\Ordering\OrderDetail object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }
    /**
     * Method to invalidate the instance pool of all tables related to order_detail     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        OrderDetailExtraTableMap::clearInstancePool();
        OrderDetailMixedWithTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? OrderDetailTableMap::CLASS_DEFAULT : OrderDetailTableMap::OM_CLASS;
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
     * @return array           (OrderDetail object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OrderDetailTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OrderDetailTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OrderDetailTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrderDetailTableMap::OM_CLASS;
            /** @var OrderDetail $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OrderDetailTableMap::addInstanceToPool($obj, $key);
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
            $key = OrderDetailTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OrderDetailTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var OrderDetail $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrderDetailTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OrderDetailTableMap::COL_ORDER_DETAILID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_ORDERID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_MENUID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_MENU_SIZEID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_MENU_GROUPID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_SINGLE_PRICE);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_EXTRA_DETAIL);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_FINISHED);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_AVAILABILITYID);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT);
            $criteria->addSelectColumn(OrderDetailTableMap::COL_VERIFIED);
        } else {
            $criteria->addSelectColumn($alias . '.order_detailid');
            $criteria->addSelectColumn($alias . '.orderid');
            $criteria->addSelectColumn($alias . '.menuid');
            $criteria->addSelectColumn($alias . '.menu_sizeid');
            $criteria->addSelectColumn($alias . '.menu_groupid');
            $criteria->addSelectColumn($alias . '.amount');
            $criteria->addSelectColumn($alias . '.single_price');
            $criteria->addSelectColumn($alias . '.single_price_modified_by_userid');
            $criteria->addSelectColumn($alias . '.extra_detail');
            $criteria->addSelectColumn($alias . '.finished');
            $criteria->addSelectColumn($alias . '.availabilityid');
            $criteria->addSelectColumn($alias . '.availability_amount');
            $criteria->addSelectColumn($alias . '.verified');
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
        return Propel::getServiceContainer()->getDatabaseMap(OrderDetailTableMap::DATABASE_NAME)->getTable(OrderDetailTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OrderDetailTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OrderDetailTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OrderDetailTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a OrderDetail or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or OrderDetail object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Ordering\OrderDetail) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrderDetailTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(OrderDetailTableMap::COL_ORDER_DETAILID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(OrderDetailTableMap::COL_ORDERID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = OrderDetailQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OrderDetailTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OrderDetailTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the order_detail table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OrderDetailQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a OrderDetail or Criteria object.
     *
     * @param mixed               $criteria Criteria or OrderDetail object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OrderDetail object
        }

        if ($criteria->containsKey(OrderDetailTableMap::COL_ORDER_DETAILID) && $criteria->keyContainsValue(OrderDetailTableMap::COL_ORDER_DETAILID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OrderDetailTableMap::COL_ORDER_DETAILID.')');
        }


        // Set the correct dbName
        $query = OrderDetailQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OrderDetailTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OrderDetailTableMap::buildTableMap();
