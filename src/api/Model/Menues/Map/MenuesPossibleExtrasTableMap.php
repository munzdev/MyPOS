<?php

namespace Model\Menues\Map;

use Model\Menues\MenuesPossibleExtras;
use Model\Menues\MenuesPossibleExtrasQuery;
use Model\Ordering\Map\OrdersDetailExtrasTableMap;
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
 * This class defines the structure of the 'menues_possible_extras' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MenuesPossibleExtrasTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Menues.Map.MenuesPossibleExtrasTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'menues_possible_extras';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Menues\\MenuesPossibleExtras';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Menues.MenuesPossibleExtras';

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
     * the column name for the menues_possible_extraid field
     */
    const COL_MENUES_POSSIBLE_EXTRAID = 'menues_possible_extras.menues_possible_extraid';

    /**
     * the column name for the menu_extraid field
     */
    const COL_MENU_EXTRAID = 'menues_possible_extras.menu_extraid';

    /**
     * the column name for the menuid field
     */
    const COL_MENUID = 'menues_possible_extras.menuid';

    /**
     * the column name for the price field
     */
    const COL_PRICE = 'menues_possible_extras.price';

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
        self::TYPE_PHPNAME       => array('MenuesPossibleExtraid', 'MenuExtraid', 'Menuid', 'Price', ),
        self::TYPE_CAMELNAME     => array('menuesPossibleExtraid', 'menuExtraid', 'menuid', 'price', ),
        self::TYPE_COLNAME       => array(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, MenuesPossibleExtrasTableMap::COL_MENUID, MenuesPossibleExtrasTableMap::COL_PRICE, ),
        self::TYPE_FIELDNAME     => array('menues_possible_extraid', 'menu_extraid', 'menuid', 'price', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('MenuesPossibleExtraid' => 0, 'MenuExtraid' => 1, 'Menuid' => 2, 'Price' => 3, ),
        self::TYPE_CAMELNAME     => array('menuesPossibleExtraid' => 0, 'menuExtraid' => 1, 'menuid' => 2, 'price' => 3, ),
        self::TYPE_COLNAME       => array(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID => 0, MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID => 1, MenuesPossibleExtrasTableMap::COL_MENUID => 2, MenuesPossibleExtrasTableMap::COL_PRICE => 3, ),
        self::TYPE_FIELDNAME     => array('menues_possible_extraid' => 0, 'menu_extraid' => 1, 'menuid' => 2, 'price' => 3, ),
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
        $this->setName('menues_possible_extras');
        $this->setPhpName('MenuesPossibleExtras');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Menues\\MenuesPossibleExtras');
        $this->setPackage('Model.Menues');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('menues_possible_extraid', 'MenuesPossibleExtraid', 'INTEGER', true, null, null);
        $this->addForeignPrimaryKey('menu_extraid', 'MenuExtraid', 'INTEGER' , 'menu_extras', 'menu_extraid', true, null, null);
        $this->addForeignPrimaryKey('menuid', 'Menuid', 'INTEGER' , 'menues', 'menuid', true, null, null);
        $this->addColumn('price', 'Price', 'DECIMAL', true, 7, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('MenuExtras', '\\Model\\Menues\\MenuExtras', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_extraid',
    1 => ':menu_extraid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('Menues', '\\Model\\Menues\\Menues', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('OrdersDetailExtras', '\\Model\\Ordering\\OrdersDetailExtras', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':menues_possible_extraid',
    1 => ':menues_possible_extraid',
  ),
), 'CASCADE', null, 'OrdersDetailExtrass', false);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Model\Menues\MenuesPossibleExtras $obj A \Model\Menues\MenuesPossibleExtras object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getMenuesPossibleExtraid() || is_scalar($obj->getMenuesPossibleExtraid()) || is_callable([$obj->getMenuesPossibleExtraid(), '__toString']) ? (string) $obj->getMenuesPossibleExtraid() : $obj->getMenuesPossibleExtraid()), (null === $obj->getMenuExtraid() || is_scalar($obj->getMenuExtraid()) || is_callable([$obj->getMenuExtraid(), '__toString']) ? (string) $obj->getMenuExtraid() : $obj->getMenuExtraid()), (null === $obj->getMenuid() || is_scalar($obj->getMenuid()) || is_callable([$obj->getMenuid(), '__toString']) ? (string) $obj->getMenuid() : $obj->getMenuid())]);
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
     * @param mixed $value A \Model\Menues\MenuesPossibleExtras object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Model\Menues\MenuesPossibleExtras) {
                $key = serialize([(null === $value->getMenuesPossibleExtraid() || is_scalar($value->getMenuesPossibleExtraid()) || is_callable([$value->getMenuesPossibleExtraid(), '__toString']) ? (string) $value->getMenuesPossibleExtraid() : $value->getMenuesPossibleExtraid()), (null === $value->getMenuExtraid() || is_scalar($value->getMenuExtraid()) || is_callable([$value->getMenuExtraid(), '__toString']) ? (string) $value->getMenuExtraid() : $value->getMenuExtraid()), (null === $value->getMenuid() || is_scalar($value->getMenuid()) || is_callable([$value->getMenuid(), '__toString']) ? (string) $value->getMenuid() : $value->getMenuid())]);

            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1]), (null === $value[2] || is_scalar($value[2]) || is_callable([$value[2], '__toString']) ? (string) $value[2] : $value[2])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Model\Menues\MenuesPossibleExtras object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }
    /**
     * Method to invalidate the instance pool of all tables related to menues_possible_extras     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        OrdersDetailExtrasTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)])]);
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
                : self::translateFieldName('MenuesPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? MenuesPossibleExtrasTableMap::CLASS_DEFAULT : MenuesPossibleExtrasTableMap::OM_CLASS;
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
     * @return array           (MenuesPossibleExtras object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MenuesPossibleExtrasTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MenuesPossibleExtrasTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MenuesPossibleExtrasTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MenuesPossibleExtrasTableMap::OM_CLASS;
            /** @var MenuesPossibleExtras $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MenuesPossibleExtrasTableMap::addInstanceToPool($obj, $key);
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
            $key = MenuesPossibleExtrasTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MenuesPossibleExtrasTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var MenuesPossibleExtras $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MenuesPossibleExtrasTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID);
            $criteria->addSelectColumn(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID);
            $criteria->addSelectColumn(MenuesPossibleExtrasTableMap::COL_MENUID);
            $criteria->addSelectColumn(MenuesPossibleExtrasTableMap::COL_PRICE);
        } else {
            $criteria->addSelectColumn($alias . '.menues_possible_extraid');
            $criteria->addSelectColumn($alias . '.menu_extraid');
            $criteria->addSelectColumn($alias . '.menuid');
            $criteria->addSelectColumn($alias . '.price');
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
        return Propel::getServiceContainer()->getDatabaseMap(MenuesPossibleExtrasTableMap::DATABASE_NAME)->getTable(MenuesPossibleExtrasTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MenuesPossibleExtrasTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MenuesPossibleExtrasTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MenuesPossibleExtrasTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a MenuesPossibleExtras or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MenuesPossibleExtras object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesPossibleExtrasTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Menues\MenuesPossibleExtras) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MenuesPossibleExtrasTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(MenuesPossibleExtrasTableMap::COL_MENUID, $value[2]));
                $criteria->addOr($criterion);
            }
        }

        $query = MenuesPossibleExtrasQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MenuesPossibleExtrasTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MenuesPossibleExtrasTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the menues_possible_extras table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MenuesPossibleExtrasQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MenuesPossibleExtras or Criteria object.
     *
     * @param mixed               $criteria Criteria or MenuesPossibleExtras object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesPossibleExtrasTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MenuesPossibleExtras object
        }

        if ($criteria->containsKey(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID) && $criteria->keyContainsValue(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID.')');
        }


        // Set the correct dbName
        $query = MenuesPossibleExtrasQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MenuesPossibleExtrasTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MenuesPossibleExtrasTableMap::buildTableMap();
