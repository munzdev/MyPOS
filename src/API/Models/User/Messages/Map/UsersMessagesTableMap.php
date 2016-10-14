<?php

namespace API\Models\User\Messages\Map;

use API\Models\User\Messages\UsersMessages;
use API\Models\User\Messages\UsersMessagesQuery;
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
 * This class defines the structure of the 'users_messages' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UsersMessagesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.User.Messages.Map.UsersMessagesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'users_messages';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\User\\Messages\\UsersMessages';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.User.Messages.UsersMessages';

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
     * the column name for the users_messageid field
     */
    const COL_USERS_MESSAGEID = 'users_messages.users_messageid';

    /**
     * the column name for the from_events_userid field
     */
    const COL_FROM_EVENTS_USERID = 'users_messages.from_events_userid';

    /**
     * the column name for the to_events_userid field
     */
    const COL_TO_EVENTS_USERID = 'users_messages.to_events_userid';

    /**
     * the column name for the message field
     */
    const COL_MESSAGE = 'users_messages.message';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'users_messages.date';

    /**
     * the column name for the readed field
     */
    const COL_READED = 'users_messages.readed';

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
        self::TYPE_PHPNAME       => array('UsersMessageid', 'FromEventsUserid', 'ToEventsUserid', 'Message', 'Date', 'Readed', ),
        self::TYPE_CAMELNAME     => array('usersMessageid', 'fromEventsUserid', 'toEventsUserid', 'message', 'date', 'readed', ),
        self::TYPE_COLNAME       => array(UsersMessagesTableMap::COL_USERS_MESSAGEID, UsersMessagesTableMap::COL_FROM_EVENTS_USERID, UsersMessagesTableMap::COL_TO_EVENTS_USERID, UsersMessagesTableMap::COL_MESSAGE, UsersMessagesTableMap::COL_DATE, UsersMessagesTableMap::COL_READED, ),
        self::TYPE_FIELDNAME     => array('users_messageid', 'from_events_userid', 'to_events_userid', 'message', 'date', 'readed', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('UsersMessageid' => 0, 'FromEventsUserid' => 1, 'ToEventsUserid' => 2, 'Message' => 3, 'Date' => 4, 'Readed' => 5, ),
        self::TYPE_CAMELNAME     => array('usersMessageid' => 0, 'fromEventsUserid' => 1, 'toEventsUserid' => 2, 'message' => 3, 'date' => 4, 'readed' => 5, ),
        self::TYPE_COLNAME       => array(UsersMessagesTableMap::COL_USERS_MESSAGEID => 0, UsersMessagesTableMap::COL_FROM_EVENTS_USERID => 1, UsersMessagesTableMap::COL_TO_EVENTS_USERID => 2, UsersMessagesTableMap::COL_MESSAGE => 3, UsersMessagesTableMap::COL_DATE => 4, UsersMessagesTableMap::COL_READED => 5, ),
        self::TYPE_FIELDNAME     => array('users_messageid' => 0, 'from_events_userid' => 1, 'to_events_userid' => 2, 'message' => 3, 'date' => 4, 'readed' => 5, ),
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
        $this->setName('users_messages');
        $this->setPhpName('UsersMessages');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\User\\Messages\\UsersMessages');
        $this->setPackage('API.Models.User.Messages');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('users_messageid', 'UsersMessageid', 'INTEGER', true, null, null);
        $this->addForeignKey('from_events_userid', 'FromEventsUserid', 'INTEGER', 'events_user', 'events_userid', false, null, null);
        $this->addForeignPrimaryKey('to_events_userid', 'ToEventsUserid', 'INTEGER' , 'events_user', 'events_userid', true, null, null);
        $this->addColumn('message', 'Message', 'LONGVARCHAR', true, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, null);
        $this->addColumn('readed', 'Readed', 'BOOLEAN', true, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EventsUserRelatedByFromEventsUserid', '\\API\\Models\\Event\\EventsUser', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':from_events_userid',
    1 => ':events_userid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('EventsUserRelatedByToEventsUserid', '\\API\\Models\\Event\\EventsUser', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':to_events_userid',
    1 => ':events_userid',
  ),
), 'CASCADE', null, null, false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \API\Models\User\Messages\UsersMessages $obj A \API\Models\User\Messages\UsersMessages object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getUsersMessageid() || is_scalar($obj->getUsersMessageid()) || is_callable([$obj->getUsersMessageid(), '__toString']) ? (string) $obj->getUsersMessageid() : $obj->getUsersMessageid()), (null === $obj->getToEventsUserid() || is_scalar($obj->getToEventsUserid()) || is_callable([$obj->getToEventsUserid(), '__toString']) ? (string) $obj->getToEventsUserid() : $obj->getToEventsUserid())]);
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
     * @param mixed $value A \API\Models\User\Messages\UsersMessages object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\User\Messages\UsersMessages) {
                $key = serialize([(null === $value->getUsersMessageid() || is_scalar($value->getUsersMessageid()) || is_callable([$value->getUsersMessageid(), '__toString']) ? (string) $value->getUsersMessageid() : $value->getUsersMessageid()), (null === $value->getToEventsUserid() || is_scalar($value->getToEventsUserid()) || is_callable([$value->getToEventsUserid(), '__toString']) ? (string) $value->getToEventsUserid() : $value->getToEventsUserid())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\User\Messages\UsersMessages object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('UsersMessageid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('ToEventsUserid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? UsersMessagesTableMap::CLASS_DEFAULT : UsersMessagesTableMap::OM_CLASS;
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
     * @return array           (UsersMessages object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UsersMessagesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UsersMessagesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UsersMessagesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UsersMessagesTableMap::OM_CLASS;
            /** @var UsersMessages $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UsersMessagesTableMap::addInstanceToPool($obj, $key);
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
            $key = UsersMessagesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UsersMessagesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var UsersMessages $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UsersMessagesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UsersMessagesTableMap::COL_USERS_MESSAGEID);
            $criteria->addSelectColumn(UsersMessagesTableMap::COL_FROM_EVENTS_USERID);
            $criteria->addSelectColumn(UsersMessagesTableMap::COL_TO_EVENTS_USERID);
            $criteria->addSelectColumn(UsersMessagesTableMap::COL_MESSAGE);
            $criteria->addSelectColumn(UsersMessagesTableMap::COL_DATE);
            $criteria->addSelectColumn(UsersMessagesTableMap::COL_READED);
        } else {
            $criteria->addSelectColumn($alias . '.users_messageid');
            $criteria->addSelectColumn($alias . '.from_events_userid');
            $criteria->addSelectColumn($alias . '.to_events_userid');
            $criteria->addSelectColumn($alias . '.message');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.readed');
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
        return Propel::getServiceContainer()->getDatabaseMap(UsersMessagesTableMap::DATABASE_NAME)->getTable(UsersMessagesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UsersMessagesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UsersMessagesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UsersMessagesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a UsersMessages or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or UsersMessages object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersMessagesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\User\Messages\UsersMessages) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UsersMessagesTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(UsersMessagesTableMap::COL_USERS_MESSAGEID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = UsersMessagesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UsersMessagesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UsersMessagesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the users_messages table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UsersMessagesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a UsersMessages or Criteria object.
     *
     * @param mixed               $criteria Criteria or UsersMessages object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersMessagesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from UsersMessages object
        }

        if ($criteria->containsKey(UsersMessagesTableMap::COL_USERS_MESSAGEID) && $criteria->keyContainsValue(UsersMessagesTableMap::COL_USERS_MESSAGEID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UsersMessagesTableMap::COL_USERS_MESSAGEID.')');
        }


        // Set the correct dbName
        $query = UsersMessagesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UsersMessagesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UsersMessagesTableMap::buildTableMap();