<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlace as ChildDistributionPlace;
use API\Models\DistributionPlace\DistributionPlaceGroup as ChildDistributionPlaceGroup;
use API\Models\DistributionPlace\DistributionPlaceGroupQuery as ChildDistributionPlaceGroupQuery;
use API\Models\DistributionPlace\DistributionPlaceQuery as ChildDistributionPlaceQuery;
use API\Models\DistributionPlace\DistributionPlaceTable as ChildDistributionPlaceTable;
use API\Models\DistributionPlace\DistributionPlaceTableQuery as ChildDistributionPlaceTableQuery;
use API\Models\DistributionPlace\DistributionPlaceUser as ChildDistributionPlaceUser;
use API\Models\DistributionPlace\DistributionPlaceUserQuery as ChildDistributionPlaceUserQuery;
use API\Models\DistributionPlace\Map\DistributionPlaceGroupTableMap;
use API\Models\DistributionPlace\Map\DistributionPlaceTableMap;
use API\Models\DistributionPlace\Map\DistributionPlaceTableTableMap;
use API\Models\DistributionPlace\Map\DistributionPlaceUserTableMap;
use API\Models\Event\Event;
use API\Models\Event\EventPrinter;
use API\Models\Event\EventQuery;
use API\Models\Event\EventTable;
use API\Models\Menu\MenuGroup;
use API\Models\Menu\MenuGroupQuery;
use API\Models\User\User;
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
 * Base class that represents a row from the 'distribution_place' table.
 *
 *
 *
 * @package    propel.generator.API.Models.DistributionPlace.Base
 */
