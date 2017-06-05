<?php

namespace API\Models\ORM\Menu\Map;

use API\Models\ORM\Menu\MenuPossibleExtra;
use API\Models\ORM\Menu\MenuPossibleExtraQuery;
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
 * This class defines the structure of the 'menu_possible_extra' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MenuPossibleExtraTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.ORM.Menu.Map.MenuPossibleExtraTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'menu_possible_extra';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\ORM\\Menu\\MenuPossibleExtra';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.ORM.Menu.MenuPossibleExtra';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the menu_possible_extraid field
     */
    const COL_MENU_POSSIBLE_EXTRAID = 'menu_possible_extra.menu_possible_extraid';

    /**
     * the column name for the menu_extraid field
     */
    const COL_MENU_EXTRAID = 'menu_possible_extra.menu_extraid';

    /**
     * the column name for the menuid field
     */
    const COL_MENUID = 'menu_possible_extra.menuid';

    /**
     * the column name for the price field
     */
    const COL_PRICE = 'menu_possible_extra.price';

    /**
     * the column name for the is_deleted field
     */
    const COL_IS_DELETED = 'menu_possible_extra.is_deleted';

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
        self::TYPE_PHPNAME       => array('MenuPossibleExtraid', 'MenuExtraid', 'Menuid', 'Price', 'IsDeleted', ),
        self::TYPE_CAMELNAME     => array('menuPossibleExtraid', 'menuExtraid', 'menuid', 'price', 'isDeleted', ),
        self::TYPE_COLNAME       => array(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, MenuPossibleExtraTableMap::COL_MENU_EXTRAID, MenuPossibleExtraTableMap::COL_MENUID, MenuPossibleExtraTableMap::COL_PRICE, MenuPossibleExtraTableMap::COL_IS_DELETED, ),
        self::TYPE_FIELDNAME     => array('menu_possible_extraid', 'menu_extraid', 'menuid', 'price', 'is_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('MenuPossibleExtraid' => 0, 'MenuExtraid' => 1, 'Menuid' => 2, 'Price' => 3, 'IsDeleted' => 4, ),
        self::TYPE_CAMELNAME     => array('menuPossibleExtraid' => 0, 'menuExtraid' => 1, 'menuid' => 2, 'price' => 3, 'isDeleted' => 4, ),
        self::TYPE_COLNAME       => array(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID => 0, MenuPossibleExtraTableMap::COL_MENU_EXTRAID => 1, MenuPossibleExtraTableMap::COL_MENUID => 2, MenuPossibleExtraTableMap::COL_PRICE => 3, MenuPossibleExtraTableMap::COL_IS_DELETED => 4, ),
        self::TYPE_FIELDNAME     => array('menu_possible_extraid' => 0, 'menu_extraid' => 1, 'menuid' => 2, 'price' => 3, 'is_deleted' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
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
        $this->setName('menu_possible_extra');
        $this->setPhpName('MenuPossibleExtra');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\API\\Models\\ORM\\Menu\\MenuPossibleExtra');
        $this->setPackage('API.Models.ORM.Menu');
        $this->setUseIdGenerator(true);
        $this->setIsCrossRef(true);
        // columns
        $this->addPrimaryKey('menu_possible_extraid', 'MenuPossibleExtraid', 'INTEGER', true, null, null);
        $this->addForeignKey('menu_extraid', 'MenuExtraid', 'INTEGER', 'menu_extra', 'menu_extraid', true, null, null);
        $this->addForeignKey('menuid', 'Menuid', 'INTEGER', 'menu', 'menuid', true, null, null);
        $this->addColumn('price', 'Price', 'DECIMAL', true, 7, null);
        $this->addColumn('is_deleted', 'IsDeleted', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('MenuExtra', '\\API\\Models\\ORM\\Menu\\MenuExtra', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menu_extraid',
    1 => ':menu_extraid',
  ),
), null, null, null, false);
        $this->addRelation('Menu', '\\API\\Models\\ORM\\Menu\\Menu', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':menuid',
    1 => ':menuid',
  ),
), null, null, null, false);
        $this->addRelation('OrderDetailExtra', '\\API\\Models\\ORM\\Ordering\\OrderDetailExtra', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':menu_possible_extraid',
    1 => ':menu_possible_extraid',
  ),
), null, null, 'OrderDetailExtras', false);
        $this->addRelation('OrderDetail', '\\API\\Models\\ORM\\Ordering\\OrderDetail', RelationMap::MANY_TO_MANY, array(), null, null, 'OrderDetails');
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? MenuPossibleExtraTableMap::CLASS_DEFAULT : MenuPossibleExtraTableMap::OM_CLASS;
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
     * @return array           (MenuPossibleExtra object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MenuPossibleExtraTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MenuPossibleExtraTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MenuPossibleExtraTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MenuPossibleExtraTableMap::OM_CLASS;
            /** @var MenuPossibleExtra $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MenuPossibleExtraTableMap::addInstanceToPool($obj, $key);
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
            $key = MenuPossibleExtraTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MenuPossibleExtraTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var MenuPossibleExtra $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MenuPossibleExtraTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID);
            $criteria->addSelectColumn(MenuPossibleExtraTableMap::COL_MENU_EXTRAID);
            $criteria->addSelectColumn(MenuPossibleExtraTableMap::COL_MENUID);
            $criteria->addSelectColumn(MenuPossibleExtraTableMap::COL_PRICE);
            $criteria->addSelectColumn(MenuPossibleExtraTableMap::COL_IS_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.menu_possible_extraid');
            $criteria->addSelectColumn($alias . '.menu_extraid');
            $criteria->addSelectColumn($alias . '.menuid');
            $criteria->addSelectColumn($alias . '.price');
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
        return Propel::getServiceContainer()->getDatabaseMap(MenuPossibleExtraTableMap::DATABASE_NAME)->getTable(MenuPossibleExtraTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MenuPossibleExtraTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MenuPossibleExtraTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MenuPossibleExtraTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a MenuPossibleExtra or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MenuPossibleExtra object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\ORM\Menu\MenuPossibleExtra) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MenuPossibleExtraTableMap::DATABASE_NAME);
            $criteria->add(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, (array) $values, Criteria::IN);
        }

        $query = MenuPossibleExtraQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MenuPossibleExtraTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MenuPossibleExtraTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the menu_possible_extra table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MenuPossibleExtraQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MenuPossibleExtra or Criteria object.
     *
     * @param mixed               $criteria Criteria or MenuPossibleExtra object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MenuPossibleExtra object
        }

        if ($criteria->containsKey(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID) && $criteria->keyContainsValue(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID.')');
        }


        // Set the correct dbName
        $query = MenuPossibleExtraQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MenuPossibleExtraTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MenuPossibleExtraTableMap::buildTableMap();
