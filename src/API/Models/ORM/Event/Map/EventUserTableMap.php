<?php

namespace API\Models\ORM\Event\Map;

use API\Models\ORM\Event\EventUser;
use API\Models\ORM\Event\EventUserQuery;
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
 * This class defines the structure of the 'event_user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventUserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.ORM.Event.Map.EventUserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'event_user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\ORM\\Event\\EventUser';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.ORM.Event.EventUser';

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
     * the column name for the event_userid field
     */
    const COL_EVENT_USERID = 'event_user.event_userid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'event_user.eventid';

    /**
     * the column name for the userid field
     */
    const COL_USERID = 'event_user.userid';

    /**
     * the column name for the user_roles field
     */
    const COL_USER_ROLES = 'event_user.user_roles';

    /**
     * the column name for the begin_money field
     */
    const COL_BEGIN_MONEY = 'event_user.begin_money';

    /**
     * the column name for the is_deleted field
     */
    const COL_IS_DELETED = 'event_user.is_deleted';

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
        self::TYPE_PHPNAME       => array('EventUserid', 'Eventid', 'Userid', 'UserRoles', 'BeginMoney', 'IsDeleted', ),
        self::TYPE_CAMELNAME     => array('eventUserid', 'eventid', 'userid', 'userRoles', 'beginMoney', 'isDeleted', ),
        self::TYPE_COLNAME       => array(EventUserTableMap::COL_EVENT_USERID, EventUserTableMap::COL_EVENTID, EventUserTableMap::COL_USERID, EventUserTableMap::COL_USER_ROLES, EventUserTableMap::COL_BEGIN_MONEY, EventUserTableMap::COL_IS_DELETED, ),
        self::TYPE_FIELDNAME     => array('event_userid', 'eventid', 'userid', 'user_roles', 'begin_money', 'is_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('EventUserid' => 0, 'Eventid' => 1, 'Userid' => 2, 'UserRoles' => 3, 'BeginMoney' => 4, 'IsDeleted' => 5, ),
        self::TYPE_CAMELNAME     => array('eventUserid' => 0, 'eventid' => 1, 'userid' => 2, 'userRoles' => 3, 'beginMoney' => 4, 'isDeleted' => 5, ),
        self::TYPE_COLNAME       => array(EventUserTableMap::COL_EVENT_USERID => 0, EventUserTableMap::COL_EVENTID => 1, EventUserTableMap::COL_USERID => 2, EventUserTableMap::COL_USER_ROLES => 3, EventUserTableMap::COL_BEGIN_MONEY => 4, EventUserTableMap::COL_IS_DELETED => 5, ),
        self::TYPE_FIELDNAME     => array('event_userid' => 0, 'eventid' => 1, 'userid' => 2, 'user_roles' => 3, 'begin_money' => 4, 'is_deleted' => 5, ),
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
        $this->setName('event_user');
        $this->setPhpName('EventUser');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\API\\Models\\ORM\\Event\\EventUser');
        $this->setPackage('API.Models.ORM.Event');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('event_userid', 'EventUserid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addForeignKey('userid', 'Userid', 'INTEGER', 'user', 'userid', true, null, null);
        $this->addColumn('user_roles', 'UserRoles', 'INTEGER', true, null, null);
        $this->addColumn('begin_money', 'BeginMoney', 'DECIMAL', true, null, null);
        $this->addColumn('is_deleted', 'IsDeleted', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('User', '\\API\\Models\\ORM\\User\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), null, null, null, false);
        $this->addRelation('UserMessageRelatedByFromEventUserid', '\\API\\Models\\ORM\\User\\Message\\UserMessage', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':from_event_userid',
    1 => ':event_userid',
  ),
), null, null, 'UserMessagesRelatedByFromEventUserid', false);
        $this->addRelation('UserMessageRelatedByToEventUserid', '\\API\\Models\\ORM\\User\\Message\\UserMessage', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':to_event_userid',
    1 => ':event_userid',
  ),
), null, null, 'UserMessagesRelatedByToEventUserid', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('EventUserid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? EventUserTableMap::CLASS_DEFAULT : EventUserTableMap::OM_CLASS;
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
     * @return array           (EventUser object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventUserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventUserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventUserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventUserTableMap::OM_CLASS;
            /** @var EventUser $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventUserTableMap::addInstanceToPool($obj, $key);
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
            $key = EventUserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventUserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var EventUser $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventUserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventUserTableMap::COL_EVENT_USERID);
            $criteria->addSelectColumn(EventUserTableMap::COL_EVENTID);
            $criteria->addSelectColumn(EventUserTableMap::COL_USERID);
            $criteria->addSelectColumn(EventUserTableMap::COL_USER_ROLES);
            $criteria->addSelectColumn(EventUserTableMap::COL_BEGIN_MONEY);
            $criteria->addSelectColumn(EventUserTableMap::COL_IS_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.event_userid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.userid');
            $criteria->addSelectColumn($alias . '.user_roles');
            $criteria->addSelectColumn($alias . '.begin_money');
            $criteria->addSelectColumn($alias . '.is_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventUserTableMap::DATABASE_NAME)->getTable(EventUserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventUserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventUserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventUserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a EventUser or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or EventUser object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventUserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\ORM\Event\EventUser) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventUserTableMap::DATABASE_NAME);
            $criteria->add(EventUserTableMap::COL_EVENT_USERID, (array) $values, Criteria::IN);
        }

        $query = EventUserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventUserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventUserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventUserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EventUser or Criteria object.
     *
     * @param mixed               $criteria Criteria or EventUser object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventUserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EventUser object
        }

        if ($criteria->containsKey(EventUserTableMap::COL_EVENT_USERID) && $criteria->keyContainsValue(EventUserTableMap::COL_EVENT_USERID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventUserTableMap::COL_EVENT_USERID.')');
        }


        // Set the correct dbName
        $query = EventUserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventUserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventUserTableMap::buildTableMap();
