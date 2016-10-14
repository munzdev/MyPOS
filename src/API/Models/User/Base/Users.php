<?php

namespace API\Models\User\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlacesUsers;
use API\Models\DistributionPlace\DistributionsPlacesUsersQuery;
use API\Models\DistributionPlace\Base\DistributionsPlacesUsers as BaseDistributionsPlacesUsers;
use API\Models\DistributionPlace\Map\DistributionsPlacesUsersTableMap;
use API\Models\Event\EventsUser;
use API\Models\Event\EventsUserQuery;
use API\Models\Event\Base\EventsUser as BaseEventsUser;
use API\Models\Event\Map\EventsUserTableMap;
use API\Models\Invoice\Invoices;
use API\Models\Invoice\InvoicesQuery;
use API\Models\Invoice\Base\Invoices as BaseInvoices;
use API\Models\Invoice\Map\InvoicesTableMap;
use API\Models\OIP\OrdersInProgress;
use API\Models\OIP\OrdersInProgressQuery;
use API\Models\OIP\Base\OrdersInProgress as BaseOrdersInProgress;
use API\Models\OIP\Map\OrdersInProgressTableMap;
use API\Models\Ordering\Orders;
use API\Models\Ordering\OrdersDetails;
use API\Models\Ordering\OrdersDetailsQuery;
use API\Models\Ordering\OrdersQuery;
use API\Models\Ordering\Base\Orders as BaseOrders;
use API\Models\Ordering\Base\OrdersDetails as BaseOrdersDetails;
use API\Models\Ordering\Map\OrdersDetailsTableMap;
use API\Models\Ordering\Map\OrdersTableMap;
use API\Models\Payment\Coupons;
use API\Models\Payment\CouponsQuery;
use API\Models\Payment\Base\Coupons as BaseCoupons;
use API\Models\Payment\Map\CouponsTableMap;
use API\Models\User\Users as ChildUsers;
use API\Models\User\UsersQuery as ChildUsersQuery;
use API\Models\User\Map\UsersTableMap;
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
 * Base class that represents a row from the 'users' table.
 *
 *
 *
 * @package    propel.generator.API.Models.User.Base
 */
