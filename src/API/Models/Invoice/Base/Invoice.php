<?php

namespace API\Models\Invoice\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventBankinformationQuery;
use API\Models\Event\EventContact;
use API\Models\Event\EventContactQuery;
use API\Models\Invoice\Invoice as ChildInvoice;
use API\Models\Invoice\InvoiceItem as ChildInvoiceItem;
use API\Models\Invoice\InvoiceItemQuery as ChildInvoiceItemQuery;
use API\Models\Invoice\InvoiceQuery as ChildInvoiceQuery;
use API\Models\Invoice\InvoiceType as ChildInvoiceType;
use API\Models\Invoice\InvoiceTypeQuery as ChildInvoiceTypeQuery;
use API\Models\Invoice\InvoiceWarning as ChildInvoiceWarning;
use API\Models\Invoice\InvoiceWarningQuery as ChildInvoiceWarningQuery;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\Invoice\Map\InvoiceTableMap;
use API\Models\Invoice\Map\InvoiceWarningTableMap;
use API\Models\Payment\PaymentRecieved;
use API\Models\Payment\PaymentRecievedQuery;
use API\Models\Payment\Base\PaymentRecieved as BasePaymentRecieved;
use API\Models\Payment\Map\PaymentRecievedTableMap;
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
 * Base class that represents a row from the 'invoice' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Invoice.Base
 */
