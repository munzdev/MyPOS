<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Event\EventQuery;
use API\Models\Menu\MenuPossibleSize as ChildMenuPossibleSize;
use API\Models\Menu\MenuPossibleSizeQuery as ChildMenuPossibleSizeQuery;
use API\Models\Menu\MenuSize as ChildMenuSize;
use API\Models\Menu\MenuSizeQuery as ChildMenuSizeQuery;
use API\Models\Menu\Map\MenuPossibleSizeTableMap;
use API\Models\Menu\Map\MenuSizeTableMap;
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
 * Base class that represents a row from the 'menu_size' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Menu.Base
 */
abstract class MenuSize implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Menu\\Map\\MenuSizeTableMap';


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
     * The value for the menu_sizeid field.
     *
     * @var        int
     */
    protected $menu_sizeid;

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
     * The value for the factor field.
     *
     * @var        string
     */
    protected $factor;

    /**
     * @var        Event
     */
    protected $aEvent;

    /**
     * @var        ObjectCollection|ChildMenuPossibleSize[] Collection to store aggregation of ChildMenuPossibleSize objects.
     */
    protected $collMenuPossibleSizes;
    protected $collMenuPossibleSizesPartial;

    /**
     * @var        ObjectCollection|OrderDetail[] Collection to store aggregation of OrderDetail objects.
     */
    protected $collOrderDetails;
    protected $collOrderDetailsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMenuPossibleSize[]
     */
    protected $menuPossibleSizesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrderDetail[]
     */
    protected $orderDetailsScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Menu\Base\MenuSize object.
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
     * Compares this with another <code>MenuSize</code> instance.  If
     * <code>obj</code> is an instance of <code>MenuSize</code>, delegates to
     * <code>equals(MenuSize)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|MenuSize The current object, for fluid interface
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
     * Get the [menu_sizeid] column value.
     *
     * @return int
     */
    public function getMenuSizeid()
    {
        return $this->menu_sizeid;
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
     * Get the [factor] column value.
     *
     * @return string
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * Set the value of [menu_sizeid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
     */
    public function setMenuSizeid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_sizeid !== $v) {
            $this->menu_sizeid = $v;
            $this->modifiedColumns[MenuSizeTableMap::COL_MENU_SIZEID] = true;
        }

        return $this;
    } // setMenuSizeid()

    /**
     * Set the value of [eventid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
     */
    public function setEventid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->eventid !== $v) {
            $this->eventid = $v;
            $this->modifiedColumns[MenuSizeTableMap::COL_EVENTID] = true;
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
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MenuSizeTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [factor] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
     */
    public function setFactor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->factor !== $v) {
            $this->factor = $v;
            $this->modifiedColumns[MenuSizeTableMap::COL_FACTOR] = true;
        }

        return $this;
    } // setFactor()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MenuSizeTableMap::translateFieldName('MenuSizeid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_sizeid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MenuSizeTableMap::translateFieldName('Eventid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->eventid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MenuSizeTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MenuSizeTableMap::translateFieldName('Factor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->factor = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = MenuSizeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Menu\\MenuSize'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMenuSizeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEvent = null;
            $this->collMenuPossibleSizes = null;

            $this->collOrderDetails = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see MenuSize::setDeleted()
     * @see MenuSize::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMenuSizeQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizeTableMap::DATABASE_NAME);
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
                MenuSizeTableMap::addInstanceToPool($this);
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

            if ($this->menuPossibleSizesScheduledForDeletion !== null) {
                if (!$this->menuPossibleSizesScheduledForDeletion->isEmpty()) {
                    \API\Models\Menu\MenuPossibleSizeQuery::create()
                        ->filterByPrimaryKeys($this->menuPossibleSizesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->menuPossibleSizesScheduledForDeletion = null;
                }
            }

            if ($this->collMenuPossibleSizes !== null) {
                foreach ($this->collMenuPossibleSizes as $referrerFK) {
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

        $this->modifiedColumns[MenuSizeTableMap::COL_MENU_SIZEID] = true;
        if (null !== $this->menu_sizeid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MenuSizeTableMap::COL_MENU_SIZEID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MenuSizeTableMap::COL_MENU_SIZEID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_sizeid';
        }
        if ($this->isColumnModified(MenuSizeTableMap::COL_EVENTID)) {
            $modifiedColumns[':p' . $index++]  = 'eventid';
        }
        if ($this->isColumnModified(MenuSizeTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(MenuSizeTableMap::COL_FACTOR)) {
            $modifiedColumns[':p' . $index++]  = 'factor';
        }

        $sql = sprintf(
            'INSERT INTO menu_size (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'menu_sizeid':
                        $stmt->bindValue($identifier, $this->menu_sizeid, PDO::PARAM_INT);
                        break;
                    case 'eventid':
                        $stmt->bindValue($identifier, $this->eventid, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'factor':
                        $stmt->bindValue($identifier, $this->factor, PDO::PARAM_STR);
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
        $this->setMenuSizeid($pk);

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
        $pos = MenuSizeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMenuSizeid();
                break;
            case 1:
                return $this->getEventid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getFactor();
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

        if (isset($alreadyDumpedObjects['MenuSize'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MenuSize'][$this->hashCode()] = true;
        $keys = MenuSizeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMenuSizeid(),
            $keys[1] => $this->getEventid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getFactor(),
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
            if (null !== $this->collMenuPossibleSizes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuPossibleSizes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_possible_sizes';
                        break;
                    default:
                        $key = 'MenuPossibleSizes';
                }

                $result[$key] = $this->collMenuPossibleSizes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\API\Models\Menu\MenuSize
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MenuSizeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Menu\MenuSize
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMenuSizeid($value);
                break;
            case 1:
                $this->setEventid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setFactor($value);
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
        $keys = MenuSizeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMenuSizeid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setFactor($arr[$keys[3]]);
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
     * @return $this|\API\Models\Menu\MenuSize The current object, for fluid interface
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
        $criteria = new Criteria(MenuSizeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MenuSizeTableMap::COL_MENU_SIZEID)) {
            $criteria->add(MenuSizeTableMap::COL_MENU_SIZEID, $this->menu_sizeid);
        }
        if ($this->isColumnModified(MenuSizeTableMap::COL_EVENTID)) {
            $criteria->add(MenuSizeTableMap::COL_EVENTID, $this->eventid);
        }
        if ($this->isColumnModified(MenuSizeTableMap::COL_NAME)) {
            $criteria->add(MenuSizeTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(MenuSizeTableMap::COL_FACTOR)) {
            $criteria->add(MenuSizeTableMap::COL_FACTOR, $this->factor);
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
        $criteria = ChildMenuSizeQuery::create();
        $criteria->add(MenuSizeTableMap::COL_MENU_SIZEID, $this->menu_sizeid);
        $criteria->add(MenuSizeTableMap::COL_EVENTID, $this->eventid);

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
        $validPk = null !== $this->getMenuSizeid() &&
            null !== $this->getEventid();

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation fk_menu_sizes_events1 to table event
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
        $pks[0] = $this->getMenuSizeid();
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
        $this->setMenuSizeid($keys[0]);
        $this->setEventid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getMenuSizeid()) && (null === $this->getEventid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Menu\MenuSize (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventid($this->getEventid());
        $copyObj->setName($this->getName());
        $copyObj->setFactor($this->getFactor());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMenuPossibleSizes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMenuPossibleSize($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrderDetails() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderDetail($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setMenuSizeid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Menu\MenuSize Clone of current object.
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
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
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
            $v->addMenuSize($this);
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
                $this->aEvent->addMenuSizes($this);
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
        if ('MenuPossibleSize' == $relationName) {
            return $this->initMenuPossibleSizes();
        }
        if ('OrderDetail' == $relationName) {
            return $this->initOrderDetails();
        }
    }

    /**
     * Clears out the collMenuPossibleSizes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMenuPossibleSizes()
     */
    public function clearMenuPossibleSizes()
    {
        $this->collMenuPossibleSizes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMenuPossibleSizes collection loaded partially.
     */
    public function resetPartialMenuPossibleSizes($v = true)
    {
        $this->collMenuPossibleSizesPartial = $v;
    }

    /**
     * Initializes the collMenuPossibleSizes collection.
     *
     * By default this just sets the collMenuPossibleSizes collection to an empty array (like clearcollMenuPossibleSizes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMenuPossibleSizes($overrideExisting = true)
    {
        if (null !== $this->collMenuPossibleSizes && !$overrideExisting) {
            return;
        }

        $collectionClassName = MenuPossibleSizeTableMap::getTableMap()->getCollectionClassName();

        $this->collMenuPossibleSizes = new $collectionClassName;
        $this->collMenuPossibleSizes->setModel('\API\Models\Menu\MenuPossibleSize');
    }

    /**
     * Gets an array of ChildMenuPossibleSize objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuSize is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMenuPossibleSize[] List of ChildMenuPossibleSize objects
     * @throws PropelException
     */
    public function getMenuPossibleSizes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuPossibleSizesPartial && !$this->isNew();
        if (null === $this->collMenuPossibleSizes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMenuPossibleSizes) {
                // return empty collection
                $this->initMenuPossibleSizes();
            } else {
                $collMenuPossibleSizes = ChildMenuPossibleSizeQuery::create(null, $criteria)
                    ->filterByMenuSize($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMenuPossibleSizesPartial && count($collMenuPossibleSizes)) {
                        $this->initMenuPossibleSizes(false);

                        foreach ($collMenuPossibleSizes as $obj) {
                            if (false == $this->collMenuPossibleSizes->contains($obj)) {
                                $this->collMenuPossibleSizes->append($obj);
                            }
                        }

                        $this->collMenuPossibleSizesPartial = true;
                    }

                    return $collMenuPossibleSizes;
                }

                if ($partial && $this->collMenuPossibleSizes) {
                    foreach ($this->collMenuPossibleSizes as $obj) {
                        if ($obj->isNew()) {
                            $collMenuPossibleSizes[] = $obj;
                        }
                    }
                }

                $this->collMenuPossibleSizes = $collMenuPossibleSizes;
                $this->collMenuPossibleSizesPartial = false;
            }
        }

        return $this->collMenuPossibleSizes;
    }

    /**
     * Sets a collection of ChildMenuPossibleSize objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $menuPossibleSizes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuSize The current object (for fluent API support)
     */
    public function setMenuPossibleSizes(Collection $menuPossibleSizes, ConnectionInterface $con = null)
    {
        /** @var ChildMenuPossibleSize[] $menuPossibleSizesToDelete */
        $menuPossibleSizesToDelete = $this->getMenuPossibleSizes(new Criteria(), $con)->diff($menuPossibleSizes);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->menuPossibleSizesScheduledForDeletion = clone $menuPossibleSizesToDelete;

        foreach ($menuPossibleSizesToDelete as $menuPossibleSizeRemoved) {
            $menuPossibleSizeRemoved->setMenuSize(null);
        }

        $this->collMenuPossibleSizes = null;
        foreach ($menuPossibleSizes as $menuPossibleSize) {
            $this->addMenuPossibleSize($menuPossibleSize);
        }

        $this->collMenuPossibleSizes = $menuPossibleSizes;
        $this->collMenuPossibleSizesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MenuPossibleSize objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MenuPossibleSize objects.
     * @throws PropelException
     */
    public function countMenuPossibleSizes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMenuPossibleSizesPartial && !$this->isNew();
        if (null === $this->collMenuPossibleSizes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMenuPossibleSizes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMenuPossibleSizes());
            }

            $query = ChildMenuPossibleSizeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuSize($this)
                ->count($con);
        }

        return count($this->collMenuPossibleSizes);
    }

    /**
     * Method called to associate a ChildMenuPossibleSize object to this object
     * through the ChildMenuPossibleSize foreign key attribute.
     *
     * @param  ChildMenuPossibleSize $l ChildMenuPossibleSize
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
     */
    public function addMenuPossibleSize(ChildMenuPossibleSize $l)
    {
        if ($this->collMenuPossibleSizes === null) {
            $this->initMenuPossibleSizes();
            $this->collMenuPossibleSizesPartial = true;
        }

        if (!$this->collMenuPossibleSizes->contains($l)) {
            $this->doAddMenuPossibleSize($l);

            if ($this->menuPossibleSizesScheduledForDeletion and $this->menuPossibleSizesScheduledForDeletion->contains($l)) {
                $this->menuPossibleSizesScheduledForDeletion->remove($this->menuPossibleSizesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMenuPossibleSize $menuPossibleSize The ChildMenuPossibleSize object to add.
     */
    protected function doAddMenuPossibleSize(ChildMenuPossibleSize $menuPossibleSize)
    {
        $this->collMenuPossibleSizes[]= $menuPossibleSize;
        $menuPossibleSize->setMenuSize($this);
    }

    /**
     * @param  ChildMenuPossibleSize $menuPossibleSize The ChildMenuPossibleSize object to remove.
     * @return $this|ChildMenuSize The current object (for fluent API support)
     */
    public function removeMenuPossibleSize(ChildMenuPossibleSize $menuPossibleSize)
    {
        if ($this->getMenuPossibleSizes()->contains($menuPossibleSize)) {
            $pos = $this->collMenuPossibleSizes->search($menuPossibleSize);
            $this->collMenuPossibleSizes->remove($pos);
            if (null === $this->menuPossibleSizesScheduledForDeletion) {
                $this->menuPossibleSizesScheduledForDeletion = clone $this->collMenuPossibleSizes;
                $this->menuPossibleSizesScheduledForDeletion->clear();
            }
            $this->menuPossibleSizesScheduledForDeletion[]= clone $menuPossibleSize;
            $menuPossibleSize->setMenuSize(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuSize is new, it will return
     * an empty collection; or if this MenuSize has previously
     * been saved, it will retrieve related MenuPossibleSizes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuSize.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMenuPossibleSize[] List of ChildMenuPossibleSize objects
     */
    public function getMenuPossibleSizesJoinMenu(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMenuPossibleSizeQuery::create(null, $criteria);
        $query->joinWith('Menu', $joinBehavior);

        return $this->getMenuPossibleSizes($query, $con);
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
     * If this ChildMenuSize is new, it will return
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
                    ->filterByMenuSize($this)
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
     * @return $this|ChildMenuSize The current object (for fluent API support)
     */
    public function setOrderDetails(Collection $orderDetails, ConnectionInterface $con = null)
    {
        /** @var OrderDetail[] $orderDetailsToDelete */
        $orderDetailsToDelete = $this->getOrderDetails(new Criteria(), $con)->diff($orderDetails);


        $this->orderDetailsScheduledForDeletion = $orderDetailsToDelete;

        foreach ($orderDetailsToDelete as $orderDetailRemoved) {
            $orderDetailRemoved->setMenuSize(null);
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
                ->filterByMenuSize($this)
                ->count($con);
        }

        return count($this->collOrderDetails);
    }

    /**
     * Method called to associate a OrderDetail object to this object
     * through the OrderDetail foreign key attribute.
     *
     * @param  OrderDetail $l OrderDetail
     * @return $this|\API\Models\Menu\MenuSize The current object (for fluent API support)
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
        $orderDetail->setMenuSize($this);
    }

    /**
     * @param  OrderDetail $orderDetail The OrderDetail object to remove.
     * @return $this|ChildMenuSize The current object (for fluent API support)
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
            $orderDetail->setMenuSize(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuSize is new, it will return
     * an empty collection; or if this MenuSize has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuSize.
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
     * Otherwise if this MenuSize is new, it will return
     * an empty collection; or if this MenuSize has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuSize.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetailsJoinMenuGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailQuery::create(null, $criteria);
        $query->joinWith('MenuGroup', $joinBehavior);

        return $this->getOrderDetails($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuSize is new, it will return
     * an empty collection; or if this MenuSize has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuSize.
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
     * Otherwise if this MenuSize is new, it will return
     * an empty collection; or if this MenuSize has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuSize.
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
     * Otherwise if this MenuSize is new, it will return
     * an empty collection; or if this MenuSize has previously
     * been saved, it will retrieve related OrderDetails from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuSize.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aEvent) {
            $this->aEvent->removeMenuSize($this);
        }
        $this->menu_sizeid = null;
        $this->eventid = null;
        $this->name = null;
        $this->factor = null;
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
            if ($this->collMenuPossibleSizes) {
                foreach ($this->collMenuPossibleSizes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderDetails) {
                foreach ($this->collOrderDetails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMenuPossibleSizes = null;
        $this->collOrderDetails = null;
        $this->aEvent = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MenuSizeTableMap::DEFAULT_STRING_FORMAT);
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
