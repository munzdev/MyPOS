<?php

namespace API\Models\ORM\Ordering\Base;

use \DateTime;
use \Exception;
use \PDO;
use API\Models\ORM\Invoice\InvoiceItem;
use API\Models\ORM\Invoice\InvoiceItemQuery;
use API\Models\ORM\Invoice\Base\InvoiceItem as BaseInvoiceItem;
use API\Models\ORM\Invoice\Map\InvoiceItemTableMap;
use API\Models\ORM\Menu\Availability;
use API\Models\ORM\Menu\AvailabilityQuery;
use API\Models\ORM\Menu\Menu;
use API\Models\ORM\Menu\MenuGroup;
use API\Models\ORM\Menu\MenuGroupQuery;
use API\Models\ORM\Menu\MenuPossibleExtra;
use API\Models\ORM\Menu\MenuPossibleExtraQuery;
use API\Models\ORM\Menu\MenuQuery;
use API\Models\ORM\Menu\MenuSize;
use API\Models\ORM\Menu\MenuSizeQuery;
use API\Models\ORM\OIP\OrderInProgressRecieved;
use API\Models\ORM\OIP\OrderInProgressRecievedQuery;
use API\Models\ORM\OIP\Base\OrderInProgressRecieved as BaseOrderInProgressRecieved;
use API\Models\ORM\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\ORM\Ordering\Order as ChildOrder;
use API\Models\ORM\Ordering\OrderDetail as ChildOrderDetail;
use API\Models\ORM\Ordering\OrderDetailExtra as ChildOrderDetailExtra;
use API\Models\ORM\Ordering\OrderDetailExtraQuery as ChildOrderDetailExtraQuery;
use API\Models\ORM\Ordering\OrderDetailMixedWith as ChildOrderDetailMixedWith;
use API\Models\ORM\Ordering\OrderDetailMixedWithQuery as ChildOrderDetailMixedWithQuery;
use API\Models\ORM\Ordering\OrderDetailQuery as ChildOrderDetailQuery;
use API\Models\ORM\Ordering\OrderQuery as ChildOrderQuery;
use API\Models\ORM\Ordering\Map\OrderDetailExtraTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailMixedWithTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
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
 * Base class that represents a row from the 'order_detail' table.
 *
 * 
 *
 * @package    propel.generator.API.Models.ORM.Ordering.Base
 */
