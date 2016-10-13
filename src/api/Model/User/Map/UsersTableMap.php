<?php

namespace Model\User\Map;

use Model\DistributionPlace\Map\DistributionsPlacesUsersTableMap;
use Model\Event\Map\EventsUserTableMap;
use Model\OIP\Map\OrdersInProgressTableMap;
use Model\Ordering\Map\OrdersDetailsTableMap;
use Model\Ordering\Map\OrdersTableMap;
use Model\User\Users;
use Model\User\UsersQuery;
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
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UsersTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.User.Map.UsersTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'users';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\User\\Users';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.User.Users';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the userid field
     */
    const COL_USERID = 'users.userid';

    /**
     * the column name for the username field
     */
    const COL_USERNAME = 'users.username';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'users.password';

    /**
     * the column name for the firstname field
     */
    const COL_FIRSTNAME = 'users.firstname';

    /**
     * the column name for the lastname field
     */
    const COL_LASTNAME = 'users.lastname';

    /**
     * the column name for the autologin_hash field
     */
    const COL_AUTOLOGIN_HASH = 'users.autologin_hash';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'users.active';

    /**
     * the column name for the phonenumber field
     */
    const COL_PHONENUMBER = 'users.phonenumber';

    /**
     * the column name for the call_request field
     */
    const COL_CALL_REQUEST = 'users.call_request';

    /**
     * the column name for the is_admin field
     */
    const COL_IS_ADMIN = 'users.is_admin';

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
        self::TYPE_PHPNAME       => array('Userid', 'Username', 'Password', 'Firstname', 'Lastname', 'AutologinHash', 'Active', 'Phonenumber', 'CallRequest', 'IsAdmin', ),
        self::TYPE_CAMELNAME     => array('userid', 'username', 'password', 'firstname', 'lastname', 'autologinHash', 'active', 'phonenumber', 'callRequest', 'isAdmin', ),
        self::TYPE_COLNAME       => array(UsersTableMap::COL_USERID, UsersTableMap::COL_USERNAME, UsersTableMap::COL_PASSWORD, UsersTableMap::COL_FIRSTNAME, UsersTableMap::COL_LASTNAME, UsersTableMap::COL_AUTOLOGIN_HASH, UsersTableMap::COL_ACTIVE, UsersTableMap::COL_PHONENUMBER, UsersTableMap::COL_CALL_REQUEST, UsersTableMap::COL_IS_ADMIN, ),
        self::TYPE_FIELDNAME     => array('userid', 'username', 'password', 'firstname', 'lastname', 'autologin_hash', 'active', 'phonenumber', 'call_request', 'is_admin', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Userid' => 0, 'Username' => 1, 'Password' => 2, 'Firstname' => 3, 'Lastname' => 4, 'AutologinHash' => 5, 'Active' => 6, 'Phonenumber' => 7, 'CallRequest' => 8, 'IsAdmin' => 9, ),
        self::TYPE_CAMELNAME     => array('userid' => 0, 'username' => 1, 'password' => 2, 'firstname' => 3, 'lastname' => 4, 'autologinHash' => 5, 'active' => 6, 'phonenumber' => 7, 'callRequest' => 8, 'isAdmin' => 9, ),
        self::TYPE_COLNAME       => array(UsersTableMap::COL_USERID => 0, UsersTableMap::COL_USERNAME => 1, UsersTableMap::COL_PASSWORD => 2, UsersTableMap::COL_FIRSTNAME => 3, UsersTableMap::COL_LASTNAME => 4, UsersTableMap::COL_AUTOLOGIN_HASH => 5, UsersTableMap::COL_ACTIVE => 6, UsersTableMap::COL_PHONENUMBER => 7, UsersTableMap::COL_CALL_REQUEST => 8, UsersTableMap::COL_IS_ADMIN => 9, ),
        self::TYPE_FIELDNAME     => array('userid' => 0, 'username' => 1, 'password' => 2, 'firstname' => 3, 'lastname' => 4, 'autologin_hash' => 5, 'active' => 6, 'phonenumber' => 7, 'call_request' => 8, 'is_admin' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
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
        $this->setName('users');
        $this->setPhpName('Users');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\User\\Users');
        $this->setPackage('Model.User');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('userid', 'Userid', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 64, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 64, null);
        $this->addColumn('firstname', 'Firstname', 'VARCHAR', true, 64, null);
        $this->addColumn('lastname', 'Lastname', 'VARCHAR', true, 64, null);
        $this->addColumn('autologin_hash', 'AutologinHash', 'VARCHAR', false, 255, null);
        $this->addColumn('active', 'Active', 'TINYINT', true, null, null);
        $this->addColumn('phonenumber', 'Phonenumber', 'VARCHAR', true, 45, null);
        $this->addColumn('call_request', 'CallRequest', 'TIMESTAMP', false, null, null);
        $this->addColumn('is_admin', 'IsAdmin', 'BOOLEAN', false, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Coupons', '\\Model\\Payment\\Coupons', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':created_by',
    1 => ':userid',
  ),
), null, null, 'Couponss', false);
        $this->addRelation('DistributionsPlacesUsers', '\\Model\\DistributionPlace\\DistributionsPlacesUsers', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), 'CASCADE', null, 'DistributionsPlacesUserss', false);
        $this->addRelation('EventsUser', '\\Model\\Event\\EventsUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), 'CASCADE', null, 'EventsUsers', false);
        $this->addRelation('Invoices', '\\Model\\Invoice\\Invoices', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':cashier_userid',
    1 => ':userid',
  ),
), null, null, 'Invoicess', false);
        $this->addRelation('Orders', '\\Model\\Ordering\\Orders', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), 'CASCADE', null, 'Orderss', false);
        $this->addRelation('OrdersDetails', '\\Model\\Ordering\\OrdersDetails', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':single_price_modified_by_userid',
    1 => ':userid',
  ),
), 'CASCADE', null, 'OrdersDetailss', false);
        $this->addRelation('OrdersInProgress', '\\Model\\OIP\\OrdersInProgress', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':userid',
    1 => ':userid',
  ),
), 'CASCADE', null, 'OrdersInProgresses', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to users     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        DistributionsPlacesUsersTableMap::clearInstancePool();
        EventsUserTableMap::clearInstancePool();
        OrdersTableMap::clearInstancePool();
        OrdersDetailsTableMap::clearInstancePool();
        OrdersInProgressTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? UsersTableMap::CLASS_DEFAULT : UsersTableMap::OM_CLASS;
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
     * @return array           (Users object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UsersTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UsersTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UsersTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UsersTableMap::OM_CLASS;
            /** @var Users $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UsersTableMap::addInstanceToPool($obj, $key);
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
            $key = UsersTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UsersTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Users $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UsersTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UsersTableMap::COL_USERID);
            $criteria->addSelectColumn(UsersTableMap::COL_USERNAME);
            $criteria->addSelectColumn(UsersTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UsersTableMap::COL_FIRSTNAME);
            $criteria->addSelectColumn(UsersTableMap::COL_LASTNAME);
            $criteria->addSelectColumn(UsersTableMap::COL_AUTOLOGIN_HASH);
            $criteria->addSelectColumn(UsersTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(UsersTableMap::COL_PHONENUMBER);
            $criteria->addSelectColumn(UsersTableMap::COL_CALL_REQUEST);
            $criteria->addSelectColumn(UsersTableMap::COL_IS_ADMIN);
        } else {
            $criteria->addSelectColumn($alias . '.userid');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.firstname');
            $criteria->addSelectColumn($alias . '.lastname');
            $criteria->addSelectColumn($alias . '.autologin_hash');
            $criteria->addSelectColumn($alias . '.active');
            $criteria->addSelectColumn($alias . '.phonenumber');
            $criteria->addSelectColumn($alias . '.call_request');
            $criteria->addSelectColumn($alias . '.is_admin');
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
        return Propel::getServiceContainer()->getDatabaseMap(UsersTableMap::DATABASE_NAME)->getTable(UsersTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UsersTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UsersTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UsersTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Users or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Users object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\User\Users) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UsersTableMap::DATABASE_NAME);
            $criteria->add(UsersTableMap::COL_USERID, (array) $values, Criteria::IN);
        }

        $query = UsersQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UsersTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UsersTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UsersQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Users or Criteria object.
     *
     * @param mixed               $criteria Criteria or Users object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Users object
        }

        if ($criteria->containsKey(UsersTableMap::COL_USERID) && $criteria->keyContainsValue(UsersTableMap::COL_USERID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UsersTableMap::COL_USERID.')');
        }


        // Set the correct dbName
        $query = UsersQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UsersTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UsersTableMap::buildTableMap();
