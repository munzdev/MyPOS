<?php

namespace API\Models\Invoice\Map;

use API\Models\Invoice\InvoiceItem;
use API\Models\Invoice\InvoiceItemQuery;
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
 * This class defines the structure of the 'invoice_item' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class InvoiceItemTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Invoice.Map.InvoiceItemTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'invoice_item';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Invoice\\InvoiceItem';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Invoice.InvoiceItem';

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
     * the column name for the invoice_itemid field
     */
    const COL_INVOICE_ITEMID = 'invoice_item.invoice_itemid';

    /**
     * the column name for the invoiceid field
     */
    const COL_INVOICEID = 'invoice_item.invoiceid';

    /**
     * the column name for the order_detailid field
     */
    const COL_ORDER_DETAILID = 'invoice_item.order_detailid';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'invoice_item.amount';

    /**
     * the column name for the price field
     */
    const COL_PRICE = 'invoice_item.price';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'invoice_item.description';

    /**
     * the column name for the tax field
     */
    const COL_TAX = 'invoice_item.tax';

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
        self::TYPE_PHPNAME       => array('InvoiceItemid', 'Invoiceid', 'OrderDetailid', 'Amount', 'Price', 'Description', 'Tax', ),
        self::TYPE_CAMELNAME     => array('invoiceItemid', 'invoiceid', 'orderDetailid', 'amount', 'price', 'description', 'tax', ),
        self::TYPE_COLNAME       => array(InvoiceItemTableMap::COL_INVOICE_ITEMID, InvoiceItemTableMap::COL_INVOICEID, InvoiceItemTableMap::COL_ORDER_DETAILID, InvoiceItemTableMap::COL_AMOUNT, InvoiceItemTableMap::COL_PRICE, InvoiceItemTableMap::COL_DESCRIPTION, InvoiceItemTableMap::COL_TAX, ),
        self::TYPE_FIELDNAME     => array('invoice_itemid', 'invoiceid', 'order_detailid', 'amount', 'price', 'description', 'tax', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('InvoiceItemid' => 0, 'Invoiceid' => 1, 'OrderDetailid' => 2, 'Amount' => 3, 'Price' => 4, 'Description' => 5, 'Tax' => 6, ),
        self::TYPE_CAMELNAME     => array('invoiceItemid' => 0, 'invoiceid' => 1, 'orderDetailid' => 2, 'amount' => 3, 'price' => 4, 'description' => 5, 'tax' => 6, ),
        self::TYPE_COLNAME       => array(InvoiceItemTableMap::COL_INVOICE_ITEMID => 0, InvoiceItemTableMap::COL_INVOICEID => 1, InvoiceItemTableMap::COL_ORDER_DETAILID => 2, InvoiceItemTableMap::COL_AMOUNT => 3, InvoiceItemTableMap::COL_PRICE => 4, InvoiceItemTableMap::COL_DESCRIPTION => 5, InvoiceItemTableMap::COL_TAX => 6, ),
        self::TYPE_FIELDNAME     => array('invoice_itemid' => 0, 'invoiceid' => 1, 'order_detailid' => 2, 'amount' => 3, 'price' => 4, 'description' => 5, 'tax' => 6, ),
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
        $this->setName('invoice_item');
        $this->setPhpName('InvoiceItem');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Invoice\\InvoiceItem');
        $this->setPackage('API.Models.Invoice');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('invoice_itemid', 'InvoiceItemid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('invoiceid', 'Invoiceid', 'INTEGER' , 'invoice', 'invoiceid', true, null, null);
        $this->addForeignPrimaryKey('order_detailid', 'OrderDetailid', 'INTEGER' , 'order_detail', 'order_detailid', true, null, null);
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
        $this->addRelation('Invoice', '\\API\\Models\\Invoice\\Invoice', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':invoiceid',
    1 => ':invoiceid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('OrderDetail', '\\API\\Models\\Ordering\\OrderDetail', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':order_detailid',
    1 => ':order_detailid',
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
     * @param \API\Models\Invoice\InvoiceItem $obj A \API\Models\Invoice\InvoiceItem object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getInvoiceItemid() || is_scalar($obj->getInvoiceItemid()) || is_callable([$obj->getInvoiceItemid(), '__toString']) ? (string) $obj->getInvoiceItemid() : $obj->getInvoiceItemid()), (null === $obj->getInvoiceid() || is_scalar($obj->getInvoiceid()) || is_callable([$obj->getInvoiceid(), '__toString']) ? (string) $obj->getInvoiceid() : $obj->getInvoiceid()), (null === $obj->getOrderDetailid() || is_scalar($obj->getOrderDetailid()) || is_callable([$obj->getOrderDetailid(), '__toString']) ? (string) $obj->getOrderDetailid() : $obj->getOrderDetailid())]);
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
     * @param mixed $value A \API\Models\Invoice\InvoiceItem object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\Invoice\InvoiceItem) {
                $key = serialize([(null === $value->getInvoiceItemid() || is_scalar($value->getInvoiceItemid()) || is_callable([$value->getInvoiceItemid(), '__toString']) ? (string) $value->getInvoiceItemid() : $value->getInvoiceItemid()), (null === $value->getInvoiceid() || is_scalar($value->getInvoiceid()) || is_callable([$value->getInvoiceid(), '__toString']) ? (string) $value->getInvoiceid() : $value->getInvoiceid()), (null === $value->getOrderDetailid() || is_scalar($value->getOrderDetailid()) || is_callable([$value->getOrderDetailid(), '__toString']) ? (string) $value->getOrderDetailid() : $value->getOrderDetailid())]);

            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1]), (null === $value[2] || is_scalar($value[2]) || is_callable([$value[2], '__toString']) ? (string) $value[2] : $value[2])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\Invoice\InvoiceItem object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('InvoiceItemid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? InvoiceItemTableMap::CLASS_DEFAULT : InvoiceItemTableMap::OM_CLASS;
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
     * @return array           (InvoiceItem object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = InvoiceItemTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InvoiceItemTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InvoiceItemTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InvoiceItemTableMap::OM_CLASS;
            /** @var InvoiceItem $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InvoiceItemTableMap::addInstanceToPool($obj, $key);
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
            $key = InvoiceItemTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InvoiceItemTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var InvoiceItem $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InvoiceItemTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_INVOICE_ITEMID);
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_INVOICEID);
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_ORDER_DETAILID);
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_PRICE);
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(InvoiceItemTableMap::COL_TAX);
        } else {
            $criteria->addSelectColumn($alias . '.invoice_itemid');
            $criteria->addSelectColumn($alias . '.invoiceid');
            $criteria->addSelectColumn($alias . '.order_detailid');
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
        return Propel::getServiceContainer()->getDatabaseMap(InvoiceItemTableMap::DATABASE_NAME)->getTable(InvoiceItemTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(InvoiceItemTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(InvoiceItemTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new InvoiceItemTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a InvoiceItem or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or InvoiceItem object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceItemTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Invoice\InvoiceItem) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InvoiceItemTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(InvoiceItemTableMap::COL_INVOICE_ITEMID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(InvoiceItemTableMap::COL_INVOICEID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(InvoiceItemTableMap::COL_ORDER_DETAILID, $value[2]));
                $criteria->addOr($criterion);
            }
        }

        $query = InvoiceItemQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InvoiceItemTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InvoiceItemTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the invoice_item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return InvoiceItemQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a InvoiceItem or Criteria object.
     *
     * @param mixed               $criteria Criteria or InvoiceItem object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceItemTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from InvoiceItem object
        }

        if ($criteria->containsKey(InvoiceItemTableMap::COL_INVOICE_ITEMID) && $criteria->keyContainsValue(InvoiceItemTableMap::COL_INVOICE_ITEMID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InvoiceItemTableMap::COL_INVOICE_ITEMID.')');
        }


        // Set the correct dbName
        $query = InvoiceItemQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // InvoiceItemTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
InvoiceItemTableMap::buildTableMap();
