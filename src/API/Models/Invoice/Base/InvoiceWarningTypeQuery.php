<?php

namespace API\Models\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Invoice\InvoiceWarningType as ChildInvoiceWarningType;
use API\Models\Invoice\InvoiceWarningTypeQuery as ChildInvoiceWarningTypeQuery;
use API\Models\Invoice\Map\InvoiceWarningTypeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoice_warning_type' table.
 *
 * @method ChildInvoiceWarningTypeQuery orderByInvoiceWarningTypeid($order = Criteria::ASC) Order by the invoice_warning_typeid column
 * @method ChildInvoiceWarningTypeQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method ChildInvoiceWarningTypeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ChildInvoiceWarningTypeQuery orderByExtraPrice($order = Criteria::ASC) Order by the extra_price column
 *
 * @method ChildInvoiceWarningTypeQuery groupByInvoiceWarningTypeid() Group by the invoice_warning_typeid column
 * @method ChildInvoiceWarningTypeQuery groupByEventid() Group by the eventid column
 * @method ChildInvoiceWarningTypeQuery groupByName() Group by the name column
 * @method ChildInvoiceWarningTypeQuery groupByExtraPrice() Group by the extra_price column
 *
 * @method ChildInvoiceWarningTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ChildInvoiceWarningTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ChildInvoiceWarningTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ChildInvoiceWarningTypeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method ChildInvoiceWarningTypeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method ChildInvoiceWarningTypeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method ChildInvoiceWarningTypeQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method ChildInvoiceWarningTypeQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method ChildInvoiceWarningTypeQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method ChildInvoiceWarningTypeQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method ChildInvoiceWarningTypeQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method ChildInvoiceWarningTypeQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method ChildInvoiceWarningTypeQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method ChildInvoiceWarningTypeQuery leftJoinInvoiceWarning($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceWarning relation
 * @method ChildInvoiceWarningTypeQuery rightJoinInvoiceWarning($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceWarning relation
 * @method ChildInvoiceWarningTypeQuery innerJoinInvoiceWarning($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceWarning relation
 *
 * @method ChildInvoiceWarningTypeQuery joinWithInvoiceWarning($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceWarning relation
 *
 * @method ChildInvoiceWarningTypeQuery leftJoinWithInvoiceWarning() Adds a LEFT JOIN clause and with to the query using the InvoiceWarning relation
 * @method ChildInvoiceWarningTypeQuery rightJoinWithInvoiceWarning() Adds a RIGHT JOIN clause and with to the query using the InvoiceWarning relation
 * @method ChildInvoiceWarningTypeQuery innerJoinWithInvoiceWarning() Adds a INNER JOIN clause and with to the query using the InvoiceWarning relation
 *
 * @method \API\Models\Event\EventQuery|\API\Models\Invoice\InvoiceWarningQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method ChildInvoiceWarningType findOne(ConnectionInterface $con = null) Return the first ChildInvoiceWarningType matching the query
 * @method ChildInvoiceWarningType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoiceWarningType matching the query, or a new ChildInvoiceWarningType object populated from the query conditions when no match is found
 *
 * @method ChildInvoiceWarningType findOneByInvoiceWarningTypeid(int $invoice_warning_typeid) Return the first ChildInvoiceWarningType filtered by the invoice_warning_typeid column
 * @method ChildInvoiceWarningType findOneByEventid(int $eventid) Return the first ChildInvoiceWarningType filtered by the eventid column
 * @method ChildInvoiceWarningType findOneByName(string $name) Return the first ChildInvoiceWarningType filtered by the name column
 * @method ChildInvoiceWarningType findOneByExtraPrice(string $extra_price) Return the first ChildInvoiceWarningType filtered by the extra_price column *

 * @method ChildInvoiceWarningType requirePk($key, ConnectionInterface $con = null) Return the ChildInvoiceWarningType by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildInvoiceWarningType requireOne(ConnectionInterface $con = null) Return the first ChildInvoiceWarningType matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildInvoiceWarningType requireOneByInvoiceWarningTypeid(int $invoice_warning_typeid) Return the first ChildInvoiceWarningType filtered by the invoice_warning_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildInvoiceWarningType requireOneByEventid(int $eventid) Return the first ChildInvoiceWarningType filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildInvoiceWarningType requireOneByName(string $name) Return the first ChildInvoiceWarningType filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildInvoiceWarningType requireOneByExtraPrice(string $extra_price) Return the first ChildInvoiceWarningType filtered by the extra_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildInvoiceWarningType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoiceWarningType objects based on current ModelCriteria
 * @method ChildInvoiceWarningType[]|ObjectCollection findByInvoiceWarningTypeid(int $invoice_warning_typeid) Return ChildInvoiceWarningType objects filtered by the invoice_warning_typeid column
 * @method ChildInvoiceWarningType[]|ObjectCollection findByEventid(int $eventid) Return ChildInvoiceWarningType objects filtered by the eventid column
 * @method ChildInvoiceWarningType[]|ObjectCollection findByName(string $name) Return ChildInvoiceWarningType objects filtered by the name column
 * @method ChildInvoiceWarningType[]|ObjectCollection findByExtraPrice(string $extra_price) Return ChildInvoiceWarningType objects filtered by the extra_price column
 * @method ChildInvoiceWarningType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class InvoiceWarningTypeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Invoice\Base\InvoiceWarningTypeQuery object.
     *
     * @param string $dbName     The database name
     * @param string $modelName  The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Invoice\\InvoiceWarningType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoiceWarningTypeQuery object.
     *
     * @param string   $modelAlias The alias of a model in the query
     * @param Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildInvoiceWarningTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoiceWarningTypeQuery) {
            return $criteria;
        }
        $query = new ChildInvoiceWarningTypeQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildInvoiceWarningType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoiceWarningTypeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if ($this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoiceWarningTypeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceWarningType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoice_warning_typeid, eventid, name, extra_price FROM invoice_warning_type WHERE invoice_warning_typeid = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /**
 * @var ChildInvoiceWarningType $obj
*/
            $obj = new ChildInvoiceWarningType();
            $obj->hydrate($row);
            InvoiceWarningTypeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildInvoiceWarningType|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     *
     * @param array               $keys Primary keys to use for the query
     * @param ConnectionInterface $con  an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the invoice_warning_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceWarningTypeid(1234); // WHERE invoice_warning_typeid = 1234
     * $query->filterByInvoiceWarningTypeid(array(12, 34)); // WHERE invoice_warning_typeid IN (12, 34)
     * $query->filterByInvoiceWarningTypeid(array('min' => 12)); // WHERE invoice_warning_typeid > 12
     * </code>
     *
     * @param mixed  $invoiceWarningTypeid The value to use as filter.
     *                                         Use scalar values for
     *                                         equality. Use array values
     *                                         for in_array() equivalent.
     *                                         Use associative array('min'
     *                                         => $minValue, 'max' =>
     *                                         $maxValue) for intervals.
     * @param string $comparison           Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByInvoiceWarningTypeid($invoiceWarningTypeid = null, $comparison = null)
    {
        if (is_array($invoiceWarningTypeid)) {
            $useMinMax = false;
            if (isset($invoiceWarningTypeid['min'])) {
                $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceWarningTypeid['max'])) {
                $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningTypeid, $comparison);
    }

    /**
     * Filter the query on the eventid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventid(1234); // WHERE eventid = 1234
     * $query->filterByEventid(array(12, 34)); // WHERE eventid IN (12, 34)
     * $query->filterByEventid(array('min' => 12)); // WHERE eventid > 12
     * </code>
     *
     * @see filterByEvent()
     *
     * @param mixed  $eventid    The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_EVENTID, $eventid, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param string $name       The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the extra_price column
     *
     * Example usage:
     * <code>
     * $query->filterByExtraPrice(1234); // WHERE extra_price = 1234
     * $query->filterByExtraPrice(array(12, 34)); // WHERE extra_price IN (12, 34)
     * $query->filterByExtraPrice(array('min' => 12)); // WHERE extra_price > 12
     * </code>
     *
     * @param mixed  $extraPrice The value to use as filter.
     *                               Use scalar values for
     *                               equality. Use array values
     *                               for in_array() equivalent.
     *                               Use associative array('min'
     *                               => $minValue, 'max' =>
     *                               $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByExtraPrice($extraPrice = null, $comparison = null)
    {
        if (is_array($extraPrice)) {
            $useMinMax = false;
            if (isset($extraPrice['min'])) {
                $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_EXTRA_PRICE, $extraPrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($extraPrice['max'])) {
                $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_EXTRA_PRICE, $extraPrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_EXTRA_PRICE, $extraPrice, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event      The related object(s) to use as filter
     * @param string                                   $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(InvoiceWarningTypeTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceWarningTypeTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \API\Models\Event\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\API\Models\Event\EventQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\InvoiceWarning object
     *
     * @param \API\Models\Invoice\InvoiceWarning|ObjectCollection $invoiceWarning the related object to use as filter
     * @param string                                              $comparison     Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function filterByInvoiceWarning($invoiceWarning, $comparison = null)
    {
        if ($invoiceWarning instanceof \API\Models\Invoice\InvoiceWarning) {
            return $this
                ->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarning->getInvoiceWarningTypeid(), $comparison);
        } elseif ($invoiceWarning instanceof ObjectCollection) {
            return $this
                ->useInvoiceWarningQuery()
                ->filterByPrimaryKeys($invoiceWarning->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceWarning() only accepts arguments of type \API\Models\Invoice\InvoiceWarning or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceWarning relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function joinInvoiceWarning($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceWarning');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'InvoiceWarning');
        }

        return $this;
    }

    /**
     * Use the InvoiceWarning relation InvoiceWarning object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceWarningQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceWarningQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceWarning($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceWarning', '\API\Models\Invoice\InvoiceWarningQuery');
    }

    /**
     * Exclude object from result
     *
     * @param ChildInvoiceWarningType $invoiceWarningType Object to remove from the list of results
     *
     * @return $this|ChildInvoiceWarningTypeQuery The current query, for fluid interface
     */
    public function prune($invoiceWarningType = null)
    {
        if ($invoiceWarningType) {
            $this->addUsingAlias(InvoiceWarningTypeTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningType->getInvoiceWarningTypeid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoice_warning_type table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceWarningTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con) {
                $affectedRows = 0; // initialize var to track total num of affected rows
                $affectedRows += parent::doDeleteAll($con);
                // Because this db requires some delete cascade/set null emulation, we have to
                // clear the cached instance *after* the emulation has happened (since
                // instances get re-added by the select statement contained therein).
                InvoiceWarningTypeTableMap::clearInstancePool();
                InvoiceWarningTypeTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceWarningTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoiceWarningTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con, $criteria) {
                $affectedRows = 0; // initialize var to track total num of affected rows

                InvoiceWarningTypeTableMap::removeInstanceFromPool($criteria);

                $affectedRows += ModelCriteria::delete($con);
                InvoiceWarningTypeTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }
} // InvoiceWarningTypeQuery
