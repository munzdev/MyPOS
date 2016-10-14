<?php

namespace API\Models\Ordering\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\Event\Events;
use API\Models\Event\EventsQuery;
use API\Models\Event\EventsTables;
use API\Models\Event\EventsTablesQuery;
use API\Models\OIP\OrdersInProgress;
use API\Models\OIP\OrdersInProgressQuery;
use API\Models\OIP\Base\OrdersInProgress as BaseOrdersInProgress;
use API\Models\OIP\Map\OrdersInProgressTableMap;
use API\Models\Ordering\Orders as ChildOrders;
use API\Models\Ordering\OrdersDetails as ChildOrdersDetails;
use API\Models\Ordering\OrdersDetailsQuery as ChildOrdersDetailsQuery;
use API\Models\Ordering\OrdersQuery as ChildOrdersQuery;
use API\Models\Ordering\Map\OrdersDetailsTableMap;
use API\Models\Ordering\Map\OrdersTableMap;
use API\Models\User\Users;
use API\Models\User\UsersQuery;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'orders' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Ordering.Base
 */
abstract class Orders implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Ordering\\Map\\OrdersTableMap';


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
     * The value for the orderid field.
     *
     * @var        int
     */
    protected $orderid;

    /**
     * The value for the eventid field.
     *
     * @var        int
     */
    protected $eventid;

    /**
     * The value for the tableid field.
     *
     * @var        int
     */
    protected $tableid;

    /**
     * The value for the userid field.
     *
     * @var        int
     */
    protected $userid;

    /**
     * The value for the ordertime field.
     *
     * @var        DateTime
     */
    protected $ordertime;

    /**
     * The value for the priority field.
     *
     * @var        int
     */
    protected $priority;

    /**
     * The value for the finished field.
     *
     * @var        DateTime
     */
    protected $finished;

    /**
     * @var        Events
     */
    protected $aEvents;

    /**
     * @var        EventsTables
     */
    protected $aEventsTables;

    /**
     * @var        Users
     */
    protected $aUsers;

    /**
     * @var        ObjectCollection|ChildOrdersDetails[] Collection to store aggregation of ChildOrdersDetails objects.
     */
    protected $collOrdersDetailss;
    protected $collOrdersDetailssPartial;

    /**
     * @var        ObjectCollection|OrdersInProgress[] Collection to store aggregation of OrdersInProgress objects.
     */
    protected $collOrdersInProgresses;
    protected $collOrdersInProgressesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrdersDetails[]
     */
    protected $ordersDetailssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersInProgress[]
     */
    protected $ordersInProgressesScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Ordering\Base\Orders object.
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
     * Compares this with another <code>Orders</code> instance.  If
     * <code>obj</code> is an instance of <code>Orders</code>, delegates to
     * <code>equals(Orders)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Orders The current object, for fluid interface
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
     * Get the [orderid] column value.
     *
     * @return int
     */
    public function getOrderid()
    {
        return $this->orderid;
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
     * Get the [tableid] column value.
     *
     * @return int
     */
    public function getTableid()
    {
        return $this->tableid;
    }

    /**
     * Get the [userid] column value.
     *
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Get the [optionally formatted] temporal [ordertime] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getOrdertime($format = NULL)
    {
        if ($format === null) {
            return $this->ordertime;
        } else {
            return $this->ordertime instanceof \DateTimeInterface ? $this->ordertime->format($format) : null;
        }
    }

    /**
     * Get the [priority] column value.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get the [optionally formatted] temporal [finished] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getFinished($format = NULL)
    {
        if ($format === null) {
            return $this->finished;
        } else {
            return $this->finished instanceof \DateTimeInterface ? $this->finished->format($format) : null;
        }
    }

    /**
     * Set the value of [orderid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setOrderid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orderid !== $v) {
            $this->orderid = $v;
            $this->modifiedColumns[OrdersTableMap::COL_ORDERID] = true;
        }

        return $this;
    } // setOrderid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[OrdersTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvents !== null && $this->aEvents->getEventid() !== $v) {
            $this->aEvents = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [tableid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setTableid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tableid !== $v) {
            $this->tableid = $v;
            $this->modifiedColumns[OrdersTableMap::COL_TABLEID] = true;
        }

        if ($this->aEventsTables !== null && $this->aEventsTables->getEventsTableid() !== $v) {
            $this->aEventsTables = null;
        }

        return $this;
    } // setTableid()

    /**
     * Set the value of [userid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[OrdersTableMap::COL_USERID] = true;
        }

        if ($this->aUsers !== null && $this->aUsers->getUserid() !== $v) {
            $this->aUsers = null;
        }

        return $this;
    } // setUserid()

    /**
     * Sets the value of [ordertime] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setOrdertime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->ordertime !== null || $dt !== null) {
            if ($this->ordertime === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->ordertime->format("Y-m-d H:i:s.u")) {
                $this->ordertime = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrdersTableMap::COL_ORDERTIME] = true;
            }
        } // if either are not null

        return $this;
    } // setOrdertime()

    /**
     * Set the value of [priority] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setPriority($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->priority !== $v) {
            $this->priority = $v;
            $this->modifiedColumns[OrdersTableMap::COL_PRIORITY] = true;
        }

        return $this;
    } // setPriority()

    /**
     * Sets the value of [finished] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function setFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->finished !== null || $dt !== null) {
            if ($this->finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->finished->format("Y-m-d H:i:s.u")) {
                $this->finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrdersTableMap::COL_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setFinished()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrdersTableMap::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orderid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrdersTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrdersTableMap::translateFieldName('Tableid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tableid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrdersTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrdersTableMap::translateFieldName('Ordertime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->ordertime = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrdersTableMap::translateFieldName('Priority', TableMap::TYPE_PHPNAME, $indexType)];
            $this->priority = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OrdersTableMap::translateFieldName('Finished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = OrdersTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Ordering\\Orders'), 0, $e);
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
        if ($this->aEventsTables !== null && $this->tableid !== $this->aEventsTables->getEventsTableid()) {
            $this->aEventsTables = null;
        }
        if ($this->aUsers !== null && $this->userid !== $this->aUsers->getUserid()) {
            $this->aUsers = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(OrdersTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrdersQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvents = null;
            $this->aEventsTables = null;
            $this->aUsers = null;
            $this->collOrdersDetailss = null;

            $this->collOrdersInProgresses = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Orders::setDeleted()
     * @see Orders::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOrdersQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersTableMap::DATABASE_NAME);
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
                OrdersTableMap::addInstanceToPool($this);
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

            if ($this->aEventsTables !== null) {
                if ($this->aEventsTables->isModified() || $this->aEventsTables->isNew()) {
                    $affectedRows += $this->aEventsTables->save($con);
                }
                $this->setEventsTables($this->aEventsTables);
            }

            if ($this->aUsers !== null) {
                if ($this->aUsers->isModified() || $this->aUsers->isNew()) {
                    $affectedRows += $this->aUsers->save($con);
                }
                $this->setUsers($this->aUsers);
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

            if ($this->ordersDetailssScheduledForDeletion !== null) {
                if (!$this->ordersDetailssScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrdersDetailsQuery::create()
                        ->filterByPrimaryKeys($this->ordersDetailssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
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

            if ($this->ordersInProgressesScheduledForDeletion !== null) {
                if (!$this->ordersInProgressesScheduledForDeletion->isEmpty()) {
                    \API\Models\OIP\OrdersInProgressQuery::create()
                        ->filterByPrimaryKeys($this->ordersInProgressesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersInProgressesScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersInProgresses !== null) {
                foreach ($this->collOrdersInProgresses as $referrerFK) {
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

        $this->modifiedColumns[OrdersTableMap::COL_ORDERID] = true;
        if (null !== $this->orderid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrdersTableMap::COL_ORDERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrdersTableMap::COL_ORDERID)) {
            $modifiedColumns[':p' . $index++]  = 'orderid';
        }
        if ($this->isColumnModified(OrdersTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(OrdersTableMap::COL_TABLEID)) {
            $modifiedColumns[':p' . $index++]  = 'tableid';
        }
        if ($this->isColumnModified(OrdersTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userid';
        }
        if ($this->isColumnModified(OrdersTableMap::COL_ORDERTIME)) {
            $modifiedColumns[':p' . $index++]  = 'ordertime';
        }
        if ($this->isColumnModified(OrdersTableMap::COL_PRIORITY)) {
            $modifiedColumns[':p' . $index++]  = 'priority';
        }
        if ($this->isColumnModified(OrdersTableMap::COL_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = 'finished';
        }

        $sql = sprintf(
            'INSERT INTO orders (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'orderid':
                        $stmt->bindValue($identifier, $this->orderid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'tableid':
                        $stmt->bindValue($identifier, $this->tableid, PDO::PARAM_INT);
                        break;
                    case 'userid':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case 'ordertime':
                        $stmt->bindValue($identifier, $this->ordertime ? $this->ordertime->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'priority':
                        $stmt->bindValue($identifier, $this->priority, PDO::PARAM_INT);
                        break;
                    case 'finished':
                        $stmt->bindValue($identifier, $this->finished ? $this->finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $this->setOrderid($pk);

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
        $pos = OrdersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getOrderid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getTableid();
                break;
            case 3:
                return $this->getUserid();
                break;
            case 4:
                return $this->getOrdertime();
                break;
            case 5:
                return $this->getPriority();
                break;
            case 6:
                return $this->getFinished();
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

        if (isset($alreadyDumpedObjects['Orders'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Orders'][$this->hashCode()] = true;
        $keys = OrdersTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrderid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getTableid(),
            $keys[3] => $this->getUserid(),
            $keys[4] => $this->getOrdertime(),
            $keys[5] => $this->getPriority(),
            $keys[6] => $this->getFinished(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

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
            if (null !== $this->aEventsTables) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventsTables';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events_tables';
                        break;
                    default:
                        $key = 'EventsTables';
                }

                $result[$key] = $this->aEventsTables->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->aUsers->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collOrdersInProgresses) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersInProgresses';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_in_progresses';
                        break;
                    default:
                        $key = 'OrdersInProgresses';
                }

                $result[$key] = $this->collOrdersInProgresses->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Ordering\Orders
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrdersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Ordering\Orders
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrderid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setTableid($value);
                break;
            case 3:
                $this->setUserid($value);
                break;
            case 4:
                $this->setOrdertime($value);
                break;
            case 5:
                $this->setPriority($value);
                break;
            case 6:
                $this->setFinished($value);
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
        $keys = OrdersTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrderid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTableid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUserid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setOrdertime($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPriority($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setFinished($arr[$keys[6]]);
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
     * @return $this|\API\Models\Ordering\Orders The current object, for fluid interface
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
        $criteria = new Criteria(OrdersTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrdersTableMap::COL_ORDERID)) {
            $criteria->add(OrdersTableMap::COL_ORDERID, $this->orderid);
        }
        if ($this->isColumnModified(OrdersTableMap::COL_EVENTID)) {
            $criteria->add(OrdersTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(OrdersTableMap::COL_TABLEID)) {
            $criteria->add(OrdersTableMap::COL_TABLEID, $this->tableid);
        }
        if ($this->isColumnModified(OrdersTableMap::COL_USERID)) {
            $criteria->add(OrdersTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(OrdersTableMap::COL_ORDERTIME)) {
            $criteria->add(OrdersTableMap::COL_ORDERTIME, $this->ordertime);
        }
        if ($this->isColumnModified(OrdersTableMap::COL_PRIORITY)) {
            $criteria->add(OrdersTableMap::COL_PRIORITY, $this->priority);
        }
        if ($this->isColumnModified(OrdersTableMap::COL_FINISHED)) {
            $criteria->add(OrdersTableMap::COL_FINISHED, $this->finished);
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
        $criteria = ChildOrdersQuery::create();
        $criteria->add(OrdersTableMap::COL_ORDERID, $this->orderid);
        $criteria->add(OrdersTableMap::COL_EVENTID, $this->eventid);
        $criteria->add(OrdersTableMap::COL_TABLEID, $this->tableid);
        $criteria->add(OrdersTableMap::COL_USERID, $this->userid);

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
        $validPk = null !== $this->getOrderid() &&
            null !== $this->getEventid() &&
            null !== $this->getTableid() &&
            null !== $this->getUserid();

        $validPrimaryKeyFKs = 3;
        $primaryKeyFKs = [];

        //relation fk_orders_events1 to table events
        if ($this->aEvents && $hash = spl_object_hash($this->aEvents)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation fk_orders_tables to table events_tables
        if ($this->aEventsTables && $hash = spl_object_hash($this->aEventsTables)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation fk_orders_users1 to table users
        if ($this->aUsers && $hash = spl_object_hash($this->aUsers)) {
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
        $pks[0] = $this->getOrderid();
        $pks[1] = $this->getEventid();
        $pks[2] = $this->getTableid();
        $pks[3] = $this->getUserid();

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
        $this->setOrderid($keys[0]);
        $this->setEventid($keys[1]);
        $this->setTableid($keys[2]);
        $this->setUserid($keys[3]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getOrderid()) && (null === $this->getEventid()) && (null === $this->getTableid()) && (null === $this->getUserid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Ordering\Orders (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setTableid($this->getTableid());
        $copyObj->setUserid($this->getUserid());
        $copyObj->setOrdertime($this->getOrdertime());
        $copyObj->setPriority($this->getPriority());
        $copyObj->setFinished($this->getFinished());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getOrdersDetailss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetails($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersInProgresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersInProgress($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setOrderid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Ordering\Orders Clone of current object.
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
     * Declares an association between this object and a Events object.
     *
     * @param  Events $v
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEvents(Events $v = null)
    {
        if ($v === null) {
            $this->setEventid(NULL);
        } else {
            $this->setEventid($v->getEventid());
        }

        $this->aEvents = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Events object, it will not be re-added.
        if ($v !== null) {
            $v->addOrders($this);
        }


        return $this;
    }


    /**
     * Get the associated Events object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Events The associated Events object.
     * @throws PropelException
     */
    public function getEvents(ConnectionInterface $con = null)
    {
        if ($this->aEvents === null && ($this->eventid !== null)) {
            $this->aEvents = EventsQuery::create()->findPk($this->eventid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvents->addOrderss($this);
             */
        }

        return $this->aEvents;
    }

    /**
     * Declares an association between this object and a EventsTables object.
     *
     * @param  EventsTables $v
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventsTables(EventsTables $v = null)
    {
        if ($v === null) {
            $this->setTableid(NULL);
        } else {
            $this->setTableid($v->getEventsTableid());
        }

        $this->aEventsTables = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EventsTables object, it will not be re-added.
        if ($v !== null) {
            $v->addOrders($this);
        }


        return $this;
    }


    /**
     * Get the associated EventsTables object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return EventsTables The associated EventsTables object.
     * @throws PropelException
     */
    public function getEventsTables(ConnectionInterface $con = null)
    {
        if ($this->aEventsTables === null && ($this->tableid !== null)) {
            $this->aEventsTables = EventsTablesQuery::create()
                ->filterByOrders($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventsTables->addOrderss($this);
             */
        }

        return $this->aEventsTables;
    }

    /**
     * Declares an association between this object and a Users object.
     *
     * @param  Users $v
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUsers(Users $v = null)
    {
        if ($v === null) {
            $this->setUserid(NULL);
        } else {
            $this->setUserid($v->getUserid());
        }

        $this->aUsers = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Users object, it will not be re-added.
        if ($v !== null) {
            $v->addOrders($this);
        }


        return $this;
    }


    /**
     * Get the associated Users object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Users The associated Users object.
     * @throws PropelException
     */
    public function getUsers(ConnectionInterface $con = null)
    {
        if ($this->aUsers === null && ($this->userid !== null)) {
            $this->aUsers = UsersQuery::create()->findPk($this->userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUsers->addOrderss($this);
             */
        }

        return $this->aUsers;
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
        if ('OrdersDetails' == $relationName) {
            return $this->initOrdersDetailss();
        }
        if ('OrdersInProgress' == $relationName) {
            return $this->initOrdersInProgresses();
        }
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
        $this->collOrdersDetailss->setModel('\API\Models\Ordering\OrdersDetails');
    }

    /**
     * Gets an array of ChildOrdersDetails objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrders is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrdersDetails[] List of ChildOrdersDetails objects
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
                $collOrdersDetailss = ChildOrdersDetailsQuery::create(null, $criteria)
                    ->filterByOrders($this)
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
     * Sets a collection of ChildOrdersDetails objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersDetailss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrders The current object (for fluent API support)
     */
    public function setOrdersDetailss(Collection $ordersDetailss, ConnectionInterface $con = null)
    {
        /** @var ChildOrdersDetails[] $ordersDetailssToDelete */
        $ordersDetailssToDelete = $this->getOrdersDetailss(new Criteria(), $con)->diff($ordersDetailss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersDetailssScheduledForDeletion = clone $ordersDetailssToDelete;

        foreach ($ordersDetailssToDelete as $ordersDetailsRemoved) {
            $ordersDetailsRemoved->setOrders(null);
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
     * Returns the number of related OrdersDetails objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related OrdersDetails objects.
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

            $query = ChildOrdersDetailsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrders($this)
                ->count($con);
        }

        return count($this->collOrdersDetailss);
    }

    /**
     * Method called to associate a ChildOrdersDetails object to this object
     * through the ChildOrdersDetails foreign key attribute.
     *
     * @param  ChildOrdersDetails $l ChildOrdersDetails
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function addOrdersDetails(ChildOrdersDetails $l)
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
     * @param ChildOrdersDetails $ordersDetails The ChildOrdersDetails object to add.
     */
    protected function doAddOrdersDetails(ChildOrdersDetails $ordersDetails)
    {
        $this->collOrdersDetailss[]= $ordersDetails;
        $ordersDetails->setOrders($this);
    }

    /**
     * @param  ChildOrdersDetails $ordersDetails The ChildOrdersDetails object to remove.
     * @return $this|ChildOrders The current object (for fluent API support)
     */
    public function removeOrdersDetails(ChildOrdersDetails $ordersDetails)
    {
        if ($this->getOrdersDetailss()->contains($ordersDetails)) {
            $pos = $this->collOrdersDetailss->search($ordersDetails);
            $this->collOrdersDetailss->remove($pos);
            if (null === $this->ordersDetailssScheduledForDeletion) {
                $this->ordersDetailssScheduledForDeletion = clone $this->collOrdersDetailss;
                $this->ordersDetailssScheduledForDeletion->clear();
            }
            $this->ordersDetailssScheduledForDeletion[]= clone $ordersDetails;
            $ordersDetails->setOrders(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetails[] List of ChildOrdersDetails objects
     */
    public function getOrdersDetailssJoinAvailabilitys(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Availabilitys', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetails[] List of ChildOrdersDetails objects
     */
    public function getOrdersDetailssJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetails[] List of ChildOrdersDetails objects
     */
    public function getOrdersDetailssJoinMenuSizes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('MenuSizes', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetails[] List of ChildOrdersDetails objects
     */
    public function getOrdersDetailssJoinMenues(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Menues', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetails[] List of ChildOrdersDetails objects
     */
    public function getOrdersDetailssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }

    /**
     * Clears out the collOrdersInProgresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersInProgresses()
     */
    public function clearOrdersInProgresses()
    {
        $this->collOrdersInProgresses = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersInProgresses collection loaded partially.
     */
    public function resetPartialOrdersInProgresses($v = true)
    {
        $this->collOrdersInProgressesPartial = $v;
    }

    /**
     * Initializes the collOrdersInProgresses collection.
     *
     * By default this just sets the collOrdersInProgresses collection to an empty array (like clearcollOrdersInProgresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersInProgresses($overrideExisting = true)
    {
        if (null !== $this->collOrdersInProgresses && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersInProgressTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersInProgresses = new $collectionClassName;
        $this->collOrdersInProgresses->setModel('\API\Models\OIP\OrdersInProgress');
    }

    /**
     * Gets an array of OrdersInProgress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrders is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     * @throws PropelException
     */
    public function getOrdersInProgresses(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersInProgressesPartial && !$this->isNew();
        if (null === $this->collOrdersInProgresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersInProgresses) {
                // return empty collection
                $this->initOrdersInProgresses();
            } else {
                $collOrdersInProgresses = OrdersInProgressQuery::create(null, $criteria)
                    ->filterByOrders($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersInProgressesPartial && count($collOrdersInProgresses)) {
                        $this->initOrdersInProgresses(false);

                        foreach ($collOrdersInProgresses as $obj) {
                            if (false == $this->collOrdersInProgresses->contains($obj)) {
                                $this->collOrdersInProgresses->append($obj);
                            }
                        }

                        $this->collOrdersInProgressesPartial = true;
                    }

                    return $collOrdersInProgresses;
                }

                if ($partial && $this->collOrdersInProgresses) {
                    foreach ($this->collOrdersInProgresses as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersInProgresses[] = $obj;
                        }
                    }
                }

                $this->collOrdersInProgresses = $collOrdersInProgresses;
                $this->collOrdersInProgressesPartial = false;
            }
        }

        return $this->collOrdersInProgresses;
    }

    /**
     * Sets a collection of OrdersInProgress objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersInProgresses A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrders The current object (for fluent API support)
     */
    public function setOrdersInProgresses(Collection $ordersInProgresses, ConnectionInterface $con = null)
    {
        /** @var OrdersInProgress[] $ordersInProgressesToDelete */
        $ordersInProgressesToDelete = $this->getOrdersInProgresses(new Criteria(), $con)->diff($ordersInProgresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersInProgressesScheduledForDeletion = clone $ordersInProgressesToDelete;

        foreach ($ordersInProgressesToDelete as $ordersInProgressRemoved) {
            $ordersInProgressRemoved->setOrders(null);
        }

        $this->collOrdersInProgresses = null;
        foreach ($ordersInProgresses as $ordersInProgress) {
            $this->addOrdersInProgress($ordersInProgress);
        }

        $this->collOrdersInProgresses = $ordersInProgresses;
        $this->collOrdersInProgressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrdersInProgress objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrdersInProgress objects.
     * @throws PropelException
     */
    public function countOrdersInProgresses(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersInProgressesPartial && !$this->isNew();
        if (null === $this->collOrdersInProgresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersInProgresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersInProgresses());
            }

            $query = OrdersInProgressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrders($this)
                ->count($con);
        }

        return count($this->collOrdersInProgresses);
    }

    /**
     * Method called to associate a OrdersInProgress object to this object
     * through the OrdersInProgress foreign key attribute.
     *
     * @param  OrdersInProgress $l OrdersInProgress
     * @return $this|\API\Models\Ordering\Orders The current object (for fluent API support)
     */
    public function addOrdersInProgress(OrdersInProgress $l)
    {
        if ($this->collOrdersInProgresses === null) {
            $this->initOrdersInProgresses();
            $this->collOrdersInProgressesPartial = true;
        }

        if (!$this->collOrdersInProgresses->contains($l)) {
            $this->doAddOrdersInProgress($l);

            if ($this->ordersInProgressesScheduledForDeletion and $this->ordersInProgressesScheduledForDeletion->contains($l)) {
                $this->ordersInProgressesScheduledForDeletion->remove($this->ordersInProgressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param OrdersInProgress $ordersInProgress The OrdersInProgress object to add.
     */
    protected function doAddOrdersInProgress(OrdersInProgress $ordersInProgress)
    {
        $this->collOrdersInProgresses[]= $ordersInProgress;
        $ordersInProgress->setOrders($this);
    }

    /**
     * @param  OrdersInProgress $ordersInProgress The OrdersInProgress object to remove.
     * @return $this|ChildOrders The current object (for fluent API support)
     */
    public function removeOrdersInProgress(OrdersInProgress $ordersInProgress)
    {
        if ($this->getOrdersInProgresses()->contains($ordersInProgress)) {
            $pos = $this->collOrdersInProgresses->search($ordersInProgress);
            $this->collOrdersInProgresses->remove($pos);
            if (null === $this->ordersInProgressesScheduledForDeletion) {
                $this->ordersInProgressesScheduledForDeletion = clone $this->collOrdersInProgresses;
                $this->ordersInProgressesScheduledForDeletion->clear();
            }
            $this->ordersInProgressesScheduledForDeletion[]= clone $ordersInProgress;
            $ordersInProgress->setOrders(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     */
    public function getOrdersInProgressesJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getOrdersInProgresses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Orders is new, it will return
     * an empty collection; or if this Orders has previously
     * been saved, it will retrieve related OrdersInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Orders.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     */
    public function getOrdersInProgressesJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getOrdersInProgresses($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvents) {
            $this->aEvents->removeOrders($this);
        }
        if (null !== $this->aEventsTables) {
            $this->aEventsTables->removeOrders($this);
        }
        if (null !== $this->aUsers) {
            $this->aUsers->removeOrders($this);
        }
        $this->orderid = null;
        $this->eventid = null;
        $this->tableid = null;
        $this->userid = null;
        $this->ordertime = null;
        $this->priority = null;
        $this->finished = null;
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
            if ($this->collOrdersDetailss) {
                foreach ($this->collOrdersDetailss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersInProgresses) {
                foreach ($this->collOrdersInProgresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOrdersDetailss = null;
        $this->collOrdersInProgresses = null;
        $this->aEvents = null;
        $this->aEventsTables = null;
        $this->aUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrdersTableMap::DEFAULT_STRING_FORMAT);
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
