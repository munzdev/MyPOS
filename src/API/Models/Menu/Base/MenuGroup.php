<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceGroup;
use API\Models\DistributionPlace\DistributionPlaceGroupQuery;
use API\Models\DistributionPlace\DistributionPlaceTable;
use API\Models\DistributionPlace\DistributionPlaceTableQuery;
use API\Models\DistributionPlace\Base\DistributionPlaceGroup as BaseDistributionPlaceGroup;
use API\Models\DistributionPlace\Base\DistributionPlaceTable as BaseDistributionPlaceTable;
use API\Models\DistributionPlace\Map\DistributionPlaceGroupTableMap;
use API\Models\DistributionPlace\Map\DistributionPlaceTableTableMap;
use API\Models\Menu\Menu as ChildMenu;
use API\Models\Menu\MenuGroup as ChildMenuGroup;
use API\Models\Menu\MenuGroupQuery as ChildMenuGroupQuery;
use API\Models\Menu\MenuQuery as ChildMenuQuery;
use API\Models\Menu\MenuType as ChildMenuType;
use API\Models\Menu\MenuTypeQuery as ChildMenuTypeQuery;
use API\Models\Menu\Map\MenuGroupTableMap;
use API\Models\Menu\Map\MenuTableMap;
use API\Models\OIP\OrderInProgress;
use API\Models\OIP\OrderInProgressQuery;
use API\Models\OIP\Base\OrderInProgress as BaseOrderInProgress;
use API\Models\OIP\Map\OrderInProgressTableMap;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\Base\OrderDetail as BaseOrderDetail;
use API\Models\Ordering\Map\OrderDetailTableMap;
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
 * Base class that represents a row from the 'menu_group' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Menu.Base
 */