abstract class Users implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\User\\Map\\UsersTableMap';


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
     * @var        ObjectCollection|Coupons[] Collection to store aggregation of Coupons objects.
     */
    protected $collCouponss;
    protected $collCouponssPartial;

    /**
     * @var        ObjectCollection|DistributionsPlacesUsers[] Collection to store aggregation of DistributionsPlacesUsers objects.
     */
    protected $collDistributionsPlacesUserss;
    protected $collDistributionsPlacesUserssPartial;

    /**
     * @var        ObjectCollection|EventsUser[] Collection to store aggregation of EventsUser objects.
     */
    protected $collEventsUsers;
    protected $collEventsUsersPartial;

    /**
     * @var        ObjectCollection|Invoices[] Collection to store aggregation of Invoices objects.
     */
    protected $collInvoicess;
    protected $collInvoicessPartial;

    /**
     * @var        ObjectCollection|Orders[] Collection to store aggregation of Orders objects.
     */
    protected $collOrderss;
    protected $collOrderssPartial;

    /**
     * @var        ObjectCollection|OrdersDetails[] Collection to store aggregation of OrdersDetails objects.
     */
    protected $collOrdersDetailss;
    protected $collOrdersDetailssPartial;

    /**
     * @var        ObjectCollection|OrdersInProgress[] Collection to store aggregation of OrdersInProgress objects.
     */
    protected $collOrdersInProgresses;
    protected $collOrdersInProgressesPartial;

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
     * @var ObjectCollection|DistributionsPlacesUsers[]
     */
    protected $distributionsPlacesUserssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|EventsUser[]
     */
    protected $eventsUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Invoices[]
     */
    protected $invoicessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Orders[]
     */
    protected $orderssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersDetails[]
     */
    protected $ordersDetailssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersInProgress[]
     */
    protected $ordersInProgressesScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\User\Base\Users object.
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
     * Compares this with another <code>Users</code> instance.  If
     * <code>obj</code> is an instance of <code>Users</code>, delegates to
     * <code>equals(Users)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Users The current object, for fluid interface
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
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[UsersTableMap::COL_USERID] = true;
        }

        return $this;
    } // setUserid()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UsersTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UsersTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [firstname] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[UsersTableMap::COL_FIRSTNAME] = true;
        }

        return $this;
    } // setFirstname()

    /**
     * Set the value of [lastname] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[UsersTableMap::COL_LASTNAME] = true;
        }

        return $this;
    } // setLastname()

    /**
     * Set the value of [autologin_hash] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setAutologinHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->autologin_hash !== $v) {
            $this->autologin_hash = $v;
            $this->modifiedColumns[UsersTableMap::COL_AUTOLOGIN_HASH] = true;
        }

        return $this;
    } // setAutologinHash()

    /**
     * Set the value of [active] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setActive($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->active !== $v) {
            $this->active = $v;
            $this->modifiedColumns[UsersTableMap::COL_ACTIVE] = true;
        }

        return $this;
    } // setActive()

    /**
     * Set the value of [phonenumber] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setPhonenumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phonenumber !== $v) {
            $this->phonenumber = $v;
            $this->modifiedColumns[UsersTableMap::COL_PHONENUMBER] = true;
        }

        return $this;
    } // setPhonenumber()

    /**
     * Sets the value of [call_request] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function setCallRequest($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->call_request !== null || $dt !== null) {
            if ($this->call_request === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->call_request->format("Y-m-d H:i:s.u")) {
                $this->call_request = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UsersTableMap::COL_CALL_REQUEST] = true;
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
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
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
            $this->modifiedColumns[UsersTableMap::COL_IS_ADMIN] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UsersTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UsersTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UsersTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UsersTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UsersTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UsersTableMap::translateFieldName('AutologinHash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->autologin_hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UsersTableMap::translateFieldName('Active', TableMap::TYPE_PHPNAME, $indexType)];
            $this->active = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UsersTableMap::translateFieldName('Phonenumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->phonenumber = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UsersTableMap::translateFieldName('CallRequest', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->call_request = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UsersTableMap::translateFieldName('IsAdmin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_admin = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = UsersTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\User\\Users'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UsersTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUsersQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCouponss = null;

            $this->collDistributionsPlacesUserss = null;

            $this->collEventsUsers = null;

            $this->collInvoicess = null;

            $this->collOrderss = null;

            $this->collOrdersDetailss = null;

            $this->collOrdersInProgresses = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Users::setDeleted()
     * @see Users::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUsersQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
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
                UsersTableMap::addInstanceToPool($this);
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

            if ($this->distributionsPlacesUserssScheduledForDeletion !== null) {
                if (!$this->distributionsPlacesUserssScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionsPlacesUsersQuery::create()
                        ->filterByPrimaryKeys($this->distributionsPlacesUserssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionsPlacesUserssScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionsPlacesUserss !== null) {
                foreach ($this->collDistributionsPlacesUserss as $referrerFK) {
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

            if ($this->invoicessScheduledForDeletion !== null) {
                if (!$this->invoicessScheduledForDeletion->isEmpty()) {
                    \API\Models\Invoice\InvoicesQuery::create()
                        ->filterByPrimaryKeys($this->invoicessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->invoicessScheduledForDeletion = null;
                }
            }

            if ($this->collInvoicess !== null) {
                foreach ($this->collInvoicess as $referrerFK) {
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

            if ($this->ordersDetailssScheduledForDeletion !== null) {
                if (!$this->ordersDetailssScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrdersDetailsQuery::create()
                        ->filterByPrimaryKeys($this->ordersDetailssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersDetailssScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersDetailss !== null) {
                foreach ($this->collOrdersDetailss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersInProgressesScheduledForDeletion !== null) {
                if (!$this->ordersInProgressesScheduledForDeletion->isEmpty()) {
                    \API\Models\OIP\OrdersInProgressQuery::create()
                        ->filterByPrimaryKeys($this->ordersInProgressesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersInProgressesScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersInProgresses !== null) {
                foreach ($this->collOrdersInProgresses as $referrerFK) {
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

        $this->modifiedColumns[UsersTableMap::COL_USERID] = true;
        if (null !== $this->userid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UsersTableMap::COL_USERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UsersTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userid';
        }
        if ($this->isColumnModified(UsersTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(UsersTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UsersTableMap::COL_FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'firstname';
        }
        if ($this->isColumnModified(UsersTableMap::COL_LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'lastname';
        }
        if ($this->isColumnModified(UsersTableMap::COL_AUTOLOGIN_HASH)) {
            $modifiedColumns[':p' . $index++]  = 'autologin_hash';
        }
        if ($this->isColumnModified(UsersTableMap::COL_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'active';
        }
        if ($this->isColumnModified(UsersTableMap::COL_PHONENUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'phonenumber';
        }
        if ($this->isColumnModified(UsersTableMap::COL_CALL_REQUEST)) {
            $modifiedColumns[':p' . $index++]  = 'call_request';
        }
        if ($this->isColumnModified(UsersTableMap::COL_IS_ADMIN)) {
            $modifiedColumns[':p' . $index++]  = 'is_admin';
        }

        $sql = sprintf(
            'INSERT INTO users (%s) VALUES (%s)',
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
        $pos = UsersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['Users'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Users'][$this->hashCode()] = true;
        $keys = UsersTableMap::getFieldNames($keyType);
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
            if (null !== $this->collDistributionsPlacesUserss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionsPlacesUserss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distributions_places_userss';
                        break;
                    default:
                        $key = 'DistributionsPlacesUserss';
                }

                $result[$key] = $this->collDistributionsPlacesUserss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collInvoicess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'invoicess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'invoicess';
                        break;
                    default:
                        $key = 'Invoicess';
                }

                $result[$key] = $this->collInvoicess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collOrdersDetailss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersDetailss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_detailss';
                        break;
                    default:
                        $key = 'OrdersDetailss';
                }

                $result[$key] = $this->collOrdersDetailss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrdersInProgresses) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersInProgresses';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_in_progresses';
                        break;
                    default:
                        $key = 'OrdersInProgresses';
                }

                $result[$key] = $this->collOrdersInProgresses->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\User\Users
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UsersTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\User\Users
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
        $keys = UsersTableMap::getFieldNames($keyType);

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
     * @return $this|\API\Models\User\Users The current object, for fluid interface
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
        $criteria = new Criteria(UsersTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UsersTableMap::COL_USERID)) {
            $criteria->add(UsersTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(UsersTableMap::COL_USERNAME)) {
            $criteria->add(UsersTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UsersTableMap::COL_PASSWORD)) {
            $criteria->add(UsersTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UsersTableMap::COL_FIRSTNAME)) {
            $criteria->add(UsersTableMap::COL_FIRSTNAME, $this->firstname);
        }
        if ($this->isColumnModified(UsersTableMap::COL_LASTNAME)) {
            $criteria->add(UsersTableMap::COL_LASTNAME, $this->lastname);
        }
        if ($this->isColumnModified(UsersTableMap::COL_AUTOLOGIN_HASH)) {
            $criteria->add(UsersTableMap::COL_AUTOLOGIN_HASH, $this->autologin_hash);
        }
        if ($this->isColumnModified(UsersTableMap::COL_ACTIVE)) {
            $criteria->add(UsersTableMap::COL_ACTIVE, $this->active);
        }
        if ($this->isColumnModified(UsersTableMap::COL_PHONENUMBER)) {
            $criteria->add(UsersTableMap::COL_PHONENUMBER, $this->phonenumber);
        }
        if ($this->isColumnModified(UsersTableMap::COL_CALL_REQUEST)) {
            $criteria->add(UsersTableMap::COL_CALL_REQUEST, $this->call_request);
        }
        if ($this->isColumnModified(UsersTableMap::COL_IS_ADMIN)) {
            $criteria->add(UsersTableMap::COL_IS_ADMIN, $this->is_admin);
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
        $criteria = ChildUsersQuery::create();
        $criteria->add(UsersTableMap::COL_USERID, $this->userid);

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
     * @param      object $copyObj An object of \API\Models\User\Users (or compatible) type.
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

            foreach ($this->getCouponss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCoupons($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDistributionsPlacesUserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionsPlacesUsers($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventsUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventsUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInvoicess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoices($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrders($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersDetailss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetails($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersInProgresses() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersInProgress($relObj->copy($deepCopy));
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
     * @return \API\Models\User\Users Clone of current object.
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
        if ('DistributionsPlacesUsers' == $relationName) {
            return $this->initDistributionsPlacesUserss();
        }
        if ('EventsUser' == $relationName) {
            return $this->initEventsUsers();
        }
        if ('Invoices' == $relationName) {
            return $this->initInvoicess();
        }
        if ('Orders' == $relationName) {
            return $this->initOrderss();
        }
        if ('OrdersDetails' == $relationName) {
            return $this->initOrdersDetailss();
        }
        if ('OrdersInProgress' == $relationName) {
            return $this->initOrdersInProgresses();
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
     * If this ChildUsers is new, it will return
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
                    ->filterByUsers($this)
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
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $couponsRemoved->setUsers(null);
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
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collCouponss);
    }

    /**
     * Method called to associate a Coupons object to this object
     * through the Coupons foreign key attribute.
     *
     * @param  Coupons $l Coupons
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
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
        $coupons->setUsers($this);
    }

    /**
     * @param  Coupons $coupons The Coupons object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $coupons->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related Couponss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Coupons[] List of Coupons objects
     */
    public function getCouponssJoinEvents(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = CouponsQuery::create(null, $criteria);
        $query->joinWith('Events', $joinBehavior);

        return $this->getCouponss($query, $con);
    }

    /**
     * Clears out the collDistributionsPlacesUserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionsPlacesUserss()
     */
    public function clearDistributionsPlacesUserss()
    {
        $this->collDistributionsPlacesUserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionsPlacesUserss collection loaded partially.
     */
    public function resetPartialDistributionsPlacesUserss($v = true)
    {
        $this->collDistributionsPlacesUserssPartial = $v;
    }

    /**
     * Initializes the collDistributionsPlacesUserss collection.
     *
     * By default this just sets the collDistributionsPlacesUserss collection to an empty array (like clearcollDistributionsPlacesUserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionsPlacesUserss($overrideExisting = true)
    {
        if (null !== $this->collDistributionsPlacesUserss && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionsPlacesUsersTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionsPlacesUserss = new $collectionClassName;
        $this->collDistributionsPlacesUserss->setModel('\API\Models\DistributionPlace\DistributionsPlacesUsers');
    }

    /**
     * Gets an array of DistributionsPlacesUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionsPlacesUsers[] List of DistributionsPlacesUsers objects
     * @throws PropelException
     */
    public function getDistributionsPlacesUserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesUserssPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesUserss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesUserss) {
                // return empty collection
                $this->initDistributionsPlacesUserss();
            } else {
                $collDistributionsPlacesUserss = DistributionsPlacesUsersQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionsPlacesUserssPartial && count($collDistributionsPlacesUserss)) {
                        $this->initDistributionsPlacesUserss(false);

                        foreach ($collDistributionsPlacesUserss as $obj) {
                            if (false == $this->collDistributionsPlacesUserss->contains($obj)) {
                                $this->collDistributionsPlacesUserss->append($obj);
                            }
                        }

                        $this->collDistributionsPlacesUserssPartial = true;
                    }

                    return $collDistributionsPlacesUserss;
                }

                if ($partial && $this->collDistributionsPlacesUserss) {
                    foreach ($this->collDistributionsPlacesUserss as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionsPlacesUserss[] = $obj;
                        }
                    }
                }

                $this->collDistributionsPlacesUserss = $collDistributionsPlacesUserss;
                $this->collDistributionsPlacesUserssPartial = false;
            }
        }

        return $this->collDistributionsPlacesUserss;
    }

    /**
     * Sets a collection of DistributionsPlacesUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setDistributionsPlacesUserss(Collection $distributionsPlacesUserss, ConnectionInterface $con = null)
    {
        /** @var DistributionsPlacesUsers[] $distributionsPlacesUserssToDelete */
        $distributionsPlacesUserssToDelete = $this->getDistributionsPlacesUserss(new Criteria(), $con)->diff($distributionsPlacesUserss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesUserssScheduledForDeletion = clone $distributionsPlacesUserssToDelete;

        foreach ($distributionsPlacesUserssToDelete as $distributionsPlacesUsersRemoved) {
            $distributionsPlacesUsersRemoved->setUsers(null);
        }

        $this->collDistributionsPlacesUserss = null;
        foreach ($distributionsPlacesUserss as $distributionsPlacesUsers) {
            $this->addDistributionsPlacesUsers($distributionsPlacesUsers);
        }

        $this->collDistributionsPlacesUserss = $distributionsPlacesUserss;
        $this->collDistributionsPlacesUserssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionsPlacesUsers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionsPlacesUsers objects.
     * @throws PropelException
     */
    public function countDistributionsPlacesUserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesUserssPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesUserss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionsPlacesUserss());
            }

            $query = DistributionsPlacesUsersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesUserss);
    }

    /**
     * Method called to associate a DistributionsPlacesUsers object to this object
     * through the DistributionsPlacesUsers foreign key attribute.
     *
     * @param  DistributionsPlacesUsers $l DistributionsPlacesUsers
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function addDistributionsPlacesUsers(DistributionsPlacesUsers $l)
    {
        if ($this->collDistributionsPlacesUserss === null) {
            $this->initDistributionsPlacesUserss();
            $this->collDistributionsPlacesUserssPartial = true;
        }

        if (!$this->collDistributionsPlacesUserss->contains($l)) {
            $this->doAddDistributionsPlacesUsers($l);

            if ($this->distributionsPlacesUserssScheduledForDeletion and $this->distributionsPlacesUserssScheduledForDeletion->contains($l)) {
                $this->distributionsPlacesUserssScheduledForDeletion->remove($this->distributionsPlacesUserssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionsPlacesUsers $distributionsPlacesUsers The DistributionsPlacesUsers object to add.
     */
    protected function doAddDistributionsPlacesUsers(DistributionsPlacesUsers $distributionsPlacesUsers)
    {
        $this->collDistributionsPlacesUserss[]= $distributionsPlacesUsers;
        $distributionsPlacesUsers->setUsers($this);
    }

    /**
     * @param  DistributionsPlacesUsers $distributionsPlacesUsers The DistributionsPlacesUsers object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeDistributionsPlacesUsers(DistributionsPlacesUsers $distributionsPlacesUsers)
    {
        if ($this->getDistributionsPlacesUserss()->contains($distributionsPlacesUsers)) {
            $pos = $this->collDistributionsPlacesUserss->search($distributionsPlacesUsers);
            $this->collDistributionsPlacesUserss->remove($pos);
            if (null === $this->distributionsPlacesUserssScheduledForDeletion) {
                $this->distributionsPlacesUserssScheduledForDeletion = clone $this->collDistributionsPlacesUserss;
                $this->distributionsPlacesUserssScheduledForDeletion->clear();
            }
            $this->distributionsPlacesUserssScheduledForDeletion[]= clone $distributionsPlacesUsers;
            $distributionsPlacesUsers->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related DistributionsPlacesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesUsers[] List of DistributionsPlacesUsers objects
     */
    public function getDistributionsPlacesUserssJoinDistributionsPlaces(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesUsersQuery::create(null, $criteria);
        $query->joinWith('DistributionsPlaces', $joinBehavior);

        return $this->getDistributionsPlacesUserss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related DistributionsPlacesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesUsers[] List of DistributionsPlacesUsers objects
     */
    public function getDistributionsPlacesUserssJoinEventsPrinters(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesUsersQuery::create(null, $criteria);
        $query->joinWith('EventsPrinters', $joinBehavior);

        return $this->getDistributionsPlacesUserss($query, $con);
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
     * Gets an array of EventsUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|EventsUser[] List of EventsUser objects
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
                $collEventsUsers = EventsUserQuery::create(null, $criteria)
                    ->filterByUsers($this)
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
     * Sets a collection of EventsUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventsUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setEventsUsers(Collection $eventsUsers, ConnectionInterface $con = null)
    {
        /** @var EventsUser[] $eventsUsersToDelete */
        $eventsUsersToDelete = $this->getEventsUsers(new Criteria(), $con)->diff($eventsUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventsUsersScheduledForDeletion = clone $eventsUsersToDelete;

        foreach ($eventsUsersToDelete as $eventsUserRemoved) {
            $eventsUserRemoved->setUsers(null);
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
     * Returns the number of related BaseEventsUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseEventsUser objects.
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

            $query = EventsUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collEventsUsers);
    }

    /**
     * Method called to associate a EventsUser object to this object
     * through the EventsUser foreign key attribute.
     *
     * @param  EventsUser $l EventsUser
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function addEventsUser(EventsUser $l)
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
     * @param EventsUser $eventsUser The EventsUser object to add.
     */
    protected function doAddEventsUser(EventsUser $eventsUser)
    {
        $this->collEventsUsers[]= $eventsUser;
        $eventsUser->setUsers($this);
    }

    /**
     * @param  EventsUser $eventsUser The EventsUser object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeEventsUser(EventsUser $eventsUser)
    {
        if ($this->getEventsUsers()->contains($eventsUser)) {
            $pos = $this->collEventsUsers->search($eventsUser);
            $this->collEventsUsers->remove($pos);
            if (null === $this->eventsUsersScheduledForDeletion) {
                $this->eventsUsersScheduledForDeletion = clone $this->collEventsUsers;
                $this->eventsUsersScheduledForDeletion->clear();
            }
            $this->eventsUsersScheduledForDeletion[]= clone $eventsUser;
            $eventsUser->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related EventsUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|EventsUser[] List of EventsUser objects
     */
    public function getEventsUsersJoinEvents(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = EventsUserQuery::create(null, $criteria);
        $query->joinWith('Events', $joinBehavior);

        return $this->getEventsUsers($query, $con);
    }

    /**
     * Clears out the collInvoicess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInvoicess()
     */
    public function clearInvoicess()
    {
        $this->collInvoicess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInvoicess collection loaded partially.
     */
    public function resetPartialInvoicess($v = true)
    {
        $this->collInvoicessPartial = $v;
    }

    /**
     * Initializes the collInvoicess collection.
     *
     * By default this just sets the collInvoicess collection to an empty array (like clearcollInvoicess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInvoicess($overrideExisting = true)
    {
        if (null !== $this->collInvoicess && !$overrideExisting) {
            return;
        }

        $collectionClassName = InvoicesTableMap::getTableMap()->getCollectionClassName();

        $this->collInvoicess = new $collectionClassName;
        $this->collInvoicess->setModel('\API\Models\Invoice\Invoices');
    }

    /**
     * Gets an array of Invoices objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Invoices[] List of Invoices objects
     * @throws PropelException
     */
    public function getInvoicess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicessPartial && !$this->isNew();
        if (null === $this->collInvoicess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collInvoicess) {
                // return empty collection
                $this->initInvoicess();
            } else {
                $collInvoicess = InvoicesQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInvoicessPartial && count($collInvoicess)) {
                        $this->initInvoicess(false);

                        foreach ($collInvoicess as $obj) {
                            if (false == $this->collInvoicess->contains($obj)) {
                                $this->collInvoicess->append($obj);
                            }
                        }

                        $this->collInvoicessPartial = true;
                    }

                    return $collInvoicess;
                }

                if ($partial && $this->collInvoicess) {
                    foreach ($this->collInvoicess as $obj) {
                        if ($obj->isNew()) {
                            $collInvoicess[] = $obj;
                        }
                    }
                }

                $this->collInvoicess = $collInvoicess;
                $this->collInvoicessPartial = false;
            }
        }

        return $this->collInvoicess;
    }

    /**
     * Sets a collection of Invoices objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoicess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setInvoicess(Collection $invoicess, ConnectionInterface $con = null)
    {
        /** @var Invoices[] $invoicessToDelete */
        $invoicessToDelete = $this->getInvoicess(new Criteria(), $con)->diff($invoicess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->invoicessScheduledForDeletion = clone $invoicessToDelete;

        foreach ($invoicessToDelete as $invoicesRemoved) {
            $invoicesRemoved->setUsers(null);
        }

        $this->collInvoicess = null;
        foreach ($invoicess as $invoices) {
            $this->addInvoices($invoices);
        }

        $this->collInvoicess = $invoicess;
        $this->collInvoicessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseInvoices objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseInvoices objects.
     * @throws PropelException
     */
    public function countInvoicess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInvoicessPartial && !$this->isNew();
        if (null === $this->collInvoicess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInvoicess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInvoicess());
            }

            $query = InvoicesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collInvoicess);
    }

    /**
     * Method called to associate a Invoices object to this object
     * through the Invoices foreign key attribute.
     *
     * @param  Invoices $l Invoices
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function addInvoices(Invoices $l)
    {
        if ($this->collInvoicess === null) {
            $this->initInvoicess();
            $this->collInvoicessPartial = true;
        }

        if (!$this->collInvoicess->contains($l)) {
            $this->doAddInvoices($l);

            if ($this->invoicessScheduledForDeletion and $this->invoicessScheduledForDeletion->contains($l)) {
                $this->invoicessScheduledForDeletion->remove($this->invoicessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Invoices $invoices The Invoices object to add.
     */
    protected function doAddInvoices(Invoices $invoices)
    {
        $this->collInvoicess[]= $invoices;
        $invoices->setUsers($this);
    }

    /**
     * @param  Invoices $invoices The Invoices object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeInvoices(Invoices $invoices)
    {
        if ($this->getInvoicess()->contains($invoices)) {
            $pos = $this->collInvoicess->search($invoices);
            $this->collInvoicess->remove($pos);
            if (null === $this->invoicessScheduledForDeletion) {
                $this->invoicessScheduledForDeletion = clone $this->collInvoicess;
                $this->invoicessScheduledForDeletion->clear();
            }
            $this->invoicessScheduledForDeletion[]= clone $invoices;
            $invoices->setUsers(null);
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
     * If this ChildUsers is new, it will return
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
                    ->filterByUsers($this)
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
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $ordersRemoved->setUsers(null);
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
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collOrderss);
    }

    /**
     * Method called to associate a Orders object to this object
     * through the Orders foreign key attribute.
     *
     * @param  Orders $l Orders
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
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
        $orders->setUsers($this);
    }

    /**
     * @param  Orders $orders The Orders object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
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
            $orders->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related Orderss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Orders[] List of Orders objects
     */
    public function getOrderssJoinEvents(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersQuery::create(null, $criteria);
        $query->joinWith('Events', $joinBehavior);

        return $this->getOrderss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related Orderss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
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
     * Clears out the collOrdersDetailss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersDetailss()
     */
    public function clearOrdersDetailss()
    {
        $this->collOrdersDetailss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersDetailss collection loaded partially.
     */
    public function resetPartialOrdersDetailss($v = true)
    {
        $this->collOrdersDetailssPartial = $v;
    }

    /**
     * Initializes the collOrdersDetailss collection.
     *
     * By default this just sets the collOrdersDetailss collection to an empty array (like clearcollOrdersDetailss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersDetailss($overrideExisting = true)
    {
        if (null !== $this->collOrdersDetailss && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersDetailsTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersDetailss = new $collectionClassName;
        $this->collOrdersDetailss->setModel('\API\Models\Ordering\OrdersDetails');
    }

    /**
     * Gets an array of OrdersDetails objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     * @throws PropelException
     */
    public function getOrdersDetailss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailssPartial && !$this->isNew();
        if (null === $this->collOrdersDetailss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailss) {
                // return empty collection
                $this->initOrdersDetailss();
            } else {
                $collOrdersDetailss = OrdersDetailsQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersDetailssPartial && count($collOrdersDetailss)) {
                        $this->initOrdersDetailss(false);

                        foreach ($collOrdersDetailss as $obj) {
                            if (false == $this->collOrdersDetailss->contains($obj)) {
                                $this->collOrdersDetailss->append($obj);
                            }
                        }

                        $this->collOrdersDetailssPartial = true;
                    }

                    return $collOrdersDetailss;
                }

                if ($partial && $this->collOrdersDetailss) {
                    foreach ($this->collOrdersDetailss as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersDetailss[] = $obj;
                        }
                    }
                }

                $this->collOrdersDetailss = $collOrdersDetailss;
                $this->collOrdersDetailssPartial = false;
            }
        }

        return $this->collOrdersDetailss;
    }

    /**
     * Sets a collection of OrdersDetails objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersDetailss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setOrdersDetailss(Collection $ordersDetailss, ConnectionInterface $con = null)
    {
        /** @var OrdersDetails[] $ordersDetailssToDelete */
        $ordersDetailssToDelete = $this->getOrdersDetailss(new Criteria(), $con)->diff($ordersDetailss);


        $this->ordersDetailssScheduledForDeletion = $ordersDetailssToDelete;

        foreach ($ordersDetailssToDelete as $ordersDetailsRemoved) {
            $ordersDetailsRemoved->setUsers(null);
        }

        $this->collOrdersDetailss = null;
        foreach ($ordersDetailss as $ordersDetails) {
            $this->addOrdersDetails($ordersDetails);
        }

        $this->collOrdersDetailss = $ordersDetailss;
        $this->collOrdersDetailssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrdersDetails objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrdersDetails objects.
     * @throws PropelException
     */
    public function countOrdersDetailss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailssPartial && !$this->isNew();
        if (null === $this->collOrdersDetailss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersDetailss());
            }

            $query = OrdersDetailsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collOrdersDetailss);
    }

    /**
     * Method called to associate a OrdersDetails object to this object
     * through the OrdersDetails foreign key attribute.
     *
     * @param  OrdersDetails $l OrdersDetails
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function addOrdersDetails(OrdersDetails $l)
    {
        if ($this->collOrdersDetailss === null) {
            $this->initOrdersDetailss();
            $this->collOrdersDetailssPartial = true;
        }

        if (!$this->collOrdersDetailss->contains($l)) {
            $this->doAddOrdersDetails($l);

            if ($this->ordersDetailssScheduledForDeletion and $this->ordersDetailssScheduledForDeletion->contains($l)) {
                $this->ordersDetailssScheduledForDeletion->remove($this->ordersDetailssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param OrdersDetails $ordersDetails The OrdersDetails object to add.
     */
    protected function doAddOrdersDetails(OrdersDetails $ordersDetails)
    {
        $this->collOrdersDetailss[]= $ordersDetails;
        $ordersDetails->setUsers($this);
    }

    /**
     * @param  OrdersDetails $ordersDetails The OrdersDetails object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeOrdersDetails(OrdersDetails $ordersDetails)
    {
        if ($this->getOrdersDetailss()->contains($ordersDetails)) {
            $pos = $this->collOrdersDetailss->search($ordersDetails);
            $this->collOrdersDetailss->remove($pos);
            if (null === $this->ordersDetailssScheduledForDeletion) {
                $this->ordersDetailssScheduledForDeletion = clone $this->collOrdersDetailss;
                $this->ordersDetailssScheduledForDeletion->clear();
            }
            $this->ordersDetailssScheduledForDeletion[]= $ordersDetails;
            $ordersDetails->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinAvailabilitys(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Availabilitys', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinMenuSizes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('MenuSizes', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinMenues(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Menues', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinOrders(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Orders', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
    }

    /**
     * Clears out the collOrdersInProgresses collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersInProgresses()
     */
    public function clearOrdersInProgresses()
    {
        $this->collOrdersInProgresses = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersInProgresses collection loaded partially.
     */
    public function resetPartialOrdersInProgresses($v = true)
    {
        $this->collOrdersInProgressesPartial = $v;
    }

    /**
     * Initializes the collOrdersInProgresses collection.
     *
     * By default this just sets the collOrdersInProgresses collection to an empty array (like clearcollOrdersInProgresses());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersInProgresses($overrideExisting = true)
    {
        if (null !== $this->collOrdersInProgresses && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersInProgressTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersInProgresses = new $collectionClassName;
        $this->collOrdersInProgresses->setModel('\API\Models\OIP\OrdersInProgress');
    }

    /**
     * Gets an array of OrdersInProgress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUsers is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     * @throws PropelException
     */
    public function getOrdersInProgresses(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersInProgressesPartial && !$this->isNew();
        if (null === $this->collOrdersInProgresses || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersInProgresses) {
                // return empty collection
                $this->initOrdersInProgresses();
            } else {
                $collOrdersInProgresses = OrdersInProgressQuery::create(null, $criteria)
                    ->filterByUsers($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersInProgressesPartial && count($collOrdersInProgresses)) {
                        $this->initOrdersInProgresses(false);

                        foreach ($collOrdersInProgresses as $obj) {
                            if (false == $this->collOrdersInProgresses->contains($obj)) {
                                $this->collOrdersInProgresses->append($obj);
                            }
                        }

                        $this->collOrdersInProgressesPartial = true;
                    }

                    return $collOrdersInProgresses;
                }

                if ($partial && $this->collOrdersInProgresses) {
                    foreach ($this->collOrdersInProgresses as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersInProgresses[] = $obj;
                        }
                    }
                }

                $this->collOrdersInProgresses = $collOrdersInProgresses;
                $this->collOrdersInProgressesPartial = false;
            }
        }

        return $this->collOrdersInProgresses;
    }

    /**
     * Sets a collection of OrdersInProgress objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersInProgresses A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function setOrdersInProgresses(Collection $ordersInProgresses, ConnectionInterface $con = null)
    {
        /** @var OrdersInProgress[] $ordersInProgressesToDelete */
        $ordersInProgressesToDelete = $this->getOrdersInProgresses(new Criteria(), $con)->diff($ordersInProgresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersInProgressesScheduledForDeletion = clone $ordersInProgressesToDelete;

        foreach ($ordersInProgressesToDelete as $ordersInProgressRemoved) {
            $ordersInProgressRemoved->setUsers(null);
        }

        $this->collOrdersInProgresses = null;
        foreach ($ordersInProgresses as $ordersInProgress) {
            $this->addOrdersInProgress($ordersInProgress);
        }

        $this->collOrdersInProgresses = $ordersInProgresses;
        $this->collOrdersInProgressesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrdersInProgress objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrdersInProgress objects.
     * @throws PropelException
     */
    public function countOrdersInProgresses(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersInProgressesPartial && !$this->isNew();
        if (null === $this->collOrdersInProgresses || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersInProgresses) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersInProgresses());
            }

            $query = OrdersInProgressQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUsers($this)
                ->count($con);
        }

        return count($this->collOrdersInProgresses);
    }

    /**
     * Method called to associate a OrdersInProgress object to this object
     * through the OrdersInProgress foreign key attribute.
     *
     * @param  OrdersInProgress $l OrdersInProgress
     * @return $this|\API\Models\User\Users The current object (for fluent API support)
     */
    public function addOrdersInProgress(OrdersInProgress $l)
    {
        if ($this->collOrdersInProgresses === null) {
            $this->initOrdersInProgresses();
            $this->collOrdersInProgressesPartial = true;
        }

        if (!$this->collOrdersInProgresses->contains($l)) {
            $this->doAddOrdersInProgress($l);

            if ($this->ordersInProgressesScheduledForDeletion and $this->ordersInProgressesScheduledForDeletion->contains($l)) {
                $this->ordersInProgressesScheduledForDeletion->remove($this->ordersInProgressesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param OrdersInProgress $ordersInProgress The OrdersInProgress object to add.
     */
    protected function doAddOrdersInProgress(OrdersInProgress $ordersInProgress)
    {
        $this->collOrdersInProgresses[]= $ordersInProgress;
        $ordersInProgress->setUsers($this);
    }

    /**
     * @param  OrdersInProgress $ordersInProgress The OrdersInProgress object to remove.
     * @return $this|ChildUsers The current object (for fluent API support)
     */
    public function removeOrdersInProgress(OrdersInProgress $ordersInProgress)
    {
        if ($this->getOrdersInProgresses()->contains($ordersInProgress)) {
            $pos = $this->collOrdersInProgresses->search($ordersInProgress);
            $this->collOrdersInProgresses->remove($pos);
            if (null === $this->ordersInProgressesScheduledForDeletion) {
                $this->ordersInProgressesScheduledForDeletion = clone $this->collOrdersInProgresses;
                $this->ordersInProgressesScheduledForDeletion->clear();
            }
            $this->ordersInProgressesScheduledForDeletion[]= clone $ordersInProgress;
            $ordersInProgress->setUsers(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     */
    public function getOrdersInProgressesJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getOrdersInProgresses($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Users is new, it will return
     * an empty collection; or if this Users has previously
     * been saved, it will retrieve related OrdersInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Users.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     */
    public function getOrdersInProgressesJoinOrders(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressQuery::create(null, $criteria);
        $query->joinWith('Orders', $joinBehavior);

        return $this->getOrdersInProgresses($query, $con);
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
            if ($this->collCouponss) {
                foreach ($this->collCouponss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDistributionsPlacesUserss) {
                foreach ($this->collDistributionsPlacesUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventsUsers) {
                foreach ($this->collEventsUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInvoicess) {
                foreach ($this->collInvoicess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderss) {
                foreach ($this->collOrderss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersDetailss) {
                foreach ($this->collOrdersDetailss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersInProgresses) {
                foreach ($this->collOrdersInProgresses as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCouponss = null;
        $this->collDistributionsPlacesUserss = null;
        $this->collEventsUsers = null;
        $this->collInvoicess = null;
        $this->collOrderss = null;
        $this->collOrdersDetailss = null;
        $this->collOrdersInProgresses = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UsersTableMap::DEFAULT_STRING_FORMAT);
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
