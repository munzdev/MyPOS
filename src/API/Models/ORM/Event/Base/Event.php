<?php

namespace API\Models\ORM\Event\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\ORM\DistributionPlace\DistributionPlace;
use API\Models\ORM\DistributionPlace\DistributionPlaceQuery;
use API\Models\ORM\DistributionPlace\Base\DistributionPlace as BaseDistributionPlace;
use API\Models\ORM\DistributionPlace\Map\DistributionPlaceTableMap;
use API\Models\ORM\Event\Event as ChildEvent;
use API\Models\ORM\Event\EventBankinformation as ChildEventBankinformation;
use API\Models\ORM\Event\EventBankinformationQuery as ChildEventBankinformationQuery;
use API\Models\ORM\Event\EventContact as ChildEventContact;
use API\Models\ORM\Event\EventContactQuery as ChildEventContactQuery;
use API\Models\ORM\Event\EventPrinter as ChildEventPrinter;
use API\Models\ORM\Event\EventPrinterQuery as ChildEventPrinterQuery;
use API\Models\ORM\Event\EventQuery as ChildEventQuery;
use API\Models\ORM\Event\EventTable as ChildEventTable;
use API\Models\ORM\Event\EventTableQuery as ChildEventTableQuery;
use API\Models\ORM\Event\EventUser as ChildEventUser;
use API\Models\ORM\Event\EventUserQuery as ChildEventUserQuery;
use API\Models\ORM\Event\Map\EventBankinformationTableMap;
use API\Models\ORM\Event\Map\EventContactTableMap;
use API\Models\ORM\Event\Map\EventPrinterTableMap;
use API\Models\ORM\Event\Map\EventTableMap;
use API\Models\ORM\Event\Map\EventTableTableMap;
use API\Models\ORM\Event\Map\EventUserTableMap;
use API\Models\ORM\Invoice\InvoiceWarningType;
use API\Models\ORM\Invoice\InvoiceWarningTypeQuery;
use API\Models\ORM\Invoice\Base\InvoiceWarningType as BaseInvoiceWarningType;
use API\Models\ORM\Invoice\Map\InvoiceWarningTypeTableMap;
use API\Models\ORM\Menu\MenuExtra;
use API\Models\ORM\Menu\MenuExtraQuery;
use API\Models\ORM\Menu\MenuSize;
use API\Models\ORM\Menu\MenuSizeQuery;
use API\Models\ORM\Menu\MenuType;
use API\Models\ORM\Menu\MenuTypeQuery;
use API\Models\ORM\Menu\Base\MenuExtra as BaseMenuExtra;
use API\Models\ORM\Menu\Base\MenuSize as BaseMenuSize;
use API\Models\ORM\Menu\Base\MenuType as BaseMenuType;
use API\Models\ORM\Menu\Map\MenuExtraTableMap;
use API\Models\ORM\Menu\Map\MenuSizeTableMap;
use API\Models\ORM\Menu\Map\MenuTypeTableMap;
use API\Models\ORM\Payment\Coupon;
use API\Models\ORM\Payment\CouponQuery;
use API\Models\ORM\Payment\Base\Coupon as BaseCoupon;
use API\Models\ORM\Payment\Map\CouponTableMap;
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
 * Base class that represents a row from the 'event' table.
 *
 * 
 *
 * @package    propel.generator.API.Models.ORM.Event.Base
 */
