<?php

namespace API\Models\Invoice\Map;

use API\Models\Invoice\InvoiceWarningType;
use API\Models\Invoice\InvoiceWarningTypeQuery;
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
 * This class defines the structure of the 'invoice_warning_type' table.
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class InvoiceWarningTypeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Invoice.Map.InvoiceWarningTypeTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'invoice_warning_type';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Invoice\\InvoiceWarningType';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Invoice.InvoiceWarningType';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the invoice_warning_typeid field
     */
    const COL_INVOICE_WARNING_TYPEID = 'invoice_warning_type.invoice_warning_typeid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'invoice_warning_type.eventid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'invoice_warning_type.name';

    /**
     * the column name for the extra_price field
     */
    const COL_EXTRA_PRICE = 'invoice_warning_type.extra_price';

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
    protected static $fieldNames = array(
        self::TYPE_PHPNAME       => array('InvoiceWarningTypeid', 'Eventid', 'Name', 'ExtraPrice', ),
        self::TYPE_CAMELNAME     => array('invoiceWarningTypeid', 'eventid', 'name', 'extraPrice', ),
        self::TYPE_COLNAME       => array(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, InvoiceWarningTypeTableMap::COL_EVENTID, InvoiceWarningTypeTableMap::COL_NAME, InvoiceWarningTypeTableMap::COL_EXTRA_PRICE, ),
        self::TYPE_FIELDNAME     => array('invoice_warning_typeid', 'eventid', 'name', 'extra_price', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('InvoiceWarningTypeid' => 0, 'Eventid' => 1, 'Name' => 2, 'ExtraPrice' => 3, ),
        self::TYPE_CAMELNAME     => array('invoiceWarningTypeid' => 0, 'eventid' => 1, 'name' => 2, 'extraPrice' => 3, ),
        self::TYPE_COLNAME       => array(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID => 0, InvoiceWarningTypeTableMap::COL_EVENTID => 1, InvoiceWarningTypeTableMap::COL_NAME => 2, InvoiceWarningTypeTableMap::COL_EXTRA_PRICE => 3, ),
        self::TYPE_FIELDNAME     => array('invoice_warning_typeid' => 0, 'eventid' => 1, 'name' => 2, 'extra_price' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
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
        $this->setName('invoice_warning_type');
        $this->setPhpName('InvoiceWarningType');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Invoice\\InvoiceWarningType');
        $this->setPackage('API.Models.Invoice');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('invoice_warning_typeid', 'InvoiceWarningTypeid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('extra_price', 'ExtraPrice', 'DECIMAL', true, 7, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation(
            'Event',
            '\\API\\Models\\Event\\Event',
            RelationMap::MANY_TO_ONE,
            array(
            0 =>
            array(
            0 => ':eventid',
            1 => ':eventid',
            ),
            ),
            null,
            null,
            null,
            false
        );
        $this->addRelation(
            'InvoiceWarning',
            '\\API\\Models\\Invoice\\InvoiceWarning',
            RelationMap::ONE_TO_MANY,
            array(
            0 =>
            array(
            0 => ':invoice_warning_typeid',
            1 => ':invoice_warning_typeid',
            ),
            ),
            null,
            null,
            'InvoiceWarnings',
            false
        );
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('InvoiceWarningTypeid', TableMap::TYPE_PHPNAME, $indexType)
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
     * @param  boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? InvoiceWarningTypeTableMap::CLASS_DEFAULT : InvoiceWarningTypeTableMap::OM_CLASS;
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
     * @return array           (InvoiceWarningType object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = InvoiceWarningTypeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InvoiceWarningTypeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InvoiceWarningTypeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InvoiceWarningTypeTableMap::OM_CLASS;
            /**
 * @var InvoiceWarningType $obj
*/
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InvoiceWarningTypeTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param  DataFetcherInterface $dataFetcher
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
            $key = InvoiceWarningTypeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InvoiceWarningTypeTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /**
 * @var InvoiceWarningType $obj
*/
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InvoiceWarningTypeTableMap::addInstanceToPool($obj, $key);
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
     * @param  Criteria $criteria object containing the columns to add.
     * @param  string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID);
            $criteria->addSelectColumn(InvoiceWarningTypeTableMap::COL_EVENTID);
            $criteria->addSelectColumn(InvoiceWarningTypeTableMap::COL_NAME);
            $criteria->addSelectColumn(InvoiceWarningTypeTableMap::COL_EXTRA_PRICE);
        } else {
            $criteria->addSelectColumn($alias . '.invoice_warning_typeid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.extra_price');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     *
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(InvoiceWarningTypeTableMap::DATABASE_NAME)->getTable(InvoiceWarningTypeTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(InvoiceWarningTypeTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(InvoiceWarningTypeTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new InvoiceWarningTypeTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a InvoiceWarningType or Criteria object OR a primary key value.
     *
     * @param  mixed               $values Criteria or InvoiceWarningType object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con    the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doDelete($values, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceWarningTypeTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Invoice\InvoiceWarningType) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InvoiceWarningTypeTableMap::DATABASE_NAME);
            $criteria->add(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, (array) $values, Criteria::IN);
        }

        $query = InvoiceWarningTypeQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InvoiceWarningTypeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InvoiceWarningTypeTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the invoice_warning_type table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return InvoiceWarningTypeQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a InvoiceWarningType or Criteria object.
     *
     * @param  mixed               $criteria Criteria or InvoiceWarningType object containing data that is used to create the INSERT statement.
     * @param  ConnectionInterface $con      the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceWarningTypeTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from InvoiceWarningType object
        }

        if ($criteria->containsKey(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID) && $criteria->keyContainsValue(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID.')');
        }


        // Set the correct dbName
        $query = InvoiceWarningTypeQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(
            function () use ($con, $query) {
                return $query->doInsert($con);
            }
        );
    }
} // InvoiceWarningTypeTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
InvoiceWarningTypeTableMap::buildTableMap();