abstract class OrderDetail implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\ORM\\Ordering\\Map\\OrderDetailTableMap';


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
     * The value for the order_detailid field.
     * 
     * @var        int
     */
    protected $order_detailid;

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
     * The value for the distribution_finished field.
     * 
     * @var        DateTime
     */
    protected $distribution_finished;

    /**
     * The value for the invoice_finished field.
     * 
     * @var        DateTime
     */
    protected $invoice_finished;

    /**
     * @var        Availability
     */
    protected $aAvailability;

    /**
     * @var        MenuGroup
     */
    protected $aMenuGroup;

    /**
     * @var        MenuSize
     */
    protected $aMenuSize;

    /**
     * @var        Menu
     */
    protected $aMenu;

    /**
     * @var        ChildOrder
     */
    protected $aOrder;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|InvoiceItem[] Collection to store aggregation of InvoiceItem objects.
     */
    protected $collInvoiceItems;
    protected $collInvoiceItemsPartial;

    /**
     * @var        ObjectCollection|ChildOrderDetailExtra[] Collection to store aggregation of ChildOrderDetailExtra objects.
     */
    protected $collOrderDetailExtras;
    protected $collOrderDetailExtrasPartial;

    /**
     * @var        ObjectCollection|ChildOrderDetailMixedWith[] Collection to store aggregation of ChildOrderDetailMixedWith objects.
     */
    protected $collOrderDetailMixedWiths;
    protected $collOrderDetailMixedWithsPartial;

    /**
     * @var        ObjectCollection|OrderInProgressRecieved[] Collection to store aggregation of OrderInProgressRecieved objects.
     */
    protected $collOrderInProgressRecieveds;
    protected $collOrderInProgressRecievedsPartial;

    /**
     * @var        ObjectCollection|MenuPossibleExtra[] Cross Collection to store aggregation of MenuPossibleExtra objects.
     */
    protected $collMenuPossibleExtras;

    /**
     * @var bool
     */
    protected $collMenuPossibleExtrasPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuPossibleExtra[]
     */
    protected $menuPossibleExtrasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|InvoiceItem[]
     */
    protected $invoiceItemsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrderDetailExtra[]
     */
    protected $orderDetailExtrasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrderDetailMixedWith[]
     */
    protected $orderDetailMixedWithsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrderInProgressRecieved[]
     */
    protected $orderInProgressRecievedsScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\ORM\Ordering\Base\OrderDetail object.
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
     * Compares this with another <code>OrderDetail</code> instance.  If
     * <code>obj</code> is an instance of <code>OrderDetail</code>, delegates to
     * <code>equals(OrderDetail)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|OrderDetail The current object, for fluid interface
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
     * Get the [order_detailid] column value.
     * 
     * @return int
     */
    public function getOrderDetailid()
    {
        return $this->order_detailid;
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
     * Get the [optionally formatted] temporal [distribution_finished] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDistributionFinished($format = NULL)
    {
        if ($format === null) {
            return $this->distribution_finished;
        } else {
            return $this->distribution_finished instanceof \DateTimeInterface ? $this->distribution_finished->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [invoice_finished] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getInvoiceFinished($format = NULL)
    {
        if ($format === null) {
            return $this->invoice_finished;
        } else {
            return $this->invoice_finished instanceof \DateTimeInterface ? $this->invoice_finished->format($format) : null;
        }
    }

    /**
     * Set the value of [order_detailid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setOrderDetailid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_detailid !== $v) {
            $this->order_detailid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_ORDER_DETAILID] = true;
        }

        return $this;
    } // setOrderDetailid()

    /**
     * Set the value of [orderid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setOrderid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->orderid !== $v) {
            $this->orderid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_ORDERID] = true;
        }

        if ($this->aOrder !== null && $this->aOrder->getOrderid() !== $v) {
            $this->aOrder = null;
        }

        return $this;
    } // setOrderid()

    /**
     * Set the value of [menuid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setMenuid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menuid !== $v) {
            $this->menuid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_MENUID] = true;
        }

        if ($this->aMenu !== null && $this->aMenu->getMenuid() !== $v) {
            $this->aMenu = null;
        }

        return $this;
    } // setMenuid()

    /**
     * Set the value of [menu_sizeid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setMenuSizeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_sizeid !== $v) {
            $this->menu_sizeid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_MENU_SIZEID] = true;
        }

        if ($this->aMenuSize !== null && $this->aMenuSize->getMenuSizeid() !== $v) {
            $this->aMenuSize = null;
        }

        return $this;
    } // setMenuSizeid()

    /**
     * Set the value of [menu_groupid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setMenuGroupid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_groupid !== $v) {
            $this->menu_groupid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_MENU_GROUPID] = true;
        }

        if ($this->aMenuGroup !== null && $this->aMenuGroup->getMenuGroupid() !== $v) {
            $this->aMenuGroup = null;
        }

        return $this;
    } // setMenuGroupid()

    /**
     * Set the value of [amount] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_AMOUNT] = true;
        }

        return $this;
    } // setAmount()

    /**
     * Set the value of [single_price] column.
     * 
     * @param string $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setSinglePrice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->single_price !== $v) {
            $this->single_price = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_SINGLE_PRICE] = true;
        }

        return $this;
    } // setSinglePrice()

    /**
     * Set the value of [single_price_modified_by_userid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setSinglePriceModifiedByUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->single_price_modified_by_userid !== $v) {
            $this->single_price_modified_by_userid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getUserid() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setSinglePriceModifiedByUserid()

    /**
     * Set the value of [extra_detail] column.
     * 
     * @param string $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setExtraDetail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->extra_detail !== $v) {
            $this->extra_detail = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_EXTRA_DETAIL] = true;
        }

        return $this;
    } // setExtraDetail()

    /**
     * Set the value of [availabilityid] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setAvailabilityid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availabilityid !== $v) {
            $this->availabilityid = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_AVAILABILITYID] = true;
        }

        if ($this->aAvailability !== null && $this->aAvailability->getAvailabilityid() !== $v) {
            $this->aAvailability = null;
        }

        return $this;
    } // setAvailabilityid()

    /**
     * Set the value of [availability_amount] column.
     * 
     * @param int $v new value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setAvailabilityAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availability_amount !== $v) {
            $this->availability_amount = $v;
            $this->modifiedColumns[OrderDetailTableMap::COL_AVAILABILITY_AMOUNT] = true;
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
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
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
            $this->modifiedColumns[OrderDetailTableMap::COL_VERIFIED] = true;
        }

        return $this;
    } // setVerified()

    /**
     * Sets the value of [distribution_finished] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setDistributionFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->distribution_finished !== null || $dt !== null) {
            if ($this->distribution_finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->distribution_finished->format("Y-m-d H:i:s.u")) {
                $this->distribution_finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderDetailTableMap::COL_DISTRIBUTION_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setDistributionFinished()

    /**
     * Sets the value of [invoice_finished] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function setInvoiceFinished($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->invoice_finished !== null || $dt !== null) {
            if ($this->invoice_finished === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->invoice_finished->format("Y-m-d H:i:s.u")) {
                $this->invoice_finished = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderDetailTableMap::COL_INVOICE_FINISHED] = true;
            }
        } // if either are not null

        return $this;
    } // setInvoiceFinished()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrderDetailTableMap::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_detailid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrderDetailTableMap::translateFieldName('Orderid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->orderid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrderDetailTableMap::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menuid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrderDetailTableMap::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_sizeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrderDetailTableMap::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_groupid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrderDetailTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OrderDetailTableMap::translateFieldName('SinglePrice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->single_price = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : OrderDetailTableMap::translateFieldName('SinglePriceModifiedByUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->single_price_modified_by_userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : OrderDetailTableMap::translateFieldName('ExtraDetail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->extra_detail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : OrderDetailTableMap::translateFieldName('Availabilityid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availabilityid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : OrderDetailTableMap::translateFieldName('AvailabilityAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availability_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : OrderDetailTableMap::translateFieldName('Verified', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verified = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : OrderDetailTableMap::translateFieldName('DistributionFinished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->distribution_finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : OrderDetailTableMap::translateFieldName('InvoiceFinished', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->invoice_finished = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = OrderDetailTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\ORM\\Ordering\\OrderDetail'), 0, $e);
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
        if ($this->aMenu !== null && $this->menuid !== $this->aMenu->getMenuid()) {
            $this->aMenu = null;
        }
        if ($this->aMenuSize !== null && $this->menu_sizeid !== $this->aMenuSize->getMenuSizeid()) {
            $this->aMenuSize = null;
        }
        if ($this->aMenuGroup !== null && $this->menu_groupid !== $this->aMenuGroup->getMenuGroupid()) {
            $this->aMenuGroup = null;
        }
        if ($this->aUser !== null && $this->single_price_modified_by_userid !== $this->aUser->getUserid()) {
            $this->aUser = null;
        }
        if ($this->aAvailability !== null && $this->availabilityid !== $this->aAvailability->getAvailabilityid()) {
            $this->aAvailability = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrderDetailQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAvailability = null;
            $this->aMenuGroup = null;
            $this->aMenuSize = null;
            $this->aMenu = null;
            $this->aOrder = null;
            $this->aUser = null;
            $this->collInvoiceItems = null;

            $this->collOrderDetailExtras = null;

            $this->collOrderDetailMixedWiths = null;

            $this->collOrderInProgressRecieveds = null;

            $this->collMenuPossibleExtras = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see OrderDetail::setDeleted()
     * @see OrderDetail::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOrderDetailQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailTableMap::DATABASE_NAME);
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
                OrderDetailTableMap::addInstanceToPool($this);
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

            if ($this->aAvailability !== null) {
                if ($this->aAvailability->isModified() || $this->aAvailability->isNew()) {
                    $affectedRows += $this->aAvailability->save($con);
                }
                $this->setAvailability($this->aAvailability);
            }

            if ($this->aMenuGroup !== null) {
                if ($this->aMenuGroup->isModified() || $this->aMenuGroup->isNew()) {
                    $affectedRows += $this->aMenuGroup->save($con);
                }
                $this->setMenuGroup($this->aMenuGroup);
            }

            if ($this->aMenuSize !== null) {
                if ($this->aMenuSize->isModified() || $this->aMenuSize->isNew()) {
                    $affectedRows += $this->aMenuSize->save($con);
                }
                $this->setMenuSize($this->aMenuSize);
            }

            if ($this->aMenu !== null) {
                if ($this->aMenu->isModified() || $this->aMenu->isNew()) {
                    $affectedRows += $this->aMenu->save($con);
                }
                $this->setMenu($this->aMenu);
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

            if ($this->menuPossibleExtrasScheduledForDeletion !== null) {
                if (!$this->menuPossibleExtrasScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->menuPossibleExtrasScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getOrderDetailid();
                        $entryPk[1] = $entry->getMenuPossibleExtraid();
                        $pks[] = $entryPk;
                    }

                    \API\Models\ORM\Ordering\OrderDetailExtraQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->menuPossibleExtrasScheduledForDeletion = null;
                }

            }

            if ($this->collMenuPossibleExtras) {
                foreach ($this->collMenuPossibleExtras as $menuPossibleExtra) {
                    if (!$menuPossibleExtra->isDeleted() && ($menuPossibleExtra->isNew() || $menuPossibleExtra->isModified())) {
                        $menuPossibleExtra->save($con);
                    }
                }
            }


            if ($this->invoiceItemsScheduledForDeletion !== null) {
                if (!$this->invoiceItemsScheduledForDeletion->isEmpty()) {
                    foreach ($this->invoiceItemsScheduledForDeletion as $invoiceItem) {
                        // need to save related object because we set the relation to null
                        $invoiceItem->save($con);
                    }
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

            if ($this->orderDetailExtrasScheduledForDeletion !== null) {
                if (!$this->orderDetailExtrasScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Ordering\OrderDetailExtraQuery::create()
                        ->filterByPrimaryKeys($this->orderDetailExtrasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->orderDetailExtrasScheduledForDeletion = null;
                }
            }

            if ($this->collOrderDetailExtras !== null) {
                foreach ($this->collOrderDetailExtras as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->orderDetailMixedWithsScheduledForDeletion !== null) {
                if (!$this->orderDetailMixedWithsScheduledForDeletion->isEmpty()) {
                    \API\Models\ORM\Ordering\OrderDetailMixedWithQuery::create()
                        ->filterByPrimaryKeys($this->orderDetailMixedWithsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->orderDetailMixedWithsScheduledForDeletion = null;
                }
            }

            if ($this->collOrderDetailMixedWiths !== null) {
                foreach ($this->collOrderDetailMixedWiths as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[OrderDetailTableMap::COL_ORDER_DETAILID] = true;
        if (null !== $this->order_detailid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrderDetailTableMap::COL_ORDER_DETAILID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrderDetailTableMap::COL_ORDER_DETAILID)) {
            $modifiedColumns[':p' . $index++]  = '`order_detailid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_ORDERID)) {
            $modifiedColumns[':p' . $index++]  = '`orderid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_MENUID)) {
            $modifiedColumns[':p' . $index++]  = '`menuid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_MENU_SIZEID)) {
            $modifiedColumns[':p' . $index++]  = '`menu_sizeid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_MENU_GROUPID)) {
            $modifiedColumns[':p' . $index++]  = '`menu_groupid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = '`amount`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_SINGLE_PRICE)) {
            $modifiedColumns[':p' . $index++]  = '`single_price`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID)) {
            $modifiedColumns[':p' . $index++]  = '`single_price_modified_by_userid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_EXTRA_DETAIL)) {
            $modifiedColumns[':p' . $index++]  = '`extra_detail`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_AVAILABILITYID)) {
            $modifiedColumns[':p' . $index++]  = '`availabilityid`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = '`availability_amount`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_VERIFIED)) {
            $modifiedColumns[':p' . $index++]  = '`verified`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_DISTRIBUTION_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = '`distribution_finished`';
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_INVOICE_FINISHED)) {
            $modifiedColumns[':p' . $index++]  = '`invoice_finished`';
        }

        $sql = sprintf(
            'INSERT INTO `order_detail` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`order_detailid`':                        
                        $stmt->bindValue($identifier, $this->order_detailid, PDO::PARAM_INT);
                        break;
                    case '`orderid`':                        
                        $stmt->bindValue($identifier, $this->orderid, PDO::PARAM_INT);
                        break;
                    case '`menuid`':                        
                        $stmt->bindValue($identifier, $this->menuid, PDO::PARAM_INT);
                        break;
                    case '`menu_sizeid`':                        
                        $stmt->bindValue($identifier, $this->menu_sizeid, PDO::PARAM_INT);
                        break;
                    case '`menu_groupid`':                        
                        $stmt->bindValue($identifier, $this->menu_groupid, PDO::PARAM_INT);
                        break;
                    case '`amount`':                        
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_INT);
                        break;
                    case '`single_price`':                        
                        $stmt->bindValue($identifier, $this->single_price, PDO::PARAM_STR);
                        break;
                    case '`single_price_modified_by_userid`':                        
                        $stmt->bindValue($identifier, $this->single_price_modified_by_userid, PDO::PARAM_INT);
                        break;
                    case '`extra_detail`':                        
                        $stmt->bindValue($identifier, $this->extra_detail, PDO::PARAM_STR);
                        break;
                    case '`availabilityid`':                        
                        $stmt->bindValue($identifier, $this->availabilityid, PDO::PARAM_INT);
                        break;
                    case '`availability_amount`':                        
                        $stmt->bindValue($identifier, $this->availability_amount, PDO::PARAM_INT);
                        break;
                    case '`verified`':
                        $stmt->bindValue($identifier, (int) $this->verified, PDO::PARAM_INT);
                        break;
                    case '`distribution_finished`':                        
                        $stmt->bindValue($identifier, $this->distribution_finished ? $this->distribution_finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`invoice_finished`':                        
                        $stmt->bindValue($identifier, $this->invoice_finished ? $this->invoice_finished->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $this->setOrderDetailid($pk);

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
        $pos = OrderDetailTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getOrderDetailid();
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
                return $this->getAvailabilityid();
                break;
            case 10:
                return $this->getAvailabilityAmount();
                break;
            case 11:
                return $this->getVerified();
                break;
            case 12:
                return $this->getDistributionFinished();
                break;
            case 13:
                return $this->getInvoiceFinished();
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

        if (isset($alreadyDumpedObjects['OrderDetail'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['OrderDetail'][$this->hashCode()] = true;
        $keys = OrderDetailTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrderDetailid(),
            $keys[1] => $this->getOrderid(),
            $keys[2] => $this->getMenuid(),
            $keys[3] => $this->getMenuSizeid(),
            $keys[4] => $this->getMenuGroupid(),
            $keys[5] => $this->getAmount(),
            $keys[6] => $this->getSinglePrice(),
            $keys[7] => $this->getSinglePriceModifiedByUserid(),
            $keys[8] => $this->getExtraDetail(),
            $keys[9] => $this->getAvailabilityid(),
            $keys[10] => $this->getAvailabilityAmount(),
            $keys[11] => $this->getVerified(),
            $keys[12] => $this->getDistributionFinished(),
            $keys[13] => $this->getInvoiceFinished(),
        );
        if ($result[$keys[12]] instanceof \DateTime) {
            $result[$keys[12]] = $result[$keys[12]]->format('c');
        }
        
        if ($result[$keys[13]] instanceof \DateTime) {
            $result[$keys[13]] = $result[$keys[13]]->format('c');
        }
        
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aAvailability) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'availability';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'availability';
                        break;
                    default:
                        $key = 'Availability';
                }
        
                $result[$key] = $this->aAvailability->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->aMenuSize) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuSize';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_size';
                        break;
                    default:
                        $key = 'MenuSize';
                }
        
                $result[$key] = $this->aMenuSize->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMenu) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menu';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu';
                        break;
                    default:
                        $key = 'Menu';
                }
        
                $result[$key] = $this->aMenu->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
            if (null !== $this->collOrderDetailExtras) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderDetailExtras';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order_detail_extras';
                        break;
                    default:
                        $key = 'OrderDetailExtras';
                }
        
                $result[$key] = $this->collOrderDetailExtras->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrderDetailMixedWiths) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderDetailMixedWiths';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order_detail_mixed_withs';
                        break;
                    default:
                        $key = 'OrderDetailMixedWiths';
                }
        
                $result[$key] = $this->collOrderDetailMixedWiths->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\ORM\Ordering\OrderDetail
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderDetailTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\ORM\Ordering\OrderDetail
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrderDetailid($value);
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
                $this->setAvailabilityid($value);
                break;
            case 10:
                $this->setAvailabilityAmount($value);
                break;
            case 11:
                $this->setVerified($value);
                break;
            case 12:
                $this->setDistributionFinished($value);
                break;
            case 13:
                $this->setInvoiceFinished($value);
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
        $keys = OrderDetailTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrderDetailid($arr[$keys[0]]);
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
            $this->setAvailabilityid($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAvailabilityAmount($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setVerified($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setDistributionFinished($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setInvoiceFinished($arr[$keys[13]]);
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
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object, for fluid interface
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
        $criteria = new Criteria(OrderDetailTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrderDetailTableMap::COL_ORDER_DETAILID)) {
            $criteria->add(OrderDetailTableMap::COL_ORDER_DETAILID, $this->order_detailid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_ORDERID)) {
            $criteria->add(OrderDetailTableMap::COL_ORDERID, $this->orderid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_MENUID)) {
            $criteria->add(OrderDetailTableMap::COL_MENUID, $this->menuid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_MENU_SIZEID)) {
            $criteria->add(OrderDetailTableMap::COL_MENU_SIZEID, $this->menu_sizeid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_MENU_GROUPID)) {
            $criteria->add(OrderDetailTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_AMOUNT)) {
            $criteria->add(OrderDetailTableMap::COL_AMOUNT, $this->amount);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_SINGLE_PRICE)) {
            $criteria->add(OrderDetailTableMap::COL_SINGLE_PRICE, $this->single_price);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID)) {
            $criteria->add(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $this->single_price_modified_by_userid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_EXTRA_DETAIL)) {
            $criteria->add(OrderDetailTableMap::COL_EXTRA_DETAIL, $this->extra_detail);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_AVAILABILITYID)) {
            $criteria->add(OrderDetailTableMap::COL_AVAILABILITYID, $this->availabilityid);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT)) {
            $criteria->add(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT, $this->availability_amount);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_VERIFIED)) {
            $criteria->add(OrderDetailTableMap::COL_VERIFIED, $this->verified);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_DISTRIBUTION_FINISHED)) {
            $criteria->add(OrderDetailTableMap::COL_DISTRIBUTION_FINISHED, $this->distribution_finished);
        }
        if ($this->isColumnModified(OrderDetailTableMap::COL_INVOICE_FINISHED)) {
            $criteria->add(OrderDetailTableMap::COL_INVOICE_FINISHED, $this->invoice_finished);
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
        $criteria = ChildOrderDetailQuery::create();
        $criteria->add(OrderDetailTableMap::COL_ORDER_DETAILID, $this->order_detailid);

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
        $validPk = null !== $this->getOrderDetailid();

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
        return $this->getOrderDetailid();
    }

    /**
     * Generic method to set the primary key (order_detailid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setOrderDetailid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getOrderDetailid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\ORM\Ordering\OrderDetail (or compatible) type.
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
        $copyObj->setAvailabilityid($this->getAvailabilityid());
        $copyObj->setAvailabilityAmount($this->getAvailabilityAmount());
        $copyObj->setVerified($this->getVerified());
        $copyObj->setDistributionFinished($this->getDistributionFinished());
        $copyObj->setInvoiceFinished($this->getInvoiceFinished());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getInvoiceItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInvoiceItem($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderDetailExtras() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderDetailExtra($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderDetailMixedWiths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderDetailMixedWith($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderInProgressRecieveds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderInProgressRecieved($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setOrderDetailid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\ORM\Ordering\OrderDetail Clone of current object.
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
     * Declares an association between this object and a Availability object.
     *
     * @param  Availability $v
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAvailability(Availability $v = null)
    {
        if ($v === null) {
            $this->setAvailabilityid(NULL);
        } else {
            $this->setAvailabilityid($v->getAvailabilityid());
        }

        $this->aAvailability = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Availability object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetail($this);
        }


        return $this;
    }


    /**
     * Get the associated Availability object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Availability The associated Availability object.
     * @throws PropelException
     */
    public function getAvailability(ConnectionInterface $con = null)
    {
        if ($this->aAvailability === null && ($this->availabilityid !== null)) {
            $this->aAvailability = AvailabilityQuery::create()->findPk($this->availabilityid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAvailability->addOrderDetails($this);
             */
        }

        return $this->aAvailability;
    }

    /**
     * Declares an association between this object and a MenuGroup object.
     *
     * @param  MenuGroup $v
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
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
            $v->addOrderDetail($this);
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
                $this->aMenuGroup->addOrderDetails($this);
             */
        }

        return $this->aMenuGroup;
    }

    /**
     * Declares an association between this object and a MenuSize object.
     *
     * @param  MenuSize $v
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuSize(MenuSize $v = null)
    {
        if ($v === null) {
            $this->setMenuSizeid(NULL);
        } else {
            $this->setMenuSizeid($v->getMenuSizeid());
        }

        $this->aMenuSize = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the MenuSize object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetail($this);
        }


        return $this;
    }


    /**
     * Get the associated MenuSize object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return MenuSize The associated MenuSize object.
     * @throws PropelException
     */
    public function getMenuSize(ConnectionInterface $con = null)
    {
        if ($this->aMenuSize === null && ($this->menu_sizeid !== null)) {
            $this->aMenuSize = MenuSizeQuery::create()->findPk($this->menu_sizeid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuSize->addOrderDetails($this);
             */
        }

        return $this->aMenuSize;
    }

    /**
     * Declares an association between this object and a Menu object.
     *
     * @param  Menu $v
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenu(Menu $v = null)
    {
        if ($v === null) {
            $this->setMenuid(NULL);
        } else {
            $this->setMenuid($v->getMenuid());
        }

        $this->aMenu = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Menu object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetail($this);
        }


        return $this;
    }


    /**
     * Get the associated Menu object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Menu The associated Menu object.
     * @throws PropelException
     */
    public function getMenu(ConnectionInterface $con = null)
    {
        if ($this->aMenu === null && ($this->menuid !== null)) {
            $this->aMenu = MenuQuery::create()->findPk($this->menuid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenu->addOrderDetails($this);
             */
        }

        return $this->aMenu;
    }

    /**
     * Declares an association between this object and a ChildOrder object.
     *
     * @param  ChildOrder $v
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrder(ChildOrder $v = null)
    {
        if ($v === null) {
            $this->setOrderid(NULL);
        } else {
            $this->setOrderid($v->getOrderid());
        }

        $this->aOrder = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildOrder object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetail($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildOrder object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildOrder The associated ChildOrder object.
     * @throws PropelException
     */
    public function getOrder(ConnectionInterface $con = null)
    {
        if ($this->aOrder === null && ($this->orderid !== null)) {
            $this->aOrder = ChildOrderQuery::create()->findPk($this->orderid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrder->addOrderDetails($this);
             */
        }

        return $this->aOrder;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setSinglePriceModifiedByUserid(NULL);
        } else {
            $this->setSinglePriceModifiedByUserid($v->getUserid());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetail($this);
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
        if ($this->aUser === null && ($this->single_price_modified_by_userid !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->single_price_modified_by_userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addOrderDetails($this);
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
        if ('OrderDetailExtra' == $relationName) {
            return $this->initOrderDetailExtras();
        }
        if ('OrderDetailMixedWith' == $relationName) {
            return $this->initOrderDetailMixedWiths();
        }
        if ('OrderInProgressRecieved' == $relationName) {
            return $this->initOrderInProgressRecieveds();
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
        $this->collInvoiceItems->setModel('\API\Models\ORM\Invoice\InvoiceItem');
    }

    /**
     * Gets an array of InvoiceItem objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrderDetail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|InvoiceItem[] List of InvoiceItem objects
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
                $collInvoiceItems = InvoiceItemQuery::create(null, $criteria)
                    ->filterByOrderDetail($this)
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
     * Sets a collection of InvoiceItem objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $invoiceItems A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function setInvoiceItems(Collection $invoiceItems, ConnectionInterface $con = null)
    {
        /** @var InvoiceItem[] $invoiceItemsToDelete */
        $invoiceItemsToDelete = $this->getInvoiceItems(new Criteria(), $con)->diff($invoiceItems);

        
        $this->invoiceItemsScheduledForDeletion = $invoiceItemsToDelete;

        foreach ($invoiceItemsToDelete as $invoiceItemRemoved) {
            $invoiceItemRemoved->setOrderDetail(null);
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
     * Returns the number of related BaseInvoiceItem objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseInvoiceItem objects.
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

            $query = InvoiceItemQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrderDetail($this)
                ->count($con);
        }

        return count($this->collInvoiceItems);
    }

    /**
     * Method called to associate a InvoiceItem object to this object
     * through the InvoiceItem foreign key attribute.
     *
     * @param  InvoiceItem $l InvoiceItem
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function addInvoiceItem(InvoiceItem $l)
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
     * @param InvoiceItem $invoiceItem The InvoiceItem object to add.
     */
    protected function doAddInvoiceItem(InvoiceItem $invoiceItem)
    {
        $this->collInvoiceItems[]= $invoiceItem;
        $invoiceItem->setOrderDetail($this);
    }

    /**
     * @param  InvoiceItem $invoiceItem The InvoiceItem object to remove.
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function removeInvoiceItem(InvoiceItem $invoiceItem)
    {
        if ($this->getInvoiceItems()->contains($invoiceItem)) {
            $pos = $this->collInvoiceItems->search($invoiceItem);
            $this->collInvoiceItems->remove($pos);
            if (null === $this->invoiceItemsScheduledForDeletion) {
                $this->invoiceItemsScheduledForDeletion = clone $this->collInvoiceItems;
                $this->invoiceItemsScheduledForDeletion->clear();
            }
            $this->invoiceItemsScheduledForDeletion[]= $invoiceItem;
            $invoiceItem->setOrderDetail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderDetail is new, it will return
     * an empty collection; or if this OrderDetail has previously
     * been saved, it will retrieve related InvoiceItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderDetail.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|InvoiceItem[] List of InvoiceItem objects
     */
    public function getInvoiceItemsJoinInvoice(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = InvoiceItemQuery::create(null, $criteria);
        $query->joinWith('Invoice', $joinBehavior);

        return $this->getInvoiceItems($query, $con);
    }

    /**
     * Clears out the collOrderDetailExtras collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrderDetailExtras()
     */
    public function clearOrderDetailExtras()
    {
        $this->collOrderDetailExtras = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrderDetailExtras collection loaded partially.
     */
    public function resetPartialOrderDetailExtras($v = true)
    {
        $this->collOrderDetailExtrasPartial = $v;
    }

    /**
     * Initializes the collOrderDetailExtras collection.
     *
     * By default this just sets the collOrderDetailExtras collection to an empty array (like clearcollOrderDetailExtras());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrderDetailExtras($overrideExisting = true)
    {
        if (null !== $this->collOrderDetailExtras && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderDetailExtraTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderDetailExtras = new $collectionClassName;
        $this->collOrderDetailExtras->setModel('\API\Models\ORM\Ordering\OrderDetailExtra');
    }

    /**
     * Gets an array of ChildOrderDetailExtra objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrderDetail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrderDetailExtra[] List of ChildOrderDetailExtra objects
     * @throws PropelException
     */
    public function getOrderDetailExtras(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailExtrasPartial && !$this->isNew();
        if (null === $this->collOrderDetailExtras || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrderDetailExtras) {
                // return empty collection
                $this->initOrderDetailExtras();
            } else {
                $collOrderDetailExtras = ChildOrderDetailExtraQuery::create(null, $criteria)
                    ->filterByOrderDetail($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrderDetailExtrasPartial && count($collOrderDetailExtras)) {
                        $this->initOrderDetailExtras(false);

                        foreach ($collOrderDetailExtras as $obj) {
                            if (false == $this->collOrderDetailExtras->contains($obj)) {
                                $this->collOrderDetailExtras->append($obj);
                            }
                        }

                        $this->collOrderDetailExtrasPartial = true;
                    }

                    return $collOrderDetailExtras;
                }

                if ($partial && $this->collOrderDetailExtras) {
                    foreach ($this->collOrderDetailExtras as $obj) {
                        if ($obj->isNew()) {
                            $collOrderDetailExtras[] = $obj;
                        }
                    }
                }

                $this->collOrderDetailExtras = $collOrderDetailExtras;
                $this->collOrderDetailExtrasPartial = false;
            }
        }

        return $this->collOrderDetailExtras;
    }

    /**
     * Sets a collection of ChildOrderDetailExtra objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderDetailExtras A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function setOrderDetailExtras(Collection $orderDetailExtras, ConnectionInterface $con = null)
    {
        /** @var ChildOrderDetailExtra[] $orderDetailExtrasToDelete */
        $orderDetailExtrasToDelete = $this->getOrderDetailExtras(new Criteria(), $con)->diff($orderDetailExtras);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->orderDetailExtrasScheduledForDeletion = clone $orderDetailExtrasToDelete;

        foreach ($orderDetailExtrasToDelete as $orderDetailExtraRemoved) {
            $orderDetailExtraRemoved->setOrderDetail(null);
        }

        $this->collOrderDetailExtras = null;
        foreach ($orderDetailExtras as $orderDetailExtra) {
            $this->addOrderDetailExtra($orderDetailExtra);
        }

        $this->collOrderDetailExtras = $orderDetailExtras;
        $this->collOrderDetailExtrasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrderDetailExtra objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related OrderDetailExtra objects.
     * @throws PropelException
     */
    public function countOrderDetailExtras(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailExtrasPartial && !$this->isNew();
        if (null === $this->collOrderDetailExtras || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderDetailExtras) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrderDetailExtras());
            }

            $query = ChildOrderDetailExtraQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrderDetail($this)
                ->count($con);
        }

        return count($this->collOrderDetailExtras);
    }

    /**
     * Method called to associate a ChildOrderDetailExtra object to this object
     * through the ChildOrderDetailExtra foreign key attribute.
     *
     * @param  ChildOrderDetailExtra $l ChildOrderDetailExtra
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function addOrderDetailExtra(ChildOrderDetailExtra $l)
    {
        if ($this->collOrderDetailExtras === null) {
            $this->initOrderDetailExtras();
            $this->collOrderDetailExtrasPartial = true;
        }

        if (!$this->collOrderDetailExtras->contains($l)) {
            $this->doAddOrderDetailExtra($l);

            if ($this->orderDetailExtrasScheduledForDeletion and $this->orderDetailExtrasScheduledForDeletion->contains($l)) {
                $this->orderDetailExtrasScheduledForDeletion->remove($this->orderDetailExtrasScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrderDetailExtra $orderDetailExtra The ChildOrderDetailExtra object to add.
     */
    protected function doAddOrderDetailExtra(ChildOrderDetailExtra $orderDetailExtra)
    {
        $this->collOrderDetailExtras[]= $orderDetailExtra;
        $orderDetailExtra->setOrderDetail($this);
    }

    /**
     * @param  ChildOrderDetailExtra $orderDetailExtra The ChildOrderDetailExtra object to remove.
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function removeOrderDetailExtra(ChildOrderDetailExtra $orderDetailExtra)
    {
        if ($this->getOrderDetailExtras()->contains($orderDetailExtra)) {
            $pos = $this->collOrderDetailExtras->search($orderDetailExtra);
            $this->collOrderDetailExtras->remove($pos);
            if (null === $this->orderDetailExtrasScheduledForDeletion) {
                $this->orderDetailExtrasScheduledForDeletion = clone $this->collOrderDetailExtras;
                $this->orderDetailExtrasScheduledForDeletion->clear();
            }
            $this->orderDetailExtrasScheduledForDeletion[]= clone $orderDetailExtra;
            $orderDetailExtra->setOrderDetail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderDetail is new, it will return
     * an empty collection; or if this OrderDetail has previously
     * been saved, it will retrieve related OrderDetailExtras from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderDetail.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetailExtra[] List of ChildOrderDetailExtra objects
     */
    public function getOrderDetailExtrasJoinMenuPossibleExtra(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailExtraQuery::create(null, $criteria);
        $query->joinWith('MenuPossibleExtra', $joinBehavior);

        return $this->getOrderDetailExtras($query, $con);
    }

    /**
     * Clears out the collOrderDetailMixedWiths collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOrderDetailMixedWiths()
     */
    public function clearOrderDetailMixedWiths()
    {
        $this->collOrderDetailMixedWiths = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOrderDetailMixedWiths collection loaded partially.
     */
    public function resetPartialOrderDetailMixedWiths($v = true)
    {
        $this->collOrderDetailMixedWithsPartial = $v;
    }

    /**
     * Initializes the collOrderDetailMixedWiths collection.
     *
     * By default this just sets the collOrderDetailMixedWiths collection to an empty array (like clearcollOrderDetailMixedWiths());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrderDetailMixedWiths($overrideExisting = true)
    {
        if (null !== $this->collOrderDetailMixedWiths && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderDetailMixedWithTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderDetailMixedWiths = new $collectionClassName;
        $this->collOrderDetailMixedWiths->setModel('\API\Models\ORM\Ordering\OrderDetailMixedWith');
    }

    /**
     * Gets an array of ChildOrderDetailMixedWith objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrderDetail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrderDetailMixedWith[] List of ChildOrderDetailMixedWith objects
     * @throws PropelException
     */
    public function getOrderDetailMixedWiths(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailMixedWithsPartial && !$this->isNew();
        if (null === $this->collOrderDetailMixedWiths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOrderDetailMixedWiths) {
                // return empty collection
                $this->initOrderDetailMixedWiths();
            } else {
                $collOrderDetailMixedWiths = ChildOrderDetailMixedWithQuery::create(null, $criteria)
                    ->filterByOrderDetail($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrderDetailMixedWithsPartial && count($collOrderDetailMixedWiths)) {
                        $this->initOrderDetailMixedWiths(false);

                        foreach ($collOrderDetailMixedWiths as $obj) {
                            if (false == $this->collOrderDetailMixedWiths->contains($obj)) {
                                $this->collOrderDetailMixedWiths->append($obj);
                            }
                        }

                        $this->collOrderDetailMixedWithsPartial = true;
                    }

                    return $collOrderDetailMixedWiths;
                }

                if ($partial && $this->collOrderDetailMixedWiths) {
                    foreach ($this->collOrderDetailMixedWiths as $obj) {
                        if ($obj->isNew()) {
                            $collOrderDetailMixedWiths[] = $obj;
                        }
                    }
                }

                $this->collOrderDetailMixedWiths = $collOrderDetailMixedWiths;
                $this->collOrderDetailMixedWithsPartial = false;
            }
        }

        return $this->collOrderDetailMixedWiths;
    }

    /**
     * Sets a collection of ChildOrderDetailMixedWith objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderDetailMixedWiths A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function setOrderDetailMixedWiths(Collection $orderDetailMixedWiths, ConnectionInterface $con = null)
    {
        /** @var ChildOrderDetailMixedWith[] $orderDetailMixedWithsToDelete */
        $orderDetailMixedWithsToDelete = $this->getOrderDetailMixedWiths(new Criteria(), $con)->diff($orderDetailMixedWiths);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->orderDetailMixedWithsScheduledForDeletion = clone $orderDetailMixedWithsToDelete;

        foreach ($orderDetailMixedWithsToDelete as $orderDetailMixedWithRemoved) {
            $orderDetailMixedWithRemoved->setOrderDetail(null);
        }

        $this->collOrderDetailMixedWiths = null;
        foreach ($orderDetailMixedWiths as $orderDetailMixedWith) {
            $this->addOrderDetailMixedWith($orderDetailMixedWith);
        }

        $this->collOrderDetailMixedWiths = $orderDetailMixedWiths;
        $this->collOrderDetailMixedWithsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related OrderDetailMixedWith objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related OrderDetailMixedWith objects.
     * @throws PropelException
     */
    public function countOrderDetailMixedWiths(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailMixedWithsPartial && !$this->isNew();
        if (null === $this->collOrderDetailMixedWiths || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderDetailMixedWiths) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrderDetailMixedWiths());
            }

            $query = ChildOrderDetailMixedWithQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrderDetail($this)
                ->count($con);
        }

        return count($this->collOrderDetailMixedWiths);
    }

    /**
     * Method called to associate a ChildOrderDetailMixedWith object to this object
     * through the ChildOrderDetailMixedWith foreign key attribute.
     *
     * @param  ChildOrderDetailMixedWith $l ChildOrderDetailMixedWith
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function addOrderDetailMixedWith(ChildOrderDetailMixedWith $l)
    {
        if ($this->collOrderDetailMixedWiths === null) {
            $this->initOrderDetailMixedWiths();
            $this->collOrderDetailMixedWithsPartial = true;
        }

        if (!$this->collOrderDetailMixedWiths->contains($l)) {
            $this->doAddOrderDetailMixedWith($l);

            if ($this->orderDetailMixedWithsScheduledForDeletion and $this->orderDetailMixedWithsScheduledForDeletion->contains($l)) {
                $this->orderDetailMixedWithsScheduledForDeletion->remove($this->orderDetailMixedWithsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrderDetailMixedWith $orderDetailMixedWith The ChildOrderDetailMixedWith object to add.
     */
    protected function doAddOrderDetailMixedWith(ChildOrderDetailMixedWith $orderDetailMixedWith)
    {
        $this->collOrderDetailMixedWiths[]= $orderDetailMixedWith;
        $orderDetailMixedWith->setOrderDetail($this);
    }

    /**
     * @param  ChildOrderDetailMixedWith $orderDetailMixedWith The ChildOrderDetailMixedWith object to remove.
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function removeOrderDetailMixedWith(ChildOrderDetailMixedWith $orderDetailMixedWith)
    {
        if ($this->getOrderDetailMixedWiths()->contains($orderDetailMixedWith)) {
            $pos = $this->collOrderDetailMixedWiths->search($orderDetailMixedWith);
            $this->collOrderDetailMixedWiths->remove($pos);
            if (null === $this->orderDetailMixedWithsScheduledForDeletion) {
                $this->orderDetailMixedWithsScheduledForDeletion = clone $this->collOrderDetailMixedWiths;
                $this->orderDetailMixedWithsScheduledForDeletion->clear();
            }
            $this->orderDetailMixedWithsScheduledForDeletion[]= clone $orderDetailMixedWith;
            $orderDetailMixedWith->setOrderDetail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderDetail is new, it will return
     * an empty collection; or if this OrderDetail has previously
     * been saved, it will retrieve related OrderDetailMixedWiths from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderDetail.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrderDetailMixedWith[] List of ChildOrderDetailMixedWith objects
     */
    public function getOrderDetailMixedWithsJoinMenu(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderDetailMixedWithQuery::create(null, $criteria);
        $query->joinWith('Menu', $joinBehavior);

        return $this->getOrderDetailMixedWiths($query, $con);
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
     * Gets an array of OrderInProgressRecieved objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrderDetail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrderInProgressRecieved[] List of OrderInProgressRecieved objects
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
                $collOrderInProgressRecieveds = OrderInProgressRecievedQuery::create(null, $criteria)
                    ->filterByOrderDetail($this)
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
     * Sets a collection of OrderInProgressRecieved objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderInProgressRecieveds A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function setOrderInProgressRecieveds(Collection $orderInProgressRecieveds, ConnectionInterface $con = null)
    {
        /** @var OrderInProgressRecieved[] $orderInProgressRecievedsToDelete */
        $orderInProgressRecievedsToDelete = $this->getOrderInProgressRecieveds(new Criteria(), $con)->diff($orderInProgressRecieveds);

        
        $this->orderInProgressRecievedsScheduledForDeletion = $orderInProgressRecievedsToDelete;

        foreach ($orderInProgressRecievedsToDelete as $orderInProgressRecievedRemoved) {
            $orderInProgressRecievedRemoved->setOrderDetail(null);
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
     * Returns the number of related BaseOrderInProgressRecieved objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrderInProgressRecieved objects.
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

            $query = OrderInProgressRecievedQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrderDetail($this)
                ->count($con);
        }

        return count($this->collOrderInProgressRecieveds);
    }

    /**
     * Method called to associate a OrderInProgressRecieved object to this object
     * through the OrderInProgressRecieved foreign key attribute.
     *
     * @param  OrderInProgressRecieved $l OrderInProgressRecieved
     * @return $this|\API\Models\ORM\Ordering\OrderDetail The current object (for fluent API support)
     */
    public function addOrderInProgressRecieved(OrderInProgressRecieved $l)
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
     * @param OrderInProgressRecieved $orderInProgressRecieved The OrderInProgressRecieved object to add.
     */
    protected function doAddOrderInProgressRecieved(OrderInProgressRecieved $orderInProgressRecieved)
    {
        $this->collOrderInProgressRecieveds[]= $orderInProgressRecieved;
        $orderInProgressRecieved->setOrderDetail($this);
    }

    /**
     * @param  OrderInProgressRecieved $orderInProgressRecieved The OrderInProgressRecieved object to remove.
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function removeOrderInProgressRecieved(OrderInProgressRecieved $orderInProgressRecieved)
    {
        if ($this->getOrderInProgressRecieveds()->contains($orderInProgressRecieved)) {
            $pos = $this->collOrderInProgressRecieveds->search($orderInProgressRecieved);
            $this->collOrderInProgressRecieveds->remove($pos);
            if (null === $this->orderInProgressRecievedsScheduledForDeletion) {
                $this->orderInProgressRecievedsScheduledForDeletion = clone $this->collOrderInProgressRecieveds;
                $this->orderInProgressRecievedsScheduledForDeletion->clear();
            }
            $this->orderInProgressRecievedsScheduledForDeletion[]= clone $orderInProgressRecieved;
            $orderInProgressRecieved->setOrderDetail(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderDetail is new, it will return
     * an empty collection; or if this OrderDetail has previously
     * been saved, it will retrieve related OrderInProgressRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderDetail.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderInProgressRecieved[] List of OrderInProgressRecieved objects
     */
    public function getOrderInProgressRecievedsJoinOrderInProgress(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderInProgressRecievedQuery::create(null, $criteria);
        $query->joinWith('OrderInProgress', $joinBehavior);

        return $this->getOrderInProgressRecieveds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this OrderDetail is new, it will return
     * an empty collection; or if this OrderDetail has previously
     * been saved, it will retrieve related OrderInProgressRecieveds from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in OrderDetail.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderInProgressRecieved[] List of OrderInProgressRecieved objects
     */
    public function getOrderInProgressRecievedsJoinDistributionGivingOut(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderInProgressRecievedQuery::create(null, $criteria);
        $query->joinWith('DistributionGivingOut', $joinBehavior);

        return $this->getOrderInProgressRecieveds($query, $con);
    }

    /**
     * Clears out the collMenuPossibleExtras collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuPossibleExtras()
     */
    public function clearMenuPossibleExtras()
    {
        $this->collMenuPossibleExtras = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collMenuPossibleExtras crossRef collection.
     *
     * By default this just sets the collMenuPossibleExtras collection to an empty collection (like clearMenuPossibleExtras());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMenuPossibleExtras()
    {
        $collectionClassName = OrderDetailExtraTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuPossibleExtras = new $collectionClassName;
        $this->collMenuPossibleExtrasPartial = true;
        $this->collMenuPossibleExtras->setModel('\API\Models\ORM\Menu\MenuPossibleExtra');
    }

    /**
     * Checks if the collMenuPossibleExtras collection is loaded.
     *
     * @return bool
     */
    public function isMenuPossibleExtrasLoaded()
    {
        return null !== $this->collMenuPossibleExtras;
    }

    /**
     * Gets a collection of MenuPossibleExtra objects related by a many-to-many relationship
     * to the current object by way of the order_detail_extra cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrderDetail is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|MenuPossibleExtra[] List of MenuPossibleExtra objects
     */
    public function getMenuPossibleExtras(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuPossibleExtrasPartial && !$this->isNew();
        if (null === $this->collMenuPossibleExtras || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMenuPossibleExtras) {
                    $this->initMenuPossibleExtras();
                }
            } else {

                $query = MenuPossibleExtraQuery::create(null, $criteria)
                    ->filterByOrderDetail($this);
                $collMenuPossibleExtras = $query->find($con);
                if (null !== $criteria) {
                    return $collMenuPossibleExtras;
                }

                if ($partial && $this->collMenuPossibleExtras) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collMenuPossibleExtras as $obj) {
                        if (!$collMenuPossibleExtras->contains($obj)) {
                            $collMenuPossibleExtras[] = $obj;
                        }
                    }
                }

                $this->collMenuPossibleExtras = $collMenuPossibleExtras;
                $this->collMenuPossibleExtrasPartial = false;
            }
        }

        return $this->collMenuPossibleExtras;
    }

    /**
     * Sets a collection of MenuPossibleExtra objects related by a many-to-many relationship
     * to the current object by way of the order_detail_extra cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $menuPossibleExtras A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildOrderDetail The current object (for fluent API support)
     */
    public function setMenuPossibleExtras(Collection $menuPossibleExtras, ConnectionInterface $con = null)
    {
        $this->clearMenuPossibleExtras();
        $currentMenuPossibleExtras = $this->getMenuPossibleExtras();

        $menuPossibleExtrasScheduledForDeletion = $currentMenuPossibleExtras->diff($menuPossibleExtras);

        foreach ($menuPossibleExtrasScheduledForDeletion as $toDelete) {
            $this->removeMenuPossibleExtra($toDelete);
        }

        foreach ($menuPossibleExtras as $menuPossibleExtra) {
            if (!$currentMenuPossibleExtras->contains($menuPossibleExtra)) {
                $this->doAddMenuPossibleExtra($menuPossibleExtra);
            }
        }

        $this->collMenuPossibleExtrasPartial = false;
        $this->collMenuPossibleExtras = $menuPossibleExtras;

        return $this;
    }

    /**
     * Gets the number of MenuPossibleExtra objects related by a many-to-many relationship
     * to the current object by way of the order_detail_extra cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related MenuPossibleExtra objects
     */
    public function countMenuPossibleExtras(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuPossibleExtrasPartial && !$this->isNew();
        if (null === $this->collMenuPossibleExtras || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuPossibleExtras) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getMenuPossibleExtras());
                }

                $query = MenuPossibleExtraQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByOrderDetail($this)
                    ->count($con);
            }
        } else {
            return count($this->collMenuPossibleExtras);
        }
    }

    /**
     * Associate a MenuPossibleExtra to this object
     * through the order_detail_extra cross reference table.
     * 
     * @param MenuPossibleExtra $menuPossibleExtra
     * @return ChildOrderDetail The current object (for fluent API support)
     */
    public function addMenuPossibleExtra(MenuPossibleExtra $menuPossibleExtra)
    {
        if ($this->collMenuPossibleExtras === null) {
            $this->initMenuPossibleExtras();
        }

        if (!$this->getMenuPossibleExtras()->contains($menuPossibleExtra)) {
            // only add it if the **same** object is not already associated
            $this->collMenuPossibleExtras->push($menuPossibleExtra);
            $this->doAddMenuPossibleExtra($menuPossibleExtra);
        }

        return $this;
    }

    /**
     * 
     * @param MenuPossibleExtra $menuPossibleExtra
     */
    protected function doAddMenuPossibleExtra(MenuPossibleExtra $menuPossibleExtra)
    {
        $orderDetailExtra = new ChildOrderDetailExtra();

        $orderDetailExtra->setMenuPossibleExtra($menuPossibleExtra);

        $orderDetailExtra->setOrderDetail($this);

        $this->addOrderDetailExtra($orderDetailExtra);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$menuPossibleExtra->isOrderDetailsLoaded()) {
            $menuPossibleExtra->initOrderDetails();
            $menuPossibleExtra->getOrderDetails()->push($this);
        } elseif (!$menuPossibleExtra->getOrderDetails()->contains($this)) {
            $menuPossibleExtra->getOrderDetails()->push($this);
        }

    }

    /**
     * Remove menuPossibleExtra of this object
     * through the order_detail_extra cross reference table.
     * 
     * @param MenuPossibleExtra $menuPossibleExtra
     * @return ChildOrderDetail The current object (for fluent API support)
     */
    public function removeMenuPossibleExtra(MenuPossibleExtra $menuPossibleExtra)
    {
        if ($this->getMenuPossibleExtras()->contains($menuPossibleExtra)) {
            $orderDetailExtra = new ChildOrderDetailExtra();
            $orderDetailExtra->setMenuPossibleExtra($menuPossibleExtra);
            if ($menuPossibleExtra->isOrderDetailsLoaded()) {
                //remove the back reference if available
                $menuPossibleExtra->getOrderDetails()->removeObject($this);
            }

            $orderDetailExtra->setOrderDetail($this);
            $this->removeOrderDetailExtra(clone $orderDetailExtra);
            $orderDetailExtra->clear();

            $this->collMenuPossibleExtras->remove($this->collMenuPossibleExtras->search($menuPossibleExtra));
            
            if (null === $this->menuPossibleExtrasScheduledForDeletion) {
                $this->menuPossibleExtrasScheduledForDeletion = clone $this->collMenuPossibleExtras;
                $this->menuPossibleExtrasScheduledForDeletion->clear();
            }

            $this->menuPossibleExtrasScheduledForDeletion->push($menuPossibleExtra);
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
        if (null !== $this->aAvailability) {
            $this->aAvailability->removeOrderDetail($this);
        }
        if (null !== $this->aMenuGroup) {
            $this->aMenuGroup->removeOrderDetail($this);
        }
        if (null !== $this->aMenuSize) {
            $this->aMenuSize->removeOrderDetail($this);
        }
        if (null !== $this->aMenu) {
            $this->aMenu->removeOrderDetail($this);
        }
        if (null !== $this->aOrder) {
            $this->aOrder->removeOrderDetail($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeOrderDetail($this);
        }
        $this->order_detailid = null;
        $this->orderid = null;
        $this->menuid = null;
        $this->menu_sizeid = null;
        $this->menu_groupid = null;
        $this->amount = null;
        $this->single_price = null;
        $this->single_price_modified_by_userid = null;
        $this->extra_detail = null;
        $this->availabilityid = null;
        $this->availability_amount = null;
        $this->verified = null;
        $this->distribution_finished = null;
        $this->invoice_finished = null;
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
            if ($this->collOrderDetailExtras) {
                foreach ($this->collOrderDetailExtras as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderDetailMixedWiths) {
                foreach ($this->collOrderDetailMixedWiths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderInProgressRecieveds) {
                foreach ($this->collOrderInProgressRecieveds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuPossibleExtras) {
                foreach ($this->collMenuPossibleExtras as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collInvoiceItems = null;
        $this->collOrderDetailExtras = null;
        $this->collOrderDetailMixedWiths = null;
        $this->collOrderInProgressRecieveds = null;
        $this->collMenuPossibleExtras = null;
        $this->aAvailability = null;
        $this->aMenuGroup = null;
        $this->aMenuSize = null;
        $this->aMenu = null;
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
        return (string) $this->exportTo(OrderDetailTableMap::DEFAULT_STRING_FORMAT);
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
