<?php

namespace API\Models\User\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceUser;
use API\Models\DistributionPlace\DistributionPlaceUserQuery;
use API\Models\DistributionPlace\Base\DistributionPlaceUser as BaseDistributionPlaceUser;
use API\Models\DistributionPlace\Map\DistributionPlaceUserTableMap;
use API\Models\Event\EventUser;
use API\Models\Event\EventUserQuery;
use API\Models\Event\Base\EventUser as BaseEventUser;
use API\Models\Event\Map\EventUserTableMap;
use API\Models\Invoice\Invoice;
use API\Models\Invoice\InvoiceQuery;
use API\Models\Invoice\Base\Invoice as BaseInvoice;
use API\Models\Invoice\Map\InvoiceTableMap;
use API\Models\OIP\OrderInProgress;
use API\Models\OIP\OrderInProgressQuery;
use API\Models\OIP\Base\OrderInProgress as BaseOrderInProgress;
use API\Models\OIP\Map\OrderInProgressTableMap;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\OrderQuery;
use API\Models\Ordering\Base\Order as BaseOrder;
use API\Models\Ordering\Base\OrderDetail as BaseOrderDetail;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\Map\OrderTableMap;
use API\Models\Payment\Coupon;
use API\Models\Payment\CouponQuery;
use API\Models\Payment\Base\Coupon as BaseCoupon;
use API\Models\Payment\Map\CouponTableMap;
use API\Models\User\User as ChildUser;
use API\Models\User\UserQuery as ChildUserQuery;
use API\Models\User\Map\UserTableMap;
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
 * Base class that represents a row from the 'user' table.
 *
 *
 *
 * @package    propel.generator.API.Models.User.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\User\\Map\\UserTableMap';


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
     * The value for the userid field.
     *
     * @var        int
     */
    protected $userid;

    /**
     * The value for the username field.
     *
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     *
     * @var        string
     */
    protected $password;

    /**
     * The value for the firstname field.
     *
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the lastname field.
     *
     * @var        string
     */
    protected $lastname;

    /**
     * The value for the autologin_hash field.
     *
     * @var        string
     */
    protected $autologin_hash;

    /**
     * The value for the active field.
     *
     * @var        int
     */
    protected $active;

    /**
     * The value for the phonenumber field.
     *
     * @var        string
     */
    protected $phonenumber;

    /**
     * The value for the call_request field.
     *
     * @var        DateTime
     */
    protected $call_request;

    /**
     * The value for the is_admin field.
     *
     * @var        boolean
     */
    protected $is_admin;

    /**
     * @var        ObjectCollection|Coupon[] Collection to store aggregation of Coupon objects.
     */
    protected $collCoupons;
    protected $collCouponsPartial;

    /**
     * @var        ObjectCollection|DistributionPlaceUser[] Collection to store aggregation of DistributionPlaceUser objects.
     */
    protected $collDistributionPlaceUsers;
    protected $collDistributionPlaceUsersPartial;

    /**
     * @var        ObjectCollection|EventUser[] Collection to store aggregation of EventUser objects.
     */
    protected $collEventUsers;
    protected $collEventUsersPartial;

    /**
     * @var        ObjectCollection|Invoice[] Collection to store aggregation of Invoice objects.
     */
    protected $collInvoices;
    protected $collInvoicesPartial;

    /**
     * @var        ObjectCollection|Order[] Collection to store aggregation of Order objects.
     */
    protected $collOrders;
    protected $collOrdersPartial;

    /**
     * @var        ObjectCollection|OrderDetail[] Collection to store aggregation of OrderDetail objects.
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
     * @var ObjectCollection|Coupon[]
     */
    protected $couponsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionPlaceUser[]
     */
    protected $distributionPlaceUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|EventUser[]
     */
    protected $eventUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Invoice[]
     */
    protected $invoicesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Order[]
     */
    protected $ordersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrderDetail[]
     */
    protected $orderDetailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrderInProgress[]
     */
    protected $orderInProgressesScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\User\Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [userid] column value.
     *
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [firstname] column value.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Get the [lastname] column value.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get the [autologin_hash] column value.
     *
     * @return string
     */
    public function getAutologinHash()
    {
        return $this->autologin_hash;
    }

    /**
     * Get the [active] column value.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get the [phonenumber] column value.
     *
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * Get the [optionally formatted] temporal [call_request] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCallRequest($format = NULL)
    {
        if ($format === null) {
            return $this->call_request;
        } else {
            return $this->call_request instanceof \DateTimeInterface ? $this->call_request->format($format) : null;
        }
    }

    /**
     * Get the [is_admin] column value.
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Get the [is_admin] column value.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->getIsAdmin();
    }

    /**
     * Set the value of [userid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[UserTableMap::COL_USERID] = true;
        }

        return $this;
    } // setUserid()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [firstname] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[UserTableMap::COL_FIRSTNAME] = true;
        }

        return $this;
    } // setFirstname()

    /**
     * Set the value of [lastname] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[UserTableMap::COL_LASTNAME] = true;
        }

        return $this;
    } // setLastname()

    /**
     * Set the value of [autologin_hash] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setAutologinHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->autologin_hash !== $v) {
            $this->autologin_hash = $v;
            $this->modifiedColumns[UserTableMap::COL_AUTOLOGIN_HASH] = true;
        }

        return $this;
    } // setAutologinHash()

    /**
     * Set the value of [active] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[UserTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [phonenumber] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setPhonenumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phonenumber !== $v) {
            $this->phonenumber = $v;
            $this->modifiedColumns[UserTableMap::COL_PHONENUMBER] = true;
        }

        return $this;
    } // setPhonenumber()

    /**
     * Sets the value of [call_request] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setCallRequest($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->call_request !== null || $dt !== null) {
            if ($this->call_request === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->call_request->format("Y-m-d H:i:s.u")) {
                $this->call_request = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CALL_REQUEST] = true;
            }
        } // if either are not null

        return $this;
    } // setCallRequest()

    /**
     * Sets the value of the [is_admin] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function setIsAdmin($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_admin !== $v) {
            $this->is_admin = $v;
            $this->modifiedColumns[UserTableMap::COL_IS_ADMIN] = true;
        }

        return $this;
    } // setIsAdmin()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('AutologinHash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->autologin_hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Phonenumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phonenumber = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('CallRequest', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->call_request = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('IsAdmin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_admin = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\User\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCoupons = null;

            $this->collDistributionPlaceUsers = null;

            $this->collEventUsers = null;

            $this->collInvoices = null;

            $this->collOrders = null;

            $this->collOrderDetails = null;

            $this->collOrderInProgresses = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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
                    \API\Models\Payment\CouponQuery::create()
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

            if ($this->distributionPlaceUsersScheduledForDeletion !== null) {
                if (!$this->distributionPlaceUsersScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionPlaceUserQuery::create()
                        ->filterByPrimaryKeys($this->distributionPlaceUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionPlaceUsersScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionPlaceUsers !== null) {
                foreach ($this->collDistributionPlaceUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventUsersScheduledForDeletion !== null) {
                if (!$this->eventUsersScheduledForDeletion->isEmpty()) {
                    \API\Models\Event\EventUserQuery::create()
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

            if ($this->invoicesScheduledForDeletion !== null) {
                if (!$this->invoicesScheduledForDeletion->isEmpty()) {
                    \API\Models\Invoice\InvoiceQuery::create()
                        ->filterByPrimaryKeys($this->invoicesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoicesScheduledForDeletion = null;
                }
            }

            if ($this->collInvoices !== null) {
                foreach ($this->collInvoices as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersScheduledForDeletion !== null) {
                if (!$this->ordersScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrderQuery::create()
                        ->filterByPrimaryKeys($this->ordersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersScheduledForDeletion = null;
                }
            }

            if ($this->collOrders !== null) {
                foreach ($this->collOrders as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->orderDetailsScheduledForDeletion !== null) {
                if (!$this->orderDetailsScheduledForDeletion->isEmpty()) {
                    foreach ($this->orderDetailsScheduledForDeletion as $orderDetail) {
                        // need to save related object because we set the relation to null
                        $orderDetail->save($con);
                    }
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
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[UserTableMap::COL_USERID] = true;
        if (null !== $this->userid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_USERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userid';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'firstname';
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'lastname';
        }
        if ($this->isColumnModified(UserTableMap::COL_AUTOLOGIN_HASH)) {
            $modifiedColumns[':p' . $index++]  = 'autologin_hash';
        }
        if ($this->isColumnModified(UserTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONENUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'phonenumber';
        }
        if ($this->isColumnModified(UserTableMap::COL_CALL_REQUEST)) {
            $modifiedColumns[':p' . $index++]  = 'call_request';
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_ADMIN)) {
            $modifiedColumns[':p' . $index++]  = 'is_admin';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'userid':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case 'username':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'firstname':
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case 'lastname':
                        $stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
                        break;
                    case 'autologin_hash':
                        $stmt->bindValue($identifier, $this->autologin_hash, PDO::PARAM_STR);
                        break;
                    case 'active':
                        $stmt->bindValue($identifier, $this->active, PDO::PARAM_INT);
                        break;
                    case 'phonenumber':
                        $stmt->bindValue($identifier, $this->phonenumber, PDO::PARAM_STR);
                        break;
                    case 'call_request':
                        $stmt->bindValue($identifier, $this->call_request ? $this->call_request->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'is_admin':
                        $stmt->bindValue($identifier, (int) $this->is_admin, PDO::PARAM_INT);
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
        $this->setUserid($pk);

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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUserid();
                break;
            case 1:
                return $this->getUsername();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getFirstname();
                break;
            case 4:
                return $this->getLastname();
                break;
            case 5:
                return $this->getAutologinHash();
                break;
            case 6:
                return $this->getActive();
                break;
            case 7:
                return $this->getPhonenumber();
                break;
            case 8:
                return $this->getCallRequest();
                break;
            case 9:
                return $this->getIsAdmin();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUserid(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getFirstname(),
            $keys[4] => $this->getLastname(),
            $keys[5] => $this->getAutologinHash(),
            $keys[6] => $this->getActive(),
            $keys[7] => $this->getPhonenumber(),
            $keys[8] => $this->getCallRequest(),
            $keys[9] => $this->getIsAdmin(),
        );
        if ($result[$keys[8]] instanceof \DateTime) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
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
            if (null !== $this->collDistributionPlaceUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionPlaceUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distribution_place_users';
                        break;
                    default:
                        $key = 'DistributionPlaceUsers';
                }

                $result[$key] = $this->collDistributionPlaceUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collInvoices) {

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

                $result[$key] = $this->collInvoices->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrders) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orders';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders';
                        break;
                    default:
                        $key = 'Orders';
                }

                $result[$key] = $this->collOrders->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\API\Models\User\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\User\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setUserid($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setFirstname($value);
                break;
            case 4:
                $this->setLastname($value);
                break;
            case 5:
                $this->setAutologinHash($value);
                break;
            case 6:
                $this->setActive($value);
                break;
            case 7:
                $this->setPhonenumber($value);
                break;
            case 8:
                $this->setCallRequest($value);
                break;
            case 9:
                $this->setIsAdmin($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setUserid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUsername($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setFirstname($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLastname($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAutologinHash($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setActive($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPhonenumber($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCallRequest($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setIsAdmin($arr[$keys[9]]);
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
     * @return $this|\API\Models\User\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_USERID)) {
            $criteria->add(UserTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRSTNAME)) {
            $criteria->add(UserTableMap::COL_FIRSTNAME, $this->firstname);
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTNAME)) {
            $criteria->add(UserTableMap::COL_LASTNAME, $this->lastname);
        }
        if ($this->isColumnModified(UserTableMap::COL_AUTOLOGIN_HASH)) {
            $criteria->add(UserTableMap::COL_AUTOLOGIN_HASH, $this->autologin_hash);
        }
        if ($this->isColumnModified(UserTableMap::COL_ACTIVE)) {
            $criteria->add(UserTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(UserTableMap::COL_PHONENUMBER)) {
            $criteria->add(UserTableMap::COL_PHONENUMBER, $this->phonenumber);
        }
        if ($this->isColumnModified(UserTableMap::COL_CALL_REQUEST)) {
            $criteria->add(UserTableMap::COL_CALL_REQUEST, $this->call_request);
        }
        if ($this->isColumnModified(UserTableMap::COL_IS_ADMIN)) {
            $criteria->add(UserTableMap::COL_IS_ADMIN, $this->is_admin);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_USERID, $this->userid);

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
        $validPk = null !== $this->getUserid();

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
        return $this->getUserid();
    }

    /**
     * Generic method to set the primary key (userid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setUserid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getUserid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\User\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setLastname($this->getLastname());
        $copyObj->setAutologinHash($this->getAutologinHash());
        $copyObj->setActive($this->getActive());
        $copyObj->setPhonenumber($this->getPhonenumber());
        $copyObj->setCallRequest($this->getCallRequest());
        $copyObj->setIsAdmin($this->getIsAdmin());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCoupons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCoupon($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDistributionPlaceUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlaceUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvoices() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoice($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrder($relObj->copy($deepCopy));
                }
            }

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
            $copyObj->setUserid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\User\User Clone of current object.
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
        if ('DistributionPlaceUser' == $relationName) {
            return $this->initDistributionPlaceUsers();
        }
        if ('EventUser' == $relationName) {
            return $this->initEventUsers();
        }
        if ('Invoice' == $relationName) {
            return $this->initInvoices();
        }
        if ('Order' == $relationName) {
            return $this->initOrders();
        }
        if ('OrderDetail' == $relationName) {
            return $this->initOrderDetails();
        }
        if ('OrderInProgress' == $relationName) {
            return $this->initOrderInProgresses();
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
        $this->collCoupons->setModel('\API\Models\Payment\Coupon');
    }

    /**
     * Gets an array of Coupon objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setCoupons(Collection $coupons, ConnectionInterface $con = null)
    {
        /** @var Coupon[] $couponsToDelete */
        $couponsToDelete = $this->getCoupons(new Criteria(), $con)->diff($coupons);


        $this->couponsScheduledForDeletion = $couponsToDelete;

        foreach ($couponsToDelete as $couponRemoved) {
            $couponRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCoupons);
    }

    /**
     * Method called to associate a Coupon object to this object
     * through the Coupon foreign key attribute.
     *
     * @param  Coupon $l Coupon
     * @return $this|\API\Models\User\User The current object (for fluent API support)
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
        $coupon->setUser($this);
    }

    /**
     * @param  Coupon $coupon The Coupon object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $coupon->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Coupons from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Coupon[] List of Coupon objects
     */
    public function getCouponsJoinEvent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = CouponQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getCoupons($query, $con);
    }

    /**
     * Clears out the collDistributionPlaceUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionPlaceUsers()
     */
    public function clearDistributionPlaceUsers()
    {
        $this->collDistributionPlaceUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionPlaceUsers collection loaded partially.
     */
    public function resetPartialDistributionPlaceUsers($v = true)
    {
        $this->collDistributionPlaceUsersPartial = $v;
    }

    /**
     * Initializes the collDistributionPlaceUsers collection.
     *
     * By default this just sets the collDistributionPlaceUsers collection to an empty array (like clearcollDistributionPlaceUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionPlaceUsers($overrideExisting = true)
    {
        if (null !== $this->collDistributionPlaceUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionPlaceUserTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionPlaceUsers = new $collectionClassName;
        $this->collDistributionPlaceUsers->setModel('\API\Models\DistributionPlace\DistributionPlaceUser');
    }

    /**
     * Gets an array of DistributionPlaceUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionPlaceUser[] List of DistributionPlaceUser objects
     * @throws PropelException
     */
    public function getDistributionPlaceUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceUsersPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceUsers) {
                // return empty collection
                $this->initDistributionPlaceUsers();
            } else {
                $collDistributionPlaceUsers = DistributionPlaceUserQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionPlaceUsersPartial && count($collDistributionPlaceUsers)) {
                        $this->initDistributionPlaceUsers(false);

                        foreach ($collDistributionPlaceUsers as $obj) {
                            if (false == $this->collDistributionPlaceUsers->contains($obj)) {
                                $this->collDistributionPlaceUsers->append($obj);
                            }
                        }

                        $this->collDistributionPlaceUsersPartial = true;
                    }

                    return $collDistributionPlaceUsers;
                }

                if ($partial && $this->collDistributionPlaceUsers) {
                    foreach ($this->collDistributionPlaceUsers as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionPlaceUsers[] = $obj;
                        }
                    }
                }

                $this->collDistributionPlaceUsers = $collDistributionPlaceUsers;
                $this->collDistributionPlaceUsersPartial = false;
            }
        }

        return $this->collDistributionPlaceUsers;
    }

    /**
     * Sets a collection of DistributionPlaceUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setDistributionPlaceUsers(Collection $distributionPlaceUsers, ConnectionInterface $con = null)
    {
        /** @var DistributionPlaceUser[] $distributionPlaceUsersToDelete */
        $distributionPlaceUsersToDelete = $this->getDistributionPlaceUsers(new Criteria(), $con)->diff($distributionPlaceUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceUsersScheduledForDeletion = clone $distributionPlaceUsersToDelete;

        foreach ($distributionPlaceUsersToDelete as $distributionPlaceUserRemoved) {
            $distributionPlaceUserRemoved->setUser(null);
        }

        $this->collDistributionPlaceUsers = null;
        foreach ($distributionPlaceUsers as $distributionPlaceUser) {
            $this->addDistributionPlaceUser($distributionPlaceUser);
        }

        $this->collDistributionPlaceUsers = $distributionPlaceUsers;
        $this->collDistributionPlaceUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionPlaceUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionPlaceUser objects.
     * @throws PropelException
     */
    public function countDistributionPlaceUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceUsersPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionPlaceUsers());
            }

            $query = DistributionPlaceUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceUsers);
    }

    /**
     * Method called to associate a DistributionPlaceUser object to this object
     * through the DistributionPlaceUser foreign key attribute.
     *
     * @param  DistributionPlaceUser $l DistributionPlaceUser
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function addDistributionPlaceUser(DistributionPlaceUser $l)
    {
        if ($this->collDistributionPlaceUsers === null) {
            $this->initDistributionPlaceUsers();
            $this->collDistributionPlaceUsersPartial = true;
        }

        if (!$this->collDistributionPlaceUsers->contains($l)) {
            $this->doAddDistributionPlaceUser($l);

            if ($this->distributionPlaceUsersScheduledForDeletion and $this->distributionPlaceUsersScheduledForDeletion->contains($l)) {
                $this->distributionPlaceUsersScheduledForDeletion->remove($this->distributionPlaceUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionPlaceUser $distributionPlaceUser The DistributionPlaceUser object to add.
     */
    protected function doAddDistributionPlaceUser(DistributionPlaceUser $distributionPlaceUser)
    {
        $this->collDistributionPlaceUsers[]= $distributionPlaceUser;
        $distributionPlaceUser->setUser($this);
    }

    /**
     * @param  DistributionPlaceUser $distributionPlaceUser The DistributionPlaceUser object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeDistributionPlaceUser(DistributionPlaceUser $distributionPlaceUser)
    {
        if ($this->getDistributionPlaceUsers()->contains($distributionPlaceUser)) {
            $pos = $this->collDistributionPlaceUsers->search($distributionPlaceUser);
            $this->collDistributionPlaceUsers->remove($pos);
            if (null === $this->distributionPlaceUsersScheduledForDeletion) {
                $this->distributionPlaceUsersScheduledForDeletion = clone $this->collDistributionPlaceUsers;
                $this->distributionPlaceUsersScheduledForDeletion->clear();
            }
            $this->distributionPlaceUsersScheduledForDeletion[]= clone $distributionPlaceUser;
            $distributionPlaceUser->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related DistributionPlaceUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceUser[] List of DistributionPlaceUser objects
     */
    public function getDistributionPlaceUsersJoinDistributionPlace(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceUserQuery::create(null, $criteria);
        $query->joinWith('DistributionPlace', $joinBehavior);

        return $this->getDistributionPlaceUsers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related DistributionPlaceUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceUser[] List of DistributionPlaceUser objects
     */
    public function getDistributionPlaceUsersJoinEventPrinter(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceUserQuery::create(null, $criteria);
        $query->joinWith('EventPrinter', $joinBehavior);

        return $this->getDistributionPlaceUsers($query, $con);
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
        $this->collEventUsers->setModel('\API\Models\Event\EventUser');
    }

    /**
     * Gets an array of EventUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|EventUser[] List of EventUser objects
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
                $collEventUsers = EventUserQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * Sets a collection of EventUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setEventUsers(Collection $eventUsers, ConnectionInterface $con = null)
    {
        /** @var EventUser[] $eventUsersToDelete */
        $eventUsersToDelete = $this->getEventUsers(new Criteria(), $con)->diff($eventUsers);


        $this->eventUsersScheduledForDeletion = $eventUsersToDelete;

        foreach ($eventUsersToDelete as $eventUserRemoved) {
            $eventUserRemoved->setUser(null);
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
     * Returns the number of related BaseEventUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseEventUser objects.
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

            $query = EventUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collEventUsers);
    }

    /**
     * Method called to associate a EventUser object to this object
     * through the EventUser foreign key attribute.
     *
     * @param  EventUser $l EventUser
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function addEventUser(EventUser $l)
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
     * @param EventUser $eventUser The EventUser object to add.
     */
    protected function doAddEventUser(EventUser $eventUser)
    {
        $this->collEventUsers[]= $eventUser;
        $eventUser->setUser($this);
    }

    /**
     * @param  EventUser $eventUser The EventUser object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeEventUser(EventUser $eventUser)
    {
        if ($this->getEventUsers()->contains($eventUser)) {
            $pos = $this->collEventUsers->search($eventUser);
            $this->collEventUsers->remove($pos);
            if (null === $this->eventUsersScheduledForDeletion) {
                $this->eventUsersScheduledForDeletion = clone $this->collEventUsers;
                $this->eventUsersScheduledForDeletion->clear();
            }
            $this->eventUsersScheduledForDeletion[]= clone $eventUser;
            $eventUser->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related EventUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|EventUser[] List of EventUser objects
     */
    public function getEventUsersJoinEvent(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = EventUserQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getEventUsers($query, $con);
    }

    /**
     * Clears out the collInvoices collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoices()
     */
    public function clearInvoices()
    {
        $this->collInvoices = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoices collection loaded partially.
     */
    public function resetPartialInvoices($v = true)
    {
        $this->collInvoicesPartial = $v;
    }

    /**
     * Initializes the collInvoices collection.
     *
     * By default this just sets the collInvoices collection to an empty array (like clearcollInvoices());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoices($overrideExisting = true)
    {
        if (null !== $this->collInvoices && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoiceTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoices = new $collectionClassName;
        $this->collInvoices->setModel('\API\Models\Invoice\Invoice');
    }

    /**
     * Gets an array of Invoice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Invoice[] List of Invoice objects
     * @throws PropelException
     */
    public function getInvoices(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesPartial && !$this->isNew();
        if (null === $this->collInvoices || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoices) {
                // return empty collection
                $this->initInvoices();
            } else {
                $collInvoices = InvoiceQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoicesPartial && count($collInvoices)) {
                        $this->initInvoices(false);

                        foreach ($collInvoices as $obj) {
                            if (false == $this->collInvoices->contains($obj)) {
                                $this->collInvoices->append($obj);
                            }
                        }

                        $this->collInvoicesPartial = true;
                    }

                    return $collInvoices;
                }

                if ($partial && $this->collInvoices) {
                    foreach ($this->collInvoices as $obj) {
                        if ($obj->isNew()) {
                            $collInvoices[] = $obj;
                        }
                    }
                }

                $this->collInvoices = $collInvoices;
                $this->collInvoicesPartial = false;
            }
        }

        return $this->collInvoices;
    }

    /**
     * Sets a collection of Invoice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoices A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setInvoices(Collection $invoices, ConnectionInterface $con = null)
    {
        /** @var Invoice[] $invoicesToDelete */
        $invoicesToDelete = $this->getInvoices(new Criteria(), $con)->diff($invoices);


        $this->invoicesScheduledForDeletion = $invoicesToDelete;

        foreach ($invoicesToDelete as $invoiceRemoved) {
            $invoiceRemoved->setUser(null);
        }

        $this->collInvoices = null;
        foreach ($invoices as $invoice) {
            $this->addInvoice($invoice);
        }

        $this->collInvoices = $invoices;
        $this->collInvoicesPartial = false;

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
    public function countInvoices(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicesPartial && !$this->isNew();
        if (null === $this->collInvoices || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoices) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoices());
            }

            $query = InvoiceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collInvoices);
    }

    /**
     * Method called to associate a Invoice object to this object
     * through the Invoice foreign key attribute.
     *
     * @param  Invoice $l Invoice
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function addInvoice(Invoice $l)
    {
        if ($this->collInvoices === null) {
            $this->initInvoices();
            $this->collInvoicesPartial = true;
        }

        if (!$this->collInvoices->contains($l)) {
            $this->doAddInvoice($l);

            if ($this->invoicesScheduledForDeletion and $this->invoicesScheduledForDeletion->contains($l)) {
                $this->invoicesScheduledForDeletion->remove($this->invoicesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Invoice $invoice The Invoice object to add.
     */
    protected function doAddInvoice(Invoice $invoice)
    {
        $this->collInvoices[]= $invoice;
        $invoice->setUser($this);
    }

    /**
     * @param  Invoice $invoice The Invoice object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeInvoice(Invoice $invoice)
    {
        if ($this->getInvoices()->contains($invoice)) {
            $pos = $this->collInvoices->search($invoice);
            $this->collInvoices->remove($pos);
            if (null === $this->invoicesScheduledForDeletion) {
                $this->invoicesScheduledForDeletion = clone $this->collInvoices;
                $this->invoicesScheduledForDeletion->clear();
            }
            $this->invoicesScheduledForDeletion[]= clone $invoice;
            $invoice->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Invoices from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesJoinEventContactRelatedByCustomerid(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('EventContactRelatedByCustomerid', $joinBehavior);

        return $this->getInvoices($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Invoices from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Invoice[] List of Invoice objects
     */
    public function getInvoicesJoinEventContactRelatedByEventContactid(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceQuery::create(null, $criteria);
        $query->joinWith('EventContactRelatedByEventContactid', $joinBehavior);

        return $this->getInvoices($query, $con);
    }

    /**
     * Clears out the collOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrders()
     */
    public function clearOrders()
    {
        $this->collOrders = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrders collection loaded partially.
     */
    public function resetPartialOrders($v = true)
    {
        $this->collOrdersPartial = $v;
    }

    /**
     * Initializes the collOrders collection.
     *
     * By default this just sets the collOrders collection to an empty array (like clearcollOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrders($overrideExisting = true)
    {
        if (null !== $this->collOrders && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

        $this->collOrders = new $collectionClassName;
        $this->collOrders->setModel('\API\Models\Ordering\Order');
    }

    /**
     * Gets an array of Order objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Order[] List of Order objects
     * @throws PropelException
     */
    public function getOrders(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                // return empty collection
                $this->initOrders();
            } else {
                $collOrders = OrderQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersPartial && count($collOrders)) {
                        $this->initOrders(false);

                        foreach ($collOrders as $obj) {
                            if (false == $this->collOrders->contains($obj)) {
                                $this->collOrders->append($obj);
                            }
                        }

                        $this->collOrdersPartial = true;
                    }

                    return $collOrders;
                }

                if ($partial && $this->collOrders) {
                    foreach ($this->collOrders as $obj) {
                        if ($obj->isNew()) {
                            $collOrders[] = $obj;
                        }
                    }
                }

                $this->collOrders = $collOrders;
                $this->collOrdersPartial = false;
            }
        }

        return $this->collOrders;
    }

    /**
     * Sets a collection of Order objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orders A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setOrders(Collection $orders, ConnectionInterface $con = null)
    {
        /** @var Order[] $ordersToDelete */
        $ordersToDelete = $this->getOrders(new Criteria(), $con)->diff($orders);


        $this->ordersScheduledForDeletion = $ordersToDelete;

        foreach ($ordersToDelete as $orderRemoved) {
            $orderRemoved->setUser(null);
        }

        $this->collOrders = null;
        foreach ($orders as $order) {
            $this->addOrder($order);
        }

        $this->collOrders = $orders;
        $this->collOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrder objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrder objects.
     * @throws PropelException
     */
    public function countOrders(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrders());
            }

            $query = OrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOrders);
    }

    /**
     * Method called to associate a Order object to this object
     * through the Order foreign key attribute.
     *
     * @param  Order $l Order
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function addOrder(Order $l)
    {
        if ($this->collOrders === null) {
            $this->initOrders();
            $this->collOrdersPartial = true;
        }

        if (!$this->collOrders->contains($l)) {
            $this->doAddOrder($l);

            if ($this->ordersScheduledForDeletion and $this->ordersScheduledForDeletion->contains($l)) {
                $this->ordersScheduledForDeletion->remove($this->ordersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Order $order The Order object to add.
     */
    protected function doAddOrder(Order $order)
    {
        $this->collOrders[]= $order;
        $order->setUser($this);
    }

    /**
     * @param  Order $order The Order object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeOrder(Order $order)
    {
        if ($this->getOrders()->contains($order)) {
            $pos = $this->collOrders->search($order);
            $this->collOrders->remove($pos);
            if (null === $this->ordersScheduledForDeletion) {
                $this->ordersScheduledForDeletion = clone $this->collOrders;
                $this->ordersScheduledForDeletion->clear();
            }
            $this->ordersScheduledForDeletion[]= clone $order;
            $order->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Order[] List of Order objects
     */
    public function getOrdersJoinEventTable(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderQuery::create(null, $criteria);
        $query->joinWith('EventTable', $joinBehavior);

        return $this->getOrders($query, $con);
    }

    /**
     * Clears out the collOrderDetails collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrderDetails()
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
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
     * Gets an array of OrderDetail objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
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
                $collOrderDetails = OrderDetailQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * Sets a collection of OrderDetail objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderDetails A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setOrderDetails(Collection $orderDetails, ConnectionInterface $con = null)
    {
        /** @var OrderDetail[] $orderDetailsToDelete */
        $orderDetailsToDelete = $this->getOrderDetails(new Criteria(), $con)->diff($orderDetails);


        $this->orderDetailsScheduledForDeletion = $orderDetailsToDelete;

        foreach ($orderDetailsToDelete as $orderDetailRemoved) {
            $orderDetailRemoved->setUser(null);
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
     * Returns the number of related BaseOrderDetail objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrderDetail objects.
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

            $query = OrderDetailQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOrderDetails);
    }

    /**
     * Method called to associate a OrderDetail object to this object
     * through the OrderDetail foreign key attribute.
     *
     * @param  OrderDetail $l OrderDetail
     * @return $this|\API\Models\User\User The current object (for fluent API support)
     */
    public function addOrderDetail(OrderDetail $l)
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
     * @param OrderDetail $orderDetail The OrderDetail object to add.
     */
    protected function doAddOrderDetail(OrderDetail $orderDetail)
    {
        $this->collOrderDetails[]= $orderDetail;
        $orderDetail->setUser($this);
    }

    /**
     * @param  OrderDetail $orderDetail The OrderDetail object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeOrderDetail(OrderDetail $orderDetail)
    {
        if ($this->getOrderDetails()->contains($orderDetail)) {
            $pos = $this->collOrderDetails->search($orderDetail);
            $this->collOrderDetails->remove($pos);
            if (null === $this->orderDetailsScheduledForDeletion) {
                $this->orderDetailsScheduledForDeletion = clone $this->collOrderDetails;
                $this->orderDetailsScheduledForDeletion->clear();
            }
            $this->orderDetailsScheduledForDeletion[]= $orderDetail;
            $orderDetail->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinAvailability(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('Availability', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinMenuSize(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('MenuSize', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinMenu(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('Menu', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinOrder(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }

    /**
     * Clears out the collOrderInProgresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrderInProgresses()
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
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
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
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
                    ->filterByUser($this)
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
     * @param      Collection $orderInProgresses A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setOrderInProgresses(Collection $orderInProgresses, ConnectionInterface $con = null)
    {
        /** @var OrderInProgress[] $orderInProgressesToDelete */
        $orderInProgressesToDelete = $this->getOrderInProgresses(new Criteria(), $con)->diff($orderInProgresses);


        $this->orderInProgressesScheduledForDeletion = $orderInProgressesToDelete;

        foreach ($orderInProgressesToDelete as $orderInProgressRemoved) {
            $orderInProgressRemoved->setUser(null);
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOrderInProgresses);
    }

    /**
     * Method called to associate a OrderInProgress object to this object
     * through the OrderInProgress foreign key attribute.
     *
     * @param  OrderInProgress $l OrderInProgress
     * @return $this|\API\Models\User\User The current object (for fluent API support)
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
        $orderInProgress->setUser($this);
    }

    /**
     * @param  OrderInProgress $orderInProgress The OrderInProgress object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $orderInProgress->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
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
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related OrderInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderInProgress[] List of OrderInProgress objects
     */
    public function getOrderInProgressesJoinOrder(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderInProgressQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getOrderInProgresses($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->userid = null;
        $this->username = null;
        $this->password = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->autologin_hash = null;
        $this->active = null;
        $this->phonenumber = null;
        $this->call_request = null;
        $this->is_admin = null;
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
            if ($this->collDistributionPlaceUsers) {
                foreach ($this->collDistributionPlaceUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventUsers) {
                foreach ($this->collEventUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvoices) {
                foreach ($this->collInvoices as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrders) {
                foreach ($this->collOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
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

        $this->collCoupons = null;
        $this->collDistributionPlaceUsers = null;
        $this->collEventUsers = null;
        $this->collInvoices = null;
        $this->collOrders = null;
        $this->collOrderDetails = null;
        $this->collOrderInProgresses = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
