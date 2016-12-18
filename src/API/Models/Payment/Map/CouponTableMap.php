<?php

namespace API\Models\Payment\Map;

use API\Models\Payment\Coupon;
use API\Models\Payment\CouponQuery;
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
 * This class defines the structure of the 'coupon' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CouponTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Payment.Map.CouponTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'coupon';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Payment\\Coupon';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Payment.Coupon';

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
     * the column name for the couponid field
     */
    const COL_COUPONID = 'coupon.couponid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'coupon.eventid';

    /**
     * the column name for the created_by_userid field
     */
    const COL_CREATED_BY_USERID = 'coupon.created_by_userid';

    /**
     * the column name for the code field
     */
    const COL_CODE = 'coupon.code';

    /**
     * the column name for the created field
     */
    const COL_CREATED = 'coupon.created';

    /**
     * the column name for the value field
     */
    const COL_VALUE = 'coupon.value';

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
        self::TYPE_PHPNAME       => array('Couponid', 'Eventid', 'CreatedByUserid', 'Code', 'Created', 'Value', ),
        self::TYPE_CAMELNAME     => array('couponid', 'eventid', 'createdByUserid', 'code', 'created', 'value', ),
        self::TYPE_COLNAME       => array(CouponTableMap::COL_COUPONID, CouponTableMap::COL_EVENTID, CouponTableMap::COL_CREATED_BY_USERID, CouponTableMap::COL_CODE, CouponTableMap::COL_CREATED, CouponTableMap::COL_VALUE, ),
        self::TYPE_FIELDNAME     => array('couponid', 'eventid', 'created_by_userid', 'code', 'created', 'value', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Couponid' => 0, 'Eventid' => 1, 'CreatedByUserid' => 2, 'Code' => 3, 'Created' => 4, 'Value' => 5, ),
        self::TYPE_CAMELNAME     => array('couponid' => 0, 'eventid' => 1, 'createdByUserid' => 2, 'code' => 3, 'created' => 4, 'value' => 5, ),
        self::TYPE_COLNAME       => array(CouponTableMap::COL_COUPONID => 0, CouponTableMap::COL_EVENTID => 1, CouponTableMap::COL_CREATED_BY_USERID => 2, CouponTableMap::COL_CODE => 3, CouponTableMap::COL_CREATED => 4, CouponTableMap::COL_VALUE => 5, ),
        self::TYPE_FIELDNAME     => array('couponid' => 0, 'eventid' => 1, 'created_by_userid' => 2, 'code' => 3, 'created' => 4, 'value' => 5, ),
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
        $this->setName('coupon');
        $this->setPhpName('Coupon');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Payment\\Coupon');
        $this->setPackage('API.Models.Payment');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('couponid', 'Couponid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addForeignKey('created_by_userid', 'CreatedByUserid', 'INTEGER', 'user', 'userid', true, null, null);
        $this->addColumn('code', 'Code', 'VARCHAR', true, 24, null);
        $this->addColumn('created', 'Created', 'TIMESTAMP', true, null, null);
        $this->addColumn('value', 'Value', 'DECIMAL', true, 7, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Event', '\\API\\Models\\Event\\Event', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':eventid',
    1 => ':eventid',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\API\\Models\\User\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':created_by_userid',
    1 => ':userid',
  ),
), null, null, null, false);
        $this->addRelation('PaymentCoupon', '\\API\\Models\\Payment\\PaymentCoupon', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':couponid',
    1 => ':couponid',
  ),
), null, null, 'PaymentCoupons', false);
        $this->addRelation('PaymentRecieved', '\\API\\Models\\Payment\\PaymentRecieved', RelationMap::MANY_TO_MANY, array(), null, null, 'PaymentRecieveds');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('Couponid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? CouponTableMap::CLASS_DEFAULT : CouponTableMap::OM_CLASS;
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
     * @return array           (Coupon object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CouponTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CouponTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CouponTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CouponTableMap::OM_CLASS;
            /** @var Coupon $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CouponTableMap::addInstanceToPool($obj, $key);
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
            $key = CouponTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CouponTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Coupon $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CouponTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CouponTableMap::COL_COUPONID);
            $criteria->addSelectColumn(CouponTableMap::COL_EVENTID);
            $criteria->addSelectColumn(CouponTableMap::COL_CREATED_BY_USERID);
            $criteria->addSelectColumn(CouponTableMap::COL_CODE);
            $criteria->addSelectColumn(CouponTableMap::COL_CREATED);
            $criteria->addSelectColumn(CouponTableMap::COL_VALUE);
        } else {
            $criteria->addSelectColumn($alias . '.couponid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.created_by_userid');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.created');
            $criteria->addSelectColumn($alias . '.value');
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
        return Propel::getServiceContainer()->getDatabaseMap(CouponTableMap::DATABASE_NAME)->getTable(CouponTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CouponTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CouponTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CouponTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Coupon or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Coupon object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Payment\Coupon) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CouponTableMap::DATABASE_NAME);
            $criteria->add(CouponTableMap::COL_COUPONID, (array) $values, Criteria::IN);
        }

        $query = CouponQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CouponTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CouponTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the coupon table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CouponQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Coupon or Criteria object.
     *
     * @param mixed               $criteria Criteria or Coupon object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Coupon object
        }

        if ($criteria->containsKey(CouponTableMap::COL_COUPONID) && $criteria->keyContainsValue(CouponTableMap::COL_COUPONID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CouponTableMap::COL_COUPONID.')');
        }


        // Set the correct dbName
        $query = CouponQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CouponTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CouponTableMap::buildTableMap();
