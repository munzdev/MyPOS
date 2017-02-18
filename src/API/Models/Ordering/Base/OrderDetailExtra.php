<?php

namespace API\Models\Ordering\Base;

use \Exception;
use \PDO;
use API\Models\Menu\MenuPossibleExtra;
use API\Models\Menu\MenuPossibleExtraQuery;
use API\Models\Ordering\OrderDetail as ChildOrderDetail;
use API\Models\Ordering\OrderDetailExtraQuery as ChildOrderDetailExtraQuery;
use API\Models\Ordering\OrderDetailQuery as ChildOrderDetailQuery;
use API\Models\Ordering\Map\OrderDetailExtraTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'order_detail_extra' table.
 *
 * @package propel.generator.API.Models.Ordering.Base
 */
abstract class OrderDetailExtra implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Ordering\\Map\\OrderDetailExtraTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     *
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     *
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     *
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     *
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the order_detailid field.
     *
     * @var int
     */
    protected $order_detailid;

    /**
     * The value for the menu_possible_extraid field.
     *
     * @var int
     */
    protected $menu_possible_extraid;

    /**
     * @var        MenuPossibleExtra
     */
    protected $aMenuPossibleExtra;

    /**
     * @var        ChildOrderDetail
     */
    protected $aOrderDetail;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of API\Models\Ordering\Base\OrderDetailExtra object.
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
     * @param  string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     *
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
     *
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     *
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     *
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
     * Compares this with another <code>OrderDetailExtra</code> instance.  If
     * <code>obj</code> is an instance of <code>OrderDetailExtra</code>, delegates to
     * <code>equals(OrderDetailExtra)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed $obj The object to compare to.
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
     * @param  string $name The virtual column name
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
     * @return $this|OrderDetailExtra The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string $msg
     * @param  int    $priority One of the Propel::LOG_* logging levels
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

        foreach ($serializableProperties as $property) {
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
     * Get the [menu_possible_extraid] column value.
     *
     * @return int
     */
    public function getMenuPossibleExtraid()
    {
        return $this->menu_possible_extraid;
    }

    /**
     * Set the value of [order_detailid] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\OrderDetailExtra The current object (for fluent API support)
     */
    public function setOrderDetailid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_detailid !== $v) {
            $this->order_detailid = $v;
            $this->modifiedColumns[OrderDetailExtraTableMap::COL_ORDER_DETAILID] = true;
        }

        if ($this->aOrderDetail !== null && $this->aOrderDetail->getOrderDetailid() !== $v) {
            $this->aOrderDetail = null;
        }

        return $this;
    } // setOrderDetailid()

    /**
     * Set the value of [menu_possible_extraid] column.
     *
     * @param  int $v new value
     * @return $this|\API\Models\Ordering\OrderDetailExtra The current object (for fluent API support)
     */
    public function setMenuPossibleExtraid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_possible_extraid !== $v) {
            $this->menu_possible_extraid = $v;
            $this->modifiedColumns[OrderDetailExtraTableMap::COL_MENU_POSSIBLE_EXTRAID] = true;
        }

        if ($this->aMenuPossibleExtra !== null && $this->aMenuPossibleExtra->getMenuPossibleExtraid() !== $v) {
            $this->aMenuPossibleExtra = null;
        }

        return $this;
    } // setMenuPossibleExtraid()

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
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrderDetailExtraTableMap::translateFieldName('OrderDetailid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_detailid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrderDetailExtraTableMap::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_possible_extraid = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = OrderDetailExtraTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Ordering\\OrderDetailExtra'), 0, $e);
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
        if ($this->aOrderDetail !== null && $this->order_detailid !== $this->aOrderDetail->getOrderDetailid()) {
            $this->aOrderDetail = null;
        }
        if ($this->aMenuPossibleExtra !== null && $this->menu_possible_extraid !== $this->aMenuPossibleExtra->getMenuPossibleExtraid()) {
            $this->aMenuPossibleExtra = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param  boolean             $deep (optional) Whether to also de-associated any related objects.
     * @param  ConnectionInterface $con  (optional) The ConnectionInterface connection to use.
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
            $con = Propel::getServiceContainer()->getReadConnection(OrderDetailExtraTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrderDetailExtraQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMenuPossibleExtra = null;
            $this->aOrderDetail = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param  ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see    OrderDetailExtra::setDeleted()
     * @see    OrderDetailExtra::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailExtraTableMap::DATABASE_NAME);
        }

        $con->transaction(
            function () use ($con) {
                $deleteQuery = ChildOrderDetailExtraQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
                $ret = $this->preDelete($con);
                if ($ret) {
                    $deleteQuery->delete($con);
                    $this->postDelete($con);
                    $this->setDeleted(true);
                }
            }
        );
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param  ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see    doSave()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailExtraTableMap::DATABASE_NAME);
        }

        return $con->transaction(
            function () use ($con) {
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
                    OrderDetailExtraTableMap::addInstanceToPool($this);
                } else {
                    $affectedRows = 0;
                }

                return $affectedRows;
            }
        );
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param  ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see    save()
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

            if ($this->aMenuPossibleExtra !== null) {
                if ($this->aMenuPossibleExtra->isModified() || $this->aMenuPossibleExtra->isNew()) {
                    $affectedRows += $this->aMenuPossibleExtra->save($con);
                }
                $this->setMenuPossibleExtra($this->aMenuPossibleExtra);
            }

            if ($this->aOrderDetail !== null) {
                if ($this->aOrderDetail->isModified() || $this->aOrderDetail->isNew()) {
                    $affectedRows += $this->aOrderDetail->save($con);
                }
                $this->setOrderDetail($this->aOrderDetail);
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

            $this->alreadyInSave = false;
        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws PropelException
     * @see    doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrderDetailExtraTableMap::COL_ORDER_DETAILID)) {
            $modifiedColumns[':p' . $index++]  = 'order_detailid';
        }
        if ($this->isColumnModified(OrderDetailExtraTableMap::COL_MENU_POSSIBLE_EXTRAID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_possible_extraid';
        }

        $sql = sprintf(
            'INSERT INTO order_detail_extra (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'order_detailid':
                        $stmt->bindValue($identifier, $this->order_detailid, PDO::PARAM_INT);
                        break;
                    case 'menu_possible_extraid':
                        $stmt->bindValue($identifier, $this->menu_possible_extraid, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see    doSave()
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
     * @param  string $name name
     * @param  string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderDetailExtraTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getOrderDetailid();
                break;
            case 1:
                return $this->getMenuPossibleExtraid();
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
     * @param string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to
     *                                            TableMap::TYPE_PHPNAME.
     * @param boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array   $alreadyDumpedObjects   List of objects to skip to avoid recursion
     * @param boolean $includeForeignObjects  (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['OrderDetailExtra'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['OrderDetailExtra'][$this->hashCode()] = true;
        $keys = OrderDetailExtraTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getOrderDetailid(),
            $keys[1] => $this->getMenuPossibleExtraid(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aMenuPossibleExtra) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuPossibleExtra';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_possible_extra';
                        break;
                    default:
                        $key = 'MenuPossibleExtra';
                }

                $result[$key] = $this->aMenuPossibleExtra->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->aOrderDetail) {
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orderDetail';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'order_detail';
                        break;
                    default:
                        $key = 'OrderDetail';
                }

                $result[$key] = $this->aOrderDetail->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type  The type of fieldname the $name is of:
     *                       one of the class type constants
     *                       TableMap::TYPE_PHPNAME,
     *                       TableMap::TYPE_CAMELNAME
     *                       TableMap::TYPE_COLNAME,
     *                       TableMap::TYPE_FIELDNAME,
     *                       TableMap::TYPE_NUM. Defaults to
     *                       TableMap::TYPE_PHPNAME.
     * @return $this|\API\Models\Ordering\OrderDetailExtra
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderDetailExtraTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int   $pos   position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Ordering\OrderDetailExtra
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setOrderDetailid($value);
                break;
            case 1:
                $this->setMenuPossibleExtraid($value);
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
     * @param  array  $arr     An array to populate the object from.
     * @param  string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = OrderDetailExtraTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setOrderDetailid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setMenuPossibleExtraid($arr[$keys[1]]);
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
     * @param mixed  $parser  A AbstractParser instance,
     *                        or a format name ('XML',
     *                        'YAML', 'JSON', 'CSV')
     * @param string $data    The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\API\Models\Ordering\OrderDetailExtra The current object, for fluid interface
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
        $criteria = new Criteria(OrderDetailExtraTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrderDetailExtraTableMap::COL_ORDER_DETAILID)) {
            $criteria->add(OrderDetailExtraTableMap::COL_ORDER_DETAILID, $this->order_detailid);
        }
        if ($this->isColumnModified(OrderDetailExtraTableMap::COL_MENU_POSSIBLE_EXTRAID)) {
            $criteria->add(OrderDetailExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $this->menu_possible_extraid);
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
        $criteria = ChildOrderDetailExtraQuery::create();
        $criteria->add(OrderDetailExtraTableMap::COL_ORDER_DETAILID, $this->order_detailid);
        $criteria->add(OrderDetailExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $this->menu_possible_extraid);

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
        $validPk = null !== $this->getOrderDetailid() &&
            null !== $this->getMenuPossibleExtraid();

        $validPrimaryKeyFKs = 2;
        $primaryKeyFKs = [];

        //relation fk_orders_detail_sizes_menues_possible_extras1 to table menu_possible_extra
        if ($this->aMenuPossibleExtra && $hash = spl_object_hash($this->aMenuPossibleExtra)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

        //relation fk_orders_detail_sizes_orders_details1 to table order_detail
        if ($this->aOrderDetail && $hash = spl_object_hash($this->aOrderDetail)) {
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
     *
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getOrderDetailid();
        $pks[1] = $this->getMenuPossibleExtraid();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param  array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setOrderDetailid($keys[0]);
        $this->setMenuPossibleExtraid($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return (null === $this->getOrderDetailid()) && (null === $this->getMenuPossibleExtraid());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  object  $copyObj  An object of \API\Models\Ordering\OrderDetailExtra (or compatible) type.
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param  boolean $makeNew  Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOrderDetailid($this->getOrderDetailid());
        $copyObj->setMenuPossibleExtraid($this->getMenuPossibleExtraid());
        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return \API\Models\Ordering\OrderDetailExtra Clone of current object.
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
     * Declares an association between this object and a MenuPossibleExtra object.
     *
     * @param  MenuPossibleExtra $v
     * @return $this|\API\Models\Ordering\OrderDetailExtra The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuPossibleExtra(MenuPossibleExtra $v = null)
    {
        if ($v === null) {
            $this->setMenuPossibleExtraid(null);
        } else {
            $this->setMenuPossibleExtraid($v->getMenuPossibleExtraid());
        }

        $this->aMenuPossibleExtra = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the MenuPossibleExtra object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetailExtra($this);
        }


        return $this;
    }


    /**
     * Get the associated MenuPossibleExtra object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return MenuPossibleExtra The associated MenuPossibleExtra object.
     * @throws PropelException
     */
    public function getMenuPossibleExtra(ConnectionInterface $con = null)
    {
        if ($this->aMenuPossibleExtra === null && ($this->menu_possible_extraid !== null)) {
            $this->aMenuPossibleExtra = MenuPossibleExtraQuery::create()->findPk($this->menu_possible_extraid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuPossibleExtra->addOrderDetailExtras($this);
             */
        }

        return $this->aMenuPossibleExtra;
    }

    /**
     * Declares an association between this object and a ChildOrderDetail object.
     *
     * @param  ChildOrderDetail $v
     * @return $this|\API\Models\Ordering\OrderDetailExtra The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOrderDetail(ChildOrderDetail $v = null)
    {
        if ($v === null) {
            $this->setOrderDetailid(null);
        } else {
            $this->setOrderDetailid($v->getOrderDetailid());
        }

        $this->aOrderDetail = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildOrderDetail object, it will not be re-added.
        if ($v !== null) {
            $v->addOrderDetailExtra($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildOrderDetail object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildOrderDetail The associated ChildOrderDetail object.
     * @throws PropelException
     */
    public function getOrderDetail(ConnectionInterface $con = null)
    {
        if ($this->aOrderDetail === null && ($this->order_detailid !== null)) {
            $this->aOrderDetail = ChildOrderDetailQuery::create()->findPk($this->order_detailid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOrderDetail->addOrderDetailExtras($this);
             */
        }

        return $this->aOrderDetail;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aMenuPossibleExtra) {
            $this->aMenuPossibleExtra->removeOrderDetailExtra($this);
        }
        if (null !== $this->aOrderDetail) {
            $this->aOrderDetail->removeOrderDetailExtra($this);
        }
        $this->order_detailid = null;
        $this->menu_possible_extraid = null;
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
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

        $this->aMenuPossibleExtra = null;
        $this->aOrderDetail = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OrderDetailExtraTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
