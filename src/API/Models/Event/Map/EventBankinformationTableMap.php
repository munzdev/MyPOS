<?php

namespace API\Models\Event\Map;

use API\Models\Event\EventBankinformation;
use API\Models\Event\EventBankinformationQuery;
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
 * This class defines the structure of the 'event_bankinformation' table.
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class EventBankinformationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Event.Map.EventBankinformationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'event_bankinformation';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Event\\EventBankinformation';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Event.EventBankinformation';

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
     * the column name for the event_bankinformationid field
     */
    const COL_EVENT_BANKINFORMATIONID = 'event_bankinformation.event_bankinformationid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'event_bankinformation.eventid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'event_bankinformation.name';

    /**
     * the column name for the iban field
     */
    const COL_IBAN = 'event_bankinformation.iban';

    /**
     * the column name for the bic field
     */
    const COL_BIC = 'event_bankinformation.bic';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'event_bankinformation.active';

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
        self::TYPE_PHPNAME       => array('EventBankinformationid', 'Eventid', 'Name', 'Iban', 'Bic', 'Active', ),
        self::TYPE_CAMELNAME     => array('eventBankinformationid', 'eventid', 'name', 'iban', 'bic', 'active', ),
        self::TYPE_COLNAME       => array(EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID, EventBankinformationTableMap::COL_EVENTID, EventBankinformationTableMap::COL_NAME, EventBankinformationTableMap::COL_IBAN, EventBankinformationTableMap::COL_BIC, EventBankinformationTableMap::COL_ACTIVE, ),
        self::TYPE_FIELDNAME     => array('event_bankinformationid', 'eventid', 'name', 'iban', 'bic', 'active', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('EventBankinformationid' => 0, 'Eventid' => 1, 'Name' => 2, 'Iban' => 3, 'Bic' => 4, 'Active' => 5, ),
        self::TYPE_CAMELNAME     => array('eventBankinformationid' => 0, 'eventid' => 1, 'name' => 2, 'iban' => 3, 'bic' => 4, 'active' => 5, ),
        self::TYPE_COLNAME       => array(EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID => 0, EventBankinformationTableMap::COL_EVENTID => 1, EventBankinformationTableMap::COL_NAME => 2, EventBankinformationTableMap::COL_IBAN => 3, EventBankinformationTableMap::COL_BIC => 4, EventBankinformationTableMap::COL_ACTIVE => 5, ),
        self::TYPE_FIELDNAME     => array('event_bankinformationid' => 0, 'eventid' => 1, 'name' => 2, 'iban' => 3, 'bic' => 4, 'active' => 5, ),
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
        $this->setName('event_bankinformation');
        $this->setPhpName('EventBankinformation');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Event\\EventBankinformation');
        $this->setPackage('API.Models.Event');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('event_bankinformationid', 'EventBankinformationid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('iban', 'Iban', 'VARCHAR', true, 32, null);
        $this->addColumn('bic', 'Bic', 'VARCHAR', true, 16, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', true, 1, null);
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
            'Invoice',
            '\\API\\Models\\Invoice\\Invoice',
            RelationMap::ONE_TO_MANY,
            array(
            0 =>
            array(
            0 => ':event_bankinformationid',
            1 => ':event_bankinformationid',
            ),
            ),
            null,
            null,
            'Invoices',
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? EventBankinformationTableMap::CLASS_DEFAULT : EventBankinformationTableMap::OM_CLASS;
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
     * @return array           (EventBankinformation object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventBankinformationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventBankinformationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventBankinformationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventBankinformationTableMap::OM_CLASS;
            /**
 * @var EventBankinformation $obj
*/
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventBankinformationTableMap::addInstanceToPool($obj, $key);
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
            $key = EventBankinformationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventBankinformationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /**
 * @var EventBankinformation $obj
*/
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventBankinformationTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID);
            $criteria->addSelectColumn(EventBankinformationTableMap::COL_EVENTID);
            $criteria->addSelectColumn(EventBankinformationTableMap::COL_NAME);
            $criteria->addSelectColumn(EventBankinformationTableMap::COL_IBAN);
            $criteria->addSelectColumn(EventBankinformationTableMap::COL_BIC);
            $criteria->addSelectColumn(EventBankinformationTableMap::COL_ACTIVE);
        } else {
            $criteria->addSelectColumn($alias . '.event_bankinformationid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.iban');
            $criteria->addSelectColumn($alias . '.bic');
            $criteria->addSelectColumn($alias . '.active');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventBankinformationTableMap::DATABASE_NAME)->getTable(EventBankinformationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventBankinformationTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventBankinformationTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventBankinformationTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a EventBankinformation or Criteria object OR a primary key value.
     *
     * @param  mixed               $values Criteria or EventBankinformation object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventBankinformationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Event\EventBankinformation) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventBankinformationTableMap::DATABASE_NAME);
            $criteria->add(EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID, (array) $values, Criteria::IN);
        }

        $query = EventBankinformationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventBankinformationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventBankinformationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event_bankinformation table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventBankinformationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EventBankinformation or Criteria object.
     *
     * @param  mixed               $criteria Criteria or EventBankinformation object containing data that is used to create the INSERT statement.
     * @param  ConnectionInterface $con      the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventBankinformationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EventBankinformation object
        }

        if ($criteria->containsKey(EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID) && $criteria->keyContainsValue(EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventBankinformationTableMap::COL_EVENT_BANKINFORMATIONID.')');
        }


        // Set the correct dbName
        $query = EventBankinformationQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(
            function () use ($con, $query) {
                return $query->doInsert($con);
            }
        );
    }
} // EventBankinformationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventBankinformationTableMap::buildTableMap();
