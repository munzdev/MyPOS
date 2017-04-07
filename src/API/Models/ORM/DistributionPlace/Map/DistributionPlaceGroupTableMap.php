<?php

namespace API\Models\ORM\DistributionPlace\Map;

use API\Models\ORM\DistributionPlace\DistributionPlaceGroup;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroupQuery;
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
 * This class defines the structure of the 'distribution_place_group' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class DistributionPlaceGroupTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.ORM.DistributionPlace.Map.DistributionPlaceGroupTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'distribution_place_group';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\ORM\\DistributionPlace\\DistributionPlaceGroup';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.ORM.DistributionPlace.DistributionPlaceGroup';

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
     * the column name for the distribution_place_groupid field
     */
    const COL_DISTRIBUTION_PLACE_GROUPID = 'distribution_place_group.distribution_place_groupid';

    /**
     * the column name for the distribution_placeid field
     */
    const COL_DISTRIBUTION_PLACEID = 'distribution_place_group.distribution_placeid';

    /**
     * the column name for the menu_groupid field
     */
    const COL_MENU_GROUPID = 'distribution_place_group.menu_groupid';

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
        self::TYPE_PHPNAME       => array('DistributionPlaceGroupid', 'DistributionPlaceid', 'MenuGroupid', ),
        self::TYPE_CAMELNAME     => array('distributionPlaceGroupid', 'distributionPlaceid', 'menuGroupid', ),
        self::TYPE_COLNAME       => array(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID, DistributionPlaceGroupTableMap::COL_MENU_GROUPID, ),
        self::TYPE_FIELDNAME     => array('distribution_place_groupid', 'distribution_placeid', 'menu_groupid', ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('DistributionPlaceGroupid' => 0, 'DistributionPlaceid' => 1, 'MenuGroupid' => 2, ),
        self::TYPE_CAMELNAME     => array('distributionPlaceGroupid' => 0, 'distributionPlaceid' => 1, 'menuGroupid' => 2, ),
        self::TYPE_COLNAME       => array(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID => 0, DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID => 1, DistributionPlaceGroupTableMap::COL_MENU_GROUPID => 2, ),
        self::TYPE_FIELDNAME     => array('distribution_place_groupid' => 0, 'distribution_placeid' => 1, 'menu_groupid' => 2, ),
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
        $this->setName('distribution_place_group');
        $this->setPhpName('DistributionPlaceGroup');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\ORM\\DistributionPlace\\DistributionPlaceGroup');
        $this->setPackage('API.Models.ORM.DistributionPlace');
        $this->setUseIdGenerator(true);
        $this->setIsCrossRef(true);
        // columns
        $this->addPrimaryKey('distribution_place_groupid', 'DistributionPlaceGroupid', 'INTEGER', true, null, null);
        $this->addForeignKey('distribution_placeid', 'DistributionPlaceid', 'INTEGER', 'distribution_place', 'distribution_placeid', true, null, null);
        $this->addForeignKey('menu_groupid', 'MenuGroupid', 'INTEGER', 'menu_group', 'menu_groupid', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('DistributionPlace', '\\API\\Models\\ORM\\DistributionPlace\\DistributionPlace', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':distribution_placeid',
    1 => ':distribution_placeid',
  ),
), null, null, null, false);
        $this->addRelation('MenuGroup', '\\API\\Models\\ORM\\Menu\\MenuGroup', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_groupid',
    1 => ':menu_groupid',
  ),
), null, null, null, false);
        $this->addRelation('DistributionPlaceTable', '\\API\\Models\\ORM\\DistributionPlace\\DistributionPlaceTable', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':distribution_place_groupid',
    1 => ':distribution_place_groupid',
  ),
), null, null, 'DistributionPlaceTables', false);
        $this->addRelation('EventTable', '\\API\\Models\\ORM\\Event\\EventTable', RelationMap::MANY_TO_MANY, array(), null, null, 'EventTables');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('DistributionPlaceGroupid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? DistributionPlaceGroupTableMap::CLASS_DEFAULT : DistributionPlaceGroupTableMap::OM_CLASS;
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
     * @return array           (DistributionPlaceGroup object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = DistributionPlaceGroupTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = DistributionPlaceGroupTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + DistributionPlaceGroupTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DistributionPlaceGroupTableMap::OM_CLASS;
            /** @var DistributionPlaceGroup $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            DistributionPlaceGroupTableMap::addInstanceToPool($obj, $key);
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
            $key = DistributionPlaceGroupTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = DistributionPlaceGroupTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var DistributionPlaceGroup $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DistributionPlaceGroupTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID);
            $criteria->addSelectColumn(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID);
            $criteria->addSelectColumn(DistributionPlaceGroupTableMap::COL_MENU_GROUPID);
        } else {
            $criteria->addSelectColumn($alias . '.distribution_place_groupid');
            $criteria->addSelectColumn($alias . '.distribution_placeid');
            $criteria->addSelectColumn($alias . '.menu_groupid');
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
        return Propel::getServiceContainer()->getDatabaseMap(DistributionPlaceGroupTableMap::DATABASE_NAME)->getTable(DistributionPlaceGroupTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(DistributionPlaceGroupTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(DistributionPlaceGroupTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new DistributionPlaceGroupTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a DistributionPlaceGroup or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or DistributionPlaceGroup object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceGroupTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\ORM\DistributionPlace\DistributionPlaceGroup) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DistributionPlaceGroupTableMap::DATABASE_NAME);
            $criteria->add(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, (array) $values, Criteria::IN);
        }

        $query = DistributionPlaceGroupQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            DistributionPlaceGroupTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                DistributionPlaceGroupTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the distribution_place_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return DistributionPlaceGroupQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a DistributionPlaceGroup or Criteria object.
     *
     * @param mixed               $criteria Criteria or DistributionPlaceGroup object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceGroupTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from DistributionPlaceGroup object
        }

        if ($criteria->containsKey(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID) && $criteria->keyContainsValue(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID.')');
        }


        // Set the correct dbName
        $query = DistributionPlaceGroupQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // DistributionPlaceGroupTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
DistributionPlaceGroupTableMap::buildTableMap();
