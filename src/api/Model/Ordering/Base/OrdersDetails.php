<?php

namespace Model\Ordering\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Invoice\InvoicesItems;
use Model\Invoice\InvoicesItemsQuery;
use Model\Invoice\Base\InvoicesItems as BaseInvoicesItems;
use Model\Invoice\Map\InvoicesItemsTableMap;
use Model\Menues\Availabilitys;
use Model\Menues\AvailabilitysQuery;
use Model\Menues\MenuGroupes;
use Model\Menues\MenuGroupesQuery;
use Model\Menues\MenuSizes;
use Model\Menues\MenuSizesQuery;
use Model\Menues\Menues;
use Model\Menues\MenuesQuery;
use Model\OIP\OrdersInProgressRecieved;
use Model\OIP\OrdersInProgressRecievedQuery;
use Model\OIP\Base\OrdersInProgressRecieved as BaseOrdersInProgressRecieved;
use Model\OIP\Map\OrdersInProgressRecievedTableMap;
use Model\Ordering\Orders as ChildOrders;
use Model\Ordering\OrdersDetailExtras as ChildOrdersDetailExtras;
use Model\Ordering\OrdersDetailExtrasQuery as ChildOrdersDetailExtrasQuery;
use Model\Ordering\OrdersDetails as ChildOrdersDetails;
use Model\Ordering\OrdersDetailsMixedWith as ChildOrdersDetailsMixedWith;
use Model\Ordering\OrdersDetailsMixedWithQuery as ChildOrdersDetailsMixedWithQuery;
use Model\Ordering\OrdersDetailsQuery as ChildOrdersDetailsQuery;
use Model\Ordering\OrdersQuery as ChildOrdersQuery;
use Model\Ordering\Map\OrdersDetailExtrasTableMap;
use Model\Ordering\Map\OrdersDetailsMixedWithTableMap;
use Model\Ordering\Map\OrdersDetailsTableMap;
use Model\User\Users;
use Model\User\UsersQuery;
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
 * Base class that represents a row from the 'orders_details' table.
 *
 *
 *
 * @package    propel.generator.Model.Ordering.Base
 */