abstract class DistributionPlace implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\DistributionPlace\\Map\\DistributionPlaceTableMap';


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
     * The value for the distribution_placeid field.
     *
     * @var        int
     */
    protected $distribution_placeid;

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
     * @var        Event
     */
    protected $aEvent;

    /**
     * @var        ObjectCollection|ChildDistributionPlaceGroup[] Collection to store aggregation of ChildDistributionPlaceGroup objects.
     */
    protected $collDistributionPlaceGroups;
    protected $collDistributionPlaceGroupsPartial;

    /**
     * @var        ObjectCollection|ChildDistributionPlaceTable[] Collection to store aggregation of ChildDistributionPlaceTable objects.
     */
    protected $collDistributionPlaceTables;
    protected $collDistributionPlaceTablesPartial;

    /**
     * @var        ObjectCollection|ChildDistributionPlaceUser[] Collection to store aggregation of ChildDistributionPlaceUser objects.
     */
    protected $collDistributionPlaceUsers;
    protected $collDistributionPlaceUsersPartial;

    /**
     * @var        ObjectCollection|MenuGroup[] Cross Collection to store aggregation of MenuGroup objects.
     */
    protected $collMenuGroups;

    /**
     * @var bool
     */
    protected $collMenuGroupsPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildMenuGroup, ChildEventTable combination combinations.
     */
    protected $combinationCollMenuGroupEventTables;

    /**
     * @var bool
     */
    protected $combinationCollMenuGroupEventTablesPartial;

    /**
     * @var        ObjectCollection|MenuGroup[] Cross Collection to store aggregation of MenuGroup objects.
     */
    protected $collMenuGroups;

    /**
     * @var bool
     */
    protected $collMenuGroupsPartial;

    /**
     * @var        ObjectCollection|EventTable[] Cross Collection to store aggregation of EventTable objects.
     */
    protected $collEventTables;

    /**
     * @var bool
     */
    protected $collEventTablesPartial;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildUser, ChildEventPrinter combination combinations.
     */
    protected $combinationCollUserEventPrinters;

    /**
     * @var bool
     */
    protected $combinationCollUserEventPrintersPartial;

    /**
     * @var        ObjectCollection|User[] Cross Collection to store aggregation of User objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * @var        ObjectCollection|EventPrinter[] Cross Collection to store aggregation of EventPrinter objects.
     */
    protected $collEventPrinters;

    /**
     * @var bool
     */
    protected $collEventPrintersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|MenuGroup[]
     */
    protected $menuGroupsScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildMenuGroup, ChildEventTable combination combinations.
     */
    protected $combinationCollMenuGroupEventTablesScheduledForDeletion = null;

    /**
     * @var ObjectCombinationCollection Cross CombinationCollection to store aggregation of ChildUser, ChildEventPrinter combination combinations.
     */
    protected $combinationCollUserEventPrintersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDistributionPlaceGroup[]
     */
    protected $distributionPlaceGroupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDistributionPlaceTable[]
     */
    protected $distributionPlaceTablesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDistributionPlaceUser[]
     */
    protected $distributionPlaceUsersScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\DistributionPlace\Base\DistributionPlace object.
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
     * Compares this with another <code>DistributionPlace</code> instance.  If
     * <code>obj</code> is an instance of <code>DistributionPlace</code>, delegates to
     * <code>equals(DistributionPlace)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|DistributionPlace The current object, for fluid interface
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
     * Get the [distribution_placeid] column value.
     *
     * @return int
     */
    public function getDistributionPlaceid()
    {
        return $this->distribution_placeid;
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
     * Set the value of [distribution_placeid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
     */
    public function setDistributionPlaceid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->distribution_placeid !== $v) {
            $this->distribution_placeid = $v;
            $this->modifiedColumns[DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID] = true;
        }

        return $this;
    } // setDistributionPlaceid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[DistributionPlaceTableMap::COL_EVENTID] = true;
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
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[DistributionPlaceTableMap::COL_NAME] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : DistributionPlaceTableMap::translateFieldName('DistributionPlaceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->distribution_placeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : DistributionPlaceTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : DistributionPlaceTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = DistributionPlaceTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\DistributionPlace\\DistributionPlace'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildDistributionPlaceQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvent = null;
            $this->collDistributionPlaceGroups = null;

            $this->collDistributionPlaceTables = null;

            $this->collDistributionPlaceUsers = null;

            $this->collMenuGroups = null;
            $this->collMenuGroupEventTables = null;
            $this->collUserEventPrinters = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see DistributionPlace::setDeleted()
     * @see DistributionPlace::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildDistributionPlaceQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableMap::DATABASE_NAME);
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
                DistributionPlaceTableMap::addInstanceToPool($this);
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

            if ($this->menuGroupsScheduledForDeletion !== null) {
                if (!$this->menuGroupsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->menuGroupsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getDistributionPlaceid();
                        $entryPk[1] = $entry->getMenuGroupid();
                        $pks[] = $entryPk;
                    }

                    \API\Models\DistributionPlace\DistributionPlaceGroupQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->menuGroupsScheduledForDeletion = null;
                }

            }

            if ($this->collMenuGroups) {
                foreach ($this->collMenuGroups as $menuGroup) {
                    if (!$menuGroup->isDeleted() && ($menuGroup->isNew() || $menuGroup->isModified())) {
                        $menuGroup->save($con);
                    }
                }
            }


            if ($this->combinationCollMenuGroupEventTablesScheduledForDeletion !== null) {
                if (!$this->combinationCollMenuGroupEventTablesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollMenuGroupEventTablesScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[1] = $this->getDistributionPlaceid();
                        $entryPk[2] = $combination[0]->getMenuGroupid();
                        $entryPk[0] = $combination[1]->getEventTableid();

                        $pks[] = $entryPk;
                    }

                    \API\Models\DistributionPlace\DistributionPlaceTableQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollMenuGroupEventTablesScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollMenuGroupEventTables) {
                foreach ($this->combinationCollMenuGroupEventTables as $combination) {

                    //$combination[0] = MenuGroup (fk_distributions_places_tables_menu_groupes1)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = EventTable (fk_tables_has_distributions_places_tables1)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
            }


            if ($this->combinationCollUserEventPrintersScheduledForDeletion !== null) {
                if (!$this->combinationCollUserEventPrintersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->combinationCollUserEventPrintersScheduledForDeletion as $combination) {
                        $entryPk = [];

                        $entryPk[0] = $this->getDistributionPlaceid();
                        $entryPk[1] = $combination[0]->getUserid();
                        $entryPk[2] = $combination[1]->getEventPrinterid();

                        $pks[] = $entryPk;
                    }

                    \API\Models\DistributionPlace\DistributionPlaceUserQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->combinationCollUserEventPrintersScheduledForDeletion = null;
                }

            }

            if (null !== $this->combinationCollUserEventPrinters) {
                foreach ($this->combinationCollUserEventPrinters as $combination) {

                    //$combination[0] = User (fk_distributions_places_has_users_users1)
                    if (!$combination[0]->isDeleted() && ($combination[0]->isNew() || $combination[0]->isModified())) {
                        $combination[0]->save($con);
                    }

                    //$combination[1] = EventPrinter (fk_distributions_places_users_events_printers1)
                    if (!$combination[1]->isDeleted() && ($combination[1]->isNew() || $combination[1]->isModified())) {
                        $combination[1]->save($con);
                    }

                }
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

        $this->modifiedColumns[DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID] = true;
        if (null !== $this->distribution_placeid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID)) {
            $modifiedColumns[':p' . $index++]  = 'distribution_placeid';
        }
        if ($this->isColumnModified(DistributionPlaceTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(DistributionPlaceTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO distribution_place (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'distribution_placeid':
                        $stmt->bindValue($identifier, $this->distribution_placeid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
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
        $this->setDistributionPlaceid($pk);

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
        $pos = DistributionPlaceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDistributionPlaceid();
                break;
            case 1:
                return $this->getEventid();
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

        if (isset($alreadyDumpedObjects['DistributionPlace'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DistributionPlace'][$this->hashCode()] = true;
        $keys = DistributionPlaceTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getDistributionPlaceid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
     * @return $this|\API\Models\DistributionPlace\DistributionPlace
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DistributionPlaceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\DistributionPlace\DistributionPlace
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setDistributionPlaceid($value);
                break;
            case 1:
                $this->setEventid($value);
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
        $keys = DistributionPlaceTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setDistributionPlaceid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
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
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object, for fluid interface
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
        $criteria = new Criteria(DistributionPlaceTableMap::DATABASE_NAME);

        if ($this->isColumnModified(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID)) {
            $criteria->add(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $this->distribution_placeid);
        }
        if ($this->isColumnModified(DistributionPlaceTableMap::COL_EVENTID)) {
            $criteria->add(DistributionPlaceTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(DistributionPlaceTableMap::COL_NAME)) {
            $criteria->add(DistributionPlaceTableMap::COL_NAME, $this->name);
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
        $criteria = ChildDistributionPlaceQuery::create();
        $criteria->add(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $this->distribution_placeid);
        $criteria->add(DistributionPlaceTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getDistributionPlaceid() &&
            null !== $this->getEventid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_events_distribution_places_events1 to table event
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
        $pks[0] = $this->getDistributionPlaceid();
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
        $this->setDistributionPlaceid($keys[0]);
        $this->setEventid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getDistributionPlaceid()) && (null === $this->getEventid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\DistributionPlace\DistributionPlace (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
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

            foreach ($this->getDistributionPlaceUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionPlaceUser($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setDistributionPlaceid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\DistributionPlace\DistributionPlace Clone of current object.
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
     * Declares an association between this object and a Event object.
     *
     * @param  Event $v
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
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
            $v->addDistributionPlace($this);
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
                $this->aEvent->addDistributionPlaces($this);
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
        if ('DistributionPlaceGroup' == $relationName) {
            return $this->initDistributionPlaceGroups();
        }
        if ('DistributionPlaceTable' == $relationName) {
            return $this->initDistributionPlaceTables();
        }
        if ('DistributionPlaceUser' == $relationName) {
            return $this->initDistributionPlaceUsers();
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
     * Gets an array of ChildDistributionPlaceGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionPlace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDistributionPlaceGroup[] List of ChildDistributionPlaceGroup objects
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
                $collDistributionPlaceGroups = ChildDistributionPlaceGroupQuery::create(null, $criteria)
                    ->filterByDistributionPlace($this)
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
     * Sets a collection of ChildDistributionPlaceGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceGroups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function setDistributionPlaceGroups(Collection $distributionPlaceGroups, ConnectionInterface $con = null)
    {
        /** @var ChildDistributionPlaceGroup[] $distributionPlaceGroupsToDelete */
        $distributionPlaceGroupsToDelete = $this->getDistributionPlaceGroups(new Criteria(), $con)->diff($distributionPlaceGroups);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceGroupsScheduledForDeletion = clone $distributionPlaceGroupsToDelete;

        foreach ($distributionPlaceGroupsToDelete as $distributionPlaceGroupRemoved) {
            $distributionPlaceGroupRemoved->setDistributionPlace(null);
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
     * Returns the number of related DistributionPlaceGroup objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DistributionPlaceGroup objects.
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

            $query = ChildDistributionPlaceGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDistributionPlace($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceGroups);
    }

    /**
     * Method called to associate a ChildDistributionPlaceGroup object to this object
     * through the ChildDistributionPlaceGroup foreign key attribute.
     *
     * @param  ChildDistributionPlaceGroup $l ChildDistributionPlaceGroup
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
     */
    public function addDistributionPlaceGroup(ChildDistributionPlaceGroup $l)
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
     * @param ChildDistributionPlaceGroup $distributionPlaceGroup The ChildDistributionPlaceGroup object to add.
     */
    protected function doAddDistributionPlaceGroup(ChildDistributionPlaceGroup $distributionPlaceGroup)
    {
        $this->collDistributionPlaceGroups[]= $distributionPlaceGroup;
        $distributionPlaceGroup->setDistributionPlace($this);
    }

    /**
     * @param  ChildDistributionPlaceGroup $distributionPlaceGroup The ChildDistributionPlaceGroup object to remove.
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function removeDistributionPlaceGroup(ChildDistributionPlaceGroup $distributionPlaceGroup)
    {
        if ($this->getDistributionPlaceGroups()->contains($distributionPlaceGroup)) {
            $pos = $this->collDistributionPlaceGroups->search($distributionPlaceGroup);
            $this->collDistributionPlaceGroups->remove($pos);
            if (null === $this->distributionPlaceGroupsScheduledForDeletion) {
                $this->distributionPlaceGroupsScheduledForDeletion = clone $this->collDistributionPlaceGroups;
                $this->distributionPlaceGroupsScheduledForDeletion->clear();
            }
            $this->distributionPlaceGroupsScheduledForDeletion[]= clone $distributionPlaceGroup;
            $distributionPlaceGroup->setDistributionPlace(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionPlace is new, it will return
     * an empty collection; or if this DistributionPlace has previously
     * been saved, it will retrieve related DistributionPlaceGroups from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionPlace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionPlaceGroup[] List of ChildDistributionPlaceGroup objects
     */
    public function getDistributionPlaceGroupsJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionPlaceGroupQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

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
     * Gets an array of ChildDistributionPlaceTable objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionPlace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDistributionPlaceTable[] List of ChildDistributionPlaceTable objects
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
                $collDistributionPlaceTables = ChildDistributionPlaceTableQuery::create(null, $criteria)
                    ->filterByDistributionPlace($this)
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
     * Sets a collection of ChildDistributionPlaceTable objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceTables A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function setDistributionPlaceTables(Collection $distributionPlaceTables, ConnectionInterface $con = null)
    {
        /** @var ChildDistributionPlaceTable[] $distributionPlaceTablesToDelete */
        $distributionPlaceTablesToDelete = $this->getDistributionPlaceTables(new Criteria(), $con)->diff($distributionPlaceTables);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceTablesScheduledForDeletion = clone $distributionPlaceTablesToDelete;

        foreach ($distributionPlaceTablesToDelete as $distributionPlaceTableRemoved) {
            $distributionPlaceTableRemoved->setDistributionPlace(null);
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
     * Returns the number of related DistributionPlaceTable objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DistributionPlaceTable objects.
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

            $query = ChildDistributionPlaceTableQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDistributionPlace($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceTables);
    }

    /**
     * Method called to associate a ChildDistributionPlaceTable object to this object
     * through the ChildDistributionPlaceTable foreign key attribute.
     *
     * @param  ChildDistributionPlaceTable $l ChildDistributionPlaceTable
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
     */
    public function addDistributionPlaceTable(ChildDistributionPlaceTable $l)
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
     * @param ChildDistributionPlaceTable $distributionPlaceTable The ChildDistributionPlaceTable object to add.
     */
    protected function doAddDistributionPlaceTable(ChildDistributionPlaceTable $distributionPlaceTable)
    {
        $this->collDistributionPlaceTables[]= $distributionPlaceTable;
        $distributionPlaceTable->setDistributionPlace($this);
    }

    /**
     * @param  ChildDistributionPlaceTable $distributionPlaceTable The ChildDistributionPlaceTable object to remove.
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function removeDistributionPlaceTable(ChildDistributionPlaceTable $distributionPlaceTable)
    {
        if ($this->getDistributionPlaceTables()->contains($distributionPlaceTable)) {
            $pos = $this->collDistributionPlaceTables->search($distributionPlaceTable);
            $this->collDistributionPlaceTables->remove($pos);
            if (null === $this->distributionPlaceTablesScheduledForDeletion) {
                $this->distributionPlaceTablesScheduledForDeletion = clone $this->collDistributionPlaceTables;
                $this->distributionPlaceTablesScheduledForDeletion->clear();
            }
            $this->distributionPlaceTablesScheduledForDeletion[]= clone $distributionPlaceTable;
            $distributionPlaceTable->setDistributionPlace(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionPlace is new, it will return
     * an empty collection; or if this DistributionPlace has previously
     * been saved, it will retrieve related DistributionPlaceTables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionPlace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionPlaceTable[] List of ChildDistributionPlaceTable objects
     */
    public function getDistributionPlaceTablesJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionPlaceTableQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

        return $this->getDistributionPlaceTables($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionPlace is new, it will return
     * an empty collection; or if this DistributionPlace has previously
     * been saved, it will retrieve related DistributionPlaceTables from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionPlace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionPlaceTable[] List of ChildDistributionPlaceTable objects
     */
    public function getDistributionPlaceTablesJoinEventTable(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionPlaceTableQuery::create(null, $criteria);
        $query->joinWith('EventTable', $joinBehavior);

        return $this->getDistributionPlaceTables($query, $con);
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
     * Gets an array of ChildDistributionPlaceUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionPlace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDistributionPlaceUser[] List of ChildDistributionPlaceUser objects
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
                $collDistributionPlaceUsers = ChildDistributionPlaceUserQuery::create(null, $criteria)
                    ->filterByDistributionPlace($this)
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
     * Sets a collection of ChildDistributionPlaceUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionPlaceUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function setDistributionPlaceUsers(Collection $distributionPlaceUsers, ConnectionInterface $con = null)
    {
        /** @var ChildDistributionPlaceUser[] $distributionPlaceUsersToDelete */
        $distributionPlaceUsersToDelete = $this->getDistributionPlaceUsers(new Criteria(), $con)->diff($distributionPlaceUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionPlaceUsersScheduledForDeletion = clone $distributionPlaceUsersToDelete;

        foreach ($distributionPlaceUsersToDelete as $distributionPlaceUserRemoved) {
            $distributionPlaceUserRemoved->setDistributionPlace(null);
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
     * Returns the number of related DistributionPlaceUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DistributionPlaceUser objects.
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

            $query = ChildDistributionPlaceUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDistributionPlace($this)
                ->count($con);
        }

        return count($this->collDistributionPlaceUsers);
    }

    /**
     * Method called to associate a ChildDistributionPlaceUser object to this object
     * through the ChildDistributionPlaceUser foreign key attribute.
     *
     * @param  ChildDistributionPlaceUser $l ChildDistributionPlaceUser
     * @return $this|\API\Models\DistributionPlace\DistributionPlace The current object (for fluent API support)
     */
    public function addDistributionPlaceUser(ChildDistributionPlaceUser $l)
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
     * @param ChildDistributionPlaceUser $distributionPlaceUser The ChildDistributionPlaceUser object to add.
     */
    protected function doAddDistributionPlaceUser(ChildDistributionPlaceUser $distributionPlaceUser)
    {
        $this->collDistributionPlaceUsers[]= $distributionPlaceUser;
        $distributionPlaceUser->setDistributionPlace($this);
    }

    /**
     * @param  ChildDistributionPlaceUser $distributionPlaceUser The ChildDistributionPlaceUser object to remove.
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function removeDistributionPlaceUser(ChildDistributionPlaceUser $distributionPlaceUser)
    {
        if ($this->getDistributionPlaceUsers()->contains($distributionPlaceUser)) {
            $pos = $this->collDistributionPlaceUsers->search($distributionPlaceUser);
            $this->collDistributionPlaceUsers->remove($pos);
            if (null === $this->distributionPlaceUsersScheduledForDeletion) {
                $this->distributionPlaceUsersScheduledForDeletion = clone $this->collDistributionPlaceUsers;
                $this->distributionPlaceUsersScheduledForDeletion->clear();
            }
            $this->distributionPlaceUsersScheduledForDeletion[]= clone $distributionPlaceUser;
            $distributionPlaceUser->setDistributionPlace(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionPlace is new, it will return
     * an empty collection; or if this DistributionPlace has previously
     * been saved, it will retrieve related DistributionPlaceUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionPlace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionPlaceUser[] List of ChildDistributionPlaceUser objects
     */
    public function getDistributionPlaceUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionPlaceUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getDistributionPlaceUsers($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionPlace is new, it will return
     * an empty collection; or if this DistributionPlace has previously
     * been saved, it will retrieve related DistributionPlaceUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionPlace.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionPlaceUser[] List of ChildDistributionPlaceUser objects
     */
    public function getDistributionPlaceUsersJoinEventPrinter(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionPlaceUserQuery::create(null, $criteria);
        $query->joinWith('EventPrinter', $joinBehavior);

        return $this->getDistributionPlaceUsers($query, $con);
    }

    /**
     * Clears out the collMenuGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuGroups()
     */
    public function clearMenuGroups()
    {
        $this->collMenuGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collMenuGroups crossRef collection.
     *
     * By default this just sets the collMenuGroups collection to an empty collection (like clearMenuGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMenuGroups()
    {
        $collectionClassName = DistributionPlaceGroupTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuGroups = new $collectionClassName;
        $this->collMenuGroupsPartial = true;
        $this->collMenuGroups->setModel('\API\Models\Menu\MenuGroup');
    }

    /**
     * Checks if the collMenuGroups collection is loaded.
     *
     * @return bool
     */
    public function isMenuGroupsLoaded()
    {
        return null !== $this->collMenuGroups;
    }

    /**
     * Gets a collection of MenuGroup objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_group cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionPlace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|MenuGroup[] List of MenuGroup objects
     */
    public function getMenuGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuGroupsPartial && !$this->isNew();
        if (null === $this->collMenuGroups || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMenuGroups) {
                    $this->initMenuGroups();
                }
            } else {

                $query = MenuGroupQuery::create(null, $criteria)
                    ->filterByDistributionPlace($this);
                $collMenuGroups = $query->find($con);
                if (null !== $criteria) {
                    return $collMenuGroups;
                }

                if ($partial && $this->collMenuGroups) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collMenuGroups as $obj) {
                        if (!$collMenuGroups->contains($obj)) {
                            $collMenuGroups[] = $obj;
                        }
                    }
                }

                $this->collMenuGroups = $collMenuGroups;
                $this->collMenuGroupsPartial = false;
            }
        }

        return $this->collMenuGroups;
    }

    /**
     * Sets a collection of MenuGroup objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_group cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $menuGroups A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function setMenuGroups(Collection $menuGroups, ConnectionInterface $con = null)
    {
        $this->clearMenuGroups();
        $currentMenuGroups = $this->getMenuGroups();

        $menuGroupsScheduledForDeletion = $currentMenuGroups->diff($menuGroups);

        foreach ($menuGroupsScheduledForDeletion as $toDelete) {
            $this->removeMenuGroup($toDelete);
        }

        foreach ($menuGroups as $menuGroup) {
            if (!$currentMenuGroups->contains($menuGroup)) {
                $this->doAddMenuGroup($menuGroup);
            }
        }

        $this->collMenuGroupsPartial = false;
        $this->collMenuGroups = $menuGroups;

        return $this;
    }

    /**
     * Gets the number of MenuGroup objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_group cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related MenuGroup objects
     */
    public function countMenuGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuGroupsPartial && !$this->isNew();
        if (null === $this->collMenuGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuGroups) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getMenuGroups());
                }

                $query = MenuGroupQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByDistributionPlace($this)
                    ->count($con);
            }
        } else {
            return count($this->collMenuGroups);
        }
    }

    /**
     * Associate a MenuGroup to this object
     * through the distribution_place_group cross reference table.
     *
     * @param MenuGroup $menuGroup
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function addMenuGroup(MenuGroup $menuGroup)
    {
        if ($this->collMenuGroups === null) {
            $this->initMenuGroups();
        }

        if (!$this->getMenuGroups()->contains($menuGroup)) {
            // only add it if the **same** object is not already associated
            $this->collMenuGroups->push($menuGroup);
            $this->doAddMenuGroup($menuGroup);
        }

        return $this;
    }

    /**
     *
     * @param MenuGroup $menuGroup
     */
    protected function doAddMenuGroup(MenuGroup $menuGroup)
    {
        $distributionPlaceGroup = new ChildDistributionPlaceGroup();

        $distributionPlaceGroup->setMenuGroup($menuGroup);

        $distributionPlaceGroup->setDistributionPlace($this);

        $this->addDistributionPlaceGroup($distributionPlaceGroup);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$menuGroup->isDistributionPlacesLoaded()) {
            $menuGroup->initDistributionPlaces();
            $menuGroup->getDistributionPlaces()->push($this);
        } elseif (!$menuGroup->getDistributionPlaces()->contains($this)) {
            $menuGroup->getDistributionPlaces()->push($this);
        }

    }

    /**
     * Remove menuGroup of this object
     * through the distribution_place_group cross reference table.
     *
     * @param MenuGroup $menuGroup
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function removeMenuGroup(MenuGroup $menuGroup)
    {
        if ($this->getMenuGroups()->contains($menuGroup)) { $distributionPlaceGroup = new ChildDistributionPlaceGroup();

            $distributionPlaceGroup->setMenuGroup($menuGroup);
            if ($menuGroup->isDistributionPlacesLoaded()) {
                //remove the back reference if available
                $menuGroup->getDistributionPlaces()->removeObject($this);
            }

            $distributionPlaceGroup->setDistributionPlace($this);
            $this->removeDistributionPlaceGroup(clone $distributionPlaceGroup);
            $distributionPlaceGroup->clear();

            $this->collMenuGroups->remove($this->collMenuGroups->search($menuGroup));

            if (null === $this->menuGroupsScheduledForDeletion) {
                $this->menuGroupsScheduledForDeletion = clone $this->collMenuGroups;
                $this->menuGroupsScheduledForDeletion->clear();
            }

            $this->menuGroupsScheduledForDeletion->push($menuGroup);
        }


        return $this;
    }

    /**
     * Clears out the collMenuGroupEventTables collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuGroupEventTables()
     */
    public function clearMenuGroupEventTables()
    {
        $this->collMenuGroupEventTables = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollMenuGroupEventTables crossRef collection.
     *
     * By default this just sets the combinationCollMenuGroupEventTables collection to an empty collection (like clearMenuGroupEventTables());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMenuGroupEventTables()
    {
        $this->combinationCollMenuGroupEventTables = new ObjectCombinationCollection;
        $this->combinationCollMenuGroupEventTablesPartial = true;
    }

    /**
     * Checks if the combinationCollMenuGroupEventTables collection is loaded.
     *
     * @return bool
     */
    public function isMenuGroupEventTablesLoaded()
    {
        return null !== $this->combinationCollMenuGroupEventTables;
    }

    /**
     * Gets a combined collection of MenuGroup, EventTable objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_table cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionPlace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of MenuGroup, EventTable objects
     */
    public function getMenuGroupEventTables($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollMenuGroupEventTablesPartial && !$this->isNew();
        if (null === $this->combinationCollMenuGroupEventTables || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollMenuGroupEventTables) {
                    $this->initMenuGroupEventTables();
                }
            } else {

                $query = ChildDistributionPlaceTableQuery::create(null, $criteria)
                    ->filterByDistributionPlace($this)
                    ->joinMenuGroup()
                    ->joinEventTable()
                ;

                $items = $query->find($con);
                $combinationCollMenuGroupEventTables = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getMenuGroup();
                    $combination[] = $item->getEventTable();
                    $combinationCollMenuGroupEventTables[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollMenuGroupEventTables;
                }

                if ($partial && $this->combinationCollMenuGroupEventTables) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollMenuGroupEventTables as $obj) {
                        if (!call_user_func_array([$combinationCollMenuGroupEventTables, 'contains'], $obj)) {
                            $combinationCollMenuGroupEventTables[] = $obj;
                        }
                    }
                }

                $this->combinationCollMenuGroupEventTables = $combinationCollMenuGroupEventTables;
                $this->combinationCollMenuGroupEventTablesPartial = false;
            }
        }

        return $this->combinationCollMenuGroupEventTables;
    }

    /**
     * Returns a not cached ObjectCollection of MenuGroup objects. This will hit always the databases.
     * If you have attached new MenuGroup object to this object you need to call `save` first to get
     * the correct return value. Use getMenuGroupEventTables() to get the current internal state.
     *
     * @param EventTable $eventTable
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return MenuGroup[]|ObjectCollection
     */
    public function getMenuGroups(EventTable $eventTable = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createMenuGroupsQuery($eventTable, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildMenuGroup, ChildEventTable combination objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_table cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $menuGroupEventTables A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function setMenuGroupEventTables(Collection $menuGroupEventTables, ConnectionInterface $con = null)
    {
        $this->clearMenuGroupEventTables();
        $currentMenuGroupEventTables = $this->getMenuGroupEventTables();

        $combinationCollMenuGroupEventTablesScheduledForDeletion = $currentMenuGroupEventTables->diff($menuGroupEventTables);

        foreach ($combinationCollMenuGroupEventTablesScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeMenuGroupEventTable'], $toDelete);
        }

        foreach ($menuGroupEventTables as $menuGroupEventTable) {
            if (!call_user_func_array([$currentMenuGroupEventTables, 'contains'], $menuGroupEventTable)) {
                call_user_func_array([$this, 'doAddMenuGroupEventTable'], $menuGroupEventTable);
            }
        }

        $this->combinationCollMenuGroupEventTablesPartial = false;
        $this->combinationCollMenuGroupEventTables = $menuGroupEventTables;

        return $this;
    }

    /**
     * Gets the number of ChildMenuGroup, ChildEventTable combination objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_table cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildMenuGroup, ChildEventTable combination objects
     */
    public function countMenuGroupEventTables(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollMenuGroupEventTablesPartial && !$this->isNew();
        if (null === $this->combinationCollMenuGroupEventTables || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollMenuGroupEventTables) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getMenuGroupEventTables());
                }

                $query = ChildDistributionPlaceTableQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByDistributionPlace($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollMenuGroupEventTables);
        }
    }

    /**
     * Returns the not cached count of MenuGroup objects. This will hit always the databases.
     * If you have attached new MenuGroup object to this object you need to call `save` first to get
     * the correct return value. Use getMenuGroupEventTables() to get the current internal state.
     *
     * @param EventTable $eventTable
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countMenuGroups(EventTable $eventTable = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createMenuGroupsQuery($eventTable, $criteria)->count($con);
    }

    /**
     * Associate a MenuGroup to this object
     * through the distribution_place_table cross reference table.
     *
     * @param MenuGroup $menuGroup,
     * @param EventTable $eventTable
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function addMenuGroup(MenuGroup $menuGroup, EventTable $eventTable)
    {
        if ($this->combinationCollMenuGroupEventTables === null) {
            $this->initMenuGroupEventTables();
        }

        if (!$this->getMenuGroupEventTables()->contains($menuGroup, $eventTable)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollMenuGroupEventTables->push($menuGroup, $eventTable);
            $this->doAddMenuGroupEventTable($menuGroup, $eventTable);
        }

        return $this;
    }

    /**
     * Associate a EventTable to this object
     * through the distribution_place_table cross reference table.
     *
     * @param EventTable $eventTable,
     * @param MenuGroup $menuGroup
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function addEventTable(EventTable $eventTable, MenuGroup $menuGroup)
    {
        if ($this->combinationCollMenuGroupEventTables === null) {
            $this->initMenuGroupEventTables();
        }

        if (!$this->getMenuGroupEventTables()->contains($eventTable, $menuGroup)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollMenuGroupEventTables->push($eventTable, $menuGroup);
            $this->doAddMenuGroupEventTable($eventTable, $menuGroup);
        }

        return $this;
    }

    /**
     *
     * @param MenuGroup $menuGroup,
     * @param EventTable $eventTable
     */
    protected function doAddMenuGroupEventTable(MenuGroup $menuGroup, EventTable $eventTable)
    {
        $distributionPlaceTable = new ChildDistributionPlaceTable();

        $distributionPlaceTable->setMenuGroup($menuGroup);
        $distributionPlaceTable->setEventTable($eventTable);

        $distributionPlaceTable->setDistributionPlace($this);

        $this->addDistributionPlaceTable($distributionPlaceTable);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($menuGroup->isDistributionPlaceEventTablesLoaded()) {
            $menuGroup->initDistributionPlaceEventTables();
            $menuGroup->getDistributionPlaceEventTables()->push($this, $eventTable);
        } elseif (!$menuGroup->getDistributionPlaceEventTables()->contains($this, $eventTable)) {
            $menuGroup->getDistributionPlaceEventTables()->push($this, $eventTable);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($eventTable->isMenuGroupDistributionPlacesLoaded()) {
            $eventTable->initMenuGroupDistributionPlaces();
            $eventTable->getMenuGroupDistributionPlaces()->push($menuGroup, $this);
        } elseif (!$eventTable->getMenuGroupDistributionPlaces()->contains($menuGroup, $this)) {
            $eventTable->getMenuGroupDistributionPlaces()->push($menuGroup, $this);
        }

    }

    /**
     * Remove menuGroup, eventTable of this object
     * through the distribution_place_table cross reference table.
     *
     * @param MenuGroup $menuGroup,
     * @param EventTable $eventTable
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function removeMenuGroupEventTable(MenuGroup $menuGroup, EventTable $eventTable)
    {
        if ($this->getMenuGroupEventTables()->contains($menuGroup, $eventTable)) { $distributionPlaceTable = new ChildDistributionPlaceTable();

            $distributionPlaceTable->setMenuGroup($menuGroup);
            if ($menuGroup->isDistributionPlaceEventTablesLoaded()) {
                //remove the back reference if available
                $menuGroup->getDistributionPlaceEventTables()->removeObject($this, $eventTable);
            }

            $distributionPlaceTable->setEventTable($eventTable);
            if ($eventTable->isMenuGroupDistributionPlacesLoaded()) {
                //remove the back reference if available
                $eventTable->getMenuGroupDistributionPlaces()->removeObject($menuGroup, $this);
            }

            $distributionPlaceTable->setDistributionPlace($this);
            $this->removeDistributionPlaceTable(clone $distributionPlaceTable);
            $distributionPlaceTable->clear();

            $this->combinationCollMenuGroupEventTables->remove($this->combinationCollMenuGroupEventTables->search($menuGroup, $eventTable));

            if (null === $this->combinationCollMenuGroupEventTablesScheduledForDeletion) {
                $this->combinationCollMenuGroupEventTablesScheduledForDeletion = clone $this->combinationCollMenuGroupEventTables;
                $this->combinationCollMenuGroupEventTablesScheduledForDeletion->clear();
            }

            $this->combinationCollMenuGroupEventTablesScheduledForDeletion->push($menuGroup, $eventTable);
        }


        return $this;
    }

    /**
     * Clears out the collUserEventPrinters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserEventPrinters()
     */
    public function clearUserEventPrinters()
    {
        $this->collUserEventPrinters = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the combinationCollUserEventPrinters crossRef collection.
     *
     * By default this just sets the combinationCollUserEventPrinters collection to an empty collection (like clearUserEventPrinters());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUserEventPrinters()
    {
        $this->combinationCollUserEventPrinters = new ObjectCombinationCollection;
        $this->combinationCollUserEventPrintersPartial = true;
    }

    /**
     * Checks if the combinationCollUserEventPrinters collection is loaded.
     *
     * @return bool
     */
    public function isUserEventPrintersLoaded()
    {
        return null !== $this->combinationCollUserEventPrinters;
    }

    /**
     * Gets a combined collection of User, EventPrinter objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_user cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionPlace is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCombinationCollection Combination list of User, EventPrinter objects
     */
    public function getUserEventPrinters($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollUserEventPrintersPartial && !$this->isNew();
        if (null === $this->combinationCollUserEventPrinters || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->combinationCollUserEventPrinters) {
                    $this->initUserEventPrinters();
                }
            } else {

                $query = ChildDistributionPlaceUserQuery::create(null, $criteria)
                    ->filterByDistributionPlace($this)
                    ->joinUser()
                    ->joinEventPrinter()
                ;

                $items = $query->find($con);
                $combinationCollUserEventPrinters = new ObjectCombinationCollection();
                foreach ($items as $item) {
                    $combination = [];

                    $combination[] = $item->getUser();
                    $combination[] = $item->getEventPrinter();
                    $combinationCollUserEventPrinters[] = $combination;
                }

                if (null !== $criteria) {
                    return $combinationCollUserEventPrinters;
                }

                if ($partial && $this->combinationCollUserEventPrinters) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->combinationCollUserEventPrinters as $obj) {
                        if (!call_user_func_array([$combinationCollUserEventPrinters, 'contains'], $obj)) {
                            $combinationCollUserEventPrinters[] = $obj;
                        }
                    }
                }

                $this->combinationCollUserEventPrinters = $combinationCollUserEventPrinters;
                $this->combinationCollUserEventPrintersPartial = false;
            }
        }

        return $this->combinationCollUserEventPrinters;
    }

    /**
     * Returns a not cached ObjectCollection of User objects. This will hit always the databases.
     * If you have attached new User object to this object you need to call `save` first to get
     * the correct return value. Use getUserEventPrinters() to get the current internal state.
     *
     * @param EventPrinter $eventPrinter
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return User[]|ObjectCollection
     */
    public function getUsers(EventPrinter $eventPrinter = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createUsersQuery($eventPrinter, $criteria)->find($con);
    }

    /**
     * Sets a collection of ChildUser, ChildEventPrinter combination objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_user cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $userEventPrinters A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionPlace The current object (for fluent API support)
     */
    public function setUserEventPrinters(Collection $userEventPrinters, ConnectionInterface $con = null)
    {
        $this->clearUserEventPrinters();
        $currentUserEventPrinters = $this->getUserEventPrinters();

        $combinationCollUserEventPrintersScheduledForDeletion = $currentUserEventPrinters->diff($userEventPrinters);

        foreach ($combinationCollUserEventPrintersScheduledForDeletion as $toDelete) {
            call_user_func_array([$this, 'removeUserEventPrinter'], $toDelete);
        }

        foreach ($userEventPrinters as $userEventPrinter) {
            if (!call_user_func_array([$currentUserEventPrinters, 'contains'], $userEventPrinter)) {
                call_user_func_array([$this, 'doAddUserEventPrinter'], $userEventPrinter);
            }
        }

        $this->combinationCollUserEventPrintersPartial = false;
        $this->combinationCollUserEventPrinters = $userEventPrinters;

        return $this;
    }

    /**
     * Gets the number of ChildUser, ChildEventPrinter combination objects related by a many-to-many relationship
     * to the current object by way of the distribution_place_user cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related ChildUser, ChildEventPrinter combination objects
     */
    public function countUserEventPrinters(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->combinationCollUserEventPrintersPartial && !$this->isNew();
        if (null === $this->combinationCollUserEventPrinters || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->combinationCollUserEventPrinters) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUserEventPrinters());
                }

                $query = ChildDistributionPlaceUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByDistributionPlace($this)
                    ->count($con);
            }
        } else {
            return count($this->combinationCollUserEventPrinters);
        }
    }

    /**
     * Returns the not cached count of User objects. This will hit always the databases.
     * If you have attached new User object to this object you need to call `save` first to get
     * the correct return value. Use getUserEventPrinters() to get the current internal state.
     *
     * @param EventPrinter $eventPrinter
     * @param Criteria $criteria
     * @param ConnectionInterface $con
     *
     * @return integer
     */
    public function countUsers(EventPrinter $eventPrinter = null, Criteria $criteria = null, ConnectionInterface $con = null)
    {
        return $this->createUsersQuery($eventPrinter, $criteria)->count($con);
    }

    /**
     * Associate a User to this object
     * through the distribution_place_user cross reference table.
     *
     * @param User $user,
     * @param EventPrinter $eventPrinter
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function addUser(User $user, EventPrinter $eventPrinter)
    {
        if ($this->combinationCollUserEventPrinters === null) {
            $this->initUserEventPrinters();
        }

        if (!$this->getUserEventPrinters()->contains($user, $eventPrinter)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollUserEventPrinters->push($user, $eventPrinter);
            $this->doAddUserEventPrinter($user, $eventPrinter);
        }

        return $this;
    }

    /**
     * Associate a EventPrinter to this object
     * through the distribution_place_user cross reference table.
     *
     * @param EventPrinter $eventPrinter,
     * @param User $user
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function addEventPrinter(EventPrinter $eventPrinter, User $user)
    {
        if ($this->combinationCollUserEventPrinters === null) {
            $this->initUserEventPrinters();
        }

        if (!$this->getUserEventPrinters()->contains($eventPrinter, $user)) {
            // only add it if the **same** object is not already associated
            $this->combinationCollUserEventPrinters->push($eventPrinter, $user);
            $this->doAddUserEventPrinter($eventPrinter, $user);
        }

        return $this;
    }

    /**
     *
     * @param User $user,
     * @param EventPrinter $eventPrinter
     */
    protected function doAddUserEventPrinter(User $user, EventPrinter $eventPrinter)
    {
        $distributionPlaceUser = new ChildDistributionPlaceUser();

        $distributionPlaceUser->setUser($user);
        $distributionPlaceUser->setEventPrinter($eventPrinter);

        $distributionPlaceUser->setDistributionPlace($this);

        $this->addDistributionPlaceUser($distributionPlaceUser);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($user->isDistributionPlaceEventPrintersLoaded()) {
            $user->initDistributionPlaceEventPrinters();
            $user->getDistributionPlaceEventPrinters()->push($this, $eventPrinter);
        } elseif (!$user->getDistributionPlaceEventPrinters()->contains($this, $eventPrinter)) {
            $user->getDistributionPlaceEventPrinters()->push($this, $eventPrinter);
        }

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if ($eventPrinter->isDistributionPlaceUsersLoaded()) {
            $eventPrinter->initDistributionPlaceUsers();
            $eventPrinter->getDistributionPlaceUsers()->push($this, $user);
        } elseif (!$eventPrinter->getDistributionPlaceUsers()->contains($this, $user)) {
            $eventPrinter->getDistributionPlaceUsers()->push($this, $user);
        }

    }

    /**
     * Remove user, eventPrinter of this object
     * through the distribution_place_user cross reference table.
     *
     * @param User $user,
     * @param EventPrinter $eventPrinter
     * @return ChildDistributionPlace The current object (for fluent API support)
     */
    public function removeUserEventPrinter(User $user, EventPrinter $eventPrinter)
    {
        if ($this->getUserEventPrinters()->contains($user, $eventPrinter)) { $distributionPlaceUser = new ChildDistributionPlaceUser();

            $distributionPlaceUser->setUser($user);
            if ($user->isDistributionPlaceEventPrintersLoaded()) {
                //remove the back reference if available
                $user->getDistributionPlaceEventPrinters()->removeObject($this, $eventPrinter);
            }

            $distributionPlaceUser->setEventPrinter($eventPrinter);
            if ($eventPrinter->isDistributionPlaceUsersLoaded()) {
                //remove the back reference if available
                $eventPrinter->getDistributionPlaceUsers()->removeObject($this, $user);
            }

            $distributionPlaceUser->setDistributionPlace($this);
            $this->removeDistributionPlaceUser(clone $distributionPlaceUser);
            $distributionPlaceUser->clear();

            $this->combinationCollUserEventPrinters->remove($this->combinationCollUserEventPrinters->search($user, $eventPrinter));

            if (null === $this->combinationCollUserEventPrintersScheduledForDeletion) {
                $this->combinationCollUserEventPrintersScheduledForDeletion = clone $this->combinationCollUserEventPrinters;
                $this->combinationCollUserEventPrintersScheduledForDeletion->clear();
            }

            $this->combinationCollUserEventPrintersScheduledForDeletion->push($user, $eventPrinter);
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
        if (null !== $this->aEvent) {
            $this->aEvent->removeDistributionPlace($this);
        }
        $this->distribution_placeid = null;
        $this->eventid = null;
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
            if ($this->collDistributionPlaceUsers) {
                foreach ($this->collDistributionPlaceUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMenuGroups) {
                foreach ($this->collMenuGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollMenuGroupEventTables) {
                foreach ($this->combinationCollMenuGroupEventTables as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->combinationCollUserEventPrinters) {
                foreach ($this->combinationCollUserEventPrinters as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collDistributionPlaceGroups = null;
        $this->collDistributionPlaceTables = null;
        $this->collDistributionPlaceUsers = null;
        $this->collMenuGroups = null;
        $this->combinationCollMenuGroupEventTables = null;
        $this->combinationCollUserEventPrinters = null;
        $this->aEvent = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DistributionPlaceTableMap::DEFAULT_STRING_FORMAT);
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