abstract class MenuGroup implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Menu\\Map\\MenuGroupTableMap';


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
     * @var        ChildMenuType
     */
    protected $aMenuType;

    /**
     * @var        ObjectCollection|DistributionPlaceGroup[] Collection to store aggregation of DistributionPlaceGroup objects.
     */
    protected $collDistributionPlaceGroups;
    protected $collDistributionPlaceGroupsPartial;

    /**
     * @var        ObjectCollection|DistributionPlaceTable[] Collection to store aggregation of DistributionPlaceTable objects.
     */
    protected $collDistributionPlaceTables;
    protected $collDistributionPlaceTablesPartial;

    /**
     * @var        ObjectCollection|ChildMenu[] Collection to store aggregation of ChildMenu objects.
     */
    protected $collMenus;
    protected $collMenusPartial;

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
     * @var ObjectCollection|DistributionPlaceGroup[]
     */
    protected $distributionPlaceGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|DistributionPlaceTable[]
     */
    protected $distributionPlaceTablesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenu[]
     */
    protected $menusScheduledForDeletion = null;

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
     * Initializes internal state of API\Models\Menu\Base\MenuGroup object.
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
     * Compares this with another <code>MenuGroup</code> instance.  If
     * <code>obj</code> is an instance of <code>MenuGroup</code>, delegates to
     * <code>equals(MenuGroup)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|MenuGroup The current object, for fluid interface
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
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     */
    public function setMenuGroupid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_groupid !== $v) {
            $this->menu_groupid = $v;
            $this->modifiedColumns[MenuGroupTableMap::COL_MENU_GROUPID] = true;
        }

        return $this;
    } // setMenuGroupid()

    /**
     * Set the value of [menu_typeid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     */
    public function setMenuTypeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_typeid !== $v) {
            $this->menu_typeid = $v;
            $this->modifiedColumns[MenuGroupTableMap::COL_MENU_TYPEID] = true;
        }

        if ($this->aMenuType !== null && $this->aMenuType->getMenuTypeid() !== $v) {
            $this->aMenuType = null;
        }

        return $this;
    } // setMenuTypeid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MenuGroupTableMap::COL_NAME] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MenuGroupTableMap::translateFieldName('MenuGroupid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_groupid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MenuGroupTableMap::translateFieldName('MenuTypeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_typeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MenuGroupTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = MenuGroupTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Menu\\MenuGroup'), 0, $e);
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
        if ($this->aMenuType !== null && $this->menu_typeid !== $this->aMenuType->getMenuTypeid()) {
            $this->aMenuType = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MenuGroupTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMenuGroupQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMenuType = null;
            $this->collDistributionPlaceGroups = null;

            $this->collDistributionPlaceTables = null;

            $this->collMenus = null;

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
     * @see MenuGroup::setDeleted()
     * @see MenuGroup::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuGroupTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMenuGroupQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuGroupTableMap::DATABASE_NAME);
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
                MenuGroupTableMap::addInstanceToPool($this);
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

            if ($this->aMenuType !== null) {
                if ($this->aMenuType->isModified() || $this->aMenuType->isNew()) {
                    $affectedRows += $this->aMenuType->save($con);
                }
                $this->setMenuType($this->aMenuType);
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

            if ($this->distributionPlaceGroupsScheduledForDeletion !== null) {
                if (!$this->distributionPlaceGroupsScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionPlaceGroupQuery::create()
                        ->filterByPrimaryKeys($this->distributionPlaceGroupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionPlaceGroupsScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionPlaceGroups !== null) {
                foreach ($this->collDistributionPlaceGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->distributionPlaceTablesScheduledForDeletion !== null) {
                if (!$this->distributionPlaceTablesScheduledForDeletion->isEmpty()) {
                    \API\Models\DistributionPlace\DistributionPlaceTableQuery::create()
                        ->filterByPrimaryKeys($this->distributionPlaceTablesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->distributionPlaceTablesScheduledForDeletion = null;
                }
            }

            if ($this->collDistributionPlaceTables !== null) {
                foreach ($this->collDistributionPlaceTables as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->menusScheduledForDeletion !== null) {
                if (!$this->menusScheduledForDeletion->isEmpty()) {
                    \API\Models\Menu\MenuQuery::create()
                        ->filterByPrimaryKeys($this->menusScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menusScheduledForDeletion = null;
                }
            }

            if ($this->collMenus !== null) {
                foreach ($this->collMenus as $referrerFK) {
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

        $this->modifiedColumns[MenuGroupTableMap::COL_MENU_GROUPID] = true;
        if (null !== $this->menu_groupid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MenuGroupTableMap::COL_MENU_GROUPID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MenuGroupTableMap::COL_MENU_GROUPID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_groupid';
        }
        if ($this->isColumnModified(MenuGroupTableMap::COL_MENU_TYPEID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_typeid';
        }
        if ($this->isColumnModified(MenuGroupTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO menu_group (%s) VALUES (%s)',
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
        $pos = MenuGroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['MenuGroup'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MenuGroup'][$this->hashCode()] = true;
        $keys = MenuGroupTableMap::getFieldNames($keyType);
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
            if (null !== $this->aMenuType) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_type';
                        break;
                    default:
                        $key = 'MenuType';
                }

                $result[$key] = $this->aMenuType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDistributionPlaceGroups) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionPlaceGroups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distribution_place_groups';
                        break;
                    default:
                        $key = 'DistributionPlaceGroups';
                }

                $result[$key] = $this->collDistributionPlaceGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDistributionPlaceTables) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'distributionPlaceTables';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'distribution_place_tables';
                        break;
                    default:
                        $key = 'DistributionPlaceTables';
                }

                $result[$key] = $this->collDistributionPlaceTables->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMenus) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menus';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menus';
                        break;
                    default:
                        $key = 'Menus';
                }

                $result[$key] = $this->collMenus->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Menu\MenuGroup
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MenuGroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Menu\MenuGroup
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
        $keys = MenuGroupTableMap::getFieldNames($keyType);

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
     * @return $this|\API\Models\Menu\MenuGroup The current object, for fluid interface
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
        $criteria = new Criteria(MenuGroupTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MenuGroupTableMap::COL_MENU_GROUPID)) {
            $criteria->add(MenuGroupTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        }
        if ($this->isColumnModified(MenuGroupTableMap::COL_MENU_TYPEID)) {
            $criteria->add(MenuGroupTableMap::COL_MENU_TYPEID, $this->menu_typeid);
        }
        if ($this->isColumnModified(MenuGroupTableMap::COL_NAME)) {
            $criteria->add(MenuGroupTableMap::COL_NAME, $this->name);
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
        $criteria = ChildMenuGroupQuery::create();
        $criteria->add(MenuGroupTableMap::COL_MENU_GROUPID, $this->menu_groupid);
        $criteria->add(MenuGroupTableMap::COL_MENU_TYPEID, $this->menu_typeid);

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

        //relation fk_menu_groupes_menu_types1 to table menu_type
        if ($this->aMenuType && $hash = spl_object_hash($this->aMenuType)) {
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
     * @param      object $copyObj An object of \API\Models\Menu\MenuGroup (or compatible) type.
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

            foreach ($this->getDistributionPlaceGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlaceGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDistributionPlaceTables() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlaceTable($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMenus() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenu($relObj->copy($deepCopy));
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
     * @return \API\Models\Menu\MenuGroup Clone of current object.
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
     * Declares an association between this object and a ChildMenuType object.
     *
     * @param  ChildMenuType $v
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuType(ChildMenuType $v = null)
    {
        if ($v === null) {
            $this->setMenuTypeid(NULL);
        } else {
            $this->setMenuTypeid($v->getMenuTypeid());
        }

        $this->aMenuType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMenuType object, it will not be re-added.
        if ($v !== null) {
            $v->addMenuGroup($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMenuType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMenuType The associated ChildMenuType object.
     * @throws PropelException
     */
    public function getMenuType(ConnectionInterface $con = null)
    {
        if ($this->aMenuType === null && ($this->menu_typeid !== null)) {
            $this->aMenuType = ChildMenuTypeQuery::create()
                ->filterByMenuGroup($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuType->addMenuGroups($this);
             */
        }

        return $this->aMenuType;
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
        if ('DistributionPlaceGroup' == $relationName) {
            return $this->initDistributionPlaceGroups();
        }
        if ('DistributionPlaceTable' == $relationName) {
            return $this->initDistributionPlaceTables();
        }
        if ('Menu' == $relationName) {
            return $this->initMenus();
        }
        if ('OrderDetail' == $relationName) {
            return $this->initOrderDetails();
        }
        if ('OrderInProgress' == $relationName) {
            return $this->initOrderInProgresses();
        }
    }

    /**
     * Clears out the collDistributionPlaceGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionPlaceGroups()
     */
    public function clearDistributionPlaceGroups()
    {
        $this->collDistributionPlaceGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionPlaceGroups collection loaded partially.
     */
    public function resetPartialDistributionPlaceGroups($v = true)
    {
        $this->collDistributionPlaceGroupsPartial = $v;
    }

    /**
     * Initializes the collDistributionPlaceGroups collection.
     *
     * By default this just sets the collDistributionPlaceGroups collection to an empty array (like clearcollDistributionPlaceGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionPlaceGroups($overrideExisting = true)
    {
        if (null !== $this->collDistributionPlaceGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionPlaceGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionPlaceGroups = new $collectionClassName;
        $this->collDistributionPlaceGroups->setModel('\API\Models\DistributionPlace\DistributionPlaceGroup');
    }

    /**
     * Gets an array of DistributionPlaceGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionPlaceGroup[] List of DistributionPlaceGroup objects
     * @throws PropelException
     */
    public function getDistributionPlaceGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceGroupsPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceGroups) {
                // return empty collection
                $this->initDistributionPlaceGroups();
            } else {
                $collDistributionPlaceGroups = DistributionPlaceGroupQuery::create(null, $criteria)
                    ->filterByMenuGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionPlaceGroupsPartial && count($collDistributionPlaceGroups)) {
                        $this->initDistributionPlaceGroups(false);

                        foreach ($collDistributionPlaceGroups as $obj) {
                            if (false == $this->collDistributionPlaceGroups->contains($obj)) {
                                $this->collDistributionPlaceGroups->append($obj);
                            }
                        }

                        $this->collDistributionPlaceGroupsPartial = true;
                    }

                    return $collDistributionPlaceGroups;
                }

                if ($partial && $this->collDistributionPlaceGroups) {
                    foreach ($this->collDistributionPlaceGroups as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionPlaceGroups[] = $obj;
                        }
                    }
                }

                $this->collDistributionPlaceGroups = $collDistributionPlaceGroups;
                $this->collDistributionPlaceGroupsPartial = false;
            }
        }

        return $this->collDistributionPlaceGroups;
    }

    /**
     * Sets a collection of DistributionPlaceGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function setDistributionPlaceGroups(Collection $distributionPlaceGroups, ConnectionInterface $con = null)
    {
        /** @var DistributionPlaceGroup[] $distributionPlaceGroupsToDelete */
        $distributionPlaceGroupsToDelete = $this->getDistributionPlaceGroups(new Criteria(), $con)->diff($distributionPlaceGroups);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceGroupsScheduledForDeletion = clone $distributionPlaceGroupsToDelete;

        foreach ($distributionPlaceGroupsToDelete as $distributionPlaceGroupRemoved) {
            $distributionPlaceGroupRemoved->setMenuGroup(null);
        }

        $this->collDistributionPlaceGroups = null;
        foreach ($distributionPlaceGroups as $distributionPlaceGroup) {
            $this->addDistributionPlaceGroup($distributionPlaceGroup);
        }

        $this->collDistributionPlaceGroups = $distributionPlaceGroups;
        $this->collDistributionPlaceGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionPlaceGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionPlaceGroup objects.
     * @throws PropelException
     */
    public function countDistributionPlaceGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceGroupsPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionPlaceGroups());
            }

            $query = DistributionPlaceGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuGroup($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceGroups);
    }

    /**
     * Method called to associate a DistributionPlaceGroup object to this object
     * through the DistributionPlaceGroup foreign key attribute.
     *
     * @param  DistributionPlaceGroup $l DistributionPlaceGroup
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     */
    public function addDistributionPlaceGroup(DistributionPlaceGroup $l)
    {
        if ($this->collDistributionPlaceGroups === null) {
            $this->initDistributionPlaceGroups();
            $this->collDistributionPlaceGroupsPartial = true;
        }

        if (!$this->collDistributionPlaceGroups->contains($l)) {
            $this->doAddDistributionPlaceGroup($l);

            if ($this->distributionPlaceGroupsScheduledForDeletion and $this->distributionPlaceGroupsScheduledForDeletion->contains($l)) {
                $this->distributionPlaceGroupsScheduledForDeletion->remove($this->distributionPlaceGroupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionPlaceGroup $distributionPlaceGroup The DistributionPlaceGroup object to add.
     */
    protected function doAddDistributionPlaceGroup(DistributionPlaceGroup $distributionPlaceGroup)
    {
        $this->collDistributionPlaceGroups[]= $distributionPlaceGroup;
        $distributionPlaceGroup->setMenuGroup($this);
    }

    /**
     * @param  DistributionPlaceGroup $distributionPlaceGroup The DistributionPlaceGroup object to remove.
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function removeDistributionPlaceGroup(DistributionPlaceGroup $distributionPlaceGroup)
    {
        if ($this->getDistributionPlaceGroups()->contains($distributionPlaceGroup)) {
            $pos = $this->collDistributionPlaceGroups->search($distributionPlaceGroup);
            $this->collDistributionPlaceGroups->remove($pos);
            if (null === $this->distributionPlaceGroupsScheduledForDeletion) {
                $this->distributionPlaceGroupsScheduledForDeletion = clone $this->collDistributionPlaceGroups;
                $this->distributionPlaceGroupsScheduledForDeletion->clear();
            }
            $this->distributionPlaceGroupsScheduledForDeletion[]= clone $distributionPlaceGroup;
            $distributionPlaceGroup->setMenuGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related DistributionPlaceGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceGroup[] List of DistributionPlaceGroup objects
     */
    public function getDistributionPlaceGroupsJoinDistributionPlace(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceGroupQuery::create(null, $criteria);
        $query->joinWith('DistributionPlace', $joinBehavior);

        return $this->getDistributionPlaceGroups($query, $con);
    }

    /**
     * Clears out the collDistributionPlaceTables collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addDistributionPlaceTables()
     */
    public function clearDistributionPlaceTables()
    {
        $this->collDistributionPlaceTables = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collDistributionPlaceTables collection loaded partially.
     */
    public function resetPartialDistributionPlaceTables($v = true)
    {
        $this->collDistributionPlaceTablesPartial = $v;
    }

    /**
     * Initializes the collDistributionPlaceTables collection.
     *
     * By default this just sets the collDistributionPlaceTables collection to an empty array (like clearcollDistributionPlaceTables());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDistributionPlaceTables($overrideExisting = true)
    {
        if (null !== $this->collDistributionPlaceTables && !$overrideExisting) {
            return;
        }

        $collectionClassName = DistributionPlaceTableTableMap::getTableMap()->getCollectionClassName();

        $this->collDistributionPlaceTables = new $collectionClassName;
        $this->collDistributionPlaceTables->setModel('\API\Models\DistributionPlace\DistributionPlaceTable');
    }

    /**
     * Gets an array of DistributionPlaceTable objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|DistributionPlaceTable[] List of DistributionPlaceTable objects
     * @throws PropelException
     */
    public function getDistributionPlaceTables(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceTablesPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceTables || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceTables) {
                // return empty collection
                $this->initDistributionPlaceTables();
            } else {
                $collDistributionPlaceTables = DistributionPlaceTableQuery::create(null, $criteria)
                    ->filterByMenuGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDistributionPlaceTablesPartial && count($collDistributionPlaceTables)) {
                        $this->initDistributionPlaceTables(false);

                        foreach ($collDistributionPlaceTables as $obj) {
                            if (false == $this->collDistributionPlaceTables->contains($obj)) {
                                $this->collDistributionPlaceTables->append($obj);
                            }
                        }

                        $this->collDistributionPlaceTablesPartial = true;
                    }

                    return $collDistributionPlaceTables;
                }

                if ($partial && $this->collDistributionPlaceTables) {
                    foreach ($this->collDistributionPlaceTables as $obj) {
                        if ($obj->isNew()) {
                            $collDistributionPlaceTables[] = $obj;
                        }
                    }
                }

                $this->collDistributionPlaceTables = $collDistributionPlaceTables;
                $this->collDistributionPlaceTablesPartial = false;
            }
        }

        return $this->collDistributionPlaceTables;
    }

    /**
     * Sets a collection of DistributionPlaceTable objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceTables A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function setDistributionPlaceTables(Collection $distributionPlaceTables, ConnectionInterface $con = null)
    {
        /** @var DistributionPlaceTable[] $distributionPlaceTablesToDelete */
        $distributionPlaceTablesToDelete = $this->getDistributionPlaceTables(new Criteria(), $con)->diff($distributionPlaceTables);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceTablesScheduledForDeletion = clone $distributionPlaceTablesToDelete;

        foreach ($distributionPlaceTablesToDelete as $distributionPlaceTableRemoved) {
            $distributionPlaceTableRemoved->setMenuGroup(null);
        }

        $this->collDistributionPlaceTables = null;
        foreach ($distributionPlaceTables as $distributionPlaceTable) {
            $this->addDistributionPlaceTable($distributionPlaceTable);
        }

        $this->collDistributionPlaceTables = $distributionPlaceTables;
        $this->collDistributionPlaceTablesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseDistributionPlaceTable objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseDistributionPlaceTable objects.
     * @throws PropelException
     */
    public function countDistributionPlaceTables(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collDistributionPlaceTablesPartial && !$this->isNew();
        if (null === $this->collDistributionPlaceTables || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDistributionPlaceTables) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDistributionPlaceTables());
            }

            $query = DistributionPlaceTableQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuGroup($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceTables);
    }

    /**
     * Method called to associate a DistributionPlaceTable object to this object
     * through the DistributionPlaceTable foreign key attribute.
     *
     * @param  DistributionPlaceTable $l DistributionPlaceTable
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     */
    public function addDistributionPlaceTable(DistributionPlaceTable $l)
    {
        if ($this->collDistributionPlaceTables === null) {
            $this->initDistributionPlaceTables();
            $this->collDistributionPlaceTablesPartial = true;
        }

        if (!$this->collDistributionPlaceTables->contains($l)) {
            $this->doAddDistributionPlaceTable($l);

            if ($this->distributionPlaceTablesScheduledForDeletion and $this->distributionPlaceTablesScheduledForDeletion->contains($l)) {
                $this->distributionPlaceTablesScheduledForDeletion->remove($this->distributionPlaceTablesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param DistributionPlaceTable $distributionPlaceTable The DistributionPlaceTable object to add.
     */
    protected function doAddDistributionPlaceTable(DistributionPlaceTable $distributionPlaceTable)
    {
        $this->collDistributionPlaceTables[]= $distributionPlaceTable;
        $distributionPlaceTable->setMenuGroup($this);
    }

    /**
     * @param  DistributionPlaceTable $distributionPlaceTable The DistributionPlaceTable object to remove.
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function removeDistributionPlaceTable(DistributionPlaceTable $distributionPlaceTable)
    {
        if ($this->getDistributionPlaceTables()->contains($distributionPlaceTable)) {
            $pos = $this->collDistributionPlaceTables->search($distributionPlaceTable);
            $this->collDistributionPlaceTables->remove($pos);
            if (null === $this->distributionPlaceTablesScheduledForDeletion) {
                $this->distributionPlaceTablesScheduledForDeletion = clone $this->collDistributionPlaceTables;
                $this->distributionPlaceTablesScheduledForDeletion->clear();
            }
            $this->distributionPlaceTablesScheduledForDeletion[]= clone $distributionPlaceTable;
            $distributionPlaceTable->setMenuGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related DistributionPlaceTables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceTable[] List of DistributionPlaceTable objects
     */
    public function getDistributionPlaceTablesJoinDistributionPlace(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceTableQuery::create(null, $criteria);
        $query->joinWith('DistributionPlace', $joinBehavior);

        return $this->getDistributionPlaceTables($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related DistributionPlaceTables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|DistributionPlaceTable[] List of DistributionPlaceTable objects
     */
    public function getDistributionPlaceTablesJoinEventTable(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = DistributionPlaceTableQuery::create(null, $criteria);
        $query->joinWith('EventTable', $joinBehavior);

        return $this->getDistributionPlaceTables($query, $con);
    }

    /**
     * Clears out the collMenus collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenus()
     */
    public function clearMenus()
    {
        $this->collMenus = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenus collection loaded partially.
     */
    public function resetPartialMenus($v = true)
    {
        $this->collMenusPartial = $v;
    }

    /**
     * Initializes the collMenus collection.
     *
     * By default this just sets the collMenus collection to an empty array (like clearcollMenus());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenus($overrideExisting = true)
    {
        if (null !== $this->collMenus && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuTableMap::getTableMap()->getCollectionClassName();

        $this->collMenus = new $collectionClassName;
        $this->collMenus->setModel('\API\Models\Menu\Menu');
    }

    /**
     * Gets an array of ChildMenu objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenu[] List of ChildMenu objects
     * @throws PropelException
     */
    public function getMenus(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenusPartial && !$this->isNew();
        if (null === $this->collMenus || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenus) {
                // return empty collection
                $this->initMenus();
            } else {
                $collMenus = ChildMenuQuery::create(null, $criteria)
                    ->filterByMenuGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenusPartial && count($collMenus)) {
                        $this->initMenus(false);

                        foreach ($collMenus as $obj) {
                            if (false == $this->collMenus->contains($obj)) {
                                $this->collMenus->append($obj);
                            }
                        }

                        $this->collMenusPartial = true;
                    }

                    return $collMenus;
                }

                if ($partial && $this->collMenus) {
                    foreach ($this->collMenus as $obj) {
                        if ($obj->isNew()) {
                            $collMenus[] = $obj;
                        }
                    }
                }

                $this->collMenus = $collMenus;
                $this->collMenusPartial = false;
            }
        }

        return $this->collMenus;
    }

    /**
     * Sets a collection of ChildMenu objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menus A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function setMenus(Collection $menus, ConnectionInterface $con = null)
    {
        /** @var ChildMenu[] $menusToDelete */
        $menusToDelete = $this->getMenus(new Criteria(), $con)->diff($menus);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menusScheduledForDeletion = clone $menusToDelete;

        foreach ($menusToDelete as $menuRemoved) {
            $menuRemoved->setMenuGroup(null);
        }

        $this->collMenus = null;
        foreach ($menus as $menu) {
            $this->addMenu($menu);
        }

        $this->collMenus = $menus;
        $this->collMenusPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Menu objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Menu objects.
     * @throws PropelException
     */
    public function countMenus(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenusPartial && !$this->isNew();
        if (null === $this->collMenus || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenus) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenus());
            }

            $query = ChildMenuQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuGroup($this)
                ->count($con);
        }

        return count($this->collMenus);
    }

    /**
     * Method called to associate a ChildMenu object to this object
     * through the ChildMenu foreign key attribute.
     *
     * @param  ChildMenu $l ChildMenu
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
     */
    public function addMenu(ChildMenu $l)
    {
        if ($this->collMenus === null) {
            $this->initMenus();
            $this->collMenusPartial = true;
        }

        if (!$this->collMenus->contains($l)) {
            $this->doAddMenu($l);

            if ($this->menusScheduledForDeletion and $this->menusScheduledForDeletion->contains($l)) {
                $this->menusScheduledForDeletion->remove($this->menusScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenu $menu The ChildMenu object to add.
     */
    protected function doAddMenu(ChildMenu $menu)
    {
        $this->collMenus[]= $menu;
        $menu->setMenuGroup($this);
    }

    /**
     * @param  ChildMenu $menu The ChildMenu object to remove.
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function removeMenu(ChildMenu $menu)
    {
        if ($this->getMenus()->contains($menu)) {
            $pos = $this->collMenus->search($menu);
            $this->collMenus->remove($pos);
            if (null === $this->menusScheduledForDeletion) {
                $this->menusScheduledForDeletion = clone $this->collMenus;
                $this->menusScheduledForDeletion->clear();
            }
            $this->menusScheduledForDeletion[]= clone $menu;
            $menu->setMenuGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related Menus from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenu[] List of ChildMenu objects
     */
    public function getMenusJoinAvailability(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuQuery::create(null, $criteria);
        $query->joinWith('Availability', $joinBehavior);

        return $this->getMenus($query, $con);
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
     * If this ChildMenuGroup is new, it will return
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
                    ->filterByMenuGroup($this)
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
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function setOrderDetails(Collection $orderDetails, ConnectionInterface $con = null)
    {
        /** @var OrderDetail[] $orderDetailsToDelete */
        $orderDetailsToDelete = $this->getOrderDetails(new Criteria(), $con)->diff($orderDetails);


        $this->orderDetailsScheduledForDeletion = $orderDetailsToDelete;

        foreach ($orderDetailsToDelete as $orderDetailRemoved) {
            $orderDetailRemoved->setMenuGroup(null);
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
                ->filterByMenuGroup($this)
                ->count($con);
        }

        return count($this->collOrderDetails);
    }

    /**
     * Method called to associate a OrderDetail object to this object
     * through the OrderDetail foreign key attribute.
     *
     * @param  OrderDetail $l OrderDetail
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
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
        $orderDetail->setMenuGroup($this);
    }

    /**
     * @param  OrderDetail $orderDetail The OrderDetail object to remove.
     * @return $this|ChildMenuGroup The current object (for fluent API support)
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
            $orderDetail->setMenuGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
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
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
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
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
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
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

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
     * If this ChildMenuGroup is new, it will return
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
                    ->filterByMenuGroup($this)
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
     * @return $this|ChildMenuGroup The current object (for fluent API support)
     */
    public function setOrderInProgresses(Collection $orderInProgresses, ConnectionInterface $con = null)
    {
        /** @var OrderInProgress[] $orderInProgressesToDelete */
        $orderInProgressesToDelete = $this->getOrderInProgresses(new Criteria(), $con)->diff($orderInProgresses);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->orderInProgressesScheduledForDeletion = clone $orderInProgressesToDelete;

        foreach ($orderInProgressesToDelete as $orderInProgressRemoved) {
            $orderInProgressRemoved->setMenuGroup(null);
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
                ->filterByMenuGroup($this)
                ->count($con);
        }

        return count($this->collOrderInProgresses);
    }

    /**
     * Method called to associate a OrderInProgress object to this object
     * through the OrderInProgress foreign key attribute.
     *
     * @param  OrderInProgress $l OrderInProgress
     * @return $this|\API\Models\Menu\MenuGroup The current object (for fluent API support)
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
        $orderInProgress->setMenuGroup($this);
    }

    /**
     * @param  OrderInProgress $orderInProgress The OrderInProgress object to remove.
     * @return $this|ChildMenuGroup The current object (for fluent API support)
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
            $orderInProgress->setMenuGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuGroup is new, it will return
     * an empty collection; or if this MenuGroup has previously
     * been saved, it will retrieve related OrderInProgresses from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderInProgress[] List of OrderInProgress objects
     */
    public function getOrderInProgressesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderInProgressQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getOrderInProgresses($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aMenuType) {
            $this->aMenuType->removeMenuGroup($this);
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
            if ($this->collDistributionPlaceGroups) {
                foreach ($this->collDistributionPlaceGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDistributionPlaceTables) {
                foreach ($this->collDistributionPlaceTables as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenus) {
                foreach ($this->collMenus as $o) {
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

        $this->collDistributionPlaceGroups = null;
        $this->collDistributionPlaceTables = null;
        $this->collMenus = null;
        $this->collOrderDetails = null;
        $this->collOrderInProgresses = null;
        $this->aMenuType = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MenuGroupTableMap::DEFAULT_STRING_FORMAT);
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
