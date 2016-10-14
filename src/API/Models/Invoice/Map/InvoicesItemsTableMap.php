<?php

namespace API\Models\Invoice\Map;

use API\Models\Invoice\InvoicesItems;
use API\Models\Invoice\InvoicesItemsQuery;
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
 * This class defines the structure of the 'invoices_items' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class InvoicesItemsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Invoice.Map.InvoicesItemsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'invoices_items';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Invoice\\InvoicesItems';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Invoice.InvoicesItems';

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
     * the column name for the invoices_itemid field
     */
    const COL_INVOICES_ITEMID = 'invoices_items.invoices_itemid';

    /**
     * the column name for the invoiceid field
     */
    const COL_INVOICEID = 'invoices_items.invoiceid';

    /**
     * the column name for the orders_detailid field
     */
    const COL_ORDERS_DETAILID = 'invoices_items.orders_detailid';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'invoices_items.amount';

    /**
     * the column name for the price field
     */
    const COL_PRICE = 'invoices_items.price';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'invoices_items.description';

    /**
     * the column name for the tax field
     */
    const COL_TAX = 'invoices_items.tax';

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
        self::TYPE_PHPNAME       => array('InvoicesItemid', 'Invoiceid', 'OrdersDetailid', 'Amount', 'Price', 'Description', 'Tax', ),
        self::TYPE_CAMELNAME     => array('invoicesItemid', 'invoiceid', 'ordersDetailid', 'amount', 'price', 'description', 'tax', ),
        self::TYPE_COLNAME       => array(InvoicesItemsTableMap::COL_INVOICES_ITEMID, InvoicesItemsTableMap::COL_INVOICEID, InvoicesItemsTableMap::COL_ORDERS_DETAILID, InvoicesItemsTableMap::COL_AMOUNT, InvoicesItemsTableMap::COL_PRICE, InvoicesItemsTableMap::COL_DESCRIPTION, InvoicesItemsTableMap::COL_TAX, ),
        self::TYPE_FIELDNAME     => array('invoices_itemid', 'invoiceid', 'orders_detailid', 'amount', 'price', 'description', 'tax', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('InvoicesItemid' => 0, 'Invoiceid' => 1, 'OrdersDetailid' => 2, 'Amount' => 3, 'Price' => 4, 'Description' => 5, 'Tax' => 6, ),
        self::TYPE_CAMELNAME     => array('invoicesItemid' => 0, 'invoiceid' => 1, 'ordersDetailid' => 2, 'amount' => 3, 'price' => 4, 'description' => 5, 'tax' => 6, ),
        self::TYPE_COLNAME       => array(InvoicesItemsTableMap::COL_INVOICES_ITEMID => 0, InvoicesItemsTableMap::COL_INVOICEID => 1, InvoicesItemsTableMap::COL_ORDERS_DETAILID => 2, InvoicesItemsTableMap::COL_AMOUNT => 3, InvoicesItemsTableMap::COL_PRICE => 4, InvoicesItemsTableMap::COL_DESCRIPTION => 5, InvoicesItemsTableMap::COL_TAX => 6, ),
        self::TYPE_FIELDNAME     => array('invoices_itemid' => 0, 'invoiceid' => 1, 'orders_detailid' => 2, 'amount' => 3, 'price' => 4, 'description' => 5, 'tax' => 6, ),
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
        $this->setName('invoices_items');
        $this->setPhpName('InvoicesItems');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Invoice\\InvoicesItems');
        $this->setPackage('API.Models.Invoice');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('invoices_itemid', 'InvoicesItemid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('invoiceid', 'Invoiceid', 'INTEGER' , 'invoices', 'invoiceid', true, null, null);
        $this->addForeignPrimaryKey('orders_detailid', 'OrdersDetailid', 'INTEGER' , 'orders_details', 'orders_detailid', true, null, null);
        $this->addColumn('amount', 'Amount', 'TINYINT', true, null, null);
        $this->addColumn('price', 'Price', 'DECIMAL', true, 7, null);
        $this->addColumn('description', 'Description', 'VARCHAR', true, 255, null);
        $this->addColumn('tax', 'Tax', 'SMALLINT', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Invoices', '\\API\\Models\\Invoice\\Invoices', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':invoiceid',
    1 => ':invoiceid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('OrdersDetails', '\\API\\Models\\Ordering\\OrdersDetails', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':orders_detailid',
    1 => ':orders_detailid',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \API\Models\Invoice\InvoicesItems $obj A \API\Models\Invoice\InvoicesItems object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getInvoicesItemid() || is_scalar($obj->getInvoicesItemid()) || is_callable([$obj->getInvoicesItemid(), '__toString']) ? (string) $obj->getInvoicesItemid() : $obj->getInvoicesItemid()), (null === $obj->getInvoiceid() || is_scalar($obj->getInvoiceid()) || is_callable([$obj->getInvoiceid(), '__toString']) ? (string) $obj->getInvoiceid() : $obj->getInvoiceid()), (null === $obj->getOrdersDetailid() || is_scalar($obj->getOrdersDetailid()) || is_callable([$obj->getOrdersDetailid(), '__toString']) ? (string) $obj->getOrdersDetailid() : $obj->getOrdersDetailid())]);
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
     * @param mixed $value A \API\Models\Invoice\InvoicesItems object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\Invoice\InvoicesItems) {
                $key = serialize([(null === $value->getInvoicesItemid() || is_scalar($value->getInvoicesItemid()) || is_callable([$value->getInvoicesItemid(), '__toString']) ? (string) $value->getInvoicesItemid() : $value->getInvoicesItemid()), (null === $value->getInvoiceid() || is_scalar($value->getInvoiceid()) || is_callable([$value->getInvoiceid(), '__toString']) ? (string) $value->getInvoiceid() : $value->getInvoiceid()), (null === $value->getOrdersDetailid() || is_scalar($value->getOrdersDetailid()) || is_callable([$value->getOrdersDetailid(), '__toString']) ? (string) $value->getOrdersDetailid() : $value->getOrdersDetailid())]);

            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1]), (null === $value[2] || is_scalar($value[2]) || is_callable([$value[2], '__toString']) ? (string) $value[2] : $value[2])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\Invoice\InvoicesItems object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('InvoicesItemid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? InvoicesItemsTableMap::CLASS_DEFAULT : InvoicesItemsTableMap::OM_CLASS;
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
     * @return array           (InvoicesItems object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = InvoicesItemsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InvoicesItemsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InvoicesItemsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InvoicesItemsTableMap::OM_CLASS;
            /** @var InvoicesItems $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InvoicesItemsTableMap::addInstanceToPool($obj, $key);
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
            $key = InvoicesItemsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InvoicesItemsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var InvoicesItems $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InvoicesItemsTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_INVOICES_ITEMID);
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_INVOICEID);
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_ORDERS_DETAILID);
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_PRICE);
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(InvoicesItemsTableMap::COL_TAX);
        } else {
            $criteria->addSelectColumn($alias . '.invoices_itemid');
            $criteria->addSelectColumn($alias . '.invoiceid');
            $criteria->addSelectColumn($alias . '.orders_detailid');
            $criteria->addSelectColumn($alias . '.amount');
            $criteria->addSelectColumn($alias . '.price');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.tax');
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
        return Propel::getServiceContainer()->getDatabaseMap(InvoicesItemsTableMap::DATABASE_NAME)->getTable(InvoicesItemsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(InvoicesItemsTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(InvoicesItemsTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new InvoicesItemsTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a InvoicesItems or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or InvoicesItems object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesItemsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Invoice\InvoicesItems) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InvoicesItemsTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(InvoicesItemsTableMap::COL_INVOICES_ITEMID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(InvoicesItemsTableMap::COL_INVOICEID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $value[2]));
                $criteria->addOr($criterion);
            }
        }

        $query = InvoicesItemsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InvoicesItemsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InvoicesItemsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the invoices_items table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return InvoicesItemsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a InvoicesItems or Criteria object.
     *
     * @param mixed               $criteria Criteria or InvoicesItems object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesItemsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from InvoicesItems object
        }

        if ($criteria->containsKey(InvoicesItemsTableMap::COL_INVOICES_ITEMID) && $criteria->keyContainsValue(InvoicesItemsTableMap::COL_INVOICES_ITEMID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InvoicesItemsTableMap::COL_INVOICES_ITEMID.')');
        }


        // Set the correct dbName
        $query = InvoicesItemsQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // InvoicesItemsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
InvoicesItemsTableMap::buildTableMap();
