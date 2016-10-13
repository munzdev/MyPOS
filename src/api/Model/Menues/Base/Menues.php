<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\Menues\Availabilitys as ChildAvailabilitys;
use Model\Menues\AvailabilitysQuery as ChildAvailabilitysQuery;
use Model\Menues\MenuGroupes as ChildMenuGroupes;
use Model\Menues\MenuGroupesQuery as ChildMenuGroupesQuery;
use Model\Menues\Menues as ChildMenues;
use Model\Menues\MenuesPossibleExtras as ChildMenuesPossibleExtras;
use Model\Menues\MenuesPossibleExtrasQuery as ChildMenuesPossibleExtrasQuery;
use Model\Menues\MenuesPossibleSizes as ChildMenuesPossibleSizes;
use Model\Menues\MenuesPossibleSizesQuery as ChildMenuesPossibleSizesQuery;
use Model\Menues\MenuesQuery as ChildMenuesQuery;
use Model\Menues\Map\MenuesPossibleExtrasTableMap;
use Model\Menues\Map\MenuesPossibleSizesTableMap;
use Model\Menues\Map\MenuesTableMap;
use Model\Ordering\OrdersDetails;
use Model\Ordering\OrdersDetailsMixedWith;
use Model\Ordering\OrdersDetailsMixedWithQuery;
use Model\Ordering\OrdersDetailsQuery;
use Model\Ordering\Base\OrdersDetails as BaseOrdersDetails;
use Model\Ordering\Base\OrdersDetailsMixedWith as BaseOrdersDetailsMixedWith;
use Model\Ordering\Map\OrdersDetailsMixedWithTableMap;
use Model\Ordering\Map\OrdersDetailsTableMap;
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

/**
 * Base class that represents a row from the 'menues' table.
 *
 *
 *
 * @package    propel.generator.Model.Menues.Base
 */
