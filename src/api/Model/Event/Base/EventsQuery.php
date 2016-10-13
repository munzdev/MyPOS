<?php

namespace Model\Event\Base;

use \Exception;
use \PDO;
use Model\DistributionPlace\DistributionsPlaces;
use Model\Event\Events as ChildEvents;
use Model\Event\EventsQuery as ChildEventsQuery;
use Model\Event\Map\EventsTableMap;
use Model\Menues\MenuExtras;
use Model\Menues\MenuSizes;
use Model\Menues\MenuTypes;
use Model\Ordering\Orders;
use Model\Payment\Coupons;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'events' table.
 *
 *
 *
 * @method     ChildEventsQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildEventsQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventsQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildEventsQuery orderByActive($order = Criteria::ASC) Order by the active column
 *
 * @method     ChildEventsQuery groupByEventid() Group by the eventid column
 * @method     ChildEventsQuery groupByName() Group by the name column
 * @method     ChildEventsQuery groupByDate() Group by the date column
 * @method     ChildEventsQuery groupByActive() Group by the active column
 *
 * @method     ChildEventsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventsQuery leftJoinCoupons($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupons relation
 * @method     ChildEventsQuery rightJoinCoupons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupons relation
 * @method     ChildEventsQuery innerJoinCoupons($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupons relation
 *
 * @method     ChildEventsQuery joinWithCoupons($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupons relation
 *
 * @method     ChildEventsQuery leftJoinWithCoupons() Adds a LEFT JOIN clause and with to the query using the Coupons relation
 * @method     ChildEventsQuery rightJoinWithCoupons() Adds a RIGHT JOIN clause and with to the query using the Coupons relation
 * @method     ChildEventsQuery innerJoinWithCoupons() Adds a INNER JOIN clause and with to the query using the Coupons relation
 *
 * @method     ChildEventsQuery leftJoinDistributionsPlaces($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildEventsQuery rightJoinDistributionsPlaces($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildEventsQuery innerJoinDistributionsPlaces($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlaces relation
 *
 * @method     ChildEventsQuery joinWithDistributionsPlaces($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildEventsQuery leftJoinWithDistributionsPlaces() Adds a LEFT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildEventsQuery rightJoinWithDistributionsPlaces() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildEventsQuery innerJoinWithDistributionsPlaces() Adds a INNER JOIN clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildEventsQuery leftJoinEventsPrinters($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsPrinters relation
 * @method     ChildEventsQuery rightJoinEventsPrinters($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsPrinters relation
 * @method     ChildEventsQuery innerJoinEventsPrinters($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsPrinters relation
 *
 * @method     ChildEventsQuery joinWithEventsPrinters($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsPrinters relation
 *
 * @method     ChildEventsQuery leftJoinWithEventsPrinters() Adds a LEFT JOIN clause and with to the query using the EventsPrinters relation
 * @method     ChildEventsQuery rightJoinWithEventsPrinters() Adds a RIGHT JOIN clause and with to the query using the EventsPrinters relation
 * @method     ChildEventsQuery innerJoinWithEventsPrinters() Adds a INNER JOIN clause and with to the query using the EventsPrinters relation
 *
 * @method     ChildEventsQuery leftJoinEventsTables($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsTables relation
 * @method     ChildEventsQuery rightJoinEventsTables($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsTables relation
 * @method     ChildEventsQuery innerJoinEventsTables($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsTables relation
 *
 * @method     ChildEventsQuery joinWithEventsTables($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsTables relation
 *
 * @method     ChildEventsQuery leftJoinWithEventsTables() Adds a LEFT JOIN clause and with to the query using the EventsTables relation
 * @method     ChildEventsQuery rightJoinWithEventsTables() Adds a RIGHT JOIN clause and with to the query using the EventsTables relation
 * @method     ChildEventsQuery innerJoinWithEventsTables() Adds a INNER JOIN clause and with to the query using the EventsTables relation
 *
 * @method     ChildEventsQuery leftJoinEventsUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsUser relation
 * @method     ChildEventsQuery rightJoinEventsUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsUser relation
 * @method     ChildEventsQuery innerJoinEventsUser($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsUser relation
 *
 * @method     ChildEventsQuery joinWithEventsUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsUser relation
 *
 * @method     ChildEventsQuery leftJoinWithEventsUser() Adds a LEFT JOIN clause and with to the query using the EventsUser relation
 * @method     ChildEventsQuery rightJoinWithEventsUser() Adds a RIGHT JOIN clause and with to the query using the EventsUser relation
 * @method     ChildEventsQuery innerJoinWithEventsUser() Adds a INNER JOIN clause and with to the query using the EventsUser relation
 *
 * @method     ChildEventsQuery leftJoinMenuExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuExtras relation
 * @method     ChildEventsQuery rightJoinMenuExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuExtras relation
 * @method     ChildEventsQuery innerJoinMenuExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuExtras relation
 *
 * @method     ChildEventsQuery joinWithMenuExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuExtras relation
 *
 * @method     ChildEventsQuery leftJoinWithMenuExtras() Adds a LEFT JOIN clause and with to the query using the MenuExtras relation
 * @method     ChildEventsQuery rightJoinWithMenuExtras() Adds a RIGHT JOIN clause and with to the query using the MenuExtras relation
 * @method     ChildEventsQuery innerJoinWithMenuExtras() Adds a INNER JOIN clause and with to the query using the MenuExtras relation
 *
 * @method     ChildEventsQuery leftJoinMenuSizes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuSizes relation
 * @method     ChildEventsQuery rightJoinMenuSizes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuSizes relation
 * @method     ChildEventsQuery innerJoinMenuSizes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuSizes relation
 *
 * @method     ChildEventsQuery joinWithMenuSizes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuSizes relation
 *
 * @method     ChildEventsQuery leftJoinWithMenuSizes() Adds a LEFT JOIN clause and with to the query using the MenuSizes relation
 * @method     ChildEventsQuery rightJoinWithMenuSizes() Adds a RIGHT JOIN clause and with to the query using the MenuSizes relation
 * @method     ChildEventsQuery innerJoinWithMenuSizes() Adds a INNER JOIN clause and with to the query using the MenuSizes relation
 *
 * @method     ChildEventsQuery leftJoinMenuTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuTypes relation
 * @method     ChildEventsQuery rightJoinMenuTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuTypes relation
 * @method     ChildEventsQuery innerJoinMenuTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuTypes relation
 *
 * @method     ChildEventsQuery joinWithMenuTypes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuTypes relation
 *
 * @method     ChildEventsQuery leftJoinWithMenuTypes() Adds a LEFT JOIN clause and with to the query using the MenuTypes relation
 * @method     ChildEventsQuery rightJoinWithMenuTypes() Adds a RIGHT JOIN clause and with to the query using the MenuTypes relation
 * @method     ChildEventsQuery innerJoinWithMenuTypes() Adds a INNER JOIN clause and with to the query using the MenuTypes relation
 *
 * @method     ChildEventsQuery leftJoinOrders($relationAlias = null) Adds a LEFT JOIN clause to the query using the Orders relation
 * @method     ChildEventsQuery rightJoinOrders($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Orders relation
 * @method     ChildEventsQuery innerJoinOrders($relationAlias = null) Adds a INNER JOIN clause to the query using the Orders relation
 *
 * @method     ChildEventsQuery joinWithOrders($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Orders relation
 *
 * @method     ChildEventsQuery leftJoinWithOrders() Adds a LEFT JOIN clause and with to the query using the Orders relation
 * @method     ChildEventsQuery rightJoinWithOrders() Adds a RIGHT JOIN clause and with to the query using the Orders relation
 * @method     ChildEventsQuery innerJoinWithOrders() Adds a INNER JOIN clause and with to the query using the Orders relation
 *
 * @method     \Model\Payment\CouponsQuery|\Model\DistributionPlace\DistributionsPlacesQuery|\Model\Event\EventsPrintersQuery|\Model\Event\EventsTablesQuery|\Model\Event\EventsUserQuery|\Model\Menues\MenuExtrasQuery|\Model\Menues\MenuSizesQuery|\Model\Menues\MenuTypesQuery|\Model\Ordering\OrdersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEvents findOne(ConnectionInterface $con = null) Return the first ChildEvents matching the query
 * @method     ChildEvents findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEvents matching the query, or a new ChildEvents object populated from the query conditions when no match is found
 *
 * @method     ChildEvents findOneByEventid(int $eventid) Return the first ChildEvents filtered by the eventid column
 * @method     ChildEvents findOneByName(string $name) Return the first ChildEvents filtered by the name column
 * @method     ChildEvents findOneByDate(string $date) Return the first ChildEvents filtered by the date column
 * @method     ChildEvents findOneByActive(boolean $active) Return the first ChildEvents filtered by the active column *

 * @method     ChildEvents requirePk($key, ConnectionInterface $con = null) Return the ChildEvents by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOne(ConnectionInterface $con = null) Return the first ChildEvents matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvents requireOneByEventid(int $eventid) Return the first ChildEvents filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByName(string $name) Return the first ChildEvents filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByDate(string $date) Return the first ChildEvents filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvents requireOneByActive(boolean $active) Return the first ChildEvents filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvents[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEvents objects based on current ModelCriteria
 * @method     ChildEvents[]|ObjectCollection findByEventid(int $eventid) Return ChildEvents objects filtered by the eventid column
 * @method     ChildEvents[]|ObjectCollection findByName(string $name) Return ChildEvents objects filtered by the name column
 * @method     ChildEvents[]|ObjectCollection findByDate(string $date) Return ChildEvents objects filtered by the date column
 * @method     ChildEvents[]|ObjectCollection findByActive(boolean $active) Return ChildEvents objects filtered by the active column
 * @method     ChildEvents[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Event\Base\EventsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Event\\Events', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventsQuery) {
            return $criteria;
        }
        $query = new ChildEventsQuery();
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildEvents|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEvents A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT eventid, name, date, active FROM events WHERE eventid = :p0';
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
            /** @var ChildEvents $obj */
            $obj = new ChildEvents();
            $obj->hydrate($row);
            EventsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildEvents|array|mixed the result, formatted by the current formatter
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
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
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
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventsTableMap::COL_EVENTID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventsTableMap::COL_EVENTID, $keys, Criteria::IN);
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
     * @param     mixed $eventid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(EventsTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EventsTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(true); // WHERE active = true
     * $query->filterByActive('yes'); // WHERE active = true
     * </code>
     *
     * @param     boolean|string $active The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventsTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query by a related \Model\Payment\Coupons object
     *
     * @param \Model\Payment\Coupons|ObjectCollection $coupons the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByCoupons($coupons, $comparison = null)
    {
        if ($coupons instanceof \Model\Payment\Coupons) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $coupons->getEventid(), $comparison);
        } elseif ($coupons instanceof ObjectCollection) {
            return $this
                ->useCouponsQuery()
                ->filterByPrimaryKeys($coupons->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCoupons() only accepts arguments of type \Model\Payment\Coupons or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Coupons relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinCoupons($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Coupons');

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
            $this->addJoinObject($join, 'Coupons');
        }

        return $this;
    }

    /**
     * Use the Coupons relation Coupons object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Payment\CouponsQuery A secondary query class using the current class as primary query
     */
    public function useCouponsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCoupons($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Coupons', '\Model\Payment\CouponsQuery');
    }

    /**
     * Filter the query by a related \Model\DistributionPlace\DistributionsPlaces object
     *
     * @param \Model\DistributionPlace\DistributionsPlaces|ObjectCollection $distributionsPlaces the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaces($distributionsPlaces, $comparison = null)
    {
        if ($distributionsPlaces instanceof \Model\DistributionPlace\DistributionsPlaces) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $distributionsPlaces->getEventid(), $comparison);
        } elseif ($distributionsPlaces instanceof ObjectCollection) {
            return $this
                ->useDistributionsPlacesQuery()
                ->filterByPrimaryKeys($distributionsPlaces->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionsPlaces() only accepts arguments of type \Model\DistributionPlace\DistributionsPlaces or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlaces relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinDistributionsPlaces($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsPlaces');

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
            $this->addJoinObject($join, 'DistributionsPlaces');
        }

        return $this;
    }

    /**
     * Use the DistributionsPlaces relation DistributionsPlaces object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\DistributionPlace\DistributionsPlacesQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlaces($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlaces', '\Model\DistributionPlace\DistributionsPlacesQuery');
    }

    /**
     * Filter the query by a related \Model\Event\EventsPrinters object
     *
     * @param \Model\Event\EventsPrinters|ObjectCollection $eventsPrinters the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventsPrinters($eventsPrinters, $comparison = null)
    {
        if ($eventsPrinters instanceof \Model\Event\EventsPrinters) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $eventsPrinters->getEventid(), $comparison);
        } elseif ($eventsPrinters instanceof ObjectCollection) {
            return $this
                ->useEventsPrintersQuery()
                ->filterByPrimaryKeys($eventsPrinters->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventsPrinters() only accepts arguments of type \Model\Event\EventsPrinters or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsPrinters relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinEventsPrinters($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsPrinters');

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
            $this->addJoinObject($join, 'EventsPrinters');
        }

        return $this;
    }

    /**
     * Use the EventsPrinters relation EventsPrinters object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Event\EventsPrintersQuery A secondary query class using the current class as primary query
     */
    public function useEventsPrintersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsPrinters($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsPrinters', '\Model\Event\EventsPrintersQuery');
    }

    /**
     * Filter the query by a related \Model\Event\EventsTables object
     *
     * @param \Model\Event\EventsTables|ObjectCollection $eventsTables the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventsTables($eventsTables, $comparison = null)
    {
        if ($eventsTables instanceof \Model\Event\EventsTables) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $eventsTables->getEventid(), $comparison);
        } elseif ($eventsTables instanceof ObjectCollection) {
            return $this
                ->useEventsTablesQuery()
                ->filterByPrimaryKeys($eventsTables->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventsTables() only accepts arguments of type \Model\Event\EventsTables or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsTables relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinEventsTables($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsTables');

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
            $this->addJoinObject($join, 'EventsTables');
        }

        return $this;
    }

    /**
     * Use the EventsTables relation EventsTables object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Event\EventsTablesQuery A secondary query class using the current class as primary query
     */
    public function useEventsTablesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsTables($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsTables', '\Model\Event\EventsTablesQuery');
    }

    /**
     * Filter the query by a related \Model\Event\EventsUser object
     *
     * @param \Model\Event\EventsUser|ObjectCollection $eventsUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByEventsUser($eventsUser, $comparison = null)
    {
        if ($eventsUser instanceof \Model\Event\EventsUser) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $eventsUser->getEventid(), $comparison);
        } elseif ($eventsUser instanceof ObjectCollection) {
            return $this
                ->useEventsUserQuery()
                ->filterByPrimaryKeys($eventsUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventsUser() only accepts arguments of type \Model\Event\EventsUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinEventsUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsUser');

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
            $this->addJoinObject($join, 'EventsUser');
        }

        return $this;
    }

    /**
     * Use the EventsUser relation EventsUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Event\EventsUserQuery A secondary query class using the current class as primary query
     */
    public function useEventsUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsUser', '\Model\Event\EventsUserQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuExtras object
     *
     * @param \Model\Menues\MenuExtras|ObjectCollection $menuExtras the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByMenuExtras($menuExtras, $comparison = null)
    {
        if ($menuExtras instanceof \Model\Menues\MenuExtras) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $menuExtras->getEventid(), $comparison);
        } elseif ($menuExtras instanceof ObjectCollection) {
            return $this
                ->useMenuExtrasQuery()
                ->filterByPrimaryKeys($menuExtras->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuExtras() only accepts arguments of type \Model\Menues\MenuExtras or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuExtras relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinMenuExtras($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuExtras');

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
            $this->addJoinObject($join, 'MenuExtras');
        }

        return $this;
    }

    /**
     * Use the MenuExtras relation MenuExtras object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuExtrasQuery A secondary query class using the current class as primary query
     */
    public function useMenuExtrasQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuExtras($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuExtras', '\Model\Menues\MenuExtrasQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuSizes object
     *
     * @param \Model\Menues\MenuSizes|ObjectCollection $menuSizes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByMenuSizes($menuSizes, $comparison = null)
    {
        if ($menuSizes instanceof \Model\Menues\MenuSizes) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $menuSizes->getEventid(), $comparison);
        } elseif ($menuSizes instanceof ObjectCollection) {
            return $this
                ->useMenuSizesQuery()
                ->filterByPrimaryKeys($menuSizes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuSizes() only accepts arguments of type \Model\Menues\MenuSizes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuSizes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinMenuSizes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuSizes');

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
            $this->addJoinObject($join, 'MenuSizes');
        }

        return $this;
    }

    /**
     * Use the MenuSizes relation MenuSizes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuSizesQuery A secondary query class using the current class as primary query
     */
    public function useMenuSizesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuSizes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuSizes', '\Model\Menues\MenuSizesQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuTypes object
     *
     * @param \Model\Menues\MenuTypes|ObjectCollection $menuTypes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByMenuTypes($menuTypes, $comparison = null)
    {
        if ($menuTypes instanceof \Model\Menues\MenuTypes) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $menuTypes->getEventid(), $comparison);
        } elseif ($menuTypes instanceof ObjectCollection) {
            return $this
                ->useMenuTypesQuery()
                ->filterByPrimaryKeys($menuTypes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuTypes() only accepts arguments of type \Model\Menues\MenuTypes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinMenuTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuTypes');

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
            $this->addJoinObject($join, 'MenuTypes');
        }

        return $this;
    }

    /**
     * Use the MenuTypes relation MenuTypes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuTypesQuery A secondary query class using the current class as primary query
     */
    public function useMenuTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuTypes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuTypes', '\Model\Menues\MenuTypesQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\Orders object
     *
     * @param \Model\Ordering\Orders|ObjectCollection $orders the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsQuery The current query, for fluid interface
     */
    public function filterByOrders($orders, $comparison = null)
    {
        if ($orders instanceof \Model\Ordering\Orders) {
            return $this
                ->addUsingAlias(EventsTableMap::COL_EVENTID, $orders->getEventid(), $comparison);
        } elseif ($orders instanceof ObjectCollection) {
            return $this
                ->useOrdersQuery()
                ->filterByPrimaryKeys($orders->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrders() only accepts arguments of type \Model\Ordering\Orders or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Orders relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function joinOrders($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Orders');

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
            $this->addJoinObject($join, 'Orders');
        }

        return $this;
    }

    /**
     * Use the Orders relation Orders object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Ordering\OrdersQuery A secondary query class using the current class as primary query
     */
    public function useOrdersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrders($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Orders', '\Model\Ordering\OrdersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEvents $events Object to remove from the list of results
     *
     * @return $this|ChildEventsQuery The current query, for fluid interface
     */
    public function prune($events = null)
    {
        if ($events) {
            $this->addUsingAlias(EventsTableMap::COL_EVENTID, $events->getEventid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventsTableMap::clearInstancePool();
            EventsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventsQuery
