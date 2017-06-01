<?php

namespace API\Models\ORM\OIP\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\ORM\Menu\MenuGroup;
use API\Models\ORM\Menu\MenuGroupQuery;
use API\Models\ORM\OIP\OrderInProgress as ChildOrderInProgress;
use API\Models\ORM\OIP\OrderInProgressQuery as ChildOrderInProgressQuery;
use API\Models\ORM\OIP\OrderInProgressRecieved as ChildOrderInProgressRecieved;
use API\Models\ORM\OIP\OrderInProgressRecievedQuery as ChildOrderInProgressRecievedQuery;
use API\Models\ORM\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\ORM\OIP\Map\OrderInProgressTableMap;
use API\Models\ORM\Ordering\Order;
use API\Models\ORM\Ordering\OrderQuery;
use API\Models\ORM\User\User;
use API\Models\ORM\User\UserQuery;
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
 * Base class that represents a row from the 'order_in_progress' table.
 *
 * 
 *
 * @package    propel.generator.API.Models.ORM.OIP.Base
 */
abstract class OrderInProgress implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\ORM\\OIP\\Map\\OrderInProgressTableMap';


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
     * The value for the order_in_progressid field.
     * 
     * @var        int
     */
    protected $order_in_progressid;

    /**
     * The value for the orderid field.
     * 
     * @var        int
     */
    protected $orderid;

    /**
     * The value for the userid field.
     * 
     * @var        int
     */
    protected $userid;

    /**
     * The value for the menu_groupid field.
     * 
     * @var        int
     */
    protected $menu_groupid;

    /**
     * The value for the begin field.
     * 
     * @var        DateTime
     */
    protected $begin;

    /**
     * The value for the done field.
     * 
     * @var        DateTime
     */
    protected $done;

    /**
     * @var        MenuGroup
     */
    protected $aMenuGroup;

    /**
     * @var        Order
     */
    protected $aOrder;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildOrderInProgressRecieved[] Collection to store aggregation of ChildOrderInProgressRecieved objects.
     */
    protected $collOrderInProgressRecieveds;
    protected $collOrderInProgressRecievedsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrderInProgressRecieved[]
     */
    protected $orderInProgressRecievedsScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\ORM\OIP\Base\OrderInProgress object.
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
     * Compares this with another <code>OrderInProgress</code> instance.  If
     * <code>obj</code> is an instance of <code>OrderInProgress</code>, delegates to
     * <code>equals(OrderInProgress)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|OrderInProgress The current object, for fluid interface
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
     * Get the [order_in_progressid] column value.
     * 
     * @return int
     */
    public function getOrderInProgressid()
    {
        return $this->order_in_progressid;
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
     * Get the [userid] column value.
     * 
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Get the [menu_groupid] column value.
     * 
     * @return int
     */
    public function getMenuGroupid()
    {
        return $this->menu_groupid;
    }

    /**
     * Get the [optionally formatted] temporal [begin] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBegin($format = NULL)
    {
        if ($format === null) {
            return $this->begin;
        } else {
            return $this->begin instanceof \DateTimeInterface ? $this->begin->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [done] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDone($format = NULL)
    {
        if ($format === null) {
            return $this->done;
        } else {
            return $this->done instanceof \DateTimeInterface ? $this->done->format($format) : null;
        }
    }

    /**
     * Set the value of [order_in_progressid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function setOrderInProgressid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_in_progressid !== $v) {
            $this->order_in_progressid = $v;
            $this->modifiedColumns[OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID] = true;
        }

        return $this;
    } // setOrderInProgressid()

    /**
     * Set the value of [orderid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function setOrderid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orderid !== $v) {
            $this->orderid = $v;
            $this->modifiedColumns[OrderInProgressTableMap::COL_ORDERID] = true;
        }

        if ($this->aOrder !== null && $this->aOrder->getOrderid() !== $v) {
            $this->aOrder = null;
        }

        return $this;
    } // setOrderid()

    /**
     * Set the value of [userid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[OrderInProgressTableMap::COL_USERID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getUserid() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserid()

    /**
     * Set the value of [menu_groupid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function setMenuGroupid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_groupid !== $v) {
            $this->menu_groupid = $v;
            $this->modifiedColumns[OrderInProgressTableMap::COL_MENU_GROUPID] = true;
        }

        if ($this->aMenuGroup !== null && $this->aMenuGroup->getMenuGroupid() !== $v) {
            $this->aMenuGroup = null;
        }

        return $this;
    } // setMenuGroupid()

    /**
     * Sets the value of [begin] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function setBegin($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->begin !== null || $dt !== null) {
            if ($this->begin === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->begin->format("Y-m-d H:i:s.u")) {
                $this->begin = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderInProgressTableMap::COL_BEGIN] = true;
            }
        } // if either are not null

        return $this;
    } // setBegin()

    /**
     * Sets the value of [done] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function setDone($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->done !== null || $dt !== null) {
            if ($this->done === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->done->format("Y-m-d H:i:s.u")) {
                $this->done = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderInProgressTableMap::COL_DONE] = true;
            }
        } // if either are not null

        return $this;
    } // setDone()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrderInProgressTableMap::translateFieldName('OrderInProgressid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_in_progressid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrderInProgressTableMap::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orderid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrderInProgressTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrderInProgressTableMap::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_groupid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrderInProgressTableMap::translateFieldName('Begin', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->begin = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrderInProgressTableMap::translateFieldName('Done', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->done = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = OrderInProgressTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\ORM\\OIP\\OrderInProgress'), 0, $e);
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
        if ($this->aOrder !== null && $this->orderid !== $this->aOrder->getOrderid()) {
            $this->aOrder = null;
        }
        if ($this->aUser !== null && $this->userid !== $this->aUser->getUserid()) {
            $this->aUser = null;
        }
        if ($this->aMenuGroup !== null && $this->menu_groupid !== $this->aMenuGroup->getMenuGroupid()) {
            $this->aMenuGroup = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrderInProgressQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMenuGroup = null;
            $this->aOrder = null;
            $this->aUser = null;
            $this->collOrderInProgressRecieveds = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see OrderInProgress::setDeleted()
     * @see OrderInProgress::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOrderInProgressQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressTableMap::DATABASE_NAME);
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
                OrderInProgressTableMap::addInstanceToPool($this);
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

            if ($this->aMenuGroup !== null) {
                if ($this->aMenuGroup->isModified() || $this->aMenuGroup->isNew()) {
                    $affectedRows += $this->aMenuGroup->save($con);
                }
                $this->setMenuGroup($this->aMenuGroup);
            }

            if ($this->aOrder !== null) {
                if ($this->aOrder->isModified() || $this->aOrder->isNew()) {
                    $affectedRows += $this->aOrder->save($con);
                }
                $this->setOrder($this->aOrder);
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

            if ($this->orderInProgressRecievedsScheduledForDeletion !== null) {
                if (!$this->orderInProgressRecievedsScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\OIP\OrderInProgressRecievedQuery::create()
                        ->filterByPrimaryKeys($this->orderInProgressRecievedsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->orderInProgressRecievedsScheduledForDeletion = null;
                }
            }

            if ($this->collOrderInProgressRecieveds !== null) {
                foreach ($this->collOrderInProgressRecieveds as $referrerFK) {
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

        $this->modifiedColumns[OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID] = true;
        if (null !== $this->order_in_progressid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID)) {
            $modifiedColumns[':p' . $index++]  = '`order_in_progressid`';
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_ORDERID)) {
            $modifiedColumns[':p' . $index++]  = '`orderid`';
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = '`userid`';
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_MENU_GROUPID)) {
            $modifiedColumns[':p' . $index++]  = '`menu_groupid`';
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_BEGIN)) {
            $modifiedColumns[':p' . $index++]  = '`begin`';
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_DONE)) {
            $modifiedColumns[':p' . $index++]  = '`done`';
        }

        $sql = sprintf(
            'INSERT INTO `order_in_progress` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`order_in_progressid`':                        
                        $stmt->bindValue($identifier, $this->order_in_progressid, PDO::PARAM_INT);
                        break;
                    case '`orderid`':                        
                        $stmt->bindValue($identifier, $this->orderid, PDO::PARAM_INT);
                        break;
                    case '`userid`':                        
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case '`menu_groupid`':                        
                        $stmt->bindValue($identifier, $this->menu_groupid, PDO::PARAM_INT);
                        break;
                    case '`begin`':                        
                        $stmt->bindValue($identifier, $this->begin ? $this->begin->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`done`':                        
                        $stmt->bindValue($identifier, $this->done ? $this->done->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $this->setOrderInProgressid($pk);

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
        $pos = OrderInProgressTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getOrderInProgressid();
                break;
            case 1:
                return $this->getOrderid();
                break;
            case 2:
                return $this->getUserid();
                break;
            case 3:
                return $this->getMenuGroupid();
                break;
            case 4:
                return $this->getBegin();
                break;
            case 5:
                return $this->getDone();
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

        if (isset($alreadyDumpedObjects['OrderInProgress'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['OrderInProgress'][$this->hashCode()] = true;
        $keys = OrderInProgressTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrderInProgressid(),
            $keys[1] => $this->getOrderid(),
            $keys[2] => $this->getUserid(),
            $keys[3] => $this->getMenuGroupid(),
            $keys[4] => $this->getBegin(),
            $keys[5] => $this->getDone(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }
        
        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }
        
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aMenuGroup) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuGroup';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_group';
                        break;
                    default:
                        $key = 'MenuGroup';
                }
        
                $result[$key] = $this->aMenuGroup->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOrder) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'order';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order';
                        break;
                    default:
                        $key = 'Order';
                }
        
                $result[$key] = $this->aOrder->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collOrderInProgressRecieveds) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderInProgressRecieveds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order_in_progress_recieveds';
                        break;
                    default:
                        $key = 'OrderInProgressRecieveds';
                }
        
                $result[$key] = $this->collOrderInProgressRecieveds->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\ORM\OIP\OrderInProgress
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderInProgressTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\ORM\OIP\OrderInProgress
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrderInProgressid($value);
                break;
            case 1:
                $this->setOrderid($value);
                break;
            case 2:
                $this->setUserid($value);
                break;
            case 3:
                $this->setMenuGroupid($value);
                break;
            case 4:
                $this->setBegin($value);
                break;
            case 5:
                $this->setDone($value);
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
        $keys = OrderInProgressTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrderInProgressid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setOrderid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setMenuGroupid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setBegin($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setDone($arr[$keys[5]]);
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
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object, for fluid interface
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
        $criteria = new Criteria(OrderInProgressTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID)) {
            $criteria->add(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $this->order_in_progressid);
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_ORDERID)) {
            $criteria->add(OrderInProgressTableMap::COL_ORDERID, $this->orderid);
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_USERID)) {
            $criteria->add(OrderInProgressTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_MENU_GROUPID)) {
            $criteria->add(OrderInProgressTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_BEGIN)) {
            $criteria->add(OrderInProgressTableMap::COL_BEGIN, $this->begin);
        }
        if ($this->isColumnModified(OrderInProgressTableMap::COL_DONE)) {
            $criteria->add(OrderInProgressTableMap::COL_DONE, $this->done);
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
        $criteria = ChildOrderInProgressQuery::create();
        $criteria->add(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $this->order_in_progressid);

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
        $validPk = null !== $this->getOrderInProgressid();

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
        return $this->getOrderInProgressid();
    }

    /**
     * Generic method to set the primary key (order_in_progressid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setOrderInProgressid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getOrderInProgressid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\ORM\OIP\OrderInProgress (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOrderid($this->getOrderid());
        $copyObj->setUserid($this->getUserid());
        $copyObj->setMenuGroupid($this->getMenuGroupid());
        $copyObj->setBegin($this->getBegin());
        $copyObj->setDone($this->getDone());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getOrderInProgressRecieveds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderInProgressRecieved($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setOrderInProgressid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\ORM\OIP\OrderInProgress Clone of current object.
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
     * Declares an association between this object and a MenuGroup object.
     *
     * @param  MenuGroup $v
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuGroup(MenuGroup $v = null)
    {
        if ($v === null) {
            $this->setMenuGroupid(NULL);
        } else {
            $this->setMenuGroupid($v->getMenuGroupid());
        }

        $this->aMenuGroup = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the MenuGroup object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderInProgress($this);
        }


        return $this;
    }


    /**
     * Get the associated MenuGroup object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return MenuGroup The associated MenuGroup object.
     * @throws PropelException
     */
    public function getMenuGroup(ConnectionInterface $con = null)
    {
        if ($this->aMenuGroup === null && ($this->menu_groupid !== null)) {
            $this->aMenuGroup = MenuGroupQuery::create()->findPk($this->menu_groupid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuGroup->addOrderInProgresses($this);
             */
        }

        return $this->aMenuGroup;
    }

    /**
     * Declares an association between this object and a Order object.
     *
     * @param  Order $v
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrder(Order $v = null)
    {
        if ($v === null) {
            $this->setOrderid(NULL);
        } else {
            $this->setOrderid($v->getOrderid());
        }

        $this->aOrder = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Order object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderInProgress($this);
        }


        return $this;
    }


    /**
     * Get the associated Order object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Order The associated Order object.
     * @throws PropelException
     */
    public function getOrder(ConnectionInterface $con = null)
    {
        if ($this->aOrder === null && ($this->orderid !== null)) {
            $this->aOrder = OrderQuery::create()->findPk($this->orderid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrder->addOrderInProgresses($this);
             */
        }

        return $this->aOrder;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserid(NULL);
        } else {
            $this->setUserid($v->getUserid());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderInProgress($this);
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
        if ($this->aUser === null && ($this->userid !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addOrderInProgresses($this);
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
        if ('OrderInProgressRecieved' == $relationName) {
            return $this->initOrderInProgressRecieveds();
        }
    }

    /**
     * Clears out the collOrderInProgressRecieveds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrderInProgressRecieveds()
     */
    public function clearOrderInProgressRecieveds()
    {
        $this->collOrderInProgressRecieveds = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrderInProgressRecieveds collection loaded partially.
     */
    public function resetPartialOrderInProgressRecieveds($v = true)
    {
        $this->collOrderInProgressRecievedsPartial = $v;
    }

    /**
     * Initializes the collOrderInProgressRecieveds collection.
     *
     * By default this just sets the collOrderInProgressRecieveds collection to an empty array (like clearcollOrderInProgressRecieveds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrderInProgressRecieveds($overrideExisting = true)
    {
        if (null !== $this->collOrderInProgressRecieveds && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderInProgressRecievedTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderInProgressRecieveds = new $collectionClassName;
        $this->collOrderInProgressRecieveds->setModel('\API\Models\ORM\OIP\OrderInProgressRecieved');
    }

    /**
     * Gets an array of ChildOrderInProgressRecieved objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrderInProgress is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrderInProgressRecieved[] List of ChildOrderInProgressRecieved objects
     * @throws PropelException
     */
    public function getOrderInProgressRecieveds(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderInProgressRecievedsPartial && !$this->isNew();
        if (null === $this->collOrderInProgressRecieveds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrderInProgressRecieveds) {
                // return empty collection
                $this->initOrderInProgressRecieveds();
            } else {
                $collOrderInProgressRecieveds = ChildOrderInProgressRecievedQuery::create(null, $criteria)
                    ->filterByOrderInProgress($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrderInProgressRecievedsPartial && count($collOrderInProgressRecieveds)) {
                        $this->initOrderInProgressRecieveds(false);

                        foreach ($collOrderInProgressRecieveds as $obj) {
                            if (false == $this->collOrderInProgressRecieveds->contains($obj)) {
                                $this->collOrderInProgressRecieveds->append($obj);
                            }
                        }

                        $this->collOrderInProgressRecievedsPartial = true;
                    }

                    return $collOrderInProgressRecieveds;
                }

                if ($partial && $this->collOrderInProgressRecieveds) {
                    foreach ($this->collOrderInProgressRecieveds as $obj) {
                        if ($obj->isNew()) {
                            $collOrderInProgressRecieveds[] = $obj;
                        }
                    }
                }

                $this->collOrderInProgressRecieveds = $collOrderInProgressRecieveds;
                $this->collOrderInProgressRecievedsPartial = false;
            }
        }

        return $this->collOrderInProgressRecieveds;
    }

    /**
     * Sets a collection of ChildOrderInProgressRecieved objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderInProgressRecieveds A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrderInProgress The current object (for fluent API support)
     */
    public function setOrderInProgressRecieveds(Collection $orderInProgressRecieveds, ConnectionInterface $con = null)
    {
        /** @var ChildOrderInProgressRecieved[] $orderInProgressRecievedsToDelete */
        $orderInProgressRecievedsToDelete = $this->getOrderInProgressRecieveds(new Criteria(), $con)->diff($orderInProgressRecieveds);

        
        $this->orderInProgressRecievedsScheduledForDeletion = $orderInProgressRecievedsToDelete;

        foreach ($orderInProgressRecievedsToDelete as $orderInProgressRecievedRemoved) {
            $orderInProgressRecievedRemoved->setOrderInProgress(null);
        }

        $this->collOrderInProgressRecieveds = null;
        foreach ($orderInProgressRecieveds as $orderInProgressRecieved) {
            $this->addOrderInProgressRecieved($orderInProgressRecieved);
        }

        $this->collOrderInProgressRecieveds = $orderInProgressRecieveds;
        $this->collOrderInProgressRecievedsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrderInProgressRecieved objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related OrderInProgressRecieved objects.
     * @throws PropelException
     */
    public function countOrderInProgressRecieveds(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderInProgressRecievedsPartial && !$this->isNew();
        if (null === $this->collOrderInProgressRecieveds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderInProgressRecieveds) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrderInProgressRecieveds());
            }

            $query = ChildOrderInProgressRecievedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrderInProgress($this)
                ->count($con);
        }

        return count($this->collOrderInProgressRecieveds);
    }

    /**
     * Method called to associate a ChildOrderInProgressRecieved object to this object
     * through the ChildOrderInProgressRecieved foreign key attribute.
     *
     * @param  ChildOrderInProgressRecieved $l ChildOrderInProgressRecieved
     * @return $this|\API\Models\ORM\OIP\OrderInProgress The current object (for fluent API support)
     */
    public function addOrderInProgressRecieved(ChildOrderInProgressRecieved $l)
    {
        if ($this->collOrderInProgressRecieveds === null) {
            $this->initOrderInProgressRecieveds();
            $this->collOrderInProgressRecievedsPartial = true;
        }

        if (!$this->collOrderInProgressRecieveds->contains($l)) {
            $this->doAddOrderInProgressRecieved($l);

            if ($this->orderInProgressRecievedsScheduledForDeletion and $this->orderInProgressRecievedsScheduledForDeletion->contains($l)) {
                $this->orderInProgressRecievedsScheduledForDeletion->remove($this->orderInProgressRecievedsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrderInProgressRecieved $orderInProgressRecieved The ChildOrderInProgressRecieved object to add.
     */
    protected function doAddOrderInProgressRecieved(ChildOrderInProgressRecieved $orderInProgressRecieved)
    {
        $this->collOrderInProgressRecieveds[]= $orderInProgressRecieved;
        $orderInProgressRecieved->setOrderInProgress($this);
    }

    /**
     * @param  ChildOrderInProgressRecieved $orderInProgressRecieved The ChildOrderInProgressRecieved object to remove.
     * @return $this|ChildOrderInProgress The current object (for fluent API support)
     */
    public function removeOrderInProgressRecieved(ChildOrderInProgressRecieved $orderInProgressRecieved)
    {
        if ($this->getOrderInProgressRecieveds()->contains($orderInProgressRecieved)) {
            $pos = $this->collOrderInProgressRecieveds->search($orderInProgressRecieved);
            $this->collOrderInProgressRecieveds->remove($pos);
            if (null === $this->orderInProgressRecievedsScheduledForDeletion) {
                $this->orderInProgressRecievedsScheduledForDeletion = clone $this->collOrderInProgressRecieveds;
                $this->orderInProgressRecievedsScheduledForDeletion->clear();
            }
            $this->orderInProgressRecievedsScheduledForDeletion[]= clone $orderInProgressRecieved;
            $orderInProgressRecieved->setOrderInProgress(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderInProgress is new, it will return
     * an empty collection; or if this OrderInProgress has previously
     * been saved, it will retrieve related OrderInProgressRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderInProgress.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderInProgressRecieved[] List of ChildOrderInProgressRecieved objects
     */
    public function getOrderInProgressRecievedsJoinOrderDetail(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderInProgressRecievedQuery::create(null, $criteria);
        $query->joinWith('OrderDetail', $joinBehavior);

        return $this->getOrderInProgressRecieveds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderInProgress is new, it will return
     * an empty collection; or if this OrderInProgress has previously
     * been saved, it will retrieve related OrderInProgressRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderInProgress.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderInProgressRecieved[] List of ChildOrderInProgressRecieved objects
     */
    public function getOrderInProgressRecievedsJoinDistributionGivingOut(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderInProgressRecievedQuery::create(null, $criteria);
        $query->joinWith('DistributionGivingOut', $joinBehavior);

        return $this->getOrderInProgressRecieveds($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aMenuGroup) {
            $this->aMenuGroup->removeOrderInProgress($this);
        }
        if (null !== $this->aOrder) {
            $this->aOrder->removeOrderInProgress($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeOrderInProgress($this);
        }
        $this->order_in_progressid = null;
        $this->orderid = null;
        $this->userid = null;
        $this->menu_groupid = null;
        $this->begin = null;
        $this->done = null;
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
            if ($this->collOrderInProgressRecieveds) {
                foreach ($this->collOrderInProgressRecieveds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOrderInProgressRecieveds = null;
        $this->aMenuGroup = null;
        $this->aOrder = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrderInProgressTableMap::DEFAULT_STRING_FORMAT);
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
