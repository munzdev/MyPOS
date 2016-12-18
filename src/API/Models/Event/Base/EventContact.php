<?php

namespace API\Models\Event\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event as ChildEvent;
use API\Models\Event\EventContact as ChildEventContact;
use API\Models\Event\EventContactQuery as ChildEventContactQuery;
use API\Models\Event\EventQuery as ChildEventQuery;
use API\Models\Event\Map\EventContactTableMap;
use API\Models\Invoice\Invoice;
use API\Models\Invoice\InvoiceQuery;
use API\Models\Invoice\Base\Invoice as BaseInvoice;
use API\Models\Invoice\Map\InvoiceTableMap;
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
 * Base class that represents a row from the 'event_contact' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Event.Base
 */
abstract class EventContact implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Event\\Map\\EventContactTableMap';


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
     * The value for the event_contactid field.
     *
     * @var        int
     */
    protected $event_contactid;

    /**
     * The value for the eventid field.
     *
     * @var        int
     */
    protected $eventid;

    /**
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the contact_person field.
     *
     * @var        string
     */
    protected $contact_person;

    /**
     * The value for the address field.
     *
     * @var        string
     */
    protected $address;

    /**
     * The value for the address2 field.
     *
     * @var        string
     */
    protected $address2;

    /**
     * The value for the city field.
     *
     * @var        string
     */
    protected $city;

    /**
     * The value for the zip field.
     *
     * @var        string
     */
    protected $zip;

    /**
     * The value for the tax_identification_nr field.
     *
     * @var        string
     */
    protected $tax_identification_nr;

    /**
     * The value for the telephon field.
     *
     * @var        string
     */
    protected $telephon;

    /**
     * The value for the fax field.
     *
     * @var        string
     */
    protected $fax;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the active field.
     *
     * @var        boolean
     */
    protected $active;

    /**
     * The value for the default field.
     *
     * @var        boolean
     */
    protected $default;

    /**
     * @var        ChildEvent
     */
    protected $aEvent;

    /**
     * @var        ObjectCollection|Invoice[] Collection to store aggregation of Invoice objects.
     */
    protected $collInvoicesRelatedByCustomerEventContactid;
    protected $collInvoicesRelatedByCustomerEventContactidPartial;

    /**
     * @var        ObjectCollection|Invoice[] Collection to store aggregation of Invoice objects.
     */
    protected $collInvoicesRelatedByEventContactid;
    protected $collInvoicesRelatedByEventContactidPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Invoice[]
     */
    protected $invoicesRelatedByCustomerEventContactidScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Invoice[]
     */
    protected $invoicesRelatedByEventContactidScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Event\Base\EventContact object.
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
     * Compares this with another <code>EventContact</code> instance.  If
     * <code>obj</code> is an instance of <code>EventContact</code>, delegates to
     * <code>equals(EventContact)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|EventContact The current object, for fluid interface
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
     * Get the [event_contactid] column value.
     *
     * @return int
     */
    public function getEventContactid()
    {
        return $this->event_contactid;
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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get the [contact_person] column value.
     *
     * @return string
     */
    public function getContactPerson()
    {
        return $this->contact_person;
    }

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the [address2] column value.
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [zip] column value.
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Get the [tax_identification_nr] column value.
     *
     * @return string
     */
    public function getTaxIdentificationNr()
    {
        return $this->tax_identification_nr;
    }

    /**
     * Get the [telephon] column value.
     *
     * @return string
     */
    public function getTelephon()
    {
        return $this->telephon;
    }

    /**
     * Get the [fax] column value.
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set the value of [event_contactid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setEventContactid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_contactid !== $v) {
            $this->event_contactid = $v;
            $this->modifiedColumns[EventContactTableMap::COL_EVENT_CONTACTID] = true;
        }

        return $this;
    } // setEventContactid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventContactTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvent !== null && $this->aEvent->getEventid() !== $v) {
            $this->aEvent = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[EventContactTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[EventContactTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [contact_person] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setContactPerson($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->contact_person !== $v) {
            $this->contact_person = $v;
            $this->modifiedColumns[EventContactTableMap::COL_CONTACT_PERSON] = true;
        }

        return $this;
    } // setContactPerson()

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[EventContactTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [address2] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setAddress2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address2 !== $v) {
            $this->address2 = $v;
            $this->modifiedColumns[EventContactTableMap::COL_ADDRESS2] = true;
        }

        return $this;
    } // setAddress2()

    /**
     * Set the value of [city] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[EventContactTableMap::COL_CITY] = true;
        }

        return $this;
    } // setCity()

    /**
     * Set the value of [zip] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setZip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->zip !== $v) {
            $this->zip = $v;
            $this->modifiedColumns[EventContactTableMap::COL_ZIP] = true;
        }

        return $this;
    } // setZip()

    /**
     * Set the value of [tax_identification_nr] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setTaxIdentificationNr($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->tax_identification_nr !== $v) {
            $this->tax_identification_nr = $v;
            $this->modifiedColumns[EventContactTableMap::COL_TAX_IDENTIFICATION_NR] = true;
        }

        return $this;
    } // setTaxIdentificationNr()

    /**
     * Set the value of [telephon] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setTelephon($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->telephon !== $v) {
            $this->telephon = $v;
            $this->modifiedColumns[EventContactTableMap::COL_TELEPHON] = true;
        }

        return $this;
    } // setTelephon()

    /**
     * Set the value of [fax] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fax !== $v) {
            $this->fax = $v;
            $this->modifiedColumns[EventContactTableMap::COL_FAX] = true;
        }

        return $this;
    } // setFax()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[EventContactTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Sets the value of the [active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
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
            $this->modifiedColumns[EventContactTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Sets the value of the [default] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
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
            $this->modifiedColumns[EventContactTableMap::COL_DEFAULT] = true;
        }

        return $this;
    } // setDefault()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventContactTableMap::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_contactid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventContactTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventContactTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventContactTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventContactTableMap::translateFieldName('ContactPerson', TableMap::TYPE_PHPNAME, $indexType)];
            $this->contact_person = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventContactTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventContactTableMap::translateFieldName('Address2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventContactTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EventContactTableMap::translateFieldName('Zip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->zip = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : EventContactTableMap::translateFieldName('TaxIdentificationNr', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tax_identification_nr = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : EventContactTableMap::translateFieldName('Telephon', TableMap::TYPE_PHPNAME, $indexType)];
            $this->telephon = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : EventContactTableMap::translateFieldName('Fax', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fax = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : EventContactTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : EventContactTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : EventContactTableMap::translateFieldName('Default', TableMap::TYPE_PHPNAME, $indexType)];
            $this->default = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 15; // 15 = EventContactTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Event\\EventContact'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(EventContactTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventContactQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvent = null;
            $this->collInvoicesRelatedByCustomerEventContactid = null;

            $this->collInvoicesRelatedByEventContactid = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EventContact::setDeleted()
     * @see EventContact::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventContactTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventContactQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventContactTableMap::DATABASE_NAME);
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
                EventContactTableMap::addInstanceToPool($this);
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

            if ($this->invoicesRelatedByCustomerEventContactidScheduledForDeletion !== null) {
                if (!$this->invoicesRelatedByCustomerEventContactidScheduledForDeletion->isEmpty()) {
                    foreach ($this->invoicesRelatedByCustomerEventContactidScheduledForDeletion as $invoiceRelatedByCustomerEventContactid) {
                        // need to save related object because we set the relation to null
                        $invoiceRelatedByCustomerEventContactid->save($con);
                    }
                    $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion = null;
                }
            }

            if ($this->collInvoicesRelatedByCustomerEventContactid !== null) {
                foreach ($this->collInvoicesRelatedByCustomerEventContactid as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->invoicesRelatedByEventContactidScheduledForDeletion !== null) {
                if (!$this->invoicesRelatedByEventContactidScheduledForDeletion->isEmpty()) {
                    \API\Models\Invoice\InvoiceQuery::create()
                        ->filterByPrimaryKeys($this->invoicesRelatedByEventContactidScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoicesRelatedByEventContactidScheduledForDeletion = null;
                }
            }

            if ($this->collInvoicesRelatedByEventContactid !== null) {
                foreach ($this->collInvoicesRelatedByEventContactid as $referrerFK) {
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

        $this->modifiedColumns[EventContactTableMap::COL_EVENT_CONTACTID] = true;
        if (null !== $this->event_contactid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventContactTableMap::COL_EVENT_CONTACTID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventContactTableMap::COL_EVENT_CONTACTID)) {
            $modifiedColumns[':p' . $index++]  = 'event_contactid';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_CONTACT_PERSON)) {
            $modifiedColumns[':p' . $index++]  = 'contact_person';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'address';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ADDRESS2)) {
            $modifiedColumns[':p' . $index++]  = 'address2';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'city';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ZIP)) {
            $modifiedColumns[':p' . $index++]  = 'zip';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_TAX_IDENTIFICATION_NR)) {
            $modifiedColumns[':p' . $index++]  = 'tax_identification_nr';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_TELEPHON)) {
            $modifiedColumns[':p' . $index++]  = 'telephon';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_FAX)) {
            $modifiedColumns[':p' . $index++]  = 'fax';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }
        if ($this->isColumnModified(EventContactTableMap::COL_DEFAULT)) {
            $modifiedColumns[':p' . $index++]  = 'default';
        }

        $sql = sprintf(
            'INSERT INTO event_contact (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'event_contactid':
                        $stmt->bindValue($identifier, $this->event_contactid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'title':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'contact_person':
                        $stmt->bindValue($identifier, $this->contact_person, PDO::PARAM_STR);
                        break;
                    case 'address':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case 'address2':
                        $stmt->bindValue($identifier, $this->address2, PDO::PARAM_STR);
                        break;
                    case 'city':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case 'zip':
                        $stmt->bindValue($identifier, $this->zip, PDO::PARAM_STR);
                        break;
                    case 'tax_identification_nr':
                        $stmt->bindValue($identifier, $this->tax_identification_nr, PDO::PARAM_STR);
                        break;
                    case 'telephon':
                        $stmt->bindValue($identifier, $this->telephon, PDO::PARAM_STR);
                        break;
                    case 'fax':
                        $stmt->bindValue($identifier, $this->fax, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'active':
                        $stmt->bindValue($identifier, (int) $this->active, PDO::PARAM_INT);
                        break;
                    case 'default':
                        $stmt->bindValue($identifier, (int) $this->default, PDO::PARAM_INT);
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
        $this->setEventContactid($pk);

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
        $pos = EventContactTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventContactid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getContactPerson();
                break;
            case 5:
                return $this->getAddress();
                break;
            case 6:
                return $this->getAddress2();
                break;
            case 7:
                return $this->getCity();
                break;
            case 8:
                return $this->getZip();
                break;
            case 9:
                return $this->getTaxIdentificationNr();
                break;
            case 10:
                return $this->getTelephon();
                break;
            case 11:
                return $this->getFax();
                break;
            case 12:
                return $this->getEmail();
                break;
            case 13:
                return $this->getActive();
                break;
            case 14:
                return $this->getDefault();
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

        if (isset($alreadyDumpedObjects['EventContact'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventContact'][$this->hashCode()] = true;
        $keys = EventContactTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventContactid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getContactPerson(),
            $keys[5] => $this->getAddress(),
            $keys[6] => $this->getAddress2(),
            $keys[7] => $this->getCity(),
            $keys[8] => $this->getZip(),
            $keys[9] => $this->getTaxIdentificationNr(),
            $keys[10] => $this->getTelephon(),
            $keys[11] => $this->getFax(),
            $keys[12] => $this->getEmail(),
            $keys[13] => $this->getActive(),
            $keys[14] => $this->getDefault(),
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
            if (null !== $this->collInvoicesRelatedByCustomerEventContactid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoices';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoices';
                        break;
                    default:
                        $key = 'Invoices';
                }

                $result[$key] = $this->collInvoicesRelatedByCustomerEventContactid->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInvoicesRelatedByEventContactid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoices';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoices';
                        break;
                    default:
                        $key = 'Invoices';
                }

                $result[$key] = $this->collInvoicesRelatedByEventContactid->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Event\EventContact
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventContactTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Event\EventContact
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventContactid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setContactPerson($value);
                break;
            case 5:
                $this->setAddress($value);
                break;
            case 6:
                $this->setAddress2($value);
                break;
            case 7:
                $this->setCity($value);
                break;
            case 8:
                $this->setZip($value);
                break;
            case 9:
                $this->setTaxIdentificationNr($value);
                break;
            case 10:
                $this->setTelephon($value);
                break;
            case 11:
                $this->setFax($value);
                break;
            case 12:
                $this->setEmail($value);
                break;
            case 13:
                $this->setActive($value);
                break;
            case 14:
                $this->setDefault($value);
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
        $keys = EventContactTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventContactid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTitle($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setContactPerson($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAddress($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setAddress2($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCity($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setZip($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setTaxIdentificationNr($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setTelephon($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setFax($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setEmail($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setActive($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setDefault($arr[$keys[14]]);
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
     * @return $this|\API\Models\Event\EventContact The current object, for fluid interface
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
        $criteria = new Criteria(EventContactTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventContactTableMap::COL_EVENT_CONTACTID)) {
            $criteria->add(EventContactTableMap::COL_EVENT_CONTACTID, $this->event_contactid);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_EVENTID)) {
            $criteria->add(EventContactTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_TITLE)) {
            $criteria->add(EventContactTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_NAME)) {
            $criteria->add(EventContactTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_CONTACT_PERSON)) {
            $criteria->add(EventContactTableMap::COL_CONTACT_PERSON, $this->contact_person);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ADDRESS)) {
            $criteria->add(EventContactTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ADDRESS2)) {
            $criteria->add(EventContactTableMap::COL_ADDRESS2, $this->address2);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_CITY)) {
            $criteria->add(EventContactTableMap::COL_CITY, $this->city);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ZIP)) {
            $criteria->add(EventContactTableMap::COL_ZIP, $this->zip);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_TAX_IDENTIFICATION_NR)) {
            $criteria->add(EventContactTableMap::COL_TAX_IDENTIFICATION_NR, $this->tax_identification_nr);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_TELEPHON)) {
            $criteria->add(EventContactTableMap::COL_TELEPHON, $this->telephon);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_FAX)) {
            $criteria->add(EventContactTableMap::COL_FAX, $this->fax);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_EMAIL)) {
            $criteria->add(EventContactTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_ACTIVE)) {
            $criteria->add(EventContactTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(EventContactTableMap::COL_DEFAULT)) {
            $criteria->add(EventContactTableMap::COL_DEFAULT, $this->default);
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
        $criteria = ChildEventContactQuery::create();
        $criteria->add(EventContactTableMap::COL_EVENT_CONTACTID, $this->event_contactid);

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
        $validPk = null !== $this->getEventContactid();

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
        return $this->getEventContactid();
    }

    /**
     * Generic method to set the primary key (event_contactid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setEventContactid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getEventContactid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Event\EventContact (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setName($this->getName());
        $copyObj->setContactPerson($this->getContactPerson());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setAddress2($this->getAddress2());
        $copyObj->setCity($this->getCity());
        $copyObj->setZip($this->getZip());
        $copyObj->setTaxIdentificationNr($this->getTaxIdentificationNr());
        $copyObj->setTelephon($this->getTelephon());
        $copyObj->setFax($this->getFax());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setActive($this->getActive());
        $copyObj->setDefault($this->getDefault());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getInvoicesRelatedByCustomerEventContactid() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceRelatedByCustomerEventContactid($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvoicesRelatedByEventContactid() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceRelatedByEventContactid($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventContactid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Event\EventContact Clone of current object.
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
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
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
            $v->addEventContact($this);
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
                $this->aEvent->addEventContacts($this);
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
        if ('InvoiceRelatedByCustomerEventContactid' == $relationName) {
            return $this->initInvoicesRelatedByCustomerEventContactid();
        }
        if ('InvoiceRelatedByEventContactid' == $relationName) {
            return $this->initInvoicesRelatedByEventContactid();
        }
    }

    /**
     * Clears out the collInvoicesRelatedByCustomerEventContactid collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoicesRelatedByCustomerEventContactid()
     */
    public function clearInvoicesRelatedByCustomerEventContactid()
    {
        $this->collInvoicesRelatedByCustomerEventContactid = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoicesRelatedByCustomerEventContactid collection loaded partially.
     */
    public function resetPartialInvoicesRelatedByCustomerEventContactid($v = true)
    {
        $this->collInvoicesRelatedByCustomerEventContactidPartial = $v;
    }

    /**
     * Initializes the collInvoicesRelatedByCustomerEventContactid collection.
     *
     * By default this just sets the collInvoicesRelatedByCustomerEventContactid collection to an empty array (like clearcollInvoicesRelatedByCustomerEventContactid());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoicesRelatedByCustomerEventContactid($overrideExisting = true)
    {
        if (null !== $this->collInvoicesRelatedByCustomerEventContactid && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoicesRelatedByCustomerEventContactid = new $collectionClassName;
        $this->collInvoicesRelatedByCustomerEventContactid->setModel('\API\Models\Invoice\Invoice');
    }

    /**
     * Gets an array of Invoice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventContact is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Invoice[] List of Invoice objects
     * @throws PropelException
     */
    public function getInvoicesRelatedByCustomerEventContactid(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesRelatedByCustomerEventContactidPartial && !$this->isNew();
        if (null === $this->collInvoicesRelatedByCustomerEventContactid || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoicesRelatedByCustomerEventContactid) {
                // return empty collection
                $this->initInvoicesRelatedByCustomerEventContactid();
            } else {
                $collInvoicesRelatedByCustomerEventContactid = InvoiceQuery::create(null, $criteria)
                    ->filterByEventContactRelatedByCustomerEventContactid($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoicesRelatedByCustomerEventContactidPartial && count($collInvoicesRelatedByCustomerEventContactid)) {
                        $this->initInvoicesRelatedByCustomerEventContactid(false);

                        foreach ($collInvoicesRelatedByCustomerEventContactid as $obj) {
                            if (false == $this->collInvoicesRelatedByCustomerEventContactid->contains($obj)) {
                                $this->collInvoicesRelatedByCustomerEventContactid->append($obj);
                            }
                        }

                        $this->collInvoicesRelatedByCustomerEventContactidPartial = true;
                    }

                    return $collInvoicesRelatedByCustomerEventContactid;
                }

                if ($partial && $this->collInvoicesRelatedByCustomerEventContactid) {
                    foreach ($this->collInvoicesRelatedByCustomerEventContactid as $obj) {
                        if ($obj->isNew()) {
                            $collInvoicesRelatedByCustomerEventContactid[] = $obj;
                        }
                    }
                }

                $this->collInvoicesRelatedByCustomerEventContactid = $collInvoicesRelatedByCustomerEventContactid;
                $this->collInvoicesRelatedByCustomerEventContactidPartial = false;
            }
        }

        return $this->collInvoicesRelatedByCustomerEventContactid;
    }

    /**
     * Sets a collection of Invoice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoicesRelatedByCustomerEventContactid A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventContact The current object (for fluent API support)
     */
    public function setInvoicesRelatedByCustomerEventContactid(Collection $invoicesRelatedByCustomerEventContactid, ConnectionInterface $con = null)
    {
        /** @var Invoice[] $invoicesRelatedByCustomerEventContactidToDelete */
        $invoicesRelatedByCustomerEventContactidToDelete = $this->getInvoicesRelatedByCustomerEventContactid(new Criteria(), $con)->diff($invoicesRelatedByCustomerEventContactid);


        $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion = $invoicesRelatedByCustomerEventContactidToDelete;

        foreach ($invoicesRelatedByCustomerEventContactidToDelete as $invoiceRelatedByCustomerEventContactidRemoved) {
            $invoiceRelatedByCustomerEventContactidRemoved->setEventContactRelatedByCustomerEventContactid(null);
        }

        $this->collInvoicesRelatedByCustomerEventContactid = null;
        foreach ($invoicesRelatedByCustomerEventContactid as $invoiceRelatedByCustomerEventContactid) {
            $this->addInvoiceRelatedByCustomerEventContactid($invoiceRelatedByCustomerEventContactid);
        }

        $this->collInvoicesRelatedByCustomerEventContactid = $invoicesRelatedByCustomerEventContactid;
        $this->collInvoicesRelatedByCustomerEventContactidPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseInvoice objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseInvoice objects.
     * @throws PropelException
     */
    public function countInvoicesRelatedByCustomerEventContactid(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesRelatedByCustomerEventContactidPartial && !$this->isNew();
        if (null === $this->collInvoicesRelatedByCustomerEventContactid || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoicesRelatedByCustomerEventContactid) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoicesRelatedByCustomerEventContactid());
            }

            $query = InvoiceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventContactRelatedByCustomerEventContactid($this)
                ->count($con);
        }

        return count($this->collInvoicesRelatedByCustomerEventContactid);
    }

    /**
     * Method called to associate a Invoice object to this object
     * through the Invoice foreign key attribute.
     *
     * @param  Invoice $l Invoice
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function addInvoiceRelatedByCustomerEventContactid(Invoice $l)
    {
        if ($this->collInvoicesRelatedByCustomerEventContactid === null) {
            $this->initInvoicesRelatedByCustomerEventContactid();
            $this->collInvoicesRelatedByCustomerEventContactidPartial = true;
        }

        if (!$this->collInvoicesRelatedByCustomerEventContactid->contains($l)) {
            $this->doAddInvoiceRelatedByCustomerEventContactid($l);

            if ($this->invoicesRelatedByCustomerEventContactidScheduledForDeletion and $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion->contains($l)) {
                $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion->remove($this->invoicesRelatedByCustomerEventContactidScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Invoice $invoiceRelatedByCustomerEventContactid The Invoice object to add.
     */
    protected function doAddInvoiceRelatedByCustomerEventContactid(Invoice $invoiceRelatedByCustomerEventContactid)
    {
        $this->collInvoicesRelatedByCustomerEventContactid[]= $invoiceRelatedByCustomerEventContactid;
        $invoiceRelatedByCustomerEventContactid->setEventContactRelatedByCustomerEventContactid($this);
    }

    /**
     * @param  Invoice $invoiceRelatedByCustomerEventContactid The Invoice object to remove.
     * @return $this|ChildEventContact The current object (for fluent API support)
     */
    public function removeInvoiceRelatedByCustomerEventContactid(Invoice $invoiceRelatedByCustomerEventContactid)
    {
        if ($this->getInvoicesRelatedByCustomerEventContactid()->contains($invoiceRelatedByCustomerEventContactid)) {
            $pos = $this->collInvoicesRelatedByCustomerEventContactid->search($invoiceRelatedByCustomerEventContactid);
            $this->collInvoicesRelatedByCustomerEventContactid->remove($pos);
            if (null === $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion) {
                $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion = clone $this->collInvoicesRelatedByCustomerEventContactid;
                $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion->clear();
            }
            $this->invoicesRelatedByCustomerEventContactidScheduledForDeletion[]= $invoiceRelatedByCustomerEventContactid;
            $invoiceRelatedByCustomerEventContactid->setEventContactRelatedByCustomerEventContactid(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByCustomerEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByCustomerEventContactidJoinEventBankinformation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('EventBankinformation', $joinBehavior);

        return $this->getInvoicesRelatedByCustomerEventContactid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByCustomerEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByCustomerEventContactidJoinInvoiceRelatedByCanceledInvoiceid(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('InvoiceRelatedByCanceledInvoiceid', $joinBehavior);

        return $this->getInvoicesRelatedByCustomerEventContactid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByCustomerEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByCustomerEventContactidJoinInvoiceType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('InvoiceType', $joinBehavior);

        return $this->getInvoicesRelatedByCustomerEventContactid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByCustomerEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByCustomerEventContactidJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getInvoicesRelatedByCustomerEventContactid($query, $con);
    }

    /**
     * Clears out the collInvoicesRelatedByEventContactid collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoicesRelatedByEventContactid()
     */
    public function clearInvoicesRelatedByEventContactid()
    {
        $this->collInvoicesRelatedByEventContactid = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoicesRelatedByEventContactid collection loaded partially.
     */
    public function resetPartialInvoicesRelatedByEventContactid($v = true)
    {
        $this->collInvoicesRelatedByEventContactidPartial = $v;
    }

    /**
     * Initializes the collInvoicesRelatedByEventContactid collection.
     *
     * By default this just sets the collInvoicesRelatedByEventContactid collection to an empty array (like clearcollInvoicesRelatedByEventContactid());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoicesRelatedByEventContactid($overrideExisting = true)
    {
        if (null !== $this->collInvoicesRelatedByEventContactid && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoicesRelatedByEventContactid = new $collectionClassName;
        $this->collInvoicesRelatedByEventContactid->setModel('\API\Models\Invoice\Invoice');
    }

    /**
     * Gets an array of Invoice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventContact is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Invoice[] List of Invoice objects
     * @throws PropelException
     */
    public function getInvoicesRelatedByEventContactid(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesRelatedByEventContactidPartial && !$this->isNew();
        if (null === $this->collInvoicesRelatedByEventContactid || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoicesRelatedByEventContactid) {
                // return empty collection
                $this->initInvoicesRelatedByEventContactid();
            } else {
                $collInvoicesRelatedByEventContactid = InvoiceQuery::create(null, $criteria)
                    ->filterByEventContactRelatedByEventContactid($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoicesRelatedByEventContactidPartial && count($collInvoicesRelatedByEventContactid)) {
                        $this->initInvoicesRelatedByEventContactid(false);

                        foreach ($collInvoicesRelatedByEventContactid as $obj) {
                            if (false == $this->collInvoicesRelatedByEventContactid->contains($obj)) {
                                $this->collInvoicesRelatedByEventContactid->append($obj);
                            }
                        }

                        $this->collInvoicesRelatedByEventContactidPartial = true;
                    }

                    return $collInvoicesRelatedByEventContactid;
                }

                if ($partial && $this->collInvoicesRelatedByEventContactid) {
                    foreach ($this->collInvoicesRelatedByEventContactid as $obj) {
                        if ($obj->isNew()) {
                            $collInvoicesRelatedByEventContactid[] = $obj;
                        }
                    }
                }

                $this->collInvoicesRelatedByEventContactid = $collInvoicesRelatedByEventContactid;
                $this->collInvoicesRelatedByEventContactidPartial = false;
            }
        }

        return $this->collInvoicesRelatedByEventContactid;
    }

    /**
     * Sets a collection of Invoice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoicesRelatedByEventContactid A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventContact The current object (for fluent API support)
     */
    public function setInvoicesRelatedByEventContactid(Collection $invoicesRelatedByEventContactid, ConnectionInterface $con = null)
    {
        /** @var Invoice[] $invoicesRelatedByEventContactidToDelete */
        $invoicesRelatedByEventContactidToDelete = $this->getInvoicesRelatedByEventContactid(new Criteria(), $con)->diff($invoicesRelatedByEventContactid);


        $this->invoicesRelatedByEventContactidScheduledForDeletion = $invoicesRelatedByEventContactidToDelete;

        foreach ($invoicesRelatedByEventContactidToDelete as $invoiceRelatedByEventContactidRemoved) {
            $invoiceRelatedByEventContactidRemoved->setEventContactRelatedByEventContactid(null);
        }

        $this->collInvoicesRelatedByEventContactid = null;
        foreach ($invoicesRelatedByEventContactid as $invoiceRelatedByEventContactid) {
            $this->addInvoiceRelatedByEventContactid($invoiceRelatedByEventContactid);
        }

        $this->collInvoicesRelatedByEventContactid = $invoicesRelatedByEventContactid;
        $this->collInvoicesRelatedByEventContactidPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseInvoice objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseInvoice objects.
     * @throws PropelException
     */
    public function countInvoicesRelatedByEventContactid(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesRelatedByEventContactidPartial && !$this->isNew();
        if (null === $this->collInvoicesRelatedByEventContactid || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoicesRelatedByEventContactid) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoicesRelatedByEventContactid());
            }

            $query = InvoiceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventContactRelatedByEventContactid($this)
                ->count($con);
        }

        return count($this->collInvoicesRelatedByEventContactid);
    }

    /**
     * Method called to associate a Invoice object to this object
     * through the Invoice foreign key attribute.
     *
     * @param  Invoice $l Invoice
     * @return $this|\API\Models\Event\EventContact The current object (for fluent API support)
     */
    public function addInvoiceRelatedByEventContactid(Invoice $l)
    {
        if ($this->collInvoicesRelatedByEventContactid === null) {
            $this->initInvoicesRelatedByEventContactid();
            $this->collInvoicesRelatedByEventContactidPartial = true;
        }

        if (!$this->collInvoicesRelatedByEventContactid->contains($l)) {
            $this->doAddInvoiceRelatedByEventContactid($l);

            if ($this->invoicesRelatedByEventContactidScheduledForDeletion and $this->invoicesRelatedByEventContactidScheduledForDeletion->contains($l)) {
                $this->invoicesRelatedByEventContactidScheduledForDeletion->remove($this->invoicesRelatedByEventContactidScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Invoice $invoiceRelatedByEventContactid The Invoice object to add.
     */
    protected function doAddInvoiceRelatedByEventContactid(Invoice $invoiceRelatedByEventContactid)
    {
        $this->collInvoicesRelatedByEventContactid[]= $invoiceRelatedByEventContactid;
        $invoiceRelatedByEventContactid->setEventContactRelatedByEventContactid($this);
    }

    /**
     * @param  Invoice $invoiceRelatedByEventContactid The Invoice object to remove.
     * @return $this|ChildEventContact The current object (for fluent API support)
     */
    public function removeInvoiceRelatedByEventContactid(Invoice $invoiceRelatedByEventContactid)
    {
        if ($this->getInvoicesRelatedByEventContactid()->contains($invoiceRelatedByEventContactid)) {
            $pos = $this->collInvoicesRelatedByEventContactid->search($invoiceRelatedByEventContactid);
            $this->collInvoicesRelatedByEventContactid->remove($pos);
            if (null === $this->invoicesRelatedByEventContactidScheduledForDeletion) {
                $this->invoicesRelatedByEventContactidScheduledForDeletion = clone $this->collInvoicesRelatedByEventContactid;
                $this->invoicesRelatedByEventContactidScheduledForDeletion->clear();
            }
            $this->invoicesRelatedByEventContactidScheduledForDeletion[]= clone $invoiceRelatedByEventContactid;
            $invoiceRelatedByEventContactid->setEventContactRelatedByEventContactid(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByEventContactidJoinEventBankinformation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('EventBankinformation', $joinBehavior);

        return $this->getInvoicesRelatedByEventContactid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByEventContactidJoinInvoiceRelatedByCanceledInvoiceid(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('InvoiceRelatedByCanceledInvoiceid', $joinBehavior);

        return $this->getInvoicesRelatedByEventContactid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByEventContactidJoinInvoiceType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('InvoiceType', $joinBehavior);

        return $this->getInvoicesRelatedByEventContactid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this EventContact is new, it will return
     * an empty collection; or if this EventContact has previously
     * been saved, it will retrieve related InvoicesRelatedByEventContactid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in EventContact.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesRelatedByEventContactidJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getInvoicesRelatedByEventContactid($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvent) {
            $this->aEvent->removeEventContact($this);
        }
        $this->event_contactid = null;
        $this->eventid = null;
        $this->title = null;
        $this->name = null;
        $this->contact_person = null;
        $this->address = null;
        $this->address2 = null;
        $this->city = null;
        $this->zip = null;
        $this->tax_identification_nr = null;
        $this->telephon = null;
        $this->fax = null;
        $this->email = null;
        $this->active = null;
        $this->default = null;
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
            if ($this->collInvoicesRelatedByCustomerEventContactid) {
                foreach ($this->collInvoicesRelatedByCustomerEventContactid as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvoicesRelatedByEventContactid) {
                foreach ($this->collInvoicesRelatedByEventContactid as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvoicesRelatedByCustomerEventContactid = null;
        $this->collInvoicesRelatedByEventContactid = null;
        $this->aEvent = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventContactTableMap::DEFAULT_STRING_FORMAT);
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
