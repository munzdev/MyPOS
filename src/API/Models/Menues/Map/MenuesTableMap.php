<?php

namespace API\Models\Menues\Map;

use API\Models\Menues\Menues;
use API\Models\Menues\MenuesQuery;
use API\Models\Ordering\Map\OrdersDetailsMixedWithTableMap;
use API\Models\Ordering\Map\OrdersDetailsTableMap;
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
 * This class defines the structure of the 'menues' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MenuesTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Menues.Map.MenuesTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'menues';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Menues\\Menues';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Menues.Menues';

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
     * the column name for the menuid field
     */
    const COL_MENUID = 'menues.menuid';

    /**
     * the column name for the menu_groupid field
     */
    const COL_MENU_GROUPID = 'menues.menu_groupid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'menues.name';

    /**
     * the column name for the price field
     */
    const COL_PRICE = 'menues.price';

    /**
     * the column name for the availabilityid field
     */
    const COL_AVAILABILITYID = 'menues.availabilityid';

    /**
     * the column name for the availability_amount field
     */
    const COL_AVAILABILITY_AMOUNT = 'menues.availability_amount';

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
        self::TYPE_PHPNAME       => array('Menuid', 'MenuGroupid', 'Name', 'Price', 'Availabilityid', 'AvailabilityAmount', ),
        self::TYPE_CAMELNAME     => array('menuid', 'menuGroupid', 'name', 'price', 'availabilityid', 'availabilityAmount', ),
        self::TYPE_COLNAME       => array(MenuesTableMap::COL_MENUID, MenuesTableMap::COL_MENU_GROUPID, MenuesTableMap::COL_NAME, MenuesTableMap::COL_PRICE, MenuesTableMap::COL_AVAILABILITYID, MenuesTableMap::COL_AVAILABILITY_AMOUNT, ),
        self::TYPE_FIELDNAME     => array('menuid', 'menu_groupid', 'name', 'price', 'availabilityid', 'availability_amount', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Menuid' => 0, 'MenuGroupid' => 1, 'Name' => 2, 'Price' => 3, 'Availabilityid' => 4, 'AvailabilityAmount' => 5, ),
        self::TYPE_CAMELNAME     => array('menuid' => 0, 'menuGroupid' => 1, 'name' => 2, 'price' => 3, 'availabilityid' => 4, 'availabilityAmount' => 5, ),
        self::TYPE_COLNAME       => array(MenuesTableMap::COL_MENUID => 0, MenuesTableMap::COL_MENU_GROUPID => 1, MenuesTableMap::COL_NAME => 2, MenuesTableMap::COL_PRICE => 3, MenuesTableMap::COL_AVAILABILITYID => 4, MenuesTableMap::COL_AVAILABILITY_AMOUNT => 5, ),
        self::TYPE_FIELDNAME     => array('menuid' => 0, 'menu_groupid' => 1, 'name' => 2, 'price' => 3, 'availabilityid' => 4, 'availability_amount' => 5, ),
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
        $this->setName('menues');
        $this->setPhpName('Menues');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Menues\\Menues');
        $this->setPackage('API.Models.Menues');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('menuid', 'Menuid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('menu_groupid', 'MenuGroupid', 'INTEGER' , 'menu_groupes', 'menu_groupid', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('price', 'Price', 'DECIMAL', true, 7, null);
        $this->addForeignKey('availabilityid', 'Availabilityid', 'INTEGER', 'availabilitys', 'availabilityid', true, null, null);
        $this->addColumn('availability_amount', 'AvailabilityAmount', 'SMALLINT', false, 5, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Availabilitys', '\\API\\Models\\Menues\\Availabilitys', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':availabilityid',
    1 => ':availabilityid',
  ),
), null, null, null, false);
        $this->addRelation('MenuGroupes', '\\API\\Models\\Menues\\MenuGroupes', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_groupid',
    1 => ':menu_groupid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('MenuesPossibleExtras', '\\API\\Models\\Menues\\MenuesPossibleExtras', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), 'CASCADE', null, 'MenuesPossibleExtrass', false);
        $this->addRelation('MenuesPossibleSizes', '\\API\\Models\\Menues\\MenuesPossibleSizes', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), 'CASCADE', null, 'MenuesPossibleSizess', false);
        $this->addRelation('OrdersDetails', '\\API\\Models\\Ordering\\OrdersDetails', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), 'CASCADE', null, 'OrdersDetailss', false);
        $this->addRelation('OrdersDetailsMixedWith', '\\API\\Models\\Ordering\\OrdersDetailsMixedWith', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), 'CASCADE', null, 'OrdersDetailsMixedWiths', false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \API\Models\Menues\Menues $obj A \API\Models\Menues\Menues object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getMenuid() || is_scalar($obj->getMenuid()) || is_callable([$obj->getMenuid(), '__toString']) ? (string) $obj->getMenuid() : $obj->getMenuid()), (null === $obj->getMenuGroupid() || is_scalar($obj->getMenuGroupid()) || is_callable([$obj->getMenuGroupid(), '__toString']) ? (string) $obj->getMenuGroupid() : $obj->getMenuGroupid())]);
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
     * @param mixed $value A \API\Models\Menues\Menues object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \API\Models\Menues\Menues) {
                $key = serialize([(null === $value->getMenuid() || is_scalar($value->getMenuid()) || is_callable([$value->getMenuid(), '__toString']) ? (string) $value->getMenuid() : $value->getMenuid()), (null === $value->getMenuGroupid() || is_scalar($value->getMenuGroupid()) || is_callable([$value->getMenuGroupid(), '__toString']) ? (string) $value->getMenuGroupid() : $value->getMenuGroupid())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \API\Models\Menues\Menues object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }
    /**
     * Method to invalidate the instance pool of all tables related to menues     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        MenuesPossibleExtrasTableMap::clearInstancePool();
        MenuesPossibleSizesTableMap::clearInstancePool();
        OrdersDetailsTableMap::clearInstancePool();
        OrdersDetailsMixedWithTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? MenuesTableMap::CLASS_DEFAULT : MenuesTableMap::OM_CLASS;
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
     * @return array           (Menues object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MenuesTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MenuesTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MenuesTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MenuesTableMap::OM_CLASS;
            /** @var Menues $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MenuesTableMap::addInstanceToPool($obj, $key);
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
            $key = MenuesTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MenuesTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Menues $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MenuesTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MenuesTableMap::COL_MENUID);
            $criteria->addSelectColumn(MenuesTableMap::COL_MENU_GROUPID);
            $criteria->addSelectColumn(MenuesTableMap::COL_NAME);
            $criteria->addSelectColumn(MenuesTableMap::COL_PRICE);
            $criteria->addSelectColumn(MenuesTableMap::COL_AVAILABILITYID);
            $criteria->addSelectColumn(MenuesTableMap::COL_AVAILABILITY_AMOUNT);
        } else {
            $criteria->addSelectColumn($alias . '.menuid');
            $criteria->addSelectColumn($alias . '.menu_groupid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.price');
            $criteria->addSelectColumn($alias . '.availabilityid');
            $criteria->addSelectColumn($alias . '.availability_amount');
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
        return Propel::getServiceContainer()->getDatabaseMap(MenuesTableMap::DATABASE_NAME)->getTable(MenuesTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MenuesTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MenuesTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MenuesTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Menues or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Menues object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Menues\Menues) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MenuesTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(MenuesTableMap::COL_MENUID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(MenuesTableMap::COL_MENU_GROUPID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = MenuesQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MenuesTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MenuesTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the menues table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MenuesQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Menues or Criteria object.
     *
     * @param mixed               $criteria Criteria or Menues object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Menues object
        }

        if ($criteria->containsKey(MenuesTableMap::COL_MENUID) && $criteria->keyContainsValue(MenuesTableMap::COL_MENUID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MenuesTableMap::COL_MENUID.')');
        }


        // Set the correct dbName
        $query = MenuesQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MenuesTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MenuesTableMap::buildTableMap();