abstract class Event implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\ORM\\Event\\Map\\EventTableMap';


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
     * @var        ObjectCollection|Coupon[] Collection to store aggregation of Coupon objects.
     */
    protected $collCoupons;
    protected $collCouponsPartial;

    /**
     * @var        ObjectCollection|ChildEventBankinformation[] Collection to store aggregation of ChildEventBankinformation objects.
     */
    protected $collEventBankinformations;
    protected $collEventBankinformationsPartial;

    /**
     * @var        ObjectCollection|ChildEventContact[] Collection to store aggregation of ChildEventContact objects.
     */
    protected $collEventContacts;
    protected $collEventContactsPartial;

    /**
     * @var        ObjectCollection|DistributionPlace[] Collection to store aggregation of DistributionPlace objects.
     */
    protected $collDistributionPlaces;
    protected $collDistributionPlacesPartial;

    /**
     * @var        ObjectCollection|ChildEventPrinter[] Collection to store aggregation of ChildEventPrinter objects.
     */
    protected $collEventPrinters;
    protected $collEventPrintersPartial;

    /**
     * @var        ObjectCollection|ChildEventTable[] Collection to store aggregation of ChildEventTable objects.
     */
    protected $collEventTables;
    protected $collEventTablesPartial;

    /**
     * @var        ObjectCollection|ChildEventUser[] Collection to store aggregation of ChildEventUser objects.
     */
    protected $collEventUsers;
    protected $collEventUsersPartial;

    /**
     * @var        ObjectCollection|MenuExtra[] Collection to store aggregation of MenuExtra objects.
     */
    protected $collMenuExtras;
    protected $collMenuExtrasPartial;

    /**
     * @var        ObjectCollection|MenuSize[] Collection to store aggregation of MenuSize objects.
     */
    protected $collMenuSizes;
    protected $collMenuSizesPartial;

    /**
     * @var        ObjectCollection|MenuType[] Collection to store aggregation of MenuType objects.
     */
    protected $collMenuTypes;
    protected $collMenuTypesPartial;

    /**
     * @var        ObjectCollection|InvoiceWarningType[] Collection to store aggregation of InvoiceWarningType objects.
     */
    protected $collInvoiceWarningTypes;
    protected $collInvoiceWarningTypesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Coupon[]
     */
    protected $couponsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventBankinformation[]
     */
    protected $eventBankinformationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventContact[]
     */
    protected $eventContactsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionPlace[]
     */
    protected $distributionPlacesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventPrinter[]
     */
    protected $eventPrintersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventTable[]
     */
    protected $eventTablesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventUser[]
     */
    protected $eventUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuExtra[]
     */
    protected $menuExtrasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuSize[]
     */
    protected $menuSizesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuType[]
     */
    protected $menuTypesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|InvoiceWarningType[]
     */
    protected $invoiceWarningTypesScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\ORM\Event\Base\Event object.
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
     * Compares this with another <code>Event</code> instance.  If
     * <code>obj</code> is an instance of <code>Event</code>, delegates to
     * <code>equals(Event)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Event The current object, for fluid interface
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
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventTableMap::COL_EVENTID] = true;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [name] column.
     * 
     * @param string $v new value
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->date->format("Y-m-d H:i:s.u")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_DATE] = true;
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
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
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
            $this->modifiedColumns[EventTableMap::COL_ACTIVE] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = EventTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\ORM\\Event\\Event'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCoupons = null;

            $this->collEventBankinformations = null;

            $this->collEventContacts = null;

            $this->collDistributionPlaces = null;

            $this->collEventPrinters = null;

            $this->collEventTables = null;

            $this->collEventUsers = null;

            $this->collMenuExtras = null;

            $this->collMenuSizes = null;

            $this->collMenuTypes = null;

            $this->collInvoiceWarningTypes = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Event::setDeleted()
     * @see Event::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
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
                EventTableMap::addInstanceToPool($this);
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

            if ($this->couponsScheduledForDeletion !== null) {
                if (!$this->couponsScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Payment\CouponQuery::create()
                        ->filterByPrimaryKeys($this->couponsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->couponsScheduledForDeletion = null;
                }
            }

            if ($this->collCoupons !== null) {
                foreach ($this->collCoupons as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventBankinformationsScheduledForDeletion !== null) {
                if (!$this->eventBankinformationsScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Event\EventBankinformationQuery::create()
                        ->filterByPrimaryKeys($this->eventBankinformationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventBankinformationsScheduledForDeletion = null;
                }
            }

            if ($this->collEventBankinformations !== null) {
                foreach ($this->collEventBankinformations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventContactsScheduledForDeletion !== null) {
                if (!$this->eventContactsScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Event\EventContactQuery::create()
                        ->filterByPrimaryKeys($this->eventContactsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventContactsScheduledForDeletion = null;
                }
            }

            if ($this->collEventContacts !== null) {
                foreach ($this->collEventContacts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->distributionPlacesScheduledForDeletion !== null) {
                if (!$this->distributionPlacesScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\DistributionPlace\DistributionPlaceQuery::create()
                        ->filterByPrimaryKeys($this->distributionPlacesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionPlacesScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionPlaces !== null) {
                foreach ($this->collDistributionPlaces as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventPrintersScheduledForDeletion !== null) {
                if (!$this->eventPrintersScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Event\EventPrinterQuery::create()
                        ->filterByPrimaryKeys($this->eventPrintersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventPrintersScheduledForDeletion = null;
                }
            }

            if ($this->collEventPrinters !== null) {
                foreach ($this->collEventPrinters as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventTablesScheduledForDeletion !== null) {
                if (!$this->eventTablesScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Event\EventTableQuery::create()
                        ->filterByPrimaryKeys($this->eventTablesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventTablesScheduledForDeletion = null;
                }
            }

            if ($this->collEventTables !== null) {
                foreach ($this->collEventTables as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventUsersScheduledForDeletion !== null) {
                if (!$this->eventUsersScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Event\EventUserQuery::create()
                        ->filterByPrimaryKeys($this->eventUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventUsersScheduledForDeletion = null;
                }
            }

            if ($this->collEventUsers !== null) {
                foreach ($this->collEventUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuExtrasScheduledForDeletion !== null) {
                if (!$this->menuExtrasScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Menu\MenuExtraQuery::create()
                        ->filterByPrimaryKeys($this->menuExtrasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuExtrasScheduledForDeletion = null;
                }
            }

            if ($this->collMenuExtras !== null) {
                foreach ($this->collMenuExtras as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuSizesScheduledForDeletion !== null) {
                if (!$this->menuSizesScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Menu\MenuSizeQuery::create()
                        ->filterByPrimaryKeys($this->menuSizesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuSizesScheduledForDeletion = null;
                }
            }

            if ($this->collMenuSizes !== null) {
                foreach ($this->collMenuSizes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuTypesScheduledForDeletion !== null) {
                if (!$this->menuTypesScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Menu\MenuTypeQuery::create()
                        ->filterByPrimaryKeys($this->menuTypesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuTypesScheduledForDeletion = null;
                }
            }

            if ($this->collMenuTypes !== null) {
                foreach ($this->collMenuTypes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->invoiceWarningTypesScheduledForDeletion !== null) {
                if (!$this->invoiceWarningTypesScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Invoice\InvoiceWarningTypeQuery::create()
                        ->filterByPrimaryKeys($this->invoiceWarningTypesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoiceWarningTypesScheduledForDeletion = null;
                }
            }

            if ($this->collInvoiceWarningTypes !== null) {
                foreach ($this->collInvoiceWarningTypes as $referrerFK) {
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

        $this->modifiedColumns[EventTableMap::COL_EVENTID] = true;
        if (null !== $this->eventid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTableMap::COL_EVENTID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(EventTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }

        $sql = sprintf(
            'INSERT INTO event (%s) VALUES (%s)',
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
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['Event'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Event'][$this->hashCode()] = true;
        $keys = EventTableMap::getFieldNames($keyType);
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
            if (null !== $this->collCoupons) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'coupons';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'coupons';
                        break;
                    default:
                        $key = 'Coupons';
                }
        
                $result[$key] = $this->collCoupons->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventBankinformations) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventBankinformations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_bankinformations';
                        break;
                    default:
                        $key = 'EventBankinformations';
                }
        
                $result[$key] = $this->collEventBankinformations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventContacts) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventContacts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_contacts';
                        break;
                    default:
                        $key = 'EventContacts';
                }
        
                $result[$key] = $this->collEventContacts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDistributionPlaces) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionPlaces';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distribution_places';
                        break;
                    default:
                        $key = 'DistributionPlaces';
                }
        
                $result[$key] = $this->collDistributionPlaces->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventPrinters) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventPrinters';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_printers';
                        break;
                    default:
                        $key = 'EventPrinters';
                }
        
                $result[$key] = $this->collEventPrinters->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventTables) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventTables';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_tables';
                        break;
                    default:
                        $key = 'EventTables';
                }
        
                $result[$key] = $this->collEventTables->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventUsers) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_users';
                        break;
                    default:
                        $key = 'EventUsers';
                }
        
                $result[$key] = $this->collEventUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuExtras) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuExtras';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_extras';
                        break;
                    default:
                        $key = 'MenuExtras';
                }
        
                $result[$key] = $this->collMenuExtras->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuSizes) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuSizes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_sizes';
                        break;
                    default:
                        $key = 'MenuSizes';
                }
        
                $result[$key] = $this->collMenuSizes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuTypes) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuTypes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_types';
                        break;
                    default:
                        $key = 'MenuTypes';
                }
        
                $result[$key] = $this->collMenuTypes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInvoiceWarningTypes) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoiceWarningTypes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoice_warning_types';
                        break;
                    default:
                        $key = 'InvoiceWarningTypes';
                }
        
                $result[$key] = $this->collInvoiceWarningTypes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\ORM\Event\Event
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\ORM\Event\Event
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
        $keys = EventTableMap::getFieldNames($keyType);

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
     * @return $this|\API\Models\ORM\Event\Event The current object, for fluid interface
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
        $criteria = new Criteria(EventTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableMap::COL_EVENTID)) {
            $criteria->add(EventTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventTableMap::COL_NAME)) {
            $criteria->add(EventTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventTableMap::COL_DATE)) {
            $criteria->add(EventTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(EventTableMap::COL_ACTIVE)) {
            $criteria->add(EventTableMap::COL_ACTIVE, $this->active);
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
        $criteria = ChildEventQuery::create();
        $criteria->add(EventTableMap::COL_EVENTID, $this->eventid);

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
     * @param      object $copyObj An object of \API\Models\ORM\Event\Event (or compatible) type.
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

            foreach ($this->getCoupons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCoupon($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventBankinformations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventBankinformation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventContact($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDistributionPlaces() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlace($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventPrinters() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventPrinter($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventTables() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventTable($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuExtras() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuExtra($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuSizes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuSize($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuTypes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuType($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvoiceWarningTypes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceWarningType($relObj->copy($deepCopy));
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
     * @return \API\Models\ORM\Event\Event Clone of current object.
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
        if ('Coupon' == $relationName) {
            return $this->initCoupons();
        }
        if ('EventBankinformation' == $relationName) {
            return $this->initEventBankinformations();
        }
        if ('EventContact' == $relationName) {
            return $this->initEventContacts();
        }
        if ('DistributionPlace' == $relationName) {
            return $this->initDistributionPlaces();
        }
        if ('EventPrinter' == $relationName) {
            return $this->initEventPrinters();
        }
        if ('EventTable' == $relationName) {
            return $this->initEventTables();
        }
        if ('EventUser' == $relationName) {
            return $this->initEventUsers();
        }
        if ('MenuExtra' == $relationName) {
            return $this->initMenuExtras();
        }
        if ('MenuSize' == $relationName) {
            return $this->initMenuSizes();
        }
        if ('MenuType' == $relationName) {
            return $this->initMenuTypes();
        }
        if ('InvoiceWarningType' == $relationName) {
            return $this->initInvoiceWarningTypes();
        }
    }

    /**
     * Clears out the collCoupons collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCoupons()
     */
    public function clearCoupons()
    {
        $this->collCoupons = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCoupons collection loaded partially.
     */
    public function resetPartialCoupons($v = true)
    {
        $this->collCouponsPartial = $v;
    }

    /**
     * Initializes the collCoupons collection.
     *
     * By default this just sets the collCoupons collection to an empty array (like clearcollCoupons());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCoupons($overrideExisting = true)
    {
        if (null !== $this->collCoupons && !$overrideExisting) {
            return;
        }

        $collectionClassName = CouponTableMap::getTableMap()->getCollectionClassName();

        $this->collCoupons = new $collectionClassName;
        $this->collCoupons->setModel('\API\Models\ORM\Payment\Coupon');
    }

    /**
     * Gets an array of Coupon objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Coupon[] List of Coupon objects
     * @throws PropelException
     */
    public function getCoupons(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCouponsPartial && !$this->isNew();
        if (null === $this->collCoupons || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCoupons) {
                // return empty collection
                $this->initCoupons();
            } else {
                $collCoupons = CouponQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCouponsPartial && count($collCoupons)) {
                        $this->initCoupons(false);

                        foreach ($collCoupons as $obj) {
                            if (false == $this->collCoupons->contains($obj)) {
                                $this->collCoupons->append($obj);
                            }
                        }

                        $this->collCouponsPartial = true;
                    }

                    return $collCoupons;
                }

                if ($partial && $this->collCoupons) {
                    foreach ($this->collCoupons as $obj) {
                        if ($obj->isNew()) {
                            $collCoupons[] = $obj;
                        }
                    }
                }

                $this->collCoupons = $collCoupons;
                $this->collCouponsPartial = false;
            }
        }

        return $this->collCoupons;
    }

    /**
     * Sets a collection of Coupon objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $coupons A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setCoupons(Collection $coupons, ConnectionInterface $con = null)
    {
        /** @var Coupon[] $couponsToDelete */
        $couponsToDelete = $this->getCoupons(new Criteria(), $con)->diff($coupons);

        
        $this->couponsScheduledForDeletion = $couponsToDelete;

        foreach ($couponsToDelete as $couponRemoved) {
            $couponRemoved->setEvent(null);
        }

        $this->collCoupons = null;
        foreach ($coupons as $coupon) {
            $this->addCoupon($coupon);
        }

        $this->collCoupons = $coupons;
        $this->collCouponsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseCoupon objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseCoupon objects.
     * @throws PropelException
     */
    public function countCoupons(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCouponsPartial && !$this->isNew();
        if (null === $this->collCoupons || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCoupons) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCoupons());
            }

            $query = CouponQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collCoupons);
    }

    /**
     * Method called to associate a Coupon object to this object
     * through the Coupon foreign key attribute.
     *
     * @param  Coupon $l Coupon
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addCoupon(Coupon $l)
    {
        if ($this->collCoupons === null) {
            $this->initCoupons();
            $this->collCouponsPartial = true;
        }

        if (!$this->collCoupons->contains($l)) {
            $this->doAddCoupon($l);

            if ($this->couponsScheduledForDeletion and $this->couponsScheduledForDeletion->contains($l)) {
                $this->couponsScheduledForDeletion->remove($this->couponsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Coupon $coupon The Coupon object to add.
     */
    protected function doAddCoupon(Coupon $coupon)
    {
        $this->collCoupons[]= $coupon;
        $coupon->setEvent($this);
    }

    /**
     * @param  Coupon $coupon The Coupon object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeCoupon(Coupon $coupon)
    {
        if ($this->getCoupons()->contains($coupon)) {
            $pos = $this->collCoupons->search($coupon);
            $this->collCoupons->remove($pos);
            if (null === $this->couponsScheduledForDeletion) {
                $this->couponsScheduledForDeletion = clone $this->collCoupons;
                $this->couponsScheduledForDeletion->clear();
            }
            $this->couponsScheduledForDeletion[]= clone $coupon;
            $coupon->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Coupons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Coupon[] List of Coupon objects
     */
    public function getCouponsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = CouponQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getCoupons($query, $con);
    }

    /**
     * Clears out the collEventBankinformations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventBankinformations()
     */
    public function clearEventBankinformations()
    {
        $this->collEventBankinformations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventBankinformations collection loaded partially.
     */
    public function resetPartialEventBankinformations($v = true)
    {
        $this->collEventBankinformationsPartial = $v;
    }

    /**
     * Initializes the collEventBankinformations collection.
     *
     * By default this just sets the collEventBankinformations collection to an empty array (like clearcollEventBankinformations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventBankinformations($overrideExisting = true)
    {
        if (null !== $this->collEventBankinformations && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventBankinformationTableMap::getTableMap()->getCollectionClassName();

        $this->collEventBankinformations = new $collectionClassName;
        $this->collEventBankinformations->setModel('\API\Models\ORM\Event\EventBankinformation');
    }

    /**
     * Gets an array of ChildEventBankinformation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventBankinformation[] List of ChildEventBankinformation objects
     * @throws PropelException
     */
    public function getEventBankinformations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventBankinformationsPartial && !$this->isNew();
        if (null === $this->collEventBankinformations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventBankinformations) {
                // return empty collection
                $this->initEventBankinformations();
            } else {
                $collEventBankinformations = ChildEventBankinformationQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventBankinformationsPartial && count($collEventBankinformations)) {
                        $this->initEventBankinformations(false);

                        foreach ($collEventBankinformations as $obj) {
                            if (false == $this->collEventBankinformations->contains($obj)) {
                                $this->collEventBankinformations->append($obj);
                            }
                        }

                        $this->collEventBankinformationsPartial = true;
                    }

                    return $collEventBankinformations;
                }

                if ($partial && $this->collEventBankinformations) {
                    foreach ($this->collEventBankinformations as $obj) {
                        if ($obj->isNew()) {
                            $collEventBankinformations[] = $obj;
                        }
                    }
                }

                $this->collEventBankinformations = $collEventBankinformations;
                $this->collEventBankinformationsPartial = false;
            }
        }

        return $this->collEventBankinformations;
    }

    /**
     * Sets a collection of ChildEventBankinformation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventBankinformations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventBankinformations(Collection $eventBankinformations, ConnectionInterface $con = null)
    {
        /** @var ChildEventBankinformation[] $eventBankinformationsToDelete */
        $eventBankinformationsToDelete = $this->getEventBankinformations(new Criteria(), $con)->diff($eventBankinformations);

        
        $this->eventBankinformationsScheduledForDeletion = $eventBankinformationsToDelete;

        foreach ($eventBankinformationsToDelete as $eventBankinformationRemoved) {
            $eventBankinformationRemoved->setEvent(null);
        }

        $this->collEventBankinformations = null;
        foreach ($eventBankinformations as $eventBankinformation) {
            $this->addEventBankinformation($eventBankinformation);
        }

        $this->collEventBankinformations = $eventBankinformations;
        $this->collEventBankinformationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventBankinformation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventBankinformation objects.
     * @throws PropelException
     */
    public function countEventBankinformations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventBankinformationsPartial && !$this->isNew();
        if (null === $this->collEventBankinformations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventBankinformations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventBankinformations());
            }

            $query = ChildEventBankinformationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventBankinformations);
    }

    /**
     * Method called to associate a ChildEventBankinformation object to this object
     * through the ChildEventBankinformation foreign key attribute.
     *
     * @param  ChildEventBankinformation $l ChildEventBankinformation
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addEventBankinformation(ChildEventBankinformation $l)
    {
        if ($this->collEventBankinformations === null) {
            $this->initEventBankinformations();
            $this->collEventBankinformationsPartial = true;
        }

        if (!$this->collEventBankinformations->contains($l)) {
            $this->doAddEventBankinformation($l);

            if ($this->eventBankinformationsScheduledForDeletion and $this->eventBankinformationsScheduledForDeletion->contains($l)) {
                $this->eventBankinformationsScheduledForDeletion->remove($this->eventBankinformationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventBankinformation $eventBankinformation The ChildEventBankinformation object to add.
     */
    protected function doAddEventBankinformation(ChildEventBankinformation $eventBankinformation)
    {
        $this->collEventBankinformations[]= $eventBankinformation;
        $eventBankinformation->setEvent($this);
    }

    /**
     * @param  ChildEventBankinformation $eventBankinformation The ChildEventBankinformation object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventBankinformation(ChildEventBankinformation $eventBankinformation)
    {
        if ($this->getEventBankinformations()->contains($eventBankinformation)) {
            $pos = $this->collEventBankinformations->search($eventBankinformation);
            $this->collEventBankinformations->remove($pos);
            if (null === $this->eventBankinformationsScheduledForDeletion) {
                $this->eventBankinformationsScheduledForDeletion = clone $this->collEventBankinformations;
                $this->eventBankinformationsScheduledForDeletion->clear();
            }
            $this->eventBankinformationsScheduledForDeletion[]= clone $eventBankinformation;
            $eventBankinformation->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventContacts()
     */
    public function clearEventContacts()
    {
        $this->collEventContacts = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventContacts collection loaded partially.
     */
    public function resetPartialEventContacts($v = true)
    {
        $this->collEventContactsPartial = $v;
    }

    /**
     * Initializes the collEventContacts collection.
     *
     * By default this just sets the collEventContacts collection to an empty array (like clearcollEventContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventContacts($overrideExisting = true)
    {
        if (null !== $this->collEventContacts && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventContactTableMap::getTableMap()->getCollectionClassName();

        $this->collEventContacts = new $collectionClassName;
        $this->collEventContacts->setModel('\API\Models\ORM\Event\EventContact');
    }

    /**
     * Gets an array of ChildEventContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventContact[] List of ChildEventContact objects
     * @throws PropelException
     */
    public function getEventContacts(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventContactsPartial && !$this->isNew();
        if (null === $this->collEventContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventContacts) {
                // return empty collection
                $this->initEventContacts();
            } else {
                $collEventContacts = ChildEventContactQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventContactsPartial && count($collEventContacts)) {
                        $this->initEventContacts(false);

                        foreach ($collEventContacts as $obj) {
                            if (false == $this->collEventContacts->contains($obj)) {
                                $this->collEventContacts->append($obj);
                            }
                        }

                        $this->collEventContactsPartial = true;
                    }

                    return $collEventContacts;
                }

                if ($partial && $this->collEventContacts) {
                    foreach ($this->collEventContacts as $obj) {
                        if ($obj->isNew()) {
                            $collEventContacts[] = $obj;
                        }
                    }
                }

                $this->collEventContacts = $collEventContacts;
                $this->collEventContactsPartial = false;
            }
        }

        return $this->collEventContacts;
    }

    /**
     * Sets a collection of ChildEventContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventContacts A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventContacts(Collection $eventContacts, ConnectionInterface $con = null)
    {
        /** @var ChildEventContact[] $eventContactsToDelete */
        $eventContactsToDelete = $this->getEventContacts(new Criteria(), $con)->diff($eventContacts);

        
        $this->eventContactsScheduledForDeletion = $eventContactsToDelete;

        foreach ($eventContactsToDelete as $eventContactRemoved) {
            $eventContactRemoved->setEvent(null);
        }

        $this->collEventContacts = null;
        foreach ($eventContacts as $eventContact) {
            $this->addEventContact($eventContact);
        }

        $this->collEventContacts = $eventContacts;
        $this->collEventContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventContact objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventContact objects.
     * @throws PropelException
     */
    public function countEventContacts(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventContactsPartial && !$this->isNew();
        if (null === $this->collEventContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventContacts());
            }

            $query = ChildEventContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventContacts);
    }

    /**
     * Method called to associate a ChildEventContact object to this object
     * through the ChildEventContact foreign key attribute.
     *
     * @param  ChildEventContact $l ChildEventContact
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addEventContact(ChildEventContact $l)
    {
        if ($this->collEventContacts === null) {
            $this->initEventContacts();
            $this->collEventContactsPartial = true;
        }

        if (!$this->collEventContacts->contains($l)) {
            $this->doAddEventContact($l);

            if ($this->eventContactsScheduledForDeletion and $this->eventContactsScheduledForDeletion->contains($l)) {
                $this->eventContactsScheduledForDeletion->remove($this->eventContactsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventContact $eventContact The ChildEventContact object to add.
     */
    protected function doAddEventContact(ChildEventContact $eventContact)
    {
        $this->collEventContacts[]= $eventContact;
        $eventContact->setEvent($this);
    }

    /**
     * @param  ChildEventContact $eventContact The ChildEventContact object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventContact(ChildEventContact $eventContact)
    {
        if ($this->getEventContacts()->contains($eventContact)) {
            $pos = $this->collEventContacts->search($eventContact);
            $this->collEventContacts->remove($pos);
            if (null === $this->eventContactsScheduledForDeletion) {
                $this->eventContactsScheduledForDeletion = clone $this->collEventContacts;
                $this->eventContactsScheduledForDeletion->clear();
            }
            $this->eventContactsScheduledForDeletion[]= clone $eventContact;
            $eventContact->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collDistributionPlaces collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionPlaces()
     */
    public function clearDistributionPlaces()
    {
        $this->collDistributionPlaces = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionPlaces collection loaded partially.
     */
    public function resetPartialDistributionPlaces($v = true)
    {
        $this->collDistributionPlacesPartial = $v;
    }

    /**
     * Initializes the collDistributionPlaces collection.
     *
     * By default this just sets the collDistributionPlaces collection to an empty array (like clearcollDistributionPlaces());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionPlaces($overrideExisting = true)
    {
        if (null !== $this->collDistributionPlaces && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionPlaceTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionPlaces = new $collectionClassName;
        $this->collDistributionPlaces->setModel('\API\Models\ORM\DistributionPlace\DistributionPlace');
    }

    /**
     * Gets an array of DistributionPlace objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionPlace[] List of DistributionPlace objects
     * @throws PropelException
     */
    public function getDistributionPlaces(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlacesPartial && !$this->isNew();
        if (null === $this->collDistributionPlaces || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaces) {
                // return empty collection
                $this->initDistributionPlaces();
            } else {
                $collDistributionPlaces = DistributionPlaceQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionPlacesPartial && count($collDistributionPlaces)) {
                        $this->initDistributionPlaces(false);

                        foreach ($collDistributionPlaces as $obj) {
                            if (false == $this->collDistributionPlaces->contains($obj)) {
                                $this->collDistributionPlaces->append($obj);
                            }
                        }

                        $this->collDistributionPlacesPartial = true;
                    }

                    return $collDistributionPlaces;
                }

                if ($partial && $this->collDistributionPlaces) {
                    foreach ($this->collDistributionPlaces as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionPlaces[] = $obj;
                        }
                    }
                }

                $this->collDistributionPlaces = $collDistributionPlaces;
                $this->collDistributionPlacesPartial = false;
            }
        }

        return $this->collDistributionPlaces;
    }

    /**
     * Sets a collection of DistributionPlace objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaces A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setDistributionPlaces(Collection $distributionPlaces, ConnectionInterface $con = null)
    {
        /** @var DistributionPlace[] $distributionPlacesToDelete */
        $distributionPlacesToDelete = $this->getDistributionPlaces(new Criteria(), $con)->diff($distributionPlaces);

        
        $this->distributionPlacesScheduledForDeletion = $distributionPlacesToDelete;

        foreach ($distributionPlacesToDelete as $distributionPlaceRemoved) {
            $distributionPlaceRemoved->setEvent(null);
        }

        $this->collDistributionPlaces = null;
        foreach ($distributionPlaces as $distributionPlace) {
            $this->addDistributionPlace($distributionPlace);
        }

        $this->collDistributionPlaces = $distributionPlaces;
        $this->collDistributionPlacesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionPlace objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionPlace objects.
     * @throws PropelException
     */
    public function countDistributionPlaces(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlacesPartial && !$this->isNew();
        if (null === $this->collDistributionPlaces || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaces) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionPlaces());
            }

            $query = DistributionPlaceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collDistributionPlaces);
    }

    /**
     * Method called to associate a DistributionPlace object to this object
     * through the DistributionPlace foreign key attribute.
     *
     * @param  DistributionPlace $l DistributionPlace
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addDistributionPlace(DistributionPlace $l)
    {
        if ($this->collDistributionPlaces === null) {
            $this->initDistributionPlaces();
            $this->collDistributionPlacesPartial = true;
        }

        if (!$this->collDistributionPlaces->contains($l)) {
            $this->doAddDistributionPlace($l);

            if ($this->distributionPlacesScheduledForDeletion and $this->distributionPlacesScheduledForDeletion->contains($l)) {
                $this->distributionPlacesScheduledForDeletion->remove($this->distributionPlacesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionPlace $distributionPlace The DistributionPlace object to add.
     */
    protected function doAddDistributionPlace(DistributionPlace $distributionPlace)
    {
        $this->collDistributionPlaces[]= $distributionPlace;
        $distributionPlace->setEvent($this);
    }

    /**
     * @param  DistributionPlace $distributionPlace The DistributionPlace object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeDistributionPlace(DistributionPlace $distributionPlace)
    {
        if ($this->getDistributionPlaces()->contains($distributionPlace)) {
            $pos = $this->collDistributionPlaces->search($distributionPlace);
            $this->collDistributionPlaces->remove($pos);
            if (null === $this->distributionPlacesScheduledForDeletion) {
                $this->distributionPlacesScheduledForDeletion = clone $this->collDistributionPlaces;
                $this->distributionPlacesScheduledForDeletion->clear();
            }
            $this->distributionPlacesScheduledForDeletion[]= clone $distributionPlace;
            $distributionPlace->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventPrinters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventPrinters()
     */
    public function clearEventPrinters()
    {
        $this->collEventPrinters = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventPrinters collection loaded partially.
     */
    public function resetPartialEventPrinters($v = true)
    {
        $this->collEventPrintersPartial = $v;
    }

    /**
     * Initializes the collEventPrinters collection.
     *
     * By default this just sets the collEventPrinters collection to an empty array (like clearcollEventPrinters());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventPrinters($overrideExisting = true)
    {
        if (null !== $this->collEventPrinters && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventPrinterTableMap::getTableMap()->getCollectionClassName();

        $this->collEventPrinters = new $collectionClassName;
        $this->collEventPrinters->setModel('\API\Models\ORM\Event\EventPrinter');
    }

    /**
     * Gets an array of ChildEventPrinter objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventPrinter[] List of ChildEventPrinter objects
     * @throws PropelException
     */
    public function getEventPrinters(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventPrintersPartial && !$this->isNew();
        if (null === $this->collEventPrinters || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventPrinters) {
                // return empty collection
                $this->initEventPrinters();
            } else {
                $collEventPrinters = ChildEventPrinterQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventPrintersPartial && count($collEventPrinters)) {
                        $this->initEventPrinters(false);

                        foreach ($collEventPrinters as $obj) {
                            if (false == $this->collEventPrinters->contains($obj)) {
                                $this->collEventPrinters->append($obj);
                            }
                        }

                        $this->collEventPrintersPartial = true;
                    }

                    return $collEventPrinters;
                }

                if ($partial && $this->collEventPrinters) {
                    foreach ($this->collEventPrinters as $obj) {
                        if ($obj->isNew()) {
                            $collEventPrinters[] = $obj;
                        }
                    }
                }

                $this->collEventPrinters = $collEventPrinters;
                $this->collEventPrintersPartial = false;
            }
        }

        return $this->collEventPrinters;
    }

    /**
     * Sets a collection of ChildEventPrinter objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventPrinters A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventPrinters(Collection $eventPrinters, ConnectionInterface $con = null)
    {
        /** @var ChildEventPrinter[] $eventPrintersToDelete */
        $eventPrintersToDelete = $this->getEventPrinters(new Criteria(), $con)->diff($eventPrinters);

        
        $this->eventPrintersScheduledForDeletion = $eventPrintersToDelete;

        foreach ($eventPrintersToDelete as $eventPrinterRemoved) {
            $eventPrinterRemoved->setEvent(null);
        }

        $this->collEventPrinters = null;
        foreach ($eventPrinters as $eventPrinter) {
            $this->addEventPrinter($eventPrinter);
        }

        $this->collEventPrinters = $eventPrinters;
        $this->collEventPrintersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventPrinter objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventPrinter objects.
     * @throws PropelException
     */
    public function countEventPrinters(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventPrintersPartial && !$this->isNew();
        if (null === $this->collEventPrinters || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventPrinters) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventPrinters());
            }

            $query = ChildEventPrinterQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventPrinters);
    }

    /**
     * Method called to associate a ChildEventPrinter object to this object
     * through the ChildEventPrinter foreign key attribute.
     *
     * @param  ChildEventPrinter $l ChildEventPrinter
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addEventPrinter(ChildEventPrinter $l)
    {
        if ($this->collEventPrinters === null) {
            $this->initEventPrinters();
            $this->collEventPrintersPartial = true;
        }

        if (!$this->collEventPrinters->contains($l)) {
            $this->doAddEventPrinter($l);

            if ($this->eventPrintersScheduledForDeletion and $this->eventPrintersScheduledForDeletion->contains($l)) {
                $this->eventPrintersScheduledForDeletion->remove($this->eventPrintersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventPrinter $eventPrinter The ChildEventPrinter object to add.
     */
    protected function doAddEventPrinter(ChildEventPrinter $eventPrinter)
    {
        $this->collEventPrinters[]= $eventPrinter;
        $eventPrinter->setEvent($this);
    }

    /**
     * @param  ChildEventPrinter $eventPrinter The ChildEventPrinter object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventPrinter(ChildEventPrinter $eventPrinter)
    {
        if ($this->getEventPrinters()->contains($eventPrinter)) {
            $pos = $this->collEventPrinters->search($eventPrinter);
            $this->collEventPrinters->remove($pos);
            if (null === $this->eventPrintersScheduledForDeletion) {
                $this->eventPrintersScheduledForDeletion = clone $this->collEventPrinters;
                $this->eventPrintersScheduledForDeletion->clear();
            }
            $this->eventPrintersScheduledForDeletion[]= clone $eventPrinter;
            $eventPrinter->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventTables collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventTables()
     */
    public function clearEventTables()
    {
        $this->collEventTables = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventTables collection loaded partially.
     */
    public function resetPartialEventTables($v = true)
    {
        $this->collEventTablesPartial = $v;
    }

    /**
     * Initializes the collEventTables collection.
     *
     * By default this just sets the collEventTables collection to an empty array (like clearcollEventTables());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventTables($overrideExisting = true)
    {
        if (null !== $this->collEventTables && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventTableTableMap::getTableMap()->getCollectionClassName();

        $this->collEventTables = new $collectionClassName;
        $this->collEventTables->setModel('\API\Models\ORM\Event\EventTable');
    }

    /**
     * Gets an array of ChildEventTable objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventTable[] List of ChildEventTable objects
     * @throws PropelException
     */
    public function getEventTables(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventTablesPartial && !$this->isNew();
        if (null === $this->collEventTables || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventTables) {
                // return empty collection
                $this->initEventTables();
            } else {
                $collEventTables = ChildEventTableQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventTablesPartial && count($collEventTables)) {
                        $this->initEventTables(false);

                        foreach ($collEventTables as $obj) {
                            if (false == $this->collEventTables->contains($obj)) {
                                $this->collEventTables->append($obj);
                            }
                        }

                        $this->collEventTablesPartial = true;
                    }

                    return $collEventTables;
                }

                if ($partial && $this->collEventTables) {
                    foreach ($this->collEventTables as $obj) {
                        if ($obj->isNew()) {
                            $collEventTables[] = $obj;
                        }
                    }
                }

                $this->collEventTables = $collEventTables;
                $this->collEventTablesPartial = false;
            }
        }

        return $this->collEventTables;
    }

    /**
     * Sets a collection of ChildEventTable objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventTables A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventTables(Collection $eventTables, ConnectionInterface $con = null)
    {
        /** @var ChildEventTable[] $eventTablesToDelete */
        $eventTablesToDelete = $this->getEventTables(new Criteria(), $con)->diff($eventTables);

        
        $this->eventTablesScheduledForDeletion = $eventTablesToDelete;

        foreach ($eventTablesToDelete as $eventTableRemoved) {
            $eventTableRemoved->setEvent(null);
        }

        $this->collEventTables = null;
        foreach ($eventTables as $eventTable) {
            $this->addEventTable($eventTable);
        }

        $this->collEventTables = $eventTables;
        $this->collEventTablesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventTable objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventTable objects.
     * @throws PropelException
     */
    public function countEventTables(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventTablesPartial && !$this->isNew();
        if (null === $this->collEventTables || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventTables) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventTables());
            }

            $query = ChildEventTableQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventTables);
    }

    /**
     * Method called to associate a ChildEventTable object to this object
     * through the ChildEventTable foreign key attribute.
     *
     * @param  ChildEventTable $l ChildEventTable
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addEventTable(ChildEventTable $l)
    {
        if ($this->collEventTables === null) {
            $this->initEventTables();
            $this->collEventTablesPartial = true;
        }

        if (!$this->collEventTables->contains($l)) {
            $this->doAddEventTable($l);

            if ($this->eventTablesScheduledForDeletion and $this->eventTablesScheduledForDeletion->contains($l)) {
                $this->eventTablesScheduledForDeletion->remove($this->eventTablesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventTable $eventTable The ChildEventTable object to add.
     */
    protected function doAddEventTable(ChildEventTable $eventTable)
    {
        $this->collEventTables[]= $eventTable;
        $eventTable->setEvent($this);
    }

    /**
     * @param  ChildEventTable $eventTable The ChildEventTable object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventTable(ChildEventTable $eventTable)
    {
        if ($this->getEventTables()->contains($eventTable)) {
            $pos = $this->collEventTables->search($eventTable);
            $this->collEventTables->remove($pos);
            if (null === $this->eventTablesScheduledForDeletion) {
                $this->eventTablesScheduledForDeletion = clone $this->collEventTables;
                $this->eventTablesScheduledForDeletion->clear();
            }
            $this->eventTablesScheduledForDeletion[]= clone $eventTable;
            $eventTable->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collEventUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventUsers()
     */
    public function clearEventUsers()
    {
        $this->collEventUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventUsers collection loaded partially.
     */
    public function resetPartialEventUsers($v = true)
    {
        $this->collEventUsersPartial = $v;
    }

    /**
     * Initializes the collEventUsers collection.
     *
     * By default this just sets the collEventUsers collection to an empty array (like clearcollEventUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventUsers($overrideExisting = true)
    {
        if (null !== $this->collEventUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventUserTableMap::getTableMap()->getCollectionClassName();

        $this->collEventUsers = new $collectionClassName;
        $this->collEventUsers->setModel('\API\Models\ORM\Event\EventUser');
    }

    /**
     * Gets an array of ChildEventUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventUser[] List of ChildEventUser objects
     * @throws PropelException
     */
    public function getEventUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventUsersPartial && !$this->isNew();
        if (null === $this->collEventUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventUsers) {
                // return empty collection
                $this->initEventUsers();
            } else {
                $collEventUsers = ChildEventUserQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventUsersPartial && count($collEventUsers)) {
                        $this->initEventUsers(false);

                        foreach ($collEventUsers as $obj) {
                            if (false == $this->collEventUsers->contains($obj)) {
                                $this->collEventUsers->append($obj);
                            }
                        }

                        $this->collEventUsersPartial = true;
                    }

                    return $collEventUsers;
                }

                if ($partial && $this->collEventUsers) {
                    foreach ($this->collEventUsers as $obj) {
                        if ($obj->isNew()) {
                            $collEventUsers[] = $obj;
                        }
                    }
                }

                $this->collEventUsers = $collEventUsers;
                $this->collEventUsersPartial = false;
            }
        }

        return $this->collEventUsers;
    }

    /**
     * Sets a collection of ChildEventUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventUsers(Collection $eventUsers, ConnectionInterface $con = null)
    {
        /** @var ChildEventUser[] $eventUsersToDelete */
        $eventUsersToDelete = $this->getEventUsers(new Criteria(), $con)->diff($eventUsers);

        
        $this->eventUsersScheduledForDeletion = $eventUsersToDelete;

        foreach ($eventUsersToDelete as $eventUserRemoved) {
            $eventUserRemoved->setEvent(null);
        }

        $this->collEventUsers = null;
        foreach ($eventUsers as $eventUser) {
            $this->addEventUser($eventUser);
        }

        $this->collEventUsers = $eventUsers;
        $this->collEventUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventUser objects.
     * @throws PropelException
     */
    public function countEventUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventUsersPartial && !$this->isNew();
        if (null === $this->collEventUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventUsers());
            }

            $query = ChildEventUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventUsers);
    }

    /**
     * Method called to associate a ChildEventUser object to this object
     * through the ChildEventUser foreign key attribute.
     *
     * @param  ChildEventUser $l ChildEventUser
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addEventUser(ChildEventUser $l)
    {
        if ($this->collEventUsers === null) {
            $this->initEventUsers();
            $this->collEventUsersPartial = true;
        }

        if (!$this->collEventUsers->contains($l)) {
            $this->doAddEventUser($l);

            if ($this->eventUsersScheduledForDeletion and $this->eventUsersScheduledForDeletion->contains($l)) {
                $this->eventUsersScheduledForDeletion->remove($this->eventUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventUser $eventUser The ChildEventUser object to add.
     */
    protected function doAddEventUser(ChildEventUser $eventUser)
    {
        $this->collEventUsers[]= $eventUser;
        $eventUser->setEvent($this);
    }

    /**
     * @param  ChildEventUser $eventUser The ChildEventUser object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventUser(ChildEventUser $eventUser)
    {
        if ($this->getEventUsers()->contains($eventUser)) {
            $pos = $this->collEventUsers->search($eventUser);
            $this->collEventUsers->remove($pos);
            if (null === $this->eventUsersScheduledForDeletion) {
                $this->eventUsersScheduledForDeletion = clone $this->collEventUsers;
                $this->eventUsersScheduledForDeletion->clear();
            }
            $this->eventUsersScheduledForDeletion[]= clone $eventUser;
            $eventUser->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventUser[] List of ChildEventUser objects
     */
    public function getEventUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getEventUsers($query, $con);
    }

    /**
     * Clears out the collMenuExtras collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuExtras()
     */
    public function clearMenuExtras()
    {
        $this->collMenuExtras = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuExtras collection loaded partially.
     */
    public function resetPartialMenuExtras($v = true)
    {
        $this->collMenuExtrasPartial = $v;
    }

    /**
     * Initializes the collMenuExtras collection.
     *
     * By default this just sets the collMenuExtras collection to an empty array (like clearcollMenuExtras());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuExtras($overrideExisting = true)
    {
        if (null !== $this->collMenuExtras && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuExtraTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuExtras = new $collectionClassName;
        $this->collMenuExtras->setModel('\API\Models\ORM\Menu\MenuExtra');
    }

    /**
     * Gets an array of MenuExtra objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MenuExtra[] List of MenuExtra objects
     * @throws PropelException
     */
    public function getMenuExtras(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuExtrasPartial && !$this->isNew();
        if (null === $this->collMenuExtras || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuExtras) {
                // return empty collection
                $this->initMenuExtras();
            } else {
                $collMenuExtras = MenuExtraQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuExtrasPartial && count($collMenuExtras)) {
                        $this->initMenuExtras(false);

                        foreach ($collMenuExtras as $obj) {
                            if (false == $this->collMenuExtras->contains($obj)) {
                                $this->collMenuExtras->append($obj);
                            }
                        }

                        $this->collMenuExtrasPartial = true;
                    }

                    return $collMenuExtras;
                }

                if ($partial && $this->collMenuExtras) {
                    foreach ($this->collMenuExtras as $obj) {
                        if ($obj->isNew()) {
                            $collMenuExtras[] = $obj;
                        }
                    }
                }

                $this->collMenuExtras = $collMenuExtras;
                $this->collMenuExtrasPartial = false;
            }
        }

        return $this->collMenuExtras;
    }

    /**
     * Sets a collection of MenuExtra objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuExtras A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setMenuExtras(Collection $menuExtras, ConnectionInterface $con = null)
    {
        /** @var MenuExtra[] $menuExtrasToDelete */
        $menuExtrasToDelete = $this->getMenuExtras(new Criteria(), $con)->diff($menuExtras);

        
        $this->menuExtrasScheduledForDeletion = $menuExtrasToDelete;

        foreach ($menuExtrasToDelete as $menuExtraRemoved) {
            $menuExtraRemoved->setEvent(null);
        }

        $this->collMenuExtras = null;
        foreach ($menuExtras as $menuExtra) {
            $this->addMenuExtra($menuExtra);
        }

        $this->collMenuExtras = $menuExtras;
        $this->collMenuExtrasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMenuExtra objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMenuExtra objects.
     * @throws PropelException
     */
    public function countMenuExtras(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuExtrasPartial && !$this->isNew();
        if (null === $this->collMenuExtras || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuExtras) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuExtras());
            }

            $query = MenuExtraQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collMenuExtras);
    }

    /**
     * Method called to associate a MenuExtra object to this object
     * through the MenuExtra foreign key attribute.
     *
     * @param  MenuExtra $l MenuExtra
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addMenuExtra(MenuExtra $l)
    {
        if ($this->collMenuExtras === null) {
            $this->initMenuExtras();
            $this->collMenuExtrasPartial = true;
        }

        if (!$this->collMenuExtras->contains($l)) {
            $this->doAddMenuExtra($l);

            if ($this->menuExtrasScheduledForDeletion and $this->menuExtrasScheduledForDeletion->contains($l)) {
                $this->menuExtrasScheduledForDeletion->remove($this->menuExtrasScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MenuExtra $menuExtra The MenuExtra object to add.
     */
    protected function doAddMenuExtra(MenuExtra $menuExtra)
    {
        $this->collMenuExtras[]= $menuExtra;
        $menuExtra->setEvent($this);
    }

    /**
     * @param  MenuExtra $menuExtra The MenuExtra object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeMenuExtra(MenuExtra $menuExtra)
    {
        if ($this->getMenuExtras()->contains($menuExtra)) {
            $pos = $this->collMenuExtras->search($menuExtra);
            $this->collMenuExtras->remove($pos);
            if (null === $this->menuExtrasScheduledForDeletion) {
                $this->menuExtrasScheduledForDeletion = clone $this->collMenuExtras;
                $this->menuExtrasScheduledForDeletion->clear();
            }
            $this->menuExtrasScheduledForDeletion[]= clone $menuExtra;
            $menuExtra->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related MenuExtras from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|MenuExtra[] List of MenuExtra objects
     */
    public function getMenuExtrasJoinAvailability(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = MenuExtraQuery::create(null, $criteria);
        $query->joinWith('Availability', $joinBehavior);

        return $this->getMenuExtras($query, $con);
    }

    /**
     * Clears out the collMenuSizes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuSizes()
     */
    public function clearMenuSizes()
    {
        $this->collMenuSizes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuSizes collection loaded partially.
     */
    public function resetPartialMenuSizes($v = true)
    {
        $this->collMenuSizesPartial = $v;
    }

    /**
     * Initializes the collMenuSizes collection.
     *
     * By default this just sets the collMenuSizes collection to an empty array (like clearcollMenuSizes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuSizes($overrideExisting = true)
    {
        if (null !== $this->collMenuSizes && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuSizeTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuSizes = new $collectionClassName;
        $this->collMenuSizes->setModel('\API\Models\ORM\Menu\MenuSize');
    }

    /**
     * Gets an array of MenuSize objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MenuSize[] List of MenuSize objects
     * @throws PropelException
     */
    public function getMenuSizes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuSizesPartial && !$this->isNew();
        if (null === $this->collMenuSizes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuSizes) {
                // return empty collection
                $this->initMenuSizes();
            } else {
                $collMenuSizes = MenuSizeQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuSizesPartial && count($collMenuSizes)) {
                        $this->initMenuSizes(false);

                        foreach ($collMenuSizes as $obj) {
                            if (false == $this->collMenuSizes->contains($obj)) {
                                $this->collMenuSizes->append($obj);
                            }
                        }

                        $this->collMenuSizesPartial = true;
                    }

                    return $collMenuSizes;
                }

                if ($partial && $this->collMenuSizes) {
                    foreach ($this->collMenuSizes as $obj) {
                        if ($obj->isNew()) {
                            $collMenuSizes[] = $obj;
                        }
                    }
                }

                $this->collMenuSizes = $collMenuSizes;
                $this->collMenuSizesPartial = false;
            }
        }

        return $this->collMenuSizes;
    }

    /**
     * Sets a collection of MenuSize objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuSizes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setMenuSizes(Collection $menuSizes, ConnectionInterface $con = null)
    {
        /** @var MenuSize[] $menuSizesToDelete */
        $menuSizesToDelete = $this->getMenuSizes(new Criteria(), $con)->diff($menuSizes);

        
        $this->menuSizesScheduledForDeletion = $menuSizesToDelete;

        foreach ($menuSizesToDelete as $menuSizeRemoved) {
            $menuSizeRemoved->setEvent(null);
        }

        $this->collMenuSizes = null;
        foreach ($menuSizes as $menuSize) {
            $this->addMenuSize($menuSize);
        }

        $this->collMenuSizes = $menuSizes;
        $this->collMenuSizesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMenuSize objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMenuSize objects.
     * @throws PropelException
     */
    public function countMenuSizes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuSizesPartial && !$this->isNew();
        if (null === $this->collMenuSizes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuSizes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuSizes());
            }

            $query = MenuSizeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collMenuSizes);
    }

    /**
     * Method called to associate a MenuSize object to this object
     * through the MenuSize foreign key attribute.
     *
     * @param  MenuSize $l MenuSize
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addMenuSize(MenuSize $l)
    {
        if ($this->collMenuSizes === null) {
            $this->initMenuSizes();
            $this->collMenuSizesPartial = true;
        }

        if (!$this->collMenuSizes->contains($l)) {
            $this->doAddMenuSize($l);

            if ($this->menuSizesScheduledForDeletion and $this->menuSizesScheduledForDeletion->contains($l)) {
                $this->menuSizesScheduledForDeletion->remove($this->menuSizesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MenuSize $menuSize The MenuSize object to add.
     */
    protected function doAddMenuSize(MenuSize $menuSize)
    {
        $this->collMenuSizes[]= $menuSize;
        $menuSize->setEvent($this);
    }

    /**
     * @param  MenuSize $menuSize The MenuSize object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeMenuSize(MenuSize $menuSize)
    {
        if ($this->getMenuSizes()->contains($menuSize)) {
            $pos = $this->collMenuSizes->search($menuSize);
            $this->collMenuSizes->remove($pos);
            if (null === $this->menuSizesScheduledForDeletion) {
                $this->menuSizesScheduledForDeletion = clone $this->collMenuSizes;
                $this->menuSizesScheduledForDeletion->clear();
            }
            $this->menuSizesScheduledForDeletion[]= clone $menuSize;
            $menuSize->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collMenuTypes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuTypes()
     */
    public function clearMenuTypes()
    {
        $this->collMenuTypes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuTypes collection loaded partially.
     */
    public function resetPartialMenuTypes($v = true)
    {
        $this->collMenuTypesPartial = $v;
    }

    /**
     * Initializes the collMenuTypes collection.
     *
     * By default this just sets the collMenuTypes collection to an empty array (like clearcollMenuTypes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuTypes($overrideExisting = true)
    {
        if (null !== $this->collMenuTypes && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuTypeTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuTypes = new $collectionClassName;
        $this->collMenuTypes->setModel('\API\Models\ORM\Menu\MenuType');
    }

    /**
     * Gets an array of MenuType objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|MenuType[] List of MenuType objects
     * @throws PropelException
     */
    public function getMenuTypes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuTypesPartial && !$this->isNew();
        if (null === $this->collMenuTypes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuTypes) {
                // return empty collection
                $this->initMenuTypes();
            } else {
                $collMenuTypes = MenuTypeQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuTypesPartial && count($collMenuTypes)) {
                        $this->initMenuTypes(false);

                        foreach ($collMenuTypes as $obj) {
                            if (false == $this->collMenuTypes->contains($obj)) {
                                $this->collMenuTypes->append($obj);
                            }
                        }

                        $this->collMenuTypesPartial = true;
                    }

                    return $collMenuTypes;
                }

                if ($partial && $this->collMenuTypes) {
                    foreach ($this->collMenuTypes as $obj) {
                        if ($obj->isNew()) {
                            $collMenuTypes[] = $obj;
                        }
                    }
                }

                $this->collMenuTypes = $collMenuTypes;
                $this->collMenuTypesPartial = false;
            }
        }

        return $this->collMenuTypes;
    }

    /**
     * Sets a collection of MenuType objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuTypes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setMenuTypes(Collection $menuTypes, ConnectionInterface $con = null)
    {
        /** @var MenuType[] $menuTypesToDelete */
        $menuTypesToDelete = $this->getMenuTypes(new Criteria(), $con)->diff($menuTypes);

        
        $this->menuTypesScheduledForDeletion = $menuTypesToDelete;

        foreach ($menuTypesToDelete as $menuTypeRemoved) {
            $menuTypeRemoved->setEvent(null);
        }

        $this->collMenuTypes = null;
        foreach ($menuTypes as $menuType) {
            $this->addMenuType($menuType);
        }

        $this->collMenuTypes = $menuTypes;
        $this->collMenuTypesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMenuType objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMenuType objects.
     * @throws PropelException
     */
    public function countMenuTypes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuTypesPartial && !$this->isNew();
        if (null === $this->collMenuTypes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuTypes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuTypes());
            }

            $query = MenuTypeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collMenuTypes);
    }

    /**
     * Method called to associate a MenuType object to this object
     * through the MenuType foreign key attribute.
     *
     * @param  MenuType $l MenuType
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addMenuType(MenuType $l)
    {
        if ($this->collMenuTypes === null) {
            $this->initMenuTypes();
            $this->collMenuTypesPartial = true;
        }

        if (!$this->collMenuTypes->contains($l)) {
            $this->doAddMenuType($l);

            if ($this->menuTypesScheduledForDeletion and $this->menuTypesScheduledForDeletion->contains($l)) {
                $this->menuTypesScheduledForDeletion->remove($this->menuTypesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param MenuType $menuType The MenuType object to add.
     */
    protected function doAddMenuType(MenuType $menuType)
    {
        $this->collMenuTypes[]= $menuType;
        $menuType->setEvent($this);
    }

    /**
     * @param  MenuType $menuType The MenuType object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeMenuType(MenuType $menuType)
    {
        if ($this->getMenuTypes()->contains($menuType)) {
            $pos = $this->collMenuTypes->search($menuType);
            $this->collMenuTypes->remove($pos);
            if (null === $this->menuTypesScheduledForDeletion) {
                $this->menuTypesScheduledForDeletion = clone $this->collMenuTypes;
                $this->menuTypesScheduledForDeletion->clear();
            }
            $this->menuTypesScheduledForDeletion[]= clone $menuType;
            $menuType->setEvent(null);
        }

        return $this;
    }

    /**
     * Clears out the collInvoiceWarningTypes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoiceWarningTypes()
     */
    public function clearInvoiceWarningTypes()
    {
        $this->collInvoiceWarningTypes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoiceWarningTypes collection loaded partially.
     */
    public function resetPartialInvoiceWarningTypes($v = true)
    {
        $this->collInvoiceWarningTypesPartial = $v;
    }

    /**
     * Initializes the collInvoiceWarningTypes collection.
     *
     * By default this just sets the collInvoiceWarningTypes collection to an empty array (like clearcollInvoiceWarningTypes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoiceWarningTypes($overrideExisting = true)
    {
        if (null !== $this->collInvoiceWarningTypes && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceWarningTypeTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoiceWarningTypes = new $collectionClassName;
        $this->collInvoiceWarningTypes->setModel('\API\Models\ORM\Invoice\InvoiceWarningType');
    }

    /**
     * Gets an array of InvoiceWarningType objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|InvoiceWarningType[] List of InvoiceWarningType objects
     * @throws PropelException
     */
    public function getInvoiceWarningTypes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoiceWarningTypesPartial && !$this->isNew();
        if (null === $this->collInvoiceWarningTypes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoiceWarningTypes) {
                // return empty collection
                $this->initInvoiceWarningTypes();
            } else {
                $collInvoiceWarningTypes = InvoiceWarningTypeQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoiceWarningTypesPartial && count($collInvoiceWarningTypes)) {
                        $this->initInvoiceWarningTypes(false);

                        foreach ($collInvoiceWarningTypes as $obj) {
                            if (false == $this->collInvoiceWarningTypes->contains($obj)) {
                                $this->collInvoiceWarningTypes->append($obj);
                            }
                        }

                        $this->collInvoiceWarningTypesPartial = true;
                    }

                    return $collInvoiceWarningTypes;
                }

                if ($partial && $this->collInvoiceWarningTypes) {
                    foreach ($this->collInvoiceWarningTypes as $obj) {
                        if ($obj->isNew()) {
                            $collInvoiceWarningTypes[] = $obj;
                        }
                    }
                }

                $this->collInvoiceWarningTypes = $collInvoiceWarningTypes;
                $this->collInvoiceWarningTypesPartial = false;
            }
        }

        return $this->collInvoiceWarningTypes;
    }

    /**
     * Sets a collection of InvoiceWarningType objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoiceWarningTypes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setInvoiceWarningTypes(Collection $invoiceWarningTypes, ConnectionInterface $con = null)
    {
        /** @var InvoiceWarningType[] $invoiceWarningTypesToDelete */
        $invoiceWarningTypesToDelete = $this->getInvoiceWarningTypes(new Criteria(), $con)->diff($invoiceWarningTypes);

        
        $this->invoiceWarningTypesScheduledForDeletion = $invoiceWarningTypesToDelete;

        foreach ($invoiceWarningTypesToDelete as $invoiceWarningTypeRemoved) {
            $invoiceWarningTypeRemoved->setEvent(null);
        }

        $this->collInvoiceWarningTypes = null;
        foreach ($invoiceWarningTypes as $invoiceWarningType) {
            $this->addInvoiceWarningType($invoiceWarningType);
        }

        $this->collInvoiceWarningTypes = $invoiceWarningTypes;
        $this->collInvoiceWarningTypesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseInvoiceWarningType objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseInvoiceWarningType objects.
     * @throws PropelException
     */
    public function countInvoiceWarningTypes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoiceWarningTypesPartial && !$this->isNew();
        if (null === $this->collInvoiceWarningTypes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoiceWarningTypes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoiceWarningTypes());
            }

            $query = InvoiceWarningTypeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collInvoiceWarningTypes);
    }

    /**
     * Method called to associate a InvoiceWarningType object to this object
     * through the InvoiceWarningType foreign key attribute.
     *
     * @param  InvoiceWarningType $l InvoiceWarningType
     * @return $this|\API\Models\ORM\Event\Event The current object (for fluent API support)
     */
    public function addInvoiceWarningType(InvoiceWarningType $l)
    {
        if ($this->collInvoiceWarningTypes === null) {
            $this->initInvoiceWarningTypes();
            $this->collInvoiceWarningTypesPartial = true;
        }

        if (!$this->collInvoiceWarningTypes->contains($l)) {
            $this->doAddInvoiceWarningType($l);

            if ($this->invoiceWarningTypesScheduledForDeletion and $this->invoiceWarningTypesScheduledForDeletion->contains($l)) {
                $this->invoiceWarningTypesScheduledForDeletion->remove($this->invoiceWarningTypesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param InvoiceWarningType $invoiceWarningType The InvoiceWarningType object to add.
     */
    protected function doAddInvoiceWarningType(InvoiceWarningType $invoiceWarningType)
    {
        $this->collInvoiceWarningTypes[]= $invoiceWarningType;
        $invoiceWarningType->setEvent($this);
    }

    /**
     * @param  InvoiceWarningType $invoiceWarningType The InvoiceWarningType object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeInvoiceWarningType(InvoiceWarningType $invoiceWarningType)
    {
        if ($this->getInvoiceWarningTypes()->contains($invoiceWarningType)) {
            $pos = $this->collInvoiceWarningTypes->search($invoiceWarningType);
            $this->collInvoiceWarningTypes->remove($pos);
            if (null === $this->invoiceWarningTypesScheduledForDeletion) {
                $this->invoiceWarningTypesScheduledForDeletion = clone $this->collInvoiceWarningTypes;
                $this->invoiceWarningTypesScheduledForDeletion->clear();
            }
            $this->invoiceWarningTypesScheduledForDeletion[]= clone $invoiceWarningType;
            $invoiceWarningType->setEvent(null);
        }

        return $this;
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
            if ($this->collCoupons) {
                foreach ($this->collCoupons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventBankinformations) {
                foreach ($this->collEventBankinformations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventContacts) {
                foreach ($this->collEventContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDistributionPlaces) {
                foreach ($this->collDistributionPlaces as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventPrinters) {
                foreach ($this->collEventPrinters as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventTables) {
                foreach ($this->collEventTables as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventUsers) {
                foreach ($this->collEventUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuExtras) {
                foreach ($this->collMenuExtras as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuSizes) {
                foreach ($this->collMenuSizes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuTypes) {
                foreach ($this->collMenuTypes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvoiceWarningTypes) {
                foreach ($this->collInvoiceWarningTypes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCoupons = null;
        $this->collEventBankinformations = null;
        $this->collEventContacts = null;
        $this->collDistributionPlaces = null;
        $this->collEventPrinters = null;
        $this->collEventTables = null;
        $this->collEventUsers = null;
        $this->collMenuExtras = null;
        $this->collMenuSizes = null;
        $this->collMenuTypes = null;
        $this->collInvoiceWarningTypes = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableMap::DEFAULT_STRING_FORMAT);
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
