<?php

namespace API\Models\Event\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceTable;
use API\Models\DistributionPlace\DistributionPlaceTableQuery;
use API\Models\DistributionPlace\Base\DistributionPlaceTable as BaseDistributionPlaceTable;
use API\Models\DistributionPlace\Map\DistributionPlaceTableTableMap;
use API\Models\Event\Event as ChildEvent;
use API\Models\Event\EventQuery as ChildEventQuery;
use API\Models\Event\EventTable as ChildEventTable;
use API\Models\Event\EventTableQuery as ChildEventTableQuery;
use API\Models\Event\Map\EventTableTableMap;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderQuery;
use API\Models\Ordering\Base\Order as BaseOrder;
use API\Models\Ordering\Map\OrderTableMap;
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
 * Base class that represents a row from the 'event_table' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Event.Base
 */
abstract class EventTable implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Event\\Map\\EventTableTableMap';


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
     * The value for the event_tableid field.
     *
     * @var        int
     */
    protected $event_tableid;

    /**
     * The value for the eventid field.
     *
     * @var        int
     */
    protected $eventid;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the data field.
     *
     * @var        string
     */
    protected $data;

    /**
     * @var        ChildEvent
     */
    protected $aEvent;

    /**
     * @var        ObjectCollection|DistributionPlaceTable[] Collection to store aggregation of DistributionPlaceTable objects.
     */
    protected $collDistributionPlaceTables;
    protected $collDistributionPlaceTablesPartial;

    /**
     * @var        ObjectCollection|Order[] Collection to store aggregation of Order objects.
     */
    protected $collOrders;
    protected $collOrdersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionPlaceTable[]
     */
    protected $distributionPlaceTablesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Order[]
     */
    protected $ordersScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Event\Base\EventTable object.
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
     * Compares this with another <code>EventTable</code> instance.  If
     * <code>obj</code> is an instance of <code>EventTable</code>, delegates to
     * <code>equals(EventTable)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|EventTable The current object, for fluid interface
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
     * Get the [event_tableid] column value.
     *
     * @return int
     */
    public function getEventTableid()
    {
        return $this->event_tableid;
    }

    /**
     * Get the [eventid] column value.
     *
     * @return int
     */
    public function getEventid()
    {
        return $this->eventid;
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
     * Get the [data] column value.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of [event_tableid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     */
    public function setEventTableid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_tableid !== $v) {
            $this->event_tableid = $v;
            $this->modifiedColumns[EventTableTableMap::COL_EVENT_TABLEID] = true;
        }

        return $this;
    } // setEventTableid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventTableTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvent !== null && $this->aEvent->getEventid() !== $v) {
            $this->aEvent = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventTableTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [data] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     */
    public function setData($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->data !== $v) {
            $this->data = $v;
            $this->modifiedColumns[EventTableTableMap::COL_DATA] = true;
        }

        return $this;
    } // setData()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableTableMap::translateFieldName('EventTableid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_tableid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableTableMap::translateFieldName('Data', TableMap::TYPE_PHPNAME, $indexType)];
            $this->data = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = EventTableTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Event\\EventTable'), 0, $e);
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
        if ($this->aEvent !== null && $this->eventid !== $this->aEvent->getEventid()) {
            $this->aEvent = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(EventTableTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventTableQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvent = null;
            $this->collDistributionPlaceTables = null;

            $this->collOrders = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EventTable::setDeleted()
     * @see EventTable::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventTableQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableTableMap::DATABASE_NAME);
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
                EventTableTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aEvent !== null) {
                if ($this->aEvent->isModified() || $this->aEvent->isNew()) {
                    $affectedRows += $this->aEvent->save($con);
                }
                $this->setEvent($this->aEvent);
            }

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

            if ($this->distributionPlaceTablesScheduledForDeletion !== null) {
                if (!$this->distributionPlaceTablesScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionPlaceTableQuery::create()
                        ->filterByPrimaryKeys($this->distributionPlaceTablesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionPlaceTablesScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionPlaceTables !== null) {
                foreach ($this->collDistributionPlaceTables as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersScheduledForDeletion !== null) {
                if (!$this->ordersScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrderQuery::create()
                        ->filterByPrimaryKeys($this->ordersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersScheduledForDeletion = null;
                }
            }

            if ($this->collOrders !== null) {
                foreach ($this->collOrders as $referrerFK) {
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

        $this->modifiedColumns[EventTableTableMap::COL_EVENT_TABLEID] = true;
        if (null !== $this->event_tableid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTableTableMap::COL_EVENT_TABLEID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableTableMap::COL_EVENT_TABLEID)) {
            $modifiedColumns[':p' . $index++]  = 'event_tableid';
        }
        if ($this->isColumnModified(EventTableTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventTableTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventTableTableMap::COL_DATA)) {
            $modifiedColumns[':p' . $index++]  = 'data';
        }

        $sql = sprintf(
            'INSERT INTO event_table (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'event_tableid':
                        $stmt->bindValue($identifier, $this->event_tableid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'data':
                        $stmt->bindValue($identifier, $this->data, PDO::PARAM_STR);
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
        $this->setEventTableid($pk);

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
        $pos = EventTableTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventTableid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getData();
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

        if (isset($alreadyDumpedObjects['EventTable'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventTable'][$this->hashCode()] = true;
        $keys = EventTableTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventTableid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getData(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEvent) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'event';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event';
                        break;
                    default:
                        $key = 'Event';
                }

                $result[$key] = $this->aEvent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDistributionPlaceTables) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionPlaceTables';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distribution_place_tables';
                        break;
                    default:
                        $key = 'DistributionPlaceTables';
                }

                $result[$key] = $this->collDistributionPlaceTables->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrders) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orders';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders';
                        break;
                    default:
                        $key = 'Orders';
                }

                $result[$key] = $this->collOrders->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Event\EventTable
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Event\EventTable
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventTableid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setData($value);
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
        $keys = EventTableTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventTableid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setData($arr[$keys[3]]);
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
     * @return $this|\API\Models\Event\EventTable The current object, for fluid interface
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
        $criteria = new Criteria(EventTableTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableTableMap::COL_EVENT_TABLEID)) {
            $criteria->add(EventTableTableMap::COL_EVENT_TABLEID, $this->event_tableid);
        }
        if ($this->isColumnModified(EventTableTableMap::COL_EVENTID)) {
            $criteria->add(EventTableTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventTableTableMap::COL_NAME)) {
            $criteria->add(EventTableTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventTableTableMap::COL_DATA)) {
            $criteria->add(EventTableTableMap::COL_DATA, $this->data);
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
        $criteria = ChildEventTableQuery::create();
        $criteria->add(EventTableTableMap::COL_EVENT_TABLEID, $this->event_tableid);
        $criteria->add(EventTableTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getEventTableid() &&
            null !== $this->getEventid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_tables_events1 to table event
        if ($this->aEvent && $hash = spl_object_hash($this->aEvent)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getEventTableid();
        $pks[1] = $this->getEventid();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param      array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setEventTableid($keys[0]);
        $this->setEventid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getEventTableid()) && (null === $this->getEventid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Event\EventTable (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setName($this->getName());
        $copyObj->setData($this->getData());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getDistributionPlaceTables() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlaceTable($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrder($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventTableid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Event\EventTable Clone of current object.
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
     * Declares an association between this object and a ChildEvent object.
     *
     * @param  ChildEvent $v
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEvent(ChildEvent $v = null)
    {
        if ($v === null) {
            $this->setEventid(NULL);
        } else {
            $this->setEventid($v->getEventid());
        }

        $this->aEvent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEvent object, it will not be re-added.
        if ($v !== null) {
            $v->addEventTable($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEvent object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildEvent The associated ChildEvent object.
     * @throws PropelException
     */
    public function getEvent(ConnectionInterface $con = null)
    {
        if ($this->aEvent === null && ($this->eventid !== null)) {
            $this->aEvent = ChildEventQuery::create()->findPk($this->eventid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvent->addEventTables($this);
             */
        }

        return $this->aEvent;
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
        if ('DistributionPlaceTable' == $relationName) {
            return $this->initDistributionPlaceTables();
        }
        if ('Order' == $relationName) {
            return $this->initOrders();
        }
    }

    /**
     * Clears out the collDistributionPlaceTables collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionPlaceTables()
     */
    public function clearDistributionPlaceTables()
    {
        $this->collDistributionPlaceTables = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionPlaceTables collection loaded partially.
     */
    public function resetPartialDistributionPlaceTables($v = true)
    {
        $this->collDistributionPlaceTablesPartial = $v;
    }

    /**
     * Initializes the collDistributionPlaceTables collection.
     *
     * By default this just sets the collDistributionPlaceTables collection to an empty array (like clearcollDistributionPlaceTables());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionPlaceTables($overrideExisting = true)
    {
        if (null !== $this->collDistributionPlaceTables && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionPlaceTableTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionPlaceTables = new $collectionClassName;
        $this->collDistributionPlaceTables->setModel('\API\Models\DistributionPlace\DistributionPlaceTable');
    }

    /**
     * Gets an array of DistributionPlaceTable objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventTable is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionPlaceTable[] List of DistributionPlaceTable objects
     * @throws PropelException
     */
    public function getDistributionPlaceTables(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceTablesPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceTables || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceTables) {
                // return empty collection
                $this->initDistributionPlaceTables();
            } else {
                $collDistributionPlaceTables = DistributionPlaceTableQuery::create(null, $criteria)
                    ->filterByEventTable($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionPlaceTablesPartial && count($collDistributionPlaceTables)) {
                        $this->initDistributionPlaceTables(false);

                        foreach ($collDistributionPlaceTables as $obj) {
                            if (false == $this->collDistributionPlaceTables->contains($obj)) {
                                $this->collDistributionPlaceTables->append($obj);
                            }
                        }

                        $this->collDistributionPlaceTablesPartial = true;
                    }

                    return $collDistributionPlaceTables;
                }

                if ($partial && $this->collDistributionPlaceTables) {
                    foreach ($this->collDistributionPlaceTables as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionPlaceTables[] = $obj;
                        }
                    }
                }

                $this->collDistributionPlaceTables = $collDistributionPlaceTables;
                $this->collDistributionPlaceTablesPartial = false;
            }
        }

        return $this->collDistributionPlaceTables;
    }

    /**
     * Sets a collection of DistributionPlaceTable objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceTables A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventTable The current object (for fluent API support)
     */
    public function setDistributionPlaceTables(Collection $distributionPlaceTables, ConnectionInterface $con = null)
    {
        /** @var DistributionPlaceTable[] $distributionPlaceTablesToDelete */
        $distributionPlaceTablesToDelete = $this->getDistributionPlaceTables(new Criteria(), $con)->diff($distributionPlaceTables);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceTablesScheduledForDeletion = clone $distributionPlaceTablesToDelete;

        foreach ($distributionPlaceTablesToDelete as $distributionPlaceTableRemoved) {
            $distributionPlaceTableRemoved->setEventTable(null);
        }

        $this->collDistributionPlaceTables = null;
        foreach ($distributionPlaceTables as $distributionPlaceTable) {
            $this->addDistributionPlaceTable($distributionPlaceTable);
        }

        $this->collDistributionPlaceTables = $distributionPlaceTables;
        $this->collDistributionPlaceTablesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionPlaceTable objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionPlaceTable objects.
     * @throws PropelException
     */
    public function countDistributionPlaceTables(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceTablesPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceTables || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceTables) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionPlaceTables());
            }

            $query = DistributionPlaceTableQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventTable($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceTables);
    }

    /**
     * Method called to associate a DistributionPlaceTable object to this object
     * through the DistributionPlaceTable foreign key attribute.
     *
     * @param  DistributionPlaceTable $l DistributionPlaceTable
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     */
    public function addDistributionPlaceTable(DistributionPlaceTable $l)
    {
        if ($this->collDistributionPlaceTables === null) {
            $this->initDistributionPlaceTables();
            $this->collDistributionPlaceTablesPartial = true;
        }

        if (!$this->collDistributionPlaceTables->contains($l)) {
            $this->doAddDistributionPlaceTable($l);

            if ($this->distributionPlaceTablesScheduledForDeletion and $this->distributionPlaceTablesScheduledForDeletion->contains($l)) {
                $this->distributionPlaceTablesScheduledForDeletion->remove($this->distributionPlaceTablesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionPlaceTable $distributionPlaceTable The DistributionPlaceTable object to add.
     */
    protected function doAddDistributionPlaceTable(DistributionPlaceTable $distributionPlaceTable)
    {
        $this->collDistributionPlaceTables[]= $distributionPlaceTable;
        $distributionPlaceTable->setEventTable($this);
    }

    /**
     * @param  DistributionPlaceTable $distributionPlaceTable The DistributionPlaceTable object to remove.
     * @return $this|ChildEventTable The current object (for fluent API support)
     */
    public function removeDistributionPlaceTable(DistributionPlaceTable $distributionPlaceTable)
    {
        if ($this->getDistributionPlaceTables()->contains($distributionPlaceTable)) {
            $pos = $this->collDistributionPlaceTables->search($distributionPlaceTable);
            $this->collDistributionPlaceTables->remove($pos);
            if (null === $this->distributionPlaceTablesScheduledForDeletion) {
                $this->distributionPlaceTablesScheduledForDeletion = clone $this->collDistributionPlaceTables;
                $this->distributionPlaceTablesScheduledForDeletion->clear();
            }
            $this->distributionPlaceTablesScheduledForDeletion[]= clone $distributionPlaceTable;
            $distributionPlaceTable->setEventTable(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventTable is new, it will return
     * an empty collection; or if this EventTable has previously
     * been saved, it will retrieve related DistributionPlaceTables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventTable.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceTable[] List of DistributionPlaceTable objects
     */
    public function getDistributionPlaceTablesJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceTableQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

        return $this->getDistributionPlaceTables($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventTable is new, it will return
     * an empty collection; or if this EventTable has previously
     * been saved, it will retrieve related DistributionPlaceTables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventTable.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceTable[] List of DistributionPlaceTable objects
     */
    public function getDistributionPlaceTablesJoinDistributionPlace(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceTableQuery::create(null, $criteria);
        $query->joinWith('DistributionPlace', $joinBehavior);

        return $this->getDistributionPlaceTables($query, $con);
    }

    /**
     * Clears out the collOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrders()
     */
    public function clearOrders()
    {
        $this->collOrders = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrders collection loaded partially.
     */
    public function resetPartialOrders($v = true)
    {
        $this->collOrdersPartial = $v;
    }

    /**
     * Initializes the collOrders collection.
     *
     * By default this just sets the collOrders collection to an empty array (like clearcollOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrders($overrideExisting = true)
    {
        if (null !== $this->collOrders && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

        $this->collOrders = new $collectionClassName;
        $this->collOrders->setModel('\API\Models\Ordering\Order');
    }

    /**
     * Gets an array of Order objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventTable is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Order[] List of Order objects
     * @throws PropelException
     */
    public function getOrders(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                // return empty collection
                $this->initOrders();
            } else {
                $collOrders = OrderQuery::create(null, $criteria)
                    ->filterByEventTable($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersPartial && count($collOrders)) {
                        $this->initOrders(false);

                        foreach ($collOrders as $obj) {
                            if (false == $this->collOrders->contains($obj)) {
                                $this->collOrders->append($obj);
                            }
                        }

                        $this->collOrdersPartial = true;
                    }

                    return $collOrders;
                }

                if ($partial && $this->collOrders) {
                    foreach ($this->collOrders as $obj) {
                        if ($obj->isNew()) {
                            $collOrders[] = $obj;
                        }
                    }
                }

                $this->collOrders = $collOrders;
                $this->collOrdersPartial = false;
            }
        }

        return $this->collOrders;
    }

    /**
     * Sets a collection of Order objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orders A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventTable The current object (for fluent API support)
     */
    public function setOrders(Collection $orders, ConnectionInterface $con = null)
    {
        /** @var Order[] $ordersToDelete */
        $ordersToDelete = $this->getOrders(new Criteria(), $con)->diff($orders);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersScheduledForDeletion = clone $ordersToDelete;

        foreach ($ordersToDelete as $orderRemoved) {
            $orderRemoved->setEventTable(null);
        }

        $this->collOrders = null;
        foreach ($orders as $order) {
            $this->addOrder($order);
        }

        $this->collOrders = $orders;
        $this->collOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrder objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrder objects.
     * @throws PropelException
     */
    public function countOrders(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrders());
            }

            $query = OrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventTable($this)
                ->count($con);
        }

        return count($this->collOrders);
    }

    /**
     * Method called to associate a Order object to this object
     * through the Order foreign key attribute.
     *
     * @param  Order $l Order
     * @return $this|\API\Models\Event\EventTable The current object (for fluent API support)
     */
    public function addOrder(Order $l)
    {
        if ($this->collOrders === null) {
            $this->initOrders();
            $this->collOrdersPartial = true;
        }

        if (!$this->collOrders->contains($l)) {
            $this->doAddOrder($l);

            if ($this->ordersScheduledForDeletion and $this->ordersScheduledForDeletion->contains($l)) {
                $this->ordersScheduledForDeletion->remove($this->ordersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Order $order The Order object to add.
     */
    protected function doAddOrder(Order $order)
    {
        $this->collOrders[]= $order;
        $order->setEventTable($this);
    }

    /**
     * @param  Order $order The Order object to remove.
     * @return $this|ChildEventTable The current object (for fluent API support)
     */
    public function removeOrder(Order $order)
    {
        if ($this->getOrders()->contains($order)) {
            $pos = $this->collOrders->search($order);
            $this->collOrders->remove($pos);
            if (null === $this->ordersScheduledForDeletion) {
                $this->ordersScheduledForDeletion = clone $this->collOrders;
                $this->ordersScheduledForDeletion->clear();
            }
            $this->ordersScheduledForDeletion[]= clone $order;
            $order->setEventTable(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventTable is new, it will return
     * an empty collection; or if this EventTable has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventTable.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Order[] List of Order objects
     */
    public function getOrdersJoinEvent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventTable is new, it will return
     * an empty collection; or if this EventTable has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventTable.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Order[] List of Order objects
     */
    public function getOrdersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOrders($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvent) {
            $this->aEvent->removeEventTable($this);
        }
        $this->event_tableid = null;
        $this->eventid = null;
        $this->name = null;
        $this->data = null;
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
            if ($this->collDistributionPlaceTables) {
                foreach ($this->collDistributionPlaceTables as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrders) {
                foreach ($this->collOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collDistributionPlaceTables = null;
        $this->collOrders = null;
        $this->aEvent = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableTableMap::DEFAULT_STRING_FORMAT);
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
