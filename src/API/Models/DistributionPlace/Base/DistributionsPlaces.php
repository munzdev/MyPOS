<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlaces as ChildDistributionsPlaces;
use API\Models\DistributionPlace\DistributionsPlacesGroupes as ChildDistributionsPlacesGroupes;
use API\Models\DistributionPlace\DistributionsPlacesGroupesQuery as ChildDistributionsPlacesGroupesQuery;
use API\Models\DistributionPlace\DistributionsPlacesQuery as ChildDistributionsPlacesQuery;
use API\Models\DistributionPlace\DistributionsPlacesTables as ChildDistributionsPlacesTables;
use API\Models\DistributionPlace\DistributionsPlacesTablesQuery as ChildDistributionsPlacesTablesQuery;
use API\Models\DistributionPlace\DistributionsPlacesUsers as ChildDistributionsPlacesUsers;
use API\Models\DistributionPlace\DistributionsPlacesUsersQuery as ChildDistributionsPlacesUsersQuery;
use API\Models\DistributionPlace\Map\DistributionsPlacesGroupesTableMap;
use API\Models\DistributionPlace\Map\DistributionsPlacesTableMap;
use API\Models\DistributionPlace\Map\DistributionsPlacesTablesTableMap;
use API\Models\DistributionPlace\Map\DistributionsPlacesUsersTableMap;
use API\Models\Event\Events;
use API\Models\Event\EventsQuery;
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
 * Base class that represents a row from the 'distributions_places' table.
 *
 *
 *
 * @package    propel.generator.API.Models.DistributionPlace.Base
 */
