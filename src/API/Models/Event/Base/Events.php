<?php

namespace API\Models\Event\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlaces;
use API\Models\DistributionPlace\DistributionsPlacesQuery;
use API\Models\DistributionPlace\Base\DistributionsPlaces as BaseDistributionsPlaces;
use API\Models\DistributionPlace\Map\DistributionsPlacesTableMap;
use API\Models\Event\Events as ChildEvents;
use API\Models\Event\EventsPrinters as ChildEventsPrinters;
use API\Models\Event\EventsPrintersQuery as ChildEventsPrintersQuery;
use API\Models\Event\EventsQuery as ChildEventsQuery;
use API\Models\Event\EventsTables as ChildEventsTables;
use API\Models\Event\EventsTablesQuery as ChildEventsTablesQuery;
use API\Models\Event\EventsUser as ChildEventsUser;
use API\Models\Event\EventsUserQuery as ChildEventsUserQuery;
use API\Models\Event\Map\EventsPrintersTableMap;
use API\Models\Event\Map\EventsTableMap;
use API\Models\Event\Map\EventsTablesTableMap;
use API\Models\Event\Map\EventsUserTableMap;
use API\Models\Menues\MenuExtras;
use API\Models\Menues\MenuExtrasQuery;
use API\Models\Menues\MenuSizes;
use API\Models\Menues\MenuSizesQuery;
use API\Models\Menues\MenuTypes;
use API\Models\Menues\MenuTypesQuery;
use API\Models\Menues\Base\MenuExtras as BaseMenuExtras;
use API\Models\Menues\Base\MenuSizes as BaseMenuSizes;
use API\Models\Menues\Base\MenuTypes as BaseMenuTypes;
use API\Models\Menues\Map\MenuExtrasTableMap;
use API\Models\Menues\Map\MenuSizesTableMap;
use API\Models\Menues\Map\MenuTypesTableMap;
use API\Models\Ordering\Orders;
use API\Models\Ordering\OrdersQuery;
use API\Models\Ordering\Base\Orders as BaseOrders;
use API\Models\Ordering\Map\OrdersTableMap;
use API\Models\Payment\Coupons;
use API\Models\Payment\CouponsQuery;
use API\Models\Payment\Base\Coupons as BaseCoupons;
use API\Models\Payment\Map\CouponsTableMap;
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
 * Base class that represents a row from the 'events' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Event.Base
 */
