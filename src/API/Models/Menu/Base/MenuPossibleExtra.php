<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Menu\Menu as ChildMenu;
use API\Models\Menu\MenuExtra as ChildMenuExtra;
use API\Models\Menu\MenuExtraQuery as ChildMenuExtraQuery;
use API\Models\Menu\MenuPossibleExtra as ChildMenuPossibleExtra;
use API\Models\Menu\MenuPossibleExtraQuery as ChildMenuPossibleExtraQuery;
use API\Models\Menu\MenuQuery as ChildMenuQuery;
use API\Models\Menu\Map\MenuPossibleExtraTableMap;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailExtra;
use API\Models\Ordering\OrderDetailExtraQuery;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Ordering\Base\OrderDetailExtra as BaseOrderDetailExtra;
use API\Models\Ordering\Map\OrderDetailExtraTableMap;
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
 * Base class that represents a row from the 'menu_possible_extra' table.
 *
 *
 *
 * @package    propel.generator.API.Models.Menu.Base
 */
abstract class MenuPossibleExtra implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\API\\Models\\Menu\\Map\\MenuPossibleExtraTableMap';


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
     * The value for the menu_possible_extraid field.
     *
     * @var        int
     */
    protected $menu_possible_extraid;

    /**
     * The value for the menu_extraid field.
     *
     * @var        int
     */
    protected $menu_extraid;

    /**
     * The value for the menuid field.
     *
     * @var        int
     */
    protected $menuid;

    /**
     * The value for the price field.
     *
     * @var        string
     */
    protected $price;

    /**
     * @var        ChildMenuExtra
     */
    protected $aMenuExtra;

    /**
     * @var        ChildMenu
     */
    protected $aMenu;

    /**
     * @var        ObjectCollection|OrderDetailExtra[] Collection to store aggregation of OrderDetailExtra objects.
     */
    protected $collOrderDetailExtras;
    protected $collOrderDetailExtrasPartial;

    /**
     * @var        ObjectCollection|OrderDetail[] Cross Collection to store aggregation of OrderDetail objects.
     */
    protected $collOrderDetails;

    /**
     * @var bool
     */
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
     * @var ObjectCollection|OrderDetail[]
     */
    protected $orderDetailsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|OrderDetailExtra[]
     */
    protected $orderDetailExtrasScheduledForDeletion = null;

    /**
     * Initializes internal state of API\Models\Menu\Base\MenuPossibleExtra object.
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
     * Compares this with another <code>MenuPossibleExtra</code> instance.  If
     * <code>obj</code> is an instance of <code>MenuPossibleExtra</code>, delegates to
     * <code>equals(MenuPossibleExtra)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|MenuPossibleExtra The current object, for fluid interface
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
     * Get the [menu_possible_extraid] column value.
     *
     * @return int
     */
    public function getMenuPossibleExtraid()
    {
        return $this->menu_possible_extraid;
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
     * Get the [menuid] column value.
     *
     * @return int
     */
    public function getMenuid()
    {
        return $this->menuid;
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
     * Set the value of [menu_possible_extraid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     */
    public function setMenuPossibleExtraid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_possible_extraid !== $v) {
            $this->menu_possible_extraid = $v;
            $this->modifiedColumns[MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID] = true;
        }

        return $this;
    } // setMenuPossibleExtraid()

    /**
     * Set the value of [menu_extraid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     */
    public function setMenuExtraid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menu_extraid !== $v) {
            $this->menu_extraid = $v;
            $this->modifiedColumns[MenuPossibleExtraTableMap::COL_MENU_EXTRAID] = true;
        }

        if ($this->aMenuExtra !== null && $this->aMenuExtra->getMenuExtraid() !== $v) {
            $this->aMenuExtra = null;
        }

        return $this;
    } // setMenuExtraid()

    /**
     * Set the value of [menuid] column.
     *
     * @param int $v new value
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     */
    public function setMenuid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->menuid !== $v) {
            $this->menuid = $v;
            $this->modifiedColumns[MenuPossibleExtraTableMap::COL_MENUID] = true;
        }

        if ($this->aMenu !== null && $this->aMenu->getMenuid() !== $v) {
            $this->aMenu = null;
        }

        return $this;
    } // setMenuid()

    /**
     * Set the value of [price] column.
     *
     * @param string $v new value
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->price !== $v) {
            $this->price = $v;
            $this->modifiedColumns[MenuPossibleExtraTableMap::COL_PRICE] = true;
        }

        return $this;
    } // setPrice()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MenuPossibleExtraTableMap::translateFieldName('MenuPossibleExtraid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_possible_extraid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MenuPossibleExtraTableMap::translateFieldName('MenuExtraid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menu_extraid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MenuPossibleExtraTableMap::translateFieldName('Menuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->menuid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MenuPossibleExtraTableMap::translateFieldName('Price', TableMap::TYPE_PHPNAME, $indexType)];
            $this->price = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = MenuPossibleExtraTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\API\\Models\\Menu\\MenuPossibleExtra'), 0, $e);
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
        if ($this->aMenuExtra !== null && $this->menu_extraid !== $this->aMenuExtra->getMenuExtraid()) {
            $this->aMenuExtra = null;
        }
        if ($this->aMenu !== null && $this->menuid !== $this->aMenu->getMenuid()) {
            $this->aMenu = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMenuPossibleExtraQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMenuExtra = null;
            $this->aMenu = null;
            $this->collOrderDetailExtras = null;

            $this->collOrderDetails = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see MenuPossibleExtra::setDeleted()
     * @see MenuPossibleExtra::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMenuPossibleExtraQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
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
                MenuPossibleExtraTableMap::addInstanceToPool($this);
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

            if ($this->aMenuExtra !== null) {
                if ($this->aMenuExtra->isModified() || $this->aMenuExtra->isNew()) {
                    $affectedRows += $this->aMenuExtra->save($con);
                }
                $this->setMenuExtra($this->aMenuExtra);
            }

            if ($this->aMenu !== null) {
                if ($this->aMenu->isModified() || $this->aMenu->isNew()) {
                    $affectedRows += $this->aMenu->save($con);
                }
                $this->setMenu($this->aMenu);
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

            if ($this->orderDetailsScheduledForDeletion !== null) {
                if (!$this->orderDetailsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->orderDetailsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getMenuPossibleExtraid();
                        $entryPk[0] = $entry->getOrderDetailid();
                        $pks[] = $entryPk;
                    }

                    \API\Models\Ordering\OrderDetailExtraQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->orderDetailsScheduledForDeletion = null;
                }

            }

            if ($this->collOrderDetails) {
                foreach ($this->collOrderDetails as $orderDetail) {
                    if (!$orderDetail->isDeleted() && ($orderDetail->isNew() || $orderDetail->isModified())) {
                        $orderDetail->save($con);
                    }
                }
            }


            if ($this->orderDetailExtrasScheduledForDeletion !== null) {
                if (!$this->orderDetailExtrasScheduledForDeletion->isEmpty()) {
                    \API\Models\Ordering\OrderDetailExtraQuery::create()
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

        $this->modifiedColumns[MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID] = true;
        if (null !== $this->menu_possible_extraid) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_possible_extraid';
        }
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_MENU_EXTRAID)) {
            $modifiedColumns[':p' . $index++]  = 'menu_extraid';
        }
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_MENUID)) {
            $modifiedColumns[':p' . $index++]  = 'menuid';
        }
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'price';
        }

        $sql = sprintf(
            'INSERT INTO menu_possible_extra (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'menu_possible_extraid':
                        $stmt->bindValue($identifier, $this->menu_possible_extraid, PDO::PARAM_INT);
                        break;
                    case 'menu_extraid':
                        $stmt->bindValue($identifier, $this->menu_extraid, PDO::PARAM_INT);
                        break;
                    case 'menuid':
                        $stmt->bindValue($identifier, $this->menuid, PDO::PARAM_INT);
                        break;
                    case 'price':
                        $stmt->bindValue($identifier, $this->price, PDO::PARAM_STR);
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
        $this->setMenuPossibleExtraid($pk);

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
        $pos = MenuPossibleExtraTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getMenuPossibleExtraid();
                break;
            case 1:
                return $this->getMenuExtraid();
                break;
            case 2:
                return $this->getMenuid();
                break;
            case 3:
                return $this->getPrice();
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

        if (isset($alreadyDumpedObjects['MenuPossibleExtra'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MenuPossibleExtra'][$this->hashCode()] = true;
        $keys = MenuPossibleExtraTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getMenuPossibleExtraid(),
            $keys[1] => $this->getMenuExtraid(),
            $keys[2] => $this->getMenuid(),
            $keys[3] => $this->getPrice(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aMenuExtra) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'menuExtra';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'menu_extra';
                        break;
                    default:
                        $key = 'MenuExtra';
                }

                $result[$key] = $this->aMenuExtra->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\API\Models\Menu\MenuPossibleExtra
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MenuPossibleExtraTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\API\Models\Menu\MenuPossibleExtra
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setMenuPossibleExtraid($value);
                break;
            case 1:
                $this->setMenuExtraid($value);
                break;
            case 2:
                $this->setMenuid($value);
                break;
            case 3:
                $this->setPrice($value);
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
        $keys = MenuPossibleExtraTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setMenuPossibleExtraid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setMenuExtraid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setMenuid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setPrice($arr[$keys[3]]);
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
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object, for fluid interface
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
        $criteria = new Criteria(MenuPossibleExtraTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID)) {
            $criteria->add(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $this->menu_possible_extraid);
        }
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_MENU_EXTRAID)) {
            $criteria->add(MenuPossibleExtraTableMap::COL_MENU_EXTRAID, $this->menu_extraid);
        }
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_MENUID)) {
            $criteria->add(MenuPossibleExtraTableMap::COL_MENUID, $this->menuid);
        }
        if ($this->isColumnModified(MenuPossibleExtraTableMap::COL_PRICE)) {
            $criteria->add(MenuPossibleExtraTableMap::COL_PRICE, $this->price);
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
        $criteria = ChildMenuPossibleExtraQuery::create();
        $criteria->add(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $this->menu_possible_extraid);

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
        $validPk = null !== $this->getMenuPossibleExtraid();

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
        return $this->getMenuPossibleExtraid();
    }

    /**
     * Generic method to set the primary key (menu_possible_extraid column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setMenuPossibleExtraid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getMenuPossibleExtraid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \API\Models\Menu\MenuPossibleExtra (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMenuExtraid($this->getMenuExtraid());
        $copyObj->setMenuid($this->getMenuid());
        $copyObj->setPrice($this->getPrice());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getOrderDetailExtras() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrderDetailExtra($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setMenuPossibleExtraid(NULL); // this is a auto-increment column, so set to default value
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
     * @return \API\Models\Menu\MenuPossibleExtra Clone of current object.
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
     * Declares an association between this object and a ChildMenuExtra object.
     *
     * @param  ChildMenuExtra $v
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenuExtra(ChildMenuExtra $v = null)
    {
        if ($v === null) {
            $this->setMenuExtraid(NULL);
        } else {
            $this->setMenuExtraid($v->getMenuExtraid());
        }

        $this->aMenuExtra = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMenuExtra object, it will not be re-added.
        if ($v !== null) {
            $v->addMenuPossibleExtra($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMenuExtra object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMenuExtra The associated ChildMenuExtra object.
     * @throws PropelException
     */
    public function getMenuExtra(ConnectionInterface $con = null)
    {
        if ($this->aMenuExtra === null && ($this->menu_extraid !== null)) {
            $this->aMenuExtra = ChildMenuExtraQuery::create()->findPk($this->menu_extraid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenuExtra->addMenuPossibleExtras($this);
             */
        }

        return $this->aMenuExtra;
    }

    /**
     * Declares an association between this object and a ChildMenu object.
     *
     * @param  ChildMenu $v
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMenu(ChildMenu $v = null)
    {
        if ($v === null) {
            $this->setMenuid(NULL);
        } else {
            $this->setMenuid($v->getMenuid());
        }

        $this->aMenu = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMenu object, it will not be re-added.
        if ($v !== null) {
            $v->addMenuPossibleExtra($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMenu object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMenu The associated ChildMenu object.
     * @throws PropelException
     */
    public function getMenu(ConnectionInterface $con = null)
    {
        if ($this->aMenu === null && ($this->menuid !== null)) {
            $this->aMenu = ChildMenuQuery::create()->findPk($this->menuid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMenu->addMenuPossibleExtras($this);
             */
        }

        return $this->aMenu;
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
        if ('OrderDetailExtra' == $relationName) {
            return $this->initOrderDetailExtras();
        }
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
        $this->collOrderDetailExtras->setModel('\API\Models\Ordering\OrderDetailExtra');
    }

    /**
     * Gets an array of OrderDetailExtra objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuPossibleExtra is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|OrderDetailExtra[] List of OrderDetailExtra objects
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
                $collOrderDetailExtras = OrderDetailExtraQuery::create(null, $criteria)
                    ->filterByMenuPossibleExtra($this)
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
     * Sets a collection of OrderDetailExtra objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $orderDetailExtras A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuPossibleExtra The current object (for fluent API support)
     */
    public function setOrderDetailExtras(Collection $orderDetailExtras, ConnectionInterface $con = null)
    {
        /** @var OrderDetailExtra[] $orderDetailExtrasToDelete */
        $orderDetailExtrasToDelete = $this->getOrderDetailExtras(new Criteria(), $con)->diff($orderDetailExtras);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->orderDetailExtrasScheduledForDeletion = clone $orderDetailExtrasToDelete;

        foreach ($orderDetailExtrasToDelete as $orderDetailExtraRemoved) {
            $orderDetailExtraRemoved->setMenuPossibleExtra(null);
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
     * Returns the number of related BaseOrderDetailExtra objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOrderDetailExtra objects.
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

            $query = OrderDetailExtraQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMenuPossibleExtra($this)
                ->count($con);
        }

        return count($this->collOrderDetailExtras);
    }

    /**
     * Method called to associate a OrderDetailExtra object to this object
     * through the OrderDetailExtra foreign key attribute.
     *
     * @param  OrderDetailExtra $l OrderDetailExtra
     * @return $this|\API\Models\Menu\MenuPossibleExtra The current object (for fluent API support)
     */
    public function addOrderDetailExtra(OrderDetailExtra $l)
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
     * @param OrderDetailExtra $orderDetailExtra The OrderDetailExtra object to add.
     */
    protected function doAddOrderDetailExtra(OrderDetailExtra $orderDetailExtra)
    {
        $this->collOrderDetailExtras[]= $orderDetailExtra;
        $orderDetailExtra->setMenuPossibleExtra($this);
    }

    /**
     * @param  OrderDetailExtra $orderDetailExtra The OrderDetailExtra object to remove.
     * @return $this|ChildMenuPossibleExtra The current object (for fluent API support)
     */
    public function removeOrderDetailExtra(OrderDetailExtra $orderDetailExtra)
    {
        if ($this->getOrderDetailExtras()->contains($orderDetailExtra)) {
            $pos = $this->collOrderDetailExtras->search($orderDetailExtra);
            $this->collOrderDetailExtras->remove($pos);
            if (null === $this->orderDetailExtrasScheduledForDeletion) {
                $this->orderDetailExtrasScheduledForDeletion = clone $this->collOrderDetailExtras;
                $this->orderDetailExtrasScheduledForDeletion->clear();
            }
            $this->orderDetailExtrasScheduledForDeletion[]= clone $orderDetailExtra;
            $orderDetailExtra->setMenuPossibleExtra(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MenuPossibleExtra is new, it will return
     * an empty collection; or if this MenuPossibleExtra has previously
     * been saved, it will retrieve related OrderDetailExtras from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MenuPossibleExtra.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|OrderDetailExtra[] List of OrderDetailExtra objects
     */
    public function getOrderDetailExtrasJoinOrderDetail(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = OrderDetailExtraQuery::create(null, $criteria);
        $query->joinWith('OrderDetail', $joinBehavior);

        return $this->getOrderDetailExtras($query, $con);
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
     * Initializes the collOrderDetails crossRef collection.
     *
     * By default this just sets the collOrderDetails collection to an empty collection (like clearOrderDetails());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initOrderDetails()
    {
        $collectionClassName = OrderDetailExtraTableMap::getTableMap()->getCollectionClassName();

        $this->collOrderDetails = new $collectionClassName;
        $this->collOrderDetailsPartial = true;
        $this->collOrderDetails->setModel('\API\Models\Ordering\OrderDetail');
    }

    /**
     * Checks if the collOrderDetails collection is loaded.
     *
     * @return bool
     */
    public function isOrderDetailsLoaded()
    {
        return null !== $this->collOrderDetails;
    }

    /**
     * Gets a collection of OrderDetail objects related by a many-to-many relationship
     * to the current object by way of the order_detail_extra cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMenuPossibleExtra is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|OrderDetail[] List of OrderDetail objects
     */
    public function getOrderDetails(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailsPartial && !$this->isNew();
        if (null === $this->collOrderDetails || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collOrderDetails) {
                    $this->initOrderDetails();
                }
            } else {

                $query = OrderDetailQuery::create(null, $criteria)
                    ->filterByMenuPossibleExtra($this);
                $collOrderDetails = $query->find($con);
                if (null !== $criteria) {
                    return $collOrderDetails;
                }

                if ($partial && $this->collOrderDetails) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collOrderDetails as $obj) {
                        if (!$collOrderDetails->contains($obj)) {
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
     * Sets a collection of OrderDetail objects related by a many-to-many relationship
     * to the current object by way of the order_detail_extra cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $orderDetails A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildMenuPossibleExtra The current object (for fluent API support)
     */
    public function setOrderDetails(Collection $orderDetails, ConnectionInterface $con = null)
    {
        $this->clearOrderDetails();
        $currentOrderDetails = $this->getOrderDetails();

        $orderDetailsScheduledForDeletion = $currentOrderDetails->diff($orderDetails);

        foreach ($orderDetailsScheduledForDeletion as $toDelete) {
            $this->removeOrderDetail($toDelete);
        }

        foreach ($orderDetails as $orderDetail) {
            if (!$currentOrderDetails->contains($orderDetail)) {
                $this->doAddOrderDetail($orderDetail);
            }
        }

        $this->collOrderDetailsPartial = false;
        $this->collOrderDetails = $orderDetails;

        return $this;
    }

    /**
     * Gets the number of OrderDetail objects related by a many-to-many relationship
     * to the current object by way of the order_detail_extra cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related OrderDetail objects
     */
    public function countOrderDetails(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOrderDetailsPartial && !$this->isNew();
        if (null === $this->collOrderDetails || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrderDetails) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getOrderDetails());
                }

                $query = OrderDetailQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByMenuPossibleExtra($this)
                    ->count($con);
            }
        } else {
            return count($this->collOrderDetails);
        }
    }

    /**
     * Associate a OrderDetail to this object
     * through the order_detail_extra cross reference table.
     *
     * @param OrderDetail $orderDetail
     * @return ChildMenuPossibleExtra The current object (for fluent API support)
     */
    public function addOrderDetail(OrderDetail $orderDetail)
    {
        if ($this->collOrderDetails === null) {
            $this->initOrderDetails();
        }

        if (!$this->getOrderDetails()->contains($orderDetail)) {
            // only add it if the **same** object is not already associated
            $this->collOrderDetails->push($orderDetail);
            $this->doAddOrderDetail($orderDetail);
        }

        return $this;
    }

    /**
     *
     * @param OrderDetail $orderDetail
     */
    protected function doAddOrderDetail(OrderDetail $orderDetail)
    {
        $orderDetailExtra = new OrderDetailExtra();

        $orderDetailExtra->setOrderDetail($orderDetail);

        $orderDetailExtra->setMenuPossibleExtra($this);

        $this->addOrderDetailExtra($orderDetailExtra);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$orderDetail->isMenuPossibleExtrasLoaded()) {
            $orderDetail->initMenuPossibleExtras();
            $orderDetail->getMenuPossibleExtras()->push($this);
        } elseif (!$orderDetail->getMenuPossibleExtras()->contains($this)) {
            $orderDetail->getMenuPossibleExtras()->push($this);
        }

    }

    /**
     * Remove orderDetail of this object
     * through the order_detail_extra cross reference table.
     *
     * @param OrderDetail $orderDetail
     * @return ChildMenuPossibleExtra The current object (for fluent API support)
     */
    public function removeOrderDetail(OrderDetail $orderDetail)
    {
        if ($this->getOrderDetails()->contains($orderDetail)) { $orderDetailExtra = new OrderDetailExtra();

            $orderDetailExtra->setOrderDetail($orderDetail);
            if ($orderDetail->isMenuPossibleExtrasLoaded()) {
                //remove the back reference if available
                $orderDetail->getMenuPossibleExtras()->removeObject($this);
            }

            $orderDetailExtra->setMenuPossibleExtra($this);
            $this->removeOrderDetailExtra(clone $orderDetailExtra);
            $orderDetailExtra->clear();

            $this->collOrderDetails->remove($this->collOrderDetails->search($orderDetail));

            if (null === $this->orderDetailsScheduledForDeletion) {
                $this->orderDetailsScheduledForDeletion = clone $this->collOrderDetails;
                $this->orderDetailsScheduledForDeletion->clear();
            }

            $this->orderDetailsScheduledForDeletion->push($orderDetail);
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
        if (null !== $this->aMenuExtra) {
            $this->aMenuExtra->removeMenuPossibleExtra($this);
        }
        if (null !== $this->aMenu) {
            $this->aMenu->removeMenuPossibleExtra($this);
        }
        $this->menu_possible_extraid = null;
        $this->menu_extraid = null;
        $this->menuid = null;
        $this->price = null;
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
            if ($this->collOrderDetailExtras) {
                foreach ($this->collOrderDetailExtras as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrderDetails) {
                foreach ($this->collOrderDetails as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collOrderDetailExtras = null;
        $this->collOrderDetails = null;
        $this->aMenuExtra = null;
        $this->aMenu = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MenuPossibleExtraTableMap::DEFAULT_STRING_FORMAT);
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
