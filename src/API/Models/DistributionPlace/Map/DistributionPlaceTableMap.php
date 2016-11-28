<?php

namespace API\Models\DistributionPlace\Map;

use API\Models\DistributionPlace\DistributionPlace;
use API\Models\DistributionPlace\DistributionPlaceQuery;
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
 * This class defines the structure of the 'distribution_place' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class DistributionPlaceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.DistributionPlace.Map.DistributionPlaceTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'distribution_place';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\DistributionPlace\\DistributionPlace';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.DistributionPlace.DistributionPlace';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 3;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 3;

    /**
     * the column name for the distribution_placeid field
     */
    const COL_DISTRIBUTION_PLACEID = 'distribution_place.distribution_placeid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'distribution_place.eventid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'distribution_place.name';

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
        self::TYPE_PHPNAME       => array('DistributionPlaceid', 'Eventid', 'Name', ),
        self::TYPE_CAMELNAME     => array('distributionPlaceid', 'eventid', 'name', ),
        self::TYPE_COLNAME       => array(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, DistributionPlaceTableMap::COL_EVENTID, DistributionPlaceTableMap::COL_NAME, ),
        self::TYPE_FIELDNAME     => array('distribution_placeid', 'eventid', 'name', ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('DistributionPlaceid' => 0, 'Eventid' => 1, 'Name' => 2, ),
        self::TYPE_CAMELNAME     => array('distributionPlaceid' => 0, 'eventid' => 1, 'name' => 2, ),
        self::TYPE_COLNAME       => array(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID => 0, DistributionPlaceTableMap::COL_EVENTID => 1, DistributionPlaceTableMap::COL_NAME => 2, ),
        self::TYPE_FIELDNAME     => array('distribution_placeid' => 0, 'eventid' => 1, 'name' => 2, ),
        self::TYPE_NUM           => array(0, 1, 2, )
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
        $this->setName('distribution_place');
        $this->setPhpName('DistributionPlace');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\DistributionPlace\\DistributionPlace');
        $this->setPackage('API.Models.DistributionPlace');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('distribution_placeid', 'DistributionPlaceid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
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
        $this->addRelation('DistributionPlaceGroup', '\\API\\Models\\DistributionPlace\\DistributionPlaceGroup', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':distribution_placeid',
    1 => ':distribution_placeid',
  ),
), null, null, 'DistributionPlaceGroups', false);
        $this->addRelation('DistributionPlaceUser', '\\API\\Models\\DistributionPlace\\DistributionPlaceUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':distribution_placeid',
    1 => ':distribution_placeid',
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? DistributionPlaceTableMap::CLASS_DEFAULT : DistributionPlaceTableMap::OM_CLASS;
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
     * @return array           (DistributionPlace object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = DistributionPlaceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = DistributionPlaceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + DistributionPlaceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DistributionPlaceTableMap::OM_CLASS;
            /** @var DistributionPlace $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            DistributionPlaceTableMap::addInstanceToPool($obj, $key);
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
            $key = DistributionPlaceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = DistributionPlaceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var DistributionPlace $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DistributionPlaceTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID);
            $criteria->addSelectColumn(DistributionPlaceTableMap::COL_EVENTID);
            $criteria->addSelectColumn(DistributionPlaceTableMap::COL_NAME);
        } else {
            $criteria->addSelectColumn($alias . '.distribution_placeid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.name');
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
        return Propel::getServiceContainer()->getDatabaseMap(DistributionPlaceTableMap::DATABASE_NAME)->getTable(DistributionPlaceTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(DistributionPlaceTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(DistributionPlaceTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new DistributionPlaceTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a DistributionPlace or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or DistributionPlace object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\DistributionPlace\DistributionPlace) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DistributionPlaceTableMap::DATABASE_NAME);
            $criteria->add(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, (array) $values, Criteria::IN);
        }

        $query = DistributionPlaceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            DistributionPlaceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                DistributionPlaceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the distribution_place table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return DistributionPlaceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a DistributionPlace or Criteria object.
     *
     * @param mixed               $criteria Criteria or DistributionPlace object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from DistributionPlace object
        }

        if ($criteria->containsKey(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID) && $criteria->keyContainsValue(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID.')');
        }


        // Set the correct dbName
        $query = DistributionPlaceQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // DistributionPlaceTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
DistributionPlaceTableMap::buildTableMap();
