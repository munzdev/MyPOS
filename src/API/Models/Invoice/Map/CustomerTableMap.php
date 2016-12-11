<?php

namespace API\Models\Invoice\Map;

use API\Models\Invoice\Customer;
use API\Models\Invoice\CustomerQuery;
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
 * This class defines the structure of the 'customer' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CustomerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.Invoice.Map.CustomerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'customer';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\Invoice\\Customer';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.Invoice.Customer';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the customerid field
     */
    const COL_CUSTOMERID = 'customer.customerid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'customer.eventid';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'customer.title';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'customer.name';

    /**
     * the column name for the contact_person field
     */
    const COL_CONTACT_PERSON = 'customer.contact_person';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'customer.address';

    /**
     * the column name for the address2 field
     */
    const COL_ADDRESS2 = 'customer.address2';

    /**
     * the column name for the city field
     */
    const COL_CITY = 'customer.city';

    /**
     * the column name for the zip field
     */
    const COL_ZIP = 'customer.zip';

    /**
     * the column name for the tax_identification_nr field
     */
    const COL_TAX_IDENTIFICATION_NR = 'customer.tax_identification_nr';

    /**
     * the column name for the telephon field
     */
    const COL_TELEPHON = 'customer.telephon';

    /**
     * the column name for the fax field
     */
    const COL_FAX = 'customer.fax';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'customer.email';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'customer.active';

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
        self::TYPE_PHPNAME       => array('Customerid', 'Eventid', 'Title', 'Name', 'ContactPerson', 'Address', 'Address2', 'City', 'Zip', 'TaxIdentificationNr', 'Telephon', 'Fax', 'Email', 'Active', ),
        self::TYPE_CAMELNAME     => array('customerid', 'eventid', 'title', 'name', 'contactPerson', 'address', 'address2', 'city', 'zip', 'taxIdentificationNr', 'telephon', 'fax', 'email', 'active', ),
        self::TYPE_COLNAME       => array(CustomerTableMap::COL_CUSTOMERID, CustomerTableMap::COL_EVENTID, CustomerTableMap::COL_TITLE, CustomerTableMap::COL_NAME, CustomerTableMap::COL_CONTACT_PERSON, CustomerTableMap::COL_ADDRESS, CustomerTableMap::COL_ADDRESS2, CustomerTableMap::COL_CITY, CustomerTableMap::COL_ZIP, CustomerTableMap::COL_TAX_IDENTIFICATION_NR, CustomerTableMap::COL_TELEPHON, CustomerTableMap::COL_FAX, CustomerTableMap::COL_EMAIL, CustomerTableMap::COL_ACTIVE, ),
        self::TYPE_FIELDNAME     => array('customerid', 'eventid', 'title', 'name', 'contact_person', 'address', 'address2', 'city', 'zip', 'tax_identification_nr', 'telephon', 'fax', 'email', 'active', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Customerid' => 0, 'Eventid' => 1, 'Title' => 2, 'Name' => 3, 'ContactPerson' => 4, 'Address' => 5, 'Address2' => 6, 'City' => 7, 'Zip' => 8, 'TaxIdentificationNr' => 9, 'Telephon' => 10, 'Fax' => 11, 'Email' => 12, 'Active' => 13, ),
        self::TYPE_CAMELNAME     => array('customerid' => 0, 'eventid' => 1, 'title' => 2, 'name' => 3, 'contactPerson' => 4, 'address' => 5, 'address2' => 6, 'city' => 7, 'zip' => 8, 'taxIdentificationNr' => 9, 'telephon' => 10, 'fax' => 11, 'email' => 12, 'active' => 13, ),
        self::TYPE_COLNAME       => array(CustomerTableMap::COL_CUSTOMERID => 0, CustomerTableMap::COL_EVENTID => 1, CustomerTableMap::COL_TITLE => 2, CustomerTableMap::COL_NAME => 3, CustomerTableMap::COL_CONTACT_PERSON => 4, CustomerTableMap::COL_ADDRESS => 5, CustomerTableMap::COL_ADDRESS2 => 6, CustomerTableMap::COL_CITY => 7, CustomerTableMap::COL_ZIP => 8, CustomerTableMap::COL_TAX_IDENTIFICATION_NR => 9, CustomerTableMap::COL_TELEPHON => 10, CustomerTableMap::COL_FAX => 11, CustomerTableMap::COL_EMAIL => 12, CustomerTableMap::COL_ACTIVE => 13, ),
        self::TYPE_FIELDNAME     => array('customerid' => 0, 'eventid' => 1, 'title' => 2, 'name' => 3, 'contact_person' => 4, 'address' => 5, 'address2' => 6, 'city' => 7, 'zip' => 8, 'tax_identification_nr' => 9, 'telephon' => 10, 'fax' => 11, 'email' => 12, 'active' => 13, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
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
        $this->setName('customer');
        $this->setPhpName('Customer');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\API\\Models\\Invoice\\Customer');
        $this->setPackage('API.Models.Invoice');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('customerid', 'Customerid', 'INTEGER', true, null, null);
        $this->addForeignKey('eventid', 'Eventid', 'INTEGER', 'event', 'eventid', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 32, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 128, null);
        $this->addColumn('contact_person', 'ContactPerson', 'VARCHAR', false, 128, null);
        $this->addColumn('address', 'Address', 'VARCHAR', true, 128, null);
        $this->addColumn('address2', 'Address2', 'VARCHAR', false, 128, null);
        $this->addColumn('city', 'City', 'VARCHAR', true, 64, null);
        $this->addColumn('zip', 'Zip', 'VARCHAR', true, 10, null);
        $this->addColumn('tax_identification_nr', 'TaxIdentificationNr', 'VARCHAR', false, 32, null);
        $this->addColumn('telephon', 'Telephon', 'VARCHAR', false, 32, null);
        $this->addColumn('fax', 'Fax', 'VARCHAR', false, 32, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 254, null);
        $this->addColumn('active', 'Active', 'BOOLEAN', true, 1, null);
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
        $this->addRelation('Invoice', '\\API\\Models\\Invoice\\Invoice', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':customerid',
    1 => ':customerid',
  ),
), null, null, 'Invoices', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('Customerid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? CustomerTableMap::CLASS_DEFAULT : CustomerTableMap::OM_CLASS;
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
     * @return array           (Customer object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CustomerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CustomerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CustomerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CustomerTableMap::OM_CLASS;
            /** @var Customer $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CustomerTableMap::addInstanceToPool($obj, $key);
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
            $key = CustomerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CustomerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Customer $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CustomerTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMERID);
            $criteria->addSelectColumn(CustomerTableMap::COL_EVENTID);
            $criteria->addSelectColumn(CustomerTableMap::COL_TITLE);
            $criteria->addSelectColumn(CustomerTableMap::COL_NAME);
            $criteria->addSelectColumn(CustomerTableMap::COL_CONTACT_PERSON);
            $criteria->addSelectColumn(CustomerTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(CustomerTableMap::COL_ADDRESS2);
            $criteria->addSelectColumn(CustomerTableMap::COL_CITY);
            $criteria->addSelectColumn(CustomerTableMap::COL_ZIP);
            $criteria->addSelectColumn(CustomerTableMap::COL_TAX_IDENTIFICATION_NR);
            $criteria->addSelectColumn(CustomerTableMap::COL_TELEPHON);
            $criteria->addSelectColumn(CustomerTableMap::COL_FAX);
            $criteria->addSelectColumn(CustomerTableMap::COL_EMAIL);
            $criteria->addSelectColumn(CustomerTableMap::COL_ACTIVE);
        } else {
            $criteria->addSelectColumn($alias . '.customerid');
            $criteria->addSelectColumn($alias . '.eventid');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.contact_person');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.address2');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.zip');
            $criteria->addSelectColumn($alias . '.tax_identification_nr');
            $criteria->addSelectColumn($alias . '.telephon');
            $criteria->addSelectColumn($alias . '.fax');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.active');
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
        return Propel::getServiceContainer()->getDatabaseMap(CustomerTableMap::DATABASE_NAME)->getTable(CustomerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CustomerTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CustomerTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CustomerTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Customer or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Customer object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\Invoice\Customer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CustomerTableMap::DATABASE_NAME);
            $criteria->add(CustomerTableMap::COL_CUSTOMERID, (array) $values, Criteria::IN);
        }

        $query = CustomerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CustomerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CustomerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the customer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CustomerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Customer or Criteria object.
     *
     * @param mixed               $criteria Criteria or Customer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Customer object
        }

        if ($criteria->containsKey(CustomerTableMap::COL_CUSTOMERID) && $criteria->keyContainsValue(CustomerTableMap::COL_CUSTOMERID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CustomerTableMap::COL_CUSTOMERID.')');
        }


        // Set the correct dbName
        $query = CustomerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CustomerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CustomerTableMap::buildTableMap();