abstract class OrdersDetails implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Ordering\\Map\\OrdersDetailsTableMap';


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
     * The value for the orders_detailid field.
     *
     * @var        int
     */
    protected $orders_detailid;

    /**
     * The value for the orderid field.
     *
     * @var        int
     */
    protected $orderid;

    /**
     * The value for the menuid field.
     *
     * @var        int
     */
    protected $menuid;

    /**
     * The value for the menu_sizeid field.
     *
     * @var        int
     */
    protected $menu_sizeid;

    /**
     * The value for the menu_groupid field.
     *
     * @var        int
     */
    protected $menu_groupid;

    /**
     * The value for the amount field.
     *
     * @var        int
     */
    protected $amount;

    /**
     * The value for the single_price field.
     *
     * @var        string
     */
    protected $single_price;

    /**
     * The value for the single_price_modified_by_userid field.
     *
     * @var        int
     */
    protected $single_price_modified_by_userid;

    /**
     * The value for the extra_detail field.
     *
     * @var        string
     */
    protected $extra_detail;

    /**
     * The value for the finished field.
     *
     * @var        DateTime
     */
    protected $finished;

    /**
     * The value for the availabilityid field.
     *
     * @var        int
     */
    protected $availabilityid;

    /**
     * The value for the availability_amount field.
     *
     * @var        int
     */
    protected $availability_amount;

    /**
     * The value for the verified field.
     *
     * @var        boolean
     */
    protected $verified;

    /**
     * @var        Availabilitys
     */
    protected $aAvailabilitys;

    /**
     * @var        MenuGroupes
     */
    protected $aMenuGroupes;

    /**
     * @var        MenuSizes
     */
    protected $aMenuSizes;

    /**
     * @var        Menues
     */
    protected $aMenues;

    /**
     * @var        ChildOrders
     */
    protected $aOrders;

    /**
     * @var        Users
     */
    protected $aUsers;

    /**
     * @var        ObjectCollection|InvoicesItems[] Collection to store aggregation of InvoicesItems objects.
     */
    protected $collInvoicesItemss;
    protected $collInvoicesItemssPartial;

    /**
     * @var        ObjectCollection|ChildOrdersDetailExtras[] Collection to store aggregation of ChildOrdersDetailExtras objects.
     */
    protected $collOrdersDetailExtrass;
    protected $collOrdersDetailExtrassPartial;

    /**
     * @var        ObjectCollection|ChildOrdersDetailsMixedWith[] Collection to store aggregation of ChildOrdersDetailsMixedWith objects.
     */
    protected $collOrdersDetailsMixedWiths;
    protected $collOrdersDetailsMixedWithsPartial;

    /**
     * @var        ObjectCollection|OrdersInProgressRecieved[] Collection to store aggregation of OrdersInProgressRecieved objects.
     */
    protected $collOrdersInProgressRecieveds;
    protected $collOrdersInProgressRecievedsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|InvoicesItems[]
     */
    protected $invoicesItemssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrdersDetailExtras[]
     */
    protected $ordersDetailExtrassScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrdersDetailsMixedWith[]
     */
    protected $ordersDetailsMixedWithsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersInProgressRecieved[]
     */
    protected $ordersInProgressRecievedsScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Ordering\Base\OrdersDetails object.
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
     * Compares this with another <code>OrdersDetails</code> instance.  If
     * <code>obj</code> is an instance of <code>OrdersDetails</code>, delegates to
     * <code>equals(OrdersDetails)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|OrdersDetails The current object, for fluid interface
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
     * Get the [orders_detailid] column value.
     *
     * @return int
     */
    public function getOrdersDetailid()
    {
        return $this->orders_detailid;
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
     * Get the [menuid] column value.
     *
     * @return int
     */
    public function getMenuid()
    {
        return $this->menuid;
    }

    /**
     * Get the [menu_sizeid] column value.
     *
     * @return int
     */
    public function getMenuSizeid()
    {
        return $this->menu_sizeid;
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
     * Get the [amount] column value.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the [single_price] column value.
     *
     * @return string
     */
    public function getSinglePrice()
    {
        return $this->single_price;
    }

    /**
     * Get the [single_price_modified_by_userid] column value.
     *
     * @return int
     */
    public function getSinglePriceModifiedByUserid()
    {
        return $this->single_price_modified_by_userid;
    }

    /**
     * Get the [extra_detail] column value.
     *
     * @return string
     */
    public function getExtraDetail()
    {
        return $this->extra_detail;
    }

    /**
     * Get the [optionally formatted] temporal [finished] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getFinished($format = NULL)
    {
        if ($format === null) {
            return $this->finished;
        } else {
            return $this->finished instanceof \DateTimeInterface ? $this->finished->format($format) : null;
        }
    }

    /**
     * Get the [availabilityid] column value.
     *
     * @return int
     */
    public function getAvailabilityid()
    {
        return $this->availabilityid;
    }

    /**
     * Get the [availability_amount] column value.
     *
     * @return int
     */
    public function getAvailabilityAmount()
    {
        return $this->availability_amount;
    }

    /**
     * Get the [verified] column value.
     *
     * @return boolean
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Get the [verified] column value.
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->getVerified();
    }

    /**
     * Set the value of [orders_detailid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setOrdersDetailid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orders_detailid !== $v) {
            $this->orders_detailid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_ORDERS_DETAILID] = true;
        }

        return $this;
    } // setOrdersDetailid()

    /**
     * Set the value of [orderid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setOrderid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orderid !== $v) {
            $this->orderid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_ORDERID] = true;
        }

        if ($this->aOrders !== null && $this->aOrders->getOrderid() !== $v) {
            $this->aOrders = null;
        }

        return $this;
    } // setOrderid()

    /**
     * Set the value of [menuid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setMenuid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menuid !== $v) {
            $this->menuid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_MENUID] = true;
        }

        if ($this->aMenues !== null && $this->aMenues->getMenuid() !== $v) {
            $this->aMenues = null;
        }

        return $this;
    } // setMenuid()

    /**
     * Set the value of [menu_sizeid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setMenuSizeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_sizeid !== $v) {
            $this->menu_sizeid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_MENU_SIZEID] = true;
        }

        if ($this->aMenuSizes !== null && $this->aMenuSizes->getMenuSizeid() !== $v) {
            $this->aMenuSizes = null;
        }

        return $this;
    } // setMenuSizeid()

    /**
     * Set the value of [menu_groupid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setMenuGroupid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_groupid !== $v) {
            $this->menu_groupid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_MENU_GROUPID] = true;
        }

        if ($this->aMenuGroupes !== null && $this->aMenuGroupes->getMenuGroupid() !== $v) {
            $this->aMenuGroupes = null;
        }

        return $this;
    } // setMenuGroupid()

    /**
     * Set the value of [amount] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_AMOUNT] = true;
        }

        return $this;
    } // setAmount()

    /**
     * Set the value of [single_price] column.
     *
     * @param string $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setSinglePrice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->single_price !== $v) {
            $this->single_price = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_SINGLE_PRICE] = true;
        }

        return $this;
    } // setSinglePrice()

    /**
     * Set the value of [single_price_modified_by_userid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setSinglePriceModifiedByUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->single_price_modified_by_userid !== $v) {
            $this->single_price_modified_by_userid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID] = true;
        }

        if ($this->aUsers !== null && $this->aUsers->getUserid() !== $v) {
            $this->aUsers = null;
        }

        return $this;
    } // setSinglePriceModifiedByUserid()

    /**
     * Set the value of [extra_detail] column.
     *
     * @param string $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setExtraDetail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->extra_detail !== $v) {
            $this->extra_detail = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_EXTRA_DETAIL] = true;
        }

        return $this;
    } // setExtraDetail()

    /**
     * Sets the value of [finished] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->finished !== null || $dt !== null) {
            if ($this->finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->finished->format("Y-m-d H:i:s.u")) {
                $this->finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrdersDetailsTableMap::COL_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setFinished()

    /**
     * Set the value of [availabilityid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setAvailabilityid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availabilityid !== $v) {
            $this->availabilityid = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_AVAILABILITYID] = true;
        }

        if ($this->aAvailabilitys !== null && $this->aAvailabilitys->getAvailabilityid() !== $v) {
            $this->aAvailabilitys = null;
        }

        return $this;
    } // setAvailabilityid()

    /**
     * Set the value of [availability_amount] column.
     *
     * @param int $v new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setAvailabilityAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availability_amount !== $v) {
            $this->availability_amount = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT] = true;
        }

        return $this;
    } // setAvailabilityAmount()

    /**
     * Sets the value of the [verified] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function setVerified($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->verified !== $v) {
            $this->verified = $v;
            $this->modifiedColumns[OrdersDetailsTableMap::COL_VERIFIED] = true;
        }

        return $this;
    } // setVerified()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrdersDetailsTableMap::translateFieldName('OrdersDetailid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orders_detailid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrdersDetailsTableMap::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orderid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrdersDetailsTableMap::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menuid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrdersDetailsTableMap::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_sizeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrdersDetailsTableMap::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_groupid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrdersDetailsTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OrdersDetailsTableMap::translateFieldName('SinglePrice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->single_price = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : OrdersDetailsTableMap::translateFieldName('SinglePriceModifiedByUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->single_price_modified_by_userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : OrdersDetailsTableMap::translateFieldName('ExtraDetail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->extra_detail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : OrdersDetailsTableMap::translateFieldName('Finished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : OrdersDetailsTableMap::translateFieldName('Availabilityid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availabilityid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : OrdersDetailsTableMap::translateFieldName('AvailabilityAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availability_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : OrdersDetailsTableMap::translateFieldName('Verified', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verified = (null !== $col) ? (boolean) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = OrdersDetailsTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Ordering\\OrdersDetails'), 0, $e);
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
        if ($this->aOrders !== null && $this->orderid !== $this->aOrders->getOrderid()) {
            $this->aOrders = null;
        }
        if ($this->aMenues !== null && $this->menuid !== $this->aMenues->getMenuid()) {
            $this->aMenues = null;
        }
        if ($this->aMenuSizes !== null && $this->menu_sizeid !== $this->aMenuSizes->getMenuSizeid()) {
            $this->aMenuSizes = null;
        }
        if ($this->aMenuGroupes !== null && $this->menu_groupid !== $this->aMenuGroupes->getMenuGroupid()) {
            $this->aMenuGroupes = null;
        }
        if ($this->aUsers !== null && $this->single_price_modified_by_userid !== $this->aUsers->getUserid()) {
            $this->aUsers = null;
        }
        if ($this->aAvailabilitys !== null && $this->availabilityid !== $this->aAvailabilitys->getAvailabilityid()) {
            $this->aAvailabilitys = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrdersDetailsQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAvailabilitys = null;
            $this->aMenuGroupes = null;
            $this->aMenuSizes = null;
            $this->aMenues = null;
            $this->aOrders = null;
            $this->aUsers = null;
            $this->collInvoicesItemss = null;

            $this->collOrdersDetailExtrass = null;

            $this->collOrdersDetailsMixedWiths = null;

            $this->collOrdersInProgressRecieveds = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see OrdersDetails::setDeleted()
     * @see OrdersDetails::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOrdersDetailsQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsTableMap::DATABASE_NAME);
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
                OrdersDetailsTableMap::addInstanceToPool($this);
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

            if ($this->aAvailabilitys !== null) {
                if ($this->aAvailabilitys->isModified() || $this->aAvailabilitys->isNew()) {
                    $affectedRows += $this->aAvailabilitys->save($con);
                }
                $this->setAvailabilitys($this->aAvailabilitys);
            }

            if ($this->aMenuGroupes !== null) {
                if ($this->aMenuGroupes->isModified() || $this->aMenuGroupes->isNew()) {
                    $affectedRows += $this->aMenuGroupes->save($con);
                }
                $this->setMenuGroupes($this->aMenuGroupes);
            }

            if ($this->aMenuSizes !== null) {
                if ($this->aMenuSizes->isModified() || $this->aMenuSizes->isNew()) {
                    $affectedRows += $this->aMenuSizes->save($con);
                }
                $this->setMenuSizes($this->aMenuSizes);
            }

            if ($this->aMenues !== null) {
                if ($this->aMenues->isModified() || $this->aMenues->isNew()) {
                    $affectedRows += $this->aMenues->save($con);
                }
                $this->setMenues($this->aMenues);
            }

            if ($this->aOrders !== null) {
                if ($this->aOrders->isModified() || $this->aOrders->isNew()) {
                    $affectedRows += $this->aOrders->save($con);
                }
                $this->setOrders($this->aOrders);
            }

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
                    \Model\Invoice\InvoicesItemsQuery::create()
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

            if ($this->ordersDetailExtrassScheduledForDeletion !== null) {
                if (!$this->ordersDetailExtrassScheduledForDeletion->isEmpty()) {
                    \Model\Ordering\OrdersDetailExtrasQuery::create()
                        ->filterByPrimaryKeys($this->ordersDetailExtrassScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersDetailExtrassScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersDetailExtrass !== null) {
                foreach ($this->collOrdersDetailExtrass as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersDetailsMixedWithsScheduledForDeletion !== null) {
                if (!$this->ordersDetailsMixedWithsScheduledForDeletion->isEmpty()) {
                    \Model\Ordering\OrdersDetailsMixedWithQuery::create()
                        ->filterByPrimaryKeys($this->ordersDetailsMixedWithsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersDetailsMixedWithsScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersDetailsMixedWiths !== null) {
                foreach ($this->collOrdersDetailsMixedWiths as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersInProgressRecievedsScheduledForDeletion !== null) {
                if (!$this->ordersInProgressRecievedsScheduledForDeletion->isEmpty()) {
                    \Model\OIP\OrdersInProgressRecievedQuery::create()
                        ->filterByPrimaryKeys($this->ordersInProgressRecievedsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->ordersInProgressRecievedsScheduledForDeletion = null;
                }
            }

            if ($this->collOrdersInProgressRecieveds !== null) {
                foreach ($this->collOrdersInProgressRecieveds as $referrerFK) {
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

        $this->modifiedColumns[OrdersDetailsTableMap::COL_ORDERS_DETAILID] = true;
        if (null !== $this->orders_detailid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrdersDetailsTableMap::COL_ORDERS_DETAILID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_ORDERS_DETAILID)) {
            $modifiedColumns[':p' . $index++]  = 'orders_detailid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_ORDERID)) {
            $modifiedColumns[':p' . $index++]  = 'orderid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_MENUID)) {
            $modifiedColumns[':p' . $index++]  = 'menuid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_MENU_SIZEID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_sizeid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_MENU_GROUPID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_groupid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'amount';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_SINGLE_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'single_price';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'single_price_modified_by_userid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_EXTRA_DETAIL)) {
            $modifiedColumns[':p' . $index++]  = 'extra_detail';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = 'finished';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_AVAILABILITYID)) {
            $modifiedColumns[':p' . $index++]  = 'availabilityid';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'availability_amount';
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_VERIFIED)) {
            $modifiedColumns[':p' . $index++]  = 'verified';
        }

        $sql = sprintf(
            'INSERT INTO orders_details (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'orders_detailid':
                        $stmt->bindValue($identifier, $this->orders_detailid, PDO::PARAM_INT);
                        break;
                    case 'orderid':
                        $stmt->bindValue($identifier, $this->orderid, PDO::PARAM_INT);
                        break;
                    case 'menuid':
                        $stmt->bindValue($identifier, $this->menuid, PDO::PARAM_INT);
                        break;
                    case 'menu_sizeid':
                        $stmt->bindValue($identifier, $this->menu_sizeid, PDO::PARAM_INT);
                        break;
                    case 'menu_groupid':
                        $stmt->bindValue($identifier, $this->menu_groupid, PDO::PARAM_INT);
                        break;
                    case 'amount':
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_INT);
                        break;
                    case 'single_price':
                        $stmt->bindValue($identifier, $this->single_price, PDO::PARAM_STR);
                        break;
                    case 'single_price_modified_by_userid':
                        $stmt->bindValue($identifier, $this->single_price_modified_by_userid, PDO::PARAM_INT);
                        break;
                    case 'extra_detail':
                        $stmt->bindValue($identifier, $this->extra_detail, PDO::PARAM_STR);
                        break;
                    case 'finished':
                        $stmt->bindValue($identifier, $this->finished ? $this->finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'availabilityid':
                        $stmt->bindValue($identifier, $this->availabilityid, PDO::PARAM_INT);
                        break;
                    case 'availability_amount':
                        $stmt->bindValue($identifier, $this->availability_amount, PDO::PARAM_INT);
                        break;
                    case 'verified':
                        $stmt->bindValue($identifier, (int) $this->verified, PDO::PARAM_INT);
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
        $this->setOrdersDetailid($pk);

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
        $pos = OrdersDetailsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getOrdersDetailid();
                break;
            case 1:
                return $this->getOrderid();
                break;
            case 2:
                return $this->getMenuid();
                break;
            case 3:
                return $this->getMenuSizeid();
                break;
            case 4:
                return $this->getMenuGroupid();
                break;
            case 5:
                return $this->getAmount();
                break;
            case 6:
                return $this->getSinglePrice();
                break;
            case 7:
                return $this->getSinglePriceModifiedByUserid();
                break;
            case 8:
                return $this->getExtraDetail();
                break;
            case 9:
                return $this->getFinished();
                break;
            case 10:
                return $this->getAvailabilityid();
                break;
            case 11:
                return $this->getAvailabilityAmount();
                break;
            case 12:
                return $this->getVerified();
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

        if (isset($alreadyDumpedObjects['OrdersDetails'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['OrdersDetails'][$this->hashCode()] = true;
        $keys = OrdersDetailsTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrdersDetailid(),
            $keys[1] => $this->getOrderid(),
            $keys[2] => $this->getMenuid(),
            $keys[3] => $this->getMenuSizeid(),
            $keys[4] => $this->getMenuGroupid(),
            $keys[5] => $this->getAmount(),
            $keys[6] => $this->getSinglePrice(),
            $keys[7] => $this->getSinglePriceModifiedByUserid(),
            $keys[8] => $this->getExtraDetail(),
            $keys[9] => $this->getFinished(),
            $keys[10] => $this->getAvailabilityid(),
            $keys[11] => $this->getAvailabilityAmount(),
            $keys[12] => $this->getVerified(),
        );
        if ($result[$keys[9]] instanceof \DateTime) {
            $result[$keys[9]] = $result[$keys[9]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAvailabilitys) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'availabilitys';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'availabilitys';
                        break;
                    default:
                        $key = 'Availabilitys';
                }

                $result[$key] = $this->aAvailabilitys->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMenuGroupes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuGroupes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_groupes';
                        break;
                    default:
                        $key = 'MenuGroupes';
                }

                $result[$key] = $this->aMenuGroupes->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMenuSizes) {

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

                $result[$key] = $this->aMenuSizes->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMenues) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menues';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menues';
                        break;
                    default:
                        $key = 'Menues';
                }

                $result[$key] = $this->aMenues->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOrders) {

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

                $result[$key] = $this->aOrders->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->collOrdersDetailExtrass) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersDetailExtrass';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_detail_extrass';
                        break;
                    default:
                        $key = 'OrdersDetailExtrass';
                }

                $result[$key] = $this->collOrdersDetailExtrass->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrdersDetailsMixedWiths) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersDetailsMixedWiths';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_details_mixed_withs';
                        break;
                    default:
                        $key = 'OrdersDetailsMixedWiths';
                }

                $result[$key] = $this->collOrdersDetailsMixedWiths->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrdersInProgressRecieveds) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'ordersInProgressRecieveds';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orders_in_progress_recieveds';
                        break;
                    default:
                        $key = 'OrdersInProgressRecieveds';
                }

                $result[$key] = $this->collOrdersInProgressRecieveds->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Model\Ordering\OrdersDetails
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrdersDetailsTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Ordering\OrdersDetails
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrdersDetailid($value);
                break;
            case 1:
                $this->setOrderid($value);
                break;
            case 2:
                $this->setMenuid($value);
                break;
            case 3:
                $this->setMenuSizeid($value);
                break;
            case 4:
                $this->setMenuGroupid($value);
                break;
            case 5:
                $this->setAmount($value);
                break;
            case 6:
                $this->setSinglePrice($value);
                break;
            case 7:
                $this->setSinglePriceModifiedByUserid($value);
                break;
            case 8:
                $this->setExtraDetail($value);
                break;
            case 9:
                $this->setFinished($value);
                break;
            case 10:
                $this->setAvailabilityid($value);
                break;
            case 11:
                $this->setAvailabilityAmount($value);
                break;
            case 12:
                $this->setVerified($value);
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
        $keys = OrdersDetailsTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrdersDetailid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setOrderid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setMenuid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setMenuSizeid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setMenuGroupid($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAmount($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSinglePrice($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSinglePriceModifiedByUserid($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setExtraDetail($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setFinished($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAvailabilityid($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAvailabilityAmount($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setVerified($arr[$keys[12]]);
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
     * @return $this|\Model\Ordering\OrdersDetails The current object, for fluid interface
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
        $criteria = new Criteria(OrdersDetailsTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrdersDetailsTableMap::COL_ORDERS_DETAILID)) {
            $criteria->add(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $this->orders_detailid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_ORDERID)) {
            $criteria->add(OrdersDetailsTableMap::COL_ORDERID, $this->orderid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_MENUID)) {
            $criteria->add(OrdersDetailsTableMap::COL_MENUID, $this->menuid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_MENU_SIZEID)) {
            $criteria->add(OrdersDetailsTableMap::COL_MENU_SIZEID, $this->menu_sizeid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_MENU_GROUPID)) {
            $criteria->add(OrdersDetailsTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_AMOUNT)) {
            $criteria->add(OrdersDetailsTableMap::COL_AMOUNT, $this->amount);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_SINGLE_PRICE)) {
            $criteria->add(OrdersDetailsTableMap::COL_SINGLE_PRICE, $this->single_price);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID)) {
            $criteria->add(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $this->single_price_modified_by_userid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_EXTRA_DETAIL)) {
            $criteria->add(OrdersDetailsTableMap::COL_EXTRA_DETAIL, $this->extra_detail);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_FINISHED)) {
            $criteria->add(OrdersDetailsTableMap::COL_FINISHED, $this->finished);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_AVAILABILITYID)) {
            $criteria->add(OrdersDetailsTableMap::COL_AVAILABILITYID, $this->availabilityid);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT)) {
            $criteria->add(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT, $this->availability_amount);
        }
        if ($this->isColumnModified(OrdersDetailsTableMap::COL_VERIFIED)) {
            $criteria->add(OrdersDetailsTableMap::COL_VERIFIED, $this->verified);
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
        $criteria = ChildOrdersDetailsQuery::create();
        $criteria->add(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $this->orders_detailid);
        $criteria->add(OrdersDetailsTableMap::COL_ORDERID, $this->orderid);

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
        $validPk = null !== $this->getOrdersDetailid() &&
            null !== $this->getOrderid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_orders_details_orders1 to table orders
        if ($this->aOrders && $hash = spl_object_hash($this->aOrders)) {
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
        $pks[0] = $this->getOrdersDetailid();
        $pks[1] = $this->getOrderid();

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
        $this->setOrdersDetailid($keys[0]);
        $this->setOrderid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getOrdersDetailid()) && (null === $this->getOrderid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Ordering\OrdersDetails (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOrderid($this->getOrderid());
        $copyObj->setMenuid($this->getMenuid());
        $copyObj->setMenuSizeid($this->getMenuSizeid());
        $copyObj->setMenuGroupid($this->getMenuGroupid());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setSinglePrice($this->getSinglePrice());
        $copyObj->setSinglePriceModifiedByUserid($this->getSinglePriceModifiedByUserid());
        $copyObj->setExtraDetail($this->getExtraDetail());
        $copyObj->setFinished($this->getFinished());
        $copyObj->setAvailabilityid($this->getAvailabilityid());
        $copyObj->setAvailabilityAmount($this->getAvailabilityAmount());
        $copyObj->setVerified($this->getVerified());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getInvoicesItemss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoicesItems($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersDetailExtrass() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetailExtras($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersDetailsMixedWiths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetailsMixedWith($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersInProgressRecieveds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersInProgressRecieved($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setOrdersDetailid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Model\Ordering\OrdersDetails Clone of current object.
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
     * Declares an association between this object and a Availabilitys object.
     *
     * @param  Availabilitys $v
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAvailabilitys(Availabilitys $v = null)
    {
        if ($v === null) {
            $this->setAvailabilityid(NULL);
        } else {
            $this->setAvailabilityid($v->getAvailabilityid());
        }

        $this->aAvailabilitys = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Availabilitys object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersDetails($this);
        }


        return $this;
    }


    /**
     * Get the associated Availabilitys object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Availabilitys The associated Availabilitys object.
     * @throws PropelException
     */
    public function getAvailabilitys(ConnectionInterface $con = null)
    {
        if ($this->aAvailabilitys === null && ($this->availabilityid !== null)) {
            $this->aAvailabilitys = AvailabilitysQuery::create()->findPk($this->availabilityid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAvailabilitys->addOrdersDetailss($this);
             */
        }

        return $this->aAvailabilitys;
    }

    /**
     * Declares an association between this object and a MenuGroupes object.
     *
     * @param  MenuGroupes $v
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuGroupes(MenuGroupes $v = null)
    {
        if ($v === null) {
            $this->setMenuGroupid(NULL);
        } else {
            $this->setMenuGroupid($v->getMenuGroupid());
        }

        $this->aMenuGroupes = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the MenuGroupes object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersDetails($this);
        }


        return $this;
    }


    /**
     * Get the associated MenuGroupes object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return MenuGroupes The associated MenuGroupes object.
     * @throws PropelException
     */
    public function getMenuGroupes(ConnectionInterface $con = null)
    {
        if ($this->aMenuGroupes === null && ($this->menu_groupid !== null)) {
            $this->aMenuGroupes = MenuGroupesQuery::create()
                ->filterByOrdersDetails($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuGroupes->addOrdersDetailss($this);
             */
        }

        return $this->aMenuGroupes;
    }

    /**
     * Declares an association between this object and a MenuSizes object.
     *
     * @param  MenuSizes $v
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuSizes(MenuSizes $v = null)
    {
        if ($v === null) {
            $this->setMenuSizeid(NULL);
        } else {
            $this->setMenuSizeid($v->getMenuSizeid());
        }

        $this->aMenuSizes = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the MenuSizes object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersDetails($this);
        }


        return $this;
    }


    /**
     * Get the associated MenuSizes object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return MenuSizes The associated MenuSizes object.
     * @throws PropelException
     */
    public function getMenuSizes(ConnectionInterface $con = null)
    {
        if ($this->aMenuSizes === null && ($this->menu_sizeid !== null)) {
            $this->aMenuSizes = MenuSizesQuery::create()
                ->filterByOrdersDetails($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuSizes->addOrdersDetailss($this);
             */
        }

        return $this->aMenuSizes;
    }

    /**
     * Declares an association between this object and a Menues object.
     *
     * @param  Menues $v
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenues(Menues $v = null)
    {
        if ($v === null) {
            $this->setMenuid(NULL);
        } else {
            $this->setMenuid($v->getMenuid());
        }

        $this->aMenues = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Menues object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersDetails($this);
        }


        return $this;
    }


    /**
     * Get the associated Menues object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Menues The associated Menues object.
     * @throws PropelException
     */
    public function getMenues(ConnectionInterface $con = null)
    {
        if ($this->aMenues === null && ($this->menuid !== null)) {
            $this->aMenues = MenuesQuery::create()
                ->filterByOrdersDetails($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenues->addOrdersDetailss($this);
             */
        }

        return $this->aMenues;
    }

    /**
     * Declares an association between this object and a ChildOrders object.
     *
     * @param  ChildOrders $v
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrders(ChildOrders $v = null)
    {
        if ($v === null) {
            $this->setOrderid(NULL);
        } else {
            $this->setOrderid($v->getOrderid());
        }

        $this->aOrders = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildOrders object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersDetails($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildOrders object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildOrders The associated ChildOrders object.
     * @throws PropelException
     */
    public function getOrders(ConnectionInterface $con = null)
    {
        if ($this->aOrders === null && ($this->orderid !== null)) {
            $this->aOrders = ChildOrdersQuery::create()
                ->filterByOrdersDetails($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrders->addOrdersDetailss($this);
             */
        }

        return $this->aOrders;
    }

    /**
     * Declares an association between this object and a Users object.
     *
     * @param  Users $v
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUsers(Users $v = null)
    {
        if ($v === null) {
            $this->setSinglePriceModifiedByUserid(NULL);
        } else {
            $this->setSinglePriceModifiedByUserid($v->getUserid());
        }

        $this->aUsers = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Users object, it will not be re-added.
        if ($v !== null) {
            $v->addOrdersDetails($this);
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
        if ($this->aUsers === null && ($this->single_price_modified_by_userid !== null)) {
            $this->aUsers = UsersQuery::create()->findPk($this->single_price_modified_by_userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUsers->addOrdersDetailss($this);
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
        if ('OrdersDetailExtras' == $relationName) {
            return $this->initOrdersDetailExtrass();
        }
        if ('OrdersDetailsMixedWith' == $relationName) {
            return $this->initOrdersDetailsMixedWiths();
        }
        if ('OrdersInProgressRecieved' == $relationName) {
            return $this->initOrdersInProgressRecieveds();
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
        $this->collInvoicesItemss->setModel('\Model\Invoice\InvoicesItems');
    }

    /**
     * Gets an array of InvoicesItems objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrdersDetails is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|InvoicesItems[] List of InvoicesItems objects
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
                $collInvoicesItemss = InvoicesItemsQuery::create(null, $criteria)
                    ->filterByOrdersDetails($this)
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
     * Sets a collection of InvoicesItems objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoicesItemss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function setInvoicesItemss(Collection $invoicesItemss, ConnectionInterface $con = null)
    {
        /** @var InvoicesItems[] $invoicesItemssToDelete */
        $invoicesItemssToDelete = $this->getInvoicesItemss(new Criteria(), $con)->diff($invoicesItemss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->invoicesItemssScheduledForDeletion = clone $invoicesItemssToDelete;

        foreach ($invoicesItemssToDelete as $invoicesItemsRemoved) {
            $invoicesItemsRemoved->setOrdersDetails(null);
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
     * Returns the number of related BaseInvoicesItems objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseInvoicesItems objects.
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

            $query = InvoicesItemsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrdersDetails($this)
                ->count($con);
        }

        return count($this->collInvoicesItemss);
    }

    /**
     * Method called to associate a InvoicesItems object to this object
     * through the InvoicesItems foreign key attribute.
     *
     * @param  InvoicesItems $l InvoicesItems
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function addInvoicesItems(InvoicesItems $l)
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
     * @param InvoicesItems $invoicesItems The InvoicesItems object to add.
     */
    protected function doAddInvoicesItems(InvoicesItems $invoicesItems)
    {
        $this->collInvoicesItemss[]= $invoicesItems;
        $invoicesItems->setOrdersDetails($this);
    }

    /**
     * @param  InvoicesItems $invoicesItems The InvoicesItems object to remove.
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function removeInvoicesItems(InvoicesItems $invoicesItems)
    {
        if ($this->getInvoicesItemss()->contains($invoicesItems)) {
            $pos = $this->collInvoicesItemss->search($invoicesItems);
            $this->collInvoicesItemss->remove($pos);
            if (null === $this->invoicesItemssScheduledForDeletion) {
                $this->invoicesItemssScheduledForDeletion = clone $this->collInvoicesItemss;
                $this->invoicesItemssScheduledForDeletion->clear();
            }
            $this->invoicesItemssScheduledForDeletion[]= clone $invoicesItems;
            $invoicesItems->setOrdersDetails(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrdersDetails is new, it will return
     * an empty collection; or if this OrdersDetails has previously
     * been saved, it will retrieve related InvoicesItemss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrdersDetails.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|InvoicesItems[] List of InvoicesItems objects
     */
    public function getInvoicesItemssJoinInvoices(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoicesItemsQuery::create(null, $criteria);
        $query->joinWith('Invoices', $joinBehavior);

        return $this->getInvoicesItemss($query, $con);
    }

    /**
     * Clears out the collOrdersDetailExtrass collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersDetailExtrass()
     */
    public function clearOrdersDetailExtrass()
    {
        $this->collOrdersDetailExtrass = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersDetailExtrass collection loaded partially.
     */
    public function resetPartialOrdersDetailExtrass($v = true)
    {
        $this->collOrdersDetailExtrassPartial = $v;
    }

    /**
     * Initializes the collOrdersDetailExtrass collection.
     *
     * By default this just sets the collOrdersDetailExtrass collection to an empty array (like clearcollOrdersDetailExtrass());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersDetailExtrass($overrideExisting = true)
    {
        if (null !== $this->collOrdersDetailExtrass && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersDetailExtrasTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersDetailExtrass = new $collectionClassName;
        $this->collOrdersDetailExtrass->setModel('\Model\Ordering\OrdersDetailExtras');
    }

    /**
     * Gets an array of ChildOrdersDetailExtras objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrdersDetails is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrdersDetailExtras[] List of ChildOrdersDetailExtras objects
     * @throws PropelException
     */
    public function getOrdersDetailExtrass(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailExtrassPartial && !$this->isNew();
        if (null === $this->collOrdersDetailExtrass || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailExtrass) {
                // return empty collection
                $this->initOrdersDetailExtrass();
            } else {
                $collOrdersDetailExtrass = ChildOrdersDetailExtrasQuery::create(null, $criteria)
                    ->filterByOrdersDetails($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersDetailExtrassPartial && count($collOrdersDetailExtrass)) {
                        $this->initOrdersDetailExtrass(false);

                        foreach ($collOrdersDetailExtrass as $obj) {
                            if (false == $this->collOrdersDetailExtrass->contains($obj)) {
                                $this->collOrdersDetailExtrass->append($obj);
                            }
                        }

                        $this->collOrdersDetailExtrassPartial = true;
                    }

                    return $collOrdersDetailExtrass;
                }

                if ($partial && $this->collOrdersDetailExtrass) {
                    foreach ($this->collOrdersDetailExtrass as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersDetailExtrass[] = $obj;
                        }
                    }
                }

                $this->collOrdersDetailExtrass = $collOrdersDetailExtrass;
                $this->collOrdersDetailExtrassPartial = false;
            }
        }

        return $this->collOrdersDetailExtrass;
    }

    /**
     * Sets a collection of ChildOrdersDetailExtras objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersDetailExtrass A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function setOrdersDetailExtrass(Collection $ordersDetailExtrass, ConnectionInterface $con = null)
    {
        /** @var ChildOrdersDetailExtras[] $ordersDetailExtrassToDelete */
        $ordersDetailExtrassToDelete = $this->getOrdersDetailExtrass(new Criteria(), $con)->diff($ordersDetailExtrass);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersDetailExtrassScheduledForDeletion = clone $ordersDetailExtrassToDelete;

        foreach ($ordersDetailExtrassToDelete as $ordersDetailExtrasRemoved) {
            $ordersDetailExtrasRemoved->setOrdersDetails(null);
        }

        $this->collOrdersDetailExtrass = null;
        foreach ($ordersDetailExtrass as $ordersDetailExtras) {
            $this->addOrdersDetailExtras($ordersDetailExtras);
        }

        $this->collOrdersDetailExtrass = $ordersDetailExtrass;
        $this->collOrdersDetailExtrassPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrdersDetailExtras objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related OrdersDetailExtras objects.
     * @throws PropelException
     */
    public function countOrdersDetailExtrass(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailExtrassPartial && !$this->isNew();
        if (null === $this->collOrdersDetailExtrass || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailExtrass) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersDetailExtrass());
            }

            $query = ChildOrdersDetailExtrasQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrdersDetails($this)
                ->count($con);
        }

        return count($this->collOrdersDetailExtrass);
    }

    /**
     * Method called to associate a ChildOrdersDetailExtras object to this object
     * through the ChildOrdersDetailExtras foreign key attribute.
     *
     * @param  ChildOrdersDetailExtras $l ChildOrdersDetailExtras
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function addOrdersDetailExtras(ChildOrdersDetailExtras $l)
    {
        if ($this->collOrdersDetailExtrass === null) {
            $this->initOrdersDetailExtrass();
            $this->collOrdersDetailExtrassPartial = true;
        }

        if (!$this->collOrdersDetailExtrass->contains($l)) {
            $this->doAddOrdersDetailExtras($l);

            if ($this->ordersDetailExtrassScheduledForDeletion and $this->ordersDetailExtrassScheduledForDeletion->contains($l)) {
                $this->ordersDetailExtrassScheduledForDeletion->remove($this->ordersDetailExtrassScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrdersDetailExtras $ordersDetailExtras The ChildOrdersDetailExtras object to add.
     */
    protected function doAddOrdersDetailExtras(ChildOrdersDetailExtras $ordersDetailExtras)
    {
        $this->collOrdersDetailExtrass[]= $ordersDetailExtras;
        $ordersDetailExtras->setOrdersDetails($this);
    }

    /**
     * @param  ChildOrdersDetailExtras $ordersDetailExtras The ChildOrdersDetailExtras object to remove.
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function removeOrdersDetailExtras(ChildOrdersDetailExtras $ordersDetailExtras)
    {
        if ($this->getOrdersDetailExtrass()->contains($ordersDetailExtras)) {
            $pos = $this->collOrdersDetailExtrass->search($ordersDetailExtras);
            $this->collOrdersDetailExtrass->remove($pos);
            if (null === $this->ordersDetailExtrassScheduledForDeletion) {
                $this->ordersDetailExtrassScheduledForDeletion = clone $this->collOrdersDetailExtrass;
                $this->ordersDetailExtrassScheduledForDeletion->clear();
            }
            $this->ordersDetailExtrassScheduledForDeletion[]= clone $ordersDetailExtras;
            $ordersDetailExtras->setOrdersDetails(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrdersDetails is new, it will return
     * an empty collection; or if this OrdersDetails has previously
     * been saved, it will retrieve related OrdersDetailExtrass from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrdersDetails.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetailExtras[] List of ChildOrdersDetailExtras objects
     */
    public function getOrdersDetailExtrassJoinMenuesPossibleExtras(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailExtrasQuery::create(null, $criteria);
        $query->joinWith('MenuesPossibleExtras', $joinBehavior);

        return $this->getOrdersDetailExtrass($query, $con);
    }

    /**
     * Clears out the collOrdersDetailsMixedWiths collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersDetailsMixedWiths()
     */
    public function clearOrdersDetailsMixedWiths()
    {
        $this->collOrdersDetailsMixedWiths = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersDetailsMixedWiths collection loaded partially.
     */
    public function resetPartialOrdersDetailsMixedWiths($v = true)
    {
        $this->collOrdersDetailsMixedWithsPartial = $v;
    }

    /**
     * Initializes the collOrdersDetailsMixedWiths collection.
     *
     * By default this just sets the collOrdersDetailsMixedWiths collection to an empty array (like clearcollOrdersDetailsMixedWiths());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersDetailsMixedWiths($overrideExisting = true)
    {
        if (null !== $this->collOrdersDetailsMixedWiths && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersDetailsMixedWithTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersDetailsMixedWiths = new $collectionClassName;
        $this->collOrdersDetailsMixedWiths->setModel('\Model\Ordering\OrdersDetailsMixedWith');
    }

    /**
     * Gets an array of ChildOrdersDetailsMixedWith objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrdersDetails is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrdersDetailsMixedWith[] List of ChildOrdersDetailsMixedWith objects
     * @throws PropelException
     */
    public function getOrdersDetailsMixedWiths(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailsMixedWithsPartial && !$this->isNew();
        if (null === $this->collOrdersDetailsMixedWiths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailsMixedWiths) {
                // return empty collection
                $this->initOrdersDetailsMixedWiths();
            } else {
                $collOrdersDetailsMixedWiths = ChildOrdersDetailsMixedWithQuery::create(null, $criteria)
                    ->filterByOrdersDetails($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersDetailsMixedWithsPartial && count($collOrdersDetailsMixedWiths)) {
                        $this->initOrdersDetailsMixedWiths(false);

                        foreach ($collOrdersDetailsMixedWiths as $obj) {
                            if (false == $this->collOrdersDetailsMixedWiths->contains($obj)) {
                                $this->collOrdersDetailsMixedWiths->append($obj);
                            }
                        }

                        $this->collOrdersDetailsMixedWithsPartial = true;
                    }

                    return $collOrdersDetailsMixedWiths;
                }

                if ($partial && $this->collOrdersDetailsMixedWiths) {
                    foreach ($this->collOrdersDetailsMixedWiths as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersDetailsMixedWiths[] = $obj;
                        }
                    }
                }

                $this->collOrdersDetailsMixedWiths = $collOrdersDetailsMixedWiths;
                $this->collOrdersDetailsMixedWithsPartial = false;
            }
        }

        return $this->collOrdersDetailsMixedWiths;
    }

    /**
     * Sets a collection of ChildOrdersDetailsMixedWith objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersDetailsMixedWiths A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function setOrdersDetailsMixedWiths(Collection $ordersDetailsMixedWiths, ConnectionInterface $con = null)
    {
        /** @var ChildOrdersDetailsMixedWith[] $ordersDetailsMixedWithsToDelete */
        $ordersDetailsMixedWithsToDelete = $this->getOrdersDetailsMixedWiths(new Criteria(), $con)->diff($ordersDetailsMixedWiths);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersDetailsMixedWithsScheduledForDeletion = clone $ordersDetailsMixedWithsToDelete;

        foreach ($ordersDetailsMixedWithsToDelete as $ordersDetailsMixedWithRemoved) {
            $ordersDetailsMixedWithRemoved->setOrdersDetails(null);
        }

        $this->collOrdersDetailsMixedWiths = null;
        foreach ($ordersDetailsMixedWiths as $ordersDetailsMixedWith) {
            $this->addOrdersDetailsMixedWith($ordersDetailsMixedWith);
        }

        $this->collOrdersDetailsMixedWiths = $ordersDetailsMixedWiths;
        $this->collOrdersDetailsMixedWithsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrdersDetailsMixedWith objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related OrdersDetailsMixedWith objects.
     * @throws PropelException
     */
    public function countOrdersDetailsMixedWiths(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersDetailsMixedWithsPartial && !$this->isNew();
        if (null === $this->collOrdersDetailsMixedWiths || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersDetailsMixedWiths) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersDetailsMixedWiths());
            }

            $query = ChildOrdersDetailsMixedWithQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrdersDetails($this)
                ->count($con);
        }

        return count($this->collOrdersDetailsMixedWiths);
    }

    /**
     * Method called to associate a ChildOrdersDetailsMixedWith object to this object
     * through the ChildOrdersDetailsMixedWith foreign key attribute.
     *
     * @param  ChildOrdersDetailsMixedWith $l ChildOrdersDetailsMixedWith
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function addOrdersDetailsMixedWith(ChildOrdersDetailsMixedWith $l)
    {
        if ($this->collOrdersDetailsMixedWiths === null) {
            $this->initOrdersDetailsMixedWiths();
            $this->collOrdersDetailsMixedWithsPartial = true;
        }

        if (!$this->collOrdersDetailsMixedWiths->contains($l)) {
            $this->doAddOrdersDetailsMixedWith($l);

            if ($this->ordersDetailsMixedWithsScheduledForDeletion and $this->ordersDetailsMixedWithsScheduledForDeletion->contains($l)) {
                $this->ordersDetailsMixedWithsScheduledForDeletion->remove($this->ordersDetailsMixedWithsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrdersDetailsMixedWith $ordersDetailsMixedWith The ChildOrdersDetailsMixedWith object to add.
     */
    protected function doAddOrdersDetailsMixedWith(ChildOrdersDetailsMixedWith $ordersDetailsMixedWith)
    {
        $this->collOrdersDetailsMixedWiths[]= $ordersDetailsMixedWith;
        $ordersDetailsMixedWith->setOrdersDetails($this);
    }

    /**
     * @param  ChildOrdersDetailsMixedWith $ordersDetailsMixedWith The ChildOrdersDetailsMixedWith object to remove.
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function removeOrdersDetailsMixedWith(ChildOrdersDetailsMixedWith $ordersDetailsMixedWith)
    {
        if ($this->getOrdersDetailsMixedWiths()->contains($ordersDetailsMixedWith)) {
            $pos = $this->collOrdersDetailsMixedWiths->search($ordersDetailsMixedWith);
            $this->collOrdersDetailsMixedWiths->remove($pos);
            if (null === $this->ordersDetailsMixedWithsScheduledForDeletion) {
                $this->ordersDetailsMixedWithsScheduledForDeletion = clone $this->collOrdersDetailsMixedWiths;
                $this->ordersDetailsMixedWithsScheduledForDeletion->clear();
            }
            $this->ordersDetailsMixedWithsScheduledForDeletion[]= clone $ordersDetailsMixedWith;
            $ordersDetailsMixedWith->setOrdersDetails(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrdersDetails is new, it will return
     * an empty collection; or if this OrdersDetails has previously
     * been saved, it will retrieve related OrdersDetailsMixedWiths from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrdersDetails.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrdersDetailsMixedWith[] List of ChildOrdersDetailsMixedWith objects
     */
    public function getOrdersDetailsMixedWithsJoinMenues(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrdersDetailsMixedWithQuery::create(null, $criteria);
        $query->joinWith('Menues', $joinBehavior);

        return $this->getOrdersDetailsMixedWiths($query, $con);
    }

    /**
     * Clears out the collOrdersInProgressRecieveds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrdersInProgressRecieveds()
     */
    public function clearOrdersInProgressRecieveds()
    {
        $this->collOrdersInProgressRecieveds = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrdersInProgressRecieveds collection loaded partially.
     */
    public function resetPartialOrdersInProgressRecieveds($v = true)
    {
        $this->collOrdersInProgressRecievedsPartial = $v;
    }

    /**
     * Initializes the collOrdersInProgressRecieveds collection.
     *
     * By default this just sets the collOrdersInProgressRecieveds collection to an empty array (like clearcollOrdersInProgressRecieveds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrdersInProgressRecieveds($overrideExisting = true)
    {
        if (null !== $this->collOrdersInProgressRecieveds && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrdersInProgressRecievedTableMap::getTableMap()->getCollectionClassName();

        $this->collOrdersInProgressRecieveds = new $collectionClassName;
        $this->collOrdersInProgressRecieveds->setModel('\Model\OIP\OrdersInProgressRecieved');
    }

    /**
     * Gets an array of OrdersInProgressRecieved objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrdersDetails is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrdersInProgressRecieved[] List of OrdersInProgressRecieved objects
     * @throws PropelException
     */
    public function getOrdersInProgressRecieveds(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersInProgressRecievedsPartial && !$this->isNew();
        if (null === $this->collOrdersInProgressRecieveds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrdersInProgressRecieveds) {
                // return empty collection
                $this->initOrdersInProgressRecieveds();
            } else {
                $collOrdersInProgressRecieveds = OrdersInProgressRecievedQuery::create(null, $criteria)
                    ->filterByOrdersDetails($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersInProgressRecievedsPartial && count($collOrdersInProgressRecieveds)) {
                        $this->initOrdersInProgressRecieveds(false);

                        foreach ($collOrdersInProgressRecieveds as $obj) {
                            if (false == $this->collOrdersInProgressRecieveds->contains($obj)) {
                                $this->collOrdersInProgressRecieveds->append($obj);
                            }
                        }

                        $this->collOrdersInProgressRecievedsPartial = true;
                    }

                    return $collOrdersInProgressRecieveds;
                }

                if ($partial && $this->collOrdersInProgressRecieveds) {
                    foreach ($this->collOrdersInProgressRecieveds as $obj) {
                        if ($obj->isNew()) {
                            $collOrdersInProgressRecieveds[] = $obj;
                        }
                    }
                }

                $this->collOrdersInProgressRecieveds = $collOrdersInProgressRecieveds;
                $this->collOrdersInProgressRecievedsPartial = false;
            }
        }

        return $this->collOrdersInProgressRecieveds;
    }

    /**
     * Sets a collection of OrdersInProgressRecieved objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersInProgressRecieveds A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function setOrdersInProgressRecieveds(Collection $ordersInProgressRecieveds, ConnectionInterface $con = null)
    {
        /** @var OrdersInProgressRecieved[] $ordersInProgressRecievedsToDelete */
        $ordersInProgressRecievedsToDelete = $this->getOrdersInProgressRecieveds(new Criteria(), $con)->diff($ordersInProgressRecieveds);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersInProgressRecievedsScheduledForDeletion = clone $ordersInProgressRecievedsToDelete;

        foreach ($ordersInProgressRecievedsToDelete as $ordersInProgressRecievedRemoved) {
            $ordersInProgressRecievedRemoved->setOrdersDetails(null);
        }

        $this->collOrdersInProgressRecieveds = null;
        foreach ($ordersInProgressRecieveds as $ordersInProgressRecieved) {
            $this->addOrdersInProgressRecieved($ordersInProgressRecieved);
        }

        $this->collOrdersInProgressRecieveds = $ordersInProgressRecieveds;
        $this->collOrdersInProgressRecievedsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOrdersInProgressRecieved objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrdersInProgressRecieved objects.
     * @throws PropelException
     */
    public function countOrdersInProgressRecieveds(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersInProgressRecievedsPartial && !$this->isNew();
        if (null === $this->collOrdersInProgressRecieveds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrdersInProgressRecieveds) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrdersInProgressRecieveds());
            }

            $query = OrdersInProgressRecievedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrdersDetails($this)
                ->count($con);
        }

        return count($this->collOrdersInProgressRecieveds);
    }

    /**
     * Method called to associate a OrdersInProgressRecieved object to this object
     * through the OrdersInProgressRecieved foreign key attribute.
     *
     * @param  OrdersInProgressRecieved $l OrdersInProgressRecieved
     * @return $this|\Model\Ordering\OrdersDetails The current object (for fluent API support)
     */
    public function addOrdersInProgressRecieved(OrdersInProgressRecieved $l)
    {
        if ($this->collOrdersInProgressRecieveds === null) {
            $this->initOrdersInProgressRecieveds();
            $this->collOrdersInProgressRecievedsPartial = true;
        }

        if (!$this->collOrdersInProgressRecieveds->contains($l)) {
            $this->doAddOrdersInProgressRecieved($l);

            if ($this->ordersInProgressRecievedsScheduledForDeletion and $this->ordersInProgressRecievedsScheduledForDeletion->contains($l)) {
                $this->ordersInProgressRecievedsScheduledForDeletion->remove($this->ordersInProgressRecievedsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param OrdersInProgressRecieved $ordersInProgressRecieved The OrdersInProgressRecieved object to add.
     */
    protected function doAddOrdersInProgressRecieved(OrdersInProgressRecieved $ordersInProgressRecieved)
    {
        $this->collOrdersInProgressRecieveds[]= $ordersInProgressRecieved;
        $ordersInProgressRecieved->setOrdersDetails($this);
    }

    /**
     * @param  OrdersInProgressRecieved $ordersInProgressRecieved The OrdersInProgressRecieved object to remove.
     * @return $this|ChildOrdersDetails The current object (for fluent API support)
     */
    public function removeOrdersInProgressRecieved(OrdersInProgressRecieved $ordersInProgressRecieved)
    {
        if ($this->getOrdersInProgressRecieveds()->contains($ordersInProgressRecieved)) {
            $pos = $this->collOrdersInProgressRecieveds->search($ordersInProgressRecieved);
            $this->collOrdersInProgressRecieveds->remove($pos);
            if (null === $this->ordersInProgressRecievedsScheduledForDeletion) {
                $this->ordersInProgressRecievedsScheduledForDeletion = clone $this->collOrdersInProgressRecieveds;
                $this->ordersInProgressRecievedsScheduledForDeletion->clear();
            }
            $this->ordersInProgressRecievedsScheduledForDeletion[]= clone $ordersInProgressRecieved;
            $ordersInProgressRecieved->setOrdersDetails(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrdersDetails is new, it will return
     * an empty collection; or if this OrdersDetails has previously
     * been saved, it will retrieve related OrdersInProgressRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrdersDetails.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgressRecieved[] List of OrdersInProgressRecieved objects
     */
    public function getOrdersInProgressRecievedsJoinOrdersInProgress(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressRecievedQuery::create(null, $criteria);
        $query->joinWith('OrdersInProgress', $joinBehavior);

        return $this->getOrdersInProgressRecieveds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrdersDetails is new, it will return
     * an empty collection; or if this OrdersDetails has previously
     * been saved, it will retrieve related OrdersInProgressRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrdersDetails.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgressRecieved[] List of OrdersInProgressRecieved objects
     */
    public function getOrdersInProgressRecievedsJoinDistributionsGivingOuts(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressRecievedQuery::create(null, $criteria);
        $query->joinWith('DistributionsGivingOuts', $joinBehavior);

        return $this->getOrdersInProgressRecieveds($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAvailabilitys) {
            $this->aAvailabilitys->removeOrdersDetails($this);
        }
        if (null !== $this->aMenuGroupes) {
            $this->aMenuGroupes->removeOrdersDetails($this);
        }
        if (null !== $this->aMenuSizes) {
            $this->aMenuSizes->removeOrdersDetails($this);
        }
        if (null !== $this->aMenues) {
            $this->aMenues->removeOrdersDetails($this);
        }
        if (null !== $this->aOrders) {
            $this->aOrders->removeOrdersDetails($this);
        }
        if (null !== $this->aUsers) {
            $this->aUsers->removeOrdersDetails($this);
        }
        $this->orders_detailid = null;
        $this->orderid = null;
        $this->menuid = null;
        $this->menu_sizeid = null;
        $this->menu_groupid = null;
        $this->amount = null;
        $this->single_price = null;
        $this->single_price_modified_by_userid = null;
        $this->extra_detail = null;
        $this->finished = null;
        $this->availabilityid = null;
        $this->availability_amount = null;
        $this->verified = null;
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
            if ($this->collOrdersDetailExtrass) {
                foreach ($this->collOrdersDetailExtrass as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersDetailsMixedWiths) {
                foreach ($this->collOrdersDetailsMixedWiths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersInProgressRecieveds) {
                foreach ($this->collOrdersInProgressRecieveds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvoicesItemss = null;
        $this->collOrdersDetailExtrass = null;
        $this->collOrdersDetailsMixedWiths = null;
        $this->collOrdersInProgressRecieveds = null;
        $this->aAvailabilitys = null;
        $this->aMenuGroupes = null;
        $this->aMenuSizes = null;
        $this->aMenues = null;
        $this->aOrders = null;
        $this->aUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrdersDetailsTableMap::DEFAULT_STRING_FORMAT);
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
