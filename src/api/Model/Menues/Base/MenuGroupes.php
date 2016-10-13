<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\DistributionPlace\DistributionsPlacesGroupes;
use Model\DistributionPlace\DistributionsPlacesGroupesQuery;
use Model\DistributionPlace\DistributionsPlacesTables;
use Model\DistributionPlace\DistributionsPlacesTablesQuery;
use Model\DistributionPlace\Base\DistributionsPlacesGroupes as BaseDistributionsPlacesGroupes;
use Model\DistributionPlace\Base\DistributionsPlacesTables as BaseDistributionsPlacesTables;
use Model\DistributionPlace\Map\DistributionsPlacesGroupesTableMap;
use Model\DistributionPlace\Map\DistributionsPlacesTablesTableMap;
use Model\Menues\MenuGroupes as ChildMenuGroupes;
use Model\Menues\MenuGroupesQuery as ChildMenuGroupesQuery;
use Model\Menues\MenuTypes as ChildMenuTypes;
use Model\Menues\MenuTypesQuery as ChildMenuTypesQuery;
use Model\Menues\Menues as ChildMenues;
use Model\Menues\MenuesQuery as ChildMenuesQuery;
use Model\Menues\Map\MenuGroupesTableMap;
use Model\Menues\Map\MenuesTableMap;
use Model\OIP\OrdersInProgress;
use Model\OIP\OrdersInProgressQuery;
use Model\OIP\Base\OrdersInProgress as BaseOrdersInProgress;
use Model\OIP\Map\OrdersInProgressTableMap;
use Model\Ordering\OrdersDetails;
use Model\Ordering\OrdersDetailsQuery;
use Model\Ordering\Base\OrdersDetails as BaseOrdersDetails;
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
 * Base class that represents a row from the 'menu_groupes' table.
 *
 *
 *
 * @package    propel.generator.Model.Menues.Base
 */
