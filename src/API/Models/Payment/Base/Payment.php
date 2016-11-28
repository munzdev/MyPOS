<?php

namespace API\Models\Payment\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\Invoice\Invoice;
use API\Models\Invoice\InvoiceQuery;
use API\Models\Payment\Coupon as ChildCoupon;
use API\Models\Payment\CouponQuery as ChildCouponQuery;
use API\Models\Payment\Payment as ChildPayment;
use API\Models\Payment\PaymentCoupon as ChildPaymentCoupon;
use API\Models\Payment\PaymentCouponQuery as ChildPaymentCouponQuery;
use API\Models\Payment\PaymentQuery as ChildPaymentQuery;
use API\Models\Payment\PaymentType as ChildPaymentType;
use API\Models\Payment\PaymentTypeQuery as ChildPaymentTypeQuery;
use API\Models\Payment\Map\PaymentCouponTableMap;
use API\Models\Payment\Map\PaymentTableMap;
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
 * Base class that represents a row from the 'payment' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Payment.Base
 */
abstract class Payment implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Payment\\Map\\PaymentTableMap';


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
     * The value for the paymentid field.
     *
     * @var        int
     */
    protected $paymentid;

    /**
     * The value for the payment_typeid field.
     *
     * @var        int
     */
    protected $payment_typeid;

    /**
     * The value for the invoiceid field.
     *
     * @var        int
     */
    protected $invoiceid;

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
     * The value for the canceled field.
     *
     * @var        DateTime
     */
    protected $canceled;

    /**
     * @var        Invoice
     */
    protected $aInvoice;

    /**
     * @var        ChildPaymentType
     */
    protected $aPaymentType;

    /**
     * @var        ObjectCollection|ChildPaymentCoupon[] Collection to store aggregation of ChildPaymentCoupon objects.
     */
    protected $collPaymentCoupons;
    protected $collPaymentCouponsPartial;

    /**
     * @var        ObjectCollection|ChildCoupon[] Cross Collection to store aggregation of ChildCoupon objects.
     */
    protected $collCoupons;

    /**
     * @var bool
     */
    protected $collCouponsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCoupon[]
     */
    protected $couponsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPaymentCoupon[]
     */
    protected $paymentCouponsScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Payment\Base\Payment object.
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
     * Compares this with another <code>Payment</code> instance.  If
     * <code>obj</code> is an instance of <code>Payment</code>, delegates to
     * <code>equals(Payment)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Payment The current object, for fluid interface
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
     * Get the [paymentid] column value.
     *
     * @return int
     */
    public function getPaymentid()
    {
        return $this->paymentid;
    }

    /**
     * Get the [payment_typeid] column value.
     *
     * @return int
     */
    public function getPaymentTypeid()
    {
        return $this->payment_typeid;
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
     * Get the [optionally formatted] temporal [canceled] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCanceled($format = NULL)
    {
        if ($format === null) {
            return $this->canceled;
        } else {
            return $this->canceled instanceof \DateTimeInterface ? $this->canceled->format($format) : null;
        }
    }

    /**
     * Set the value of [paymentid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function setPaymentid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->paymentid !== $v) {
            $this->paymentid = $v;
            $this->modifiedColumns[PaymentTableMap::COL_PAYMENTID] = true;
        }

        return $this;
    } // setPaymentid()

    /**
     * Set the value of [payment_typeid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function setPaymentTypeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->payment_typeid !== $v) {
            $this->payment_typeid = $v;
            $this->modifiedColumns[PaymentTableMap::COL_PAYMENT_TYPEID] = true;
        }

        if ($this->aPaymentType !== null && $this->aPaymentType->getPaymentTypeid() !== $v) {
            $this->aPaymentType = null;
        }

        return $this;
    } // setPaymentTypeid()

    /**
     * Set the value of [invoiceid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function setInvoiceid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->invoiceid !== $v) {
            $this->invoiceid = $v;
            $this->modifiedColumns[PaymentTableMap::COL_INVOICEID] = true;
        }

        if ($this->aInvoice !== null && $this->aInvoice->getInvoiceid() !== $v) {
            $this->aInvoice = null;
        }

        return $this;
    } // setInvoiceid()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->date->format("Y-m-d H:i:s.u")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PaymentTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Set the value of [amount] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[PaymentTableMap::COL_AMOUNT] = true;
        }

        return $this;
    } // setAmount()

    /**
     * Sets the value of [canceled] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function setCanceled($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->canceled !== null || $dt !== null) {
            if ($this->canceled === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->canceled->format("Y-m-d H:i:s.u")) {
                $this->canceled = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PaymentTableMap::COL_CANCELED] = true;
            }
        } // if either are not null

        return $this;
    } // setCanceled()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PaymentTableMap::translateFieldName('Paymentid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->paymentid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PaymentTableMap::translateFieldName('PaymentTypeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->payment_typeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PaymentTableMap::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invoiceid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PaymentTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PaymentTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PaymentTableMap::translateFieldName('Canceled', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->canceled = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = PaymentTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Payment\\Payment'), 0, $e);
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
        if ($this->aPaymentType !== null && $this->payment_typeid !== $this->aPaymentType->getPaymentTypeid()) {
            $this->aPaymentType = null;
        }
        if ($this->aInvoice !== null && $this->invoiceid !== $this->aInvoice->getInvoiceid()) {
            $this->aInvoice = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(PaymentTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPaymentQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aInvoice = null;
            $this->aPaymentType = null;
            $this->collPaymentCoupons = null;

            $this->collCoupons = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Payment::setDeleted()
     * @see Payment::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPaymentQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
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
                PaymentTableMap::addInstanceToPool($this);
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

            if ($this->aInvoice !== null) {
                if ($this->aInvoice->isModified() || $this->aInvoice->isNew()) {
                    $affectedRows += $this->aInvoice->save($con);
                }
                $this->setInvoice($this->aInvoice);
            }

            if ($this->aPaymentType !== null) {
                if ($this->aPaymentType->isModified() || $this->aPaymentType->isNew()) {
                    $affectedRows += $this->aPaymentType->save($con);
                }
                $this->setPaymentType($this->aPaymentType);
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

            if ($this->couponsScheduledForDeletion !== null) {
                if (!$this->couponsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->couponsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getPaymentid();
                        $entryPk[0] = $entry->getCouponid();
                        $pks[] = $entryPk;
                    }

                    \API\Models\Payment\PaymentCouponQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->couponsScheduledForDeletion = null;
                }

            }

            if ($this->collCoupons) {
                foreach ($this->collCoupons as $coupon) {
                    if (!$coupon->isDeleted() && ($coupon->isNew() || $coupon->isModified())) {
                        $coupon->save($con);
                    }
                }
            }


            if ($this->paymentCouponsScheduledForDeletion !== null) {
                if (!$this->paymentCouponsScheduledForDeletion->isEmpty()) {
                    \API\Models\Payment\PaymentCouponQuery::create()
                        ->filterByPrimaryKeys($this->paymentCouponsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->paymentCouponsScheduledForDeletion = null;
                }
            }

            if ($this->collPaymentCoupons !== null) {
                foreach ($this->collPaymentCoupons as $referrerFK) {
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PaymentTableMap::COL_PAYMENTID)) {
            $modifiedColumns[':p' . $index++]  = 'paymentid';
        }
        if ($this->isColumnModified(PaymentTableMap::COL_PAYMENT_TYPEID)) {
            $modifiedColumns[':p' . $index++]  = 'payment_typeid';
        }
        if ($this->isColumnModified(PaymentTableMap::COL_INVOICEID)) {
            $modifiedColumns[':p' . $index++]  = 'invoiceid';
        }
        if ($this->isColumnModified(PaymentTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(PaymentTableMap::COL_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'amount';
        }
        if ($this->isColumnModified(PaymentTableMap::COL_CANCELED)) {
            $modifiedColumns[':p' . $index++]  = 'canceled';
        }

        $sql = sprintf(
            'INSERT INTO payment (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'paymentid':
                        $stmt->bindValue($identifier, $this->paymentid, PDO::PARAM_INT);
                        break;
                    case 'payment_typeid':
                        $stmt->bindValue($identifier, $this->payment_typeid, PDO::PARAM_INT);
                        break;
                    case 'invoiceid':
                        $stmt->bindValue($identifier, $this->invoiceid, PDO::PARAM_INT);
                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'amount':
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_STR);
                        break;
                    case 'canceled':
                        $stmt->bindValue($identifier, $this->canceled ? $this->canceled->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

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
        $pos = PaymentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getPaymentid();
                break;
            case 1:
                return $this->getPaymentTypeid();
                break;
            case 2:
                return $this->getInvoiceid();
                break;
            case 3:
                return $this->getDate();
                break;
            case 4:
                return $this->getAmount();
                break;
            case 5:
                return $this->getCanceled();
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

        if (isset($alreadyDumpedObjects['Payment'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Payment'][$this->hashCode()] = true;
        $keys = PaymentTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getPaymentid(),
            $keys[1] => $this->getPaymentTypeid(),
            $keys[2] => $this->getInvoiceid(),
            $keys[3] => $this->getDate(),
            $keys[4] => $this->getAmount(),
            $keys[5] => $this->getCanceled(),
        );
        if ($result[$keys[3]] instanceof \DateTime) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aInvoice) {

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

                $result[$key] = $this->aInvoice->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aPaymentType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'paymentType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'payment_type';
                        break;
                    default:
                        $key = 'PaymentType';
                }

                $result[$key] = $this->aPaymentType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPaymentCoupons) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'paymentCoupons';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'payment_coupons';
                        break;
                    default:
                        $key = 'PaymentCoupons';
                }

                $result[$key] = $this->collPaymentCoupons->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Payment\Payment
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PaymentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Payment\Payment
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setPaymentid($value);
                break;
            case 1:
                $this->setPaymentTypeid($value);
                break;
            case 2:
                $this->setInvoiceid($value);
                break;
            case 3:
                $this->setDate($value);
                break;
            case 4:
                $this->setAmount($value);
                break;
            case 5:
                $this->setCanceled($value);
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
        $keys = PaymentTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setPaymentid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setPaymentTypeid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setInvoiceid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDate($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAmount($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCanceled($arr[$keys[5]]);
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
     * @return $this|\API\Models\Payment\Payment The current object, for fluid interface
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
        $criteria = new Criteria(PaymentTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PaymentTableMap::COL_PAYMENTID)) {
            $criteria->add(PaymentTableMap::COL_PAYMENTID, $this->paymentid);
        }
        if ($this->isColumnModified(PaymentTableMap::COL_PAYMENT_TYPEID)) {
            $criteria->add(PaymentTableMap::COL_PAYMENT_TYPEID, $this->payment_typeid);
        }
        if ($this->isColumnModified(PaymentTableMap::COL_INVOICEID)) {
            $criteria->add(PaymentTableMap::COL_INVOICEID, $this->invoiceid);
        }
        if ($this->isColumnModified(PaymentTableMap::COL_DATE)) {
            $criteria->add(PaymentTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(PaymentTableMap::COL_AMOUNT)) {
            $criteria->add(PaymentTableMap::COL_AMOUNT, $this->amount);
        }
        if ($this->isColumnModified(PaymentTableMap::COL_CANCELED)) {
            $criteria->add(PaymentTableMap::COL_CANCELED, $this->canceled);
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
        $criteria = ChildPaymentQuery::create();
        $criteria->add(PaymentTableMap::COL_PAYMENTID, $this->paymentid);

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
        $validPk = null !== $this->getPaymentid();

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
        return $this->getPaymentid();
    }

    /**
     * Generic method to set the primary key (paymentid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setPaymentid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getPaymentid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Payment\Payment (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setPaymentid($this->getPaymentid());
        $copyObj->setPaymentTypeid($this->getPaymentTypeid());
        $copyObj->setInvoiceid($this->getInvoiceid());
        $copyObj->setDate($this->getDate());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setCanceled($this->getCanceled());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPaymentCoupons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPaymentCoupon($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return \API\Models\Payment\Payment Clone of current object.
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
     * Declares an association between this object and a Invoice object.
     *
     * @param  Invoice $v
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setInvoice(Invoice $v = null)
    {
        if ($v === null) {
            $this->setInvoiceid(NULL);
        } else {
            $this->setInvoiceid($v->getInvoiceid());
        }

        $this->aInvoice = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Invoice object, it will not be re-added.
        if ($v !== null) {
            $v->addPayment($this);
        }


        return $this;
    }


    /**
     * Get the associated Invoice object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Invoice The associated Invoice object.
     * @throws PropelException
     */
    public function getInvoice(ConnectionInterface $con = null)
    {
        if ($this->aInvoice === null && ($this->invoiceid !== null)) {
            $this->aInvoice = InvoiceQuery::create()->findPk($this->invoiceid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aInvoice->addPayments($this);
             */
        }

        return $this->aInvoice;
    }

    /**
     * Declares an association between this object and a ChildPaymentType object.
     *
     * @param  ChildPaymentType $v
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPaymentType(ChildPaymentType $v = null)
    {
        if ($v === null) {
            $this->setPaymentTypeid(NULL);
        } else {
            $this->setPaymentTypeid($v->getPaymentTypeid());
        }

        $this->aPaymentType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPaymentType object, it will not be re-added.
        if ($v !== null) {
            $v->addPayment($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPaymentType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPaymentType The associated ChildPaymentType object.
     * @throws PropelException
     */
    public function getPaymentType(ConnectionInterface $con = null)
    {
        if ($this->aPaymentType === null && ($this->payment_typeid !== null)) {
            $this->aPaymentType = ChildPaymentTypeQuery::create()->findPk($this->payment_typeid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPaymentType->addPayments($this);
             */
        }

        return $this->aPaymentType;
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
        if ('PaymentCoupon' == $relationName) {
            return $this->initPaymentCoupons();
        }
    }

    /**
     * Clears out the collPaymentCoupons collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPaymentCoupons()
     */
    public function clearPaymentCoupons()
    {
        $this->collPaymentCoupons = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPaymentCoupons collection loaded partially.
     */
    public function resetPartialPaymentCoupons($v = true)
    {
        $this->collPaymentCouponsPartial = $v;
    }

    /**
     * Initializes the collPaymentCoupons collection.
     *
     * By default this just sets the collPaymentCoupons collection to an empty array (like clearcollPaymentCoupons());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPaymentCoupons($overrideExisting = true)
    {
        if (null !== $this->collPaymentCoupons && !$overrideExisting) {
            return;
        }

        $collectionClassName = PaymentCouponTableMap::getTableMap()->getCollectionClassName();

        $this->collPaymentCoupons = new $collectionClassName;
        $this->collPaymentCoupons->setModel('\API\Models\Payment\PaymentCoupon');
    }

    /**
     * Gets an array of ChildPaymentCoupon objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPayment is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPaymentCoupon[] List of ChildPaymentCoupon objects
     * @throws PropelException
     */
    public function getPaymentCoupons(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentCouponsPartial && !$this->isNew();
        if (null === $this->collPaymentCoupons || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPaymentCoupons) {
                // return empty collection
                $this->initPaymentCoupons();
            } else {
                $collPaymentCoupons = ChildPaymentCouponQuery::create(null, $criteria)
                    ->filterByPayment($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPaymentCouponsPartial && count($collPaymentCoupons)) {
                        $this->initPaymentCoupons(false);

                        foreach ($collPaymentCoupons as $obj) {
                            if (false == $this->collPaymentCoupons->contains($obj)) {
                                $this->collPaymentCoupons->append($obj);
                            }
                        }

                        $this->collPaymentCouponsPartial = true;
                    }

                    return $collPaymentCoupons;
                }

                if ($partial && $this->collPaymentCoupons) {
                    foreach ($this->collPaymentCoupons as $obj) {
                        if ($obj->isNew()) {
                            $collPaymentCoupons[] = $obj;
                        }
                    }
                }

                $this->collPaymentCoupons = $collPaymentCoupons;
                $this->collPaymentCouponsPartial = false;
            }
        }

        return $this->collPaymentCoupons;
    }

    /**
     * Sets a collection of ChildPaymentCoupon objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $paymentCoupons A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPayment The current object (for fluent API support)
     */
    public function setPaymentCoupons(Collection $paymentCoupons, ConnectionInterface $con = null)
    {
        /** @var ChildPaymentCoupon[] $paymentCouponsToDelete */
        $paymentCouponsToDelete = $this->getPaymentCoupons(new Criteria(), $con)->diff($paymentCoupons);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->paymentCouponsScheduledForDeletion = clone $paymentCouponsToDelete;

        foreach ($paymentCouponsToDelete as $paymentCouponRemoved) {
            $paymentCouponRemoved->setPayment(null);
        }

        $this->collPaymentCoupons = null;
        foreach ($paymentCoupons as $paymentCoupon) {
            $this->addPaymentCoupon($paymentCoupon);
        }

        $this->collPaymentCoupons = $paymentCoupons;
        $this->collPaymentCouponsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PaymentCoupon objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PaymentCoupon objects.
     * @throws PropelException
     */
    public function countPaymentCoupons(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentCouponsPartial && !$this->isNew();
        if (null === $this->collPaymentCoupons || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPaymentCoupons) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPaymentCoupons());
            }

            $query = ChildPaymentCouponQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPayment($this)
                ->count($con);
        }

        return count($this->collPaymentCoupons);
    }

    /**
     * Method called to associate a ChildPaymentCoupon object to this object
     * through the ChildPaymentCoupon foreign key attribute.
     *
     * @param  ChildPaymentCoupon $l ChildPaymentCoupon
     * @return $this|\API\Models\Payment\Payment The current object (for fluent API support)
     */
    public function addPaymentCoupon(ChildPaymentCoupon $l)
    {
        if ($this->collPaymentCoupons === null) {
            $this->initPaymentCoupons();
            $this->collPaymentCouponsPartial = true;
        }

        if (!$this->collPaymentCoupons->contains($l)) {
            $this->doAddPaymentCoupon($l);

            if ($this->paymentCouponsScheduledForDeletion and $this->paymentCouponsScheduledForDeletion->contains($l)) {
                $this->paymentCouponsScheduledForDeletion->remove($this->paymentCouponsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPaymentCoupon $paymentCoupon The ChildPaymentCoupon object to add.
     */
    protected function doAddPaymentCoupon(ChildPaymentCoupon $paymentCoupon)
    {
        $this->collPaymentCoupons[]= $paymentCoupon;
        $paymentCoupon->setPayment($this);
    }

    /**
     * @param  ChildPaymentCoupon $paymentCoupon The ChildPaymentCoupon object to remove.
     * @return $this|ChildPayment The current object (for fluent API support)
     */
    public function removePaymentCoupon(ChildPaymentCoupon $paymentCoupon)
    {
        if ($this->getPaymentCoupons()->contains($paymentCoupon)) {
            $pos = $this->collPaymentCoupons->search($paymentCoupon);
            $this->collPaymentCoupons->remove($pos);
            if (null === $this->paymentCouponsScheduledForDeletion) {
                $this->paymentCouponsScheduledForDeletion = clone $this->collPaymentCoupons;
                $this->paymentCouponsScheduledForDeletion->clear();
            }
            $this->paymentCouponsScheduledForDeletion[]= clone $paymentCoupon;
            $paymentCoupon->setPayment(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Payment is new, it will return
     * an empty collection; or if this Payment has previously
     * been saved, it will retrieve related PaymentCoupons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Payment.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPaymentCoupon[] List of ChildPaymentCoupon objects
     */
    public function getPaymentCouponsJoinCoupon(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPaymentCouponQuery::create(null, $criteria);
        $query->joinWith('Coupon', $joinBehavior);

        return $this->getPaymentCoupons($query, $con);
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
     * Initializes the collCoupons crossRef collection.
     *
     * By default this just sets the collCoupons collection to an empty collection (like clearCoupons());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initCoupons()
    {
        $collectionClassName = PaymentCouponTableMap::getTableMap()->getCollectionClassName();

        $this->collCoupons = new $collectionClassName;
        $this->collCouponsPartial = true;
        $this->collCoupons->setModel('\API\Models\Payment\Coupon');
    }

    /**
     * Checks if the collCoupons collection is loaded.
     *
     * @return bool
     */
    public function isCouponsLoaded()
    {
        return null !== $this->collCoupons;
    }

    /**
     * Gets a collection of ChildCoupon objects related by a many-to-many relationship
     * to the current object by way of the payment_coupon cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPayment is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildCoupon[] List of ChildCoupon objects
     */
    public function getCoupons(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCouponsPartial && !$this->isNew();
        if (null === $this->collCoupons || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCoupons) {
                    $this->initCoupons();
                }
            } else {

                $query = ChildCouponQuery::create(null, $criteria)
                    ->filterByPayment($this);
                $collCoupons = $query->find($con);
                if (null !== $criteria) {
                    return $collCoupons;
                }

                if ($partial && $this->collCoupons) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collCoupons as $obj) {
                        if (!$collCoupons->contains($obj)) {
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
     * Sets a collection of Coupon objects related by a many-to-many relationship
     * to the current object by way of the payment_coupon cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $coupons A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildPayment The current object (for fluent API support)
     */
    public function setCoupons(Collection $coupons, ConnectionInterface $con = null)
    {
        $this->clearCoupons();
        $currentCoupons = $this->getCoupons();

        $couponsScheduledForDeletion = $currentCoupons->diff($coupons);

        foreach ($couponsScheduledForDeletion as $toDelete) {
            $this->removeCoupon($toDelete);
        }

        foreach ($coupons as $coupon) {
            if (!$currentCoupons->contains($coupon)) {
                $this->doAddCoupon($coupon);
            }
        }

        $this->collCouponsPartial = false;
        $this->collCoupons = $coupons;

        return $this;
    }

    /**
     * Gets the number of Coupon objects related by a many-to-many relationship
     * to the current object by way of the payment_coupon cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Coupon objects
     */
    public function countCoupons(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCouponsPartial && !$this->isNew();
        if (null === $this->collCoupons || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCoupons) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getCoupons());
                }

                $query = ChildCouponQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByPayment($this)
                    ->count($con);
            }
        } else {
            return count($this->collCoupons);
        }
    }

    /**
     * Associate a ChildCoupon to this object
     * through the payment_coupon cross reference table.
     *
     * @param ChildCoupon $coupon
     * @return ChildPayment The current object (for fluent API support)
     */
    public function addCoupon(ChildCoupon $coupon)
    {
        if ($this->collCoupons === null) {
            $this->initCoupons();
        }

        if (!$this->getCoupons()->contains($coupon)) {
            // only add it if the **same** object is not already associated
            $this->collCoupons->push($coupon);
            $this->doAddCoupon($coupon);
        }

        return $this;
    }

    /**
     *
     * @param ChildCoupon $coupon
     */
    protected function doAddCoupon(ChildCoupon $coupon)
    {
        $paymentCoupon = new ChildPaymentCoupon();

        $paymentCoupon->setCoupon($coupon);

        $paymentCoupon->setPayment($this);

        $this->addPaymentCoupon($paymentCoupon);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$coupon->isPaymentsLoaded()) {
            $coupon->initPayments();
            $coupon->getPayments()->push($this);
        } elseif (!$coupon->getPayments()->contains($this)) {
            $coupon->getPayments()->push($this);
        }

    }

    /**
     * Remove coupon of this object
     * through the payment_coupon cross reference table.
     *
     * @param ChildCoupon $coupon
     * @return ChildPayment The current object (for fluent API support)
     */
    public function removeCoupon(ChildCoupon $coupon)
    {
        if ($this->getCoupons()->contains($coupon)) { $paymentCoupon = new ChildPaymentCoupon();

            $paymentCoupon->setCoupon($coupon);
            if ($coupon->isPaymentsLoaded()) {
                //remove the back reference if available
                $coupon->getPayments()->removeObject($this);
            }

            $paymentCoupon->setPayment($this);
            $this->removePaymentCoupon(clone $paymentCoupon);
            $paymentCoupon->clear();

            $this->collCoupons->remove($this->collCoupons->search($coupon));

            if (null === $this->couponsScheduledForDeletion) {
                $this->couponsScheduledForDeletion = clone $this->collCoupons;
                $this->couponsScheduledForDeletion->clear();
            }

            $this->couponsScheduledForDeletion->push($coupon);
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
        if (null !== $this->aInvoice) {
            $this->aInvoice->removePayment($this);
        }
        if (null !== $this->aPaymentType) {
            $this->aPaymentType->removePayment($this);
        }
        $this->paymentid = null;
        $this->payment_typeid = null;
        $this->invoiceid = null;
        $this->date = null;
        $this->amount = null;
        $this->canceled = null;
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
            if ($this->collPaymentCoupons) {
                foreach ($this->collPaymentCoupons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCoupons) {
                foreach ($this->collCoupons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPaymentCoupons = null;
        $this->collCoupons = null;
        $this->aInvoice = null;
        $this->aPaymentType = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PaymentTableMap::DEFAULT_STRING_FORMAT);
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