abstract class Events implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Event\\Map\\EventsTableMap';


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
     * The value for the date field.
     *
     * @var        DateTime
     */
    protected $date;

    /**
     * The value for the active field.
     *
     * @var        boolean
     */
    protected $active;

    /**
     * @var        ObjectCollection|Coupons[] Collection to store aggregation of Coupons objects.
     */
    protected $collCouponss;
    protected $collCouponssPartial;

    /**
     * @var        ObjectCollection|DistributionsPlaces[] Collection to store aggregation of DistributionsPlaces objects.
     */
    protected $collDistributionsPlacess;
    protected $collDistributionsPlacessPartial;

    /**
     * @var        ObjectCollection|ChildEventsPrinters[] Collection to store aggregation of ChildEventsPrinters objects.
     */
    protected $collEventsPrinterss;
    protected $collEventsPrinterssPartial;

    /**
     * @var        ObjectCollection|ChildEventsTables[] Collection to store aggregation of ChildEventsTables objects.
     */
    protected $collEventsTabless;
    protected $collEventsTablessPartial;

    /**
     * @var        ObjectCollection|ChildEventsUser[] Collection to store aggregation of ChildEventsUser objects.
     */
    protected $collEventsUsers;
    protected $collEventsUsersPartial;

    /**
     * @var        ObjectCollection|MenuExtras[] Collection to store aggregation of MenuExtras objects.
     */
    protected $collMenuExtrass;
    protected $collMenuExtrassPartial;

    /**
     * @var        ObjectCollection|MenuSizes[] Collection to store aggregation of MenuSizes objects.
     */
    protected $collMenuSizess;
    protected $collMenuSizessPartial;

    /**
     * @var        ObjectCollection|MenuTypes[] Collection to store aggregation of MenuTypes objects.
     */
    protected $collMenuTypess;
    protected $collMenuTypessPartial;

    /**
     * @var        ObjectCollection|Orders[] Collection to store aggregation of Orders objects.
     */
    protected $collOrderss;
    protected $collOrderssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Coupons[]
     */
    protected $couponssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionsPlaces[]
     */
    protected $distributionsPlacessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventsPrinters[]
     */
    protected $eventsPrinterssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventsTables[]
     */
    protected $eventsTablessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventsUser[]
     */
    protected $eventsUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuExtras[]
     */
    protected $menuExtrassScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuSizes[]
     */
    protected $menuSizessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuTypes[]
     */
    protected $menuTypessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Orders[]
     */
    protected $orderssScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Event\Base\Events object.
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
     * Compares this with another <code>Events</code> instance.  If
     * <code>obj</code> is an instance of <code>Events</code>, delegates to
     * <code>equals(Events)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Events The current object, for fluid interface
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
     * Get the [optionally formatted] temporal [date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDate($format = NULL)
    {
        if ($format === null) {
            return $this->date;
        } else {
            return $this->date instanceof \DateTimeInterface ? $this->date->format($format) : null;
        }
    }

    /**
     * Get the [active] column value.
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get the [active] column value.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->getActive();
    }

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventsTableMap::COL_EVENTID] = true;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventsTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->date->format("Y-m-d H:i:s.u")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventsTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Sets the value of the [active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[EventsTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventsTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventsTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventsTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventsTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = EventsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Event\\Events'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(EventsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCouponss = null;

            $this->collDistributionsPlacess = null;

            $this->collEventsPrinterss = null;

            $this->collEventsTabless = null;

            $this->collEventsUsers = null;

            $this->collMenuExtrass = null;

            $this->collMenuSizess = null;

            $this->collMenuTypess = null;

            $this->collOrderss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Events::setDeleted()
     * @see Events::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
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
                EventsTableMap::addInstanceToPool($this);
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

            if ($this->couponssScheduledForDeletion !== null) {
                if (!$this->couponssScheduledForDeletion->isEmpty()) {
                    \API\Models\Payment\CouponsQuery::create()
                        ->filterByPrimaryKeys($this->couponssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->couponssScheduledForDeletion = null;
                }
            }

            if ($this->collCouponss !== null) {
                foreach ($this->collCouponss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->distributionsPlacessScheduledForDeletion !== null) {
                if (!$this->distributionsPlacessScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionsPlacesQuery::create()
                        ->filterByPrimaryKeys($this->distributionsPlacessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionsPlacessScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionsPlacess !== null) {
                foreach ($this->collDistributionsPlacess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventsPrinterssScheduledForDeletion !== null) {
                if (!$this->eventsPrinterssScheduledForDeletion->isEmpty()) {
                    \API\Models\Event\EventsPrintersQuery::create()
                        ->filterByPrimaryKeys($this->eventsPrinterssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventsPrinterssScheduledForDeletion = null;
                }
            }

            if ($this->collEventsPrinterss !== null) {
                foreach ($this->collEventsPrinterss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventsTablessScheduledForDeletion !== null) {
                if (!$this->eventsTablessScheduledForDeletion->isEmpty()) {
                    \API\Models\Event\EventsTablesQuery::create()
                        ->filterByPrimaryKeys($this->eventsTablessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventsTablessScheduledForDeletion = null;
                }
            }

            if ($this->collEventsTabless !== null) {
                foreach ($this->collEventsTabless as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventsUsersScheduledForDeletion !== null) {
                if (!$this->eventsUsersScheduledForDeletion->isEmpty()) {
                    \API\Models\Event\EventsUserQuery::create()
                        ->filterByPrimaryKeys($this->eventsUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventsUsersScheduledForDeletion = null;
                }
            }

            if ($this->collEventsUsers !== null) {
                foreach ($this->collEventsUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuExtrassScheduledForDeletion !== null) {
                if (!$this->menuExtrassScheduledForDeletion->isEmpty()) {
                    \API\Models\Menues\MenuExtrasQuery::create()
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

            if ($this->menuSizessScheduledForDeletion !== null) {
                if (!$this->menuSizessScheduledForDeletion->isEmpty()) {
                    \API\Models\Menues\MenuSizesQuery::create()
                        ->filterByPrimaryKeys($this->menuSizessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuSizessScheduledForDeletion = null;
                }
            }

            if ($this->collMenuSizess !== null) {
                foreach ($this->collMenuSizess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuTypessScheduledForDeletion !== null) {
                if (!$this->menuTypessScheduledForDeletion->isEmpty()) {
                    \API\Models\Menues\MenuTypesQuery::create()
                        ->filterByPrimaryKeys($this->menuTypessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuTypessScheduledForDeletion = null;
                }
            }

            if ($this->collMenuTypess !== null) {
                foreach ($this->collMenuTypess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->orderssScheduledForDeletion !== null) {
                if (!$this->orderssScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrdersQuery::create()
                        ->filterByPrimaryKeys($this->orderssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->orderssScheduledForDeletion = null;
                }
            }

            if ($this->collOrderss !== null) {
                foreach ($this->collOrderss as $referrerFK) {
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

        $this->modifiedColumns[EventsTableMap::COL_EVENTID] = true;
        if (null !== $this->eventid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventsTableMap::COL_EVENTID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventsTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventsTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventsTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(EventsTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }

        $sql = sprintf(
            'INSERT INTO events (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'active':
                        $stmt->bindValue($identifier, (int) $this->active, PDO::PARAM_INT);
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
        $this->setEventid($pk);

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
        $pos = EventsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventid();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getDate();
                break;
            case 3:
                return $this->getActive();
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

        if (isset($alreadyDumpedObjects['Events'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Events'][$this->hashCode()] = true;
        $keys = EventsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventid(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getDate(),
            $keys[3] => $this->getActive(),
        );
        if ($result[$keys[2]] instanceof \DateTime) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collCouponss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'couponss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'couponss';
                        break;
                    default:
                        $key = 'Couponss';
                }

                $result[$key] = $this->collCouponss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDistributionsPlacess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionsPlacess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distributions_placess';
                        break;
                    default:
                        $key = 'DistributionsPlacess';
                }

                $result[$key] = $this->collDistributionsPlacess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventsPrinterss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventsPrinterss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events_printerss';
                        break;
                    default:
                        $key = 'EventsPrinterss';
                }

                $result[$key] = $this->collEventsPrinterss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventsTabless) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventsTabless';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events_tabless';
                        break;
                    default:
                        $key = 'EventsTabless';
                }

                $result[$key] = $this->collEventsTabless->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventsUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventsUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events_users';
                        break;
                    default:
                        $key = 'EventsUsers';
                }

                $result[$key] = $this->collEventsUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
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
            if (null !== $this->collMenuSizess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuSizess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_sizess';
                        break;
                    default:
                        $key = 'MenuSizess';
                }

                $result[$key] = $this->collMenuSizess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuTypess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuTypess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_typess';
                        break;
                    default:
                        $key = 'MenuTypess';
                }

                $result[$key] = $this->collMenuTypess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrderss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orderss';
                        break;
                    default:
                        $key = 'Orderss';
                }

                $result[$key] = $this->collOrderss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Event\Events
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Event\Events
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventid($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setDate($value);
                break;
            case 3:
                $this->setActive($value);
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
        $keys = EventsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDate($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setActive($arr[$keys[3]]);
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
     * @return $this|\API\Models\Event\Events The current object, for fluid interface
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
        $criteria = new Criteria(EventsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventsTableMap::COL_EVENTID)) {
            $criteria->add(EventsTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventsTableMap::COL_NAME)) {
            $criteria->add(EventsTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventsTableMap::COL_DATE)) {
            $criteria->add(EventsTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(EventsTableMap::COL_ACTIVE)) {
            $criteria->add(EventsTableMap::COL_ACTIVE, $this->active);
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
        $criteria = ChildEventsQuery::create();
        $criteria->add(EventsTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getEventid();

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
        return $this->getEventid();
    }

    /**
     * Generic method to set the primary key (eventid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setEventid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getEventid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Event\Events (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setDate($this->getDate());
        $copyObj->setActive($this->getActive());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCouponss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCoupons($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDistributionsPlacess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionsPlaces($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventsPrinterss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventsPrinters($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventsTabless() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventsTables($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventsUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventsUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuExtrass() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuExtras($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuSizess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuSizes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuTypess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuTypes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrders($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Event\Events Clone of current object.
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
        if ('Coupons' == $relationName) {
            return $this->initCouponss();
        }
        if ('DistributionsPlaces' == $relationName) {
            return $this->initDistributionsPlacess();
        }
        if ('EventsPrinters' == $relationName) {
            return $this->initEventsPrinterss();
        }
        if ('EventsTables' == $relationName) {
            return $this->initEventsTabless();
        }
        if ('EventsUser' == $relationName) {
            return $this->initEventsUsers();
        }
        if ('MenuExtras' == $relationName) {
            return $this->initMenuExtrass();
        }
        if ('MenuSizes' == $relationName) {
            return $this->initMenuSizess();
        }
        if ('MenuTypes' == $relationName) {
            return $this->initMenuTypess();
        }
        if ('Orders' == $relationName) {
            return $this->initOrderss();
        }
    }

    /**
     * Clears out the collCouponss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCouponss()
     */
    public function clearCouponss()
    {
        $this->collCouponss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCouponss collection loaded partially.
     */
    public function resetPartialCouponss($v = true)
    {
        $this->collCouponssPartial = $v;
    }

    /**
     * Initializes the collCouponss collection.
     *
     * By default this just sets the collCouponss collection to an empty array (like clearcollCouponss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCouponss($overrideExisting = true)
    {
        if (null !== $this->collCouponss && !$overrideExisting) {
            return;
        }

        $collectionClassName = CouponsTableMap::getTableMap()->getCollectionClassName();

        $this->collCouponss = new $collectionClassName;
        $this->collCouponss->setModel('\API\Models\Payment\Coupons');
    }

    /**
     * Gets an array of Coupons objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Coupons[] List of Coupons objects
     * @throws PropelException
     */
    public function getCouponss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCouponssPartial && !$this->isNew();
        if (null === $this->collCouponss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCouponss) {
                // return empty collection
                $this->initCouponss();
            } else {
                $collCouponss = CouponsQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCouponssPartial && count($collCouponss)) {
                        $this->initCouponss(false);

                        foreach ($collCouponss as $obj) {
                            if (false == $this->collCouponss->contains($obj)) {
                                $this->collCouponss->append($obj);
                            }
                        }

                        $this->collCouponssPartial = true;
                    }

                    return $collCouponss;
                }

                if ($partial && $this->collCouponss) {
                    foreach ($this->collCouponss as $obj) {
                        if ($obj->isNew()) {
                            $collCouponss[] = $obj;
                        }
                    }
                }

                $this->collCouponss = $collCouponss;
                $this->collCouponssPartial = false;
            }
        }

        return $this->collCouponss;
    }

    /**
     * Sets a collection of Coupons objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $couponss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setCouponss(Collection $couponss, ConnectionInterface $con = null)
    {
        /** @var Coupons[] $couponssToDelete */
        $couponssToDelete = $this->getCouponss(new Criteria(), $con)->diff($couponss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->couponssScheduledForDeletion = clone $couponssToDelete;

        foreach ($couponssToDelete as $couponsRemoved) {
            $couponsRemoved->setEvents(null);
        }

        $this->collCouponss = null;
        foreach ($couponss as $coupons) {
            $this->addCoupons($coupons);
        }

        $this->collCouponss = $couponss;
        $this->collCouponssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseCoupons objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseCoupons objects.
     * @throws PropelException
     */
    public function countCouponss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCouponssPartial && !$this->isNew();
        if (null === $this->collCouponss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCouponss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCouponss());
            }

            $query = CouponsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collCouponss);
    }

    /**
     * Method called to associate a Coupons object to this object
     * through the Coupons foreign key attribute.
     *
     * @param  Coupons $l Coupons
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addCoupons(Coupons $l)
    {
        if ($this->collCouponss === null) {
            $this->initCouponss();
            $this->collCouponssPartial = true;
        }

        if (!$this->collCouponss->contains($l)) {
            $this->doAddCoupons($l);

            if ($this->couponssScheduledForDeletion and $this->couponssScheduledForDeletion->contains($l)) {
                $this->couponssScheduledForDeletion->remove($this->couponssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Coupons $coupons The Coupons object to add.
     */
    protected function doAddCoupons(Coupons $coupons)
    {
        $this->collCouponss[]= $coupons;
        $coupons->setEvents($this);
    }

    /**
     * @param  Coupons $coupons The Coupons object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeCoupons(Coupons $coupons)
    {
        if ($this->getCouponss()->contains($coupons)) {
            $pos = $this->collCouponss->search($coupons);
            $this->collCouponss->remove($pos);
            if (null === $this->couponssScheduledForDeletion) {
                $this->couponssScheduledForDeletion = clone $this->collCouponss;
                $this->couponssScheduledForDeletion->clear();
            }
            $this->couponssScheduledForDeletion[]= clone $coupons;
            $coupons->setEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related Couponss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Coupons[] List of Coupons objects
     */
    public function getCouponssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = CouponsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getCouponss($query, $con);
    }

    /**
     * Clears out the collDistributionsPlacess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionsPlacess()
     */
    public function clearDistributionsPlacess()
    {
        $this->collDistributionsPlacess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionsPlacess collection loaded partially.
     */
    public function resetPartialDistributionsPlacess($v = true)
    {
        $this->collDistributionsPlacessPartial = $v;
    }

    /**
     * Initializes the collDistributionsPlacess collection.
     *
     * By default this just sets the collDistributionsPlacess collection to an empty array (like clearcollDistributionsPlacess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionsPlacess($overrideExisting = true)
    {
        if (null !== $this->collDistributionsPlacess && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionsPlacesTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionsPlacess = new $collectionClassName;
        $this->collDistributionsPlacess->setModel('\API\Models\DistributionPlace\DistributionsPlaces');
    }

    /**
     * Gets an array of DistributionsPlaces objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionsPlaces[] List of DistributionsPlaces objects
     * @throws PropelException
     */
    public function getDistributionsPlacess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacessPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacess) {
                // return empty collection
                $this->initDistributionsPlacess();
            } else {
                $collDistributionsPlacess = DistributionsPlacesQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionsPlacessPartial && count($collDistributionsPlacess)) {
                        $this->initDistributionsPlacess(false);

                        foreach ($collDistributionsPlacess as $obj) {
                            if (false == $this->collDistributionsPlacess->contains($obj)) {
                                $this->collDistributionsPlacess->append($obj);
                            }
                        }

                        $this->collDistributionsPlacessPartial = true;
                    }

                    return $collDistributionsPlacess;
                }

                if ($partial && $this->collDistributionsPlacess) {
                    foreach ($this->collDistributionsPlacess as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionsPlacess[] = $obj;
                        }
                    }
                }

                $this->collDistributionsPlacess = $collDistributionsPlacess;
                $this->collDistributionsPlacessPartial = false;
            }
        }

        return $this->collDistributionsPlacess;
    }

    /**
     * Sets a collection of DistributionsPlaces objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setDistributionsPlacess(Collection $distributionsPlacess, ConnectionInterface $con = null)
    {
        /** @var DistributionsPlaces[] $distributionsPlacessToDelete */
        $distributionsPlacessToDelete = $this->getDistributionsPlacess(new Criteria(), $con)->diff($distributionsPlacess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacessScheduledForDeletion = clone $distributionsPlacessToDelete;

        foreach ($distributionsPlacessToDelete as $distributionsPlacesRemoved) {
            $distributionsPlacesRemoved->setEvents(null);
        }

        $this->collDistributionsPlacess = null;
        foreach ($distributionsPlacess as $distributionsPlaces) {
            $this->addDistributionsPlaces($distributionsPlaces);
        }

        $this->collDistributionsPlacess = $distributionsPlacess;
        $this->collDistributionsPlacessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionsPlaces objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionsPlaces objects.
     * @throws PropelException
     */
    public function countDistributionsPlacess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacessPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionsPlacess());
            }

            $query = DistributionsPlacesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacess);
    }

    /**
     * Method called to associate a DistributionsPlaces object to this object
     * through the DistributionsPlaces foreign key attribute.
     *
     * @param  DistributionsPlaces $l DistributionsPlaces
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addDistributionsPlaces(DistributionsPlaces $l)
    {
        if ($this->collDistributionsPlacess === null) {
            $this->initDistributionsPlacess();
            $this->collDistributionsPlacessPartial = true;
        }

        if (!$this->collDistributionsPlacess->contains($l)) {
            $this->doAddDistributionsPlaces($l);

            if ($this->distributionsPlacessScheduledForDeletion and $this->distributionsPlacessScheduledForDeletion->contains($l)) {
                $this->distributionsPlacessScheduledForDeletion->remove($this->distributionsPlacessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionsPlaces $distributionsPlaces The DistributionsPlaces object to add.
     */
    protected function doAddDistributionsPlaces(DistributionsPlaces $distributionsPlaces)
    {
        $this->collDistributionsPlacess[]= $distributionsPlaces;
        $distributionsPlaces->setEvents($this);
    }

    /**
     * @param  DistributionsPlaces $distributionsPlaces The DistributionsPlaces object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeDistributionsPlaces(DistributionsPlaces $distributionsPlaces)
    {
        if ($this->getDistributionsPlacess()->contains($distributionsPlaces)) {
            $pos = $this->collDistributionsPlacess->search($distributionsPlaces);
            $this->collDistributionsPlacess->remove($pos);
            if (null === $this->distributionsPlacessScheduledForDeletion) {
                $this->distributionsPlacessScheduledForDeletion = clone $this->collDistributionsPlacess;
                $this->distributionsPlacessScheduledForDeletion->clear();
            }
            $this->distributionsPlacessScheduledForDeletion[]= clone $distributionsPlaces;
            $distributionsPlaces->setEvents(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventsPrinterss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventsPrinterss()
     */
    public function clearEventsPrinterss()
    {
        $this->collEventsPrinterss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventsPrinterss collection loaded partially.
     */
    public function resetPartialEventsPrinterss($v = true)
    {
        $this->collEventsPrinterssPartial = $v;
    }

    /**
     * Initializes the collEventsPrinterss collection.
     *
     * By default this just sets the collEventsPrinterss collection to an empty array (like clearcollEventsPrinterss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventsPrinterss($overrideExisting = true)
    {
        if (null !== $this->collEventsPrinterss && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventsPrintersTableMap::getTableMap()->getCollectionClassName();

        $this->collEventsPrinterss = new $collectionClassName;
        $this->collEventsPrinterss->setModel('\API\Models\Event\EventsPrinters');
    }

    /**
     * Gets an array of ChildEventsPrinters objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventsPrinters[] List of ChildEventsPrinters objects
     * @throws PropelException
     */
    public function getEventsPrinterss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPrinterssPartial && !$this->isNew();
        if (null === $this->collEventsPrinterss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventsPrinterss) {
                // return empty collection
                $this->initEventsPrinterss();
            } else {
                $collEventsPrinterss = ChildEventsPrintersQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventsPrinterssPartial && count($collEventsPrinterss)) {
                        $this->initEventsPrinterss(false);

                        foreach ($collEventsPrinterss as $obj) {
                            if (false == $this->collEventsPrinterss->contains($obj)) {
                                $this->collEventsPrinterss->append($obj);
                            }
                        }

                        $this->collEventsPrinterssPartial = true;
                    }

                    return $collEventsPrinterss;
                }

                if ($partial && $this->collEventsPrinterss) {
                    foreach ($this->collEventsPrinterss as $obj) {
                        if ($obj->isNew()) {
                            $collEventsPrinterss[] = $obj;
                        }
                    }
                }

                $this->collEventsPrinterss = $collEventsPrinterss;
                $this->collEventsPrinterssPartial = false;
            }
        }

        return $this->collEventsPrinterss;
    }

    /**
     * Sets a collection of ChildEventsPrinters objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventsPrinterss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setEventsPrinterss(Collection $eventsPrinterss, ConnectionInterface $con = null)
    {
        /** @var ChildEventsPrinters[] $eventsPrinterssToDelete */
        $eventsPrinterssToDelete = $this->getEventsPrinterss(new Criteria(), $con)->diff($eventsPrinterss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventsPrinterssScheduledForDeletion = clone $eventsPrinterssToDelete;

        foreach ($eventsPrinterssToDelete as $eventsPrintersRemoved) {
            $eventsPrintersRemoved->setEvents(null);
        }

        $this->collEventsPrinterss = null;
        foreach ($eventsPrinterss as $eventsPrinters) {
            $this->addEventsPrinters($eventsPrinters);
        }

        $this->collEventsPrinterss = $eventsPrinterss;
        $this->collEventsPrinterssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventsPrinters objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventsPrinters objects.
     * @throws PropelException
     */
    public function countEventsPrinterss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsPrinterssPartial && !$this->isNew();
        if (null === $this->collEventsPrinterss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventsPrinterss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventsPrinterss());
            }

            $query = ChildEventsPrintersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collEventsPrinterss);
    }

    /**
     * Method called to associate a ChildEventsPrinters object to this object
     * through the ChildEventsPrinters foreign key attribute.
     *
     * @param  ChildEventsPrinters $l ChildEventsPrinters
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addEventsPrinters(ChildEventsPrinters $l)
    {
        if ($this->collEventsPrinterss === null) {
            $this->initEventsPrinterss();
            $this->collEventsPrinterssPartial = true;
        }

        if (!$this->collEventsPrinterss->contains($l)) {
            $this->doAddEventsPrinters($l);

            if ($this->eventsPrinterssScheduledForDeletion and $this->eventsPrinterssScheduledForDeletion->contains($l)) {
                $this->eventsPrinterssScheduledForDeletion->remove($this->eventsPrinterssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventsPrinters $eventsPrinters The ChildEventsPrinters object to add.
     */
    protected function doAddEventsPrinters(ChildEventsPrinters $eventsPrinters)
    {
        $this->collEventsPrinterss[]= $eventsPrinters;
        $eventsPrinters->setEvents($this);
    }

    /**
     * @param  ChildEventsPrinters $eventsPrinters The ChildEventsPrinters object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeEventsPrinters(ChildEventsPrinters $eventsPrinters)
    {
        if ($this->getEventsPrinterss()->contains($eventsPrinters)) {
            $pos = $this->collEventsPrinterss->search($eventsPrinters);
            $this->collEventsPrinterss->remove($pos);
            if (null === $this->eventsPrinterssScheduledForDeletion) {
                $this->eventsPrinterssScheduledForDeletion = clone $this->collEventsPrinterss;
                $this->eventsPrinterssScheduledForDeletion->clear();
            }
            $this->eventsPrinterssScheduledForDeletion[]= clone $eventsPrinters;
            $eventsPrinters->setEvents(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventsTabless collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventsTabless()
     */
    public function clearEventsTabless()
    {
        $this->collEventsTabless = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventsTabless collection loaded partially.
     */
    public function resetPartialEventsTabless($v = true)
    {
        $this->collEventsTablessPartial = $v;
    }

    /**
     * Initializes the collEventsTabless collection.
     *
     * By default this just sets the collEventsTabless collection to an empty array (like clearcollEventsTabless());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventsTabless($overrideExisting = true)
    {
        if (null !== $this->collEventsTabless && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventsTablesTableMap::getTableMap()->getCollectionClassName();

        $this->collEventsTabless = new $collectionClassName;
        $this->collEventsTabless->setModel('\API\Models\Event\EventsTables');
    }

    /**
     * Gets an array of ChildEventsTables objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventsTables[] List of ChildEventsTables objects
     * @throws PropelException
     */
    public function getEventsTabless(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsTablessPartial && !$this->isNew();
        if (null === $this->collEventsTabless || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventsTabless) {
                // return empty collection
                $this->initEventsTabless();
            } else {
                $collEventsTabless = ChildEventsTablesQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventsTablessPartial && count($collEventsTabless)) {
                        $this->initEventsTabless(false);

                        foreach ($collEventsTabless as $obj) {
                            if (false == $this->collEventsTabless->contains($obj)) {
                                $this->collEventsTabless->append($obj);
                            }
                        }

                        $this->collEventsTablessPartial = true;
                    }

                    return $collEventsTabless;
                }

                if ($partial && $this->collEventsTabless) {
                    foreach ($this->collEventsTabless as $obj) {
                        if ($obj->isNew()) {
                            $collEventsTabless[] = $obj;
                        }
                    }
                }

                $this->collEventsTabless = $collEventsTabless;
                $this->collEventsTablessPartial = false;
            }
        }

        return $this->collEventsTabless;
    }

    /**
     * Sets a collection of ChildEventsTables objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventsTabless A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setEventsTabless(Collection $eventsTabless, ConnectionInterface $con = null)
    {
        /** @var ChildEventsTables[] $eventsTablessToDelete */
        $eventsTablessToDelete = $this->getEventsTabless(new Criteria(), $con)->diff($eventsTabless);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventsTablessScheduledForDeletion = clone $eventsTablessToDelete;

        foreach ($eventsTablessToDelete as $eventsTablesRemoved) {
            $eventsTablesRemoved->setEvents(null);
        }

        $this->collEventsTabless = null;
        foreach ($eventsTabless as $eventsTables) {
            $this->addEventsTables($eventsTables);
        }

        $this->collEventsTabless = $eventsTabless;
        $this->collEventsTablessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventsTables objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventsTables objects.
     * @throws PropelException
     */
    public function countEventsTabless(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsTablessPartial && !$this->isNew();
        if (null === $this->collEventsTabless || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventsTabless) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventsTabless());
            }

            $query = ChildEventsTablesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collEventsTabless);
    }

    /**
     * Method called to associate a ChildEventsTables object to this object
     * through the ChildEventsTables foreign key attribute.
     *
     * @param  ChildEventsTables $l ChildEventsTables
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addEventsTables(ChildEventsTables $l)
    {
        if ($this->collEventsTabless === null) {
            $this->initEventsTabless();
            $this->collEventsTablessPartial = true;
        }

        if (!$this->collEventsTabless->contains($l)) {
            $this->doAddEventsTables($l);

            if ($this->eventsTablessScheduledForDeletion and $this->eventsTablessScheduledForDeletion->contains($l)) {
                $this->eventsTablessScheduledForDeletion->remove($this->eventsTablessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventsTables $eventsTables The ChildEventsTables object to add.
     */
    protected function doAddEventsTables(ChildEventsTables $eventsTables)
    {
        $this->collEventsTabless[]= $eventsTables;
        $eventsTables->setEvents($this);
    }

    /**
     * @param  ChildEventsTables $eventsTables The ChildEventsTables object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeEventsTables(ChildEventsTables $eventsTables)
    {
        if ($this->getEventsTabless()->contains($eventsTables)) {
            $pos = $this->collEventsTabless->search($eventsTables);
            $this->collEventsTabless->remove($pos);
            if (null === $this->eventsTablessScheduledForDeletion) {
                $this->eventsTablessScheduledForDeletion = clone $this->collEventsTabless;
                $this->eventsTablessScheduledForDeletion->clear();
            }
            $this->eventsTablessScheduledForDeletion[]= clone $eventsTables;
            $eventsTables->setEvents(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventsUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventsUsers()
     */
    public function clearEventsUsers()
    {
        $this->collEventsUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventsUsers collection loaded partially.
     */
    public function resetPartialEventsUsers($v = true)
    {
        $this->collEventsUsersPartial = $v;
    }

    /**
     * Initializes the collEventsUsers collection.
     *
     * By default this just sets the collEventsUsers collection to an empty array (like clearcollEventsUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventsUsers($overrideExisting = true)
    {
        if (null !== $this->collEventsUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventsUserTableMap::getTableMap()->getCollectionClassName();

        $this->collEventsUsers = new $collectionClassName;
        $this->collEventsUsers->setModel('\API\Models\Event\EventsUser');
    }

    /**
     * Gets an array of ChildEventsUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventsUser[] List of ChildEventsUser objects
     * @throws PropelException
     */
    public function getEventsUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsUsersPartial && !$this->isNew();
        if (null === $this->collEventsUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventsUsers) {
                // return empty collection
                $this->initEventsUsers();
            } else {
                $collEventsUsers = ChildEventsUserQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventsUsersPartial && count($collEventsUsers)) {
                        $this->initEventsUsers(false);

                        foreach ($collEventsUsers as $obj) {
                            if (false == $this->collEventsUsers->contains($obj)) {
                                $this->collEventsUsers->append($obj);
                            }
                        }

                        $this->collEventsUsersPartial = true;
                    }

                    return $collEventsUsers;
                }

                if ($partial && $this->collEventsUsers) {
                    foreach ($this->collEventsUsers as $obj) {
                        if ($obj->isNew()) {
                            $collEventsUsers[] = $obj;
                        }
                    }
                }

                $this->collEventsUsers = $collEventsUsers;
                $this->collEventsUsersPartial = false;
            }
        }

        return $this->collEventsUsers;
    }

    /**
     * Sets a collection of ChildEventsUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventsUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setEventsUsers(Collection $eventsUsers, ConnectionInterface $con = null)
    {
        /** @var ChildEventsUser[] $eventsUsersToDelete */
        $eventsUsersToDelete = $this->getEventsUsers(new Criteria(), $con)->diff($eventsUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventsUsersScheduledForDeletion = clone $eventsUsersToDelete;

        foreach ($eventsUsersToDelete as $eventsUserRemoved) {
            $eventsUserRemoved->setEvents(null);
        }

        $this->collEventsUsers = null;
        foreach ($eventsUsers as $eventsUser) {
            $this->addEventsUser($eventsUser);
        }

        $this->collEventsUsers = $eventsUsers;
        $this->collEventsUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventsUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventsUser objects.
     * @throws PropelException
     */
    public function countEventsUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventsUsersPartial && !$this->isNew();
        if (null === $this->collEventsUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventsUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventsUsers());
            }

            $query = ChildEventsUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collEventsUsers);
    }

    /**
     * Method called to associate a ChildEventsUser object to this object
     * through the ChildEventsUser foreign key attribute.
     *
     * @param  ChildEventsUser $l ChildEventsUser
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addEventsUser(ChildEventsUser $l)
    {
        if ($this->collEventsUsers === null) {
            $this->initEventsUsers();
            $this->collEventsUsersPartial = true;
        }

        if (!$this->collEventsUsers->contains($l)) {
            $this->doAddEventsUser($l);

            if ($this->eventsUsersScheduledForDeletion and $this->eventsUsersScheduledForDeletion->contains($l)) {
                $this->eventsUsersScheduledForDeletion->remove($this->eventsUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventsUser $eventsUser The ChildEventsUser object to add.
     */
    protected function doAddEventsUser(ChildEventsUser $eventsUser)
    {
        $this->collEventsUsers[]= $eventsUser;
        $eventsUser->setEvents($this);
    }

    /**
     * @param  ChildEventsUser $eventsUser The ChildEventsUser object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeEventsUser(ChildEventsUser $eventsUser)
    {
        if ($this->getEventsUsers()->contains($eventsUser)) {
            $pos = $this->collEventsUsers->search($eventsUser);
            $this->collEventsUsers->remove($pos);
            if (null === $this->eventsUsersScheduledForDeletion) {
                $this->eventsUsersScheduledForDeletion = clone $this->collEventsUsers;
                $this->eventsUsersScheduledForDeletion->clear();
            }
            $this->eventsUsersScheduledForDeletion[]= clone $eventsUser;
            $eventsUser->setEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related EventsUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventsUser[] List of ChildEventsUser objects
     */
    public function getEventsUsersJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventsUserQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getEventsUsers($query, $con);
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
        $this->collMenuExtrass->setModel('\API\Models\Menues\MenuExtras');
    }

    /**
     * Gets an array of MenuExtras objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MenuExtras[] List of MenuExtras objects
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
                $collMenuExtrass = MenuExtrasQuery::create(null, $criteria)
                    ->filterByEvents($this)
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
     * Sets a collection of MenuExtras objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuExtrass A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setMenuExtrass(Collection $menuExtrass, ConnectionInterface $con = null)
    {
        /** @var MenuExtras[] $menuExtrassToDelete */
        $menuExtrassToDelete = $this->getMenuExtrass(new Criteria(), $con)->diff($menuExtrass);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuExtrassScheduledForDeletion = clone $menuExtrassToDelete;

        foreach ($menuExtrassToDelete as $menuExtrasRemoved) {
            $menuExtrasRemoved->setEvents(null);
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
     * Returns the number of related BaseMenuExtras objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMenuExtras objects.
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

            $query = MenuExtrasQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collMenuExtrass);
    }

    /**
     * Method called to associate a MenuExtras object to this object
     * through the MenuExtras foreign key attribute.
     *
     * @param  MenuExtras $l MenuExtras
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addMenuExtras(MenuExtras $l)
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
     * @param MenuExtras $menuExtras The MenuExtras object to add.
     */
    protected function doAddMenuExtras(MenuExtras $menuExtras)
    {
        $this->collMenuExtrass[]= $menuExtras;
        $menuExtras->setEvents($this);
    }

    /**
     * @param  MenuExtras $menuExtras The MenuExtras object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeMenuExtras(MenuExtras $menuExtras)
    {
        if ($this->getMenuExtrass()->contains($menuExtras)) {
            $pos = $this->collMenuExtrass->search($menuExtras);
            $this->collMenuExtrass->remove($pos);
            if (null === $this->menuExtrassScheduledForDeletion) {
                $this->menuExtrassScheduledForDeletion = clone $this->collMenuExtrass;
                $this->menuExtrassScheduledForDeletion->clear();
            }
            $this->menuExtrassScheduledForDeletion[]= clone $menuExtras;
            $menuExtras->setEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related MenuExtrass from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|MenuExtras[] List of MenuExtras objects
     */
    public function getMenuExtrassJoinAvailabilitys(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = MenuExtrasQuery::create(null, $criteria);
        $query->joinWith('Availabilitys', $joinBehavior);

        return $this->getMenuExtrass($query, $con);
    }

    /**
     * Clears out the collMenuSizess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuSizess()
     */
    public function clearMenuSizess()
    {
        $this->collMenuSizess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuSizess collection loaded partially.
     */
    public function resetPartialMenuSizess($v = true)
    {
        $this->collMenuSizessPartial = $v;
    }

    /**
     * Initializes the collMenuSizess collection.
     *
     * By default this just sets the collMenuSizess collection to an empty array (like clearcollMenuSizess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuSizess($overrideExisting = true)
    {
        if (null !== $this->collMenuSizess && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuSizesTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuSizess = new $collectionClassName;
        $this->collMenuSizess->setModel('\API\Models\Menues\MenuSizes');
    }

    /**
     * Gets an array of MenuSizes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MenuSizes[] List of MenuSizes objects
     * @throws PropelException
     */
    public function getMenuSizess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuSizessPartial && !$this->isNew();
        if (null === $this->collMenuSizess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuSizess) {
                // return empty collection
                $this->initMenuSizess();
            } else {
                $collMenuSizess = MenuSizesQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuSizessPartial && count($collMenuSizess)) {
                        $this->initMenuSizess(false);

                        foreach ($collMenuSizess as $obj) {
                            if (false == $this->collMenuSizess->contains($obj)) {
                                $this->collMenuSizess->append($obj);
                            }
                        }

                        $this->collMenuSizessPartial = true;
                    }

                    return $collMenuSizess;
                }

                if ($partial && $this->collMenuSizess) {
                    foreach ($this->collMenuSizess as $obj) {
                        if ($obj->isNew()) {
                            $collMenuSizess[] = $obj;
                        }
                    }
                }

                $this->collMenuSizess = $collMenuSizess;
                $this->collMenuSizessPartial = false;
            }
        }

        return $this->collMenuSizess;
    }

    /**
     * Sets a collection of MenuSizes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuSizess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setMenuSizess(Collection $menuSizess, ConnectionInterface $con = null)
    {
        /** @var MenuSizes[] $menuSizessToDelete */
        $menuSizessToDelete = $this->getMenuSizess(new Criteria(), $con)->diff($menuSizess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuSizessScheduledForDeletion = clone $menuSizessToDelete;

        foreach ($menuSizessToDelete as $menuSizesRemoved) {
            $menuSizesRemoved->setEvents(null);
        }

        $this->collMenuSizess = null;
        foreach ($menuSizess as $menuSizes) {
            $this->addMenuSizes($menuSizes);
        }

        $this->collMenuSizess = $menuSizess;
        $this->collMenuSizessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMenuSizes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMenuSizes objects.
     * @throws PropelException
     */
    public function countMenuSizess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuSizessPartial && !$this->isNew();
        if (null === $this->collMenuSizess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuSizess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuSizess());
            }

            $query = MenuSizesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collMenuSizess);
    }

    /**
     * Method called to associate a MenuSizes object to this object
     * through the MenuSizes foreign key attribute.
     *
     * @param  MenuSizes $l MenuSizes
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addMenuSizes(MenuSizes $l)
    {
        if ($this->collMenuSizess === null) {
            $this->initMenuSizess();
            $this->collMenuSizessPartial = true;
        }

        if (!$this->collMenuSizess->contains($l)) {
            $this->doAddMenuSizes($l);

            if ($this->menuSizessScheduledForDeletion and $this->menuSizessScheduledForDeletion->contains($l)) {
                $this->menuSizessScheduledForDeletion->remove($this->menuSizessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MenuSizes $menuSizes The MenuSizes object to add.
     */
    protected function doAddMenuSizes(MenuSizes $menuSizes)
    {
        $this->collMenuSizess[]= $menuSizes;
        $menuSizes->setEvents($this);
    }

    /**
     * @param  MenuSizes $menuSizes The MenuSizes object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeMenuSizes(MenuSizes $menuSizes)
    {
        if ($this->getMenuSizess()->contains($menuSizes)) {
            $pos = $this->collMenuSizess->search($menuSizes);
            $this->collMenuSizess->remove($pos);
            if (null === $this->menuSizessScheduledForDeletion) {
                $this->menuSizessScheduledForDeletion = clone $this->collMenuSizess;
                $this->menuSizessScheduledForDeletion->clear();
            }
            $this->menuSizessScheduledForDeletion[]= clone $menuSizes;
            $menuSizes->setEvents(null);
        }

        return $this;
    }

    /**
     * Clears out the collMenuTypess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuTypess()
     */
    public function clearMenuTypess()
    {
        $this->collMenuTypess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuTypess collection loaded partially.
     */
    public function resetPartialMenuTypess($v = true)
    {
        $this->collMenuTypessPartial = $v;
    }

    /**
     * Initializes the collMenuTypess collection.
     *
     * By default this just sets the collMenuTypess collection to an empty array (like clearcollMenuTypess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuTypess($overrideExisting = true)
    {
        if (null !== $this->collMenuTypess && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuTypesTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuTypess = new $collectionClassName;
        $this->collMenuTypess->setModel('\API\Models\Menues\MenuTypes');
    }

    /**
     * Gets an array of MenuTypes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MenuTypes[] List of MenuTypes objects
     * @throws PropelException
     */
    public function getMenuTypess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuTypessPartial && !$this->isNew();
        if (null === $this->collMenuTypess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuTypess) {
                // return empty collection
                $this->initMenuTypess();
            } else {
                $collMenuTypess = MenuTypesQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuTypessPartial && count($collMenuTypess)) {
                        $this->initMenuTypess(false);

                        foreach ($collMenuTypess as $obj) {
                            if (false == $this->collMenuTypess->contains($obj)) {
                                $this->collMenuTypess->append($obj);
                            }
                        }

                        $this->collMenuTypessPartial = true;
                    }

                    return $collMenuTypess;
                }

                if ($partial && $this->collMenuTypess) {
                    foreach ($this->collMenuTypess as $obj) {
                        if ($obj->isNew()) {
                            $collMenuTypess[] = $obj;
                        }
                    }
                }

                $this->collMenuTypess = $collMenuTypess;
                $this->collMenuTypessPartial = false;
            }
        }

        return $this->collMenuTypess;
    }

    /**
     * Sets a collection of MenuTypes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuTypess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setMenuTypess(Collection $menuTypess, ConnectionInterface $con = null)
    {
        /** @var MenuTypes[] $menuTypessToDelete */
        $menuTypessToDelete = $this->getMenuTypess(new Criteria(), $con)->diff($menuTypess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuTypessScheduledForDeletion = clone $menuTypessToDelete;

        foreach ($menuTypessToDelete as $menuTypesRemoved) {
            $menuTypesRemoved->setEvents(null);
        }

        $this->collMenuTypess = null;
        foreach ($menuTypess as $menuTypes) {
            $this->addMenuTypes($menuTypes);
        }

        $this->collMenuTypess = $menuTypess;
        $this->collMenuTypessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMenuTypes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMenuTypes objects.
     * @throws PropelException
     */
    public function countMenuTypess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuTypessPartial && !$this->isNew();
        if (null === $this->collMenuTypess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuTypess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuTypess());
            }

            $query = MenuTypesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collMenuTypess);
    }

    /**
     * Method called to associate a MenuTypes object to this object
     * through the MenuTypes foreign key attribute.
     *
     * @param  MenuTypes $l MenuTypes
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addMenuTypes(MenuTypes $l)
    {
        if ($this->collMenuTypess === null) {
            $this->initMenuTypess();
            $this->collMenuTypessPartial = true;
        }

        if (!$this->collMenuTypess->contains($l)) {
            $this->doAddMenuTypes($l);

            if ($this->menuTypessScheduledForDeletion and $this->menuTypessScheduledForDeletion->contains($l)) {
                $this->menuTypessScheduledForDeletion->remove($this->menuTypessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MenuTypes $menuTypes The MenuTypes object to add.
     */
    protected function doAddMenuTypes(MenuTypes $menuTypes)
    {
        $this->collMenuTypess[]= $menuTypes;
        $menuTypes->setEvents($this);
    }

    /**
     * @param  MenuTypes $menuTypes The MenuTypes object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeMenuTypes(MenuTypes $menuTypes)
    {
        if ($this->getMenuTypess()->contains($menuTypes)) {
            $pos = $this->collMenuTypess->search($menuTypes);
            $this->collMenuTypess->remove($pos);
            if (null === $this->menuTypessScheduledForDeletion) {
                $this->menuTypessScheduledForDeletion = clone $this->collMenuTypess;
                $this->menuTypessScheduledForDeletion->clear();
            }
            $this->menuTypessScheduledForDeletion[]= clone $menuTypes;
            $menuTypes->setEvents(null);
        }

        return $this;
    }

    /**
     * Clears out the collOrderss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrderss()
     */
    public function clearOrderss()
    {
        $this->collOrderss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrderss collection loaded partially.
     */
    public function resetPartialOrderss($v = true)
    {
        $this->collOrderssPartial = $v;
    }

    /**
     * Initializes the collOrderss collection.
     *
     * By default this just sets the collOrderss collection to an empty array (like clearcollOrderss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrderss($overrideExisting = true)
    {
        if (null !== $this->collOrderss && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderss = new $collectionClassName;
        $this->collOrderss->setModel('\API\Models\Ordering\Orders');
    }

    /**
     * Gets an array of Orders objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvents is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Orders[] List of Orders objects
     * @throws PropelException
     */
    public function getOrderss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderssPartial && !$this->isNew();
        if (null === $this->collOrderss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrderss) {
                // return empty collection
                $this->initOrderss();
            } else {
                $collOrderss = OrdersQuery::create(null, $criteria)
                    ->filterByEvents($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrderssPartial && count($collOrderss)) {
                        $this->initOrderss(false);

                        foreach ($collOrderss as $obj) {
                            if (false == $this->collOrderss->contains($obj)) {
                                $this->collOrderss->append($obj);
                            }
                        }

                        $this->collOrderssPartial = true;
                    }

                    return $collOrderss;
                }

                if ($partial && $this->collOrderss) {
                    foreach ($this->collOrderss as $obj) {
                        if ($obj->isNew()) {
                            $collOrderss[] = $obj;
                        }
                    }
                }

                $this->collOrderss = $collOrderss;
                $this->collOrderssPartial = false;
            }
        }

        return $this->collOrderss;
    }

    /**
     * Sets a collection of Orders objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function setOrderss(Collection $orderss, ConnectionInterface $con = null)
    {
        /** @var Orders[] $orderssToDelete */
        $orderssToDelete = $this->getOrderss(new Criteria(), $con)->diff($orderss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->orderssScheduledForDeletion = clone $orderssToDelete;

        foreach ($orderssToDelete as $ordersRemoved) {
            $ordersRemoved->setEvents(null);
        }

        $this->collOrderss = null;
        foreach ($orderss as $orders) {
            $this->addOrders($orders);
        }

        $this->collOrderss = $orderss;
        $this->collOrderssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrders objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrders objects.
     * @throws PropelException
     */
    public function countOrderss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderssPartial && !$this->isNew();
        if (null === $this->collOrderss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrderss());
            }

            $query = OrdersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvents($this)
                ->count($con);
        }

        return count($this->collOrderss);
    }

    /**
     * Method called to associate a Orders object to this object
     * through the Orders foreign key attribute.
     *
     * @param  Orders $l Orders
     * @return $this|\API\Models\Event\Events The current object (for fluent API support)
     */
    public function addOrders(Orders $l)
    {
        if ($this->collOrderss === null) {
            $this->initOrderss();
            $this->collOrderssPartial = true;
        }

        if (!$this->collOrderss->contains($l)) {
            $this->doAddOrders($l);

            if ($this->orderssScheduledForDeletion and $this->orderssScheduledForDeletion->contains($l)) {
                $this->orderssScheduledForDeletion->remove($this->orderssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Orders $orders The Orders object to add.
     */
    protected function doAddOrders(Orders $orders)
    {
        $this->collOrderss[]= $orders;
        $orders->setEvents($this);
    }

    /**
     * @param  Orders $orders The Orders object to remove.
     * @return $this|ChildEvents The current object (for fluent API support)
     */
    public function removeOrders(Orders $orders)
    {
        if ($this->getOrderss()->contains($orders)) {
            $pos = $this->collOrderss->search($orders);
            $this->collOrderss->remove($pos);
            if (null === $this->orderssScheduledForDeletion) {
                $this->orderssScheduledForDeletion = clone $this->collOrderss;
                $this->orderssScheduledForDeletion->clear();
            }
            $this->orderssScheduledForDeletion[]= clone $orders;
            $orders->setEvents(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related Orderss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Orders[] List of Orders objects
     */
    public function getOrderssJoinEventsTables(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersQuery::create(null, $criteria);
        $query->joinWith('EventsTables', $joinBehavior);

        return $this->getOrderss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Events is new, it will return
     * an empty collection; or if this Events has previously
     * been saved, it will retrieve related Orderss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Events.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Orders[] List of Orders objects
     */
    public function getOrderssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getOrderss($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->eventid = null;
        $this->name = null;
        $this->date = null;
        $this->active = null;
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
            if ($this->collCouponss) {
                foreach ($this->collCouponss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDistributionsPlacess) {
                foreach ($this->collDistributionsPlacess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventsPrinterss) {
                foreach ($this->collEventsPrinterss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventsTabless) {
                foreach ($this->collEventsTabless as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventsUsers) {
                foreach ($this->collEventsUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuExtrass) {
                foreach ($this->collMenuExtrass as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuSizess) {
                foreach ($this->collMenuSizess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuTypess) {
                foreach ($this->collMenuTypess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderss) {
                foreach ($this->collOrderss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCouponss = null;
        $this->collDistributionsPlacess = null;
        $this->collEventsPrinterss = null;
        $this->collEventsTabless = null;
        $this->collEventsUsers = null;
        $this->collMenuExtrass = null;
        $this->collMenuSizess = null;
        $this->collMenuTypess = null;
        $this->collOrderss = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventsTableMap::DEFAULT_STRING_FORMAT);
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
