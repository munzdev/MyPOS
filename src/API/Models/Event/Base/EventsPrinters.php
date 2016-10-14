<?php

namespace API\Models\Event\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlacesUsers;
use API\Models\DistributionPlace\DistributionsPlacesUsersQuery;
use API\Models\DistributionPlace\Base\DistributionsPlacesUsers as BaseDistributionsPlacesUsers;
use API\Models\DistributionPlace\Map\DistributionsPlacesUsersTableMap;
use API\Models\Event\Events as ChildEvents;
use API\Models\Event\EventsPrinters as ChildEventsPrinters;
use API\Models\Event\EventsPrintersQuery as ChildEventsPrintersQuery;
use API\Models\Event\EventsQuery as ChildEventsQuery;
use API\Models\Event\Map\EventsPrintersTableMap;
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
 * Base class that represents a row from the 'events_printers' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Event.Base
 */
abstract class EventsPrinters implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Event\\Map\\EventsPrintersTableMap';


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
     * The value for the events_printerid field.
     *
     * @var        int
     */
    protected $events_printerid;

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
     * The value for the ip field.
     *
     * @var        string
     */
    protected $ip;

    /**
     * The value for the port field.
     *
     * @var        int
     */
    protected $port;

    /**
     * The value for the default field.
     *
     * @var        boolean
     */
    protected $default;

    /**
     * The value for the characters_per_row field.
     *
     * @var        int
     */
    protected $characters_per_row;

    /**
     * @var        ChildEvents
     */
    protected $aEvents;

    /**
     * @var        ObjectCollection|DistributionsPlacesUsers[] Collection to store aggregation of DistributionsPlacesUsers objects.
     */
    protected $collDistributionsPlacesUserss;
    protected $collDistributionsPlacesUserssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionsPlacesUsers[]
     */
    protected $distributionsPlacesUserssScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Event\Base\EventsPrinters object.
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
     * Compares this with another <code>EventsPrinters</code> instance.  If
     * <code>obj</code> is an instance of <code>EventsPrinters</code>, delegates to
     * <code>equals(EventsPrinters)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|EventsPrinters The current object, for fluid interface
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
     * Get the [events_printerid] column value.
     *
     * @return int
     */
    public function getEventsPrinterid()
    {
        return $this->events_printerid;
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
     * Get the [ip] column value.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the [port] column value.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Get the [default] column value.
     *
     * @return boolean
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Get the [default] column value.
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->getDefault();
    }

    /**
     * Get the [characters_per_row] column value.
     *
     * @return int
     */
    public function getCharactersPerRow()
    {
        return $this->characters_per_row;
    }

    /**
     * Set the value of [events_printerid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setEventsPrinterid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->events_printerid !== $v) {
            $this->events_printerid = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_EVENTS_PRINTERID] = true;
        }

        return $this;
    } // setEventsPrinterid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvents !== null && $this->aEvents->getEventid() !== $v) {
            $this->aEvents = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [ip] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setIp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->ip !== $v) {
            $this->ip = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_IP] = true;
        }

        return $this;
    } // setIp()

    /**
     * Set the value of [port] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setPort($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->port !== $v) {
            $this->port = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_PORT] = true;
        }

        return $this;
    } // setPort()

    /**
     * Sets the value of the [default] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setDefault($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->default !== $v) {
            $this->default = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_DEFAULT] = true;
        }

        return $this;
    } // setDefault()

    /**
     * Set the value of [characters_per_row] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function setCharactersPerRow($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->characters_per_row !== $v) {
            $this->characters_per_row = $v;
            $this->modifiedColumns[EventsPrintersTableMap::COL_CHARACTERS_PER_ROW] = true;
        }

        return $this;
    } // setCharactersPerRow()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventsPrintersTableMap::translateFieldName('EventsPrinterid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->events_printerid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventsPrintersTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventsPrintersTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventsPrintersTableMap::translateFieldName('Ip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ip = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventsPrintersTableMap::translateFieldName('Port', TableMap::TYPE_PHPNAME, $indexType)];
            $this->port = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventsPrintersTableMap::translateFieldName('Default', TableMap::TYPE_PHPNAME, $indexType)];
            $this->default = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventsPrintersTableMap::translateFieldName('CharactersPerRow', TableMap::TYPE_PHPNAME, $indexType)];
            $this->characters_per_row = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = EventsPrintersTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Event\\EventsPrinters'), 0, $e);
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
        if ($this->aEvents !== null && $this->eventid !== $this->aEvents->getEventid()) {
            $this->aEvents = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(EventsPrintersTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventsPrintersQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvents = null;
            $this->collDistributionsPlacesUserss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EventsPrinters::setDeleted()
     * @see EventsPrinters::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsPrintersTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventsPrintersQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsPrintersTableMap::DATABASE_NAME);
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
                EventsPrintersTableMap::addInstanceToPool($this);
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

            if ($this->aEvents !== null) {
                if ($this->aEvents->isModified() || $this->aEvents->isNew()) {
                    $affectedRows += $this->aEvents->save($con);
                }
                $this->setEvents($this->aEvents);
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

            if ($this->distributionsPlacesUserssScheduledForDeletion !== null) {
                if (!$this->distributionsPlacesUserssScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionsPlacesUsersQuery::create()
                        ->filterByPrimaryKeys($this->distributionsPlacesUserssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionsPlacesUserssScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionsPlacesUserss !== null) {
                foreach ($this->collDistributionsPlacesUserss as $referrerFK) {
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

        $this->modifiedColumns[EventsPrintersTableMap::COL_EVENTS_PRINTERID] = true;
        if (null !== $this->events_printerid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventsPrintersTableMap::COL_EVENTS_PRINTERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventsPrintersTableMap::COL_EVENTS_PRINTERID)) {
            $modifiedColumns[':p' . $index++]  = 'events_printerid';
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_IP)) {
            $modifiedColumns[':p' . $index++]  = 'ip';
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_PORT)) {
            $modifiedColumns[':p' . $index++]  = 'port';
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_DEFAULT)) {
            $modifiedColumns[':p' . $index++]  = 'default';
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_CHARACTERS_PER_ROW)) {
            $modifiedColumns[':p' . $index++]  = 'characters_per_row';
        }

        $sql = sprintf(
            'INSERT INTO events_printers (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'events_printerid':
                        $stmt->bindValue($identifier, $this->events_printerid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'ip':
                        $stmt->bindValue($identifier, $this->ip, PDO::PARAM_STR);
                        break;
                    case 'port':
                        $stmt->bindValue($identifier, $this->port, PDO::PARAM_INT);
                        break;
                    case 'default':
                        $stmt->bindValue($identifier, (int) $this->default, PDO::PARAM_INT);
                        break;
                    case 'characters_per_row':
                        $stmt->bindValue($identifier, $this->characters_per_row, PDO::PARAM_INT);
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
        $this->setEventsPrinterid($pk);

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
        $pos = EventsPrintersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventsPrinterid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getIp();
                break;
            case 4:
                return $this->getPort();
                break;
            case 5:
                return $this->getDefault();
                break;
            case 6:
                return $this->getCharactersPerRow();
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

        if (isset($alreadyDumpedObjects['EventsPrinters'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventsPrinters'][$this->hashCode()] = true;
        $keys = EventsPrintersTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventsPrinterid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getIp(),
            $keys[4] => $this->getPort(),
            $keys[5] => $this->getDefault(),
            $keys[6] => $this->getCharactersPerRow(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEvents) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'events';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events';
                        break;
                    default:
                        $key = 'Events';
                }

                $result[$key] = $this->aEvents->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDistributionsPlacesUserss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionsPlacesUserss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distributions_places_userss';
                        break;
                    default:
                        $key = 'DistributionsPlacesUserss';
                }

                $result[$key] = $this->collDistributionsPlacesUserss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Event\EventsPrinters
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventsPrintersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Event\EventsPrinters
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventsPrinterid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setIp($value);
                break;
            case 4:
                $this->setPort($value);
                break;
            case 5:
                $this->setDefault($value);
                break;
            case 6:
                $this->setCharactersPerRow($value);
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
        $keys = EventsPrintersTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventsPrinterid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setIp($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPort($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDefault($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCharactersPerRow($arr[$keys[6]]);
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
     * @return $this|\API\Models\Event\EventsPrinters The current object, for fluid interface
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
        $criteria = new Criteria(EventsPrintersTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventsPrintersTableMap::COL_EVENTS_PRINTERID)) {
            $criteria->add(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $this->events_printerid);
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_EVENTID)) {
            $criteria->add(EventsPrintersTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_NAME)) {
            $criteria->add(EventsPrintersTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_IP)) {
            $criteria->add(EventsPrintersTableMap::COL_IP, $this->ip);
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_PORT)) {
            $criteria->add(EventsPrintersTableMap::COL_PORT, $this->port);
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_DEFAULT)) {
            $criteria->add(EventsPrintersTableMap::COL_DEFAULT, $this->default);
        }
        if ($this->isColumnModified(EventsPrintersTableMap::COL_CHARACTERS_PER_ROW)) {
            $criteria->add(EventsPrintersTableMap::COL_CHARACTERS_PER_ROW, $this->characters_per_row);
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
        $criteria = ChildEventsPrintersQuery::create();
        $criteria->add(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $this->events_printerid);
        $criteria->add(EventsPrintersTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getEventsPrinterid() &&
            null !== $this->getEventid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_printers_events1 to table events
        if ($this->aEvents && $hash = spl_object_hash($this->aEvents)) {
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
        $pks[0] = $this->getEventsPrinterid();
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
        $this->setEventsPrinterid($keys[0]);
        $this->setEventid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getEventsPrinterid()) && (null === $this->getEventid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Event\EventsPrinters (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setName($this->getName());
        $copyObj->setIp($this->getIp());
        $copyObj->setPort($this->getPort());
        $copyObj->setDefault($this->getDefault());
        $copyObj->setCharactersPerRow($this->getCharactersPerRow());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getDistributionsPlacesUserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionsPlacesUsers($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventsPrinterid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Event\EventsPrinters Clone of current object.
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
     * Declares an association between this object and a ChildEvents object.
     *
     * @param  ChildEvents $v
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEvents(ChildEvents $v = null)
    {
        if ($v === null) {
            $this->setEventid(NULL);
        } else {
            $this->setEventid($v->getEventid());
        }

        $this->aEvents = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEvents object, it will not be re-added.
        if ($v !== null) {
            $v->addEventsPrinters($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEvents object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildEvents The associated ChildEvents object.
     * @throws PropelException
     */
    public function getEvents(ConnectionInterface $con = null)
    {
        if ($this->aEvents === null && ($this->eventid !== null)) {
            $this->aEvents = ChildEventsQuery::create()->findPk($this->eventid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvents->addEventsPrinterss($this);
             */
        }

        return $this->aEvents;
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
        if ('DistributionsPlacesUsers' == $relationName) {
            return $this->initDistributionsPlacesUserss();
        }
    }

    /**
     * Clears out the collDistributionsPlacesUserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionsPlacesUserss()
     */
    public function clearDistributionsPlacesUserss()
    {
        $this->collDistributionsPlacesUserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionsPlacesUserss collection loaded partially.
     */
    public function resetPartialDistributionsPlacesUserss($v = true)
    {
        $this->collDistributionsPlacesUserssPartial = $v;
    }

    /**
     * Initializes the collDistributionsPlacesUserss collection.
     *
     * By default this just sets the collDistributionsPlacesUserss collection to an empty array (like clearcollDistributionsPlacesUserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionsPlacesUserss($overrideExisting = true)
    {
        if (null !== $this->collDistributionsPlacesUserss && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionsPlacesUsersTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionsPlacesUserss = new $collectionClassName;
        $this->collDistributionsPlacesUserss->setModel('\API\Models\DistributionPlace\DistributionsPlacesUsers');
    }

    /**
     * Gets an array of DistributionsPlacesUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventsPrinters is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionsPlacesUsers[] List of DistributionsPlacesUsers objects
     * @throws PropelException
     */
    public function getDistributionsPlacesUserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesUserssPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesUserss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesUserss) {
                // return empty collection
                $this->initDistributionsPlacesUserss();
            } else {
                $collDistributionsPlacesUserss = DistributionsPlacesUsersQuery::create(null, $criteria)
                    ->filterByEventsPrinters($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionsPlacesUserssPartial && count($collDistributionsPlacesUserss)) {
                        $this->initDistributionsPlacesUserss(false);

                        foreach ($collDistributionsPlacesUserss as $obj) {
                            if (false == $this->collDistributionsPlacesUserss->contains($obj)) {
                                $this->collDistributionsPlacesUserss->append($obj);
                            }
                        }

                        $this->collDistributionsPlacesUserssPartial = true;
                    }

                    return $collDistributionsPlacesUserss;
                }

                if ($partial && $this->collDistributionsPlacesUserss) {
                    foreach ($this->collDistributionsPlacesUserss as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionsPlacesUserss[] = $obj;
                        }
                    }
                }

                $this->collDistributionsPlacesUserss = $collDistributionsPlacesUserss;
                $this->collDistributionsPlacesUserssPartial = false;
            }
        }

        return $this->collDistributionsPlacesUserss;
    }

    /**
     * Sets a collection of DistributionsPlacesUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventsPrinters The current object (for fluent API support)
     */
    public function setDistributionsPlacesUserss(Collection $distributionsPlacesUserss, ConnectionInterface $con = null)
    {
        /** @var DistributionsPlacesUsers[] $distributionsPlacesUserssToDelete */
        $distributionsPlacesUserssToDelete = $this->getDistributionsPlacesUserss(new Criteria(), $con)->diff($distributionsPlacesUserss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesUserssScheduledForDeletion = clone $distributionsPlacesUserssToDelete;

        foreach ($distributionsPlacesUserssToDelete as $distributionsPlacesUsersRemoved) {
            $distributionsPlacesUsersRemoved->setEventsPrinters(null);
        }

        $this->collDistributionsPlacesUserss = null;
        foreach ($distributionsPlacesUserss as $distributionsPlacesUsers) {
            $this->addDistributionsPlacesUsers($distributionsPlacesUsers);
        }

        $this->collDistributionsPlacesUserss = $distributionsPlacesUserss;
        $this->collDistributionsPlacesUserssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionsPlacesUsers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionsPlacesUsers objects.
     * @throws PropelException
     */
    public function countDistributionsPlacesUserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesUserssPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesUserss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionsPlacesUserss());
            }

            $query = DistributionsPlacesUsersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventsPrinters($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesUserss);
    }

    /**
     * Method called to associate a DistributionsPlacesUsers object to this object
     * through the DistributionsPlacesUsers foreign key attribute.
     *
     * @param  DistributionsPlacesUsers $l DistributionsPlacesUsers
     * @return $this|\API\Models\Event\EventsPrinters The current object (for fluent API support)
     */
    public function addDistributionsPlacesUsers(DistributionsPlacesUsers $l)
    {
        if ($this->collDistributionsPlacesUserss === null) {
            $this->initDistributionsPlacesUserss();
            $this->collDistributionsPlacesUserssPartial = true;
        }

        if (!$this->collDistributionsPlacesUserss->contains($l)) {
            $this->doAddDistributionsPlacesUsers($l);

            if ($this->distributionsPlacesUserssScheduledForDeletion and $this->distributionsPlacesUserssScheduledForDeletion->contains($l)) {
                $this->distributionsPlacesUserssScheduledForDeletion->remove($this->distributionsPlacesUserssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionsPlacesUsers $distributionsPlacesUsers The DistributionsPlacesUsers object to add.
     */
    protected function doAddDistributionsPlacesUsers(DistributionsPlacesUsers $distributionsPlacesUsers)
    {
        $this->collDistributionsPlacesUserss[]= $distributionsPlacesUsers;
        $distributionsPlacesUsers->setEventsPrinters($this);
    }

    /**
     * @param  DistributionsPlacesUsers $distributionsPlacesUsers The DistributionsPlacesUsers object to remove.
     * @return $this|ChildEventsPrinters The current object (for fluent API support)
     */
    public function removeDistributionsPlacesUsers(DistributionsPlacesUsers $distributionsPlacesUsers)
    {
        if ($this->getDistributionsPlacesUserss()->contains($distributionsPlacesUsers)) {
            $pos = $this->collDistributionsPlacesUserss->search($distributionsPlacesUsers);
            $this->collDistributionsPlacesUserss->remove($pos);
            if (null === $this->distributionsPlacesUserssScheduledForDeletion) {
                $this->distributionsPlacesUserssScheduledForDeletion = clone $this->collDistributionsPlacesUserss;
                $this->distributionsPlacesUserssScheduledForDeletion->clear();
            }
            $this->distributionsPlacesUserssScheduledForDeletion[]= clone $distributionsPlacesUsers;
            $distributionsPlacesUsers->setEventsPrinters(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventsPrinters is new, it will return
     * an empty collection; or if this EventsPrinters has previously
     * been saved, it will retrieve related DistributionsPlacesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventsPrinters.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesUsers[] List of DistributionsPlacesUsers objects
     */
    public function getDistributionsPlacesUserssJoinDistributionsPlaces(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesUsersQuery::create(null, $criteria);
        $query->joinWith('DistributionsPlaces', $joinBehavior);

        return $this->getDistributionsPlacesUserss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventsPrinters is new, it will return
     * an empty collection; or if this EventsPrinters has previously
     * been saved, it will retrieve related DistributionsPlacesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventsPrinters.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesUsers[] List of DistributionsPlacesUsers objects
     */
    public function getDistributionsPlacesUserssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesUsersQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getDistributionsPlacesUserss($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvents) {
            $this->aEvents->removeEventsPrinters($this);
        }
        $this->events_printerid = null;
        $this->eventid = null;
        $this->name = null;
        $this->ip = null;
        $this->port = null;
        $this->default = null;
        $this->characters_per_row = null;
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
            if ($this->collDistributionsPlacesUserss) {
                foreach ($this->collDistributionsPlacesUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collDistributionsPlacesUserss = null;
        $this->aEvents = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventsPrintersTableMap::DEFAULT_STRING_FORMAT);
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