abstract class Invoice implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Invoice\\Map\\InvoiceTableMap';


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
     * The value for the invoiceid field.
     *
     * @var        int
     */
    protected $invoiceid;

    /**
     * The value for the invoice_typeid field.
     *
     * @var        int
     */
    protected $invoice_typeid;

    /**
     * The value for the event_contactid field.
     *
     * @var        int
     */
    protected $event_contactid;

    /**
     * The value for the cashier_userid field.
     *
     * @var        int
     */
    protected $cashier_userid;

    /**
     * The value for the event_bankinformationid field.
     *
     * @var        int
     */
    protected $event_bankinformationid;

    /**
     * The value for the customer_event_contactid field.
     *
     * @var        int
     */
    protected $customer_event_contactid;

    /**
     * The value for the canceled_invoiceid field.
     *
     * @var        int
     */
    protected $canceled_invoiceid;

    /**
     * The value for the date field.
     *
     * @var        DateTime
     */
    protected $date;

    /**
     * The value for the amount field.
     *
     * @var        string
     */
    protected $amount;

    /**
     * The value for the maturity_date field.
     *
     * @var        DateTime
     */
    protected $maturity_date;

    /**
     * The value for the payment_finished field.
     *
     * @var        DateTime
     */
    protected $payment_finished;

    /**
     * The value for the amount_recieved field.
     *
     * @var        string
     */
    protected $amount_recieved;

    /**
     * @var        EventContact
     */
    protected $aEventContactRelatedByCustomerEventContactid;

    /**
     * @var        EventBankinformation
     */
    protected $aEventBankinformation;

    /**
     * @var        EventContact
     */
    protected $aEventContactRelatedByEventContactid;

    /**
     * @var        ChildInvoice
     */
    protected $aInvoiceRelatedByCanceledInvoiceid;

    /**
     * @var        ChildInvoiceType
     */
    protected $aInvoiceType;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildInvoice[] Collection to store aggregation of ChildInvoice objects.
     */
    protected $collInvoicesRelatedByInvoiceid;
    protected $collInvoicesRelatedByInvoiceidPartial;

    /**
     * @var        ObjectCollection|ChildInvoiceItem[] Collection to store aggregation of ChildInvoiceItem objects.
     */
    protected $collInvoiceItems;
    protected $collInvoiceItemsPartial;

    /**
     * @var        ObjectCollection|PaymentRecieved[] Collection to store aggregation of PaymentRecieved objects.
     */
    protected $collPaymentRecieveds;
    protected $collPaymentRecievedsPartial;

    /**
     * @var        ObjectCollection|ChildInvoiceWarning[] Collection to store aggregation of ChildInvoiceWarning objects.
     */
    protected $collInvoiceWarnings;
    protected $collInvoiceWarningsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInvoice[]
     */
    protected $invoicesRelatedByInvoiceidScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInvoiceItem[]
     */
    protected $invoiceItemsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|PaymentRecieved[]
     */
    protected $paymentRecievedsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInvoiceWarning[]
     */
    protected $invoiceWarningsScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Invoice\Base\Invoice object.
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
     * Compares this with another <code>Invoice</code> instance.  If
     * <code>obj</code> is an instance of <code>Invoice</code>, delegates to
     * <code>equals(Invoice)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Invoice The current object, for fluid interface
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
     * Get the [invoiceid] column value.
     *
     * @return int
     */
    public function getInvoiceid()
    {
        return $this->invoiceid;
    }

    /**
     * Get the [invoice_typeid] column value.
     *
     * @return int
     */
    public function getInvoiceTypeid()
    {
        return $this->invoice_typeid;
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
     * Get the [cashier_userid] column value.
     *
     * @return int
     */
    public function getCashierUserid()
    {
        return $this->cashier_userid;
    }

    /**
     * Get the [event_bankinformationid] column value.
     *
     * @return int
     */
    public function getEventBankinformationid()
    {
        return $this->event_bankinformationid;
    }

    /**
     * Get the [customer_event_contactid] column value.
     *
     * @return int
     */
    public function getCustomerEventContactid()
    {
        return $this->customer_event_contactid;
    }

    /**
     * Get the [canceled_invoiceid] column value.
     *
     * @return int
     */
    public function getCanceledInvoiceid()
    {
        return $this->canceled_invoiceid;
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
     * Get the [amount] column value.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the [optionally formatted] temporal [maturity_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getMaturityDate($format = NULL)
    {
        if ($format === null) {
            return $this->maturity_date;
        } else {
            return $this->maturity_date instanceof \DateTimeInterface ? $this->maturity_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [payment_finished] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPaymentFinished($format = NULL)
    {
        if ($format === null) {
            return $this->payment_finished;
        } else {
            return $this->payment_finished instanceof \DateTimeInterface ? $this->payment_finished->format($format) : null;
        }
    }

    /**
     * Get the [amount_recieved] column value.
     *
     * @return string
     */
    public function getAmountRecieved()
    {
        return $this->amount_recieved;
    }

    /**
     * Set the value of [invoiceid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setInvoiceid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->invoiceid !== $v) {
            $this->invoiceid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_INVOICEID] = true;
        }

        return $this;
    } // setInvoiceid()

    /**
     * Set the value of [invoice_typeid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setInvoiceTypeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->invoice_typeid !== $v) {
            $this->invoice_typeid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_INVOICE_TYPEID] = true;
        }

        if ($this->aInvoiceType !== null && $this->aInvoiceType->getInvoiceTypeid() !== $v) {
            $this->aInvoiceType = null;
        }

        return $this;
    } // setInvoiceTypeid()

    /**
     * Set the value of [event_contactid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setEventContactid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_contactid !== $v) {
            $this->event_contactid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_EVENT_CONTACTID] = true;
        }

        if ($this->aEventContactRelatedByEventContactid !== null && $this->aEventContactRelatedByEventContactid->getEventContactid() !== $v) {
            $this->aEventContactRelatedByEventContactid = null;
        }

        return $this;
    } // setEventContactid()

    /**
     * Set the value of [cashier_userid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setCashierUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cashier_userid !== $v) {
            $this->cashier_userid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_CASHIER_USERID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getUserid() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setCashierUserid()

    /**
     * Set the value of [event_bankinformationid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setEventBankinformationid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_bankinformationid !== $v) {
            $this->event_bankinformationid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_EVENT_BANKINFORMATIONID] = true;
        }

        if ($this->aEventBankinformation !== null && $this->aEventBankinformation->getEventBankinformationid() !== $v) {
            $this->aEventBankinformation = null;
        }

        return $this;
    } // setEventBankinformationid()

    /**
     * Set the value of [customer_event_contactid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setCustomerEventContactid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->customer_event_contactid !== $v) {
            $this->customer_event_contactid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID] = true;
        }

        if ($this->aEventContactRelatedByCustomerEventContactid !== null && $this->aEventContactRelatedByCustomerEventContactid->getEventContactid() !== $v) {
            $this->aEventContactRelatedByCustomerEventContactid = null;
        }

        return $this;
    } // setCustomerEventContactid()

    /**
     * Set the value of [canceled_invoiceid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setCanceledInvoiceid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->canceled_invoiceid !== $v) {
            $this->canceled_invoiceid = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_CANCELED_INVOICEID] = true;
        }

        if ($this->aInvoiceRelatedByCanceledInvoiceid !== null && $this->aInvoiceRelatedByCanceledInvoiceid->getInvoiceid() !== $v) {
            $this->aInvoiceRelatedByCanceledInvoiceid = null;
        }

        return $this;
    } // setCanceledInvoiceid()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->date->format("Y-m-d H:i:s.u")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvoiceTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Set the value of [amount] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_AMOUNT] = true;
        }

        return $this;
    } // setAmount()

    /**
     * Sets the value of [maturity_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setMaturityDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->maturity_date !== null || $dt !== null) {
            if ($this->maturity_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->maturity_date->format("Y-m-d H:i:s.u")) {
                $this->maturity_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvoiceTableMap::COL_MATURITY_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setMaturityDate()

    /**
     * Sets the value of [payment_finished] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setPaymentFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->payment_finished !== null || $dt !== null) {
            if ($this->payment_finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->payment_finished->format("Y-m-d H:i:s.u")) {
                $this->payment_finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvoiceTableMap::COL_PAYMENT_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setPaymentFinished()

    /**
     * Set the value of [amount_recieved] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setAmountRecieved($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->amount_recieved !== $v) {
            $this->amount_recieved = $v;
            $this->modifiedColumns[InvoiceTableMap::COL_AMOUNT_RECIEVED] = true;
        }

        return $this;
    } // setAmountRecieved()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InvoiceTableMap::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invoiceid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : InvoiceTableMap::translateFieldName('InvoiceTypeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invoice_typeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : InvoiceTableMap::translateFieldName('EventContactid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_contactid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : InvoiceTableMap::translateFieldName('CashierUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cashier_userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : InvoiceTableMap::translateFieldName('EventBankinformationid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_bankinformationid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : InvoiceTableMap::translateFieldName('CustomerEventContactid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_event_contactid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : InvoiceTableMap::translateFieldName('CanceledInvoiceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->canceled_invoiceid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : InvoiceTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : InvoiceTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : InvoiceTableMap::translateFieldName('MaturityDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->maturity_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : InvoiceTableMap::translateFieldName('PaymentFinished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->payment_finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : InvoiceTableMap::translateFieldName('AmountRecieved', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount_recieved = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = InvoiceTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Invoice\\Invoice'), 0, $e);
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
        if ($this->aInvoiceType !== null && $this->invoice_typeid !== $this->aInvoiceType->getInvoiceTypeid()) {
            $this->aInvoiceType = null;
        }
        if ($this->aEventContactRelatedByEventContactid !== null && $this->event_contactid !== $this->aEventContactRelatedByEventContactid->getEventContactid()) {
            $this->aEventContactRelatedByEventContactid = null;
        }
        if ($this->aUser !== null && $this->cashier_userid !== $this->aUser->getUserid()) {
            $this->aUser = null;
        }
        if ($this->aEventBankinformation !== null && $this->event_bankinformationid !== $this->aEventBankinformation->getEventBankinformationid()) {
            $this->aEventBankinformation = null;
        }
        if ($this->aEventContactRelatedByCustomerEventContactid !== null && $this->customer_event_contactid !== $this->aEventContactRelatedByCustomerEventContactid->getEventContactid()) {
            $this->aEventContactRelatedByCustomerEventContactid = null;
        }
        if ($this->aInvoiceRelatedByCanceledInvoiceid !== null && $this->canceled_invoiceid !== $this->aInvoiceRelatedByCanceledInvoiceid->getInvoiceid()) {
            $this->aInvoiceRelatedByCanceledInvoiceid = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(InvoiceTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildInvoiceQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEventContactRelatedByCustomerEventContactid = null;
            $this->aEventBankinformation = null;
            $this->aEventContactRelatedByEventContactid = null;
            $this->aInvoiceRelatedByCanceledInvoiceid = null;
            $this->aInvoiceType = null;
            $this->aUser = null;
            $this->collInvoicesRelatedByInvoiceid = null;

            $this->collInvoiceItems = null;

            $this->collPaymentRecieveds = null;

            $this->collInvoiceWarnings = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Invoice::setDeleted()
     * @see Invoice::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildInvoiceQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
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
                InvoiceTableMap::addInstanceToPool($this);
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

            if ($this->aEventContactRelatedByCustomerEventContactid !== null) {
                if ($this->aEventContactRelatedByCustomerEventContactid->isModified() || $this->aEventContactRelatedByCustomerEventContactid->isNew()) {
                    $affectedRows += $this->aEventContactRelatedByCustomerEventContactid->save($con);
                }
                $this->setEventContactRelatedByCustomerEventContactid($this->aEventContactRelatedByCustomerEventContactid);
            }

            if ($this->aEventBankinformation !== null) {
                if ($this->aEventBankinformation->isModified() || $this->aEventBankinformation->isNew()) {
                    $affectedRows += $this->aEventBankinformation->save($con);
                }
                $this->setEventBankinformation($this->aEventBankinformation);
            }

            if ($this->aEventContactRelatedByEventContactid !== null) {
                if ($this->aEventContactRelatedByEventContactid->isModified() || $this->aEventContactRelatedByEventContactid->isNew()) {
                    $affectedRows += $this->aEventContactRelatedByEventContactid->save($con);
                }
                $this->setEventContactRelatedByEventContactid($this->aEventContactRelatedByEventContactid);
            }

            if ($this->aInvoiceRelatedByCanceledInvoiceid !== null) {
                if ($this->aInvoiceRelatedByCanceledInvoiceid->isModified() || $this->aInvoiceRelatedByCanceledInvoiceid->isNew()) {
                    $affectedRows += $this->aInvoiceRelatedByCanceledInvoiceid->save($con);
                }
                $this->setInvoiceRelatedByCanceledInvoiceid($this->aInvoiceRelatedByCanceledInvoiceid);
            }

            if ($this->aInvoiceType !== null) {
                if ($this->aInvoiceType->isModified() || $this->aInvoiceType->isNew()) {
                    $affectedRows += $this->aInvoiceType->save($con);
                }
                $this->setInvoiceType($this->aInvoiceType);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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

            if ($this->invoicesRelatedByInvoiceidScheduledForDeletion !== null) {
                if (!$this->invoicesRelatedByInvoiceidScheduledForDeletion->isEmpty()) {
                    foreach ($this->invoicesRelatedByInvoiceidScheduledForDeletion as $invoiceRelatedByInvoiceid) {
                        // need to save related object because we set the relation to null
                        $invoiceRelatedByInvoiceid->save($con);
                    }
                    $this->invoicesRelatedByInvoiceidScheduledForDeletion = null;
                }
            }

            if ($this->collInvoicesRelatedByInvoiceid !== null) {
                foreach ($this->collInvoicesRelatedByInvoiceid as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->invoiceItemsScheduledForDeletion !== null) {
                if (!$this->invoiceItemsScheduledForDeletion->isEmpty()) {
                    \API\Models\Invoice\InvoiceItemQuery::create()
                        ->filterByPrimaryKeys($this->invoiceItemsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoiceItemsScheduledForDeletion = null;
                }
            }

            if ($this->collInvoiceItems !== null) {
                foreach ($this->collInvoiceItems as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->paymentRecievedsScheduledForDeletion !== null) {
                if (!$this->paymentRecievedsScheduledForDeletion->isEmpty()) {
                    \API\Models\Payment\PaymentRecievedQuery::create()
                        ->filterByPrimaryKeys($this->paymentRecievedsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->paymentRecievedsScheduledForDeletion = null;
                }
            }

            if ($this->collPaymentRecieveds !== null) {
                foreach ($this->collPaymentRecieveds as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->invoiceWarningsScheduledForDeletion !== null) {
                if (!$this->invoiceWarningsScheduledForDeletion->isEmpty()) {
                    \API\Models\Invoice\InvoiceWarningQuery::create()
                        ->filterByPrimaryKeys($this->invoiceWarningsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoiceWarningsScheduledForDeletion = null;
                }
            }

            if ($this->collInvoiceWarnings !== null) {
                foreach ($this->collInvoiceWarnings as $referrerFK) {
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

        $this->modifiedColumns[InvoiceTableMap::COL_INVOICEID] = true;
        if (null !== $this->invoiceid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . InvoiceTableMap::COL_INVOICEID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(InvoiceTableMap::COL_INVOICEID)) {
            $modifiedColumns[':p' . $index++]  = 'invoiceid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_INVOICE_TYPEID)) {
            $modifiedColumns[':p' . $index++]  = 'invoice_typeid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_EVENT_CONTACTID)) {
            $modifiedColumns[':p' . $index++]  = 'event_contactid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CASHIER_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'cashier_userid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID)) {
            $modifiedColumns[':p' . $index++]  = 'event_bankinformationid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID)) {
            $modifiedColumns[':p' . $index++]  = 'customer_event_contactid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CANCELED_INVOICEID)) {
            $modifiedColumns[':p' . $index++]  = 'canceled_invoiceid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'amount';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_MATURITY_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'maturity_date';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_PAYMENT_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = 'payment_finished';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_AMOUNT_RECIEVED)) {
            $modifiedColumns[':p' . $index++]  = 'amount_recieved';
        }

        $sql = sprintf(
            'INSERT INTO invoice (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'invoiceid':
                        $stmt->bindValue($identifier, $this->invoiceid, PDO::PARAM_INT);
                        break;
                    case 'invoice_typeid':
                        $stmt->bindValue($identifier, $this->invoice_typeid, PDO::PARAM_INT);
                        break;
                    case 'event_contactid':
                        $stmt->bindValue($identifier, $this->event_contactid, PDO::PARAM_INT);
                        break;
                    case 'cashier_userid':
                        $stmt->bindValue($identifier, $this->cashier_userid, PDO::PARAM_INT);
                        break;
                    case 'event_bankinformationid':
                        $stmt->bindValue($identifier, $this->event_bankinformationid, PDO::PARAM_INT);
                        break;
                    case 'customer_event_contactid':
                        $stmt->bindValue($identifier, $this->customer_event_contactid, PDO::PARAM_INT);
                        break;
                    case 'canceled_invoiceid':
                        $stmt->bindValue($identifier, $this->canceled_invoiceid, PDO::PARAM_INT);
                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'amount':
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_STR);
                        break;
                    case 'maturity_date':
                        $stmt->bindValue($identifier, $this->maturity_date ? $this->maturity_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'payment_finished':
                        $stmt->bindValue($identifier, $this->payment_finished ? $this->payment_finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'amount_recieved':
                        $stmt->bindValue($identifier, $this->amount_recieved, PDO::PARAM_STR);
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
        $this->setInvoiceid($pk);

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
        $pos = InvoiceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getInvoiceid();
                break;
            case 1:
                return $this->getInvoiceTypeid();
                break;
            case 2:
                return $this->getEventContactid();
                break;
            case 3:
                return $this->getCashierUserid();
                break;
            case 4:
                return $this->getEventBankinformationid();
                break;
            case 5:
                return $this->getCustomerEventContactid();
                break;
            case 6:
                return $this->getCanceledInvoiceid();
                break;
            case 7:
                return $this->getDate();
                break;
            case 8:
                return $this->getAmount();
                break;
            case 9:
                return $this->getMaturityDate();
                break;
            case 10:
                return $this->getPaymentFinished();
                break;
            case 11:
                return $this->getAmountRecieved();
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

        if (isset($alreadyDumpedObjects['Invoice'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Invoice'][$this->hashCode()] = true;
        $keys = InvoiceTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getInvoiceid(),
            $keys[1] => $this->getInvoiceTypeid(),
            $keys[2] => $this->getEventContactid(),
            $keys[3] => $this->getCashierUserid(),
            $keys[4] => $this->getEventBankinformationid(),
            $keys[5] => $this->getCustomerEventContactid(),
            $keys[6] => $this->getCanceledInvoiceid(),
            $keys[7] => $this->getDate(),
            $keys[8] => $this->getAmount(),
            $keys[9] => $this->getMaturityDate(),
            $keys[10] => $this->getPaymentFinished(),
            $keys[11] => $this->getAmountRecieved(),
        );
        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        if ($result[$keys[9]] instanceof \DateTime) {
            $result[$keys[9]] = $result[$keys[9]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEventContactRelatedByCustomerEventContactid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventContact';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_contact';
                        break;
                    default:
                        $key = 'EventContact';
                }

                $result[$key] = $this->aEventContactRelatedByCustomerEventContactid->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventBankinformation) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventBankinformation';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_bankinformation';
                        break;
                    default:
                        $key = 'EventBankinformation';
                }

                $result[$key] = $this->aEventBankinformation->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventContactRelatedByEventContactid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventContact';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_contact';
                        break;
                    default:
                        $key = 'EventContact';
                }

                $result[$key] = $this->aEventContactRelatedByEventContactid->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aInvoiceRelatedByCanceledInvoiceid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoice';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoice';
                        break;
                    default:
                        $key = 'Invoice';
                }

                $result[$key] = $this->aInvoiceRelatedByCanceledInvoiceid->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aInvoiceType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoiceType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoice_type';
                        break;
                    default:
                        $key = 'InvoiceType';
                }

                $result[$key] = $this->aInvoiceType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {

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

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collInvoicesRelatedByInvoiceid) {

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

                $result[$key] = $this->collInvoicesRelatedByInvoiceid->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInvoiceItems) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoiceItems';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoice_items';
                        break;
                    default:
                        $key = 'InvoiceItems';
                }

                $result[$key] = $this->collInvoiceItems->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPaymentRecieveds) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'paymentRecieveds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'payment_recieveds';
                        break;
                    default:
                        $key = 'PaymentRecieveds';
                }

                $result[$key] = $this->collPaymentRecieveds->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInvoiceWarnings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoiceWarnings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoice_warnings';
                        break;
                    default:
                        $key = 'InvoiceWarnings';
                }

                $result[$key] = $this->collInvoiceWarnings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Invoice\Invoice
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = InvoiceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Invoice\Invoice
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setInvoiceid($value);
                break;
            case 1:
                $this->setInvoiceTypeid($value);
                break;
            case 2:
                $this->setEventContactid($value);
                break;
            case 3:
                $this->setCashierUserid($value);
                break;
            case 4:
                $this->setEventBankinformationid($value);
                break;
            case 5:
                $this->setCustomerEventContactid($value);
                break;
            case 6:
                $this->setCanceledInvoiceid($value);
                break;
            case 7:
                $this->setDate($value);
                break;
            case 8:
                $this->setAmount($value);
                break;
            case 9:
                $this->setMaturityDate($value);
                break;
            case 10:
                $this->setPaymentFinished($value);
                break;
            case 11:
                $this->setAmountRecieved($value);
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
        $keys = InvoiceTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setInvoiceid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setInvoiceTypeid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setEventContactid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCashierUserid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEventBankinformationid($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCustomerEventContactid($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCanceledInvoiceid($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setDate($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setAmount($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setMaturityDate($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setPaymentFinished($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAmountRecieved($arr[$keys[11]]);
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
     * @return $this|\API\Models\Invoice\Invoice The current object, for fluid interface
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
        $criteria = new Criteria(InvoiceTableMap::DATABASE_NAME);

        if ($this->isColumnModified(InvoiceTableMap::COL_INVOICEID)) {
            $criteria->add(InvoiceTableMap::COL_INVOICEID, $this->invoiceid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_INVOICE_TYPEID)) {
            $criteria->add(InvoiceTableMap::COL_INVOICE_TYPEID, $this->invoice_typeid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_EVENT_CONTACTID)) {
            $criteria->add(InvoiceTableMap::COL_EVENT_CONTACTID, $this->event_contactid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CASHIER_USERID)) {
            $criteria->add(InvoiceTableMap::COL_CASHIER_USERID, $this->cashier_userid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID)) {
            $criteria->add(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID, $this->event_bankinformationid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID)) {
            $criteria->add(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID, $this->customer_event_contactid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CANCELED_INVOICEID)) {
            $criteria->add(InvoiceTableMap::COL_CANCELED_INVOICEID, $this->canceled_invoiceid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_DATE)) {
            $criteria->add(InvoiceTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_AMOUNT)) {
            $criteria->add(InvoiceTableMap::COL_AMOUNT, $this->amount);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_MATURITY_DATE)) {
            $criteria->add(InvoiceTableMap::COL_MATURITY_DATE, $this->maturity_date);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_PAYMENT_FINISHED)) {
            $criteria->add(InvoiceTableMap::COL_PAYMENT_FINISHED, $this->payment_finished);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_AMOUNT_RECIEVED)) {
            $criteria->add(InvoiceTableMap::COL_AMOUNT_RECIEVED, $this->amount_recieved);
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
        $criteria = ChildInvoiceQuery::create();
        $criteria->add(InvoiceTableMap::COL_INVOICEID, $this->invoiceid);

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
        $validPk = null !== $this->getInvoiceid();

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
        return $this->getInvoiceid();
    }

    /**
     * Generic method to set the primary key (invoiceid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setInvoiceid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getInvoiceid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Invoice\Invoice (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setInvoiceTypeid($this->getInvoiceTypeid());
        $copyObj->setEventContactid($this->getEventContactid());
        $copyObj->setCashierUserid($this->getCashierUserid());
        $copyObj->setEventBankinformationid($this->getEventBankinformationid());
        $copyObj->setCustomerEventContactid($this->getCustomerEventContactid());
        $copyObj->setCanceledInvoiceid($this->getCanceledInvoiceid());
        $copyObj->setDate($this->getDate());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setMaturityDate($this->getMaturityDate());
        $copyObj->setPaymentFinished($this->getPaymentFinished());
        $copyObj->setAmountRecieved($this->getAmountRecieved());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getInvoicesRelatedByInvoiceid() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceRelatedByInvoiceid($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvoiceItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceItem($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPaymentRecieveds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPaymentRecieved($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvoiceWarnings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceWarning($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setInvoiceid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Invoice\Invoice Clone of current object.
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
     * Declares an association between this object and a EventContact object.
     *
     * @param  EventContact $v
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventContactRelatedByCustomerEventContactid(EventContact $v = null)
    {
        if ($v === null) {
            $this->setCustomerEventContactid(NULL);
        } else {
            $this->setCustomerEventContactid($v->getEventContactid());
        }

        $this->aEventContactRelatedByCustomerEventContactid = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EventContact object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoiceRelatedByCustomerEventContactid($this);
        }


        return $this;
    }


    /**
     * Get the associated EventContact object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return EventContact The associated EventContact object.
     * @throws PropelException
     */
    public function getEventContactRelatedByCustomerEventContactid(ConnectionInterface $con = null)
    {
        if ($this->aEventContactRelatedByCustomerEventContactid === null && ($this->customer_event_contactid !== null)) {
            $this->aEventContactRelatedByCustomerEventContactid = EventContactQuery::create()->findPk($this->customer_event_contactid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventContactRelatedByCustomerEventContactid->addInvoicesRelatedByCustomerEventContactid($this);
             */
        }

        return $this->aEventContactRelatedByCustomerEventContactid;
    }

    /**
     * Declares an association between this object and a EventBankinformation object.
     *
     * @param  EventBankinformation $v
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventBankinformation(EventBankinformation $v = null)
    {
        if ($v === null) {
            $this->setEventBankinformationid(NULL);
        } else {
            $this->setEventBankinformationid($v->getEventBankinformationid());
        }

        $this->aEventBankinformation = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EventBankinformation object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoice($this);
        }


        return $this;
    }


    /**
     * Get the associated EventBankinformation object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return EventBankinformation The associated EventBankinformation object.
     * @throws PropelException
     */
    public function getEventBankinformation(ConnectionInterface $con = null)
    {
        if ($this->aEventBankinformation === null && ($this->event_bankinformationid !== null)) {
            $this->aEventBankinformation = EventBankinformationQuery::create()->findPk($this->event_bankinformationid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventBankinformation->addInvoices($this);
             */
        }

        return $this->aEventBankinformation;
    }

    /**
     * Declares an association between this object and a EventContact object.
     *
     * @param  EventContact $v
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventContactRelatedByEventContactid(EventContact $v = null)
    {
        if ($v === null) {
            $this->setEventContactid(NULL);
        } else {
            $this->setEventContactid($v->getEventContactid());
        }

        $this->aEventContactRelatedByEventContactid = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the EventContact object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoiceRelatedByEventContactid($this);
        }


        return $this;
    }


    /**
     * Get the associated EventContact object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return EventContact The associated EventContact object.
     * @throws PropelException
     */
    public function getEventContactRelatedByEventContactid(ConnectionInterface $con = null)
    {
        if ($this->aEventContactRelatedByEventContactid === null && ($this->event_contactid !== null)) {
            $this->aEventContactRelatedByEventContactid = EventContactQuery::create()->findPk($this->event_contactid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventContactRelatedByEventContactid->addInvoicesRelatedByEventContactid($this);
             */
        }

        return $this->aEventContactRelatedByEventContactid;
    }

    /**
     * Declares an association between this object and a ChildInvoice object.
     *
     * @param  ChildInvoice $v
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     * @throws PropelException
     */
    public function setInvoiceRelatedByCanceledInvoiceid(ChildInvoice $v = null)
    {
        if ($v === null) {
            $this->setCanceledInvoiceid(NULL);
        } else {
            $this->setCanceledInvoiceid($v->getInvoiceid());
        }

        $this->aInvoiceRelatedByCanceledInvoiceid = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildInvoice object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoiceRelatedByInvoiceid($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildInvoice object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildInvoice The associated ChildInvoice object.
     * @throws PropelException
     */
    public function getInvoiceRelatedByCanceledInvoiceid(ConnectionInterface $con = null)
    {
        if ($this->aInvoiceRelatedByCanceledInvoiceid === null && ($this->canceled_invoiceid !== null)) {
            $this->aInvoiceRelatedByCanceledInvoiceid = ChildInvoiceQuery::create()->findPk($this->canceled_invoiceid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aInvoiceRelatedByCanceledInvoiceid->addInvoicesRelatedByInvoiceid($this);
             */
        }

        return $this->aInvoiceRelatedByCanceledInvoiceid;
    }

    /**
     * Declares an association between this object and a ChildInvoiceType object.
     *
     * @param  ChildInvoiceType $v
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     * @throws PropelException
     */
    public function setInvoiceType(ChildInvoiceType $v = null)
    {
        if ($v === null) {
            $this->setInvoiceTypeid(NULL);
        } else {
            $this->setInvoiceTypeid($v->getInvoiceTypeid());
        }

        $this->aInvoiceType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildInvoiceType object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoice($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildInvoiceType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildInvoiceType The associated ChildInvoiceType object.
     * @throws PropelException
     */
    public function getInvoiceType(ConnectionInterface $con = null)
    {
        if ($this->aInvoiceType === null && ($this->invoice_typeid !== null)) {
            $this->aInvoiceType = ChildInvoiceTypeQuery::create()->findPk($this->invoice_typeid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aInvoiceType->addInvoices($this);
             */
        }

        return $this->aInvoiceType;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setCashierUserid(NULL);
        } else {
            $this->setCashierUserid($v->getUserid());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoice($this);
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
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->cashier_userid !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->cashier_userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addInvoices($this);
             */
        }

        return $this->aUser;
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
        if ('InvoiceRelatedByInvoiceid' == $relationName) {
            return $this->initInvoicesRelatedByInvoiceid();
        }
        if ('InvoiceItem' == $relationName) {
            return $this->initInvoiceItems();
        }
        if ('PaymentRecieved' == $relationName) {
            return $this->initPaymentRecieveds();
        }
        if ('InvoiceWarning' == $relationName) {
            return $this->initInvoiceWarnings();
        }
    }

    /**
     * Clears out the collInvoicesRelatedByInvoiceid collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoicesRelatedByInvoiceid()
     */
    public function clearInvoicesRelatedByInvoiceid()
    {
        $this->collInvoicesRelatedByInvoiceid = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoicesRelatedByInvoiceid collection loaded partially.
     */
    public function resetPartialInvoicesRelatedByInvoiceid($v = true)
    {
        $this->collInvoicesRelatedByInvoiceidPartial = $v;
    }

    /**
     * Initializes the collInvoicesRelatedByInvoiceid collection.
     *
     * By default this just sets the collInvoicesRelatedByInvoiceid collection to an empty array (like clearcollInvoicesRelatedByInvoiceid());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoicesRelatedByInvoiceid($overrideExisting = true)
    {
        if (null !== $this->collInvoicesRelatedByInvoiceid && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoicesRelatedByInvoiceid = new $collectionClassName;
        $this->collInvoicesRelatedByInvoiceid->setModel('\API\Models\Invoice\Invoice');
    }

    /**
     * Gets an array of ChildInvoice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoice is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInvoice[] List of ChildInvoice objects
     * @throws PropelException
     */
    public function getInvoicesRelatedByInvoiceid(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesRelatedByInvoiceidPartial && !$this->isNew();
        if (null === $this->collInvoicesRelatedByInvoiceid || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoicesRelatedByInvoiceid) {
                // return empty collection
                $this->initInvoicesRelatedByInvoiceid();
            } else {
                $collInvoicesRelatedByInvoiceid = ChildInvoiceQuery::create(null, $criteria)
                    ->filterByInvoiceRelatedByCanceledInvoiceid($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoicesRelatedByInvoiceidPartial && count($collInvoicesRelatedByInvoiceid)) {
                        $this->initInvoicesRelatedByInvoiceid(false);

                        foreach ($collInvoicesRelatedByInvoiceid as $obj) {
                            if (false == $this->collInvoicesRelatedByInvoiceid->contains($obj)) {
                                $this->collInvoicesRelatedByInvoiceid->append($obj);
                            }
                        }

                        $this->collInvoicesRelatedByInvoiceidPartial = true;
                    }

                    return $collInvoicesRelatedByInvoiceid;
                }

                if ($partial && $this->collInvoicesRelatedByInvoiceid) {
                    foreach ($this->collInvoicesRelatedByInvoiceid as $obj) {
                        if ($obj->isNew()) {
                            $collInvoicesRelatedByInvoiceid[] = $obj;
                        }
                    }
                }

                $this->collInvoicesRelatedByInvoiceid = $collInvoicesRelatedByInvoiceid;
                $this->collInvoicesRelatedByInvoiceidPartial = false;
            }
        }

        return $this->collInvoicesRelatedByInvoiceid;
    }

    /**
     * Sets a collection of ChildInvoice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoicesRelatedByInvoiceid A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function setInvoicesRelatedByInvoiceid(Collection $invoicesRelatedByInvoiceid, ConnectionInterface $con = null)
    {
        /** @var ChildInvoice[] $invoicesRelatedByInvoiceidToDelete */
        $invoicesRelatedByInvoiceidToDelete = $this->getInvoicesRelatedByInvoiceid(new Criteria(), $con)->diff($invoicesRelatedByInvoiceid);


        $this->invoicesRelatedByInvoiceidScheduledForDeletion = $invoicesRelatedByInvoiceidToDelete;

        foreach ($invoicesRelatedByInvoiceidToDelete as $invoiceRelatedByInvoiceidRemoved) {
            $invoiceRelatedByInvoiceidRemoved->setInvoiceRelatedByCanceledInvoiceid(null);
        }

        $this->collInvoicesRelatedByInvoiceid = null;
        foreach ($invoicesRelatedByInvoiceid as $invoiceRelatedByInvoiceid) {
            $this->addInvoiceRelatedByInvoiceid($invoiceRelatedByInvoiceid);
        }

        $this->collInvoicesRelatedByInvoiceid = $invoicesRelatedByInvoiceid;
        $this->collInvoicesRelatedByInvoiceidPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Invoice objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Invoice objects.
     * @throws PropelException
     */
    public function countInvoicesRelatedByInvoiceid(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesRelatedByInvoiceidPartial && !$this->isNew();
        if (null === $this->collInvoicesRelatedByInvoiceid || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoicesRelatedByInvoiceid) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoicesRelatedByInvoiceid());
            }

            $query = ChildInvoiceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoiceRelatedByCanceledInvoiceid($this)
                ->count($con);
        }

        return count($this->collInvoicesRelatedByInvoiceid);
    }

    /**
     * Method called to associate a ChildInvoice object to this object
     * through the ChildInvoice foreign key attribute.
     *
     * @param  ChildInvoice $l ChildInvoice
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function addInvoiceRelatedByInvoiceid(ChildInvoice $l)
    {
        if ($this->collInvoicesRelatedByInvoiceid === null) {
            $this->initInvoicesRelatedByInvoiceid();
            $this->collInvoicesRelatedByInvoiceidPartial = true;
        }

        if (!$this->collInvoicesRelatedByInvoiceid->contains($l)) {
            $this->doAddInvoiceRelatedByInvoiceid($l);

            if ($this->invoicesRelatedByInvoiceidScheduledForDeletion and $this->invoicesRelatedByInvoiceidScheduledForDeletion->contains($l)) {
                $this->invoicesRelatedByInvoiceidScheduledForDeletion->remove($this->invoicesRelatedByInvoiceidScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInvoice $invoiceRelatedByInvoiceid The ChildInvoice object to add.
     */
    protected function doAddInvoiceRelatedByInvoiceid(ChildInvoice $invoiceRelatedByInvoiceid)
    {
        $this->collInvoicesRelatedByInvoiceid[]= $invoiceRelatedByInvoiceid;
        $invoiceRelatedByInvoiceid->setInvoiceRelatedByCanceledInvoiceid($this);
    }

    /**
     * @param  ChildInvoice $invoiceRelatedByInvoiceid The ChildInvoice object to remove.
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function removeInvoiceRelatedByInvoiceid(ChildInvoice $invoiceRelatedByInvoiceid)
    {
        if ($this->getInvoicesRelatedByInvoiceid()->contains($invoiceRelatedByInvoiceid)) {
            $pos = $this->collInvoicesRelatedByInvoiceid->search($invoiceRelatedByInvoiceid);
            $this->collInvoicesRelatedByInvoiceid->remove($pos);
            if (null === $this->invoicesRelatedByInvoiceidScheduledForDeletion) {
                $this->invoicesRelatedByInvoiceidScheduledForDeletion = clone $this->collInvoicesRelatedByInvoiceid;
                $this->invoicesRelatedByInvoiceidScheduledForDeletion->clear();
            }
            $this->invoicesRelatedByInvoiceidScheduledForDeletion[]= $invoiceRelatedByInvoiceid;
            $invoiceRelatedByInvoiceid->setInvoiceRelatedByCanceledInvoiceid(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoicesRelatedByInvoiceid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoice[] List of ChildInvoice objects
     */
    public function getInvoicesRelatedByInvoiceidJoinEventContactRelatedByCustomerEventContactid(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceQuery::create(null, $criteria);
        $query->joinWith('EventContactRelatedByCustomerEventContactid', $joinBehavior);

        return $this->getInvoicesRelatedByInvoiceid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoicesRelatedByInvoiceid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoice[] List of ChildInvoice objects
     */
    public function getInvoicesRelatedByInvoiceidJoinEventBankinformation(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceQuery::create(null, $criteria);
        $query->joinWith('EventBankinformation', $joinBehavior);

        return $this->getInvoicesRelatedByInvoiceid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoicesRelatedByInvoiceid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoice[] List of ChildInvoice objects
     */
    public function getInvoicesRelatedByInvoiceidJoinEventContactRelatedByEventContactid(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceQuery::create(null, $criteria);
        $query->joinWith('EventContactRelatedByEventContactid', $joinBehavior);

        return $this->getInvoicesRelatedByInvoiceid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoicesRelatedByInvoiceid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoice[] List of ChildInvoice objects
     */
    public function getInvoicesRelatedByInvoiceidJoinInvoiceType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceQuery::create(null, $criteria);
        $query->joinWith('InvoiceType', $joinBehavior);

        return $this->getInvoicesRelatedByInvoiceid($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoicesRelatedByInvoiceid from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoice[] List of ChildInvoice objects
     */
    public function getInvoicesRelatedByInvoiceidJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getInvoicesRelatedByInvoiceid($query, $con);
    }

    /**
     * Clears out the collInvoiceItems collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoiceItems()
     */
    public function clearInvoiceItems()
    {
        $this->collInvoiceItems = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoiceItems collection loaded partially.
     */
    public function resetPartialInvoiceItems($v = true)
    {
        $this->collInvoiceItemsPartial = $v;
    }

    /**
     * Initializes the collInvoiceItems collection.
     *
     * By default this just sets the collInvoiceItems collection to an empty array (like clearcollInvoiceItems());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoiceItems($overrideExisting = true)
    {
        if (null !== $this->collInvoiceItems && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceItemTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoiceItems = new $collectionClassName;
        $this->collInvoiceItems->setModel('\API\Models\Invoice\InvoiceItem');
    }

    /**
     * Gets an array of ChildInvoiceItem objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoice is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInvoiceItem[] List of ChildInvoiceItem objects
     * @throws PropelException
     */
    public function getInvoiceItems(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoiceItemsPartial && !$this->isNew();
        if (null === $this->collInvoiceItems || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoiceItems) {
                // return empty collection
                $this->initInvoiceItems();
            } else {
                $collInvoiceItems = ChildInvoiceItemQuery::create(null, $criteria)
                    ->filterByInvoice($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoiceItemsPartial && count($collInvoiceItems)) {
                        $this->initInvoiceItems(false);

                        foreach ($collInvoiceItems as $obj) {
                            if (false == $this->collInvoiceItems->contains($obj)) {
                                $this->collInvoiceItems->append($obj);
                            }
                        }

                        $this->collInvoiceItemsPartial = true;
                    }

                    return $collInvoiceItems;
                }

                if ($partial && $this->collInvoiceItems) {
                    foreach ($this->collInvoiceItems as $obj) {
                        if ($obj->isNew()) {
                            $collInvoiceItems[] = $obj;
                        }
                    }
                }

                $this->collInvoiceItems = $collInvoiceItems;
                $this->collInvoiceItemsPartial = false;
            }
        }

        return $this->collInvoiceItems;
    }

    /**
     * Sets a collection of ChildInvoiceItem objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoiceItems A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function setInvoiceItems(Collection $invoiceItems, ConnectionInterface $con = null)
    {
        /** @var ChildInvoiceItem[] $invoiceItemsToDelete */
        $invoiceItemsToDelete = $this->getInvoiceItems(new Criteria(), $con)->diff($invoiceItems);


        $this->invoiceItemsScheduledForDeletion = $invoiceItemsToDelete;

        foreach ($invoiceItemsToDelete as $invoiceItemRemoved) {
            $invoiceItemRemoved->setInvoice(null);
        }

        $this->collInvoiceItems = null;
        foreach ($invoiceItems as $invoiceItem) {
            $this->addInvoiceItem($invoiceItem);
        }

        $this->collInvoiceItems = $invoiceItems;
        $this->collInvoiceItemsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InvoiceItem objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related InvoiceItem objects.
     * @throws PropelException
     */
    public function countInvoiceItems(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoiceItemsPartial && !$this->isNew();
        if (null === $this->collInvoiceItems || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoiceItems) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoiceItems());
            }

            $query = ChildInvoiceItemQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoice($this)
                ->count($con);
        }

        return count($this->collInvoiceItems);
    }

    /**
     * Method called to associate a ChildInvoiceItem object to this object
     * through the ChildInvoiceItem foreign key attribute.
     *
     * @param  ChildInvoiceItem $l ChildInvoiceItem
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function addInvoiceItem(ChildInvoiceItem $l)
    {
        if ($this->collInvoiceItems === null) {
            $this->initInvoiceItems();
            $this->collInvoiceItemsPartial = true;
        }

        if (!$this->collInvoiceItems->contains($l)) {
            $this->doAddInvoiceItem($l);

            if ($this->invoiceItemsScheduledForDeletion and $this->invoiceItemsScheduledForDeletion->contains($l)) {
                $this->invoiceItemsScheduledForDeletion->remove($this->invoiceItemsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInvoiceItem $invoiceItem The ChildInvoiceItem object to add.
     */
    protected function doAddInvoiceItem(ChildInvoiceItem $invoiceItem)
    {
        $this->collInvoiceItems[]= $invoiceItem;
        $invoiceItem->setInvoice($this);
    }

    /**
     * @param  ChildInvoiceItem $invoiceItem The ChildInvoiceItem object to remove.
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function removeInvoiceItem(ChildInvoiceItem $invoiceItem)
    {
        if ($this->getInvoiceItems()->contains($invoiceItem)) {
            $pos = $this->collInvoiceItems->search($invoiceItem);
            $this->collInvoiceItems->remove($pos);
            if (null === $this->invoiceItemsScheduledForDeletion) {
                $this->invoiceItemsScheduledForDeletion = clone $this->collInvoiceItems;
                $this->invoiceItemsScheduledForDeletion->clear();
            }
            $this->invoiceItemsScheduledForDeletion[]= clone $invoiceItem;
            $invoiceItem->setInvoice(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoiceItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoiceItem[] List of ChildInvoiceItem objects
     */
    public function getInvoiceItemsJoinOrderDetail(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceItemQuery::create(null, $criteria);
        $query->joinWith('OrderDetail', $joinBehavior);

        return $this->getInvoiceItems($query, $con);
    }

    /**
     * Clears out the collPaymentRecieveds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPaymentRecieveds()
     */
    public function clearPaymentRecieveds()
    {
        $this->collPaymentRecieveds = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPaymentRecieveds collection loaded partially.
     */
    public function resetPartialPaymentRecieveds($v = true)
    {
        $this->collPaymentRecievedsPartial = $v;
    }

    /**
     * Initializes the collPaymentRecieveds collection.
     *
     * By default this just sets the collPaymentRecieveds collection to an empty array (like clearcollPaymentRecieveds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPaymentRecieveds($overrideExisting = true)
    {
        if (null !== $this->collPaymentRecieveds && !$overrideExisting) {
            return;
        }

        $collectionClassName = PaymentRecievedTableMap::getTableMap()->getCollectionClassName();

        $this->collPaymentRecieveds = new $collectionClassName;
        $this->collPaymentRecieveds->setModel('\API\Models\Payment\PaymentRecieved');
    }

    /**
     * Gets an array of PaymentRecieved objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoice is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|PaymentRecieved[] List of PaymentRecieved objects
     * @throws PropelException
     */
    public function getPaymentRecieveds(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentRecievedsPartial && !$this->isNew();
        if (null === $this->collPaymentRecieveds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPaymentRecieveds) {
                // return empty collection
                $this->initPaymentRecieveds();
            } else {
                $collPaymentRecieveds = PaymentRecievedQuery::create(null, $criteria)
                    ->filterByInvoice($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPaymentRecievedsPartial && count($collPaymentRecieveds)) {
                        $this->initPaymentRecieveds(false);

                        foreach ($collPaymentRecieveds as $obj) {
                            if (false == $this->collPaymentRecieveds->contains($obj)) {
                                $this->collPaymentRecieveds->append($obj);
                            }
                        }

                        $this->collPaymentRecievedsPartial = true;
                    }

                    return $collPaymentRecieveds;
                }

                if ($partial && $this->collPaymentRecieveds) {
                    foreach ($this->collPaymentRecieveds as $obj) {
                        if ($obj->isNew()) {
                            $collPaymentRecieveds[] = $obj;
                        }
                    }
                }

                $this->collPaymentRecieveds = $collPaymentRecieveds;
                $this->collPaymentRecievedsPartial = false;
            }
        }

        return $this->collPaymentRecieveds;
    }

    /**
     * Sets a collection of PaymentRecieved objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $paymentRecieveds A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function setPaymentRecieveds(Collection $paymentRecieveds, ConnectionInterface $con = null)
    {
        /** @var PaymentRecieved[] $paymentRecievedsToDelete */
        $paymentRecievedsToDelete = $this->getPaymentRecieveds(new Criteria(), $con)->diff($paymentRecieveds);


        $this->paymentRecievedsScheduledForDeletion = $paymentRecievedsToDelete;

        foreach ($paymentRecievedsToDelete as $paymentRecievedRemoved) {
            $paymentRecievedRemoved->setInvoice(null);
        }

        $this->collPaymentRecieveds = null;
        foreach ($paymentRecieveds as $paymentRecieved) {
            $this->addPaymentRecieved($paymentRecieved);
        }

        $this->collPaymentRecieveds = $paymentRecieveds;
        $this->collPaymentRecievedsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePaymentRecieved objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePaymentRecieved objects.
     * @throws PropelException
     */
    public function countPaymentRecieveds(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentRecievedsPartial && !$this->isNew();
        if (null === $this->collPaymentRecieveds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPaymentRecieveds) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPaymentRecieveds());
            }

            $query = PaymentRecievedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoice($this)
                ->count($con);
        }

        return count($this->collPaymentRecieveds);
    }

    /**
     * Method called to associate a PaymentRecieved object to this object
     * through the PaymentRecieved foreign key attribute.
     *
     * @param  PaymentRecieved $l PaymentRecieved
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function addPaymentRecieved(PaymentRecieved $l)
    {
        if ($this->collPaymentRecieveds === null) {
            $this->initPaymentRecieveds();
            $this->collPaymentRecievedsPartial = true;
        }

        if (!$this->collPaymentRecieveds->contains($l)) {
            $this->doAddPaymentRecieved($l);

            if ($this->paymentRecievedsScheduledForDeletion and $this->paymentRecievedsScheduledForDeletion->contains($l)) {
                $this->paymentRecievedsScheduledForDeletion->remove($this->paymentRecievedsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param PaymentRecieved $paymentRecieved The PaymentRecieved object to add.
     */
    protected function doAddPaymentRecieved(PaymentRecieved $paymentRecieved)
    {
        $this->collPaymentRecieveds[]= $paymentRecieved;
        $paymentRecieved->setInvoice($this);
    }

    /**
     * @param  PaymentRecieved $paymentRecieved The PaymentRecieved object to remove.
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function removePaymentRecieved(PaymentRecieved $paymentRecieved)
    {
        if ($this->getPaymentRecieveds()->contains($paymentRecieved)) {
            $pos = $this->collPaymentRecieveds->search($paymentRecieved);
            $this->collPaymentRecieveds->remove($pos);
            if (null === $this->paymentRecievedsScheduledForDeletion) {
                $this->paymentRecievedsScheduledForDeletion = clone $this->collPaymentRecieveds;
                $this->paymentRecievedsScheduledForDeletion->clear();
            }
            $this->paymentRecievedsScheduledForDeletion[]= clone $paymentRecieved;
            $paymentRecieved->setInvoice(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related PaymentRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|PaymentRecieved[] List of PaymentRecieved objects
     */
    public function getPaymentRecievedsJoinPaymentType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PaymentRecievedQuery::create(null, $criteria);
        $query->joinWith('PaymentType', $joinBehavior);

        return $this->getPaymentRecieveds($query, $con);
    }

    /**
     * Clears out the collInvoiceWarnings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoiceWarnings()
     */
    public function clearInvoiceWarnings()
    {
        $this->collInvoiceWarnings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoiceWarnings collection loaded partially.
     */
    public function resetPartialInvoiceWarnings($v = true)
    {
        $this->collInvoiceWarningsPartial = $v;
    }

    /**
     * Initializes the collInvoiceWarnings collection.
     *
     * By default this just sets the collInvoiceWarnings collection to an empty array (like clearcollInvoiceWarnings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoiceWarnings($overrideExisting = true)
    {
        if (null !== $this->collInvoiceWarnings && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceWarningTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoiceWarnings = new $collectionClassName;
        $this->collInvoiceWarnings->setModel('\API\Models\Invoice\InvoiceWarning');
    }

    /**
     * Gets an array of ChildInvoiceWarning objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoice is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInvoiceWarning[] List of ChildInvoiceWarning objects
     * @throws PropelException
     */
    public function getInvoiceWarnings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoiceWarningsPartial && !$this->isNew();
        if (null === $this->collInvoiceWarnings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoiceWarnings) {
                // return empty collection
                $this->initInvoiceWarnings();
            } else {
                $collInvoiceWarnings = ChildInvoiceWarningQuery::create(null, $criteria)
                    ->filterByInvoice($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoiceWarningsPartial && count($collInvoiceWarnings)) {
                        $this->initInvoiceWarnings(false);

                        foreach ($collInvoiceWarnings as $obj) {
                            if (false == $this->collInvoiceWarnings->contains($obj)) {
                                $this->collInvoiceWarnings->append($obj);
                            }
                        }

                        $this->collInvoiceWarningsPartial = true;
                    }

                    return $collInvoiceWarnings;
                }

                if ($partial && $this->collInvoiceWarnings) {
                    foreach ($this->collInvoiceWarnings as $obj) {
                        if ($obj->isNew()) {
                            $collInvoiceWarnings[] = $obj;
                        }
                    }
                }

                $this->collInvoiceWarnings = $collInvoiceWarnings;
                $this->collInvoiceWarningsPartial = false;
            }
        }

        return $this->collInvoiceWarnings;
    }

    /**
     * Sets a collection of ChildInvoiceWarning objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoiceWarnings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function setInvoiceWarnings(Collection $invoiceWarnings, ConnectionInterface $con = null)
    {
        /** @var ChildInvoiceWarning[] $invoiceWarningsToDelete */
        $invoiceWarningsToDelete = $this->getInvoiceWarnings(new Criteria(), $con)->diff($invoiceWarnings);


        $this->invoiceWarningsScheduledForDeletion = $invoiceWarningsToDelete;

        foreach ($invoiceWarningsToDelete as $invoiceWarningRemoved) {
            $invoiceWarningRemoved->setInvoice(null);
        }

        $this->collInvoiceWarnings = null;
        foreach ($invoiceWarnings as $invoiceWarning) {
            $this->addInvoiceWarning($invoiceWarning);
        }

        $this->collInvoiceWarnings = $invoiceWarnings;
        $this->collInvoiceWarningsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InvoiceWarning objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related InvoiceWarning objects.
     * @throws PropelException
     */
    public function countInvoiceWarnings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoiceWarningsPartial && !$this->isNew();
        if (null === $this->collInvoiceWarnings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoiceWarnings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoiceWarnings());
            }

            $query = ChildInvoiceWarningQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoice($this)
                ->count($con);
        }

        return count($this->collInvoiceWarnings);
    }

    /**
     * Method called to associate a ChildInvoiceWarning object to this object
     * through the ChildInvoiceWarning foreign key attribute.
     *
     * @param  ChildInvoiceWarning $l ChildInvoiceWarning
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function addInvoiceWarning(ChildInvoiceWarning $l)
    {
        if ($this->collInvoiceWarnings === null) {
            $this->initInvoiceWarnings();
            $this->collInvoiceWarningsPartial = true;
        }

        if (!$this->collInvoiceWarnings->contains($l)) {
            $this->doAddInvoiceWarning($l);

            if ($this->invoiceWarningsScheduledForDeletion and $this->invoiceWarningsScheduledForDeletion->contains($l)) {
                $this->invoiceWarningsScheduledForDeletion->remove($this->invoiceWarningsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInvoiceWarning $invoiceWarning The ChildInvoiceWarning object to add.
     */
    protected function doAddInvoiceWarning(ChildInvoiceWarning $invoiceWarning)
    {
        $this->collInvoiceWarnings[]= $invoiceWarning;
        $invoiceWarning->setInvoice($this);
    }

    /**
     * @param  ChildInvoiceWarning $invoiceWarning The ChildInvoiceWarning object to remove.
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function removeInvoiceWarning(ChildInvoiceWarning $invoiceWarning)
    {
        if ($this->getInvoiceWarnings()->contains($invoiceWarning)) {
            $pos = $this->collInvoiceWarnings->search($invoiceWarning);
            $this->collInvoiceWarnings->remove($pos);
            if (null === $this->invoiceWarningsScheduledForDeletion) {
                $this->invoiceWarningsScheduledForDeletion = clone $this->collInvoiceWarnings;
                $this->invoiceWarningsScheduledForDeletion->clear();
            }
            $this->invoiceWarningsScheduledForDeletion[]= clone $invoiceWarning;
            $invoiceWarning->setInvoice(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related InvoiceWarnings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoiceWarning[] List of ChildInvoiceWarning objects
     */
    public function getInvoiceWarningsJoinInvoiceWarningType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoiceWarningQuery::create(null, $criteria);
        $query->joinWith('InvoiceWarningType', $joinBehavior);

        return $this->getInvoiceWarnings($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEventContactRelatedByCustomerEventContactid) {
            $this->aEventContactRelatedByCustomerEventContactid->removeInvoiceRelatedByCustomerEventContactid($this);
        }
        if (null !== $this->aEventBankinformation) {
            $this->aEventBankinformation->removeInvoice($this);
        }
        if (null !== $this->aEventContactRelatedByEventContactid) {
            $this->aEventContactRelatedByEventContactid->removeInvoiceRelatedByEventContactid($this);
        }
        if (null !== $this->aInvoiceRelatedByCanceledInvoiceid) {
            $this->aInvoiceRelatedByCanceledInvoiceid->removeInvoiceRelatedByInvoiceid($this);
        }
        if (null !== $this->aInvoiceType) {
            $this->aInvoiceType->removeInvoice($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeInvoice($this);
        }
        $this->invoiceid = null;
        $this->invoice_typeid = null;
        $this->event_contactid = null;
        $this->cashier_userid = null;
        $this->event_bankinformationid = null;
        $this->customer_event_contactid = null;
        $this->canceled_invoiceid = null;
        $this->date = null;
        $this->amount = null;
        $this->maturity_date = null;
        $this->payment_finished = null;
        $this->amount_recieved = null;
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
            if ($this->collInvoicesRelatedByInvoiceid) {
                foreach ($this->collInvoicesRelatedByInvoiceid as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvoiceItems) {
                foreach ($this->collInvoiceItems as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPaymentRecieveds) {
                foreach ($this->collPaymentRecieveds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvoiceWarnings) {
                foreach ($this->collInvoiceWarnings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvoicesRelatedByInvoiceid = null;
        $this->collInvoiceItems = null;
        $this->collPaymentRecieveds = null;
        $this->collInvoiceWarnings = null;
        $this->aEventContactRelatedByCustomerEventContactid = null;
        $this->aEventBankinformation = null;
        $this->aEventContactRelatedByEventContactid = null;
        $this->aInvoiceRelatedByCanceledInvoiceid = null;
        $this->aInvoiceType = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(InvoiceTableMap::DEFAULT_STRING_FORMAT);
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