abstract class MenuGroupes implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Menues\\Map\\MenuGroupesTableMap';


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
     * The value for the menu_groupid field.
     *
     * @var        int
     */
    protected $menu_groupid;

    /**
     * The value for the menu_typeid field.
     *
     * @var        int
     */
    protected $menu_typeid;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * @var        ChildMenuTypes
     */
    protected $aMenuTypes;

    /**
     * @var        ObjectCollection|DistributionsPlacesGroupes[] Collection to store aggregation of DistributionsPlacesGroupes objects.
     */
    protected $collDistributionsPlacesGroupess;
    protected $collDistributionsPlacesGroupessPartial;

    /**
     * @var        ObjectCollection|DistributionsPlacesTables[] Collection to store aggregation of DistributionsPlacesTables objects.
     */
    protected $collDistributionsPlacesTabless;
    protected $collDistributionsPlacesTablessPartial;

    /**
     * @var        ObjectCollection|ChildMenues[] Collection to store aggregation of ChildMenues objects.
     */
    protected $collMenuess;
    protected $collMenuessPartial;

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
     * @var ObjectCollection|DistributionsPlacesGroupes[]
     */
    protected $distributionsPlacesGroupessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionsPlacesTables[]
     */
    protected $distributionsPlacesTablessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenues[]
     */
    protected $menuessScheduledForDeletion = null;

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
     * Initializes internal state of Model\Menues\Base\MenuGroupes object.
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
     * Compares this with another <code>MenuGroupes</code> instance.  If
     * <code>obj</code> is an instance of <code>MenuGroupes</code>, delegates to
     * <code>equals(MenuGroupes)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|MenuGroupes The current object, for fluid interface
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
     * Get the [menu_groupid] column value.
     *
     * @return int
     */
    public function getMenuGroupid()
    {
        return $this->menu_groupid;
    }

    /**
     * Get the [menu_typeid] column value.
     *
     * @return int
     */
    public function getMenuTypeid()
    {
        return $this->menu_typeid;
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
     * Set the value of [menu_groupid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     */
    public function setMenuGroupid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_groupid !== $v) {
            $this->menu_groupid = $v;
            $this->modifiedColumns[MenuGroupesTableMap::COL_MENU_GROUPID] = true;
        }

        return $this;
    } // setMenuGroupid()

    /**
     * Set the value of [menu_typeid] column.
     *
     * @param int $v new value
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     */
    public function setMenuTypeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_typeid !== $v) {
            $this->menu_typeid = $v;
            $this->modifiedColumns[MenuGroupesTableMap::COL_MENU_TYPEID] = true;
        }

        if ($this->aMenuTypes !== null && $this->aMenuTypes->getMenuTypeid() !== $v) {
            $this->aMenuTypes = null;
        }

        return $this;
    } // setMenuTypeid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MenuGroupesTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MenuGroupesTableMap::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_groupid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MenuGroupesTableMap::translateFieldName('MenuTypeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_typeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MenuGroupesTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = MenuGroupesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Menues\\MenuGroupes'), 0, $e);
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
        if ($this->aMenuTypes !== null && $this->menu_typeid !== $this->aMenuTypes->getMenuTypeid()) {
            $this->aMenuTypes = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MenuGroupesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMenuGroupesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMenuTypes = null;
            $this->collDistributionsPlacesGroupess = null;

            $this->collDistributionsPlacesTabless = null;

            $this->collMenuess = null;

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
     * @see MenuGroupes::setDeleted()
     * @see MenuGroupes::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuGroupesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMenuGroupesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuGroupesTableMap::DATABASE_NAME);
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
                MenuGroupesTableMap::addInstanceToPool($this);
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

            if ($this->aMenuTypes !== null) {
                if ($this->aMenuTypes->isModified() || $this->aMenuTypes->isNew()) {
                    $affectedRows += $this->aMenuTypes->save($con);
                }
                $this->setMenuTypes($this->aMenuTypes);
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

            if ($this->distributionsPlacesGroupessScheduledForDeletion !== null) {
                if (!$this->distributionsPlacesGroupessScheduledForDeletion->isEmpty()) {
                    \Model\DistributionPlace\DistributionsPlacesGroupesQuery::create()
                        ->filterByPrimaryKeys($this->distributionsPlacesGroupessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionsPlacesGroupessScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionsPlacesGroupess !== null) {
                foreach ($this->collDistributionsPlacesGroupess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->distributionsPlacesTablessScheduledForDeletion !== null) {
                if (!$this->distributionsPlacesTablessScheduledForDeletion->isEmpty()) {
                    \Model\DistributionPlace\DistributionsPlacesTablesQuery::create()
                        ->filterByPrimaryKeys($this->distributionsPlacesTablessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionsPlacesTablessScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionsPlacesTabless !== null) {
                foreach ($this->collDistributionsPlacesTabless as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menuessScheduledForDeletion !== null) {
                if (!$this->menuessScheduledForDeletion->isEmpty()) {
                    \Model\Menues\MenuesQuery::create()
                        ->filterByPrimaryKeys($this->menuessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuessScheduledForDeletion = null;
                }
            }

            if ($this->collMenuess !== null) {
                foreach ($this->collMenuess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersDetailssScheduledForDeletion !== null) {
                if (!$this->ordersDetailssScheduledForDeletion->isEmpty()) {
                    foreach ($this->ordersDetailssScheduledForDeletion as $ordersDetails) {
                        // need to save related object because we set the relation to null
                        $ordersDetails->save($con);
                    }
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
                    \Model\OIP\OrdersInProgressQuery::create()
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

        $this->modifiedColumns[MenuGroupesTableMap::COL_MENU_GROUPID] = true;
        if (null !== $this->menu_groupid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MenuGroupesTableMap::COL_MENU_GROUPID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MenuGroupesTableMap::COL_MENU_GROUPID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_groupid';
        }
        if ($this->isColumnModified(MenuGroupesTableMap::COL_MENU_TYPEID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_typeid';
        }
        if ($this->isColumnModified(MenuGroupesTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO menu_groupes (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'menu_groupid':
                        $stmt->bindValue($identifier, $this->menu_groupid, PDO::PARAM_INT);
                        break;
                    case 'menu_typeid':
                        $stmt->bindValue($identifier, $this->menu_typeid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
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
        $this->setMenuGroupid($pk);

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
        $pos = MenuGroupesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMenuGroupid();
                break;
            case 1:
                return $this->getMenuTypeid();
                break;
            case 2:
                return $this->getName();
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

        if (isset($alreadyDumpedObjects['MenuGroupes'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MenuGroupes'][$this->hashCode()] = true;
        $keys = MenuGroupesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMenuGroupid(),
            $keys[1] => $this->getMenuTypeid(),
            $keys[2] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aMenuTypes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuTypes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_types';
                        break;
                    default:
                        $key = 'MenuTypes';
                }

                $result[$key] = $this->aMenuTypes->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDistributionsPlacesGroupess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionsPlacesGroupess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distributions_places_groupess';
                        break;
                    default:
                        $key = 'DistributionsPlacesGroupess';
                }

                $result[$key] = $this->collDistributionsPlacesGroupess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDistributionsPlacesTabless) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionsPlacesTabless';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distributions_places_tabless';
                        break;
                    default:
                        $key = 'DistributionsPlacesTabless';
                }

                $result[$key] = $this->collDistributionsPlacesTabless->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenuess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menuess';
                        break;
                    default:
                        $key = 'Menuess';
                }

                $result[$key] = $this->collMenuess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Model\Menues\MenuGroupes
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MenuGroupesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Menues\MenuGroupes
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMenuGroupid($value);
                break;
            case 1:
                $this->setMenuTypeid($value);
                break;
            case 2:
                $this->setName($value);
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
        $keys = MenuGroupesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMenuGroupid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setMenuTypeid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
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
     * @return $this|\Model\Menues\MenuGroupes The current object, for fluid interface
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
        $criteria = new Criteria(MenuGroupesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MenuGroupesTableMap::COL_MENU_GROUPID)) {
            $criteria->add(MenuGroupesTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        }
        if ($this->isColumnModified(MenuGroupesTableMap::COL_MENU_TYPEID)) {
            $criteria->add(MenuGroupesTableMap::COL_MENU_TYPEID, $this->menu_typeid);
        }
        if ($this->isColumnModified(MenuGroupesTableMap::COL_NAME)) {
            $criteria->add(MenuGroupesTableMap::COL_NAME, $this->name);
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
        $criteria = ChildMenuGroupesQuery::create();
        $criteria->add(MenuGroupesTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        $criteria->add(MenuGroupesTableMap::COL_MENU_TYPEID, $this->menu_typeid);

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
        $validPk = null !== $this->getMenuGroupid() &&
            null !== $this->getMenuTypeid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_menu_groupes_menu_types1 to table menu_types
        if ($this->aMenuTypes && $hash = spl_object_hash($this->aMenuTypes)) {
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
        $pks[0] = $this->getMenuGroupid();
        $pks[1] = $this->getMenuTypeid();

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
        $this->setMenuGroupid($keys[0]);
        $this->setMenuTypeid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getMenuGroupid()) && (null === $this->getMenuTypeid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Menues\MenuGroupes (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMenuTypeid($this->getMenuTypeid());
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getDistributionsPlacesGroupess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionsPlacesGroupes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDistributionsPlacesTabless() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionsPlacesTables($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenuess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenues($relObj->copy($deepCopy));
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
            $copyObj->setMenuGroupid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Model\Menues\MenuGroupes Clone of current object.
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
     * Declares an association between this object and a ChildMenuTypes object.
     *
     * @param  ChildMenuTypes $v
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuTypes(ChildMenuTypes $v = null)
    {
        if ($v === null) {
            $this->setMenuTypeid(NULL);
        } else {
            $this->setMenuTypeid($v->getMenuTypeid());
        }

        $this->aMenuTypes = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMenuTypes object, it will not be re-added.
        if ($v !== null) {
            $v->addMenuGroupes($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMenuTypes object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMenuTypes The associated ChildMenuTypes object.
     * @throws PropelException
     */
    public function getMenuTypes(ConnectionInterface $con = null)
    {
        if ($this->aMenuTypes === null && ($this->menu_typeid !== null)) {
            $this->aMenuTypes = ChildMenuTypesQuery::create()
                ->filterByMenuGroupes($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuTypes->addMenuGroupess($this);
             */
        }

        return $this->aMenuTypes;
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
        if ('DistributionsPlacesGroupes' == $relationName) {
            return $this->initDistributionsPlacesGroupess();
        }
        if ('DistributionsPlacesTables' == $relationName) {
            return $this->initDistributionsPlacesTabless();
        }
        if ('Menues' == $relationName) {
            return $this->initMenuess();
        }
        if ('OrdersDetails' == $relationName) {
            return $this->initOrdersDetailss();
        }
        if ('OrdersInProgress' == $relationName) {
            return $this->initOrdersInProgresses();
        }
    }

    /**
     * Clears out the collDistributionsPlacesGroupess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionsPlacesGroupess()
     */
    public function clearDistributionsPlacesGroupess()
    {
        $this->collDistributionsPlacesGroupess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionsPlacesGroupess collection loaded partially.
     */
    public function resetPartialDistributionsPlacesGroupess($v = true)
    {
        $this->collDistributionsPlacesGroupessPartial = $v;
    }

    /**
     * Initializes the collDistributionsPlacesGroupess collection.
     *
     * By default this just sets the collDistributionsPlacesGroupess collection to an empty array (like clearcollDistributionsPlacesGroupess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionsPlacesGroupess($overrideExisting = true)
    {
        if (null !== $this->collDistributionsPlacesGroupess && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionsPlacesGroupesTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionsPlacesGroupess = new $collectionClassName;
        $this->collDistributionsPlacesGroupess->setModel('\Model\DistributionPlace\DistributionsPlacesGroupes');
    }

    /**
     * Gets an array of DistributionsPlacesGroupes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroupes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionsPlacesGroupes[] List of DistributionsPlacesGroupes objects
     * @throws PropelException
     */
    public function getDistributionsPlacesGroupess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesGroupessPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesGroupess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesGroupess) {
                // return empty collection
                $this->initDistributionsPlacesGroupess();
            } else {
                $collDistributionsPlacesGroupess = DistributionsPlacesGroupesQuery::create(null, $criteria)
                    ->filterByMenuGroupes($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionsPlacesGroupessPartial && count($collDistributionsPlacesGroupess)) {
                        $this->initDistributionsPlacesGroupess(false);

                        foreach ($collDistributionsPlacesGroupess as $obj) {
                            if (false == $this->collDistributionsPlacesGroupess->contains($obj)) {
                                $this->collDistributionsPlacesGroupess->append($obj);
                            }
                        }

                        $this->collDistributionsPlacesGroupessPartial = true;
                    }

                    return $collDistributionsPlacesGroupess;
                }

                if ($partial && $this->collDistributionsPlacesGroupess) {
                    foreach ($this->collDistributionsPlacesGroupess as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionsPlacesGroupess[] = $obj;
                        }
                    }
                }

                $this->collDistributionsPlacesGroupess = $collDistributionsPlacesGroupess;
                $this->collDistributionsPlacesGroupessPartial = false;
            }
        }

        return $this->collDistributionsPlacesGroupess;
    }

    /**
     * Sets a collection of DistributionsPlacesGroupes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesGroupess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function setDistributionsPlacesGroupess(Collection $distributionsPlacesGroupess, ConnectionInterface $con = null)
    {
        /** @var DistributionsPlacesGroupes[] $distributionsPlacesGroupessToDelete */
        $distributionsPlacesGroupessToDelete = $this->getDistributionsPlacesGroupess(new Criteria(), $con)->diff($distributionsPlacesGroupess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesGroupessScheduledForDeletion = clone $distributionsPlacesGroupessToDelete;

        foreach ($distributionsPlacesGroupessToDelete as $distributionsPlacesGroupesRemoved) {
            $distributionsPlacesGroupesRemoved->setMenuGroupes(null);
        }

        $this->collDistributionsPlacesGroupess = null;
        foreach ($distributionsPlacesGroupess as $distributionsPlacesGroupes) {
            $this->addDistributionsPlacesGroupes($distributionsPlacesGroupes);
        }

        $this->collDistributionsPlacesGroupess = $distributionsPlacesGroupess;
        $this->collDistributionsPlacesGroupessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionsPlacesGroupes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionsPlacesGroupes objects.
     * @throws PropelException
     */
    public function countDistributionsPlacesGroupess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesGroupessPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesGroupess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesGroupess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionsPlacesGroupess());
            }

            $query = DistributionsPlacesGroupesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuGroupes($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesGroupess);
    }

    /**
     * Method called to associate a DistributionsPlacesGroupes object to this object
     * through the DistributionsPlacesGroupes foreign key attribute.
     *
     * @param  DistributionsPlacesGroupes $l DistributionsPlacesGroupes
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     */
    public function addDistributionsPlacesGroupes(DistributionsPlacesGroupes $l)
    {
        if ($this->collDistributionsPlacesGroupess === null) {
            $this->initDistributionsPlacesGroupess();
            $this->collDistributionsPlacesGroupessPartial = true;
        }

        if (!$this->collDistributionsPlacesGroupess->contains($l)) {
            $this->doAddDistributionsPlacesGroupes($l);

            if ($this->distributionsPlacesGroupessScheduledForDeletion and $this->distributionsPlacesGroupessScheduledForDeletion->contains($l)) {
                $this->distributionsPlacesGroupessScheduledForDeletion->remove($this->distributionsPlacesGroupessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionsPlacesGroupes $distributionsPlacesGroupes The DistributionsPlacesGroupes object to add.
     */
    protected function doAddDistributionsPlacesGroupes(DistributionsPlacesGroupes $distributionsPlacesGroupes)
    {
        $this->collDistributionsPlacesGroupess[]= $distributionsPlacesGroupes;
        $distributionsPlacesGroupes->setMenuGroupes($this);
    }

    /**
     * @param  DistributionsPlacesGroupes $distributionsPlacesGroupes The DistributionsPlacesGroupes object to remove.
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function removeDistributionsPlacesGroupes(DistributionsPlacesGroupes $distributionsPlacesGroupes)
    {
        if ($this->getDistributionsPlacesGroupess()->contains($distributionsPlacesGroupes)) {
            $pos = $this->collDistributionsPlacesGroupess->search($distributionsPlacesGroupes);
            $this->collDistributionsPlacesGroupess->remove($pos);
            if (null === $this->distributionsPlacesGroupessScheduledForDeletion) {
                $this->distributionsPlacesGroupessScheduledForDeletion = clone $this->collDistributionsPlacesGroupess;
                $this->distributionsPlacesGroupessScheduledForDeletion->clear();
            }
            $this->distributionsPlacesGroupessScheduledForDeletion[]= clone $distributionsPlacesGroupes;
            $distributionsPlacesGroupes->setMenuGroupes(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related DistributionsPlacesGroupess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesGroupes[] List of DistributionsPlacesGroupes objects
     */
    public function getDistributionsPlacesGroupessJoinDistributionsPlaces(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesGroupesQuery::create(null, $criteria);
        $query->joinWith('DistributionsPlaces', $joinBehavior);

        return $this->getDistributionsPlacesGroupess($query, $con);
    }

    /**
     * Clears out the collDistributionsPlacesTabless collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionsPlacesTabless()
     */
    public function clearDistributionsPlacesTabless()
    {
        $this->collDistributionsPlacesTabless = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionsPlacesTabless collection loaded partially.
     */
    public function resetPartialDistributionsPlacesTabless($v = true)
    {
        $this->collDistributionsPlacesTablessPartial = $v;
    }

    /**
     * Initializes the collDistributionsPlacesTabless collection.
     *
     * By default this just sets the collDistributionsPlacesTabless collection to an empty array (like clearcollDistributionsPlacesTabless());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionsPlacesTabless($overrideExisting = true)
    {
        if (null !== $this->collDistributionsPlacesTabless && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionsPlacesTablesTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionsPlacesTabless = new $collectionClassName;
        $this->collDistributionsPlacesTabless->setModel('\Model\DistributionPlace\DistributionsPlacesTables');
    }

    /**
     * Gets an array of DistributionsPlacesTables objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroupes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionsPlacesTables[] List of DistributionsPlacesTables objects
     * @throws PropelException
     */
    public function getDistributionsPlacesTabless(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesTablessPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesTabless || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesTabless) {
                // return empty collection
                $this->initDistributionsPlacesTabless();
            } else {
                $collDistributionsPlacesTabless = DistributionsPlacesTablesQuery::create(null, $criteria)
                    ->filterByMenuGroupes($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionsPlacesTablessPartial && count($collDistributionsPlacesTabless)) {
                        $this->initDistributionsPlacesTabless(false);

                        foreach ($collDistributionsPlacesTabless as $obj) {
                            if (false == $this->collDistributionsPlacesTabless->contains($obj)) {
                                $this->collDistributionsPlacesTabless->append($obj);
                            }
                        }

                        $this->collDistributionsPlacesTablessPartial = true;
                    }

                    return $collDistributionsPlacesTabless;
                }

                if ($partial && $this->collDistributionsPlacesTabless) {
                    foreach ($this->collDistributionsPlacesTabless as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionsPlacesTabless[] = $obj;
                        }
                    }
                }

                $this->collDistributionsPlacesTabless = $collDistributionsPlacesTabless;
                $this->collDistributionsPlacesTablessPartial = false;
            }
        }

        return $this->collDistributionsPlacesTabless;
    }

    /**
     * Sets a collection of DistributionsPlacesTables objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesTabless A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function setDistributionsPlacesTabless(Collection $distributionsPlacesTabless, ConnectionInterface $con = null)
    {
        /** @var DistributionsPlacesTables[] $distributionsPlacesTablessToDelete */
        $distributionsPlacesTablessToDelete = $this->getDistributionsPlacesTabless(new Criteria(), $con)->diff($distributionsPlacesTabless);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesTablessScheduledForDeletion = clone $distributionsPlacesTablessToDelete;

        foreach ($distributionsPlacesTablessToDelete as $distributionsPlacesTablesRemoved) {
            $distributionsPlacesTablesRemoved->setMenuGroupes(null);
        }

        $this->collDistributionsPlacesTabless = null;
        foreach ($distributionsPlacesTabless as $distributionsPlacesTables) {
            $this->addDistributionsPlacesTables($distributionsPlacesTables);
        }

        $this->collDistributionsPlacesTabless = $distributionsPlacesTabless;
        $this->collDistributionsPlacesTablessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionsPlacesTables objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionsPlacesTables objects.
     * @throws PropelException
     */
    public function countDistributionsPlacesTabless(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionsPlacesTablessPartial && !$this->isNew();
        if (null === $this->collDistributionsPlacesTabless || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionsPlacesTabless) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionsPlacesTabless());
            }

            $query = DistributionsPlacesTablesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuGroupes($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesTabless);
    }

    /**
     * Method called to associate a DistributionsPlacesTables object to this object
     * through the DistributionsPlacesTables foreign key attribute.
     *
     * @param  DistributionsPlacesTables $l DistributionsPlacesTables
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     */
    public function addDistributionsPlacesTables(DistributionsPlacesTables $l)
    {
        if ($this->collDistributionsPlacesTabless === null) {
            $this->initDistributionsPlacesTabless();
            $this->collDistributionsPlacesTablessPartial = true;
        }

        if (!$this->collDistributionsPlacesTabless->contains($l)) {
            $this->doAddDistributionsPlacesTables($l);

            if ($this->distributionsPlacesTablessScheduledForDeletion and $this->distributionsPlacesTablessScheduledForDeletion->contains($l)) {
                $this->distributionsPlacesTablessScheduledForDeletion->remove($this->distributionsPlacesTablessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionsPlacesTables $distributionsPlacesTables The DistributionsPlacesTables object to add.
     */
    protected function doAddDistributionsPlacesTables(DistributionsPlacesTables $distributionsPlacesTables)
    {
        $this->collDistributionsPlacesTabless[]= $distributionsPlacesTables;
        $distributionsPlacesTables->setMenuGroupes($this);
    }

    /**
     * @param  DistributionsPlacesTables $distributionsPlacesTables The DistributionsPlacesTables object to remove.
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function removeDistributionsPlacesTables(DistributionsPlacesTables $distributionsPlacesTables)
    {
        if ($this->getDistributionsPlacesTabless()->contains($distributionsPlacesTables)) {
            $pos = $this->collDistributionsPlacesTabless->search($distributionsPlacesTables);
            $this->collDistributionsPlacesTabless->remove($pos);
            if (null === $this->distributionsPlacesTablessScheduledForDeletion) {
                $this->distributionsPlacesTablessScheduledForDeletion = clone $this->collDistributionsPlacesTabless;
                $this->distributionsPlacesTablessScheduledForDeletion->clear();
            }
            $this->distributionsPlacesTablessScheduledForDeletion[]= clone $distributionsPlacesTables;
            $distributionsPlacesTables->setMenuGroupes(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related DistributionsPlacesTabless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesTables[] List of DistributionsPlacesTables objects
     */
    public function getDistributionsPlacesTablessJoinDistributionsPlaces(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesTablesQuery::create(null, $criteria);
        $query->joinWith('DistributionsPlaces', $joinBehavior);

        return $this->getDistributionsPlacesTabless($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related DistributionsPlacesTabless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionsPlacesTables[] List of DistributionsPlacesTables objects
     */
    public function getDistributionsPlacesTablessJoinEventsTables(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionsPlacesTablesQuery::create(null, $criteria);
        $query->joinWith('EventsTables', $joinBehavior);

        return $this->getDistributionsPlacesTabless($query, $con);
    }

    /**
     * Clears out the collMenuess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuess()
     */
    public function clearMenuess()
    {
        $this->collMenuess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuess collection loaded partially.
     */
    public function resetPartialMenuess($v = true)
    {
        $this->collMenuessPartial = $v;
    }

    /**
     * Initializes the collMenuess collection.
     *
     * By default this just sets the collMenuess collection to an empty array (like clearcollMenuess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuess($overrideExisting = true)
    {
        if (null !== $this->collMenuess && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuesTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuess = new $collectionClassName;
        $this->collMenuess->setModel('\Model\Menues\Menues');
    }

    /**
     * Gets an array of ChildMenues objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroupes is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenues[] List of ChildMenues objects
     * @throws PropelException
     */
    public function getMenuess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuessPartial && !$this->isNew();
        if (null === $this->collMenuess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuess) {
                // return empty collection
                $this->initMenuess();
            } else {
                $collMenuess = ChildMenuesQuery::create(null, $criteria)
                    ->filterByMenuGroupes($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuessPartial && count($collMenuess)) {
                        $this->initMenuess(false);

                        foreach ($collMenuess as $obj) {
                            if (false == $this->collMenuess->contains($obj)) {
                                $this->collMenuess->append($obj);
                            }
                        }

                        $this->collMenuessPartial = true;
                    }

                    return $collMenuess;
                }

                if ($partial && $this->collMenuess) {
                    foreach ($this->collMenuess as $obj) {
                        if ($obj->isNew()) {
                            $collMenuess[] = $obj;
                        }
                    }
                }

                $this->collMenuess = $collMenuess;
                $this->collMenuessPartial = false;
            }
        }

        return $this->collMenuess;
    }

    /**
     * Sets a collection of ChildMenues objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function setMenuess(Collection $menuess, ConnectionInterface $con = null)
    {
        /** @var ChildMenues[] $menuessToDelete */
        $menuessToDelete = $this->getMenuess(new Criteria(), $con)->diff($menuess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuessScheduledForDeletion = clone $menuessToDelete;

        foreach ($menuessToDelete as $menuesRemoved) {
            $menuesRemoved->setMenuGroupes(null);
        }

        $this->collMenuess = null;
        foreach ($menuess as $menues) {
            $this->addMenues($menues);
        }

        $this->collMenuess = $menuess;
        $this->collMenuessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Menues objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Menues objects.
     * @throws PropelException
     */
    public function countMenuess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuessPartial && !$this->isNew();
        if (null === $this->collMenuess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuess());
            }

            $query = ChildMenuesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuGroupes($this)
                ->count($con);
        }

        return count($this->collMenuess);
    }

    /**
     * Method called to associate a ChildMenues object to this object
     * through the ChildMenues foreign key attribute.
     *
     * @param  ChildMenues $l ChildMenues
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
     */
    public function addMenues(ChildMenues $l)
    {
        if ($this->collMenuess === null) {
            $this->initMenuess();
            $this->collMenuessPartial = true;
        }

        if (!$this->collMenuess->contains($l)) {
            $this->doAddMenues($l);

            if ($this->menuessScheduledForDeletion and $this->menuessScheduledForDeletion->contains($l)) {
                $this->menuessScheduledForDeletion->remove($this->menuessScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenues $menues The ChildMenues object to add.
     */
    protected function doAddMenues(ChildMenues $menues)
    {
        $this->collMenuess[]= $menues;
        $menues->setMenuGroupes($this);
    }

    /**
     * @param  ChildMenues $menues The ChildMenues object to remove.
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function removeMenues(ChildMenues $menues)
    {
        if ($this->getMenuess()->contains($menues)) {
            $pos = $this->collMenuess->search($menues);
            $this->collMenuess->remove($pos);
            if (null === $this->menuessScheduledForDeletion) {
                $this->menuessScheduledForDeletion = clone $this->collMenuess;
                $this->menuessScheduledForDeletion->clear();
            }
            $this->menuessScheduledForDeletion[]= clone $menues;
            $menues->setMenuGroupes(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related Menuess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenues[] List of ChildMenues objects
     */
    public function getMenuessJoinAvailabilitys(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuesQuery::create(null, $criteria);
        $query->joinWith('Availabilitys', $joinBehavior);

        return $this->getMenuess($query, $con);
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
     * If this ChildMenuGroupes is new, it will return
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
                    ->filterByMenuGroupes($this)
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
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
     */
    public function setOrdersDetailss(Collection $ordersDetailss, ConnectionInterface $con = null)
    {
        /** @var OrdersDetails[] $ordersDetailssToDelete */
        $ordersDetailssToDelete = $this->getOrdersDetailss(new Criteria(), $con)->diff($ordersDetailss);


        $this->ordersDetailssScheduledForDeletion = $ordersDetailssToDelete;

        foreach ($ordersDetailssToDelete as $ordersDetailsRemoved) {
            $ordersDetailsRemoved->setMenuGroupes(null);
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
                ->filterByMenuGroupes($this)
                ->count($con);
        }

        return count($this->collOrdersDetailss);
    }

    /**
     * Method called to associate a OrdersDetails object to this object
     * through the OrdersDetails foreign key attribute.
     *
     * @param  OrdersDetails $l OrdersDetails
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
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
        $ordersDetails->setMenuGroupes($this);
    }

    /**
     * @param  OrdersDetails $ordersDetails The OrdersDetails object to remove.
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
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
            $ordersDetails->setMenuGroupes(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
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
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
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
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
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
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
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
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersDetailss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
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
        $this->collOrdersInProgresses->setModel('\Model\OIP\OrdersInProgress');
    }

    /**
     * Gets an array of OrdersInProgress objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroupes is new, it will return
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
                    ->filterByMenuGroupes($this)
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
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
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
            $ordersInProgressRemoved->setMenuGroupes(null);
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
                ->filterByMenuGroupes($this)
                ->count($con);
        }

        return count($this->collOrdersInProgresses);
    }

    /**
     * Method called to associate a OrdersInProgress object to this object
     * through the OrdersInProgress foreign key attribute.
     *
     * @param  OrdersInProgress $l OrdersInProgress
     * @return $this|\Model\Menues\MenuGroupes The current object (for fluent API support)
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
        $ordersInProgress->setMenuGroupes($this);
    }

    /**
     * @param  OrdersInProgress $ordersInProgress The OrdersInProgress object to remove.
     * @return $this|ChildMenuGroupes The current object (for fluent API support)
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
            $ordersInProgress->setMenuGroupes(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroupes is new, it will return
     * an empty collection; or if this MenuGroupes has previously
     * been saved, it will retrieve related OrdersInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroupes.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrdersInProgress[] List of OrdersInProgress objects
     */
    public function getOrdersInProgressesJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrdersInProgressQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getOrdersInProgresses($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aMenuTypes) {
            $this->aMenuTypes->removeMenuGroupes($this);
        }
        $this->menu_groupid = null;
        $this->menu_typeid = null;
        $this->name = null;
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
            if ($this->collDistributionsPlacesGroupess) {
                foreach ($this->collDistributionsPlacesGroupess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDistributionsPlacesTabless) {
                foreach ($this->collDistributionsPlacesTabless as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuess) {
                foreach ($this->collMenuess as $o) {
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

        $this->collDistributionsPlacesGroupess = null;
        $this->collDistributionsPlacesTabless = null;
        $this->collMenuess = null;
        $this->collOrdersDetailss = null;
        $this->collOrdersInProgresses = null;
        $this->aMenuTypes = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MenuGroupesTableMap::DEFAULT_STRING_FORMAT);
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
