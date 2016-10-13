<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\Event\Events;
use Model\Menues\MenuTypes as ChildMenuTypes;
use Model\Menues\MenuTypesQuery as ChildMenuTypesQuery;
use Model\Menues\Map\MenuTypesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_types' table.
 *
 *
 *
 * @method     ChildMenuTypesQuery orderByMenuTypeid($order = Criteria::ASC) Order by the menu_typeid column
 * @method     ChildMenuTypesQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildMenuTypesQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuTypesQuery orderByTax($order = Criteria::ASC) Order by the tax column
 * @method     ChildMenuTypesQuery orderByAllowmixing($order = Criteria::ASC) Order by the allowMixing column
 *
 * @method     ChildMenuTypesQuery groupByMenuTypeid() Group by the menu_typeid column
 * @method     ChildMenuTypesQuery groupByEventid() Group by the eventid column
 * @method     ChildMenuTypesQuery groupByName() Group by the name column
 * @method     ChildMenuTypesQuery groupByTax() Group by the tax column
 * @method     ChildMenuTypesQuery groupByAllowmixing() Group by the allowMixing column
 *
 * @method     ChildMenuTypesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuTypesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuTypesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuTypesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuTypesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuTypesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuTypesQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildMenuTypesQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildMenuTypesQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildMenuTypesQuery joinWithEvents($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Events relation
 *
 * @method     ChildMenuTypesQuery leftJoinWithEvents() Adds a LEFT JOIN clause and with to the query using the Events relation
 * @method     ChildMenuTypesQuery rightJoinWithEvents() Adds a RIGHT JOIN clause and with to the query using the Events relation
 * @method     ChildMenuTypesQuery innerJoinWithEvents() Adds a INNER JOIN clause and with to the query using the Events relation
 *
 * @method     ChildMenuTypesQuery leftJoinMenuGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildMenuTypesQuery rightJoinMenuGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildMenuTypesQuery innerJoinMenuGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroupes relation
 *
 * @method     ChildMenuTypesQuery joinWithMenuGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildMenuTypesQuery leftJoinWithMenuGroupes() Adds a LEFT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildMenuTypesQuery rightJoinWithMenuGroupes() Adds a RIGHT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildMenuTypesQuery innerJoinWithMenuGroupes() Adds a INNER JOIN clause and with to the query using the MenuGroupes relation
 *
 * @method     \Model\Event\EventsQuery|\Model\Menues\MenuGroupesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuTypes findOne(ConnectionInterface $con = null) Return the first ChildMenuTypes matching the query
 * @method     ChildMenuTypes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuTypes matching the query, or a new ChildMenuTypes object populated from the query conditions when no match is found
 *
 * @method     ChildMenuTypes findOneByMenuTypeid(int $menu_typeid) Return the first ChildMenuTypes filtered by the menu_typeid column
 * @method     ChildMenuTypes findOneByEventid(int $eventid) Return the first ChildMenuTypes filtered by the eventid column
 * @method     ChildMenuTypes findOneByName(string $name) Return the first ChildMenuTypes filtered by the name column
 * @method     ChildMenuTypes findOneByTax(int $tax) Return the first ChildMenuTypes filtered by the tax column
 * @method     ChildMenuTypes findOneByAllowmixing(boolean $allowMixing) Return the first ChildMenuTypes filtered by the allowMixing column *

 * @method     ChildMenuTypes requirePk($key, ConnectionInterface $con = null) Return the ChildMenuTypes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuTypes requireOne(ConnectionInterface $con = null) Return the first ChildMenuTypes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuTypes requireOneByMenuTypeid(int $menu_typeid) Return the first ChildMenuTypes filtered by the menu_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuTypes requireOneByEventid(int $eventid) Return the first ChildMenuTypes filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuTypes requireOneByName(string $name) Return the first ChildMenuTypes filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuTypes requireOneByTax(int $tax) Return the first ChildMenuTypes filtered by the tax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuTypes requireOneByAllowmixing(boolean $allowMixing) Return the first ChildMenuTypes filtered by the allowMixing column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuTypes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuTypes objects based on current ModelCriteria
 * @method     ChildMenuTypes[]|ObjectCollection findByMenuTypeid(int $menu_typeid) Return ChildMenuTypes objects filtered by the menu_typeid column
 * @method     ChildMenuTypes[]|ObjectCollection findByEventid(int $eventid) Return ChildMenuTypes objects filtered by the eventid column
 * @method     ChildMenuTypes[]|ObjectCollection findByName(string $name) Return ChildMenuTypes objects filtered by the name column
 * @method     ChildMenuTypes[]|ObjectCollection findByTax(int $tax) Return ChildMenuTypes objects filtered by the tax column
 * @method     ChildMenuTypes[]|ObjectCollection findByAllowmixing(boolean $allowMixing) Return ChildMenuTypes objects filtered by the allowMixing column
 * @method     ChildMenuTypes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuTypesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Menues\Base\MenuTypesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Menues\\MenuTypes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuTypesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuTypesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuTypesQuery) {
            return $criteria;
        }
        $query = new ChildMenuTypesQuery();
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
     * @param array[$menu_typeid, $eventid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuTypes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuTypesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuTypesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenuTypes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_typeid, eventid, name, tax, allowMixing FROM menu_types WHERE menu_typeid = :p0 AND eventid = :p1';
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
            /** @var ChildMenuTypes $obj */
            $obj = new ChildMenuTypes();
            $obj->hydrate($row);
            MenuTypesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenuTypes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuTypesTableMap::COL_MENU_TYPEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuTypesTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuTypesTableMap::COL_MENU_TYPEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuTypesTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the menu_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuTypeid(1234); // WHERE menu_typeid = 1234
     * $query->filterByMenuTypeid(array(12, 34)); // WHERE menu_typeid IN (12, 34)
     * $query->filterByMenuTypeid(array('min' => 12)); // WHERE menu_typeid > 12
     * </code>
     *
     * @param     mixed $menuTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByMenuTypeid($menuTypeid = null, $comparison = null)
    {
        if (is_array($menuTypeid)) {
            $useMinMax = false;
            if (isset($menuTypeid['min'])) {
                $this->addUsingAlias(MenuTypesTableMap::COL_MENU_TYPEID, $menuTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuTypeid['max'])) {
                $this->addUsingAlias(MenuTypesTableMap::COL_MENU_TYPEID, $menuTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTypesTableMap::COL_MENU_TYPEID, $menuTypeid, $comparison);
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
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(MenuTypesTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(MenuTypesTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTypesTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTypesTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the tax column
     *
     * Example usage:
     * <code>
     * $query->filterByTax(1234); // WHERE tax = 1234
     * $query->filterByTax(array(12, 34)); // WHERE tax IN (12, 34)
     * $query->filterByTax(array('min' => 12)); // WHERE tax > 12
     * </code>
     *
     * @param     mixed $tax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByTax($tax = null, $comparison = null)
    {
        if (is_array($tax)) {
            $useMinMax = false;
            if (isset($tax['min'])) {
                $this->addUsingAlias(MenuTypesTableMap::COL_TAX, $tax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tax['max'])) {
                $this->addUsingAlias(MenuTypesTableMap::COL_TAX, $tax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTypesTableMap::COL_TAX, $tax, $comparison);
    }

    /**
     * Filter the query on the allowMixing column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowmixing(true); // WHERE allowMixing = true
     * $query->filterByAllowmixing('yes'); // WHERE allowMixing = true
     * </code>
     *
     * @param     boolean|string $allowmixing The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByAllowmixing($allowmixing = null, $comparison = null)
    {
        if (is_string($allowmixing)) {
            $allowmixing = in_array(strtolower($allowmixing), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MenuTypesTableMap::COL_ALLOWMIXING, $allowmixing, $comparison);
    }

    /**
     * Filter the query by a related \Model\Event\Events object
     *
     * @param \Model\Event\Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \Model\Event\Events) {
            return $this
                ->addUsingAlias(MenuTypesTableMap::COL_EVENTID, $events->getEventid(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuTypesTableMap::COL_EVENTID, $events->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type \Model\Event\Events or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
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
     * @return \Model\Event\EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\Model\Event\EventsQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuGroupes object
     *
     * @param \Model\Menues\MenuGroupes|ObjectCollection $menuGroupes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuTypesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupes($menuGroupes, $comparison = null)
    {
        if ($menuGroupes instanceof \Model\Menues\MenuGroupes) {
            return $this
                ->addUsingAlias(MenuTypesTableMap::COL_MENU_TYPEID, $menuGroupes->getMenuTypeid(), $comparison);
        } elseif ($menuGroupes instanceof ObjectCollection) {
            return $this
                ->useMenuGroupesQuery()
                ->filterByPrimaryKeys($menuGroupes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuGroupes() only accepts arguments of type \Model\Menues\MenuGroupes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroupes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function joinMenuGroupes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuGroupes');

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
            $this->addJoinObject($join, 'MenuGroupes');
        }

        return $this;
    }

    /**
     * Use the MenuGroupes relation MenuGroupes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuGroupesQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroupes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroupes', '\Model\Menues\MenuGroupesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuTypes $menuTypes Object to remove from the list of results
     *
     * @return $this|ChildMenuTypesQuery The current query, for fluid interface
     */
    public function prune($menuTypes = null)
    {
        if ($menuTypes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuTypesTableMap::COL_MENU_TYPEID), $menuTypes->getMenuTypeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuTypesTableMap::COL_EVENTID), $menuTypes->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuTypesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuTypesTableMap::clearInstancePool();
            MenuTypesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuTypesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuTypesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuTypesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuTypesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuTypesQuery
