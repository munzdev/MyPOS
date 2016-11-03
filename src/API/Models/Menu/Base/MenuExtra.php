<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Event\EventQuery;
use API\Models\Menu\Availability as ChildAvailability;
use API\Models\Menu\AvailabilityQuery as ChildAvailabilityQuery;
use API\Models\Menu\Menu as ChildMenu;
use API\Models\Menu\MenuExtra as ChildMenuExtra;
use API\Models\Menu\MenuExtraQuery as ChildMenuExtraQuery;
use API\Models\Menu\MenuPossibleExtra as ChildMenuPossibleExtra;
use API\Models\Menu\MenuPossibleExtraQuery as ChildMenuPossibleExtraQuery;
use API\Models\Menu\MenuQuery as ChildMenuQuery;
use API\Models\Menu\Map\MenuExtraTableMap;
use API\Models\Menu\Map\MenuPossibleExtraTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Collection\ObjectCombinationCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'menu_extra' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Menu.Base
 */
abstract class MenuExtra implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Menu\\Map\\MenuExtraTableMap';


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
     * The value for the menu_extraid field.
     *
     * @var        int
     */
    protected $menu_extraid;

    /**
     * The value for the eventid field.
     *
     * @var        int
     */
    protected $eventid;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

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
     * @var        ChildAvailability
     */
    protected $aAvailability;

    /**
     * @var        Event
     */
    protected $aEvent;

    /**
     * @var        ObjectCollection|ChildMenuPossibleExtra[] Collection to store aggregation of ChildMenuPossibleExtra objects.
     */
    protected $collMenuPossibleExtras;
    protected $collMenuPossibleExtrasPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildMenu combinations.
     */
    protected $combinationCollMenuMenuPossibleExtraids;

    /**
     * @var bool
     */
    protected $combinationCollMenuMenuPossibleExtraidsPartial;

    /**
     * @var        ObjectCollection|ChildMenu[] Cross Collection to store aggregation of ChildMenu objects.
     */
    protected $collMenus;

    /**
     * @var bool
     */
    protected $collMenusPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildMenu combinations.
     */
    protected $combinationCollMenuMenuPossibleExtraidsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenuPossibleExtra[]
     */
    protected $menuPossibleExtrasScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Menu\Base\MenuExtra object.
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
     * Compares this with another <code>MenuExtra</code> instance.  If
     * <code>obj</code> is an instance of <code>MenuExtra</code>, delegates to
     * <code>equals(MenuExtra)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|MenuExtra The current object, for fluid interface
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
     * Get the [menu_extraid] column value.
     *
     * @return int
     */
    public function getMenuExtraid()
    {
        return $this->menu_extraid;
    }

    /**
     * Get the [eventid] column value.
     *
     * @return int
     */
    public function getEventid()
    {
        return $this->eventid;
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
     * Set the value of [menu_extraid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     */
    public function setMenuExtraid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_extraid !== $v) {
            $this->menu_extraid = $v;
            $this->modifiedColumns[MenuExtraTableMap::COL_MENU_EXTRAID] = true;
        }

        return $this;
    } // setMenuExtraid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[MenuExtraTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvent !== null && $this->aEvent->getEventid() !== $v) {
            $this->aEvent = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MenuExtraTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [availabilityid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     */
    public function setAvailabilityid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availabilityid !== $v) {
            $this->availabilityid = $v;
            $this->modifiedColumns[MenuExtraTableMap::COL_AVAILABILITYID] = true;
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
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     */
    public function setAvailabilityAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->availability_amount !== $v) {
            $this->availability_amount = $v;
            $this->modifiedColumns[MenuExtraTableMap::COL_AVAILABILITY_AMOUNT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MenuExtraTableMap::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_extraid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MenuExtraTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MenuExtraTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MenuExtraTableMap::translateFieldName('Availabilityid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availabilityid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MenuExtraTableMap::translateFieldName('AvailabilityAmount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->availability_amount = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = MenuExtraTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Menu\\MenuExtra'), 0, $e);
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
        if ($this->aEvent !== null && $this->eventid !== $this->aEvent->getEventid()) {
            $this->aEvent = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MenuExtraTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMenuExtraQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAvailability = null;
            $this->aEvent = null;
            $this->collMenuPossibleExtras = null;

            $this->collMenuMenuPossibleExtraids = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see MenuExtra::setDeleted()
     * @see MenuExtra::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuExtraTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMenuExtraQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuExtraTableMap::DATABASE_NAME);
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
                MenuExtraTableMap::addInstanceToPool($this);
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

            if ($this->aEvent !== null) {
                if ($this->aEvent->isModified() || $this->aEvent->isNew()) {
                    $affectedRows += $this->aEvent->save($con);
                }
                $this->setEvent($this->aEvent);
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

            if ($this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion !== null) {
                if (!$this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[1] = $this->getMenuExtraid();
                        $entryPk[2] = $combination[0]->getMenuid();
                        //$combination[1] = MenuPossibleExtraid;
                        $entryPk[0] = $combination[1];

                        $pks[] = $entryPk;
                    }

                    \API\Models\Menu\MenuPossibleExtraQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollMenuMenuPossibleExtraids) {
                foreach ($this->combinationCollMenuMenuPossibleExtraids as $combination) {

                    //$combination[0] = Menu (fk_menues_possible_extras_menues1)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = MenuPossibleExtraid; Nothing to save.
                }
            }


            if ($this->menuPossibleExtrasScheduledForDeletion !== null) {
                if (!$this->menuPossibleExtrasScheduledForDeletion->isEmpty()) {
                    \API\Models\Menu\MenuPossibleExtraQuery::create()
                        ->filterByPrimaryKeys($this->menuPossibleExtrasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuPossibleExtrasScheduledForDeletion = null;
                }
            }

            if ($this->collMenuPossibleExtras !== null) {
                foreach ($this->collMenuPossibleExtras as $referrerFK) {
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

        $this->modifiedColumns[MenuExtraTableMap::COL_MENU_EXTRAID] = true;
        if (null !== $this->menu_extraid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MenuExtraTableMap::COL_MENU_EXTRAID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MenuExtraTableMap::COL_MENU_EXTRAID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_extraid';
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_AVAILABILITYID)) {
            $modifiedColumns[':p' . $index++]  = 'availabilityid';
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_AVAILABILITY_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'availability_amount';
        }

        $sql = sprintf(
            'INSERT INTO menu_extra (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'menu_extraid':
                        $stmt->bindValue($identifier, $this->menu_extraid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
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
        $this->setMenuExtraid($pk);

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
        $pos = MenuExtraTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMenuExtraid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getAvailabilityid();
                break;
            case 4:
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

        if (isset($alreadyDumpedObjects['MenuExtra'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MenuExtra'][$this->hashCode()] = true;
        $keys = MenuExtraTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMenuExtraid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getAvailabilityid(),
            $keys[4] => $this->getAvailabilityAmount(),
        );
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
            if (null !== $this->aEvent) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'event';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event';
                        break;
                    default:
                        $key = 'Event';
                }

                $result[$key] = $this->aEvent->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMenuPossibleExtras) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuPossibleExtras';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_possible_extras';
                        break;
                    default:
                        $key = 'MenuPossibleExtras';
                }

                $result[$key] = $this->collMenuPossibleExtras->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Menu\MenuExtra
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MenuExtraTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Menu\MenuExtra
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMenuExtraid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setAvailabilityid($value);
                break;
            case 4:
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
        $keys = MenuExtraTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMenuExtraid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAvailabilityid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAvailabilityAmount($arr[$keys[4]]);
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
     * @return $this|\API\Models\Menu\MenuExtra The current object, for fluid interface
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
        $criteria = new Criteria(MenuExtraTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MenuExtraTableMap::COL_MENU_EXTRAID)) {
            $criteria->add(MenuExtraTableMap::COL_MENU_EXTRAID, $this->menu_extraid);
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_EVENTID)) {
            $criteria->add(MenuExtraTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_NAME)) {
            $criteria->add(MenuExtraTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_AVAILABILITYID)) {
            $criteria->add(MenuExtraTableMap::COL_AVAILABILITYID, $this->availabilityid);
        }
        if ($this->isColumnModified(MenuExtraTableMap::COL_AVAILABILITY_AMOUNT)) {
            $criteria->add(MenuExtraTableMap::COL_AVAILABILITY_AMOUNT, $this->availability_amount);
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
        $criteria = ChildMenuExtraQuery::create();
        $criteria->add(MenuExtraTableMap::COL_MENU_EXTRAID, $this->menu_extraid);
        $criteria->add(MenuExtraTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getMenuExtraid() &&
            null !== $this->getEventid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_menu_extras_events1 to table event
        if ($this->aEvent && $hash = spl_object_hash($this->aEvent)) {
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
        $pks[0] = $this->getMenuExtraid();
        $pks[1] = $this->getEventid();

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
        $this->setMenuExtraid($keys[0]);
        $this->setEventid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getMenuExtraid()) && (null === $this->getEventid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Menu\MenuExtra (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setName($this->getName());
        $copyObj->setAvailabilityid($this->getAvailabilityid());
        $copyObj->setAvailabilityAmount($this->getAvailabilityAmount());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMenuPossibleExtras() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuPossibleExtra($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setMenuExtraid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Menu\MenuExtra Clone of current object.
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
     * Declares an association between this object and a ChildAvailability object.
     *
     * @param  ChildAvailability $v
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAvailability(ChildAvailability $v = null)
    {
        if ($v === null) {
            $this->setAvailabilityid(NULL);
        } else {
            $this->setAvailabilityid($v->getAvailabilityid());
        }

        $this->aAvailability = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAvailability object, it will not be re-added.
        if ($v !== null) {
            $v->addMenuExtra($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAvailability object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAvailability The associated ChildAvailability object.
     * @throws PropelException
     */
    public function getAvailability(ConnectionInterface $con = null)
    {
        if ($this->aAvailability === null && ($this->availabilityid !== null)) {
            $this->aAvailability = ChildAvailabilityQuery::create()->findPk($this->availabilityid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAvailability->addMenuExtras($this);
             */
        }

        return $this->aAvailability;
    }

    /**
     * Declares an association between this object and a Event object.
     *
     * @param  Event $v
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEvent(Event $v = null)
    {
        if ($v === null) {
            $this->setEventid(NULL);
        } else {
            $this->setEventid($v->getEventid());
        }

        $this->aEvent = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Event object, it will not be re-added.
        if ($v !== null) {
            $v->addMenuExtra($this);
        }


        return $this;
    }


    /**
     * Get the associated Event object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Event The associated Event object.
     * @throws PropelException
     */
    public function getEvent(ConnectionInterface $con = null)
    {
        if ($this->aEvent === null && ($this->eventid !== null)) {
            $this->aEvent = EventQuery::create()->findPk($this->eventid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvent->addMenuExtras($this);
             */
        }

        return $this->aEvent;
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
        if ('MenuPossibleExtra' == $relationName) {
            return $this->initMenuPossibleExtras();
        }
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
     * Reset is the collMenuPossibleExtras collection loaded partially.
     */
    public function resetPartialMenuPossibleExtras($v = true)
    {
        $this->collMenuPossibleExtrasPartial = $v;
    }

    /**
     * Initializes the collMenuPossibleExtras collection.
     *
     * By default this just sets the collMenuPossibleExtras collection to an empty array (like clearcollMenuPossibleExtras());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuPossibleExtras($overrideExisting = true)
    {
        if (null !== $this->collMenuPossibleExtras && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuPossibleExtraTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuPossibleExtras = new $collectionClassName;
        $this->collMenuPossibleExtras->setModel('\API\Models\Menu\MenuPossibleExtra');
    }

    /**
     * Gets an array of ChildMenuPossibleExtra objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuExtra is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenuPossibleExtra[] List of ChildMenuPossibleExtra objects
     * @throws PropelException
     */
    public function getMenuPossibleExtras(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuPossibleExtrasPartial && !$this->isNew();
        if (null === $this->collMenuPossibleExtras || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuPossibleExtras) {
                // return empty collection
                $this->initMenuPossibleExtras();
            } else {
                $collMenuPossibleExtras = ChildMenuPossibleExtraQuery::create(null, $criteria)
                    ->filterByMenuExtra($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuPossibleExtrasPartial && count($collMenuPossibleExtras)) {
                        $this->initMenuPossibleExtras(false);

                        foreach ($collMenuPossibleExtras as $obj) {
                            if (false == $this->collMenuPossibleExtras->contains($obj)) {
                                $this->collMenuPossibleExtras->append($obj);
                            }
                        }

                        $this->collMenuPossibleExtrasPartial = true;
                    }

                    return $collMenuPossibleExtras;
                }

                if ($partial && $this->collMenuPossibleExtras) {
                    foreach ($this->collMenuPossibleExtras as $obj) {
                        if ($obj->isNew()) {
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
     * Sets a collection of ChildMenuPossibleExtra objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuPossibleExtras A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuExtra The current object (for fluent API support)
     */
    public function setMenuPossibleExtras(Collection $menuPossibleExtras, ConnectionInterface $con = null)
    {
        /** @var ChildMenuPossibleExtra[] $menuPossibleExtrasToDelete */
        $menuPossibleExtrasToDelete = $this->getMenuPossibleExtras(new Criteria(), $con)->diff($menuPossibleExtras);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuPossibleExtrasScheduledForDeletion = clone $menuPossibleExtrasToDelete;

        foreach ($menuPossibleExtrasToDelete as $menuPossibleExtraRemoved) {
            $menuPossibleExtraRemoved->setMenuExtra(null);
        }

        $this->collMenuPossibleExtras = null;
        foreach ($menuPossibleExtras as $menuPossibleExtra) {
            $this->addMenuPossibleExtra($menuPossibleExtra);
        }

        $this->collMenuPossibleExtras = $menuPossibleExtras;
        $this->collMenuPossibleExtrasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MenuPossibleExtra objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MenuPossibleExtra objects.
     * @throws PropelException
     */
    public function countMenuPossibleExtras(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuPossibleExtrasPartial && !$this->isNew();
        if (null === $this->collMenuPossibleExtras || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuPossibleExtras) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuPossibleExtras());
            }

            $query = ChildMenuPossibleExtraQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuExtra($this)
                ->count($con);
        }

        return count($this->collMenuPossibleExtras);
    }

    /**
     * Method called to associate a ChildMenuPossibleExtra object to this object
     * through the ChildMenuPossibleExtra foreign key attribute.
     *
     * @param  ChildMenuPossibleExtra $l ChildMenuPossibleExtra
     * @return $this|\API\Models\Menu\MenuExtra The current object (for fluent API support)
     */
    public function addMenuPossibleExtra(ChildMenuPossibleExtra $l)
    {
        if ($this->collMenuPossibleExtras === null) {
            $this->initMenuPossibleExtras();
            $this->collMenuPossibleExtrasPartial = true;
        }

        if (!$this->collMenuPossibleExtras->contains($l)) {
            $this->doAddMenuPossibleExtra($l);

            if ($this->menuPossibleExtrasScheduledForDeletion and $this->menuPossibleExtrasScheduledForDeletion->contains($l)) {
                $this->menuPossibleExtrasScheduledForDeletion->remove($this->menuPossibleExtrasScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenuPossibleExtra $menuPossibleExtra The ChildMenuPossibleExtra object to add.
     */
    protected function doAddMenuPossibleExtra(ChildMenuPossibleExtra $menuPossibleExtra)
    {
        $this->collMenuPossibleExtras[]= $menuPossibleExtra;
        $menuPossibleExtra->setMenuExtra($this);
    }

    /**
     * @param  ChildMenuPossibleExtra $menuPossibleExtra The ChildMenuPossibleExtra object to remove.
     * @return $this|ChildMenuExtra The current object (for fluent API support)
     */
    public function removeMenuPossibleExtra(ChildMenuPossibleExtra $menuPossibleExtra)
    {
        if ($this->getMenuPossibleExtras()->contains($menuPossibleExtra)) {
            $pos = $this->collMenuPossibleExtras->search($menuPossibleExtra);
            $this->collMenuPossibleExtras->remove($pos);
            if (null === $this->menuPossibleExtrasScheduledForDeletion) {
                $this->menuPossibleExtrasScheduledForDeletion = clone $this->collMenuPossibleExtras;
                $this->menuPossibleExtrasScheduledForDeletion->clear();
            }
            $this->menuPossibleExtrasScheduledForDeletion[]= clone $menuPossibleExtra;
            $menuPossibleExtra->setMenuExtra(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuExtra is new, it will return
     * an empty collection; or if this MenuExtra has previously
     * been saved, it will retrieve related MenuPossibleExtras from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuExtra.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenuPossibleExtra[] List of ChildMenuPossibleExtra objects
     */
    public function getMenuPossibleExtrasJoinMenu(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuPossibleExtraQuery::create(null, $criteria);
        $query->joinWith('Menu', $joinBehavior);

        return $this->getMenuPossibleExtras($query, $con);
    }

    /**
     * Clears out the collMenuMenuPossibleExtraids collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuMenuPossibleExtraids()
     */
    public function clearMenuMenuPossibleExtraids()
    {
        $this->collMenuMenuPossibleExtraids = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollMenuMenuPossibleExtraids crossRef collection.
     *
     * By default this just sets the combinationCollMenuMenuPossibleExtraids collection to an empty collection (like clearMenuMenuPossibleExtraids());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMenuMenuPossibleExtraids()
    {
        $this->combinationCollMenuMenuPossibleExtraids = new ObjectCombinationCollection;
        $this->combinationCollMenuMenuPossibleExtraidsPartial = true;
    }

    /**
     * Checks if the combinationCollMenuMenuPossibleExtraids collection is loaded.
     *
     * @return bool
     */
    public function isMenuMenuPossibleExtraidsLoaded()
    {
        return null !== $this->combinationCollMenuMenuPossibleExtraids;
    }

    /**
     * Returns a new query object pre configured with filters from current object and given arguments to query the database.
     *
     * @param int $menuPossibleExtraid
     * @param Criteria $criteria
     *
     * @return ChildMenuQuery
     */
    public function createMenusQuery($menuPossibleExtraid = null, Criteria $criteria = null)
    {
        $criteria = ChildMenuQuery::create($criteria)
            ->filterByMenuExtra($this);

        $menuPossibleExtraQuery = $criteria->useMenuPossibleExtraQuery();

        if (null !== $menuPossibleExtraid) {
            $menuPossibleExtraQuery->filterByMenuPossibleExtraid($menuPossibleExtraid);
        }

        $menuPossibleExtraQuery->endUse();

        return $criteria;
    }

    /**
     * Gets a combined collection of ChildMenu objects related by a many-to-many relationship
     * to the current object by way of the menu_possible_extra cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuExtra is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of ChildMenu objects
     */
    public function getMenuMenuPossibleExtraids($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollMenuMenuPossibleExtraidsPartial && !$this->isNew();
        if (null === $this->combinationCollMenuMenuPossibleExtraids || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollMenuMenuPossibleExtraids) {
                    $this->initMenuMenuPossibleExtraids();
                }
            } else {

                $query = ChildMenuPossibleExtraQuery::create(null, $criteria)
                    ->filterByMenuExtra($this)
                    ->joinMenu()
                ;

                $items = $query->find($con);
                $combinationCollMenuMenuPossibleExtraids = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getMenu();
                    $combination[] = $item->getMenuPossibleExtraid();
                    $combinationCollMenuMenuPossibleExtraids[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollMenuMenuPossibleExtraids;
                }

                if ($partial && $this->combinationCollMenuMenuPossibleExtraids) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollMenuMenuPossibleExtraids as $obj) {
                        if (!call_user_func_array([$combinationCollMenuMenuPossibleExtraids, 'contains'], $obj)) {
                            $combinationCollMenuMenuPossibleExtraids[] = $obj;
                        }
                    }
                }

                $this->combinationCollMenuMenuPossibleExtraids = $combinationCollMenuMenuPossibleExtraids;
                $this->combinationCollMenuMenuPossibleExtraidsPartial = false;
            }
        }

        return $this->combinationCollMenuMenuPossibleExtraids;
    }

    /**
     * Returns a not cached ObjectCollection of ChildMenu objects. This will hit always the databases.
     * If you have attached new ChildMenu object to this object you need to call `save` first to get
     * the correct return value. Use getMenuMenuPossibleExtraids() to get the current internal state.
     *
     * @param int $menuPossibleExtraid
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return ChildMenu[]|ObjectCollection
     */
    public function getMenus($menuPossibleExtraid = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createMenusQuery($menuPossibleExtraid, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildMenu objects related by a many-to-many relationship
     * to the current object by way of the menu_possible_extra cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $menuMenuPossibleExtraids A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuExtra The current object (for fluent API support)
     */
    public function setMenuMenuPossibleExtraids(Collection $menuMenuPossibleExtraids, ConnectionInterface $con = null)
    {
        $this->clearMenuMenuPossibleExtraids();
        $currentMenuMenuPossibleExtraids = $this->getMenuMenuPossibleExtraids();

        $combinationCollMenuMenuPossibleExtraidsScheduledForDeletion = $currentMenuMenuPossibleExtraids->diff($menuMenuPossibleExtraids);

        foreach ($combinationCollMenuMenuPossibleExtraidsScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeMenuMenuPossibleExtraid'], $toDelete);
        }

        foreach ($menuMenuPossibleExtraids as $menuMenuPossibleExtraid) {
            if (!call_user_func_array([$currentMenuMenuPossibleExtraids, 'contains'], $menuMenuPossibleExtraid)) {
                call_user_func_array([$this, 'doAddMenuMenuPossibleExtraid'], $menuMenuPossibleExtraid);
            }
        }

        $this->combinationCollMenuMenuPossibleExtraidsPartial = false;
        $this->combinationCollMenuMenuPossibleExtraids = $menuMenuPossibleExtraids;

        return $this;
    }

    /**
     * Gets the number of ChildMenu objects related by a many-to-many relationship
     * to the current object by way of the menu_possible_extra cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildMenu objects
     */
    public function countMenuMenuPossibleExtraids(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollMenuMenuPossibleExtraidsPartial && !$this->isNew();
        if (null === $this->combinationCollMenuMenuPossibleExtraids || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollMenuMenuPossibleExtraids) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getMenuMenuPossibleExtraids());
                }

                $query = ChildMenuPossibleExtraQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByMenuExtra($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollMenuMenuPossibleExtraids);
        }
    }

    /**
     * Returns the not cached count of ChildMenu objects. This will hit always the databases.
     * If you have attached new ChildMenu object to this object you need to call `save` first to get
     * the correct return value. Use getMenuMenuPossibleExtraids() to get the current internal state.
     *
     * @param int $menuPossibleExtraid
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countMenus($menuPossibleExtraid = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createMenusQuery($menuPossibleExtraid, $criteria)->count($con);
    }

    /**
     * Associate a ChildMenu to this object
     * through the menu_possible_extra cross reference table.
     *
     * @param ChildMenu $menu,
     * @param int $menuPossibleExtraid
     * @return ChildMenuExtra The current object (for fluent API support)
     */
    public function addMenu(ChildMenu $menu, $menuPossibleExtraid)
    {
        if ($this->combinationCollMenuMenuPossibleExtraids === null) {
            $this->initMenuMenuPossibleExtraids();
        }

        if (!$this->getMenuMenuPossibleExtraids()->contains($menu, $menuPossibleExtraid)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollMenuMenuPossibleExtraids->push($menu, $menuPossibleExtraid);
            $this->doAddMenuMenuPossibleExtraid($menu, $menuPossibleExtraid);
        }

        return $this;
    }

    /**
     *
     * @param ChildMenu $menu,
     * @param int $menuPossibleExtraid
     */
    protected function doAddMenuMenuPossibleExtraid(ChildMenu $menu, $menuPossibleExtraid)
    {
        $menuPossibleExtra = new ChildMenuPossibleExtra();

        $menuPossibleExtra->setMenu($menu);
        $menuPossibleExtra->setMenuPossibleExtraid($menuPossibleExtraid);


        $menuPossibleExtra->setMenuExtra($this);

        $this->addMenuPossibleExtra($menuPossibleExtra);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($menu->isMenuExtraMenuPossibleExtraidsLoaded()) {
            $menu->initMenuExtraMenuPossibleExtraids();
            $menu->getMenuExtraMenuPossibleExtraids()->push($this, $menuPossibleExtraid);
        } elseif (!$menu->getMenuExtraMenuPossibleExtraids()->contains($this, $menuPossibleExtraid)) {
            $menu->getMenuExtraMenuPossibleExtraids()->push($this, $menuPossibleExtraid);
        }

    }

    /**
     * Remove menu, menuPossibleExtraid of this object
     * through the menu_possible_extra cross reference table.
     *
     * @param ChildMenu $menu,
     * @param int $menuPossibleExtraid
     * @return ChildMenuExtra The current object (for fluent API support)
     */
    public function removeMenuMenuPossibleExtraid(ChildMenu $menu, $menuPossibleExtraid)
    {
        if ($this->getMenuMenuPossibleExtraids()->contains($menu, $menuPossibleExtraid)) { $menuPossibleExtra = new ChildMenuPossibleExtra();

            $menuPossibleExtra->setMenu($menu);
            if ($menu->isMenuExtraMenuPossibleExtraidsLoaded()) {
                //remove the back reference if available
                $menu->getMenuExtraMenuPossibleExtraids()->removeObject($this, $menuPossibleExtraid);
            }

            $menuPossibleExtra->setMenuPossibleExtraid($menuPossibleExtraid);
            $menuPossibleExtra->setMenuExtra($this);
            $this->removeMenuPossibleExtra(clone $menuPossibleExtra);
            $menuPossibleExtra->clear();

            $this->combinationCollMenuMenuPossibleExtraids->remove($this->combinationCollMenuMenuPossibleExtraids->search($menu, $menuPossibleExtraid));

            if (null === $this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion) {
                $this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion = clone $this->combinationCollMenuMenuPossibleExtraids;
                $this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion->clear();
            }

            $this->combinationCollMenuMenuPossibleExtraidsScheduledForDeletion->push($menu, $menuPossibleExtraid);
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
            $this->aAvailability->removeMenuExtra($this);
        }
        if (null !== $this->aEvent) {
            $this->aEvent->removeMenuExtra($this);
        }
        $this->menu_extraid = null;
        $this->eventid = null;
        $this->name = null;
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
            if ($this->collMenuPossibleExtras) {
                foreach ($this->collMenuPossibleExtras as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollMenuMenuPossibleExtraids) {
                foreach ($this->combinationCollMenuMenuPossibleExtraids as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMenuPossibleExtras = null;
        $this->combinationCollMenuMenuPossibleExtraids = null;
        $this->aAvailability = null;
        $this->aEvent = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MenuExtraTableMap::DEFAULT_STRING_FORMAT);
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
