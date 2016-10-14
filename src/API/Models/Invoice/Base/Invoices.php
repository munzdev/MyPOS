<?php

namespace API\Models\Invoice\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\Invoice\Invoices as ChildInvoices;
use API\Models\Invoice\InvoicesItems as ChildInvoicesItems;
use API\Models\Invoice\InvoicesItemsQuery as ChildInvoicesItemsQuery;
use API\Models\Invoice\InvoicesQuery as ChildInvoicesQuery;
use API\Models\Invoice\Map\InvoicesItemsTableMap;
use API\Models\Invoice\Map\InvoicesTableMap;
use API\Models\Payment\Payments;
use API\Models\Payment\PaymentsQuery;
use API\Models\Payment\Base\Payments as BasePayments;
use API\Models\Payment\Map\PaymentsTableMap;
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
 * Base class that represents a row from the 'invoices' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Invoice.Base
 */
abstract class Invoices implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Invoice\\Map\\InvoicesTableMap';


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
     * @var        Users
     */
    protected $aUsers;

    /**
     * @var        ObjectCollection|ChildInvoicesItems[] Collection to store aggregation of ChildInvoicesItems objects.
     */
    protected $collInvoicesItemss;
    protected $collInvoicesItemssPartial;

    /**
     * @var        ObjectCollection|Payments[] Collection to store aggregation of Payments objects.
     */
    protected $collPaymentss;
    protected $collPaymentssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInvoicesItems[]
     */
    protected $invoicesItemssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Payments[]
     */
    protected $paymentssScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Invoice\Base\Invoices object.
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
     * Compares this with another <code>Invoices</code> instance.  If
     * <code>obj</code> is an instance of <code>Invoices</code>, delegates to
     * <code>equals(Invoices)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Invoices The current object, for fluid interface
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
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     */
    public function setInvoiceid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->invoiceid !== $v) {
            $this->invoiceid = $v;
            $this->modifiedColumns[InvoicesTableMap::COL_INVOICEID] = true;
        }

        return $this;
    } // setInvoiceid()

    /**
     * Set the value of [cashier_userid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     */
    public function setCashierUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cashier_userid !== $v) {
            $this->cashier_userid = $v;
            $this->modifiedColumns[InvoicesTableMap::COL_CASHIER_USERID] = true;
        }

        if ($this->aUsers !== null && $this->aUsers->getUserid() !== $v) {
            $this->aUsers = null;
        }

        return $this;
    } // setCashierUserid()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->date->format("Y-m-d H:i:s.u")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvoicesTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Sets the value of [canceled] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     */
    public function setCanceled($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->canceled !== null || $dt !== null) {
            if ($this->canceled === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->canceled->format("Y-m-d H:i:s.u")) {
                $this->canceled = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InvoicesTableMap::COL_CANCELED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InvoicesTableMap::translateFieldName('Invoiceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->invoiceid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : InvoicesTableMap::translateFieldName('CashierUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cashier_userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : InvoicesTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : InvoicesTableMap::translateFieldName('Canceled', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->canceled = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = InvoicesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Invoice\\Invoices'), 0, $e);
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
        if ($this->aUsers !== null && $this->cashier_userid !== $this->aUsers->getUserid()) {
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
            $con = Propel::getServiceContainer()->getReadConnection(InvoicesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildInvoicesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUsers = null;
            $this->collInvoicesItemss = null;

            $this->collPaymentss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Invoices::setDeleted()
     * @see Invoices::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildInvoicesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesTableMap::DATABASE_NAME);
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
                InvoicesTableMap::addInstanceToPool($this);
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

            if ($this->invoicesItemssScheduledForDeletion !== null) {
                if (!$this->invoicesItemssScheduledForDeletion->isEmpty()) {
                    \API\Models\Invoice\InvoicesItemsQuery::create()
                        ->filterByPrimaryKeys($this->invoicesItemssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoicesItemssScheduledForDeletion = null;
                }
            }

            if ($this->collInvoicesItemss !== null) {
                foreach ($this->collInvoicesItemss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->paymentssScheduledForDeletion !== null) {
                if (!$this->paymentssScheduledForDeletion->isEmpty()) {
                    \API\Models\Payment\PaymentsQuery::create()
                        ->filterByPrimaryKeys($this->paymentssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->paymentssScheduledForDeletion = null;
                }
            }

            if ($this->collPaymentss !== null) {
                foreach ($this->collPaymentss as $referrerFK) {
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

        $this->modifiedColumns[InvoicesTableMap::COL_INVOICEID] = true;
        if (null !== $this->invoiceid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . InvoicesTableMap::COL_INVOICEID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(InvoicesTableMap::COL_INVOICEID)) {
            $modifiedColumns[':p' . $index++]  = 'invoiceid';
        }
        if ($this->isColumnModified(InvoicesTableMap::COL_CASHIER_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'cashier_userid';
        }
        if ($this->isColumnModified(InvoicesTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(InvoicesTableMap::COL_CANCELED)) {
            $modifiedColumns[':p' . $index++]  = 'canceled';
        }

        $sql = sprintf(
            'INSERT INTO invoices (%s) VALUES (%s)',
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
        $pos = InvoicesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['Invoices'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Invoices'][$this->hashCode()] = true;
        $keys = InvoicesTableMap::getFieldNames($keyType);
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
            if (null !== $this->collInvoicesItemss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoicesItemss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoices_itemss';
                        break;
                    default:
                        $key = 'InvoicesItemss';
                }

                $result[$key] = $this->collInvoicesItemss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPaymentss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'paymentss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'paymentss';
                        break;
                    default:
                        $key = 'Paymentss';
                }

                $result[$key] = $this->collPaymentss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Invoice\Invoices
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = InvoicesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Invoice\Invoices
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
        $keys = InvoicesTableMap::getFieldNames($keyType);

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
     * @return $this|\API\Models\Invoice\Invoices The current object, for fluid interface
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
        $criteria = new Criteria(InvoicesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(InvoicesTableMap::COL_INVOICEID)) {
            $criteria->add(InvoicesTableMap::COL_INVOICEID, $this->invoiceid);
        }
        if ($this->isColumnModified(InvoicesTableMap::COL_CASHIER_USERID)) {
            $criteria->add(InvoicesTableMap::COL_CASHIER_USERID, $this->cashier_userid);
        }
        if ($this->isColumnModified(InvoicesTableMap::COL_DATE)) {
            $criteria->add(InvoicesTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(InvoicesTableMap::COL_CANCELED)) {
            $criteria->add(InvoicesTableMap::COL_CANCELED, $this->canceled);
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
        $criteria = ChildInvoicesQuery::create();
        $criteria->add(InvoicesTableMap::COL_INVOICEID, $this->invoiceid);
        $criteria->add(InvoicesTableMap::COL_CASHIER_USERID, $this->cashier_userid);

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

        //relation fk_invoices_users1 to table users
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
     * @param      object $copyObj An object of \API\Models\Invoice\Invoices (or compatible) type.
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

            foreach ($this->getInvoicesItemss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoicesItems($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPaymentss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPayments($relObj->copy($deepCopy));
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
     * @return \API\Models\Invoice\Invoices Clone of current object.
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
     * Declares an association between this object and a Users object.
     *
     * @param  Users $v
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUsers(Users $v = null)
    {
        if ($v === null) {
            $this->setCashierUserid(NULL);
        } else {
            $this->setCashierUserid($v->getUserid());
        }

        $this->aUsers = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Users object, it will not be re-added.
        if ($v !== null) {
            $v->addInvoices($this);
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
        if ($this->aUsers === null && ($this->cashier_userid !== null)) {
            $this->aUsers = UsersQuery::create()->findPk($this->cashier_userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUsers->addInvoicess($this);
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
        if ('InvoicesItems' == $relationName) {
            return $this->initInvoicesItemss();
        }
        if ('Payments' == $relationName) {
            return $this->initPaymentss();
        }
    }

    /**
     * Clears out the collInvoicesItemss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoicesItemss()
     */
    public function clearInvoicesItemss()
    {
        $this->collInvoicesItemss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoicesItemss collection loaded partially.
     */
    public function resetPartialInvoicesItemss($v = true)
    {
        $this->collInvoicesItemssPartial = $v;
    }

    /**
     * Initializes the collInvoicesItemss collection.
     *
     * By default this just sets the collInvoicesItemss collection to an empty array (like clearcollInvoicesItemss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoicesItemss($overrideExisting = true)
    {
        if (null !== $this->collInvoicesItemss && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoicesItemsTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoicesItemss = new $collectionClassName;
        $this->collInvoicesItemss->setModel('\API\Models\Invoice\InvoicesItems');
    }

    /**
     * Gets an array of ChildInvoicesItems objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoices is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInvoicesItems[] List of ChildInvoicesItems objects
     * @throws PropelException
     */
    public function getInvoicesItemss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesItemssPartial && !$this->isNew();
        if (null === $this->collInvoicesItemss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoicesItemss) {
                // return empty collection
                $this->initInvoicesItemss();
            } else {
                $collInvoicesItemss = ChildInvoicesItemsQuery::create(null, $criteria)
                    ->filterByInvoices($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoicesItemssPartial && count($collInvoicesItemss)) {
                        $this->initInvoicesItemss(false);

                        foreach ($collInvoicesItemss as $obj) {
                            if (false == $this->collInvoicesItemss->contains($obj)) {
                                $this->collInvoicesItemss->append($obj);
                            }
                        }

                        $this->collInvoicesItemssPartial = true;
                    }

                    return $collInvoicesItemss;
                }

                if ($partial && $this->collInvoicesItemss) {
                    foreach ($this->collInvoicesItemss as $obj) {
                        if ($obj->isNew()) {
                            $collInvoicesItemss[] = $obj;
                        }
                    }
                }

                $this->collInvoicesItemss = $collInvoicesItemss;
                $this->collInvoicesItemssPartial = false;
            }
        }

        return $this->collInvoicesItemss;
    }

    /**
     * Sets a collection of ChildInvoicesItems objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoicesItemss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoices The current object (for fluent API support)
     */
    public function setInvoicesItemss(Collection $invoicesItemss, ConnectionInterface $con = null)
    {
        /** @var ChildInvoicesItems[] $invoicesItemssToDelete */
        $invoicesItemssToDelete = $this->getInvoicesItemss(new Criteria(), $con)->diff($invoicesItemss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->invoicesItemssScheduledForDeletion = clone $invoicesItemssToDelete;

        foreach ($invoicesItemssToDelete as $invoicesItemsRemoved) {
            $invoicesItemsRemoved->setInvoices(null);
        }

        $this->collInvoicesItemss = null;
        foreach ($invoicesItemss as $invoicesItems) {
            $this->addInvoicesItems($invoicesItems);
        }

        $this->collInvoicesItemss = $invoicesItemss;
        $this->collInvoicesItemssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related InvoicesItems objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related InvoicesItems objects.
     * @throws PropelException
     */
    public function countInvoicesItemss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesItemssPartial && !$this->isNew();
        if (null === $this->collInvoicesItemss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoicesItemss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoicesItemss());
            }

            $query = ChildInvoicesItemsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoices($this)
                ->count($con);
        }

        return count($this->collInvoicesItemss);
    }

    /**
     * Method called to associate a ChildInvoicesItems object to this object
     * through the ChildInvoicesItems foreign key attribute.
     *
     * @param  ChildInvoicesItems $l ChildInvoicesItems
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     */
    public function addInvoicesItems(ChildInvoicesItems $l)
    {
        if ($this->collInvoicesItemss === null) {
            $this->initInvoicesItemss();
            $this->collInvoicesItemssPartial = true;
        }

        if (!$this->collInvoicesItemss->contains($l)) {
            $this->doAddInvoicesItems($l);

            if ($this->invoicesItemssScheduledForDeletion and $this->invoicesItemssScheduledForDeletion->contains($l)) {
                $this->invoicesItemssScheduledForDeletion->remove($this->invoicesItemssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInvoicesItems $invoicesItems The ChildInvoicesItems object to add.
     */
    protected function doAddInvoicesItems(ChildInvoicesItems $invoicesItems)
    {
        $this->collInvoicesItemss[]= $invoicesItems;
        $invoicesItems->setInvoices($this);
    }

    /**
     * @param  ChildInvoicesItems $invoicesItems The ChildInvoicesItems object to remove.
     * @return $this|ChildInvoices The current object (for fluent API support)
     */
    public function removeInvoicesItems(ChildInvoicesItems $invoicesItems)
    {
        if ($this->getInvoicesItemss()->contains($invoicesItems)) {
            $pos = $this->collInvoicesItemss->search($invoicesItems);
            $this->collInvoicesItemss->remove($pos);
            if (null === $this->invoicesItemssScheduledForDeletion) {
                $this->invoicesItemssScheduledForDeletion = clone $this->collInvoicesItemss;
                $this->invoicesItemssScheduledForDeletion->clear();
            }
            $this->invoicesItemssScheduledForDeletion[]= clone $invoicesItems;
            $invoicesItems->setInvoices(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoices is new, it will return
     * an empty collection; or if this Invoices has previously
     * been saved, it will retrieve related InvoicesItemss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoices.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildInvoicesItems[] List of ChildInvoicesItems objects
     */
    public function getInvoicesItemssJoinOrdersDetails(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildInvoicesItemsQuery::create(null, $criteria);
        $query->joinWith('OrdersDetails', $joinBehavior);

        return $this->getInvoicesItemss($query, $con);
    }

    /**
     * Clears out the collPaymentss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPaymentss()
     */
    public function clearPaymentss()
    {
        $this->collPaymentss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPaymentss collection loaded partially.
     */
    public function resetPartialPaymentss($v = true)
    {
        $this->collPaymentssPartial = $v;
    }

    /**
     * Initializes the collPaymentss collection.
     *
     * By default this just sets the collPaymentss collection to an empty array (like clearcollPaymentss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPaymentss($overrideExisting = true)
    {
        if (null !== $this->collPaymentss && !$overrideExisting) {
            return;
        }

        $collectionClassName = PaymentsTableMap::getTableMap()->getCollectionClassName();

        $this->collPaymentss = new $collectionClassName;
        $this->collPaymentss->setModel('\API\Models\Payment\Payments');
    }

    /**
     * Gets an array of Payments objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInvoices is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Payments[] List of Payments objects
     * @throws PropelException
     */
    public function getPaymentss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentssPartial && !$this->isNew();
        if (null === $this->collPaymentss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPaymentss) {
                // return empty collection
                $this->initPaymentss();
            } else {
                $collPaymentss = PaymentsQuery::create(null, $criteria)
                    ->filterByInvoices($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPaymentssPartial && count($collPaymentss)) {
                        $this->initPaymentss(false);

                        foreach ($collPaymentss as $obj) {
                            if (false == $this->collPaymentss->contains($obj)) {
                                $this->collPaymentss->append($obj);
                            }
                        }

                        $this->collPaymentssPartial = true;
                    }

                    return $collPaymentss;
                }

                if ($partial && $this->collPaymentss) {
                    foreach ($this->collPaymentss as $obj) {
                        if ($obj->isNew()) {
                            $collPaymentss[] = $obj;
                        }
                    }
                }

                $this->collPaymentss = $collPaymentss;
                $this->collPaymentssPartial = false;
            }
        }

        return $this->collPaymentss;
    }

    /**
     * Sets a collection of Payments objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $paymentss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInvoices The current object (for fluent API support)
     */
    public function setPaymentss(Collection $paymentss, ConnectionInterface $con = null)
    {
        /** @var Payments[] $paymentssToDelete */
        $paymentssToDelete = $this->getPaymentss(new Criteria(), $con)->diff($paymentss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->paymentssScheduledForDeletion = clone $paymentssToDelete;

        foreach ($paymentssToDelete as $paymentsRemoved) {
            $paymentsRemoved->setInvoices(null);
        }

        $this->collPaymentss = null;
        foreach ($paymentss as $payments) {
            $this->addPayments($payments);
        }

        $this->collPaymentss = $paymentss;
        $this->collPaymentssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePayments objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePayments objects.
     * @throws PropelException
     */
    public function countPaymentss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentssPartial && !$this->isNew();
        if (null === $this->collPaymentss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPaymentss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPaymentss());
            }

            $query = PaymentsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInvoices($this)
                ->count($con);
        }

        return count($this->collPaymentss);
    }

    /**
     * Method called to associate a Payments object to this object
     * through the Payments foreign key attribute.
     *
     * @param  Payments $l Payments
     * @return $this|\API\Models\Invoice\Invoices The current object (for fluent API support)
     */
    public function addPayments(Payments $l)
    {
        if ($this->collPaymentss === null) {
            $this->initPaymentss();
            $this->collPaymentssPartial = true;
        }

        if (!$this->collPaymentss->contains($l)) {
            $this->doAddPayments($l);

            if ($this->paymentssScheduledForDeletion and $this->paymentssScheduledForDeletion->contains($l)) {
                $this->paymentssScheduledForDeletion->remove($this->paymentssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Payments $payments The Payments object to add.
     */
    protected function doAddPayments(Payments $payments)
    {
        $this->collPaymentss[]= $payments;
        $payments->setInvoices($this);
    }

    /**
     * @param  Payments $payments The Payments object to remove.
     * @return $this|ChildInvoices The current object (for fluent API support)
     */
    public function removePayments(Payments $payments)
    {
        if ($this->getPaymentss()->contains($payments)) {
            $pos = $this->collPaymentss->search($payments);
            $this->collPaymentss->remove($pos);
            if (null === $this->paymentssScheduledForDeletion) {
                $this->paymentssScheduledForDeletion = clone $this->collPaymentss;
                $this->paymentssScheduledForDeletion->clear();
            }
            $this->paymentssScheduledForDeletion[]= clone $payments;
            $payments->setInvoices(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Invoices is new, it will return
     * an empty collection; or if this Invoices has previously
     * been saved, it will retrieve related Paymentss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Invoices.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Payments[] List of Payments objects
     */
    public function getPaymentssJoinPaymentTypes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PaymentsQuery::create(null, $criteria);
        $query->joinWith('PaymentTypes', $joinBehavior);

        return $this->getPaymentss($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUsers) {
            $this->aUsers->removeInvoices($this);
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
            if ($this->collInvoicesItemss) {
                foreach ($this->collInvoicesItemss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPaymentss) {
                foreach ($this->collPaymentss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvoicesItemss = null;
        $this->collPaymentss = null;
        $this->aUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(InvoicesTableMap::DEFAULT_STRING_FORMAT);
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