abstract class Menues implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Menues\\Map\\MenuesTableMap';


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
     * The value for the menuid field.
     *
     * @var        int
     */
    protected $menuid;

    /**
     * The value for the menu_groupid field.
     *
     * @var        int
     */
    protected $menu_groupid;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the price field.
     *
     * @var        string
     */
    protected $price;

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
     * @var        ChildAvailabilitys
     */
    protected $aAvailabilitys;

    /**
     * @var        ChildMenuGroupes
     */
    protected $aMenuGroupes;

    /**
     * @var        ObjectCollection|ChildMenuesPossibleExtras[] Collection to store aggregation of ChildMenuesPossibleExtras objects.
     */
    protected $collMenuesPossibleExtrass;
    protected $collMenuesPossibleExtrassPartial;

    /**
     * @var        ObjectCollection|ChildMenuesPossibleSizes[] Collection to store aggregation of ChildMenuesPossibleSizes objects.
     */
    protected $collMenuesPossibleSizess;
    protected $collMenuesPossibleSizessPartial;

    /**
     * @var        ObjectCollection|OrdersDetails[] Collection to store aggregation of OrdersDetails objects.
     */
    protected $collOrdersDetailss;
    protected $collOrdersDetailssPartial;

    /**
     * @var        ObjectCollection|OrdersDetailsMixedWith[] Collection to store aggregation of OrdersDetailsMixedWith objects.
     */
    protected $collOrdersDetailsMixedWiths;
    protected $collOrdersDetailsMixedWithsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenuesPossibleExtras[]
     */
    protected $menuesPossibleExtrassScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenuesPossibleSizes[]
     */
    protected $menuesPossibleSizessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersDetails[]
     */
    protected $ordersDetailssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrdersDetailsMixedWith[]
     */
    protected $ordersDetailsMixedWithsScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Menues\Base\Menues object.
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
     * Compares this with another <code>Menues</code> instance.  If
     * <code>obj</code> is an instance of <code>Menues</code>, delegates to
     * <code>equals(Menues)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Menues The current object, for fluid interface
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
     * Get the [menuid] column value.
     *
     * @return int
     */
    public function getMenuid()
    {
        return $this->menuid;
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [price] column value.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
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
     * Set the value of [menuid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function setMenuid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menuid !== $v) {
            $this->menuid = $v;
            $this->modifiedColumns[MenuesTableMap::COL_MENUID] = true;
        }

        return $this;
    } // setMenuid()

    /**
     * Set the value of [menu_groupid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function setMenuGroupid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_groupid !== $v) {
            $this->menu_groupid = $v;
            $this->modifiedColumns[MenuesTableMap::COL_MENU_GROUPID] = true;
        }

        if ($this->aMenuGroupes !== null && $this->aMenuGroupes->getMenuGroupid() !== $v) {
            $this->aMenuGroupes = null;
        }

        return $this;
    } // setMenuGroupid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MenuesTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [price] column.
     *
     * @param string $v new value
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->price !== $v) {
            $this->price = $v;
            $this->modifiedColumns[MenuesTableMap::COL_PRICE] = true;
        }

        return $this;
    } // setPrice()

    /**
     * Set the value of [availabilityid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function setAvailabilityid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availabilityid !== $v) {
            $this->availabilityid = $v;
            $this->modifiedColumns[MenuesTableMap::COL_AVAILABILITYID] = true;
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
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function setAvailabilityAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availability_amount !== $v) {
            $this->availability_amount = $v;
            $this->modifiedColumns[MenuesTableMap::COL_AVAILABILITY_AMOUNT] = true;
        }

        return $this;
    } // setAvailabilityAmount()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MenuesTableMap::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menuid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MenuesTableMap::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_groupid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MenuesTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MenuesTableMap::translateFieldName('Price', TableMap::TYPE_PHPNAME, $indexType)];
            $this->price = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MenuesTableMap::translateFieldName('Availabilityid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availabilityid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MenuesTableMap::translateFieldName('AvailabilityAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availability_amount = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = MenuesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Menues\\Menues'), 0, $e);
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
        if ($this->aMenuGroupes !== null && $this->menu_groupid !== $this->aMenuGroupes->getMenuGroupid()) {
            $this->aMenuGroupes = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MenuesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMenuesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAvailabilitys = null;
            $this->aMenuGroupes = null;
            $this->collMenuesPossibleExtrass = null;

            $this->collMenuesPossibleSizess = null;

            $this->collOrdersDetailss = null;

            $this->collOrdersDetailsMixedWiths = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Menues::setDeleted()
     * @see Menues::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMenuesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesTableMap::DATABASE_NAME);
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
                MenuesTableMap::addInstanceToPool($this);
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

            if ($this->menuesPossibleExtrassScheduledForDeletion !== null) {
                if (!$this->menuesPossibleExtrassScheduledForDeletion->isEmpty()) {
                    \Model\Menues\MenuesPossibleExtrasQuery::create()
                        ->filterByPrimaryKeys($this->menuesPossibleExtrassScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuesPossibleExtrassScheduledForDeletion = null;
                }
            }

            if ($this->collMenuesPossibleExtrass !== null) {
                foreach ($this->collMenuesPossibleExtrass as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuesPossibleSizessScheduledForDeletion !== null) {
                if (!$this->menuesPossibleSizessScheduledForDeletion->isEmpty()) {
                    \Model\Menues\MenuesPossibleSizesQuery::create()
                        ->filterByPrimaryKeys($this->menuesPossibleSizessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuesPossibleSizessScheduledForDeletion = null;
                }
            }

            if ($this->collMenuesPossibleSizess !== null) {
                foreach ($this->collMenuesPossibleSizess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersDetailssScheduledForDeletion !== null) {
                if (!$this->ordersDetailssScheduledForDeletion->isEmpty()) {
                    \Model\Ordering\OrdersDetailsQuery::create()
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

        $this->modifiedColumns[MenuesTableMap::COL_MENUID] = true;
        if (null !== $this->menuid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MenuesTableMap::COL_MENUID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MenuesTableMap::COL_MENUID)) {
            $modifiedColumns[':p' . $index++]  = 'menuid';
        }
        if ($this->isColumnModified(MenuesTableMap::COL_MENU_GROUPID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_groupid';
        }
        if ($this->isColumnModified(MenuesTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(MenuesTableMap::COL_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'price';
        }
        if ($this->isColumnModified(MenuesTableMap::COL_AVAILABILITYID)) {
            $modifiedColumns[':p' . $index++]  = 'availabilityid';
        }
        if ($this->isColumnModified(MenuesTableMap::COL_AVAILABILITY_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'availability_amount';
        }

        $sql = sprintf(
            'INSERT INTO menues (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'menuid':
                        $stmt->bindValue($identifier, $this->menuid, PDO::PARAM_INT);
                        break;
                    case 'menu_groupid':
                        $stmt->bindValue($identifier, $this->menu_groupid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'price':
                        $stmt->bindValue($identifier, $this->price, PDO::PARAM_STR);
                        break;
                    case 'availabilityid':
                        $stmt->bindValue($identifier, $this->availabilityid, PDO::PARAM_INT);
                        break;
                    case 'availability_amount':
                        $stmt->bindValue($identifier, $this->availability_amount, PDO::PARAM_INT);
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
        $this->setMenuid($pk);

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
        $pos = MenuesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMenuid();
                break;
            case 1:
                return $this->getMenuGroupid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getPrice();
                break;
            case 4:
                return $this->getAvailabilityid();
                break;
            case 5:
                return $this->getAvailabilityAmount();
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

        if (isset($alreadyDumpedObjects['Menues'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Menues'][$this->hashCode()] = true;
        $keys = MenuesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMenuid(),
            $keys[1] => $this->getMenuGroupid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getPrice(),
            $keys[4] => $this->getAvailabilityid(),
            $keys[5] => $this->getAvailabilityAmount(),
        );
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
            if (null !== $this->collMenuesPossibleExtrass) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuesPossibleExtrass';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menues_possible_extrass';
                        break;
                    default:
                        $key = 'MenuesPossibleExtrass';
                }

                $result[$key] = $this->collMenuesPossibleExtrass->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuesPossibleSizess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuesPossibleSizess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menues_possible_sizess';
                        break;
                    default:
                        $key = 'MenuesPossibleSizess';
                }

                $result[$key] = $this->collMenuesPossibleSizess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Model\Menues\Menues
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MenuesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Menues\Menues
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMenuid($value);
                break;
            case 1:
                $this->setMenuGroupid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setPrice($value);
                break;
            case 4:
                $this->setAvailabilityid($value);
                break;
            case 5:
                $this->setAvailabilityAmount($value);
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
        $keys = MenuesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMenuid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setMenuGroupid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPrice($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAvailabilityid($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAvailabilityAmount($arr[$keys[5]]);
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
     * @return $this|\Model\Menues\Menues The current object, for fluid interface
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
        $criteria = new Criteria(MenuesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MenuesTableMap::COL_MENUID)) {
            $criteria->add(MenuesTableMap::COL_MENUID, $this->menuid);
        }
        if ($this->isColumnModified(MenuesTableMap::COL_MENU_GROUPID)) {
            $criteria->add(MenuesTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        }
        if ($this->isColumnModified(MenuesTableMap::COL_NAME)) {
            $criteria->add(MenuesTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(MenuesTableMap::COL_PRICE)) {
            $criteria->add(MenuesTableMap::COL_PRICE, $this->price);
        }
        if ($this->isColumnModified(MenuesTableMap::COL_AVAILABILITYID)) {
            $criteria->add(MenuesTableMap::COL_AVAILABILITYID, $this->availabilityid);
        }
        if ($this->isColumnModified(MenuesTableMap::COL_AVAILABILITY_AMOUNT)) {
            $criteria->add(MenuesTableMap::COL_AVAILABILITY_AMOUNT, $this->availability_amount);
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
        $criteria = ChildMenuesQuery::create();
        $criteria->add(MenuesTableMap::COL_MENUID, $this->menuid);
        $criteria->add(MenuesTableMap::COL_MENU_GROUPID, $this->menu_groupid);

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
        $validPk = null !== $this->getMenuid() &&
            null !== $this->getMenuGroupid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_menues_menu_groupes1 to table menu_groupes
        if ($this->aMenuGroupes && $hash = spl_object_hash($this->aMenuGroupes)) {
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
        $pks[0] = $this->getMenuid();
        $pks[1] = $this->getMenuGroupid();

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
        $this->setMenuid($keys[0]);
        $this->setMenuGroupid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getMenuid()) && (null === $this->getMenuGroupid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Menues\Menues (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMenuGroupid($this->getMenuGroupid());
        $copyObj->setName($this->getName());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setAvailabilityid($this->getAvailabilityid());
        $copyObj->setAvailabilityAmount($this->getAvailabilityAmount());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMenuesPossibleExtrass() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuesPossibleExtras($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuesPossibleSizess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuesPossibleSizes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersDetailss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetails($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrdersDetailsMixedWiths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrdersDetailsMixedWith($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setMenuid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Model\Menues\Menues Clone of current object.
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
     * Declares an association between this object and a ChildAvailabilitys object.
     *
     * @param  ChildAvailabilitys $v
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAvailabilitys(ChildAvailabilitys $v = null)
    {
        if ($v === null) {
            $this->setAvailabilityid(NULL);
        } else {
            $this->setAvailabilityid($v->getAvailabilityid());
        }

        $this->aAvailabilitys = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAvailabilitys object, it will not be re-added.
        if ($v !== null) {
            $v->addMenues($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAvailabilitys object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAvailabilitys The associated ChildAvailabilitys object.
     * @throws PropelException
     */
    public function getAvailabilitys(ConnectionInterface $con = null)
    {
        if ($this->aAvailabilitys === null && ($this->availabilityid !== null)) {
            $this->aAvailabilitys = ChildAvailabilitysQuery::create()->findPk($this->availabilityid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAvailabilitys->addMenuess($this);
             */
        }

        return $this->aAvailabilitys;
    }

    /**
     * Declares an association between this object and a ChildMenuGroupes object.
     *
     * @param  ChildMenuGroupes $v
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuGroupes(ChildMenuGroupes $v = null)
    {
        if ($v === null) {
            $this->setMenuGroupid(NULL);
        } else {
            $this->setMenuGroupid($v->getMenuGroupid());
        }

        $this->aMenuGroupes = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMenuGroupes object, it will not be re-added.
        if ($v !== null) {
            $v->addMenues($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMenuGroupes object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMenuGroupes The associated ChildMenuGroupes object.
     * @throws PropelException
     */
    public function getMenuGroupes(ConnectionInterface $con = null)
    {
        if ($this->aMenuGroupes === null && ($this->menu_groupid !== null)) {
            $this->aMenuGroupes = ChildMenuGroupesQuery::create()
                ->filterByMenues($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuGroupes->addMenuess($this);
             */
        }

        return $this->aMenuGroupes;
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
        if ('MenuesPossibleExtras' == $relationName) {
            return $this->initMenuesPossibleExtrass();
        }
        if ('MenuesPossibleSizes' == $relationName) {
            return $this->initMenuesPossibleSizess();
        }
        if ('OrdersDetails' == $relationName) {
            return $this->initOrdersDetailss();
        }
        if ('OrdersDetailsMixedWith' == $relationName) {
            return $this->initOrdersDetailsMixedWiths();
        }
    }

    /**
     * Clears out the collMenuesPossibleExtrass collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuesPossibleExtrass()
     */
    public function clearMenuesPossibleExtrass()
    {
        $this->collMenuesPossibleExtrass = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuesPossibleExtrass collection loaded partially.
     */
    public function resetPartialMenuesPossibleExtrass($v = true)
    {
        $this->collMenuesPossibleExtrassPartial = $v;
    }

    /**
     * Initializes the collMenuesPossibleExtrass collection.
     *
     * By default this just sets the collMenuesPossibleExtrass collection to an empty array (like clearcollMenuesPossibleExtrass());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuesPossibleExtrass($overrideExisting = true)
    {
        if (null !== $this->collMenuesPossibleExtrass && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuesPossibleExtrasTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuesPossibleExtrass = new $collectionClassName;
        $this->collMenuesPossibleExtrass->setModel('\Model\Menues\MenuesPossibleExtras');
    }

    /**
     * Gets an array of ChildMenuesPossibleExtras objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenues is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenuesPossibleExtras[] List of ChildMenuesPossibleExtras objects
     * @throws PropelException
     */
    public function getMenuesPossibleExtrass(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuesPossibleExtrassPartial && !$this->isNew();
        if (null === $this->collMenuesPossibleExtrass || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuesPossibleExtrass) {
                // return empty collection
                $this->initMenuesPossibleExtrass();
            } else {
                $collMenuesPossibleExtrass = ChildMenuesPossibleExtrasQuery::create(null, $criteria)
                    ->filterByMenues($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuesPossibleExtrassPartial && count($collMenuesPossibleExtrass)) {
                        $this->initMenuesPossibleExtrass(false);

                        foreach ($collMenuesPossibleExtrass as $obj) {
                            if (false == $this->collMenuesPossibleExtrass->contains($obj)) {
                                $this->collMenuesPossibleExtrass->append($obj);
                            }
                        }

                        $this->collMenuesPossibleExtrassPartial = true;
                    }

                    return $collMenuesPossibleExtrass;
                }

                if ($partial && $this->collMenuesPossibleExtrass) {
                    foreach ($this->collMenuesPossibleExtrass as $obj) {
                        if ($obj->isNew()) {
                            $collMenuesPossibleExtrass[] = $obj;
                        }
                    }
                }

                $this->collMenuesPossibleExtrass = $collMenuesPossibleExtrass;
                $this->collMenuesPossibleExtrassPartial = false;
            }
        }

        return $this->collMenuesPossibleExtrass;
    }

    /**
     * Sets a collection of ChildMenuesPossibleExtras objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuesPossibleExtrass A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function setMenuesPossibleExtrass(Collection $menuesPossibleExtrass, ConnectionInterface $con = null)
    {
        /** @var ChildMenuesPossibleExtras[] $menuesPossibleExtrassToDelete */
        $menuesPossibleExtrassToDelete = $this->getMenuesPossibleExtrass(new Criteria(), $con)->diff($menuesPossibleExtrass);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuesPossibleExtrassScheduledForDeletion = clone $menuesPossibleExtrassToDelete;

        foreach ($menuesPossibleExtrassToDelete as $menuesPossibleExtrasRemoved) {
            $menuesPossibleExtrasRemoved->setMenues(null);
        }

        $this->collMenuesPossibleExtrass = null;
        foreach ($menuesPossibleExtrass as $menuesPossibleExtras) {
            $this->addMenuesPossibleExtras($menuesPossibleExtras);
        }

        $this->collMenuesPossibleExtrass = $menuesPossibleExtrass;
        $this->collMenuesPossibleExtrassPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MenuesPossibleExtras objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MenuesPossibleExtras objects.
     * @throws PropelException
     */
    public function countMenuesPossibleExtrass(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuesPossibleExtrassPartial && !$this->isNew();
        if (null === $this->collMenuesPossibleExtrass || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuesPossibleExtrass) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuesPossibleExtrass());
            }

            $query = ChildMenuesPossibleExtrasQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenues($this)
                ->count($con);
        }

        return count($this->collMenuesPossibleExtrass);
    }

    /**
     * Method called to associate a ChildMenuesPossibleExtras object to this object
     * through the ChildMenuesPossibleExtras foreign key attribute.
     *
     * @param  ChildMenuesPossibleExtras $l ChildMenuesPossibleExtras
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function addMenuesPossibleExtras(ChildMenuesPossibleExtras $l)
    {
        if ($this->collMenuesPossibleExtrass === null) {
            $this->initMenuesPossibleExtrass();
            $this->collMenuesPossibleExtrassPartial = true;
        }

        if (!$this->collMenuesPossibleExtrass->contains($l)) {
            $this->doAddMenuesPossibleExtras($l);

            if ($this->menuesPossibleExtrassScheduledForDeletion and $this->menuesPossibleExtrassScheduledForDeletion->contains($l)) {
                $this->menuesPossibleExtrassScheduledForDeletion->remove($this->menuesPossibleExtrassScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenuesPossibleExtras $menuesPossibleExtras The ChildMenuesPossibleExtras object to add.
     */
    protected function doAddMenuesPossibleExtras(ChildMenuesPossibleExtras $menuesPossibleExtras)
    {
        $this->collMenuesPossibleExtrass[]= $menuesPossibleExtras;
        $menuesPossibleExtras->setMenues($this);
    }

    /**
     * @param  ChildMenuesPossibleExtras $menuesPossibleExtras The ChildMenuesPossibleExtras object to remove.
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function removeMenuesPossibleExtras(ChildMenuesPossibleExtras $menuesPossibleExtras)
    {
        if ($this->getMenuesPossibleExtrass()->contains($menuesPossibleExtras)) {
            $pos = $this->collMenuesPossibleExtrass->search($menuesPossibleExtras);
            $this->collMenuesPossibleExtrass->remove($pos);
            if (null === $this->menuesPossibleExtrassScheduledForDeletion) {
                $this->menuesPossibleExtrassScheduledForDeletion = clone $this->collMenuesPossibleExtrass;
                $this->menuesPossibleExtrassScheduledForDeletion->clear();
            }
            $this->menuesPossibleExtrassScheduledForDeletion[]= clone $menuesPossibleExtras;
            $menuesPossibleExtras->setMenues(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related MenuesPossibleExtrass from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenuesPossibleExtras[] List of ChildMenuesPossibleExtras objects
     */
    public function getMenuesPossibleExtrassJoinMenuExtras(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuesPossibleExtrasQuery::create(null, $criteria);
        $query->joinWith('MenuExtras', $joinBehavior);

        return $this->getMenuesPossibleExtrass($query, $con);
    }

    /**
     * Clears out the collMenuesPossibleSizess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuesPossibleSizess()
     */
    public function clearMenuesPossibleSizess()
    {
        $this->collMenuesPossibleSizess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuesPossibleSizess collection loaded partially.
     */
    public function resetPartialMenuesPossibleSizess($v = true)
    {
        $this->collMenuesPossibleSizessPartial = $v;
    }

    /**
     * Initializes the collMenuesPossibleSizess collection.
     *
     * By default this just sets the collMenuesPossibleSizess collection to an empty array (like clearcollMenuesPossibleSizess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuesPossibleSizess($overrideExisting = true)
    {
        if (null !== $this->collMenuesPossibleSizess && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuesPossibleSizesTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuesPossibleSizess = new $collectionClassName;
        $this->collMenuesPossibleSizess->setModel('\Model\Menues\MenuesPossibleSizes');
    }

    /**
     * Gets an array of ChildMenuesPossibleSizes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenues is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenuesPossibleSizes[] List of ChildMenuesPossibleSizes objects
     * @throws PropelException
     */
    public function getMenuesPossibleSizess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuesPossibleSizessPartial && !$this->isNew();
        if (null === $this->collMenuesPossibleSizess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuesPossibleSizess) {
                // return empty collection
                $this->initMenuesPossibleSizess();
            } else {
                $collMenuesPossibleSizess = ChildMenuesPossibleSizesQuery::create(null, $criteria)
                    ->filterByMenues($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuesPossibleSizessPartial && count($collMenuesPossibleSizess)) {
                        $this->initMenuesPossibleSizess(false);

                        foreach ($collMenuesPossibleSizess as $obj) {
                            if (false == $this->collMenuesPossibleSizess->contains($obj)) {
                                $this->collMenuesPossibleSizess->append($obj);
                            }
                        }

                        $this->collMenuesPossibleSizessPartial = true;
                    }

                    return $collMenuesPossibleSizess;
                }

                if ($partial && $this->collMenuesPossibleSizess) {
                    foreach ($this->collMenuesPossibleSizess as $obj) {
                        if ($obj->isNew()) {
                            $collMenuesPossibleSizess[] = $obj;
                        }
                    }
                }

                $this->collMenuesPossibleSizess = $collMenuesPossibleSizess;
                $this->collMenuesPossibleSizessPartial = false;
            }
        }

        return $this->collMenuesPossibleSizess;
    }

    /**
     * Sets a collection of ChildMenuesPossibleSizes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuesPossibleSizess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function setMenuesPossibleSizess(Collection $menuesPossibleSizess, ConnectionInterface $con = null)
    {
        /** @var ChildMenuesPossibleSizes[] $menuesPossibleSizessToDelete */
        $menuesPossibleSizessToDelete = $this->getMenuesPossibleSizess(new Criteria(), $con)->diff($menuesPossibleSizess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuesPossibleSizessScheduledForDeletion = clone $menuesPossibleSizessToDelete;

        foreach ($menuesPossibleSizessToDelete as $menuesPossibleSizesRemoved) {
            $menuesPossibleSizesRemoved->setMenues(null);
        }

        $this->collMenuesPossibleSizess = null;
        foreach ($menuesPossibleSizess as $menuesPossibleSizes) {
            $this->addMenuesPossibleSizes($menuesPossibleSizes);
        }

        $this->collMenuesPossibleSizess = $menuesPossibleSizess;
        $this->collMenuesPossibleSizessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MenuesPossibleSizes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MenuesPossibleSizes objects.
     * @throws PropelException
     */
    public function countMenuesPossibleSizess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuesPossibleSizessPartial && !$this->isNew();
        if (null === $this->collMenuesPossibleSizess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuesPossibleSizess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuesPossibleSizess());
            }

            $query = ChildMenuesPossibleSizesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenues($this)
                ->count($con);
        }

        return count($this->collMenuesPossibleSizess);
    }

    /**
     * Method called to associate a ChildMenuesPossibleSizes object to this object
     * through the ChildMenuesPossibleSizes foreign key attribute.
     *
     * @param  ChildMenuesPossibleSizes $l ChildMenuesPossibleSizes
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function addMenuesPossibleSizes(ChildMenuesPossibleSizes $l)
    {
        if ($this->collMenuesPossibleSizess === null) {
            $this->initMenuesPossibleSizess();
            $this->collMenuesPossibleSizessPartial = true;
        }

        if (!$this->collMenuesPossibleSizess->contains($l)) {
            $this->doAddMenuesPossibleSizes($l);

            if ($this->menuesPossibleSizessScheduledForDeletion and $this->menuesPossibleSizessScheduledForDeletion->contains($l)) {
                $this->menuesPossibleSizessScheduledForDeletion->remove($this->menuesPossibleSizessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenuesPossibleSizes $menuesPossibleSizes The ChildMenuesPossibleSizes object to add.
     */
    protected function doAddMenuesPossibleSizes(ChildMenuesPossibleSizes $menuesPossibleSizes)
    {
        $this->collMenuesPossibleSizess[]= $menuesPossibleSizes;
        $menuesPossibleSizes->setMenues($this);
    }

    /**
     * @param  ChildMenuesPossibleSizes $menuesPossibleSizes The ChildMenuesPossibleSizes object to remove.
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function removeMenuesPossibleSizes(ChildMenuesPossibleSizes $menuesPossibleSizes)
    {
        if ($this->getMenuesPossibleSizess()->contains($menuesPossibleSizes)) {
            $pos = $this->collMenuesPossibleSizess->search($menuesPossibleSizes);
            $this->collMenuesPossibleSizess->remove($pos);
            if (null === $this->menuesPossibleSizessScheduledForDeletion) {
                $this->menuesPossibleSizessScheduledForDeletion = clone $this->collMenuesPossibleSizess;
                $this->menuesPossibleSizessScheduledForDeletion->clear();
            }
            $this->menuesPossibleSizessScheduledForDeletion[]= clone $menuesPossibleSizes;
            $menuesPossibleSizes->setMenues(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related MenuesPossibleSizess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenuesPossibleSizes[] List of ChildMenuesPossibleSizes objects
     */
    public function getMenuesPossibleSizessJoinMenuSizes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuesPossibleSizesQuery::create(null, $criteria);
        $query->joinWith('MenuSizes', $joinBehavior);

        return $this->getMenuesPossibleSizess($query, $con);
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
        $this->collOrdersDetailss->setModel('\Model\Ordering\OrdersDetails');
    }

    /**
     * Gets an array of OrdersDetails objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenues is new, it will return
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
                    ->filterByMenues($this)
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
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function setOrdersDetailss(Collection $ordersDetailss, ConnectionInterface $con = null)
    {
        /** @var OrdersDetails[] $ordersDetailssToDelete */
        $ordersDetailssToDelete = $this->getOrdersDetailss(new Criteria(), $con)->diff($ordersDetailss);


        $this->ordersDetailssScheduledForDeletion = $ordersDetailssToDelete;

        foreach ($ordersDetailssToDelete as $ordersDetailsRemoved) {
            $ordersDetailsRemoved->setMenues(null);
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
                ->filterByMenues($this)
                ->count($con);
        }

        return count($this->collOrdersDetailss);
    }

    /**
     * Method called to associate a OrdersDetails object to this object
     * through the OrdersDetails foreign key attribute.
     *
     * @param  OrdersDetails $l OrdersDetails
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
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
        $ordersDetails->setMenues($this);
    }

    /**
     * @param  OrdersDetails $ordersDetails The OrdersDetails object to remove.
     * @return $this|ChildMenues The current object (for fluent API support)
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
            $ordersDetails->setMenues(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
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
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
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
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
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
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetails[] List of OrdersDetails objects
     */
    public function getOrdersDetailssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getOrdersDetailss($query, $con);
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
     * Gets an array of OrdersDetailsMixedWith objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenues is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrdersDetailsMixedWith[] List of OrdersDetailsMixedWith objects
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
                $collOrdersDetailsMixedWiths = OrdersDetailsMixedWithQuery::create(null, $criteria)
                    ->filterByMenues($this)
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
     * Sets a collection of OrdersDetailsMixedWith objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $ordersDetailsMixedWiths A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function setOrdersDetailsMixedWiths(Collection $ordersDetailsMixedWiths, ConnectionInterface $con = null)
    {
        /** @var OrdersDetailsMixedWith[] $ordersDetailsMixedWithsToDelete */
        $ordersDetailsMixedWithsToDelete = $this->getOrdersDetailsMixedWiths(new Criteria(), $con)->diff($ordersDetailsMixedWiths);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->ordersDetailsMixedWithsScheduledForDeletion = clone $ordersDetailsMixedWithsToDelete;

        foreach ($ordersDetailsMixedWithsToDelete as $ordersDetailsMixedWithRemoved) {
            $ordersDetailsMixedWithRemoved->setMenues(null);
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
     * Returns the number of related BaseOrdersDetailsMixedWith objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrdersDetailsMixedWith objects.
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

            $query = OrdersDetailsMixedWithQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenues($this)
                ->count($con);
        }

        return count($this->collOrdersDetailsMixedWiths);
    }

    /**
     * Method called to associate a OrdersDetailsMixedWith object to this object
     * through the OrdersDetailsMixedWith foreign key attribute.
     *
     * @param  OrdersDetailsMixedWith $l OrdersDetailsMixedWith
     * @return $this|\Model\Menues\Menues The current object (for fluent API support)
     */
    public function addOrdersDetailsMixedWith(OrdersDetailsMixedWith $l)
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
     * @param OrdersDetailsMixedWith $ordersDetailsMixedWith The OrdersDetailsMixedWith object to add.
     */
    protected function doAddOrdersDetailsMixedWith(OrdersDetailsMixedWith $ordersDetailsMixedWith)
    {
        $this->collOrdersDetailsMixedWiths[]= $ordersDetailsMixedWith;
        $ordersDetailsMixedWith->setMenues($this);
    }

    /**
     * @param  OrdersDetailsMixedWith $ordersDetailsMixedWith The OrdersDetailsMixedWith object to remove.
     * @return $this|ChildMenues The current object (for fluent API support)
     */
    public function removeOrdersDetailsMixedWith(OrdersDetailsMixedWith $ordersDetailsMixedWith)
    {
        if ($this->getOrdersDetailsMixedWiths()->contains($ordersDetailsMixedWith)) {
            $pos = $this->collOrdersDetailsMixedWiths->search($ordersDetailsMixedWith);
            $this->collOrdersDetailsMixedWiths->remove($pos);
            if (null === $this->ordersDetailsMixedWithsScheduledForDeletion) {
                $this->ordersDetailsMixedWithsScheduledForDeletion = clone $this->collOrdersDetailsMixedWiths;
                $this->ordersDetailsMixedWithsScheduledForDeletion->clear();
            }
            $this->ordersDetailsMixedWithsScheduledForDeletion[]= clone $ordersDetailsMixedWith;
            $ordersDetailsMixedWith->setMenues(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Menues is new, it will return
     * an empty collection; or if this Menues has previously
     * been saved, it will retrieve related OrdersDetailsMixedWiths from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Menues.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersDetailsMixedWith[] List of OrdersDetailsMixedWith objects
     */
    public function getOrdersDetailsMixedWithsJoinOrdersDetails(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersDetailsMixedWithQuery::create(null, $criteria);
        $query->joinWith('OrdersDetails', $joinBehavior);

        return $this->getOrdersDetailsMixedWiths($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAvailabilitys) {
            $this->aAvailabilitys->removeMenues($this);
        }
        if (null !== $this->aMenuGroupes) {
            $this->aMenuGroupes->removeMenues($this);
        }
        $this->menuid = null;
        $this->menu_groupid = null;
        $this->name = null;
        $this->price = null;
        $this->availabilityid = null;
        $this->availability_amount = null;
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
            if ($this->collMenuesPossibleExtrass) {
                foreach ($this->collMenuesPossibleExtrass as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuesPossibleSizess) {
                foreach ($this->collMenuesPossibleSizess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersDetailss) {
                foreach ($this->collOrdersDetailss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrdersDetailsMixedWiths) {
                foreach ($this->collOrdersDetailsMixedWiths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMenuesPossibleExtrass = null;
        $this->collMenuesPossibleSizess = null;
        $this->collOrdersDetailss = null;
        $this->collOrdersDetailsMixedWiths = null;
        $this->aAvailabilitys = null;
        $this->aMenuGroupes = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MenuesTableMap::DEFAULT_STRING_FORMAT);
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
