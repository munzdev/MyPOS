<?php

namespace API\Models\Event\Base;

use \Exception;
use \PDO;
use API\Models\Event\Events as ChildEvents;
use API\Models\Event\EventsQuery as ChildEventsQuery;
use API\Models\Event\EventsUser as ChildEventsUser;
use API\Models\Event\EventsUserQuery as ChildEventsUserQuery;
use API\Models\Event\Map\EventsUserTableMap;
use API\Models\User\Users;
use API\Models\User\UsersQuery;
use API\Models\User\Messages\UsersMessages;
use API\Models\User\Messages\UsersMessagesQuery;
use API\Models\User\Messages\Base\UsersMessages as BaseUsersMessages;
use API\Models\User\Messages\Map\UsersMessagesTableMap;
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
 * Base class that represents a row from the 'events_user' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Event.Base
 */
abstract class EventsUser implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Event\\Map\\EventsUserTableMap';


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
     * The value for the events_userid field.
     *
     * @var        int
     */
    protected $events_userid;

    /**
     * The value for the eventid field.
     *
     * @var        int
     */
    protected $eventid;

    /**
     * The value for the userid field.
     *
     * @var        int
     */
    protected $userid;

    /**
     * The value for the user_roles field.
     *
     * @var        int
     */
    protected $user_roles;

    /**
     * The value for the begin_money field.
     *
     * @var        string
     */
    protected $begin_money;

    /**
     * @var        ChildEvents
     */
    protected $aEvents;

    /**
     * @var        Users
     */
    protected $aUsers;

    /**
     * @var        ObjectCollection|UsersMessages[] Collection to store aggregation of UsersMessages objects.
     */
    protected $collUsersMessagessRelatedByFromEventsUserid;
    protected $collUsersMessagessRelatedByFromEventsUseridPartial;

    /**
     * @var        ObjectCollection|UsersMessages[] Collection to store aggregation of UsersMessages objects.
     */
    protected $collUsersMessagessRelatedByToEventsUserid;
    protected $collUsersMessagessRelatedByToEventsUseridPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UsersMessages[]
     */
    protected $usersMessagessRelatedByFromEventsUseridScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|UsersMessages[]
     */
    protected $usersMessagessRelatedByToEventsUseridScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Event\Base\EventsUser object.
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
     * Compares this with another <code>EventsUser</code> instance.  If
     * <code>obj</code> is an instance of <code>EventsUser</code>, delegates to
     * <code>equals(EventsUser)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|EventsUser The current object, for fluid interface
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
     * Get the [events_userid] column value.
     *
     * @return int
     */
    public function getEventsUserid()
    {
        return $this->events_userid;
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
     * Get the [userid] column value.
     *
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Get the [user_roles] column value.
     *
     * @return int
     */
    public function getUserRoles()
    {
        return $this->user_roles;
    }

    /**
     * Get the [begin_money] column value.
     *
     * @return string
     */
    public function getBeginMoney()
    {
        return $this->begin_money;
    }

    /**
     * Set the value of [events_userid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function setEventsUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->events_userid !== $v) {
            $this->events_userid = $v;
            $this->modifiedColumns[EventsUserTableMap::COL_EVENTS_USERID] = true;
        }

        return $this;
    } // setEventsUserid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[EventsUserTableMap::COL_EVENTID] = true;
        }

        if ($this->aEvents !== null && $this->aEvents->getEventid() !== $v) {
            $this->aEvents = null;
        }

        return $this;
    } // setEventid()

    /**
     * Set the value of [userid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[EventsUserTableMap::COL_USERID] = true;
        }

        if ($this->aUsers !== null && $this->aUsers->getUserid() !== $v) {
            $this->aUsers = null;
        }

        return $this;
    } // setUserid()

    /**
     * Set the value of [user_roles] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function setUserRoles($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_roles !== $v) {
            $this->user_roles = $v;
            $this->modifiedColumns[EventsUserTableMap::COL_USER_ROLES] = true;
        }

        return $this;
    } // setUserRoles()

    /**
     * Set the value of [begin_money] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function setBeginMoney($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->begin_money !== $v) {
            $this->begin_money = $v;
            $this->modifiedColumns[EventsUserTableMap::COL_BEGIN_MONEY] = true;
        }

        return $this;
    } // setBeginMoney()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventsUserTableMap::translateFieldName('EventsUserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->events_userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventsUserTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventsUserTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventsUserTableMap::translateFieldName('UserRoles', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_roles = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventsUserTableMap::translateFieldName('BeginMoney', TableMap::TYPE_PHPNAME, $indexType)];
            $this->begin_money = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = EventsUserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Event\\EventsUser'), 0, $e);
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
        if ($this->aUsers !== null && $this->userid !== $this->aUsers->getUserid()) {
            $this->aUsers = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(EventsUserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventsUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvents = null;
            $this->aUsers = null;
            $this->collUsersMessagessRelatedByFromEventsUserid = null;

            $this->collUsersMessagessRelatedByToEventsUserid = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see EventsUser::setDeleted()
     * @see EventsUser::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsUserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventsUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsUserTableMap::DATABASE_NAME);
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
                EventsUserTableMap::addInstanceToPool($this);
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

            if ($this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion !== null) {
                if (!$this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion->isEmpty()) {
                    \API\Models\User\Messages\UsersMessagesQuery::create()
                        ->filterByPrimaryKeys($this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion = null;
                }
            }

            if ($this->collUsersMessagessRelatedByFromEventsUserid !== null) {
                foreach ($this->collUsersMessagessRelatedByFromEventsUserid as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->usersMessagessRelatedByToEventsUseridScheduledForDeletion !== null) {
                if (!$this->usersMessagessRelatedByToEventsUseridScheduledForDeletion->isEmpty()) {
                    \API\Models\User\Messages\UsersMessagesQuery::create()
                        ->filterByPrimaryKeys($this->usersMessagessRelatedByToEventsUseridScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion = null;
                }
            }

            if ($this->collUsersMessagessRelatedByToEventsUserid !== null) {
                foreach ($this->collUsersMessagessRelatedByToEventsUserid as $referrerFK) {
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

        $this->modifiedColumns[EventsUserTableMap::COL_EVENTS_USERID] = true;
        if (null !== $this->events_userid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventsUserTableMap::COL_EVENTS_USERID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventsUserTableMap::COL_EVENTS_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'events_userid';
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userid';
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_USER_ROLES)) {
            $modifiedColumns[':p' . $index++]  = 'user_roles';
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_BEGIN_MONEY)) {
            $modifiedColumns[':p' . $index++]  = 'begin_money';
        }

        $sql = sprintf(
            'INSERT INTO events_user (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'events_userid':
                        $stmt->bindValue($identifier, $this->events_userid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'userid':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case 'user_roles':
                        $stmt->bindValue($identifier, $this->user_roles, PDO::PARAM_INT);
                        break;
                    case 'begin_money':
                        $stmt->bindValue($identifier, $this->begin_money, PDO::PARAM_STR);
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
        $this->setEventsUserid($pk);

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
        $pos = EventsUserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventsUserid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getUserid();
                break;
            case 3:
                return $this->getUserRoles();
                break;
            case 4:
                return $this->getBeginMoney();
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

        if (isset($alreadyDumpedObjects['EventsUser'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['EventsUser'][$this->hashCode()] = true;
        $keys = EventsUserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEventsUserid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getUserid(),
            $keys[3] => $this->getUserRoles(),
            $keys[4] => $this->getBeginMoney(),
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
            if (null !== $this->collUsersMessagessRelatedByFromEventsUserid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'usersMessagess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users_messagess';
                        break;
                    default:
                        $key = 'UsersMessagess';
                }

                $result[$key] = $this->collUsersMessagessRelatedByFromEventsUserid->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUsersMessagessRelatedByToEventsUserid) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'usersMessagess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users_messagess';
                        break;
                    default:
                        $key = 'UsersMessagess';
                }

                $result[$key] = $this->collUsersMessagessRelatedByToEventsUserid->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Event\EventsUser
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventsUserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Event\EventsUser
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEventsUserid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setUserid($value);
                break;
            case 3:
                $this->setUserRoles($value);
                break;
            case 4:
                $this->setBeginMoney($value);
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
        $keys = EventsUserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEventsUserid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUserRoles($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setBeginMoney($arr[$keys[4]]);
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
     * @return $this|\API\Models\Event\EventsUser The current object, for fluid interface
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
        $criteria = new Criteria(EventsUserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventsUserTableMap::COL_EVENTS_USERID)) {
            $criteria->add(EventsUserTableMap::COL_EVENTS_USERID, $this->events_userid);
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_EVENTID)) {
            $criteria->add(EventsUserTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_USERID)) {
            $criteria->add(EventsUserTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_USER_ROLES)) {
            $criteria->add(EventsUserTableMap::COL_USER_ROLES, $this->user_roles);
        }
        if ($this->isColumnModified(EventsUserTableMap::COL_BEGIN_MONEY)) {
            $criteria->add(EventsUserTableMap::COL_BEGIN_MONEY, $this->begin_money);
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
        $criteria = ChildEventsUserQuery::create();
        $criteria->add(EventsUserTableMap::COL_EVENTS_USERID, $this->events_userid);
        $criteria->add(EventsUserTableMap::COL_EVENTID, $this->eventid);
        $criteria->add(EventsUserTableMap::COL_USERID, $this->userid);

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
        $validPk = null !== $this->getEventsUserid() &&
            null !== $this->getEventid() &&
            null !== $this->getUserid();

        $validPrimaryKeyFKs = 2;
        $primaryKeyFKs = [];

        //relation fk_events_has_users_events1 to table events
        if ($this->aEvents && $hash = spl_object_hash($this->aEvents)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation fk_events_has_users_users1 to table users
        if ($this->aUsers && $hash = spl_object_hash($this->aUsers)) {
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
        $pks[0] = $this->getEventsUserid();
        $pks[1] = $this->getEventid();
        $pks[2] = $this->getUserid();

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
        $this->setEventsUserid($keys[0]);
        $this->setEventid($keys[1]);
        $this->setUserid($keys[2]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getEventsUserid()) && (null === $this->getEventid()) && (null === $this->getUserid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Event\EventsUser (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setUserid($this->getUserid());
        $copyObj->setUserRoles($this->getUserRoles());
        $copyObj->setBeginMoney($this->getBeginMoney());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUsersMessagessRelatedByFromEventsUserid() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUsersMessagesRelatedByFromEventsUserid($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsersMessagessRelatedByToEventsUserid() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUsersMessagesRelatedByToEventsUserid($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setEventsUserid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Event\EventsUser Clone of current object.
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
     * Declares an association between this object and a ChildEvents object.
     *
     * @param  ChildEvents $v
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEvents(ChildEvents $v = null)
    {
        if ($v === null) {
            $this->setEventid(NULL);
        } else {
            $this->setEventid($v->getEventid());
        }

        $this->aEvents = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildEvents object, it will not be re-added.
        if ($v !== null) {
            $v->addEventsUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildEvents object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildEvents The associated ChildEvents object.
     * @throws PropelException
     */
    public function getEvents(ConnectionInterface $con = null)
    {
        if ($this->aEvents === null && ($this->eventid !== null)) {
            $this->aEvents = ChildEventsQuery::create()->findPk($this->eventid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEvents->addEventsUsers($this);
             */
        }

        return $this->aEvents;
    }

    /**
     * Declares an association between this object and a Users object.
     *
     * @param  Users $v
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUsers(Users $v = null)
    {
        if ($v === null) {
            $this->setUserid(NULL);
        } else {
            $this->setUserid($v->getUserid());
        }

        $this->aUsers = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Users object, it will not be re-added.
        if ($v !== null) {
            $v->addEventsUser($this);
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
        if ($this->aUsers === null && ($this->userid !== null)) {
            $this->aUsers = UsersQuery::create()->findPk($this->userid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUsers->addEventsUsers($this);
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
        if ('UsersMessagesRelatedByFromEventsUserid' == $relationName) {
            return $this->initUsersMessagessRelatedByFromEventsUserid();
        }
        if ('UsersMessagesRelatedByToEventsUserid' == $relationName) {
            return $this->initUsersMessagessRelatedByToEventsUserid();
        }
    }

    /**
     * Clears out the collUsersMessagessRelatedByFromEventsUserid collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsersMessagessRelatedByFromEventsUserid()
     */
    public function clearUsersMessagessRelatedByFromEventsUserid()
    {
        $this->collUsersMessagessRelatedByFromEventsUserid = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsersMessagessRelatedByFromEventsUserid collection loaded partially.
     */
    public function resetPartialUsersMessagessRelatedByFromEventsUserid($v = true)
    {
        $this->collUsersMessagessRelatedByFromEventsUseridPartial = $v;
    }

    /**
     * Initializes the collUsersMessagessRelatedByFromEventsUserid collection.
     *
     * By default this just sets the collUsersMessagessRelatedByFromEventsUserid collection to an empty array (like clearcollUsersMessagessRelatedByFromEventsUserid());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersMessagessRelatedByFromEventsUserid($overrideExisting = true)
    {
        if (null !== $this->collUsersMessagessRelatedByFromEventsUserid && !$overrideExisting) {
            return;
        }

        $collectionClassName = UsersMessagesTableMap::getTableMap()->getCollectionClassName();

        $this->collUsersMessagessRelatedByFromEventsUserid = new $collectionClassName;
        $this->collUsersMessagessRelatedByFromEventsUserid->setModel('\API\Models\User\Messages\UsersMessages');
    }

    /**
     * Gets an array of UsersMessages objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventsUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UsersMessages[] List of UsersMessages objects
     * @throws PropelException
     */
    public function getUsersMessagessRelatedByFromEventsUserid(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersMessagessRelatedByFromEventsUseridPartial && !$this->isNew();
        if (null === $this->collUsersMessagessRelatedByFromEventsUserid || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersMessagessRelatedByFromEventsUserid) {
                // return empty collection
                $this->initUsersMessagessRelatedByFromEventsUserid();
            } else {
                $collUsersMessagessRelatedByFromEventsUserid = UsersMessagesQuery::create(null, $criteria)
                    ->filterByEventsUserRelatedByFromEventsUserid($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersMessagessRelatedByFromEventsUseridPartial && count($collUsersMessagessRelatedByFromEventsUserid)) {
                        $this->initUsersMessagessRelatedByFromEventsUserid(false);

                        foreach ($collUsersMessagessRelatedByFromEventsUserid as $obj) {
                            if (false == $this->collUsersMessagessRelatedByFromEventsUserid->contains($obj)) {
                                $this->collUsersMessagessRelatedByFromEventsUserid->append($obj);
                            }
                        }

                        $this->collUsersMessagessRelatedByFromEventsUseridPartial = true;
                    }

                    return $collUsersMessagessRelatedByFromEventsUserid;
                }

                if ($partial && $this->collUsersMessagessRelatedByFromEventsUserid) {
                    foreach ($this->collUsersMessagessRelatedByFromEventsUserid as $obj) {
                        if ($obj->isNew()) {
                            $collUsersMessagessRelatedByFromEventsUserid[] = $obj;
                        }
                    }
                }

                $this->collUsersMessagessRelatedByFromEventsUserid = $collUsersMessagessRelatedByFromEventsUserid;
                $this->collUsersMessagessRelatedByFromEventsUseridPartial = false;
            }
        }

        return $this->collUsersMessagessRelatedByFromEventsUserid;
    }

    /**
     * Sets a collection of UsersMessages objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $usersMessagessRelatedByFromEventsUserid A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventsUser The current object (for fluent API support)
     */
    public function setUsersMessagessRelatedByFromEventsUserid(Collection $usersMessagessRelatedByFromEventsUserid, ConnectionInterface $con = null)
    {
        /** @var UsersMessages[] $usersMessagessRelatedByFromEventsUseridToDelete */
        $usersMessagessRelatedByFromEventsUseridToDelete = $this->getUsersMessagessRelatedByFromEventsUserid(new Criteria(), $con)->diff($usersMessagessRelatedByFromEventsUserid);


        $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion = $usersMessagessRelatedByFromEventsUseridToDelete;

        foreach ($usersMessagessRelatedByFromEventsUseridToDelete as $usersMessagesRelatedByFromEventsUseridRemoved) {
            $usersMessagesRelatedByFromEventsUseridRemoved->setEventsUserRelatedByFromEventsUserid(null);
        }

        $this->collUsersMessagessRelatedByFromEventsUserid = null;
        foreach ($usersMessagessRelatedByFromEventsUserid as $usersMessagesRelatedByFromEventsUserid) {
            $this->addUsersMessagesRelatedByFromEventsUserid($usersMessagesRelatedByFromEventsUserid);
        }

        $this->collUsersMessagessRelatedByFromEventsUserid = $usersMessagessRelatedByFromEventsUserid;
        $this->collUsersMessagessRelatedByFromEventsUseridPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseUsersMessages objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUsersMessages objects.
     * @throws PropelException
     */
    public function countUsersMessagessRelatedByFromEventsUserid(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersMessagessRelatedByFromEventsUseridPartial && !$this->isNew();
        if (null === $this->collUsersMessagessRelatedByFromEventsUserid || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersMessagessRelatedByFromEventsUserid) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersMessagessRelatedByFromEventsUserid());
            }

            $query = UsersMessagesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventsUserRelatedByFromEventsUserid($this)
                ->count($con);
        }

        return count($this->collUsersMessagessRelatedByFromEventsUserid);
    }

    /**
     * Method called to associate a UsersMessages object to this object
     * through the UsersMessages foreign key attribute.
     *
     * @param  UsersMessages $l UsersMessages
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function addUsersMessagesRelatedByFromEventsUserid(UsersMessages $l)
    {
        if ($this->collUsersMessagessRelatedByFromEventsUserid === null) {
            $this->initUsersMessagessRelatedByFromEventsUserid();
            $this->collUsersMessagessRelatedByFromEventsUseridPartial = true;
        }

        if (!$this->collUsersMessagessRelatedByFromEventsUserid->contains($l)) {
            $this->doAddUsersMessagesRelatedByFromEventsUserid($l);

            if ($this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion and $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion->contains($l)) {
                $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion->remove($this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param UsersMessages $usersMessagesRelatedByFromEventsUserid The UsersMessages object to add.
     */
    protected function doAddUsersMessagesRelatedByFromEventsUserid(UsersMessages $usersMessagesRelatedByFromEventsUserid)
    {
        $this->collUsersMessagessRelatedByFromEventsUserid[]= $usersMessagesRelatedByFromEventsUserid;
        $usersMessagesRelatedByFromEventsUserid->setEventsUserRelatedByFromEventsUserid($this);
    }

    /**
     * @param  UsersMessages $usersMessagesRelatedByFromEventsUserid The UsersMessages object to remove.
     * @return $this|ChildEventsUser The current object (for fluent API support)
     */
    public function removeUsersMessagesRelatedByFromEventsUserid(UsersMessages $usersMessagesRelatedByFromEventsUserid)
    {
        if ($this->getUsersMessagessRelatedByFromEventsUserid()->contains($usersMessagesRelatedByFromEventsUserid)) {
            $pos = $this->collUsersMessagessRelatedByFromEventsUserid->search($usersMessagesRelatedByFromEventsUserid);
            $this->collUsersMessagessRelatedByFromEventsUserid->remove($pos);
            if (null === $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion) {
                $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion = clone $this->collUsersMessagessRelatedByFromEventsUserid;
                $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion->clear();
            }
            $this->usersMessagessRelatedByFromEventsUseridScheduledForDeletion[]= $usersMessagesRelatedByFromEventsUserid;
            $usersMessagesRelatedByFromEventsUserid->setEventsUserRelatedByFromEventsUserid(null);
        }

        return $this;
    }

    /**
     * Clears out the collUsersMessagessRelatedByToEventsUserid collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsersMessagessRelatedByToEventsUserid()
     */
    public function clearUsersMessagessRelatedByToEventsUserid()
    {
        $this->collUsersMessagessRelatedByToEventsUserid = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsersMessagessRelatedByToEventsUserid collection loaded partially.
     */
    public function resetPartialUsersMessagessRelatedByToEventsUserid($v = true)
    {
        $this->collUsersMessagessRelatedByToEventsUseridPartial = $v;
    }

    /**
     * Initializes the collUsersMessagessRelatedByToEventsUserid collection.
     *
     * By default this just sets the collUsersMessagessRelatedByToEventsUserid collection to an empty array (like clearcollUsersMessagessRelatedByToEventsUserid());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersMessagessRelatedByToEventsUserid($overrideExisting = true)
    {
        if (null !== $this->collUsersMessagessRelatedByToEventsUserid && !$overrideExisting) {
            return;
        }

        $collectionClassName = UsersMessagesTableMap::getTableMap()->getCollectionClassName();

        $this->collUsersMessagessRelatedByToEventsUserid = new $collectionClassName;
        $this->collUsersMessagessRelatedByToEventsUserid->setModel('\API\Models\User\Messages\UsersMessages');
    }

    /**
     * Gets an array of UsersMessages objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEventsUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|UsersMessages[] List of UsersMessages objects
     * @throws PropelException
     */
    public function getUsersMessagessRelatedByToEventsUserid(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersMessagessRelatedByToEventsUseridPartial && !$this->isNew();
        if (null === $this->collUsersMessagessRelatedByToEventsUserid || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersMessagessRelatedByToEventsUserid) {
                // return empty collection
                $this->initUsersMessagessRelatedByToEventsUserid();
            } else {
                $collUsersMessagessRelatedByToEventsUserid = UsersMessagesQuery::create(null, $criteria)
                    ->filterByEventsUserRelatedByToEventsUserid($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersMessagessRelatedByToEventsUseridPartial && count($collUsersMessagessRelatedByToEventsUserid)) {
                        $this->initUsersMessagessRelatedByToEventsUserid(false);

                        foreach ($collUsersMessagessRelatedByToEventsUserid as $obj) {
                            if (false == $this->collUsersMessagessRelatedByToEventsUserid->contains($obj)) {
                                $this->collUsersMessagessRelatedByToEventsUserid->append($obj);
                            }
                        }

                        $this->collUsersMessagessRelatedByToEventsUseridPartial = true;
                    }

                    return $collUsersMessagessRelatedByToEventsUserid;
                }

                if ($partial && $this->collUsersMessagessRelatedByToEventsUserid) {
                    foreach ($this->collUsersMessagessRelatedByToEventsUserid as $obj) {
                        if ($obj->isNew()) {
                            $collUsersMessagessRelatedByToEventsUserid[] = $obj;
                        }
                    }
                }

                $this->collUsersMessagessRelatedByToEventsUserid = $collUsersMessagessRelatedByToEventsUserid;
                $this->collUsersMessagessRelatedByToEventsUseridPartial = false;
            }
        }

        return $this->collUsersMessagessRelatedByToEventsUserid;
    }

    /**
     * Sets a collection of UsersMessages objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $usersMessagessRelatedByToEventsUserid A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEventsUser The current object (for fluent API support)
     */
    public function setUsersMessagessRelatedByToEventsUserid(Collection $usersMessagessRelatedByToEventsUserid, ConnectionInterface $con = null)
    {
        /** @var UsersMessages[] $usersMessagessRelatedByToEventsUseridToDelete */
        $usersMessagessRelatedByToEventsUseridToDelete = $this->getUsersMessagessRelatedByToEventsUserid(new Criteria(), $con)->diff($usersMessagessRelatedByToEventsUserid);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion = clone $usersMessagessRelatedByToEventsUseridToDelete;

        foreach ($usersMessagessRelatedByToEventsUseridToDelete as $usersMessagesRelatedByToEventsUseridRemoved) {
            $usersMessagesRelatedByToEventsUseridRemoved->setEventsUserRelatedByToEventsUserid(null);
        }

        $this->collUsersMessagessRelatedByToEventsUserid = null;
        foreach ($usersMessagessRelatedByToEventsUserid as $usersMessagesRelatedByToEventsUserid) {
            $this->addUsersMessagesRelatedByToEventsUserid($usersMessagesRelatedByToEventsUserid);
        }

        $this->collUsersMessagessRelatedByToEventsUserid = $usersMessagessRelatedByToEventsUserid;
        $this->collUsersMessagessRelatedByToEventsUseridPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseUsersMessages objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseUsersMessages objects.
     * @throws PropelException
     */
    public function countUsersMessagessRelatedByToEventsUserid(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersMessagessRelatedByToEventsUseridPartial && !$this->isNew();
        if (null === $this->collUsersMessagessRelatedByToEventsUserid || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersMessagessRelatedByToEventsUserid) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersMessagessRelatedByToEventsUserid());
            }

            $query = UsersMessagesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventsUserRelatedByToEventsUserid($this)
                ->count($con);
        }

        return count($this->collUsersMessagessRelatedByToEventsUserid);
    }

    /**
     * Method called to associate a UsersMessages object to this object
     * through the UsersMessages foreign key attribute.
     *
     * @param  UsersMessages $l UsersMessages
     * @return $this|\API\Models\Event\EventsUser The current object (for fluent API support)
     */
    public function addUsersMessagesRelatedByToEventsUserid(UsersMessages $l)
    {
        if ($this->collUsersMessagessRelatedByToEventsUserid === null) {
            $this->initUsersMessagessRelatedByToEventsUserid();
            $this->collUsersMessagessRelatedByToEventsUseridPartial = true;
        }

        if (!$this->collUsersMessagessRelatedByToEventsUserid->contains($l)) {
            $this->doAddUsersMessagesRelatedByToEventsUserid($l);

            if ($this->usersMessagessRelatedByToEventsUseridScheduledForDeletion and $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion->contains($l)) {
                $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion->remove($this->usersMessagessRelatedByToEventsUseridScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param UsersMessages $usersMessagesRelatedByToEventsUserid The UsersMessages object to add.
     */
    protected function doAddUsersMessagesRelatedByToEventsUserid(UsersMessages $usersMessagesRelatedByToEventsUserid)
    {
        $this->collUsersMessagessRelatedByToEventsUserid[]= $usersMessagesRelatedByToEventsUserid;
        $usersMessagesRelatedByToEventsUserid->setEventsUserRelatedByToEventsUserid($this);
    }

    /**
     * @param  UsersMessages $usersMessagesRelatedByToEventsUserid The UsersMessages object to remove.
     * @return $this|ChildEventsUser The current object (for fluent API support)
     */
    public function removeUsersMessagesRelatedByToEventsUserid(UsersMessages $usersMessagesRelatedByToEventsUserid)
    {
        if ($this->getUsersMessagessRelatedByToEventsUserid()->contains($usersMessagesRelatedByToEventsUserid)) {
            $pos = $this->collUsersMessagessRelatedByToEventsUserid->search($usersMessagesRelatedByToEventsUserid);
            $this->collUsersMessagessRelatedByToEventsUserid->remove($pos);
            if (null === $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion) {
                $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion = clone $this->collUsersMessagessRelatedByToEventsUserid;
                $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion->clear();
            }
            $this->usersMessagessRelatedByToEventsUseridScheduledForDeletion[]= clone $usersMessagesRelatedByToEventsUserid;
            $usersMessagesRelatedByToEventsUserid->setEventsUserRelatedByToEventsUserid(null);
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
        if (null !== $this->aEvents) {
            $this->aEvents->removeEventsUser($this);
        }
        if (null !== $this->aUsers) {
            $this->aUsers->removeEventsUser($this);
        }
        $this->events_userid = null;
        $this->eventid = null;
        $this->userid = null;
        $this->user_roles = null;
        $this->begin_money = null;
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
            if ($this->collUsersMessagessRelatedByFromEventsUserid) {
                foreach ($this->collUsersMessagessRelatedByFromEventsUserid as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsersMessagessRelatedByToEventsUserid) {
                foreach ($this->collUsersMessagessRelatedByToEventsUserid as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUsersMessagessRelatedByFromEventsUserid = null;
        $this->collUsersMessagessRelatedByToEventsUserid = null;
        $this->aEvents = null;
        $this->aUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventsUserTableMap::DEFAULT_STRING_FORMAT);
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