abstract class DistributionsPlaces implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\DistributionPlace\\Map\\DistributionsPlacesTableMap';


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
     * The value for the distributions_placeid field.
     *
     * @var        int
     */
    protected $distributions_placeid;

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
     * @var        Events
     */
    protected $aEvents;

    /**
     * @var        ObjectCollection|ChildDistributionsPlacesGroupes[] Collection to store aggregation of ChildDistributionsPlacesGroupes objects.
     */
    protected $collDistributionsPlacesGroupess;
    protected $collDistributionsPlacesGroupessPartial;

    /**
     * @var        ObjectCollection|ChildDistributionsPlacesTables[] Collection to store aggregation of ChildDistributionsPlacesTables objects.
     */
    protected $collDistributionsPlacesTabless;
    protected $collDistributionsPlacesTablessPartial;

    /**
     * @var        ObjectCollection|ChildDistributionsPlacesUsers[] Collection to store aggregation of ChildDistributionsPlacesUsers objects.
     */
    protected $collDistributionsPlacesUserss;
    protected $collDistributionsPlacesUserssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDistributionsPlacesGroupes[]
     */
    protected $distributionsPlacesGroupessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDistributionsPlacesTables[]
     */
    protected $distributionsPlacesTablessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDistributionsPlacesUsers[]
     */
    protected $distributionsPlacesUserssScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\DistributionPlace\Base\DistributionsPlaces object.
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
     * Compares this with another <code>DistributionsPlaces</code> instance.  If
     * <code>obj</code> is an instance of <code>DistributionsPlaces</code>, delegates to
     * <code>equals(DistributionsPlaces)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|DistributionsPlaces The current object, for fluid interface
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
     * Get the [distributions_placeid] column value.
     *
     * @return int
     */
    public function getDistributionsPlaceid()
    {
        return $this->distributions_placeid;
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
     * Set the value of [distributions_placeid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     */
    public function setDistributionsPlaceid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->distributions_placeid !== $v) {
            $this->distributions_placeid = $v;
            $this->modifiedColumns[DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID] = true;
        }

        return $this;
    } // setDistributionsPlaceid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[DistributionsPlacesTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvents !== null && $this->aEvents->getEventid() !== $v) {
            $this->aEvents = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[DistributionsPlacesTableMap::COL_NAME] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : DistributionsPlacesTableMap::translateFieldName('DistributionsPlaceid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->distributions_placeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : DistributionsPlacesTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : DistributionsPlacesTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = DistributionsPlacesTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\DistributionPlace\\DistributionsPlaces'), 0, $e);
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
        if ($this->aEvents !== null && $this->eventid !== $this->aEvents->getEventid()) {
            $this->aEvents = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(DistributionsPlacesTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildDistributionsPlacesQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvents = null;
            $this->collDistributionsPlacesGroupess = null;

            $this->collDistributionsPlacesTabless = null;

            $this->collDistributionsPlacesUserss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see DistributionsPlaces::setDeleted()
     * @see DistributionsPlaces::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildDistributionsPlacesQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesTableMap::DATABASE_NAME);
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
                DistributionsPlacesTableMap::addInstanceToPool($this);
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

            if ($this->aEvents !== null) {
                if ($this->aEvents->isModified() || $this->aEvents->isNew()) {
                    $affectedRows += $this->aEvents->save($con);
                }
                $this->setEvents($this->aEvents);
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
                    \API\Models\DistributionPlace\DistributionsPlacesGroupesQuery::create()
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
                    \API\Models\DistributionPlace\DistributionsPlacesTablesQuery::create()
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

        $this->modifiedColumns[DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID] = true;
        if (null !== $this->distributions_placeid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID)) {
            $modifiedColumns[':p' . $index++]  = 'distributions_placeid';
        }
        if ($this->isColumnModified(DistributionsPlacesTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(DistributionsPlacesTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO distributions_places (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'distributions_placeid':
                        $stmt->bindValue($identifier, $this->distributions_placeid, PDO::PARAM_INT);
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
        $this->setDistributionsPlaceid($pk);

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
        $pos = DistributionsPlacesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDistributionsPlaceid();
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

        if (isset($alreadyDumpedObjects['DistributionsPlaces'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DistributionsPlaces'][$this->hashCode()] = true;
        $keys = DistributionsPlacesTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getDistributionsPlaceid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEvents) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'events';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'events';
                        break;
                    default:
                        $key = 'Events';
                }

                $result[$key] = $this->aEvents->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DistributionsPlacesTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setDistributionsPlaceid($value);
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
        $keys = DistributionsPlacesTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setDistributionsPlaceid($arr[$keys[0]]);
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
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object, for fluid interface
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
        $criteria = new Criteria(DistributionsPlacesTableMap::DATABASE_NAME);

        if ($this->isColumnModified(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID)) {
            $criteria->add(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $this->distributions_placeid);
        }
        if ($this->isColumnModified(DistributionsPlacesTableMap::COL_EVENTID)) {
            $criteria->add(DistributionsPlacesTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(DistributionsPlacesTableMap::COL_NAME)) {
            $criteria->add(DistributionsPlacesTableMap::COL_NAME, $this->name);
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
        $criteria = ChildDistributionsPlacesQuery::create();
        $criteria->add(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $this->distributions_placeid);
        $criteria->add(DistributionsPlacesTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getDistributionsPlaceid() &&
            null !== $this->getEventid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_events_distribution_places_events1 to table events
        if ($this->aEvents && $hash = spl_object_hash($this->aEvents)) {
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
        $pks[0] = $this->getDistributionsPlaceid();
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
        $this->setDistributionsPlaceid($keys[0]);
        $this->setEventid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getDistributionsPlaceid()) && (null === $this->getEventid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\DistributionPlace\DistributionsPlaces (or compatible) type.
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

            foreach ($this->getDistributionsPlacesUserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDistributionsPlacesUsers($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setDistributionsPlaceid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\DistributionPlace\DistributionsPlaces Clone of current object.
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
     * Declares an association between this object and a Events object.
     *
     * @param  Events $v
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEvents(Events $v = null)
    {
        if ($v === null) {
            $this->setEventid(NULL);
        } else {
            $this->setEventid($v->getEventid());
        }

        $this->aEvents = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Events object, it will not be re-added.
        if ($v !== null) {
            $v->addDistributionsPlaces($this);
        }


        return $this;
    }


    /**
     * Get the associated Events object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return Events The associated Events object.
     * @throws PropelException
     */
    public function getEvents(ConnectionInterface $con = null)
    {
        if ($this->aEvents === null && ($this->eventid !== null)) {
            $this->aEvents = EventsQuery::create()->findPk($this->eventid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvents->addDistributionsPlacess($this);
             */
        }

        return $this->aEvents;
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
        if ('DistributionsPlacesUsers' == $relationName) {
            return $this->initDistributionsPlacesUserss();
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
        $this->collDistributionsPlacesGroupess->setModel('\API\Models\DistributionPlace\DistributionsPlacesGroupes');
    }

    /**
     * Gets an array of ChildDistributionsPlacesGroupes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionsPlaces is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDistributionsPlacesGroupes[] List of ChildDistributionsPlacesGroupes objects
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
                $collDistributionsPlacesGroupess = ChildDistributionsPlacesGroupesQuery::create(null, $criteria)
                    ->filterByDistributionsPlaces($this)
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
     * Sets a collection of ChildDistributionsPlacesGroupes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesGroupess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionsPlaces The current object (for fluent API support)
     */
    public function setDistributionsPlacesGroupess(Collection $distributionsPlacesGroupess, ConnectionInterface $con = null)
    {
        /** @var ChildDistributionsPlacesGroupes[] $distributionsPlacesGroupessToDelete */
        $distributionsPlacesGroupessToDelete = $this->getDistributionsPlacesGroupess(new Criteria(), $con)->diff($distributionsPlacesGroupess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesGroupessScheduledForDeletion = clone $distributionsPlacesGroupessToDelete;

        foreach ($distributionsPlacesGroupessToDelete as $distributionsPlacesGroupesRemoved) {
            $distributionsPlacesGroupesRemoved->setDistributionsPlaces(null);
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
     * Returns the number of related DistributionsPlacesGroupes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DistributionsPlacesGroupes objects.
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

            $query = ChildDistributionsPlacesGroupesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDistributionsPlaces($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesGroupess);
    }

    /**
     * Method called to associate a ChildDistributionsPlacesGroupes object to this object
     * through the ChildDistributionsPlacesGroupes foreign key attribute.
     *
     * @param  ChildDistributionsPlacesGroupes $l ChildDistributionsPlacesGroupes
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     */
    public function addDistributionsPlacesGroupes(ChildDistributionsPlacesGroupes $l)
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
     * @param ChildDistributionsPlacesGroupes $distributionsPlacesGroupes The ChildDistributionsPlacesGroupes object to add.
     */
    protected function doAddDistributionsPlacesGroupes(ChildDistributionsPlacesGroupes $distributionsPlacesGroupes)
    {
        $this->collDistributionsPlacesGroupess[]= $distributionsPlacesGroupes;
        $distributionsPlacesGroupes->setDistributionsPlaces($this);
    }

    /**
     * @param  ChildDistributionsPlacesGroupes $distributionsPlacesGroupes The ChildDistributionsPlacesGroupes object to remove.
     * @return $this|ChildDistributionsPlaces The current object (for fluent API support)
     */
    public function removeDistributionsPlacesGroupes(ChildDistributionsPlacesGroupes $distributionsPlacesGroupes)
    {
        if ($this->getDistributionsPlacesGroupess()->contains($distributionsPlacesGroupes)) {
            $pos = $this->collDistributionsPlacesGroupess->search($distributionsPlacesGroupes);
            $this->collDistributionsPlacesGroupess->remove($pos);
            if (null === $this->distributionsPlacesGroupessScheduledForDeletion) {
                $this->distributionsPlacesGroupessScheduledForDeletion = clone $this->collDistributionsPlacesGroupess;
                $this->distributionsPlacesGroupessScheduledForDeletion->clear();
            }
            $this->distributionsPlacesGroupessScheduledForDeletion[]= clone $distributionsPlacesGroupes;
            $distributionsPlacesGroupes->setDistributionsPlaces(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionsPlaces is new, it will return
     * an empty collection; or if this DistributionsPlaces has previously
     * been saved, it will retrieve related DistributionsPlacesGroupess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionsPlaces.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionsPlacesGroupes[] List of ChildDistributionsPlacesGroupes objects
     */
    public function getDistributionsPlacesGroupessJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionsPlacesGroupesQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

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
        $this->collDistributionsPlacesTabless->setModel('\API\Models\DistributionPlace\DistributionsPlacesTables');
    }

    /**
     * Gets an array of ChildDistributionsPlacesTables objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionsPlaces is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDistributionsPlacesTables[] List of ChildDistributionsPlacesTables objects
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
                $collDistributionsPlacesTabless = ChildDistributionsPlacesTablesQuery::create(null, $criteria)
                    ->filterByDistributionsPlaces($this)
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
     * Sets a collection of ChildDistributionsPlacesTables objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesTabless A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionsPlaces The current object (for fluent API support)
     */
    public function setDistributionsPlacesTabless(Collection $distributionsPlacesTabless, ConnectionInterface $con = null)
    {
        /** @var ChildDistributionsPlacesTables[] $distributionsPlacesTablessToDelete */
        $distributionsPlacesTablessToDelete = $this->getDistributionsPlacesTabless(new Criteria(), $con)->diff($distributionsPlacesTabless);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesTablessScheduledForDeletion = clone $distributionsPlacesTablessToDelete;

        foreach ($distributionsPlacesTablessToDelete as $distributionsPlacesTablesRemoved) {
            $distributionsPlacesTablesRemoved->setDistributionsPlaces(null);
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
     * Returns the number of related DistributionsPlacesTables objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DistributionsPlacesTables objects.
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

            $query = ChildDistributionsPlacesTablesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDistributionsPlaces($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesTabless);
    }

    /**
     * Method called to associate a ChildDistributionsPlacesTables object to this object
     * through the ChildDistributionsPlacesTables foreign key attribute.
     *
     * @param  ChildDistributionsPlacesTables $l ChildDistributionsPlacesTables
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     */
    public function addDistributionsPlacesTables(ChildDistributionsPlacesTables $l)
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
     * @param ChildDistributionsPlacesTables $distributionsPlacesTables The ChildDistributionsPlacesTables object to add.
     */
    protected function doAddDistributionsPlacesTables(ChildDistributionsPlacesTables $distributionsPlacesTables)
    {
        $this->collDistributionsPlacesTabless[]= $distributionsPlacesTables;
        $distributionsPlacesTables->setDistributionsPlaces($this);
    }

    /**
     * @param  ChildDistributionsPlacesTables $distributionsPlacesTables The ChildDistributionsPlacesTables object to remove.
     * @return $this|ChildDistributionsPlaces The current object (for fluent API support)
     */
    public function removeDistributionsPlacesTables(ChildDistributionsPlacesTables $distributionsPlacesTables)
    {
        if ($this->getDistributionsPlacesTabless()->contains($distributionsPlacesTables)) {
            $pos = $this->collDistributionsPlacesTabless->search($distributionsPlacesTables);
            $this->collDistributionsPlacesTabless->remove($pos);
            if (null === $this->distributionsPlacesTablessScheduledForDeletion) {
                $this->distributionsPlacesTablessScheduledForDeletion = clone $this->collDistributionsPlacesTabless;
                $this->distributionsPlacesTablessScheduledForDeletion->clear();
            }
            $this->distributionsPlacesTablessScheduledForDeletion[]= clone $distributionsPlacesTables;
            $distributionsPlacesTables->setDistributionsPlaces(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionsPlaces is new, it will return
     * an empty collection; or if this DistributionsPlaces has previously
     * been saved, it will retrieve related DistributionsPlacesTabless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionsPlaces.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionsPlacesTables[] List of ChildDistributionsPlacesTables objects
     */
    public function getDistributionsPlacesTablessJoinMenuGroupes(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionsPlacesTablesQuery::create(null, $criteria);
        $query->joinWith('MenuGroupes', $joinBehavior);

        return $this->getDistributionsPlacesTabless($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionsPlaces is new, it will return
     * an empty collection; or if this DistributionsPlaces has previously
     * been saved, it will retrieve related DistributionsPlacesTabless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionsPlaces.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionsPlacesTables[] List of ChildDistributionsPlacesTables objects
     */
    public function getDistributionsPlacesTablessJoinEventsTables(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionsPlacesTablesQuery::create(null, $criteria);
        $query->joinWith('EventsTables', $joinBehavior);

        return $this->getDistributionsPlacesTabless($query, $con);
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
     * Gets an array of ChildDistributionsPlacesUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDistributionsPlaces is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDistributionsPlacesUsers[] List of ChildDistributionsPlacesUsers objects
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
                $collDistributionsPlacesUserss = ChildDistributionsPlacesUsersQuery::create(null, $criteria)
                    ->filterByDistributionsPlaces($this)
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
     * Sets a collection of ChildDistributionsPlacesUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $distributionsPlacesUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDistributionsPlaces The current object (for fluent API support)
     */
    public function setDistributionsPlacesUserss(Collection $distributionsPlacesUserss, ConnectionInterface $con = null)
    {
        /** @var ChildDistributionsPlacesUsers[] $distributionsPlacesUserssToDelete */
        $distributionsPlacesUserssToDelete = $this->getDistributionsPlacesUserss(new Criteria(), $con)->diff($distributionsPlacesUserss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->distributionsPlacesUserssScheduledForDeletion = clone $distributionsPlacesUserssToDelete;

        foreach ($distributionsPlacesUserssToDelete as $distributionsPlacesUsersRemoved) {
            $distributionsPlacesUsersRemoved->setDistributionsPlaces(null);
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
     * Returns the number of related DistributionsPlacesUsers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related DistributionsPlacesUsers objects.
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

            $query = ChildDistributionsPlacesUsersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDistributionsPlaces($this)
                ->count($con);
        }

        return count($this->collDistributionsPlacesUserss);
    }

    /**
     * Method called to associate a ChildDistributionsPlacesUsers object to this object
     * through the ChildDistributionsPlacesUsers foreign key attribute.
     *
     * @param  ChildDistributionsPlacesUsers $l ChildDistributionsPlacesUsers
     * @return $this|\API\Models\DistributionPlace\DistributionsPlaces The current object (for fluent API support)
     */
    public function addDistributionsPlacesUsers(ChildDistributionsPlacesUsers $l)
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
     * @param ChildDistributionsPlacesUsers $distributionsPlacesUsers The ChildDistributionsPlacesUsers object to add.
     */
    protected function doAddDistributionsPlacesUsers(ChildDistributionsPlacesUsers $distributionsPlacesUsers)
    {
        $this->collDistributionsPlacesUserss[]= $distributionsPlacesUsers;
        $distributionsPlacesUsers->setDistributionsPlaces($this);
    }

    /**
     * @param  ChildDistributionsPlacesUsers $distributionsPlacesUsers The ChildDistributionsPlacesUsers object to remove.
     * @return $this|ChildDistributionsPlaces The current object (for fluent API support)
     */
    public function removeDistributionsPlacesUsers(ChildDistributionsPlacesUsers $distributionsPlacesUsers)
    {
        if ($this->getDistributionsPlacesUserss()->contains($distributionsPlacesUsers)) {
            $pos = $this->collDistributionsPlacesUserss->search($distributionsPlacesUsers);
            $this->collDistributionsPlacesUserss->remove($pos);
            if (null === $this->distributionsPlacesUserssScheduledForDeletion) {
                $this->distributionsPlacesUserssScheduledForDeletion = clone $this->collDistributionsPlacesUserss;
                $this->distributionsPlacesUserssScheduledForDeletion->clear();
            }
            $this->distributionsPlacesUserssScheduledForDeletion[]= clone $distributionsPlacesUsers;
            $distributionsPlacesUsers->setDistributionsPlaces(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionsPlaces is new, it will return
     * an empty collection; or if this DistributionsPlaces has previously
     * been saved, it will retrieve related DistributionsPlacesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionsPlaces.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionsPlacesUsers[] List of ChildDistributionsPlacesUsers objects
     */
    public function getDistributionsPlacesUserssJoinUsers(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionsPlacesUsersQuery::create(null, $criteria);
        $query->joinWith('Users', $joinBehavior);

        return $this->getDistributionsPlacesUserss($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DistributionsPlaces is new, it will return
     * an empty collection; or if this DistributionsPlaces has previously
     * been saved, it will retrieve related DistributionsPlacesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DistributionsPlaces.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDistributionsPlacesUsers[] List of ChildDistributionsPlacesUsers objects
     */
    public function getDistributionsPlacesUserssJoinEventsPrinters(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDistributionsPlacesUsersQuery::create(null, $criteria);
        $query->joinWith('EventsPrinters', $joinBehavior);

        return $this->getDistributionsPlacesUserss($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvents) {
            $this->aEvents->removeDistributionsPlaces($this);
        }
        $this->distributions_placeid = null;
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
            if ($this->collDistributionsPlacesUserss) {
                foreach ($this->collDistributionsPlacesUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collDistributionsPlacesGroupess = null;
        $this->collDistributionsPlacesTabless = null;
        $this->collDistributionsPlacesUserss = null;
        $this->aEvents = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DistributionsPlacesTableMap::DEFAULT_STRING_FORMAT);
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