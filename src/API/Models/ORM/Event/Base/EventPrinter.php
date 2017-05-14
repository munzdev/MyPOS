<?php

namespace API\Models\ORM\Event\Base;

use \Exception;
use \PDO;
use API\Models\ORM\DistributionPlace\DistributionPlaceUser;
use API\Models\ORM\DistributionPlace\DistributionPlaceUserQuery;
use API\Models\ORM\DistributionPlace\Base\DistributionPlaceUser as BaseDistributionPlaceUser;
use API\Models\ORM\DistributionPlace\Map\DistributionPlaceUserTableMap;
use API\Models\ORM\Event\Event as ChildEvent;
use API\Models\ORM\Event\EventPrinter as ChildEventPrinter;
use API\Models\ORM\Event\EventPrinterQuery as ChildEventPrinterQuery;
use API\Models\ORM\Event\EventQuery as ChildEventQuery;
use API\Models\ORM\Event\Map\EventPrinterTableMap;
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
 * Base class that represents a row from the 'event_printer' table.
 *
 * 
 *
 * @package    propel.generator.API.Models.ORM.Event.Base
 */
abstract class EventPrinter implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\ORM\\Event\\Map\\EventPrinterTableMap';


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
     * The value for the event_printerid field.
     * 
     * @var        int
     */
    protected $event_printerid;

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
     * The value for the type field.
     * 
     * @var        int
     */
    protected $type;

    /**
     * The value for the attr1 field.
     * 
     * @var        string
     */
    protected $attr1;

    /**
     * The value for the attr2 field.
     * 
     * @var        string
     */
    protected $attr2;

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
     * @var        ChildEvent
     */
    protected $aEvent;

    /**
     * @var        ObjectCollection|DistributionPlaceUser[] Collection to store aggregation of DistributionPlaceUser objects.
     */
    protected $collDistributionPlaceUsers;
    protected $collDistributionPlaceUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionPlaceUser[]
     */
    protected $distributionPlaceUsersScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\ORM\Event\Base\EventPrinter object.
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
     * Compares this with another <code>EventPrinter</code> instance.  If
     * <code>obj</code> is an instance of <code>EventPrinter</code>, delegates to
     * <code>equals(EventPrinter)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|EventPrinter The current object, for fluid interface
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
     * Get the [event_printerid] column value.
     * 
     * @return int
     */
    public function getEventPrinterid()
    {
        return $this->event_printerid;
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
     * Get the [type] column value.
     * 
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [attr1] column value.
     * 
     * @return string
     */
    public function getAttr1()
    {
        return $this->attr1;
    }

    /**
     * Get the [attr2] column value.
     * 
     * @return string
     */
    public function getAttr2()
    {
        return $this->attr2;
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
     * Set the value of [event_printerid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setEventPrinterid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_printerid !== $v) {
            $this->event_printerid = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_EVENT_PRINTERID] = true;
        }

        return $this;
    } // setEventPrinterid()

    /**
     * Set the value of [eventid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_EVENTID] = true;
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
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [type] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [attr1] column.
     * 
     * @param string $v new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setAttr1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->attr1 !== $v) {
            $this->attr1 = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_ATTR1] = true;
        }

        return $this;
    } // setAttr1()

    /**
     * Set the value of [attr2] column.
     * 
     * @param string $v new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setAttr2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->attr2 !== $v) {
            $this->attr2 = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_ATTR2] = true;
        }

        return $this;
    } // setAttr2()

    /**
     * Sets the value of the [default] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
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
            $this->modifiedColumns[EventPrinterTableMap::COL_DEFAULT] = true;
        }

        return $this;
    } // setDefault()

    /**
     * Set the value of [characters_per_row] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function setCharactersPerRow($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->characters_per_row !== $v) {
            $this->characters_per_row = $v;
            $this->modifiedColumns[EventPrinterTableMap::COL_CHARACTERS_PER_ROW] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventPrinterTableMap::translateFieldName('EventPrinterid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_printerid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventPrinterTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventPrinterTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventPrinterTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventPrinterTableMap::translateFieldName('Attr1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->attr1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventPrinterTableMap::translateFieldName('Attr2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->attr2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventPrinterTableMap::translateFieldName('Default', TableMap::TYPE_PHPNAME, $indexType)];
            $this->default = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventPrinterTableMap::translateFieldName('CharactersPerRow', TableMap::TYPE_PHPNAME, $indexType)];
            $this->characters_per_row = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = EventPrinterTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\ORM\\Event\\EventPrinter'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(EventPrinterTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventPrinterQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvent = null;
            $this->collDistributionPlaceUsers = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EventPrinter::setDeleted()
     * @see EventPrinter::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventPrinterTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventPrinterQuery::create()
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

        if ($this->alreadyInSave) {
            return 0;
        }
 
        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventPrinterTableMap::DATABASE_NAME);
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
                EventPrinterTableMap::addInstanceToPool($this);
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

            if ($this->distributionPlaceUsersScheduledForDeletion !== null) {
                if (!$this->distributionPlaceUsersScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\DistributionPlace\DistributionPlaceUserQuery::create()
                        ->filterByPrimaryKeys($this->distributionPlaceUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionPlaceUsersScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionPlaceUsers !== null) {
                foreach ($this->collDistributionPlaceUsers as $referrerFK) {
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

        $this->modifiedColumns[EventPrinterTableMap::COL_EVENT_PRINTERID] = true;
        if (null !== $this->event_printerid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventPrinterTableMap::COL_EVENT_PRINTERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventPrinterTableMap::COL_EVENT_PRINTERID)) {
            $modifiedColumns[':p' . $index++]  = 'event_printerid';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_ATTR1)) {
            $modifiedColumns[':p' . $index++]  = 'attr1';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_ATTR2)) {
            $modifiedColumns[':p' . $index++]  = 'attr2';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_DEFAULT)) {
            $modifiedColumns[':p' . $index++]  = 'default';
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_CHARACTERS_PER_ROW)) {
            $modifiedColumns[':p' . $index++]  = 'characters_per_row';
        }

        $sql = sprintf(
            'INSERT INTO event_printer (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'event_printerid':                        
                        $stmt->bindValue($identifier, $this->event_printerid, PDO::PARAM_INT);
                        break;
                    case 'eventid':                        
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'name':                        
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'type':                        
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case 'attr1':                        
                        $stmt->bindValue($identifier, $this->attr1, PDO::PARAM_STR);
                        break;
                    case 'attr2':                        
                        $stmt->bindValue($identifier, $this->attr2, PDO::PARAM_STR);
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
        $this->setEventPrinterid($pk);

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
        $pos = EventPrinterTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventPrinterid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getType();
                break;
            case 4:
                return $this->getAttr1();
                break;
            case 5:
                return $this->getAttr2();
                break;
            case 6:
                return $this->getDefault();
                break;
            case 7:
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

        if (isset($alreadyDumpedObjects['EventPrinter'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventPrinter'][$this->hashCode()] = true;
        $keys = EventPrinterTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventPrinterid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getType(),
            $keys[4] => $this->getAttr1(),
            $keys[5] => $this->getAttr2(),
            $keys[6] => $this->getDefault(),
            $keys[7] => $this->getCharactersPerRow(),
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
            if (null !== $this->collDistributionPlaceUsers) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionPlaceUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distribution_place_users';
                        break;
                    default:
                        $key = 'DistributionPlaceUsers';
                }
        
                $result[$key] = $this->collDistributionPlaceUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\ORM\Event\EventPrinter
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventPrinterTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\ORM\Event\EventPrinter
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventPrinterid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setType($value);
                break;
            case 4:
                $this->setAttr1($value);
                break;
            case 5:
                $this->setAttr2($value);
                break;
            case 6:
                $this->setDefault($value);
                break;
            case 7:
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
        $keys = EventPrinterTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventPrinterid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setType($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAttr1($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAttr2($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setDefault($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCharactersPerRow($arr[$keys[7]]);
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
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object, for fluid interface
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
        $criteria = new Criteria(EventPrinterTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventPrinterTableMap::COL_EVENT_PRINTERID)) {
            $criteria->add(EventPrinterTableMap::COL_EVENT_PRINTERID, $this->event_printerid);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_EVENTID)) {
            $criteria->add(EventPrinterTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_NAME)) {
            $criteria->add(EventPrinterTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_TYPE)) {
            $criteria->add(EventPrinterTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_ATTR1)) {
            $criteria->add(EventPrinterTableMap::COL_ATTR1, $this->attr1);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_ATTR2)) {
            $criteria->add(EventPrinterTableMap::COL_ATTR2, $this->attr2);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_DEFAULT)) {
            $criteria->add(EventPrinterTableMap::COL_DEFAULT, $this->default);
        }
        if ($this->isColumnModified(EventPrinterTableMap::COL_CHARACTERS_PER_ROW)) {
            $criteria->add(EventPrinterTableMap::COL_CHARACTERS_PER_ROW, $this->characters_per_row);
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
        $criteria = ChildEventPrinterQuery::create();
        $criteria->add(EventPrinterTableMap::COL_EVENT_PRINTERID, $this->event_printerid);

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
        $validPk = null !== $this->getEventPrinterid();

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
        return $this->getEventPrinterid();
    }

    /**
     * Generic method to set the primary key (event_printerid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setEventPrinterid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getEventPrinterid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\ORM\Event\EventPrinter (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setName($this->getName());
        $copyObj->setType($this->getType());
        $copyObj->setAttr1($this->getAttr1());
        $copyObj->setAttr2($this->getAttr2());
        $copyObj->setDefault($this->getDefault());
        $copyObj->setCharactersPerRow($this->getCharactersPerRow());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getDistributionPlaceUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlaceUser($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventPrinterid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\ORM\Event\EventPrinter Clone of current object.
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
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
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
            $v->addEventPrinter($this);
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
                $this->aEvent->addEventPrinters($this);
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
        if ('DistributionPlaceUser' == $relationName) {
            return $this->initDistributionPlaceUsers();
        }
    }

    /**
     * Clears out the collDistributionPlaceUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionPlaceUsers()
     */
    public function clearDistributionPlaceUsers()
    {
        $this->collDistributionPlaceUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionPlaceUsers collection loaded partially.
     */
    public function resetPartialDistributionPlaceUsers($v = true)
    {
        $this->collDistributionPlaceUsersPartial = $v;
    }

    /**
     * Initializes the collDistributionPlaceUsers collection.
     *
     * By default this just sets the collDistributionPlaceUsers collection to an empty array (like clearcollDistributionPlaceUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionPlaceUsers($overrideExisting = true)
    {
        if (null !== $this->collDistributionPlaceUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionPlaceUserTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionPlaceUsers = new $collectionClassName;
        $this->collDistributionPlaceUsers->setModel('\API\Models\ORM\DistributionPlace\DistributionPlaceUser');
    }

    /**
     * Gets an array of DistributionPlaceUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventPrinter is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionPlaceUser[] List of DistributionPlaceUser objects
     * @throws PropelException
     */
    public function getDistributionPlaceUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceUsersPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceUsers) {
                // return empty collection
                $this->initDistributionPlaceUsers();
            } else {
                $collDistributionPlaceUsers = DistributionPlaceUserQuery::create(null, $criteria)
                    ->filterByEventPrinter($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionPlaceUsersPartial && count($collDistributionPlaceUsers)) {
                        $this->initDistributionPlaceUsers(false);

                        foreach ($collDistributionPlaceUsers as $obj) {
                            if (false == $this->collDistributionPlaceUsers->contains($obj)) {
                                $this->collDistributionPlaceUsers->append($obj);
                            }
                        }

                        $this->collDistributionPlaceUsersPartial = true;
                    }

                    return $collDistributionPlaceUsers;
                }

                if ($partial && $this->collDistributionPlaceUsers) {
                    foreach ($this->collDistributionPlaceUsers as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionPlaceUsers[] = $obj;
                        }
                    }
                }

                $this->collDistributionPlaceUsers = $collDistributionPlaceUsers;
                $this->collDistributionPlaceUsersPartial = false;
            }
        }

        return $this->collDistributionPlaceUsers;
    }

    /**
     * Sets a collection of DistributionPlaceUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventPrinter The current object (for fluent API support)
     */
    public function setDistributionPlaceUsers(Collection $distributionPlaceUsers, ConnectionInterface $con = null)
    {
        /** @var DistributionPlaceUser[] $distributionPlaceUsersToDelete */
        $distributionPlaceUsersToDelete = $this->getDistributionPlaceUsers(new Criteria(), $con)->diff($distributionPlaceUsers);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceUsersScheduledForDeletion = clone $distributionPlaceUsersToDelete;

        foreach ($distributionPlaceUsersToDelete as $distributionPlaceUserRemoved) {
            $distributionPlaceUserRemoved->setEventPrinter(null);
        }

        $this->collDistributionPlaceUsers = null;
        foreach ($distributionPlaceUsers as $distributionPlaceUser) {
            $this->addDistributionPlaceUser($distributionPlaceUser);
        }

        $this->collDistributionPlaceUsers = $distributionPlaceUsers;
        $this->collDistributionPlaceUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionPlaceUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionPlaceUser objects.
     * @throws PropelException
     */
    public function countDistributionPlaceUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceUsersPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionPlaceUsers());
            }

            $query = DistributionPlaceUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventPrinter($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceUsers);
    }

    /**
     * Method called to associate a DistributionPlaceUser object to this object
     * through the DistributionPlaceUser foreign key attribute.
     *
     * @param  DistributionPlaceUser $l DistributionPlaceUser
     * @return $this|\API\Models\ORM\Event\EventPrinter The current object (for fluent API support)
     */
    public function addDistributionPlaceUser(DistributionPlaceUser $l)
    {
        if ($this->collDistributionPlaceUsers === null) {
            $this->initDistributionPlaceUsers();
            $this->collDistributionPlaceUsersPartial = true;
        }

        if (!$this->collDistributionPlaceUsers->contains($l)) {
            $this->doAddDistributionPlaceUser($l);

            if ($this->distributionPlaceUsersScheduledForDeletion and $this->distributionPlaceUsersScheduledForDeletion->contains($l)) {
                $this->distributionPlaceUsersScheduledForDeletion->remove($this->distributionPlaceUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionPlaceUser $distributionPlaceUser The DistributionPlaceUser object to add.
     */
    protected function doAddDistributionPlaceUser(DistributionPlaceUser $distributionPlaceUser)
    {
        $this->collDistributionPlaceUsers[]= $distributionPlaceUser;
        $distributionPlaceUser->setEventPrinter($this);
    }

    /**
     * @param  DistributionPlaceUser $distributionPlaceUser The DistributionPlaceUser object to remove.
     * @return $this|ChildEventPrinter The current object (for fluent API support)
     */
    public function removeDistributionPlaceUser(DistributionPlaceUser $distributionPlaceUser)
    {
        if ($this->getDistributionPlaceUsers()->contains($distributionPlaceUser)) {
            $pos = $this->collDistributionPlaceUsers->search($distributionPlaceUser);
            $this->collDistributionPlaceUsers->remove($pos);
            if (null === $this->distributionPlaceUsersScheduledForDeletion) {
                $this->distributionPlaceUsersScheduledForDeletion = clone $this->collDistributionPlaceUsers;
                $this->distributionPlaceUsersScheduledForDeletion->clear();
            }
            $this->distributionPlaceUsersScheduledForDeletion[]= clone $distributionPlaceUser;
            $distributionPlaceUser->setEventPrinter(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventPrinter is new, it will return
     * an empty collection; or if this EventPrinter has previously
     * been saved, it will retrieve related DistributionPlaceUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventPrinter.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceUser[] List of DistributionPlaceUser objects
     */
    public function getDistributionPlaceUsersJoinDistributionPlace(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceUserQuery::create(null, $criteria);
        $query->joinWith('DistributionPlace', $joinBehavior);

        return $this->getDistributionPlaceUsers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventPrinter is new, it will return
     * an empty collection; or if this EventPrinter has previously
     * been saved, it will retrieve related DistributionPlaceUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventPrinter.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceUser[] List of DistributionPlaceUser objects
     */
    public function getDistributionPlaceUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getDistributionPlaceUsers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvent) {
            $this->aEvent->removeEventPrinter($this);
        }
        $this->event_printerid = null;
        $this->eventid = null;
        $this->name = null;
        $this->type = null;
        $this->attr1 = null;
        $this->attr2 = null;
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
            if ($this->collDistributionPlaceUsers) {
                foreach ($this->collDistributionPlaceUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collDistributionPlaceUsers = null;
        $this->aEvent = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventPrinterTableMap::DEFAULT_STRING_FORMAT);
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
