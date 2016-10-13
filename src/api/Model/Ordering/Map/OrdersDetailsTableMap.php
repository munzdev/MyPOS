<?php

namespace Model\Ordering\Map;

use Model\OIP\Map\OrdersInProgressRecievedTableMap;
use Model\Ordering\OrdersDetails;
use Model\Ordering\OrdersDetailsQuery;
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
 * This class defines the structure of the 'orders_details' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OrdersDetailsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Ordering.Map.OrdersDetailsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'orders_details';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Ordering\\OrdersDetails';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Ordering.OrdersDetails';

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
     * the column name for the orders_detailid field
     */
    const COL_ORDERS_DETAILID = 'orders_details.orders_detailid';

    /**
     * the column name for the orderid field
     */
    const COL_ORDERID = 'orders_details.orderid';

    /**
     * the column name for the menuid field
     */
    const COL_MENUID = 'orders_details.menuid';

    /**
     * the column name for the menu_sizeid field
     */
    const COL_MENU_SIZEID = 'orders_details.menu_sizeid';

    /**
     * the column name for the menu_groupid field
     */
    const COL_MENU_GROUPID = 'orders_details.menu_groupid';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'orders_details.amount';

    /**
     * the column name for the single_price field
     */
    const COL_SINGLE_PRICE = 'orders_details.single_price';

    /**
     * the column name for the single_price_modified_by_userid field
     */
    const COL_SINGLE_PRICE_MODIFIED_BY_USERID = 'orders_details.single_price_modified_by_userid';

    /**
     * the column name for the extra_detail field
     */
    const COL_EXTRA_DETAIL = 'orders_details.extra_detail';

    /**
     * the column name for the finished field
     */
    const COL_FINISHED = 'orders_details.finished';

    /**
     * the column name for the availabilityid field
     */
    const COL_AVAILABILITYID = 'orders_details.availabilityid';

    /**
     * the column name for the availability_amount field
     */
    const COL_AVAILABILITY_AMOUNT = 'orders_details.availability_amount';

    /**
     * the column name for the verified field
     */
    const COL_VERIFIED = 'orders_details.verified';

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
        self::TYPE_PHPNAME       => array('OrdersDetailid', 'Orderid', 'Menuid', 'MenuSizeid', 'MenuGroupid', 'Amount', 'SinglePrice', 'SinglePriceModifiedByUserid', 'ExtraDetail', 'Finished', 'Availabilityid', 'AvailabilityAmount', 'Verified', ),
        self::TYPE_CAMELNAME     => array('ordersDetailid', 'orderid', 'menuid', 'menuSizeid', 'menuGroupid', 'amount', 'singlePrice', 'singlePriceModifiedByUserid', 'extraDetail', 'finished', 'availabilityid', 'availabilityAmount', 'verified', ),
        self::TYPE_COLNAME       => array(OrdersDetailsTableMap::COL_ORDERS_DETAILID, OrdersDetailsTableMap::COL_ORDERID, OrdersDetailsTableMap::COL_MENUID, OrdersDetailsTableMap::COL_MENU_SIZEID, OrdersDetailsTableMap::COL_MENU_GROUPID, OrdersDetailsTableMap::COL_AMOUNT, OrdersDetailsTableMap::COL_SINGLE_PRICE, OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, OrdersDetailsTableMap::COL_EXTRA_DETAIL, OrdersDetailsTableMap::COL_FINISHED, OrdersDetailsTableMap::COL_AVAILABILITYID, OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT, OrdersDetailsTableMap::COL_VERIFIED, ),
        self::TYPE_FIELDNAME     => array('orders_detailid', 'orderid', 'menuid', 'menu_sizeid', 'menu_groupid', 'amount', 'single_price', 'single_price_modified_by_userid', 'extra_detail', 'finished', 'availabilityid', 'availability_amount', 'verified', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('OrdersDetailid' => 0, 'Orderid' => 1, 'Menuid' => 2, 'MenuSizeid' => 3, 'MenuGroupid' => 4, 'Amount' => 5, 'SinglePrice' => 6, 'SinglePriceModifiedByUserid' => 7, 'ExtraDetail' => 8, 'Finished' => 9, 'Availabilityid' => 10, 'AvailabilityAmount' => 11, 'Verified' => 12, ),
        self::TYPE_CAMELNAME     => array('ordersDetailid' => 0, 'orderid' => 1, 'menuid' => 2, 'menuSizeid' => 3, 'menuGroupid' => 4, 'amount' => 5, 'singlePrice' => 6, 'singlePriceModifiedByUserid' => 7, 'extraDetail' => 8, 'finished' => 9, 'availabilityid' => 10, 'availabilityAmount' => 11, 'verified' => 12, ),
        self::TYPE_COLNAME       => array(OrdersDetailsTableMap::COL_ORDERS_DETAILID => 0, OrdersDetailsTableMap::COL_ORDERID => 1, OrdersDetailsTableMap::COL_MENUID => 2, OrdersDetailsTableMap::COL_MENU_SIZEID => 3, OrdersDetailsTableMap::COL_MENU_GROUPID => 4, OrdersDetailsTableMap::COL_AMOUNT => 5, OrdersDetailsTableMap::COL_SINGLE_PRICE => 6, OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID => 7, OrdersDetailsTableMap::COL_EXTRA_DETAIL => 8, OrdersDetailsTableMap::COL_FINISHED => 9, OrdersDetailsTableMap::COL_AVAILABILITYID => 10, OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT => 11, OrdersDetailsTableMap::COL_VERIFIED => 12, ),
        self::TYPE_FIELDNAME     => array('orders_detailid' => 0, 'orderid' => 1, 'menuid' => 2, 'menu_sizeid' => 3, 'menu_groupid' => 4, 'amount' => 5, 'single_price' => 6, 'single_price_modified_by_userid' => 7, 'extra_detail' => 8, 'finished' => 9, 'availabilityid' => 10, 'availability_amount' => 11, 'verified' => 12, ),
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
        $this->setName('orders_details');
        $this->setPhpName('OrdersDetails');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Ordering\\OrdersDetails');
        $this->setPackage('Model.Ordering');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('orders_detailid', 'OrdersDetailid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('orderid', 'Orderid', 'INTEGER' , 'orders', 'orderid', true, null, null);
        $this->addForeignKey('menuid', 'Menuid', 'INTEGER', 'menues', 'menuid', false, null, null);
        $this->addForeignKey('menu_sizeid', 'MenuSizeid', 'INTEGER', 'menu_sizes', 'menu_sizeid', false, null, null);
        $this->addForeignKey('menu_groupid', 'MenuGroupid', 'INTEGER', 'menu_groupes', 'menu_groupid', false, null, null);
        $this->addColumn('amount', 'Amount', 'TINYINT', true, null, null);
        $this->addColumn('single_price', 'SinglePrice', 'DECIMAL', true, 7, null);
        $this->addForeignKey('single_price_modified_by_userid', 'SinglePriceModifiedByUserid', 'INTEGER', 'users', 'userid', false, null, null);
        $this->addColumn('extra_detail', 'ExtraDetail', 'VARCHAR', false, 255, null);
        $this->addColumn('finished', 'Finished', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('availabilityid', 'Availabilityid', 'INTEGER', 'availabilitys', 'availabilityid', false, null, null);
        $this->addColumn('availability_amount', 'AvailabilityAmount', 'SMALLINT', false, null, null);
        $this->addColumn('verified', 'Verified', 'BOOLEAN', true, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Availabilitys', '\\Model\\Menues\\Availabilitys', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':availabilityid',
    1 => ':availabilityid',
  ),
), null, null, null, false);
        $this->addRelation('MenuGroupes', '\\Model\\Menues\\MenuGroupes', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_groupid',
    1 => ':menu_groupid',
  ),
), null, null, null, false);
        $this->addRelation('MenuSizes', '\\Model\\Menues\\MenuSizes', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_sizeid',
    1 => ':menu_sizeid',
  ),
), null, null, null, false);
        $this->addRelation('Menues', '\\Model\\Menues\\Menues', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Orders', '\\Model\\Ordering\\Orders', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':orderid',
    1 => ':orderid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Users', '\\Model\\User\\Users', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':single_price_modified_by_userid',
    1 => ':userid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('InvoicesItems', '\\Model\\Invoice\\InvoicesItems', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':orders_detailid',
    1 => ':orders_detailid',
  ),
), null, null, 'InvoicesItemss', false);
        $this->addRelation('OrdersDetailExtras', '\\Model\\Ordering\\OrdersDetailExtras', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':orders_detailid',
    1 => ':orders_detailid',
  ),
), 'CASCADE', null, 'OrdersDetailExtrass', false);
        $this->addRelation('OrdersDetailsMixedWith', '\\Model\\Ordering\\OrdersDetailsMixedWith', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':orders_detailid',
    1 => ':orders_detailid',
  ),
), 'CASCADE', null, 'OrdersDetailsMixedWiths', false);
        $this->addRelation('OrdersInProgressRecieved', '\\Model\\OIP\\OrdersInProgressRecieved', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':orders_detailid',
    1 => ':orders_detailid',
  ),
), 'CASCADE', null, 'OrdersInProgressRecieveds', false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Model\Ordering\OrdersDetails $obj A \Model\Ordering\OrdersDetails object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getOrdersDetailid() || is_scalar($obj->getOrdersDetailid()) || is_callable([$obj->getOrdersDetailid(), '__toString']) ? (string) $obj->getOrdersDetailid() : $obj->getOrdersDetailid()), (null === $obj->getOrderid() || is_scalar($obj->getOrderid()) || is_callable([$obj->getOrderid(), '__toString']) ? (string) $obj->getOrderid() : $obj->getOrderid())]);
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
     * @param mixed $value A \Model\Ordering\OrdersDetails object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Model\Ordering\OrdersDetails) {
                $key = serialize([(null === $value->getOrdersDetailid() || is_scalar($value->getOrdersDetailid()) || is_callable([$value->getOrdersDetailid(), '__toString']) ? (string) $value->getOrdersDetailid() : $value->getOrdersDetailid()), (null === $value->getOrderid() || is_scalar($value->getOrderid()) || is_callable([$value->getOrderid(), '__toString']) ? (string) $value->getOrderid() : $value->getOrderid())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Model\Ordering\OrdersDetails object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }
    /**
     * Method to invalidate the instance pool of all tables related to orders_details     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        OrdersDetailExtrasTableMap::clearInstancePool();
        OrdersDetailsMixedWithTableMap::clearInstancePool();
        OrdersInProgressRecievedTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? OrdersDetailsTableMap::CLASS_DEFAULT : OrdersDetailsTableMap::OM_CLASS;
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
     * @return array           (OrdersDetails object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OrdersDetailsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OrdersDetailsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OrdersDetailsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrdersDetailsTableMap::OM_CLASS;
            /** @var OrdersDetails $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OrdersDetailsTableMap::addInstanceToPool($obj, $key);
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
            $key = OrdersDetailsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OrdersDetailsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var OrdersDetails $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrdersDetailsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_ORDERS_DETAILID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_ORDERID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_MENUID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_MENU_SIZEID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_MENU_GROUPID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_SINGLE_PRICE);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_EXTRA_DETAIL);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_FINISHED);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_AVAILABILITYID);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT);
            $criteria->addSelectColumn(OrdersDetailsTableMap::COL_VERIFIED);
        } else {
            $criteria->addSelectColumn($alias . '.orders_detailid');
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
        return Propel::getServiceContainer()->getDatabaseMap(OrdersDetailsTableMap::DATABASE_NAME)->getTable(OrdersDetailsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OrdersDetailsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OrdersDetailsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OrdersDetailsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a OrdersDetails or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or OrdersDetails object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Ordering\OrdersDetails) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrdersDetailsTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(OrdersDetailsTableMap::COL_ORDERID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = OrdersDetailsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OrdersDetailsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OrdersDetailsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the orders_details table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OrdersDetailsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a OrdersDetails or Criteria object.
     *
     * @param mixed               $criteria Criteria or OrdersDetails object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OrdersDetails object
        }

        if ($criteria->containsKey(OrdersDetailsTableMap::COL_ORDERS_DETAILID) && $criteria->keyContainsValue(OrdersDetailsTableMap::COL_ORDERS_DETAILID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OrdersDetailsTableMap::COL_ORDERS_DETAILID.')');
        }


        // Set the correct dbName
        $query = OrdersDetailsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OrdersDetailsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OrdersDetailsTableMap::buildTableMap();
