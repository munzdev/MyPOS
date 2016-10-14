<?php

namespace API\Models\OIP\Base;

use \Exception;
use \PDO;
use API\Models\OIP\DistributionsGivingOuts as ChildDistributionsGivingOuts;
use API\Models\OIP\DistributionsGivingOutsQuery as ChildDistributionsGivingOutsQuery;
use API\Models\OIP\OrdersInProgress as ChildOrdersInProgress;
use API\Models\OIP\OrdersInProgressQuery as ChildOrdersInProgressQuery;
use API\Models\OIP\OrdersInProgressRecievedQuery as ChildOrdersInProgressRecievedQuery;
use API\Models\OIP\Map\OrdersInProgressRecievedTableMap;
use API\Models\Ordering\OrdersDetails;
use API\Models\Ordering\OrdersDetailsQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'orders_in_progress_recieved' table.
 *
 *
 *
 * @package    propel.generator.API.Models.OIP.Base
 */
abstract class OrdersInProgressRecieved implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\OIP\\Map\\OrdersInProgressRecievedTableMap';


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
     * The value for the orders_in_progress_recievedid field.
     *
     * @var        int
     */
    protected $orders_in_progress_recievedid;

    /**
     * The value for the orders_detailid field.
     *
     * @var        int
     */
    protected $orders_detailid;

    /**
     * The value for the orders_in_progressid field.
     *
     * @var        int
     */
    protected $orders_in_progressid;

    /**
     * The value for the distributions_giving_outid field.
     *
     * @var        int
     */
    protected $distributions_giving_outid;

    /**
     * The value for the amount field.
     *
     * @var        int
     */
    protected $amount;

    /**
     * @var        OrdersDetails
     */
    protected $aOrdersDetails;

    /**
     * @var        ChildOrdersInProgress
     */
    protected $aOrdersInProgress;

    /**
     * @var        ChildDistributionsGivingOuts
     */
    protected $aDistributionsGivingOuts;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of API\Models\OIP\Base\OrdersInProgressRecieved object.
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
     * Compares this with another <code>OrdersInProgressRecieved</code> instance.  If
     * <code>obj</code> is an instance of <code>OrdersInProgressRecieved</code>, delegates to
     * <code>equals(OrdersInProgressRecieved)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|OrdersInProgressRecieved The current object, for fluid interface
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
     * Get the [orders_in_progress_recievedid] column value.
     *
     * @return int
     */
    public function getOrdersInProgressRecievedid()
    {
        return $this->orders_in_progress_recievedid;
    }

    /**
     * Get the [orders_detailid] column value.
     *
     * @return int
     */
    public function getOrdersDetailid()
    {
        return $this->orders_detailid;
    }

    /**
     * Get the [orders_in_progressid] column value.
     *
     * @return int
     */
    public function getOrdersInProgressid()
    {
        return $this->orders_in_progressid;
    }

    /**
     * Get the [distributions_giving_outid] column value.
     *
     * @return int
     */
    public function getDistributionsGivingOutid()
    {
        return $this->distributions_giving_outid;
    }

    /**
     * Get the [amount] column value.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of [orders_in_progress_recievedid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     */
    public function setOrdersInProgressRecievedid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orders_in_progress_recievedid !== $v) {
            $this->orders_in_progress_recievedid = $v;
            $this->modifiedColumns[OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID] = true;
        }

        return $this;
    } // setOrdersInProgressRecievedid()

    /**
     * Set the value of [orders_detailid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     */
    public function setOrdersDetailid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orders_detailid !== $v) {
            $this->orders_detailid = $v;
            $this->modifiedColumns[OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID] = true;
        }

        if ($this->aOrdersDetails !== null && $this->aOrdersDetails->getOrdersDetailid() !== $v) {
            $this->aOrdersDetails = null;
        }

        return $this;
    } // setOrdersDetailid()

    /**
     * Set the value of [orders_in_progressid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     */
    public function setOrdersInProgressid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orders_in_progressid !== $v) {
            $this->orders_in_progressid = $v;
            $this->modifiedColumns[OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID] = true;
        }

        if ($this->aOrdersInProgress !== null && $this->aOrdersInProgress->getOrdersInProgressid() !== $v) {
            $this->aOrdersInProgress = null;
        }

        return $this;
    } // setOrdersInProgressid()

    /**
     * Set the value of [distributions_giving_outid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     */
    public function setDistributionsGivingOutid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->distributions_giving_outid !== $v) {
            $this->distributions_giving_outid = $v;
            $this->modifiedColumns[OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID] = true;
        }

        if ($this->aDistributionsGivingOuts !== null && $this->aDistributionsGivingOuts->getDistributionsGivingOutid() !== $v) {
            $this->aDistributionsGivingOuts = null;
        }

        return $this;
    } // setDistributionsGivingOutid()

    /**
     * Set the value of [amount] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[OrdersInProgressRecievedTableMap::COL_AMOUNT] = true;
        }

        return $this;
    } // setAmount()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrdersInProgressRecievedTableMap::translateFieldName('OrdersInProgressRecievedid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orders_in_progress_recievedid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrdersInProgressRecievedTableMap::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orders_detailid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrdersInProgressRecievedTableMap::translateFieldName('OrdersInProgressid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orders_in_progressid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrdersInProgressRecievedTableMap::translateFieldName('DistributionsGivingOutid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->distributions_giving_outid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrdersInProgressRecievedTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = OrdersInProgressRecievedTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\OIP\\OrdersInProgressRecieved'), 0, $e);
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
        if ($this->aOrdersDetails !== null && $this->orders_detailid !== $this->aOrdersDetails->getOrdersDetailid()) {
            $this->aOrdersDetails = null;
        }
        if ($this->aOrdersInProgress !== null && $this->orders_in_progressid !== $this->aOrdersInProgress->getOrdersInProgressid()) {
            $this->aOrdersInProgress = null;
        }
        if ($this->aDistributionsGivingOuts !== null && $this->distributions_giving_outid !== $this->aDistributionsGivingOuts->getDistributionsGivingOutid()) {
            $this->aDistributionsGivingOuts = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(OrdersInProgressRecievedTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrdersInProgressRecievedQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aOrdersDetails = null;
            $this->aOrdersInProgress = null;
            $this->aDistributionsGivingOuts = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see OrdersInProgressRecieved::setDeleted()
     * @see OrdersInProgressRecieved::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersInProgressRecievedTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOrdersInProgressRecievedQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersInProgressRecievedTableMap::DATABASE_NAME);
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
                OrdersInProgressRecievedTableMap::addInstanceToPool($this);
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

            if ($this->aOrdersDetails !== null) {
                if ($this->aOrdersDetails->isModified() || $this->aOrdersDetails->isNew()) {
                    $affectedRows += $this->aOrdersDetails->save($con);
                }
                $this->setOrdersDetails($this->aOrdersDetails);
            }

            if ($this->aOrdersInProgress !== null) {
                if ($this->aOrdersInProgress->isModified() || $this->aOrdersInProgress->isNew()) {
                    $affectedRows += $this->aOrdersInProgress->save($con);
                }
                $this->setOrdersInProgress($this->aOrdersInProgress);
            }

            if ($this->aDistributionsGivingOuts !== null) {
                if ($this->aDistributionsGivingOuts->isModified() || $this->aDistributionsGivingOuts->isNew()) {
                    $affectedRows += $this->aDistributionsGivingOuts->save($con);
                }
                $this->setDistributionsGivingOuts($this->aDistributionsGivingOuts);
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

        $this->modifiedColumns[OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID] = true;
        if (null !== $this->orders_in_progress_recievedid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID)) {
            $modifiedColumns[':p' . $index++]  = 'orders_in_progress_recievedid';
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID)) {
            $modifiedColumns[':p' . $index++]  = 'orders_detailid';
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID)) {
            $modifiedColumns[':p' . $index++]  = 'orders_in_progressid';
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID)) {
            $modifiedColumns[':p' . $index++]  = 'distributions_giving_outid';
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'amount';
        }

        $sql = sprintf(
            'INSERT INTO orders_in_progress_recieved (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'orders_in_progress_recievedid':
                        $stmt->bindValue($identifier, $this->orders_in_progress_recievedid, PDO::PARAM_INT);
                        break;
                    case 'orders_detailid':
                        $stmt->bindValue($identifier, $this->orders_detailid, PDO::PARAM_INT);
                        break;
                    case 'orders_in_progressid':
                        $stmt->bindValue($identifier, $this->orders_in_progressid, PDO::PARAM_INT);
                        break;
                    case 'distributions_giving_outid':
                        $stmt->bindValue($identifier, $this->distributions_giving_outid, PDO::PARAM_INT);
                        break;
                    case 'amount':
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_INT);
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
        $this->setOrdersInProgressRecievedid($pk);

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
        $pos = OrdersInProgressRecievedTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getOrdersInProgressRecievedid();
                break;
            case 1:
                return $this->getOrdersDetailid();
                break;
            case 2:
                return $this->getOrdersInProgressid();
                break;
            case 3:
                return $this->getDistributionsGivingOutid();
                break;
            case 4:
                return $this->getAmount();
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

        if (isset($alreadyDumpedObjects['OrdersInProgressRecieved'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['OrdersInProgressRecieved'][$this->hashCode()] = true;
        $keys = OrdersInProgressRecievedTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrdersInProgressRecievedid(),
            $keys[1] => $this->getOrdersDetailid(),
            $keys[2] => $this->getOrdersInProgressid(),
            $keys[3] => $this->getDistributionsGivingOutid(),
            $keys[4] => $this->getAmount(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aOrdersDetails) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersDetails';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_details';
                        break;
                    default:
                        $key = 'OrdersDetails';
                }

                $result[$key] = $this->aOrdersDetails->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOrdersInProgress) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersInProgress';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_in_progress';
                        break;
                    default:
                        $key = 'OrdersInProgress';
                }

                $result[$key] = $this->aOrdersInProgress->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDistributionsGivingOuts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionsGivingOuts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distributions_giving_outs';
                        break;
                    default:
                        $key = 'DistributionsGivingOuts';
                }

                $result[$key] = $this->aDistributionsGivingOuts->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrdersInProgressRecievedTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrdersInProgressRecievedid($value);
                break;
            case 1:
                $this->setOrdersDetailid($value);
                break;
            case 2:
                $this->setOrdersInProgressid($value);
                break;
            case 3:
                $this->setDistributionsGivingOutid($value);
                break;
            case 4:
                $this->setAmount($value);
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
        $keys = OrdersInProgressRecievedTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrdersInProgressRecievedid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setOrdersDetailid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setOrdersInProgressid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDistributionsGivingOutid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAmount($arr[$keys[4]]);
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
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object, for fluid interface
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
        $criteria = new Criteria(OrdersInProgressRecievedTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID)) {
            $criteria->add(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $this->orders_in_progress_recievedid);
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID)) {
            $criteria->add(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $this->orders_detailid);
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID)) {
            $criteria->add(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $this->orders_in_progressid);
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID)) {
            $criteria->add(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $this->distributions_giving_outid);
        }
        if ($this->isColumnModified(OrdersInProgressRecievedTableMap::COL_AMOUNT)) {
            $criteria->add(OrdersInProgressRecievedTableMap::COL_AMOUNT, $this->amount);
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
        $criteria = ChildOrdersInProgressRecievedQuery::create();
        $criteria->add(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $this->orders_in_progress_recievedid);
        $criteria->add(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $this->orders_detailid);
        $criteria->add(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $this->orders_in_progressid);
        $criteria->add(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $this->distributions_giving_outid);

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
        $validPk = null !== $this->getOrdersInProgressRecievedid() &&
            null !== $this->getOrdersDetailid() &&
            null !== $this->getOrdersInProgressid() &&
            null !== $this->getDistributionsGivingOutid();

        $validPrimaryKeyFKs = 3;
        $primaryKeyFKs = [];

        //relation fk_orders_details_has_orders_in_progress_orders_details1 to table orders_details
        if ($this->aOrdersDetails && $hash = spl_object_hash($this->aOrdersDetails)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation fk_orders_details_has_orders_in_progress_orders_in_progress1 to table orders_in_progress
        if ($this->aOrdersInProgress && $hash = spl_object_hash($this->aOrdersInProgress)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation fk_orders_in_progress_recieved_distribution_givin_out1 to table distributions_giving_outs
        if ($this->aDistributionsGivingOuts && $hash = spl_object_hash($this->aDistributionsGivingOuts)) {
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
        $pks[0] = $this->getOrdersInProgressRecievedid();
        $pks[1] = $this->getOrdersDetailid();
        $pks[2] = $this->getOrdersInProgressid();
        $pks[3] = $this->getDistributionsGivingOutid();

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
        $this->setOrdersInProgressRecievedid($keys[0]);
        $this->setOrdersDetailid($keys[1]);
        $this->setOrdersInProgressid($keys[2]);
        $this->setDistributionsGivingOutid($keys[3]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getOrdersInProgressRecievedid()) && (null === $this->getOrdersDetailid()) && (null === $this->getOrdersInProgressid()) && (null === $this->getDistributionsGivingOutid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\OIP\OrdersInProgressRecieved (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOrdersDetailid($this->getOrdersDetailid());
        $copyObj->setOrdersInProgressid($this->getOrdersInProgressid());
        $copyObj->setDistributionsGivingOutid($this->getDistributionsGivingOutid());
        $copyObj->setAmount($this->getAmount());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setOrdersInProgressRecievedid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\OIP\OrdersInProgressRecieved Clone of current object.
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
     * Declares an association between this object and a OrdersDetails object.
     *
     * @param  OrdersDetails $v
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrdersDetails(OrdersDetails $v = null)
    {
        if ($v === null) {
            $this->setOrdersDetailid(NULL);
        } else {
            $this->setOrdersDetailid($v->getOrdersDetailid());
        }

        $this->aOrdersDetails = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the OrdersDetails object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersInProgressRecieved($this);
        }


        return $this;
    }


    /**
     * Get the associated OrdersDetails object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return OrdersDetails The associated OrdersDetails object.
     * @throws PropelException
     */
    public function getOrdersDetails(ConnectionInterface $con = null)
    {
        if ($this->aOrdersDetails === null && ($this->orders_detailid !== null)) {
            $this->aOrdersDetails = OrdersDetailsQuery::create()
                ->filterByOrdersInProgressRecieved($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrdersDetails->addOrdersInProgressRecieveds($this);
             */
        }

        return $this->aOrdersDetails;
    }

    /**
     * Declares an association between this object and a ChildOrdersInProgress object.
     *
     * @param  ChildOrdersInProgress $v
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrdersInProgress(ChildOrdersInProgress $v = null)
    {
        if ($v === null) {
            $this->setOrdersInProgressid(NULL);
        } else {
            $this->setOrdersInProgressid($v->getOrdersInProgressid());
        }

        $this->aOrdersInProgress = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildOrdersInProgress object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersInProgressRecieved($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildOrdersInProgress object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildOrdersInProgress The associated ChildOrdersInProgress object.
     * @throws PropelException
     */
    public function getOrdersInProgress(ConnectionInterface $con = null)
    {
        if ($this->aOrdersInProgress === null && ($this->orders_in_progressid !== null)) {
            $this->aOrdersInProgress = ChildOrdersInProgressQuery::create()
                ->filterByOrdersInProgressRecieved($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrdersInProgress->addOrdersInProgressRecieveds($this);
             */
        }

        return $this->aOrdersInProgress;
    }

    /**
     * Declares an association between this object and a ChildDistributionsGivingOuts object.
     *
     * @param  ChildDistributionsGivingOuts $v
     * @return $this|\API\Models\OIP\OrdersInProgressRecieved The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDistributionsGivingOuts(ChildDistributionsGivingOuts $v = null)
    {
        if ($v === null) {
            $this->setDistributionsGivingOutid(NULL);
        } else {
            $this->setDistributionsGivingOutid($v->getDistributionsGivingOutid());
        }

        $this->aDistributionsGivingOuts = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildDistributionsGivingOuts object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersInProgressRecieved($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildDistributionsGivingOuts object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildDistributionsGivingOuts The associated ChildDistributionsGivingOuts object.
     * @throws PropelException
     */
    public function getDistributionsGivingOuts(ConnectionInterface $con = null)
    {
        if ($this->aDistributionsGivingOuts === null && ($this->distributions_giving_outid !== null)) {
            $this->aDistributionsGivingOuts = ChildDistributionsGivingOutsQuery::create()->findPk($this->distributions_giving_outid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDistributionsGivingOuts->addOrdersInProgressRecieveds($this);
             */
        }

        return $this->aDistributionsGivingOuts;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aOrdersDetails) {
            $this->aOrdersDetails->removeOrdersInProgressRecieved($this);
        }
        if (null !== $this->aOrdersInProgress) {
            $this->aOrdersInProgress->removeOrdersInProgressRecieved($this);
        }
        if (null !== $this->aDistributionsGivingOuts) {
            $this->aDistributionsGivingOuts->removeOrdersInProgressRecieved($this);
        }
        $this->orders_in_progress_recievedid = null;
        $this->orders_detailid = null;
        $this->orders_in_progressid = null;
        $this->distributions_giving_outid = null;
        $this->amount = null;
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
        } // if ($deep)

        $this->aOrdersDetails = null;
        $this->aOrdersInProgress = null;
        $this->aDistributionsGivingOuts = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrdersInProgressRecievedTableMap::DEFAULT_STRING_FORMAT);
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