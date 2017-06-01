<?php

namespace API\Models\ORM\Event\Map;

use API\Models\ORM\Event\EventPrinter;
use API\Models\ORM\Event\EventPrinterQuery;
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
 * This class defines the structure of the 'event_printer' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventPrinterTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.ORM.Event.Map.EventPrinterTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'event_printer';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\ORM\\Event\\EventPrinter';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.ORM.Event.EventPrinter';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the event_printerid field
     */
    const COL_EVENT_PRINTERID = 'event_printer.event_printerid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'event_printer.eventid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'event_printer.name';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'event_printer.type';

    /**
     * the column name for the attr1 field
     */
    const COL_ATTR1 = 'event_printer.attr1';

    /**
     * the column name for the attr2 field
     */
    const COL_ATTR2 = 'event_printer.attr2';

    /**
     * the column name for the default field
     */
    const COL_DEFAULT = 'event_printer.default';

    /**
     * the column name for the characters_per_row field
     */
    const COL_CHARACTERS_PER_ROW = 'event_printer.characters_per_row';

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
        self::TYPE_PHPNAME       => array('EventPrinterid', 'Eventid', 'Name', 'Type', 'Attr1', 'Attr2', 'Default', 'CharactersPerRow', ),
        self::TYPE_CAMELNAME     => array('eventPrinterid', 'eventid', 'name', 'type', 'attr1', 'attr2', 'default', 'charactersPerRow', ),
        self::TYPE_COLNAME       => array(EventPrinterTableMap::COL_EVENT_PRINTERID, EventPrinterTableMap::COL_EVENTID, EventPrinterTableMap::COL_NAME, EventPrinterTableMap::COL_TYPE, EventPrinterTableMap::COL_ATTR1, EventPrinterTableMap::COL_ATTR2, EventPrinterTableMap::COL_DEFAULT, EventPrinterTableMap::COL_CHARACTERS_PER_ROW, ),
        self::TYPE_FIELDNAME     => array('event_printerid', 'eventid', 'name', 'type', 'attr1', 'attr2', 'default', 'characters_per_row', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('EventPrinterid' => 0, 'Eventid' => 1, 'Name' => 2, 'Type' => 3, 'Attr1' => 4, 'Attr2' => 5, 'Default' => 6, 'CharactersPerRow' => 7, ),
        self::TYPE_CAMELNAME     => array('eventPrinterid' => 0, 'eventid' => 1, 'name' => 2, 'type' => 3, 'attr1' => 4, 'attr2' => 5, 'default' => 6, 'charactersPerRow' => 7, ),
        self::TYPE_COLNAME       => array(EventPrinterTableMap::COL_EVENT_PRINTERID => 0, EventPrinterTableMap::COL_EVENTID => 1, EventPrinterTableMap::COL_NAME => 2, EventPrinterTableMap::COL_TYPE => 3, EventPrinterTableMap::COL_ATTR1 => 4, EventPrinterTableMap::COL_ATTR2 => 5, EventPrinterTableMap::COL_DEFAULT => 6, EventPrinterTableMap::COL_CHARACTERS_PER_ROW => 7, ),
        self::TYPE_FIELDNAME     => array('event_printerid' => 0, 'eventid' => 1, 'name' => 2, 'type' => 3, 'attr1' => 4, 'attr2' => 5, 'default' => 6, 'characters_per_row' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('event_printer');
        $this->setPhpName('EventPrinter');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\API\\Models\\ORM\\Event\\EventPrinter');
        $this->setPackage('API.Models.ORM.Event');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('event_printerid', 'EventPrinterid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('type', 'Type', 'SMALLINT', true, null, null);
        $this->addColumn('attr1', 'Attr1', 'VARCHAR', false, 128, null);
        $this->addColumn('attr2', 'Attr2', 'VARCHAR', false, 128, null);
        $this->addColumn('default', 'Default', 'BOOLEAN', true, 1, null);
        $this->addColumn('characters_per_row', 'CharactersPerRow', 'TINYINT', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Event', '\\API\\Models\\ORM\\Event\\Event', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':eventid',
    1 => ':eventid',
  ),
), null, null, null, false);
        $this->addRelation('DistributionPlaceUser', '\\API\\Models\\ORM\\DistributionPlace\\DistributionPlaceUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':event_printerid',
    1 => ':event_printerid',
  ),
), null, null, 'DistributionPlaceUsers', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? EventPrinterTableMap::CLASS_DEFAULT : EventPrinterTableMap::OM_CLASS;
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
     * @return array           (EventPrinter object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventPrinterTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventPrinterTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventPrinterTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventPrinterTableMap::OM_CLASS;
            /** @var EventPrinter $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventPrinterTableMap::addInstanceToPool($obj, $key);
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
            $key = EventPrinterTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventPrinterTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var EventPrinter $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventPrinterTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventPrinterTableMap::COL_EVENT_PRINTERID);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_EVENTID);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_NAME);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_TYPE);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_ATTR1);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_ATTR2);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_DEFAULT);
            $criteria->addSelectColumn(EventPrinterTableMap::COL_CHARACTERS_PER_ROW);
        } else {
            $criteria->addSelectColumn($alias . '.event_printerid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.attr1');
            $criteria->addSelectColumn($alias . '.attr2');
            $criteria->addSelectColumn($alias . '.default');
            $criteria->addSelectColumn($alias . '.characters_per_row');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventPrinterTableMap::DATABASE_NAME)->getTable(EventPrinterTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventPrinterTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventPrinterTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventPrinterTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a EventPrinter or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or EventPrinter object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventPrinterTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\ORM\Event\EventPrinter) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventPrinterTableMap::DATABASE_NAME);
            $criteria->add(EventPrinterTableMap::COL_EVENT_PRINTERID, (array) $values, Criteria::IN);
        }

        $query = EventPrinterQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventPrinterTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventPrinterTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event_printer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventPrinterQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EventPrinter or Criteria object.
     *
     * @param mixed               $criteria Criteria or EventPrinter object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventPrinterTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EventPrinter object
        }

        if ($criteria->containsKey(EventPrinterTableMap::COL_EVENT_PRINTERID) && $criteria->keyContainsValue(EventPrinterTableMap::COL_EVENT_PRINTERID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventPrinterTableMap::COL_EVENT_PRINTERID.')');
        }


        // Set the correct dbName
        $query = EventPrinterQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventPrinterTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventPrinterTableMap::buildTableMap();
