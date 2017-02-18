<?php

namespace API\Models\Ordering\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\Event\EventTable;
use API\Models\Event\EventTableQuery;
use API\Models\OIP\OrderInProgress;
use API\Models\OIP\OrderInProgressQuery;
use API\Models\OIP\Base\OrderInProgress as BaseOrderInProgress;
use API\Models\OIP\Map\OrderInProgressTableMap;
use API\Models\Ordering\Order as ChildOrder;
use API\Models\Ordering\OrderDetail as ChildOrderDetail;
use API\Models\Ordering\OrderDetailQuery as ChildOrderDetailQuery;
use API\Models\Ordering\OrderQuery as ChildOrderQuery;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\Map\OrderTableMap;
use API\Models\User\User;
use API\Models\User\UserQuery;
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
 * Base class that represents a row from the 'order' table.
 *
 * @package propel.generator.API.Models.Ordering.Base
 */
abstract class Order implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Ordering\\Map\\OrderTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     *
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     *
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     *
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     *
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the orderid field.
     *
     * @var int
     */
    protected $orderid;

    /**
     * The value for the event_tableid field.
     *
     * @var int
     */
    protected $event_tableid;

    /**
     * The value for the userid field.
     *
     * @var int
     */
    protected $userid;

    /**
     * The value for the ordertime field.
     *
     * @var DateTime
     */
    protected $ordertime;

    /**
     * The value for the priority field.
     *
     * @var int
     */
    protected $priority;

    /**
     * The value for the distribution_finished field.
     *
     * @var DateTime
     */
    protected $distribution_finished;

    /**
     * The value for the invoice_finished field.
     *
     * @var DateTime
     */
    protected $invoice_finished;

    /**
     * The value for the cancellation field.
     *
     * @var DateTime
     */
    protected $cancellation;

    /**
     * The value for the cancellation_created_by_userid field.
     *
     * @var int
     */
    protected $cancellation_created_by_userid;

    /**
     * @var        User
     */
    protected $aUserRelatedByCancellationCreatedByUserid;

    /**
     * @var        EventTable
     */
    protected $aEventTable;

    /**
     * @var        User
     */
    protected $aUserRelatedByUserid;

    /**
     * @var        ObjectCollection|ChildOrderDetail[] Collection to store aggregation of ChildOrderDetail objects.
     */
    protected $collOrderDetails;
    protected $collOrderDetailsPartial;

    /**
     * @var        ObjectCollection|OrderInProgress[] Collection to store aggregation of OrderInProgress objects.
     */
    protected $collOrderInProgresses;
    protected $collOrderInProgressesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     *
     * @var ObjectCollection|ChildOrderDetail[]
     */
    protected $orderDetailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     *
     * @var ObjectCollection|OrderInProgress[]
     */
    protected $orderInProgressesScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Ordering\Base\Order object.
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
     * @param  string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     *
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
     *
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     *
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     *
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
     * Compares this with another <code>Order</code> instance.  If
     * <code>obj</code> is an instance of <code>Order</code>, delegates to
     * <code>equals(Order)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed $obj The object to compare to.
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
     * @param  string $name The virtual column name
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
     * @return $this|Order The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string $msg
     * @param  int    $priority One of the Propel::LOG_* logging levels
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

        foreach ($serializableProperties as $property) {
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
     * Get the [event_tableid] column value.
     *
     * @return int
     */
    public function getEventTableid()
    {
        return $this->event_tableid;
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
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getOrdertime($format = null)
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
     * Get the [optionally formatted] temporal [distribution_finished] column value.
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDistributionFinished($format = null)
    {
        if ($format === null) {
            return $this->distribution_finished;
        } else {
            return $this->distribution_finished instanceof \DateTimeInterface ? $this->distribution_finished->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [invoice_finished] column value.
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getInvoiceFinished($format = null)
    {
        if ($format === null) {
            return $this->invoice_finished;
        } else {
            return $this->invoice_finished instanceof \DateTimeInterface ? $this->invoice_finished->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [cancellation] column value.
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCancellation($format = null)
    {
        if ($format === null) {
            return $this->cancellation;
        } else {
            return $this->cancellation instanceof \DateTimeInterface ? $this->cancellation->format($format) : null;
        }
    }

    /**
     * Get the [cancellation_created_by_userid] column value.
     *
     * @return int
     */
    public function getCancellationCreatedByUserid()
    {
        return $this->cancellation_created_by_userid;
    }

    /**
     * Set the value of [orderid] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setOrderid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orderid !== $v) {
            $this->orderid = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDERID] = true;
        }

        return $this;
    } // setOrderid()

    /**
     * Set the value of [event_tableid] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setEventTableid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_tableid !== $v) {
            $this->event_tableid = $v;
            $this->modifiedColumns[OrderTableMap::COL_EVENT_TABLEID] = true;
        }

        if ($this->aEventTable !== null && $this->aEventTable->getEventTableid() !== $v) {
            $this->aEventTable = null;
        }

        return $this;
    } // setEventTableid()

    /**
     * Set the value of [userid] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[OrderTableMap::COL_USERID] = true;
        }

        if ($this->aUserRelatedByUserid !== null && $this->aUserRelatedByUserid->getUserid() !== $v) {
            $this->aUserRelatedByUserid = null;
        }

        return $this;
    } // setUserid()

    /**
     * Sets the value of [ordertime] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setOrdertime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->ordertime !== null || $dt !== null) {
            if ($this->ordertime === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->ordertime->format("Y-m-d H:i:s.u")) {
                $this->ordertime = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDERTIME] = true;
            }
        } // if either are not null

        return $this;
    } // setOrdertime()

    /**
     * Set the value of [priority] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setPriority($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->priority !== $v) {
            $this->priority = $v;
            $this->modifiedColumns[OrderTableMap::COL_PRIORITY] = true;
        }

        return $this;
    } // setPriority()

    /**
     * Sets the value of [distribution_finished] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setDistributionFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->distribution_finished !== null || $dt !== null) {
            if ($this->distribution_finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->distribution_finished->format("Y-m-d H:i:s.u")) {
                $this->distribution_finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_DISTRIBUTION_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setDistributionFinished()

    /**
     * Sets the value of [invoice_finished] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setInvoiceFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->invoice_finished !== null || $dt !== null) {
            if ($this->invoice_finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->invoice_finished->format("Y-m-d H:i:s.u")) {
                $this->invoice_finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_INVOICE_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setInvoiceFinished()

    /**
     * Sets the value of [cancellation] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setCancellation($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cancellation !== null || $dt !== null) {
            if ($this->cancellation === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cancellation->format("Y-m-d H:i:s.u")) {
                $this->cancellation = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_CANCELLATION] = true;
            }
        } // if either are not null

        return $this;
    } // setCancellation()

    /**
     * Set the value of [cancellation_created_by_userid] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function setCancellationCreatedByUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cancellation_created_by_userid !== $v) {
            $this->cancellation_created_by_userid = $v;
            $this->modifiedColumns[OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID] = true;
        }

        if ($this->aUserRelatedByCancellationCreatedByUserid !== null && $this->aUserRelatedByCancellationCreatedByUserid->getUserid() !== $v) {
            $this->aUserRelatedByCancellationCreatedByUserid = null;
        }

        return $this;
    } // setCancellationCreatedByUserid()

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
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrderTableMap::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orderid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrderTableMap::translateFieldName('EventTableid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_tableid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrderTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrderTableMap::translateFieldName('Ordertime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->ordertime = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrderTableMap::translateFieldName('Priority', TableMap::TYPE_PHPNAME, $indexType)];
            $this->priority = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrderTableMap::translateFieldName('DistributionFinished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->distribution_finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OrderTableMap::translateFieldName('InvoiceFinished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->invoice_finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : OrderTableMap::translateFieldName('Cancellation', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cancellation = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : OrderTableMap::translateFieldName('CancellationCreatedByUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cancellation_created_by_userid = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = OrderTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Ordering\\Order'), 0, $e);
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
        if ($this->aEventTable !== null && $this->event_tableid !== $this->aEventTable->getEventTableid()) {
            $this->aEventTable = null;
        }
        if ($this->aUserRelatedByUserid !== null && $this->userid !== $this->aUserRelatedByUserid->getUserid()) {
            $this->aUserRelatedByUserid = null;
        }
        if ($this->aUserRelatedByCancellationCreatedByUserid !== null && $this->cancellation_created_by_userid !== $this->aUserRelatedByCancellationCreatedByUserid->getUserid()) {
            $this->aUserRelatedByCancellationCreatedByUserid = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param  boolean             $deep (optional) Whether to also de-associated any related objects.
     * @param  ConnectionInterface $con  (optional) The ConnectionInterface connection to use.
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
            $con = Propel::getServiceContainer()->getReadConnection(OrderTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrderQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUserRelatedByCancellationCreatedByUserid = null;
            $this->aEventTable = null;
            $this->aUserRelatedByUserid = null;
            $this->collOrderDetails = null;

            $this->collOrderInProgresses = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param  ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see    Order::setDeleted()
     * @see    Order::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $con->transaction(
            function () use ($con) {
                $deleteQuery = ChildOrderQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
                $ret = $this->preDelete($con);
                if ($ret) {
                    $deleteQuery->delete($con);
                    $this->postDelete($con);
                    $this->setDeleted(true);
                }
            }
        );
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param  ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see    doSave()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        return $con->transaction(
            function () use ($con) {
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
                    OrderTableMap::addInstanceToPool($this);
                } else {
                    $affectedRows = 0;
                }

                return $affectedRows;
            }
        );
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param  ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see    save()
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

            if ($this->aUserRelatedByCancellationCreatedByUserid !== null) {
                if ($this->aUserRelatedByCancellationCreatedByUserid->isModified() || $this->aUserRelatedByCancellationCreatedByUserid->isNew()) {
                    $affectedRows += $this->aUserRelatedByCancellationCreatedByUserid->save($con);
                }
                $this->setUserRelatedByCancellationCreatedByUserid($this->aUserRelatedByCancellationCreatedByUserid);
            }

            if ($this->aEventTable !== null) {
                if ($this->aEventTable->isModified() || $this->aEventTable->isNew()) {
                    $affectedRows += $this->aEventTable->save($con);
                }
                $this->setEventTable($this->aEventTable);
            }

            if ($this->aUserRelatedByUserid !== null) {
                if ($this->aUserRelatedByUserid->isModified() || $this->aUserRelatedByUserid->isNew()) {
                    $affectedRows += $this->aUserRelatedByUserid->save($con);
                }
                $this->setUserRelatedByUserid($this->aUserRelatedByUserid);
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

            if ($this->orderDetailsScheduledForDeletion !== null) {
                if (!$this->orderDetailsScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrderDetailQuery::create()
                        ->filterByPrimaryKeys($this->orderDetailsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->orderDetailsScheduledForDeletion = null;
                }
            }

            if ($this->collOrderDetails !== null) {
                foreach ($this->collOrderDetails as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->orderInProgressesScheduledForDeletion !== null) {
                if (!$this->orderInProgressesScheduledForDeletion->isEmpty()) {
                    \API\Models\OIP\OrderInProgressQuery::create()
                        ->filterByPrimaryKeys($this->orderInProgressesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->orderInProgressesScheduledForDeletion = null;
                }
            }

            if ($this->collOrderInProgresses !== null) {
                foreach ($this->collOrderInProgresses as $referrerFK) {
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
     * @param ConnectionInterface $con
     *
     * @throws PropelException
     * @see    doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[OrderTableMap::COL_ORDERID] = true;
        if (null !== $this->orderid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrderTableMap::COL_ORDERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrderTableMap::COL_ORDERID)) {
            $modifiedColumns[':p' . $index++]  = '`orderid`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_EVENT_TABLEID)) {
            $modifiedColumns[':p' . $index++]  = '`event_tableid`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = '`userid`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDERTIME)) {
            $modifiedColumns[':p' . $index++]  = '`ordertime`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_PRIORITY)) {
            $modifiedColumns[':p' . $index++]  = '`priority`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_DISTRIBUTION_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = '`distribution_finished`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_INVOICE_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = '`invoice_finished`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_CANCELLATION)) {
            $modifiedColumns[':p' . $index++]  = '`cancellation`';
        }
        if ($this->isColumnModified(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID)) {
            $modifiedColumns[':p' . $index++]  = '`cancellation_created_by_userid`';
        }

        $sql = sprintf(
            'INSERT INTO `order` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`orderid`':
                        $stmt->bindValue($identifier, $this->orderid, PDO::PARAM_INT);
                        break;
                    case '`event_tableid`':
                        $stmt->bindValue($identifier, $this->event_tableid, PDO::PARAM_INT);
                        break;
                    case '`userid`':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case '`ordertime`':
                        $stmt->bindValue($identifier, $this->ordertime ? $this->ordertime->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`priority`':
                        $stmt->bindValue($identifier, $this->priority, PDO::PARAM_INT);
                        break;
                    case '`distribution_finished`':
                        $stmt->bindValue($identifier, $this->distribution_finished ? $this->distribution_finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`invoice_finished`':
                        $stmt->bindValue($identifier, $this->invoice_finished ? $this->invoice_finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`cancellation`':
                        $stmt->bindValue($identifier, $this->cancellation ? $this->cancellation->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`cancellation_created_by_userid`':
                        $stmt->bindValue($identifier, $this->cancellation_created_by_userid, PDO::PARAM_INT);
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
     * @param ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see    doSave()
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
     * @param  string $name name
     * @param  string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getOrderid();
                break;
            case 1:
                return $this->getEventTableid();
                break;
            case 2:
                return $this->getUserid();
                break;
            case 3:
                return $this->getOrdertime();
                break;
            case 4:
                return $this->getPriority();
                break;
            case 5:
                return $this->getDistributionFinished();
                break;
            case 6:
                return $this->getInvoiceFinished();
                break;
            case 7:
                return $this->getCancellation();
                break;
            case 8:
                return $this->getCancellationCreatedByUserid();
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
     * @param string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to
     *                                            TableMap::TYPE_PHPNAME.
     * @param boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array   $alreadyDumpedObjects   List of objects to skip to avoid recursion
     * @param boolean $includeForeignObjects  (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Order'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Order'][$this->hashCode()] = true;
        $keys = OrderTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrderid(),
            $keys[1] => $this->getEventTableid(),
            $keys[2] => $this->getUserid(),
            $keys[3] => $this->getOrdertime(),
            $keys[4] => $this->getPriority(),
            $keys[5] => $this->getDistributionFinished(),
            $keys[6] => $this->getInvoiceFinished(),
            $keys[7] => $this->getCancellation(),
            $keys[8] => $this->getCancellationCreatedByUserid(),
        );
        if ($result[$keys[3]] instanceof \DateTime) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUserRelatedByCancellationCreatedByUserid) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUserRelatedByCancellationCreatedByUserid->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventTable) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventTable';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_table';
                        break;
                    default:
                        $key = 'EventTable';
                }

                $result[$key] = $this->aEventTable->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserRelatedByUserid) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUserRelatedByUserid->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collOrderDetails) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderDetails';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order_details';
                        break;
                    default:
                        $key = 'OrderDetails';
                }

                $result[$key] = $this->collOrderDetails->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrderInProgresses) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderInProgresses';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order_in_progresses';
                        break;
                    default:
                        $key = 'OrderInProgresses';
                }

                $result[$key] = $this->collOrderInProgresses->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type  The type of fieldname the $name is of:
     *                       one of the class type constants
     *                       TableMap::TYPE_PHPNAME,
     *                       TableMap::TYPE_CAMELNAME
     *                       TableMap::TYPE_COLNAME,
     *                       TableMap::TYPE_FIELDNAME,
     *                       TableMap::TYPE_NUM. Defaults to
     *                       TableMap::TYPE_PHPNAME.
     * @return $this|\API\Models\Ordering\Order
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int   $pos   position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Ordering\Order
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrderid($value);
                break;
            case 1:
                $this->setEventTableid($value);
                break;
            case 2:
                $this->setUserid($value);
                break;
            case 3:
                $this->setOrdertime($value);
                break;
            case 4:
                $this->setPriority($value);
                break;
            case 5:
                $this->setDistributionFinished($value);
                break;
            case 6:
                $this->setInvoiceFinished($value);
                break;
            case 7:
                $this->setCancellation($value);
                break;
            case 8:
                $this->setCancellationCreatedByUserid($value);
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
     * @param  array  $arr     An array to populate the object from.
     * @param  string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = OrderTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrderid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventTableid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setOrdertime($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPriority($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDistributionFinished($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setInvoiceFinished($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCancellation($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCancellationCreatedByUserid($arr[$keys[8]]);
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
     * @param mixed  $parser  A AbstractParser instance,
     *                        or a format name ('XML',
     *                        'YAML', 'JSON', 'CSV')
     * @param string $data    The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\API\Models\Ordering\Order The current object, for fluid interface
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
        $criteria = new Criteria(OrderTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrderTableMap::COL_ORDERID)) {
            $criteria->add(OrderTableMap::COL_ORDERID, $this->orderid);
        }
        if ($this->isColumnModified(OrderTableMap::COL_EVENT_TABLEID)) {
            $criteria->add(OrderTableMap::COL_EVENT_TABLEID, $this->event_tableid);
        }
        if ($this->isColumnModified(OrderTableMap::COL_USERID)) {
            $criteria->add(OrderTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDERTIME)) {
            $criteria->add(OrderTableMap::COL_ORDERTIME, $this->ordertime);
        }
        if ($this->isColumnModified(OrderTableMap::COL_PRIORITY)) {
            $criteria->add(OrderTableMap::COL_PRIORITY, $this->priority);
        }
        if ($this->isColumnModified(OrderTableMap::COL_DISTRIBUTION_FINISHED)) {
            $criteria->add(OrderTableMap::COL_DISTRIBUTION_FINISHED, $this->distribution_finished);
        }
        if ($this->isColumnModified(OrderTableMap::COL_INVOICE_FINISHED)) {
            $criteria->add(OrderTableMap::COL_INVOICE_FINISHED, $this->invoice_finished);
        }
        if ($this->isColumnModified(OrderTableMap::COL_CANCELLATION)) {
            $criteria->add(OrderTableMap::COL_CANCELLATION, $this->cancellation);
        }
        if ($this->isColumnModified(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID)) {
            $criteria->add(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID, $this->cancellation_created_by_userid);
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
        $criteria = ChildOrderQuery::create();
        $criteria->add(OrderTableMap::COL_ORDERID, $this->orderid);

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
        $validPk = null !== $this->getOrderid();

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
     *
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getOrderid();
    }

    /**
     * Generic method to set the primary key (orderid column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setOrderid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getOrderid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  object  $copyObj  An object of \API\Models\Ordering\Order (or compatible) type.
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param  boolean $makeNew  Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventTableid($this->getEventTableid());
        $copyObj->setUserid($this->getUserid());
        $copyObj->setOrdertime($this->getOrdertime());
        $copyObj->setPriority($this->getPriority());
        $copyObj->setDistributionFinished($this->getDistributionFinished());
        $copyObj->setInvoiceFinished($this->getInvoiceFinished());
        $copyObj->setCancellation($this->getCancellation());
        $copyObj->setCancellationCreatedByUserid($this->getCancellationCreatedByUserid());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getOrderDetails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderDetail($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderInProgresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderInProgress($relObj->copy($deepCopy));
                }
            }
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setOrderid(null); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Ordering\Order Clone of current object.
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
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByCancellationCreatedByUserid(User $v = null)
    {
        if ($v === null) {
            $this->setCancellationCreatedByUserid(null);
        } else {
            $this->setCancellationCreatedByUserid($v->getUserid());
        }

        $this->aUserRelatedByCancellationCreatedByUserid = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderRelatedByCancellationCreatedByUserid($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUserRelatedByCancellationCreatedByUserid(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedByCancellationCreatedByUserid === null && ($this->cancellation_created_by_userid !== null)) {
            $this->aUserRelatedByCancellationCreatedByUserid = UserQuery::create()->findPk($this->cancellation_created_by_userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByCancellationCreatedByUserid->addOrdersRelatedByCancellationCreatedByUserid($this);
             */
        }

        return $this->aUserRelatedByCancellationCreatedByUserid;
    }

    /**
     * Declares an association between this object and a EventTable object.
     *
     * @param  EventTable $v
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventTable(EventTable $v = null)
    {
        if ($v === null) {
            $this->setEventTableid(null);
        } else {
            $this->setEventTableid($v->getEventTableid());
        }

        $this->aEventTable = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EventTable object, it will not be re-added.
        if ($v !== null) {
            $v->addOrder($this);
        }


        return $this;
    }


    /**
     * Get the associated EventTable object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return EventTable The associated EventTable object.
     * @throws PropelException
     */
    public function getEventTable(ConnectionInterface $con = null)
    {
        if ($this->aEventTable === null && ($this->event_tableid !== null)) {
            $this->aEventTable = EventTableQuery::create()->findPk($this->event_tableid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventTable->addOrders($this);
             */
        }

        return $this->aEventTable;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedByUserid(User $v = null)
    {
        if ($v === null) {
            $this->setUserid(null);
        } else {
            $this->setUserid($v->getUserid());
        }

        $this->aUserRelatedByUserid = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderRelatedByUserid($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUserRelatedByUserid(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedByUserid === null && ($this->userid !== null)) {
            $this->aUserRelatedByUserid = UserQuery::create()->findPk($this->userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedByUserid->addOrdersRelatedByUserid($this);
             */
        }

        return $this->aUserRelatedByUserid;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param  string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('OrderDetail' == $relationName) {
            return $this->initOrderDetails();
        }
        if ('OrderInProgress' == $relationName) {
            return $this->initOrderInProgresses();
        }
    }

    /**
     * Clears out the collOrderDetails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see    addOrderDetails()
     */
    public function clearOrderDetails()
    {
        $this->collOrderDetails = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrderDetails collection loaded partially.
     */
    public function resetPartialOrderDetails($v = true)
    {
        $this->collOrderDetailsPartial = $v;
    }

    /**
     * Initializes the collOrderDetails collection.
     *
     * By default this just sets the collOrderDetails collection to an empty array (like clearcollOrderDetails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrderDetails($overrideExisting = true)
    {
        if (null !== $this->collOrderDetails && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderDetailTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderDetails = new $collectionClassName;
        $this->collOrderDetails->setModel('\API\Models\Ordering\OrderDetail');
    }

    /**
     * Gets an array of ChildOrderDetail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrder is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param  Criteria            $criteria optional Criteria object to narrow the query
     * @param  ConnectionInterface $con      optional connection object
     * @return ObjectCollection|ChildOrderDetail[] List of ChildOrderDetail objects
     * @throws PropelException
     */
    public function getOrderDetails(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailsPartial && !$this->isNew();
        if (null === $this->collOrderDetails || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrderDetails) {
                // return empty collection
                $this->initOrderDetails();
            } else {
                $collOrderDetails = ChildOrderDetailQuery::create(null, $criteria)
                    ->filterByOrder($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrderDetailsPartial && count($collOrderDetails)) {
                        $this->initOrderDetails(false);

                        foreach ($collOrderDetails as $obj) {
                            if (false == $this->collOrderDetails->contains($obj)) {
                                $this->collOrderDetails->append($obj);
                            }
                        }

                        $this->collOrderDetailsPartial = true;
                    }

                    return $collOrderDetails;
                }

                if ($partial && $this->collOrderDetails) {
                    foreach ($this->collOrderDetails as $obj) {
                        if ($obj->isNew()) {
                            $collOrderDetails[] = $obj;
                        }
                    }
                }

                $this->collOrderDetails = $collOrderDetails;
                $this->collOrderDetailsPartial = false;
            }
        }

        return $this->collOrderDetails;
    }

    /**
     * Sets a collection of ChildOrderDetail objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection          $orderDetails A Propel collection.
     * @param  ConnectionInterface $con          Optional connection object
     * @return $this|ChildOrder The current object (for fluent API support)
     */
    public function setOrderDetails(Collection $orderDetails, ConnectionInterface $con = null)
    {
        /**
 * @var ChildOrderDetail[] $orderDetailsToDelete
*/
        $orderDetailsToDelete = $this->getOrderDetails(new Criteria(), $con)->diff($orderDetails);


        $this->orderDetailsScheduledForDeletion = $orderDetailsToDelete;

        foreach ($orderDetailsToDelete as $orderDetailRemoved) {
            $orderDetailRemoved->setOrder(null);
        }

        $this->collOrderDetails = null;
        foreach ($orderDetails as $orderDetail) {
            $this->addOrderDetail($orderDetail);
        }

        $this->collOrderDetails = $orderDetails;
        $this->collOrderDetailsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrderDetail objects.
     *
     * @param  Criteria            $criteria
     * @param  boolean             $distinct
     * @param  ConnectionInterface $con
     * @return int             Count of related OrderDetail objects.
     * @throws PropelException
     */
    public function countOrderDetails(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailsPartial && !$this->isNew();
        if (null === $this->collOrderDetails || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderDetails) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrderDetails());
            }

            $query = ChildOrderDetailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrder($this)
                ->count($con);
        }

        return count($this->collOrderDetails);
    }

    /**
     * Method called to associate a ChildOrderDetail object to this object
     * through the ChildOrderDetail foreign key attribute.
     *
     * @param  ChildOrderDetail $l ChildOrderDetail
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function addOrderDetail(ChildOrderDetail $l)
    {
        if ($this->collOrderDetails === null) {
            $this->initOrderDetails();
            $this->collOrderDetailsPartial = true;
        }

        if (!$this->collOrderDetails->contains($l)) {
            $this->doAddOrderDetail($l);

            if ($this->orderDetailsScheduledForDeletion and $this->orderDetailsScheduledForDeletion->contains($l)) {
                $this->orderDetailsScheduledForDeletion->remove($this->orderDetailsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrderDetail $orderDetail The ChildOrderDetail object to add.
     */
    protected function doAddOrderDetail(ChildOrderDetail $orderDetail)
    {
        $this->collOrderDetails[]= $orderDetail;
        $orderDetail->setOrder($this);
    }

    /**
     * @param  ChildOrderDetail $orderDetail The ChildOrderDetail object to remove.
     * @return $this|ChildOrder The current object (for fluent API support)
     */
    public function removeOrderDetail(ChildOrderDetail $orderDetail)
    {
        if ($this->getOrderDetails()->contains($orderDetail)) {
            $pos = $this->collOrderDetails->search($orderDetail);
            $this->collOrderDetails->remove($pos);
            if (null === $this->orderDetailsScheduledForDeletion) {
                $this->orderDetailsScheduledForDeletion = clone $this->collOrderDetails;
                $this->orderDetailsScheduledForDeletion->clear();
            }
            $this->orderDetailsScheduledForDeletion[]= clone $orderDetail;
            $orderDetail->setOrder(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetail[] List of ChildOrderDetail objects
     */
    public function getOrderDetailsJoinAvailability(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailQuery::create(null, $criteria);
        $query->joinWith('Availability', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetail[] List of ChildOrderDetail objects
     */
    public function getOrderDetailsJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetail[] List of ChildOrderDetail objects
     */
    public function getOrderDetailsJoinMenuSize(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailQuery::create(null, $criteria);
        $query->joinWith('MenuSize', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetail[] List of ChildOrderDetail objects
     */
    public function getOrderDetailsJoinMenu(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailQuery::create(null, $criteria);
        $query->joinWith('Menu', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetail[] List of ChildOrderDetail objects
     */
    public function getOrderDetailsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }

    /**
     * Clears out the collOrderInProgresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see    addOrderInProgresses()
     */
    public function clearOrderInProgresses()
    {
        $this->collOrderInProgresses = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrderInProgresses collection loaded partially.
     */
    public function resetPartialOrderInProgresses($v = true)
    {
        $this->collOrderInProgressesPartial = $v;
    }

    /**
     * Initializes the collOrderInProgresses collection.
     *
     * By default this just sets the collOrderInProgresses collection to an empty array (like clearcollOrderInProgresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrderInProgresses($overrideExisting = true)
    {
        if (null !== $this->collOrderInProgresses && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderInProgressTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderInProgresses = new $collectionClassName;
        $this->collOrderInProgresses->setModel('\API\Models\OIP\OrderInProgress');
    }

    /**
     * Gets an array of OrderInProgress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrder is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param  Criteria            $criteria optional Criteria object to narrow the query
     * @param  ConnectionInterface $con      optional connection object
     * @return ObjectCollection|OrderInProgress[] List of OrderInProgress objects
     * @throws PropelException
     */
    public function getOrderInProgresses(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderInProgressesPartial && !$this->isNew();
        if (null === $this->collOrderInProgresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrderInProgresses) {
                // return empty collection
                $this->initOrderInProgresses();
            } else {
                $collOrderInProgresses = OrderInProgressQuery::create(null, $criteria)
                    ->filterByOrder($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrderInProgressesPartial && count($collOrderInProgresses)) {
                        $this->initOrderInProgresses(false);

                        foreach ($collOrderInProgresses as $obj) {
                            if (false == $this->collOrderInProgresses->contains($obj)) {
                                $this->collOrderInProgresses->append($obj);
                            }
                        }

                        $this->collOrderInProgressesPartial = true;
                    }

                    return $collOrderInProgresses;
                }

                if ($partial && $this->collOrderInProgresses) {
                    foreach ($this->collOrderInProgresses as $obj) {
                        if ($obj->isNew()) {
                            $collOrderInProgresses[] = $obj;
                        }
                    }
                }

                $this->collOrderInProgresses = $collOrderInProgresses;
                $this->collOrderInProgressesPartial = false;
            }
        }

        return $this->collOrderInProgresses;
    }

    /**
     * Sets a collection of OrderInProgress objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection          $orderInProgresses A Propel collection.
     * @param  ConnectionInterface $con               Optional connection object
     * @return $this|ChildOrder The current object (for fluent API support)
     */
    public function setOrderInProgresses(Collection $orderInProgresses, ConnectionInterface $con = null)
    {
        /**
 * @var OrderInProgress[] $orderInProgressesToDelete
*/
        $orderInProgressesToDelete = $this->getOrderInProgresses(new Criteria(), $con)->diff($orderInProgresses);


        $this->orderInProgressesScheduledForDeletion = $orderInProgressesToDelete;

        foreach ($orderInProgressesToDelete as $orderInProgressRemoved) {
            $orderInProgressRemoved->setOrder(null);
        }

        $this->collOrderInProgresses = null;
        foreach ($orderInProgresses as $orderInProgress) {
            $this->addOrderInProgress($orderInProgress);
        }

        $this->collOrderInProgresses = $orderInProgresses;
        $this->collOrderInProgressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrderInProgress objects.
     *
     * @param  Criteria            $criteria
     * @param  boolean             $distinct
     * @param  ConnectionInterface $con
     * @return int             Count of related BaseOrderInProgress objects.
     * @throws PropelException
     */
    public function countOrderInProgresses(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderInProgressesPartial && !$this->isNew();
        if (null === $this->collOrderInProgresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderInProgresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrderInProgresses());
            }

            $query = OrderInProgressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrder($this)
                ->count($con);
        }

        return count($this->collOrderInProgresses);
    }

    /**
     * Method called to associate a OrderInProgress object to this object
     * through the OrderInProgress foreign key attribute.
     *
     * @param  OrderInProgress $l OrderInProgress
     * @return $this|\API\Models\Ordering\Order The current object (for fluent API support)
     */
    public function addOrderInProgress(OrderInProgress $l)
    {
        if ($this->collOrderInProgresses === null) {
            $this->initOrderInProgresses();
            $this->collOrderInProgressesPartial = true;
        }

        if (!$this->collOrderInProgresses->contains($l)) {
            $this->doAddOrderInProgress($l);

            if ($this->orderInProgressesScheduledForDeletion and $this->orderInProgressesScheduledForDeletion->contains($l)) {
                $this->orderInProgressesScheduledForDeletion->remove($this->orderInProgressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param OrderInProgress $orderInProgress The OrderInProgress object to add.
     */
    protected function doAddOrderInProgress(OrderInProgress $orderInProgress)
    {
        $this->collOrderInProgresses[]= $orderInProgress;
        $orderInProgress->setOrder($this);
    }

    /**
     * @param  OrderInProgress $orderInProgress The OrderInProgress object to remove.
     * @return $this|ChildOrder The current object (for fluent API support)
     */
    public function removeOrderInProgress(OrderInProgress $orderInProgress)
    {
        if ($this->getOrderInProgresses()->contains($orderInProgress)) {
            $pos = $this->collOrderInProgresses->search($orderInProgress);
            $this->collOrderInProgresses->remove($pos);
            if (null === $this->orderInProgressesScheduledForDeletion) {
                $this->orderInProgressesScheduledForDeletion = clone $this->collOrderInProgresses;
                $this->orderInProgressesScheduledForDeletion->clear();
            }
            $this->orderInProgressesScheduledForDeletion[]= clone $orderInProgress;
            $orderInProgress->setOrder(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderInProgress[] List of OrderInProgress objects
     */
    public function getOrderInProgressesJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderInProgressQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

        return $this->getOrderInProgresses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related OrderInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param  Criteria            $criteria     optional Criteria object to narrow the query
     * @param  ConnectionInterface $con          optional connection object
     * @param  string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderInProgress[] List of OrderInProgress objects
     */
    public function getOrderInProgressesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderInProgressQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOrderInProgresses($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUserRelatedByCancellationCreatedByUserid) {
            $this->aUserRelatedByCancellationCreatedByUserid->removeOrderRelatedByCancellationCreatedByUserid($this);
        }
        if (null !== $this->aEventTable) {
            $this->aEventTable->removeOrder($this);
        }
        if (null !== $this->aUserRelatedByUserid) {
            $this->aUserRelatedByUserid->removeOrderRelatedByUserid($this);
        }
        $this->orderid = null;
        $this->event_tableid = null;
        $this->userid = null;
        $this->ordertime = null;
        $this->priority = null;
        $this->distribution_finished = null;
        $this->invoice_finished = null;
        $this->cancellation = null;
        $this->cancellation_created_by_userid = null;
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
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collOrderDetails) {
                foreach ($this->collOrderDetails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderInProgresses) {
                foreach ($this->collOrderInProgresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOrderDetails = null;
        $this->collOrderInProgresses = null;
        $this->aUserRelatedByCancellationCreatedByUserid = null;
        $this->aEventTable = null;
        $this->aUserRelatedByUserid = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrderTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
