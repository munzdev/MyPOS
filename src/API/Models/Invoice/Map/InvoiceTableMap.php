<?php

namespace API\Models\Invoice\Map;

use API\Models\Invoice\Invoice;
use API\Models\Invoice\InvoiceQuery;
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
 * This class defines the structure of the 'invoice' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class InvoiceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Invoice.Map.InvoiceTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'invoice';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Invoice\\Invoice';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Invoice.Invoice';

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
     * the column name for the invoiceid field
     */
    const COL_INVOICEID = 'invoice.invoiceid';

    /**
     * the column name for the cashier_userid field
     */
    const COL_CASHIER_USERID = 'invoice.cashier_userid';

    /**
     * the column name for the customerid field
     */
    const COL_CUSTOMERID = 'invoice.customerid';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'invoice.date';

    /**
     * the column name for the canceled field
     */
    const COL_CANCELED = 'invoice.canceled';

    /**
     * the column name for the payment_finished field
     */
    const COL_PAYMENT_FINISHED = 'invoice.payment_finished';

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
        self::TYPE_PHPNAME       => array('Invoiceid', 'CashierUserid', 'Customerid', 'Date', 'Canceled', 'PaymentFinished', ),
        self::TYPE_CAMELNAME     => array('invoiceid', 'cashierUserid', 'customerid', 'date', 'canceled', 'paymentFinished', ),
        self::TYPE_COLNAME       => array(InvoiceTableMap::COL_INVOICEID, InvoiceTableMap::COL_CASHIER_USERID, InvoiceTableMap::COL_CUSTOMERID, InvoiceTableMap::COL_DATE, InvoiceTableMap::COL_CANCELED, InvoiceTableMap::COL_PAYMENT_FINISHED, ),
        self::TYPE_FIELDNAME     => array('invoiceid', 'cashier_userid', 'customerid', 'date', 'canceled', 'payment_finished', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Invoiceid' => 0, 'CashierUserid' => 1, 'Customerid' => 2, 'Date' => 3, 'Canceled' => 4, 'PaymentFinished' => 5, ),
        self::TYPE_CAMELNAME     => array('invoiceid' => 0, 'cashierUserid' => 1, 'customerid' => 2, 'date' => 3, 'canceled' => 4, 'paymentFinished' => 5, ),
        self::TYPE_COLNAME       => array(InvoiceTableMap::COL_INVOICEID => 0, InvoiceTableMap::COL_CASHIER_USERID => 1, InvoiceTableMap::COL_CUSTOMERID => 2, InvoiceTableMap::COL_DATE => 3, InvoiceTableMap::COL_CANCELED => 4, InvoiceTableMap::COL_PAYMENT_FINISHED => 5, ),
        self::TYPE_FIELDNAME     => array('invoiceid' => 0, 'cashier_userid' => 1, 'customerid' => 2, 'date' => 3, 'canceled' => 4, 'payment_finished' => 5, ),
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
        $this->setName('invoice');
        $this->setPhpName('Invoice');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Invoice\\Invoice');
        $this->setPackage('API.Models.Invoice');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('invoiceid', 'Invoiceid', 'INTEGER', true, null, null);
        $this->addForeignKey('cashier_userid', 'CashierUserid', 'INTEGER', 'user', 'userid', true, null, null);
        $this->addForeignKey('customerid', 'Customerid', 'INTEGER', 'customer', 'customerid', false, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, null);
        $this->addColumn('canceled', 'Canceled', 'TIMESTAMP', false, null, null);
        $this->addColumn('payment_finished', 'PaymentFinished', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Customer', '\\API\\Models\\Invoice\\Customer', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':customerid',
    1 => ':customerid',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\API\\Models\\User\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':cashier_userid',
    1 => ':userid',
  ),
), null, null, null, false);
        $this->addRelation('InvoiceItem', '\\API\\Models\\Invoice\\InvoiceItem', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':invoiceid',
    1 => ':invoiceid',
  ),
), null, null, 'InvoiceItems', false);
        $this->addRelation('Payment', '\\API\\Models\\Payment\\Payment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':invoiceid',
    1 => ':invoiceid',
  ),
), null, null, 'Payments', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? InvoiceTableMap::CLASS_DEFAULT : InvoiceTableMap::OM_CLASS;
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
     * @return array           (Invoice object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = InvoiceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InvoiceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InvoiceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InvoiceTableMap::OM_CLASS;
            /** @var Invoice $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InvoiceTableMap::addInstanceToPool($obj, $key);
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
            $key = InvoiceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InvoiceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Invoice $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InvoiceTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InvoiceTableMap::COL_INVOICEID);
            $criteria->addSelectColumn(InvoiceTableMap::COL_CASHIER_USERID);
            $criteria->addSelectColumn(InvoiceTableMap::COL_CUSTOMERID);
            $criteria->addSelectColumn(InvoiceTableMap::COL_DATE);
            $criteria->addSelectColumn(InvoiceTableMap::COL_CANCELED);
            $criteria->addSelectColumn(InvoiceTableMap::COL_PAYMENT_FINISHED);
        } else {
            $criteria->addSelectColumn($alias . '.invoiceid');
            $criteria->addSelectColumn($alias . '.cashier_userid');
            $criteria->addSelectColumn($alias . '.customerid');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.canceled');
            $criteria->addSelectColumn($alias . '.payment_finished');
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
        return Propel::getServiceContainer()->getDatabaseMap(InvoiceTableMap::DATABASE_NAME)->getTable(InvoiceTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(InvoiceTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(InvoiceTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new InvoiceTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Invoice or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Invoice object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Invoice\Invoice) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InvoiceTableMap::DATABASE_NAME);
            $criteria->add(InvoiceTableMap::COL_INVOICEID, (array) $values, Criteria::IN);
        }

        $query = InvoiceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InvoiceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InvoiceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the invoice table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return InvoiceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Invoice or Criteria object.
     *
     * @param mixed               $criteria Criteria or Invoice object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Invoice object
        }

        if ($criteria->containsKey(InvoiceTableMap::COL_INVOICEID) && $criteria->keyContainsValue(InvoiceTableMap::COL_INVOICEID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InvoiceTableMap::COL_INVOICEID.')');
        }


        // Set the correct dbName
        $query = InvoiceQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // InvoiceTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
InvoiceTableMap::buildTableMap();
