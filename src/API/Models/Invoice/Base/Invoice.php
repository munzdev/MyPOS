<?php

namespace API\Models\Invoice\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\Invoice\Invoice as ChildInvoice;
use API\Models\Invoice\InvoiceItem as ChildInvoiceItem;
use API\Models\Invoice\InvoiceItemQuery as ChildInvoiceItemQuery;
use API\Models\Invoice\InvoiceQuery as ChildInvoiceQuery;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\Invoice\Map\InvoiceTableMap;
use API\Models\Payment\Payment;
use API\Models\Payment\PaymentQuery;
use API\Models\Payment\Base\Payment as BasePayment;
use API\Models\Payment\Map\PaymentTableMap;
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
     * The value for the cashier_userid field.
     *
     * @var        int
     */
    protected $cashier_userid;

    /**
     * The value for the date field.
     *
     * @var        DateTime
     */
    protected $date;

    /**
     * The value for the canceled field.
     *
     * @var        DateTime
     */
    protected $canceled;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildInvoiceItem[] Collection to store aggregation of ChildInvoiceItem objects.
     */
    protected $collInvoiceItems;
    protected $collInvoiceItemsPartial;

    /**
     * @var        ObjectCollection|Payment[] Collection to store aggregation of Payment objects.
     */
    protected $collPayments;
    protected $collPaymentsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInvoiceItem[]
     */
    protected $invoiceItemsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Payment[]
     */
    protected $paymentsScheduledForDeletion = null;

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
     * Get the [cashier_userid] column value.
     *
     * @return int
     */
    public function getCashierUserid()
    {
        return $this->cashier_userid;
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
     * Sets the value of [canceled] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function setCanceled($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->canceled !== null || $dt !== null) {
            if ($this->canceled === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->canceled->format("Y-m-d H:i:s.u")) {
                $this->canceled = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvoiceTableMap::COL_CANCELED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InvoiceTableMap::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invoiceid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : InvoiceTableMap::translateFieldName('CashierUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cashier_userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : InvoiceTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : InvoiceTableMap::translateFieldName('Canceled', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->canceled = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = InvoiceTableMap::NUM_HYDRATE_COLUMNS.

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
        if ($this->aUser !== null && $this->cashier_userid !== $this->aUser->getUserid()) {
            $this->aUser = null;
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

            $this->aUser = null;
            $this->collInvoiceItems = null;

            $this->collPayments = null;

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

            if ($this->paymentsScheduledForDeletion !== null) {
                if (!$this->paymentsScheduledForDeletion->isEmpty()) {
                    \API\Models\Payment\PaymentQuery::create()
                        ->filterByPrimaryKeys($this->paymentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->paymentsScheduledForDeletion = null;
                }
            }

            if ($this->collPayments !== null) {
                foreach ($this->collPayments as $referrerFK) {
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
        if ($this->isColumnModified(InvoiceTableMap::COL_CASHIER_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'cashier_userid';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CANCELED)) {
            $modifiedColumns[':p' . $index++]  = 'canceled';
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
                    case 'cashier_userid':
                        $stmt->bindValue($identifier, $this->cashier_userid, PDO::PARAM_INT);
                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
                return $this->getCashierUserid();
                break;
            case 2:
                return $this->getDate();
                break;
            case 3:
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

        if (isset($alreadyDumpedObjects['Invoice'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Invoice'][$this->hashCode()] = true;
        $keys = InvoiceTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getInvoiceid(),
            $keys[1] => $this->getCashierUserid(),
            $keys[2] => $this->getDate(),
            $keys[3] => $this->getCanceled(),
        );
        if ($result[$keys[2]] instanceof \DateTime) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        if ($result[$keys[3]] instanceof \DateTime) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
            if (null !== $this->collPayments) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'payments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'payments';
                        break;
                    default:
                        $key = 'Payments';
                }

                $result[$key] = $this->collPayments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
                $this->setCashierUserid($value);
                break;
            case 2:
                $this->setDate($value);
                break;
            case 3:
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
        $keys = InvoiceTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setInvoiceid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setCashierUserid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDate($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCanceled($arr[$keys[3]]);
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
        if ($this->isColumnModified(InvoiceTableMap::COL_CASHIER_USERID)) {
            $criteria->add(InvoiceTableMap::COL_CASHIER_USERID, $this->cashier_userid);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_DATE)) {
            $criteria->add(InvoiceTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(InvoiceTableMap::COL_CANCELED)) {
            $criteria->add(InvoiceTableMap::COL_CANCELED, $this->canceled);
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
        $criteria->add(InvoiceTableMap::COL_CASHIER_USERID, $this->cashier_userid);

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
        $validPk = null !== $this->getInvoiceid() &&
            null !== $this->getCashierUserid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_invoices_users1 to table user
        if ($this->aUser && $hash = spl_object_hash($this->aUser)) {
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
        $pks[0] = $this->getInvoiceid();
        $pks[1] = $this->getCashierUserid();

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
        $this->setInvoiceid($keys[0]);
        $this->setCashierUserid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getInvoiceid()) && (null === $this->getCashierUserid());
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
        $copyObj->setCashierUserid($this->getCashierUserid());
        $copyObj->setDate($this->getDate());
        $copyObj->setCanceled($this->getCanceled());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getInvoiceItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceItem($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPayments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPayment($relObj->copy($deepCopy));
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
        if ('InvoiceItem' == $relationName) {
            return $this->initInvoiceItems();
        }
        if ('Payment' == $relationName) {
            return $this->initPayments();
        }
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


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->invoiceItemsScheduledForDeletion = clone $invoiceItemsToDelete;

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
     * Clears out the collPayments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPayments()
     */
    public function clearPayments()
    {
        $this->collPayments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPayments collection loaded partially.
     */
    public function resetPartialPayments($v = true)
    {
        $this->collPaymentsPartial = $v;
    }

    /**
     * Initializes the collPayments collection.
     *
     * By default this just sets the collPayments collection to an empty array (like clearcollPayments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPayments($overrideExisting = true)
    {
        if (null !== $this->collPayments && !$overrideExisting) {
            return;
        }

        $collectionClassName = PaymentTableMap::getTableMap()->getCollectionClassName();

        $this->collPayments = new $collectionClassName;
        $this->collPayments->setModel('\API\Models\Payment\Payment');
    }

    /**
     * Gets an array of Payment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoice is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Payment[] List of Payment objects
     * @throws PropelException
     */
    public function getPayments(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentsPartial && !$this->isNew();
        if (null === $this->collPayments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPayments) {
                // return empty collection
                $this->initPayments();
            } else {
                $collPayments = PaymentQuery::create(null, $criteria)
                    ->filterByInvoice($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPaymentsPartial && count($collPayments)) {
                        $this->initPayments(false);

                        foreach ($collPayments as $obj) {
                            if (false == $this->collPayments->contains($obj)) {
                                $this->collPayments->append($obj);
                            }
                        }

                        $this->collPaymentsPartial = true;
                    }

                    return $collPayments;
                }

                if ($partial && $this->collPayments) {
                    foreach ($this->collPayments as $obj) {
                        if ($obj->isNew()) {
                            $collPayments[] = $obj;
                        }
                    }
                }

                $this->collPayments = $collPayments;
                $this->collPaymentsPartial = false;
            }
        }

        return $this->collPayments;
    }

    /**
     * Sets a collection of Payment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $payments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function setPayments(Collection $payments, ConnectionInterface $con = null)
    {
        /** @var Payment[] $paymentsToDelete */
        $paymentsToDelete = $this->getPayments(new Criteria(), $con)->diff($payments);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->paymentsScheduledForDeletion = clone $paymentsToDelete;

        foreach ($paymentsToDelete as $paymentRemoved) {
            $paymentRemoved->setInvoice(null);
        }

        $this->collPayments = null;
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }

        $this->collPayments = $payments;
        $this->collPaymentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePayment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePayment objects.
     * @throws PropelException
     */
    public function countPayments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentsPartial && !$this->isNew();
        if (null === $this->collPayments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPayments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPayments());
            }

            $query = PaymentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoice($this)
                ->count($con);
        }

        return count($this->collPayments);
    }

    /**
     * Method called to associate a Payment object to this object
     * through the Payment foreign key attribute.
     *
     * @param  Payment $l Payment
     * @return $this|\API\Models\Invoice\Invoice The current object (for fluent API support)
     */
    public function addPayment(Payment $l)
    {
        if ($this->collPayments === null) {
            $this->initPayments();
            $this->collPaymentsPartial = true;
        }

        if (!$this->collPayments->contains($l)) {
            $this->doAddPayment($l);

            if ($this->paymentsScheduledForDeletion and $this->paymentsScheduledForDeletion->contains($l)) {
                $this->paymentsScheduledForDeletion->remove($this->paymentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Payment $payment The Payment object to add.
     */
    protected function doAddPayment(Payment $payment)
    {
        $this->collPayments[]= $payment;
        $payment->setInvoice($this);
    }

    /**
     * @param  Payment $payment The Payment object to remove.
     * @return $this|ChildInvoice The current object (for fluent API support)
     */
    public function removePayment(Payment $payment)
    {
        if ($this->getPayments()->contains($payment)) {
            $pos = $this->collPayments->search($payment);
            $this->collPayments->remove($pos);
            if (null === $this->paymentsScheduledForDeletion) {
                $this->paymentsScheduledForDeletion = clone $this->collPayments;
                $this->paymentsScheduledForDeletion->clear();
            }
            $this->paymentsScheduledForDeletion[]= clone $payment;
            $payment->setInvoice(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoice is new, it will return
     * an empty collection; or if this Invoice has previously
     * been saved, it will retrieve related Payments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoice.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Payment[] List of Payment objects
     */
    public function getPaymentsJoinPaymentType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PaymentQuery::create(null, $criteria);
        $query->joinWith('PaymentType', $joinBehavior);

        return $this->getPayments($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeInvoice($this);
        }
        $this->invoiceid = null;
        $this->cashier_userid = null;
        $this->date = null;
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
            if ($this->collInvoiceItems) {
                foreach ($this->collInvoiceItems as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPayments) {
                foreach ($this->collPayments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvoiceItems = null;
        $this->collPayments = null;
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
