<?php

namespace API\Models\ORM\Event\Map;

use API\Models\ORM\Event\EventContact;
use API\Models\ORM\Event\EventContactQuery;
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
 * This class defines the structure of the 'event_contact' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventContactTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'API.Models.ORM.Event.Map.EventContactTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'event_contact';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\API\\Models\\ORM\\Event\\EventContact';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'API.Models.ORM.Event.EventContact';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the event_contactid field
     */
    const COL_EVENT_CONTACTID = 'event_contact.event_contactid';

    /**
     * the column name for the eventid field
     */
    const COL_EVENTID = 'event_contact.eventid';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'event_contact.title';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'event_contact.name';

    /**
     * the column name for the contact_person field
     */
    const COL_CONTACT_PERSON = 'event_contact.contact_person';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'event_contact.address';

    /**
     * the column name for the address2 field
     */
    const COL_ADDRESS2 = 'event_contact.address2';

    /**
     * the column name for the city field
     */
    const COL_CITY = 'event_contact.city';

    /**
     * the column name for the zip field
     */
    const COL_ZIP = 'event_contact.zip';

    /**
     * the column name for the tax_identification_nr field
     */
    const COL_TAX_IDENTIFICATION_NR = 'event_contact.tax_identification_nr';

    /**
     * the column name for the telephon field
     */
    const COL_TELEPHON = 'event_contact.telephon';

    /**
     * the column name for the fax field
     */
    const COL_FAX = 'event_contact.fax';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'event_contact.email';

    /**
     * the column name for the active field
     */
    const COL_ACTIVE = 'event_contact.active';

    /**
     * the column name for the default field
     */
    const COL_DEFAULT = 'event_contact.default';

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
        self::TYPE_PHPNAME       => array('EventContactid', 'Eventid', 'Title', 'Name', 'ContactPerson', 'Address', 'Address2', 'City', 'Zip', 'TaxIdentificationNr', 'Telephon', 'Fax', 'Email', 'Active', 'Default', ),
        self::TYPE_CAMELNAME     => array('eventContactid', 'eventid', 'title', 'name', 'contactPerson', 'address', 'address2', 'city', 'zip', 'taxIdentificationNr', 'telephon', 'fax', 'email', 'active', 'default', ),
        self::TYPE_COLNAME       => array(EventContactTableMap::COL_EVENT_CONTACTID, EventContactTableMap::COL_EVENTID, EventContactTableMap::COL_TITLE, EventContactTableMap::COL_NAME, EventContactTableMap::COL_CONTACT_PERSON, EventContactTableMap::COL_ADDRESS, EventContactTableMap::COL_ADDRESS2, EventContactTableMap::COL_CITY, EventContactTableMap::COL_ZIP, EventContactTableMap::COL_TAX_IDENTIFICATION_NR, EventContactTableMap::COL_TELEPHON, EventContactTableMap::COL_FAX, EventContactTableMap::COL_EMAIL, EventContactTableMap::COL_ACTIVE, EventContactTableMap::COL_DEFAULT, ),
        self::TYPE_FIELDNAME     => array('event_contactid', 'eventid', 'title', 'name', 'contact_person', 'address', 'address2', 'city', 'zip', 'tax_identification_nr', 'telephon', 'fax', 'email', 'active', 'default', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('EventContactid' => 0, 'Eventid' => 1, 'Title' => 2, 'Name' => 3, 'ContactPerson' => 4, 'Address' => 5, 'Address2' => 6, 'City' => 7, 'Zip' => 8, 'TaxIdentificationNr' => 9, 'Telephon' => 10, 'Fax' => 11, 'Email' => 12, 'Active' => 13, 'Default' => 14, ),
        self::TYPE_CAMELNAME     => array('eventContactid' => 0, 'eventid' => 1, 'title' => 2, 'name' => 3, 'contactPerson' => 4, 'address' => 5, 'address2' => 6, 'city' => 7, 'zip' => 8, 'taxIdentificationNr' => 9, 'telephon' => 10, 'fax' => 11, 'email' => 12, 'active' => 13, 'default' => 14, ),
        self::TYPE_COLNAME       => array(EventContactTableMap::COL_EVENT_CONTACTID => 0, EventContactTableMap::COL_EVENTID => 1, EventContactTableMap::COL_TITLE => 2, EventContactTableMap::COL_NAME => 3, EventContactTableMap::COL_CONTACT_PERSON => 4, EventContactTableMap::COL_ADDRESS => 5, EventContactTableMap::COL_ADDRESS2 => 6, EventContactTableMap::COL_CITY => 7, EventContactTableMap::COL_ZIP => 8, EventContactTableMap::COL_TAX_IDENTIFICATION_NR => 9, EventContactTableMap::COL_TELEPHON => 10, EventContactTableMap::COL_FAX => 11, EventContactTableMap::COL_EMAIL => 12, EventContactTableMap::COL_ACTIVE => 13, EventContactTableMap::COL_DEFAULT => 14, ),
        self::TYPE_FIELDNAME     => array('event_contactid' => 0, 'eventid' => 1, 'title' => 2, 'name' => 3, 'contact_person' => 4, 'address' => 5, 'address2' => 6, 'city' => 7, 'zip' => 8, 'tax_identification_nr' => 9, 'telephon' => 10, 'fax' => 11, 'email' => 12, 'active' => 13, 'default' => 14, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
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
        $this->setName('event_contact');
        $this->setPhpName('EventContact');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\API\\Models\\ORM\\Event\\EventContact');
        $this->setPackage('API.Models.ORM.Event');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('event_contactid', 'EventContactid', 'INTEGER', true, null, null);
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
        $this->addColumn('default', 'Default', 'BOOLEAN', true, 1, null);
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
        $this->addRelation('InvoiceRelatedByCustomerEventContactid', '\\API\\Models\\ORM\\Invoice\\Invoice', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':customer_event_contactid',
    1 => ':event_contactid',
  ),
), null, null, 'InvoicesRelatedByCustomerEventContactid', false);
        $this->addRelation('InvoiceRelatedByEventContactid', '\\API\\Models\\ORM\\Invoice\\Invoice', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':event_contactid',
    1 => ':event_contactid',
  ),
), null, null, 'InvoicesRelatedByEventContactid', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? EventContactTableMap::CLASS_DEFAULT : EventContactTableMap::OM_CLASS;
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
     * @return array           (EventContact object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventContactTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventContactTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventContactTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventContactTableMap::OM_CLASS;
            /** @var EventContact $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventContactTableMap::addInstanceToPool($obj, $key);
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
            $key = EventContactTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventContactTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var EventContact $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventContactTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(EventContactTableMap::COL_EVENT_CONTACTID);
            $criteria->addSelectColumn(EventContactTableMap::COL_EVENTID);
            $criteria->addSelectColumn(EventContactTableMap::COL_TITLE);
            $criteria->addSelectColumn(EventContactTableMap::COL_NAME);
            $criteria->addSelectColumn(EventContactTableMap::COL_CONTACT_PERSON);
            $criteria->addSelectColumn(EventContactTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(EventContactTableMap::COL_ADDRESS2);
            $criteria->addSelectColumn(EventContactTableMap::COL_CITY);
            $criteria->addSelectColumn(EventContactTableMap::COL_ZIP);
            $criteria->addSelectColumn(EventContactTableMap::COL_TAX_IDENTIFICATION_NR);
            $criteria->addSelectColumn(EventContactTableMap::COL_TELEPHON);
            $criteria->addSelectColumn(EventContactTableMap::COL_FAX);
            $criteria->addSelectColumn(EventContactTableMap::COL_EMAIL);
            $criteria->addSelectColumn(EventContactTableMap::COL_ACTIVE);
            $criteria->addSelectColumn(EventContactTableMap::COL_DEFAULT);
        } else {
            $criteria->addSelectColumn($alias . '.event_contactid');
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
            $criteria->addSelectColumn($alias . '.default');
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
        return Propel::getServiceContainer()->getDatabaseMap(EventContactTableMap::DATABASE_NAME)->getTable(EventContactTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventContactTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventContactTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventContactTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a EventContact or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or EventContact object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventContactTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \API\Models\ORM\Event\EventContact) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventContactTableMap::DATABASE_NAME);
            $criteria->add(EventContactTableMap::COL_EVENT_CONTACTID, (array) $values, Criteria::IN);
        }

        $query = EventContactQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventContactTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventContactTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event_contact table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventContactQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a EventContact or Criteria object.
     *
     * @param mixed               $criteria Criteria or EventContact object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventContactTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from EventContact object
        }

        if ($criteria->containsKey(EventContactTableMap::COL_EVENT_CONTACTID) && $criteria->keyContainsValue(EventContactTableMap::COL_EVENT_CONTACTID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventContactTableMap::COL_EVENT_CONTACTID.')');
        }


        // Set the correct dbName
        $query = EventContactQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventContactTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventContactTableMap::buildTableMap();
