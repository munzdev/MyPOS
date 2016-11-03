<?php

namespace API\Models\Payment\Map;

use API\Models\Payment\Payment;
use API\Models\Payment\PaymentQuery;
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
 * This class defines the structure of the 'payment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PaymentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Payment.Map.PaymentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'payment';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Payment\\Payment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Payment.Payment';

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
     * the column name for the paymentid field
     */
    const COL_PAYMENTID = 'payment.paymentid';

    /**
     * the column name for the payment_typeid field
     */
    const COL_PAYMENT_TYPEID = 'payment.payment_typeid';

    /**
     * the column name for the invoiceid field
     */
    const COL_INVOICEID = 'payment.invoiceid';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'payment.date';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'payment.amount';

    /**
     * the column name for the canceled field
     */
    const COL_CANCELED = 'payment.canceled';

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
        self::TYPE_PHPNAME       => array('Paymentid', 'PaymentTypeid', 'Invoiceid', 'Date', 'Amount', 'Canceled', ),
        self::TYPE_CAMELNAME     => array('paymentid', 'paymentTypeid', 'invoiceid', 'date', 'amount', 'canceled', ),
        self::TYPE_COLNAME       => array(PaymentTableMap::COL_PAYMENTID, PaymentTableMap::COL_PAYMENT_TYPEID, PaymentTableMap::COL_INVOICEID, PaymentTableMap::COL_DATE, PaymentTableMap::COL_AMOUNT, PaymentTableMap::COL_CANCELED, ),
        self::TYPE_FIELDNAME     => array('paymentid', 'payment_typeid', 'invoiceid', 'date', 'amount', 'canceled', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Paymentid' => 0, 'PaymentTypeid' => 1, 'Invoiceid' => 2, 'Date' => 3, 'Amount' => 4, 'Canceled' => 5, ),
        self::TYPE_CAMELNAME     => array('paymentid' => 0, 'paymentTypeid' => 1, 'invoiceid' => 2, 'date' => 3, 'amount' => 4, 'canceled' => 5, ),
        self::TYPE_COLNAME       => array(PaymentTableMap::COL_PAYMENTID => 0, PaymentTableMap::COL_PAYMENT_TYPEID => 1, PaymentTableMap::COL_INVOICEID => 2, PaymentTableMap::COL_DATE => 3, PaymentTableMap::COL_AMOUNT => 4, PaymentTableMap::COL_CANCELED => 5, ),
        self::TYPE_FIELDNAME     => array('paymentid' => 0, 'payment_typeid' => 1, 'invoiceid' => 2, 'date' => 3, 'amount' => 4, 'canceled' => 5, ),
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
        $this->setName('payment');
        $this->setPhpName('Payment');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Payment\\Payment');
        $this->setPackage('API.Models.Payment');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('paymentid', 'Paymentid', 'INTEGER', true, 10, null);
        $this->addForeignPrimaryKey('payment_typeid', 'PaymentTypeid', 'INTEGER' , 'payment_type', 'payment_typeid', true, null, null);
        $this->addForeignPrimaryKey('invoiceid', 'Invoiceid', 'INTEGER' , 'invoice', 'invoiceid', true, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, null);
        $this->addColumn('amount', 'Amount', 'DECIMAL', true, 7, null);
        $this->addColumn('canceled', 'Canceled', 'TIMESTAMP', false, null, null);
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
), null, null, null, false);
        $this->addRelation('PaymentType', '\\API\\Models\\Payment\\PaymentType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':payment_typeid',
    1 => ':payment_typeid',
  ),
), null, null, null, false);
        $this->addRelation('PaymentCoupon', '\\API\\Models\\Payment\\PaymentCoupon', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':paymentid',
    1 => ':paymentid',
  ),
), null, null, 'PaymentCoupons', false);
        $this->addRelation('Coupon', '\\API\\Models\\Payment\\Coupon', RelationMap::MANY_TO_MANY, array(), null, null, 'Coupons');
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \API\Models\Payment\Payment $obj A \API\Models\Payment\Payment object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getPaymentid() || is_scalar($obj->getPaymentid()) || is_callable([$obj->getPaymentid(), '__toString']) ? (string) $obj->getPaymentid() : $obj->getPaymentid()), (null === $obj->getPaymentTypeid() || is_scalar($obj->getPaymentTypeid()) || is_callable([$obj->getPaymentTypeid(), '__toString']) ? (string) $obj->getPaymentTypeid() : $obj->getPaymentTypeid()), (null === $obj->getInvoiceid() || is_scalar($obj->getInvoiceid()) || is_callable([$obj->getInvoiceid(), '__toString']) ? (string) $obj->getInvoiceid() : $obj->getInvoiceid())]);
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
     * @param mixed $value A \API\Models\Payment\Payment object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\Payment\Payment) {
                $key = serialize([(null === $value->getPaymentid() || is_scalar($value->getPaymentid()) || is_callable([$value->getPaymentid(), '__toString']) ? (string) $value->getPaymentid() : $value->getPaymentid()), (null === $value->getPaymentTypeid() || is_scalar($value->getPaymentTypeid()) || is_callable([$value->getPaymentTypeid(), '__toString']) ? (string) $value->getPaymentTypeid() : $value->getPaymentTypeid()), (null === $value->getInvoiceid() || is_scalar($value->getInvoiceid()) || is_callable([$value->getInvoiceid(), '__toString']) ? (string) $value->getInvoiceid() : $value->getInvoiceid())]);

            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1]), (null === $value[2] || is_scalar($value[2]) || is_callable([$value[2], '__toString']) ? (string) $value[2] : $value[2])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\Payment\Payment object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? PaymentTableMap::CLASS_DEFAULT : PaymentTableMap::OM_CLASS;
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
     * @return array           (Payment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PaymentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PaymentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PaymentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PaymentTableMap::OM_CLASS;
            /** @var Payment $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PaymentTableMap::addInstanceToPool($obj, $key);
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
            $key = PaymentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PaymentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Payment $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PaymentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENTID);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_TYPEID);
            $criteria->addSelectColumn(PaymentTableMap::COL_INVOICEID);
            $criteria->addSelectColumn(PaymentTableMap::COL_DATE);
            $criteria->addSelectColumn(PaymentTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(PaymentTableMap::COL_CANCELED);
        } else {
            $criteria->addSelectColumn($alias . '.paymentid');
            $criteria->addSelectColumn($alias . '.payment_typeid');
            $criteria->addSelectColumn($alias . '.invoiceid');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.amount');
            $criteria->addSelectColumn($alias . '.canceled');
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
        return Propel::getServiceContainer()->getDatabaseMap(PaymentTableMap::DATABASE_NAME)->getTable(PaymentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PaymentTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PaymentTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PaymentTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Payment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Payment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Payment\Payment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PaymentTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(PaymentTableMap::COL_PAYMENTID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(PaymentTableMap::COL_PAYMENT_TYPEID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(PaymentTableMap::COL_INVOICEID, $value[2]));
                $criteria->addOr($criterion);
            }
        }

        $query = PaymentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PaymentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PaymentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the payment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PaymentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Payment or Criteria object.
     *
     * @param mixed               $criteria Criteria or Payment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Payment object
        }


        // Set the correct dbName
        $query = PaymentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PaymentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PaymentTableMap::buildTableMap();
