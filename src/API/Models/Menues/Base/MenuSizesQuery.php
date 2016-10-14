<?php

namespace API\Models\Menues\Base;

use \Exception;
use \PDO;
use API\Models\Event\Events;
use API\Models\Menues\MenuSizes as ChildMenuSizes;
use API\Models\Menues\MenuSizesQuery as ChildMenuSizesQuery;
use API\Models\Menues\Map\MenuSizesTableMap;
use API\Models\Ordering\OrdersDetails;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_sizes' table.
 *
 *
 *
 * @method     ChildMenuSizesQuery orderByMenuSizeid($order = Criteria::ASC) Order by the menu_sizeid column
 * @method     ChildMenuSizesQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildMenuSizesQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuSizesQuery orderByFactor($order = Criteria::ASC) Order by the factor column
 *
 * @method     ChildMenuSizesQuery groupByMenuSizeid() Group by the menu_sizeid column
 * @method     ChildMenuSizesQuery groupByEventid() Group by the eventid column
 * @method     ChildMenuSizesQuery groupByName() Group by the name column
 * @method     ChildMenuSizesQuery groupByFactor() Group by the factor column
 *
 * @method     ChildMenuSizesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuSizesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuSizesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuSizesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuSizesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuSizesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuSizesQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildMenuSizesQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildMenuSizesQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildMenuSizesQuery joinWithEvents($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Events relation
 *
 * @method     ChildMenuSizesQuery leftJoinWithEvents() Adds a LEFT JOIN clause and with to the query using the Events relation
 * @method     ChildMenuSizesQuery rightJoinWithEvents() Adds a RIGHT JOIN clause and with to the query using the Events relation
 * @method     ChildMenuSizesQuery innerJoinWithEvents() Adds a INNER JOIN clause and with to the query using the Events relation
 *
 * @method     ChildMenuSizesQuery leftJoinMenuesPossibleSizes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuSizesQuery rightJoinMenuesPossibleSizes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuSizesQuery innerJoinMenuesPossibleSizes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuesPossibleSizes relation
 *
 * @method     ChildMenuSizesQuery joinWithMenuesPossibleSizes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuesPossibleSizes relation
 *
 * @method     ChildMenuSizesQuery leftJoinWithMenuesPossibleSizes() Adds a LEFT JOIN clause and with to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuSizesQuery rightJoinWithMenuesPossibleSizes() Adds a RIGHT JOIN clause and with to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuSizesQuery innerJoinWithMenuesPossibleSizes() Adds a INNER JOIN clause and with to the query using the MenuesPossibleSizes relation
 *
 * @method     ChildMenuSizesQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildMenuSizesQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildMenuSizesQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildMenuSizesQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildMenuSizesQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildMenuSizesQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildMenuSizesQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     \API\Models\Event\EventsQuery|\API\Models\Menues\MenuesPossibleSizesQuery|\API\Models\Ordering\OrdersDetailsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuSizes findOne(ConnectionInterface $con = null) Return the first ChildMenuSizes matching the query
 * @method     ChildMenuSizes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuSizes matching the query, or a new ChildMenuSizes object populated from the query conditions when no match is found
 *
 * @method     ChildMenuSizes findOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuSizes filtered by the menu_sizeid column
 * @method     ChildMenuSizes findOneByEventid(int $eventid) Return the first ChildMenuSizes filtered by the eventid column
 * @method     ChildMenuSizes findOneByName(string $name) Return the first ChildMenuSizes filtered by the name column
 * @method     ChildMenuSizes findOneByFactor(string $factor) Return the first ChildMenuSizes filtered by the factor column *

 * @method     ChildMenuSizes requirePk($key, ConnectionInterface $con = null) Return the ChildMenuSizes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSizes requireOne(ConnectionInterface $con = null) Return the first ChildMenuSizes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuSizes requireOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuSizes filtered by the menu_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSizes requireOneByEventid(int $eventid) Return the first ChildMenuSizes filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSizes requireOneByName(string $name) Return the first ChildMenuSizes filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSizes requireOneByFactor(string $factor) Return the first ChildMenuSizes filtered by the factor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuSizes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuSizes objects based on current ModelCriteria
 * @method     ChildMenuSizes[]|ObjectCollection findByMenuSizeid(int $menu_sizeid) Return ChildMenuSizes objects filtered by the menu_sizeid column
 * @method     ChildMenuSizes[]|ObjectCollection findByEventid(int $eventid) Return ChildMenuSizes objects filtered by the eventid column
 * @method     ChildMenuSizes[]|ObjectCollection findByName(string $name) Return ChildMenuSizes objects filtered by the name column
 * @method     ChildMenuSizes[]|ObjectCollection findByFactor(string $factor) Return ChildMenuSizes objects filtered by the factor column
 * @method     ChildMenuSizes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuSizesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menues\Base\MenuSizesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menues\\MenuSizes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuSizesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuSizesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuSizesQuery) {
            return $criteria;
        }
        $query = new ChildMenuSizesQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$menu_sizeid, $eventid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuSizes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuSizesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuSizesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenuSizes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_sizeid, eventid, name, factor FROM menu_sizes WHERE menu_sizeid = :p0 AND eventid = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMenuSizes $obj */
            $obj = new ChildMenuSizes();
            $obj->hydrate($row);
            MenuSizesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenuSizes|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuSizesTableMap::COL_MENU_SIZEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuSizesTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuSizesTableMap::COL_MENU_SIZEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuSizesTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the menu_sizeid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuSizeid(1234); // WHERE menu_sizeid = 1234
     * $query->filterByMenuSizeid(array(12, 34)); // WHERE menu_sizeid IN (12, 34)
     * $query->filterByMenuSizeid(array('min' => 12)); // WHERE menu_sizeid > 12
     * </code>
     *
     * @param     mixed $menuSizeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByMenuSizeid($menuSizeid = null, $comparison = null)
    {
        if (is_array($menuSizeid)) {
            $useMinMax = false;
            if (isset($menuSizeid['min'])) {
                $this->addUsingAlias(MenuSizesTableMap::COL_MENU_SIZEID, $menuSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuSizeid['max'])) {
                $this->addUsingAlias(MenuSizesTableMap::COL_MENU_SIZEID, $menuSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizesTableMap::COL_MENU_SIZEID, $menuSizeid, $comparison);
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
     * @see       filterByEvents()
     *
     * @param     mixed $eventid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(MenuSizesTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(MenuSizesTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizesTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizesTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the factor column
     *
     * Example usage:
     * <code>
     * $query->filterByFactor(1234); // WHERE factor = 1234
     * $query->filterByFactor(array(12, 34)); // WHERE factor IN (12, 34)
     * $query->filterByFactor(array('min' => 12)); // WHERE factor > 12
     * </code>
     *
     * @param     mixed $factor The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByFactor($factor = null, $comparison = null)
    {
        if (is_array($factor)) {
            $useMinMax = false;
            if (isset($factor['min'])) {
                $this->addUsingAlias(MenuSizesTableMap::COL_FACTOR, $factor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($factor['max'])) {
                $this->addUsingAlias(MenuSizesTableMap::COL_FACTOR, $factor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizesTableMap::COL_FACTOR, $factor, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Events object
     *
     * @param \API\Models\Event\Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \API\Models\Event\Events) {
            return $this
                ->addUsingAlias(MenuSizesTableMap::COL_EVENTID, $events->getEventid(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuSizesTableMap::COL_EVENTID, $events->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type \API\Models\Event\Events or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function joinEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Events');

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
            $this->addJoinObject($join, 'Events');
        }

        return $this;
    }

    /**
     * Use the Events relation Events object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\API\Models\Event\EventsQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menues\MenuesPossibleSizes object
     *
     * @param \API\Models\Menues\MenuesPossibleSizes|ObjectCollection $menuesPossibleSizes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByMenuesPossibleSizes($menuesPossibleSizes, $comparison = null)
    {
        if ($menuesPossibleSizes instanceof \API\Models\Menues\MenuesPossibleSizes) {
            return $this
                ->addUsingAlias(MenuSizesTableMap::COL_MENU_SIZEID, $menuesPossibleSizes->getMenuSizeid(), $comparison);
        } elseif ($menuesPossibleSizes instanceof ObjectCollection) {
            return $this
                ->useMenuesPossibleSizesQuery()
                ->filterByPrimaryKeys($menuesPossibleSizes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuesPossibleSizes() only accepts arguments of type \API\Models\Menues\MenuesPossibleSizes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuesPossibleSizes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function joinMenuesPossibleSizes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuesPossibleSizes');

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
            $this->addJoinObject($join, 'MenuesPossibleSizes');
        }

        return $this;
    }

    /**
     * Use the MenuesPossibleSizes relation MenuesPossibleSizes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menues\MenuesPossibleSizesQuery A secondary query class using the current class as primary query
     */
    public function useMenuesPossibleSizesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuesPossibleSizes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuesPossibleSizes', '\API\Models\Menues\MenuesPossibleSizesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrdersDetails object
     *
     * @param \API\Models\Ordering\OrdersDetails|ObjectCollection $ordersDetails the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuSizesQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \API\Models\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(MenuSizesTableMap::COL_MENU_SIZEID, $ordersDetails->getMenuSizeid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            return $this
                ->useOrdersDetailsQuery()
                ->filterByPrimaryKeys($ordersDetails->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersDetails() only accepts arguments of type \API\Models\Ordering\OrdersDetails or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersDetails relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function joinOrdersDetails($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersDetails');

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
            $this->addJoinObject($join, 'OrdersDetails');
        }

        return $this;
    }

    /**
     * Use the OrdersDetails relation OrdersDetails object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrdersDetailsQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\API\Models\Ordering\OrdersDetailsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuSizes $menuSizes Object to remove from the list of results
     *
     * @return $this|ChildMenuSizesQuery The current query, for fluid interface
     */
    public function prune($menuSizes = null)
    {
        if ($menuSizes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuSizesTableMap::COL_MENU_SIZEID), $menuSizes->getMenuSizeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuSizesTableMap::COL_EVENTID), $menuSizes->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_sizes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuSizesTableMap::clearInstancePool();
            MenuSizesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuSizesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuSizesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuSizesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuSizesQuery
