<?php

namespace API\Models\Menu\Map;

use API\Models\Menu\MenuSize;
use API\Models\Menu\MenuSizeQuery;
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
 * This class defines the structure of the 'menu_size' table.
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class MenuSizeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Menu.Map.MenuSizeTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'menu_size';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Menu\\MenuSize';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Menu.MenuSize';

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
     * the column name for the menu_sizeid field
     */
    const COL_MENU_SIZEID = 'menu_size.menu_sizeid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'menu_size.eventid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'menu_size.name';

    /**
     * the column name for the factor field
     */
    const COL_FACTOR = 'menu_size.factor';

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
        self::TYPE_PHPNAME       => array('MenuSizeid', 'Eventid', 'Name', 'Factor', ),
        self::TYPE_CAMELNAME     => array('menuSizeid', 'eventid', 'name', 'factor', ),
        self::TYPE_COLNAME       => array(MenuSizeTableMap::COL_MENU_SIZEID, MenuSizeTableMap::COL_EVENTID, MenuSizeTableMap::COL_NAME, MenuSizeTableMap::COL_FACTOR, ),
        self::TYPE_FIELDNAME     => array('menu_sizeid', 'eventid', 'name', 'factor', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME       => array('MenuSizeid' => 0, 'Eventid' => 1, 'Name' => 2, 'Factor' => 3, ),
        self::TYPE_CAMELNAME     => array('menuSizeid' => 0, 'eventid' => 1, 'name' => 2, 'factor' => 3, ),
        self::TYPE_COLNAME       => array(MenuSizeTableMap::COL_MENU_SIZEID => 0, MenuSizeTableMap::COL_EVENTID => 1, MenuSizeTableMap::COL_NAME => 2, MenuSizeTableMap::COL_FACTOR => 3, ),
        self::TYPE_FIELDNAME     => array('menu_sizeid' => 0, 'eventid' => 1, 'name' => 2, 'factor' => 3, ),
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
        $this->setName('menu_size');
        $this->setPhpName('MenuSize');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Menu\\MenuSize');
        $this->setPackage('API.Models.Menu');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('menu_sizeid', 'MenuSizeid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 32, null);
        $this->addColumn('factor', 'Factor', 'DECIMAL', true, 3, null);
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
            'MenuPossibleSize',
            '\\API\\Models\\Menu\\MenuPossibleSize',
            RelationMap::ONE_TO_MANY,
            array(
            0 =>
            array(
            0 => ':menu_sizeid',
            1 => ':menu_sizeid',
            ),
            ),
            null,
            null,
            'MenuPossibleSizes',
            false
        );
        $this->addRelation(
            'OrderDetail',
            '\\API\\Models\\Ordering\\OrderDetail',
            RelationMap::ONE_TO_MANY,
            array(
            0 =>
            array(
            0 => ':menu_sizeid',
            1 => ':menu_sizeid',
            ),
            ),
            null,
            null,
            'OrderDetails',
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? MenuSizeTableMap::CLASS_DEFAULT : MenuSizeTableMap::OM_CLASS;
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
     * @return array           (MenuSize object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MenuSizeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MenuSizeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MenuSizeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MenuSizeTableMap::OM_CLASS;
            /**
 * @var MenuSize $obj
*/
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MenuSizeTableMap::addInstanceToPool($obj, $key);
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
            $key = MenuSizeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MenuSizeTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /**
 * @var MenuSize $obj
*/
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MenuSizeTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MenuSizeTableMap::COL_MENU_SIZEID);
            $criteria->addSelectColumn(MenuSizeTableMap::COL_EVENTID);
            $criteria->addSelectColumn(MenuSizeTableMap::COL_NAME);
            $criteria->addSelectColumn(MenuSizeTableMap::COL_FACTOR);
        } else {
            $criteria->addSelectColumn($alias . '.menu_sizeid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.factor');
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
        return Propel::getServiceContainer()->getDatabaseMap(MenuSizeTableMap::DATABASE_NAME)->getTable(MenuSizeTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MenuSizeTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MenuSizeTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MenuSizeTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a MenuSize or Criteria object OR a primary key value.
     *
     * @param  mixed               $values Criteria or MenuSize object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Menu\MenuSize) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MenuSizeTableMap::DATABASE_NAME);
            $criteria->add(MenuSizeTableMap::COL_MENU_SIZEID, (array) $values, Criteria::IN);
        }

        $query = MenuSizeQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MenuSizeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MenuSizeTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the menu_size table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MenuSizeQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MenuSize or Criteria object.
     *
     * @param  mixed               $criteria Criteria or MenuSize object containing data that is used to create the INSERT statement.
     * @param  ConnectionInterface $con      the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MenuSize object
        }

        if ($criteria->containsKey(MenuSizeTableMap::COL_MENU_SIZEID) && $criteria->keyContainsValue(MenuSizeTableMap::COL_MENU_SIZEID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MenuSizeTableMap::COL_MENU_SIZEID.')');
        }


        // Set the correct dbName
        $query = MenuSizeQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(
            function () use ($con, $query) {
                return $query->doInsert($con);
            }
        );
    }
} // MenuSizeTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MenuSizeTableMap::buildTableMap();
