<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\Menues\Availabilitys as ChildAvailabilitys;
use Model\Menues\AvailabilitysQuery as ChildAvailabilitysQuery;
use Model\Menues\MenuExtras as ChildMenuExtras;
use Model\Menues\MenuExtrasQuery as ChildMenuExtrasQuery;
use Model\Menues\Menues as ChildMenues;
use Model\Menues\MenuesQuery as ChildMenuesQuery;
use Model\Menues\Map\AvailabilitysTableMap;
use Model\Menues\Map\MenuExtrasTableMap;
use Model\Menues\Map\MenuesTableMap;
use Model\Ordering\OrdersDetails;
use Model\Ordering\OrdersDetailsQuery;
use Model\Ordering\Base\OrdersDetails as BaseOrdersDetails;
use Model\Ordering\Map\OrdersDetailsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'availabilitys' table.
 *
 *
 *
 * @package    propel.generator.Model.Menues.Base
 */
abstract class Availabilitys implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Menues\\Map\\AvailabilitysTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the availabilityid field.
     *
     * @var        int
     */
    protected $availabilityid;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * @var        ObjectCollection|ChildMenuExtras[] Collection to store aggregation of ChildMenuExtras objects.
     */
    protected $collMenuExtrass;
    protected $collMenuExtrassPartial;

    /**
     * @var        ObjectCollection|ChildMenues[] Collection to store aggregation of ChildMenues objects.
     */
    protected $collMenuess;
    protected $collMenuessPartial;

    /**
     * @var        ObjectCollection|OrdersDetails[] Collection to store aggregation of OrdersDetails objects.
     */
    protected $collOrdersDetailss;
    protected $collOrdersDetailssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenuExtras[]
     */
    protected $menuExtrassScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenues[]
     */
    protected $menuessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersDetails[]
     */
    protected $ordersDetailssScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Menues\Base\Availabilitys object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Availabilitys</code> instance.  If
     * <code>obj</code> is an instance of <code>Availabilitys</code>, delegates to
     * <code>equals(Availabilitys)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Availabilitys The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [availabilityid] column value.
     *
     * @return int
     */
    public function getAvailabilityid()
    {
        return $this->availabilityid;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of [availabilityid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Menues\Availabilitys The current object (for fluent API support)
     */
    public function setAvailabilityid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availabilityid !== $v) {
            $this->availabilityid = $v;
            $this->modifiedColumns[AvailabilitysTableMap::COL_AVAILABILITYID] = true;
        }

        return $this;
    } // setAvailabilityid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Model\Menues\Availabilitys The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[AvailabilitysTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AvailabilitysTableMap::translateFieldName('Availabilityid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availabilityid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AvailabilitysTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = AvailabilitysTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Menues\\Availabilitys'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AvailabilitysTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAvailabilitysQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collMenuExtrass = null;

            $this->collMenuess = null;

            $this->collOrdersDetailss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Availabilitys::setDeleted()
     * @see Availabilitys::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AvailabilitysTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAvailabilitysQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AvailabilitysTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                AvailabilitysTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->menuExtrassScheduledForDeletion !== null) {
                if (!$this->menuExtrassScheduledForDeletion->isEmpty()) {
                    \Model\Menues\MenuExtrasQuery::create()
                        ->filterByPrimaryKeys($this->menuExtrassScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuExtrassScheduledForDeletion = null;
                }
            }

            if ($this->collMenuExtrass !== null) {
                foreach ($this->collMenuExtrass as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuessScheduledForDeletion !== null) {
                if (!$this->menuessScheduledForDeletion->isEmpty()) {
                    \Model\Menues\MenuesQuery::create()
                        ->filterByPrimaryKeys($this->menuessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuessScheduledForDeletion = null;
                }
            }

            if ($this->collMenuess !== null) {
                foreach ($this->collMenuess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersDetailssScheduledForDeletion !== null) {
                if (!$this->ordersDetailssScheduledForDeletion->isEmpty()) {
                    foreach ($this->ordersDetailssScheduledForDeletion as $ordersDetails) {
                        // need to save related object because we set the relation to null
                        $ordersDetails->save($con);
                    }
                    $this->ordersDetailssScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersDetailss !== null) {
                foreach ($this->collOrdersDetailss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[AvailabilitysTableMap::COL_AVAILABILITYID] = true;
        if (null !== $this->availabilityid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AvailabilitysTableMap::COL_AVAILABILITYID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AvailabilitysTableMap::COL_AVAILABILITYID)) {
            $modifiedColumns[':p' . $index++]  = 'availabilityid';
        }
        if ($this->isColumnModified(AvailabilitysTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO availabilitys (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'availabilityid':
                        $stmt->bindValue($identifier, $this->availabilityid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setAvailabilityid($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AvailabilitysTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getAvailabilityid();
                break;
            case 1:
                return $this->getName();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Availabilitys'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Availabilitys'][$this->hashCode()] = true;
        $keys = AvailabilitysTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getAvailabilityid(),
            $keys[1] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collMenuExtrass) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuExtrass';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_extrass';
                        break;
                    default:
                        $key = 'MenuExtrass';
                }

                $result[$key] = $this->collMenuExtrass->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menuess';
                        break;
                    default:
                        $key = 'Menuess';
                }

                $result[$key] = $this->collMenuess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrdersDetailss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersDetailss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_detailss';
                        break;
                    default:
                        $key = 'OrdersDetailss';
                }

                $result[$key] = $this->collOrdersDetailss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Model\Menues\Availabilitys
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AvailabilitysTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Menues\Availabilitys
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setAvailabilityid($value);
                break;
            case 1:
                $this->setName($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = AvailabilitysTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setAvailabilityid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Model\Menues\Availabilitys The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AvailabilitysTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AvailabilitysTableMap::COL_AVAILABILITYID)) {
            $criteria->add(AvailabilitysTableMap::COL_AVAILABILITYID, $this->availabilityid);
        }
        if ($this->isColumnModified(AvailabilitysTableMap::COL_NAME)) {
            $criteria->add(AvailabilitysTableMap::COL_NAME, $this->name);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildAvailabilitysQuery::create();
        $criteria->add(AvailabilitysTableMap::COL_AVAILABILITYID, $this->availabilityid);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getAvailabilityid();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getAvailabilityid();
    }

    /**
     * Generic method to set the primary key (availabilityid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setAvailabilityid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getAvailabilityid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Menues\Availabilitys (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMenuExtrass() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuExtras($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenues($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersDetailss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetails($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setAvailabilityid(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\Menues\Availabilitys Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MenuExtras' == $relationName) {
            return $this->initMenuExtrass();
        }
        if ('Menues' == $relationName) {
            return $this->initMenuess();
        }
        if ('OrdersDetails' == $relationName) {
            return $this->initOrdersDetailss();
        }
    }

    /**
     * Clears out the collMenuExtrass collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuExtrass()
     */
    public function clearMenuExtrass()
    {
        $this->collMenuExtrass = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuExtrass collection loaded partially.
     */
    public function resetPartialMenuExtrass($v = true)
    {
        $this->collMenuExtrassPartial = $v;
    }

    /**
     * Initializes the collMenuExtrass collection.
     *
     * By default this just sets the collMenuExtrass collection to an empty array (like clearcollMenuExtrass());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuExtrass($overrideExisting = true)
    {
        if (null !== $this->collMenuExtrass && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuExtrasTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuExtrass = new $collectionClassName;
        $this->collMenuExtrass->setModel('\Model\Menues\MenuExtras');
    }

    /**
     * Gets an array of ChildMenuExtras objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAvailabilitys is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenuExtras[] List of ChildMenuExtras objects
     * @throws PropelException
     */
    public function getMenuExtrass(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuExtrassPartial && !$this->isNew();
        if (null === $this->collMenuExtrass || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuExtrass) {
                // return empty collection
                $this->initMenuExtrass();
            } else {
                $collMenuExtrass = ChildMenuExtrasQuery::create(null, $criteria)
                    ->filterByAvailabilitys($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuExtrassPartial && count($collMenuExtrass)) {
                        $this->initMenuExtrass(false);

                        foreach ($collMenuExtrass as $obj) {
                            if (false == $this->collMenuExtrass->contains($obj)) {
                                $this->collMenuExtrass->append($obj);
                            }
                        }

                        $this->collMenuExtrassPartial = true;
                    }

                    return $collMenuExtrass;
                }

                if ($partial && $this->collMenuExtrass) {
                    foreach ($this->collMenuExtrass as $obj) {
                        if ($obj->isNew()) {
                            $collMenuExtrass[] = $obj;
                        }
                    }
                }

                $this->collMenuExtrass = $collMenuExtrass;
                $this->collMenuExtrassPartial = false;
            }
        }

        return $this->collMenuExtrass;
    }

    /**
     * Sets a collection of ChildMenuExtras objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuExtrass A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAvailabilitys The current object (for fluent API support)
     */
    public function setMenuExtrass(Collection $menuExtrass, ConnectionInterface $con = null)
    {
        /** @var ChildMenuExtras[] $menuExtrassToDelete */
        $menuExtrassToDelete = $this->getMenuExtrass(new Criteria(), $con)->diff($menuExtrass);


        $this->menuExtrassScheduledForDeletion = $menuExtrassToDelete;

        foreach ($menuExtrassToDelete as $menuExtrasRemoved) {
            $menuExtrasRemoved->setAvailabilitys(null);
        }

        $this->collMenuExtrass = null;
        foreach ($menuExtrass as $menuExtras) {
            $this->addMenuExtras($menuExtras);
        }

        $this->collMenuExtrass = $menuExtrass;
        $this->collMenuExtrassPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MenuExtras objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MenuExtras objects.
     * @throws PropelException
     */
    public function countMenuExtrass(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuExtrassPartial && !$this->isNew();
        if (null === $this->collMenuExtrass || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuExtrass) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuExtrass());
            }

            $query = ChildMenuExtrasQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAvailabilitys($this)
                ->count($con);
        }

        return count($this->collMenuExtrass);
    }

    /**
     * Method called to associate a ChildMenuExtras object to this object
     * through the ChildMenuExtras foreign key attribute.
     *
     * @param  ChildMenuExtras $l ChildMenuExtras
     * @return $this|\Model\Menues\Availabilitys The current object (for fluent API support)
     */
    public function addMenuExtras(ChildMenuExtras $l)
    {
        if ($this->collMenuExtrass === null) {
            $this->initMenuExtrass();
            $this->collMenuExtrassPartial = true;
        }

        if (!$this->collMenuExtrass->contains($l)) {
            $this->doAddMenuExtras($l);

            if ($this->menuExtrassScheduledForDeletion and $this->menuExtrassScheduledForDeletion->contains($l)) {
                $this->menuExtrassScheduledForDeletion->remove($this->menuExtrassScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenuExtras $menuExtras The ChildMenuExtras object to add.
     */
    protected function doAddMenuExtras(ChildMenuExtras $menuExtras)
    {
        $this->collMenuExtrass[]= $menuExtras;
        $menuExtras->setAvailabilitys($this);
    }

    /**
     * @param  ChildMenuExtras $menuExtras The ChildMenuExtras object to remove.
     * @return $this|ChildAvailabilitys The current object (for fluent API support)
     */
    public function removeMenuExtras(ChildMenuExtras $menuExtras)
    {
        if ($this->getMenuExtrass()->contains($menuExtras)) {
            $pos = $this->collMenuExtrass->search($menuExtras);
            $this->collMenuExtrass->remove($pos);
            if (null === $this->menuExtrassScheduledForDeletion) {
                $this->menuExtrassScheduledForDeletion = clone $this->collMenuExtrass;
                $this->menuExtrassScheduledForDeletion->clear();
            }
            $this->menuExtrassScheduledForDeletion[]= clone $menuExtras;
            $menuExtras->setAvailabilitys(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related MenuExtrass from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenuExtras[] List of ChildMenuExtras objects
     */
    public function getMenuExtrassJoinEvents(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuExtrasQuery::create(null, $criteria);
        $query->joinWith('Events', $joinBehavior);

        return $this->getMenuExtrass($query, $con);
    }

    /**
     * Clears out the collMenuess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuess()
     */
    public function clearMenuess()
    {
        $this->collMenuess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuess collection loaded partially.
     */
    public function resetPartialMenuess($v = true)
    {
        $this->collMenuessPartial = $v;
    }

    /**
     * Initializes the collMenuess collection.
     *
     * By default this just sets the collMenuess collection to an empty array (like clearcollMenuess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuess($overrideExisting = true)
    {
        if (null !== $this->collMenuess && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuesTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuess = new $collectionClassName;
        $this->collMenuess->setModel('\Model\Menues\Menues');
    }

    /**
     * Gets an array of ChildMenues objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAvailabilitys is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenues[] List of ChildMenues objects
     * @throws PropelException
     */
    public function getMenuess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuessPartial && !$this->isNew();
        if (null === $this->collMenuess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuess) {
                // return empty collection
                $this->initMenuess();
            } else {
                $collMenuess = ChildMenuesQuery::create(null, $criteria)
                    ->filterByAvailabilitys($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuessPartial && count($collMenuess)) {
                        $this->initMenuess(false);

                        foreach ($collMenuess as $obj) {
                            if (false == $this->collMenuess->contains($obj)) {
                                $this->collMenuess->append($obj);
                            }
                        }

                        $this->collMenuessPartial = true;
                    }

                    return $collMenuess;
                }

                if ($partial && $this->collMenuess) {
                    foreach ($this->collMenuess as $obj) {
                        if ($obj->isNew()) {
                            $collMenuess[] = $obj;
                        }
                    }
                }

                $this->collMenuess = $collMenuess;
                $this->collMenuessPartial = false;
            }
        }

        return $this->collMenuess;
    }

    /**
     * Sets a collection of ChildMenues objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAvailabilitys The current object (for fluent API support)
     */
    public function setMenuess(Collection $menuess, ConnectionInterface $con = null)
    {
        /** @var ChildMenues[] $menuessToDelete */
        $menuessToDelete = $this->getMenuess(new Criteria(), $con)->diff($menuess);


        $this->menuessScheduledForDeletion = $menuessToDelete;

        foreach ($menuessToDelete as $menuesRemoved) {
            $menuesRemoved->setAvailabilitys(null);
        }

        $this->collMenuess = null;
        foreach ($menuess as $menues) {
            $this->addMenues($menues);
        }

        $this->collMenuess = $menuess;
        $this->collMenuessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Menues objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Menues objects.
     * @throws PropelException
     */
    public function countMenuess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuessPartial && !$this->isNew();
        if (null === $this->collMenuess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuess());
            }

            $query = ChildMenuesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAvailabilitys($this)
                ->count($con);
        }

        return count($this->collMenuess);
    }

    /**
     * Method called to associate a ChildMenues object to this object
     * through the ChildMenues foreign key attribute.
     *
     * @param  ChildMenues $l ChildMenues
     * @return $this|\Model\Menues\Availabilitys The current object (for fluent API support)
     */
    public function addMenues(ChildMenues $l)
    {
        if ($this->collMenuess === null) {
            $this->initMenuess();
            $this->collMenuessPartial = true;
        }

        if (!$this->collMenuess->contains($l)) {
            $this->doAddMenues($l);

            if ($this->menuessScheduledForDeletion and $this->menuessScheduledForDeletion->contains($l)) {
                $this->menuessScheduledForDeletion->remove($this->menuessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenues $menues The ChildMenues object to add.
     */
    protected function doAddMenues(ChildMenues $menues)
    {
        $this->collMenuess[]= $menues;
        $menues->setAvailabilitys($this);
    }

    /**
     * @param  ChildMenues $menues The ChildMenues object to remove.
     * @return $this|ChildAvailabilitys The current object (for fluent API support)
     */
    public function removeMenues(ChildMenues $menues)
    {
        if ($this->getMenuess()->contains($menues)) {
            $pos = $this->collMenuess->search($menues);
            $this->collMenuess->remove($pos);
            if (null === $this->menuessScheduledForDeletion) {
                $this->menuessScheduledForDeletion = clone $this->collMenuess;
                $this->menuessScheduledForDeletion->clear();
            }
            $this->menuessScheduledForDeletion[]= clone $menues;
            $menues->setAvailabilitys(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related Menuess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenues[] List of ChildMenues objects
     */
    public function getMenuessJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuesQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getMenuess($query, $con);
    }

    /**
     * Clears out the collOrdersDetailss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersDetailss()
     */
    public function clearOrdersDetailss()
    {
        $this->collOrdersDetailss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersDetailss collection loaded partially.
     */
    public function resetPartialOrdersDetailss($v = true)
    {
        $this->collOrdersDetailssPartial = $v;
    }

    /**
     * Initializes the collOrdersDetailss collection.
     *
     * By default this just sets the collOrdersDetailss collection to an empty array (like clearcollOrdersDetailss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersDetailss($overrideExisting = true)
    {
        if (null !== $this->collOrdersDetailss && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersDetailsTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersDetailss = new $collectionClassName;
        $this->collOrdersDetailss->setModel('\Model\Ordering\OrdersDetails');
    }

    /**
     * Gets an array of OrdersDetails objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAvailabilitys is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     * @throws PropelException
     */
    public function getOrdersDetailss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailssPartial && !$this->isNew();
        if (null === $this->collOrdersDetailss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailss) {
                // return empty collection
                $this->initOrdersDetailss();
            } else {
                $collOrdersDetailss = OrdersDetailsQuery::create(null, $criteria)
                    ->filterByAvailabilitys($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersDetailssPartial && count($collOrdersDetailss)) {
                        $this->initOrdersDetailss(false);

                        foreach ($collOrdersDetailss as $obj) {
                            if (false == $this->collOrdersDetailss->contains($obj)) {
                                $this->collOrdersDetailss->append($obj);
                            }
                        }

                        $this->collOrdersDetailssPartial = true;
                    }

                    return $collOrdersDetailss;
                }

                if ($partial && $this->collOrdersDetailss) {
                    foreach ($this->collOrdersDetailss as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersDetailss[] = $obj;
                        }
                    }
                }

                $this->collOrdersDetailss = $collOrdersDetailss;
                $this->collOrdersDetailssPartial = false;
            }
        }

        return $this->collOrdersDetailss;
    }

    /**
     * Sets a collection of OrdersDetails objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersDetailss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAvailabilitys The current object (for fluent API support)
     */
    public function setOrdersDetailss(Collection $ordersDetailss, ConnectionInterface $con = null)
    {
        /** @var OrdersDetails[] $ordersDetailssToDelete */
        $ordersDetailssToDelete = $this->getOrdersDetailss(new Criteria(), $con)->diff($ordersDetailss);


        $this->ordersDetailssScheduledForDeletion = $ordersDetailssToDelete;

        foreach ($ordersDetailssToDelete as $ordersDetailsRemoved) {
            $ordersDetailsRemoved->setAvailabilitys(null);
        }

        $this->collOrdersDetailss = null;
        foreach ($ordersDetailss as $ordersDetails) {
            $this->addOrdersDetails($ordersDetails);
        }

        $this->collOrdersDetailss = $ordersDetailss;
        $this->collOrdersDetailssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrdersDetails objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrdersDetails objects.
     * @throws PropelException
     */
    public function countOrdersDetailss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailssPartial && !$this->isNew();
        if (null === $this->collOrdersDetailss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersDetailss());
            }

            $query = OrdersDetailsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAvailabilitys($this)
                ->count($con);
        }

        return count($this->collOrdersDetailss);
    }

    /**
     * Method called to associate a OrdersDetails object to this object
     * through the OrdersDetails foreign key attribute.
     *
     * @param  OrdersDetails $l OrdersDetails
     * @return $this|\Model\Menues\Availabilitys The current object (for fluent API support)
     */
    public function addOrdersDetails(OrdersDetails $l)
    {
        if ($this->collOrdersDetailss === null) {
            $this->initOrdersDetailss();
            $this->collOrdersDetailssPartial = true;
        }

        if (!$this->collOrdersDetailss->contains($l)) {
            $this->doAddOrdersDetails($l);

            if ($this->ordersDetailssScheduledForDeletion and $this->ordersDetailssScheduledForDeletion->contains($l)) {
                $this->ordersDetailssScheduledForDeletion->remove($this->ordersDetailssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param OrdersDetails $ordersDetails The OrdersDetails object to add.
     */
    protected function doAddOrdersDetails(OrdersDetails $ordersDetails)
    {
        $this->collOrdersDetailss[]= $ordersDetails;
        $ordersDetails->setAvailabilitys($this);
    }

    /**
     * @param  OrdersDetails $ordersDetails The OrdersDetails object to remove.
     * @return $this|ChildAvailabilitys The current object (for fluent API support)
     */
    public function removeOrdersDetails(OrdersDetails $ordersDetails)
    {
        if ($this->getOrdersDetailss()->contains($ordersDetails)) {
            $pos = $this->collOrdersDetailss->search($ordersDetails);
            $this->collOrdersDetailss->remove($pos);
            if (null === $this->ordersDetailssScheduledForDeletion) {
                $this->ordersDetailssScheduledForDeletion = clone $this->collOrdersDetailss;
                $this->ordersDetailssScheduledForDeletion->clear();
            }
            $this->ordersDetailssScheduledForDeletion[]= $ordersDetails;
            $ordersDetails->setAvailabilitys(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinMenuSizes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('MenuSizes', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinMenues(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Menues', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinOrders(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Orders', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Availabilitys is new, it will return
     * an empty collection; or if this Availabilitys has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Availabilitys.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->availabilityid = null;
        $this->name = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collMenuExtrass) {
                foreach ($this->collMenuExtrass as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuess) {
                foreach ($this->collMenuess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersDetailss) {
                foreach ($this->collOrdersDetailss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMenuExtrass = null;
        $this->collMenuess = null;
        $this->collOrdersDetailss = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AvailabilitysTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
