<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Menu\MenuSize as ChildMenuSize;
use API\Models\Menu\MenuSizeQuery as ChildMenuSizeQuery;
use API\Models\Menu\Map\MenuSizeTableMap;
use API\Models\Ordering\OrderDetail;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_size' table.
 *
 *
 *
 * @method     ChildMenuSizeQuery orderByMenuSizeid($order = Criteria::ASC) Order by the menu_sizeid column
 * @method     ChildMenuSizeQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildMenuSizeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuSizeQuery orderByFactor($order = Criteria::ASC) Order by the factor column
 *
 * @method     ChildMenuSizeQuery groupByMenuSizeid() Group by the menu_sizeid column
 * @method     ChildMenuSizeQuery groupByEventid() Group by the eventid column
 * @method     ChildMenuSizeQuery groupByName() Group by the name column
 * @method     ChildMenuSizeQuery groupByFactor() Group by the factor column
 *
 * @method     ChildMenuSizeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuSizeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuSizeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuSizeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuSizeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuSizeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuSizeQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildMenuSizeQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildMenuSizeQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildMenuSizeQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildMenuSizeQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildMenuSizeQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildMenuSizeQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildMenuSizeQuery leftJoinMenuPossibleSize($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuPossibleSize relation
 * @method     ChildMenuSizeQuery rightJoinMenuPossibleSize($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuPossibleSize relation
 * @method     ChildMenuSizeQuery innerJoinMenuPossibleSize($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuPossibleSize relation
 *
 * @method     ChildMenuSizeQuery joinWithMenuPossibleSize($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuPossibleSize relation
 *
 * @method     ChildMenuSizeQuery leftJoinWithMenuPossibleSize() Adds a LEFT JOIN clause and with to the query using the MenuPossibleSize relation
 * @method     ChildMenuSizeQuery rightJoinWithMenuPossibleSize() Adds a RIGHT JOIN clause and with to the query using the MenuPossibleSize relation
 * @method     ChildMenuSizeQuery innerJoinWithMenuPossibleSize() Adds a INNER JOIN clause and with to the query using the MenuPossibleSize relation
 *
 * @method     ChildMenuSizeQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildMenuSizeQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildMenuSizeQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildMenuSizeQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildMenuSizeQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildMenuSizeQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildMenuSizeQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     \API\Models\Event\EventQuery|\API\Models\Menu\MenuPossibleSizeQuery|\API\Models\Ordering\OrderDetailQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuSize findOne(ConnectionInterface $con = null) Return the first ChildMenuSize matching the query
 * @method     ChildMenuSize findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuSize matching the query, or a new ChildMenuSize object populated from the query conditions when no match is found
 *
 * @method     ChildMenuSize findOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuSize filtered by the menu_sizeid column
 * @method     ChildMenuSize findOneByEventid(int $eventid) Return the first ChildMenuSize filtered by the eventid column
 * @method     ChildMenuSize findOneByName(string $name) Return the first ChildMenuSize filtered by the name column
 * @method     ChildMenuSize findOneByFactor(string $factor) Return the first ChildMenuSize filtered by the factor column *

 * @method     ChildMenuSize requirePk($key, ConnectionInterface $con = null) Return the ChildMenuSize by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSize requireOne(ConnectionInterface $con = null) Return the first ChildMenuSize matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuSize requireOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuSize filtered by the menu_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSize requireOneByEventid(int $eventid) Return the first ChildMenuSize filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSize requireOneByName(string $name) Return the first ChildMenuSize filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuSize requireOneByFactor(string $factor) Return the first ChildMenuSize filtered by the factor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuSize[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuSize objects based on current ModelCriteria
 * @method     ChildMenuSize[]|ObjectCollection findByMenuSizeid(int $menu_sizeid) Return ChildMenuSize objects filtered by the menu_sizeid column
 * @method     ChildMenuSize[]|ObjectCollection findByEventid(int $eventid) Return ChildMenuSize objects filtered by the eventid column
 * @method     ChildMenuSize[]|ObjectCollection findByName(string $name) Return ChildMenuSize objects filtered by the name column
 * @method     ChildMenuSize[]|ObjectCollection findByFactor(string $factor) Return ChildMenuSize objects filtered by the factor column
 * @method     ChildMenuSize[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuSizeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menu\Base\MenuSizeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menu\\MenuSize', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuSizeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuSizeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuSizeQuery) {
            return $criteria;
        }
        $query = new ChildMenuSizeQuery();
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
     * @return ChildMenuSize|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuSizeTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenuSize A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_sizeid, eventid, name, factor FROM menu_size WHERE menu_sizeid = :p0 AND eventid = :p1';
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
            /** @var ChildMenuSize $obj */
            $obj = new ChildMenuSize();
            $obj->hydrate($row);
            MenuSizeTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenuSize|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuSizeTableMap::COL_MENU_SIZEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuSizeTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuSizeTableMap::COL_MENU_SIZEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuSizeTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
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
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByMenuSizeid($menuSizeid = null, $comparison = null)
    {
        if (is_array($menuSizeid)) {
            $useMinMax = false;
            if (isset($menuSizeid['min'])) {
                $this->addUsingAlias(MenuSizeTableMap::COL_MENU_SIZEID, $menuSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuSizeid['max'])) {
                $this->addUsingAlias(MenuSizeTableMap::COL_MENU_SIZEID, $menuSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizeTableMap::COL_MENU_SIZEID, $menuSizeid, $comparison);
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
     * @see       filterByEvent()
     *
     * @param     mixed $eventid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(MenuSizeTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(MenuSizeTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizeTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizeTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByFactor($factor = null, $comparison = null)
    {
        if (is_array($factor)) {
            $useMinMax = false;
            if (isset($factor['min'])) {
                $this->addUsingAlias(MenuSizeTableMap::COL_FACTOR, $factor['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($factor['max'])) {
                $this->addUsingAlias(MenuSizeTableMap::COL_FACTOR, $factor['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuSizeTableMap::COL_FACTOR, $factor, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(MenuSizeTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuSizeTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \API\Models\Event\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \API\Models\Menu\MenuPossibleSize object
     *
     * @param \API\Models\Menu\MenuPossibleSize|ObjectCollection $menuPossibleSize the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleSize($menuPossibleSize, $comparison = null)
    {
        if ($menuPossibleSize instanceof \API\Models\Menu\MenuPossibleSize) {
            return $this
                ->addUsingAlias(MenuSizeTableMap::COL_MENU_SIZEID, $menuPossibleSize->getMenuSizeid(), $comparison);
        } elseif ($menuPossibleSize instanceof ObjectCollection) {
            return $this
                ->useMenuPossibleSizeQuery()
                ->filterByPrimaryKeys($menuPossibleSize->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuPossibleSize() only accepts arguments of type \API\Models\Menu\MenuPossibleSize or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuPossibleSize relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function joinMenuPossibleSize($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuPossibleSize');

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
            $this->addJoinObject($join, 'MenuPossibleSize');
        }

        return $this;
    }

    /**
     * Use the MenuPossibleSize relation MenuPossibleSize object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuPossibleSizeQuery A secondary query class using the current class as primary query
     */
    public function useMenuPossibleSizeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuPossibleSize($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuPossibleSize', '\API\Models\Menu\MenuPossibleSizeQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrderDetail object
     *
     * @param \API\Models\Ordering\OrderDetail|ObjectCollection $orderDetail the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(MenuSizeTableMap::COL_MENU_SIZEID, $orderDetail->getMenuSizeid(), $comparison);
        } elseif ($orderDetail instanceof ObjectCollection) {
            return $this
                ->useOrderDetailQuery()
                ->filterByPrimaryKeys($orderDetail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderDetail() only accepts arguments of type \API\Models\Ordering\OrderDetail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function joinOrderDetail($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderDetail');

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
            $this->addJoinObject($join, 'OrderDetail');
        }

        return $this;
    }

    /**
     * Use the OrderDetail relation OrderDetail object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrderDetailQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrderDetail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetail', '\API\Models\Ordering\OrderDetailQuery');
    }

    /**
     * Filter the query by a related Menu object
     * using the menu_possible_size table as cross reference
     *
     * @param Menu $menu the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuSizeQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useMenuPossibleSizeQuery()
            ->filterByMenu($menu, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuSize $menuSize Object to remove from the list of results
     *
     * @return $this|ChildMenuSizeQuery The current query, for fluid interface
     */
    public function prune($menuSize = null)
    {
        if ($menuSize) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuSizeTableMap::COL_MENU_SIZEID), $menuSize->getMenuSizeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuSizeTableMap::COL_EVENTID), $menuSize->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_size table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuSizeTableMap::clearInstancePool();
            MenuSizeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuSizeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuSizeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuSizeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuSizeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuSizeQuery
