<?php

namespace API\Models\Menues\Base;

use \Exception;
use \PDO;
use API\Models\Event\Events;
use API\Models\Menues\MenuExtras as ChildMenuExtras;
use API\Models\Menues\MenuExtrasQuery as ChildMenuExtrasQuery;
use API\Models\Menues\Map\MenuExtrasTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_extras' table.
 *
 *
 *
 * @method     ChildMenuExtrasQuery orderByMenuExtraid($order = Criteria::ASC) Order by the menu_extraid column
 * @method     ChildMenuExtrasQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildMenuExtrasQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuExtrasQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildMenuExtrasQuery orderByAvailabilityAmount($order = Criteria::ASC) Order by the availability_amount column
 *
 * @method     ChildMenuExtrasQuery groupByMenuExtraid() Group by the menu_extraid column
 * @method     ChildMenuExtrasQuery groupByEventid() Group by the eventid column
 * @method     ChildMenuExtrasQuery groupByName() Group by the name column
 * @method     ChildMenuExtrasQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildMenuExtrasQuery groupByAvailabilityAmount() Group by the availability_amount column
 *
 * @method     ChildMenuExtrasQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuExtrasQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuExtrasQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuExtrasQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuExtrasQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuExtrasQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuExtrasQuery leftJoinAvailabilitys($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availabilitys relation
 * @method     ChildMenuExtrasQuery rightJoinAvailabilitys($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availabilitys relation
 * @method     ChildMenuExtrasQuery innerJoinAvailabilitys($relationAlias = null) Adds a INNER JOIN clause to the query using the Availabilitys relation
 *
 * @method     ChildMenuExtrasQuery joinWithAvailabilitys($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availabilitys relation
 *
 * @method     ChildMenuExtrasQuery leftJoinWithAvailabilitys() Adds a LEFT JOIN clause and with to the query using the Availabilitys relation
 * @method     ChildMenuExtrasQuery rightJoinWithAvailabilitys() Adds a RIGHT JOIN clause and with to the query using the Availabilitys relation
 * @method     ChildMenuExtrasQuery innerJoinWithAvailabilitys() Adds a INNER JOIN clause and with to the query using the Availabilitys relation
 *
 * @method     ChildMenuExtrasQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildMenuExtrasQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildMenuExtrasQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildMenuExtrasQuery joinWithEvents($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Events relation
 *
 * @method     ChildMenuExtrasQuery leftJoinWithEvents() Adds a LEFT JOIN clause and with to the query using the Events relation
 * @method     ChildMenuExtrasQuery rightJoinWithEvents() Adds a RIGHT JOIN clause and with to the query using the Events relation
 * @method     ChildMenuExtrasQuery innerJoinWithEvents() Adds a INNER JOIN clause and with to the query using the Events relation
 *
 * @method     ChildMenuExtrasQuery leftJoinMenuesPossibleExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuExtrasQuery rightJoinMenuesPossibleExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuExtrasQuery innerJoinMenuesPossibleExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuesPossibleExtras relation
 *
 * @method     ChildMenuExtrasQuery joinWithMenuesPossibleExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuesPossibleExtras relation
 *
 * @method     ChildMenuExtrasQuery leftJoinWithMenuesPossibleExtras() Adds a LEFT JOIN clause and with to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuExtrasQuery rightJoinWithMenuesPossibleExtras() Adds a RIGHT JOIN clause and with to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuExtrasQuery innerJoinWithMenuesPossibleExtras() Adds a INNER JOIN clause and with to the query using the MenuesPossibleExtras relation
 *
 * @method     \API\Models\Menues\AvailabilitysQuery|\API\Models\Event\EventsQuery|\API\Models\Menues\MenuesPossibleExtrasQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuExtras findOne(ConnectionInterface $con = null) Return the first ChildMenuExtras matching the query
 * @method     ChildMenuExtras findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuExtras matching the query, or a new ChildMenuExtras object populated from the query conditions when no match is found
 *
 * @method     ChildMenuExtras findOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuExtras filtered by the menu_extraid column
 * @method     ChildMenuExtras findOneByEventid(int $eventid) Return the first ChildMenuExtras filtered by the eventid column
 * @method     ChildMenuExtras findOneByName(string $name) Return the first ChildMenuExtras filtered by the name column
 * @method     ChildMenuExtras findOneByAvailabilityid(int $availabilityid) Return the first ChildMenuExtras filtered by the availabilityid column
 * @method     ChildMenuExtras findOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenuExtras filtered by the availability_amount column *

 * @method     ChildMenuExtras requirePk($key, ConnectionInterface $con = null) Return the ChildMenuExtras by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtras requireOne(ConnectionInterface $con = null) Return the first ChildMenuExtras matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuExtras requireOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuExtras filtered by the menu_extraid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtras requireOneByEventid(int $eventid) Return the first ChildMenuExtras filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtras requireOneByName(string $name) Return the first ChildMenuExtras filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtras requireOneByAvailabilityid(int $availabilityid) Return the first ChildMenuExtras filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtras requireOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenuExtras filtered by the availability_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuExtras[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuExtras objects based on current ModelCriteria
 * @method     ChildMenuExtras[]|ObjectCollection findByMenuExtraid(int $menu_extraid) Return ChildMenuExtras objects filtered by the menu_extraid column
 * @method     ChildMenuExtras[]|ObjectCollection findByEventid(int $eventid) Return ChildMenuExtras objects filtered by the eventid column
 * @method     ChildMenuExtras[]|ObjectCollection findByName(string $name) Return ChildMenuExtras objects filtered by the name column
 * @method     ChildMenuExtras[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildMenuExtras objects filtered by the availabilityid column
 * @method     ChildMenuExtras[]|ObjectCollection findByAvailabilityAmount(int $availability_amount) Return ChildMenuExtras objects filtered by the availability_amount column
 * @method     ChildMenuExtras[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuExtrasQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menues\Base\MenuExtrasQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menues\\MenuExtras', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuExtrasQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuExtrasQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuExtrasQuery) {
            return $criteria;
        }
        $query = new ChildMenuExtrasQuery();
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
     * @param array[$menu_extraid, $eventid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuExtras|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuExtrasTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuExtrasTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenuExtras A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_extraid, eventid, name, availabilityid, availability_amount FROM menu_extras WHERE menu_extraid = :p0 AND eventid = :p1';
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
            /** @var ChildMenuExtras $obj */
            $obj = new ChildMenuExtras();
            $obj->hydrate($row);
            MenuExtrasTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenuExtras|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuExtrasTableMap::COL_MENU_EXTRAID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuExtrasTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuExtrasTableMap::COL_MENU_EXTRAID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuExtrasTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the menu_extraid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuExtraid(1234); // WHERE menu_extraid = 1234
     * $query->filterByMenuExtraid(array(12, 34)); // WHERE menu_extraid IN (12, 34)
     * $query->filterByMenuExtraid(array('min' => 12)); // WHERE menu_extraid > 12
     * </code>
     *
     * @param     mixed $menuExtraid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByMenuExtraid($menuExtraid = null, $comparison = null)
    {
        if (is_array($menuExtraid)) {
            $useMinMax = false;
            if (isset($menuExtraid['min'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_MENU_EXTRAID, $menuExtraid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuExtraid['max'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_MENU_EXTRAID, $menuExtraid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtrasTableMap::COL_MENU_EXTRAID, $menuExtraid, $comparison);
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
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtrasTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtrasTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the availabilityid column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailabilityid(1234); // WHERE availabilityid = 1234
     * $query->filterByAvailabilityid(array(12, 34)); // WHERE availabilityid IN (12, 34)
     * $query->filterByAvailabilityid(array('min' => 12)); // WHERE availabilityid > 12
     * </code>
     *
     * @see       filterByAvailabilitys()
     *
     * @param     mixed $availabilityid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
    }

    /**
     * Filter the query on the availability_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailabilityAmount(1234); // WHERE availability_amount = 1234
     * $query->filterByAvailabilityAmount(array(12, 34)); // WHERE availability_amount IN (12, 34)
     * $query->filterByAvailabilityAmount(array('min' => 12)); // WHERE availability_amount > 12
     * </code>
     *
     * @param     mixed $availabilityAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByAvailabilityAmount($availabilityAmount = null, $comparison = null)
    {
        if (is_array($availabilityAmount)) {
            $useMinMax = false;
            if (isset($availabilityAmount['min'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityAmount['max'])) {
                $this->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menues\Availabilitys object
     *
     * @param \API\Models\Menues\Availabilitys|ObjectCollection $availabilitys The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByAvailabilitys($availabilitys, $comparison = null)
    {
        if ($availabilitys instanceof \API\Models\Menues\Availabilitys) {
            return $this
                ->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITYID, $availabilitys->getAvailabilityid(), $comparison);
        } elseif ($availabilitys instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuExtrasTableMap::COL_AVAILABILITYID, $availabilitys->toKeyValue('PrimaryKey', 'Availabilityid'), $comparison);
        } else {
            throw new PropelException('filterByAvailabilitys() only accepts arguments of type \API\Models\Menues\Availabilitys or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availabilitys relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function joinAvailabilitys($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Availabilitys');

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
            $this->addJoinObject($join, 'Availabilitys');
        }

        return $this;
    }

    /**
     * Use the Availabilitys relation Availabilitys object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menues\AvailabilitysQuery A secondary query class using the current class as primary query
     */
    public function useAvailabilitysQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAvailabilitys($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Availabilitys', '\API\Models\Menues\AvailabilitysQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\Events object
     *
     * @param \API\Models\Event\Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \API\Models\Event\Events) {
            return $this
                ->addUsingAlias(MenuExtrasTableMap::COL_EVENTID, $events->getEventid(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuExtrasTableMap::COL_EVENTID, $events->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
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
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Menues\MenuesPossibleExtras object
     *
     * @param \API\Models\Menues\MenuesPossibleExtras|ObjectCollection $menuesPossibleExtras the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function filterByMenuesPossibleExtras($menuesPossibleExtras, $comparison = null)
    {
        if ($menuesPossibleExtras instanceof \API\Models\Menues\MenuesPossibleExtras) {
            return $this
                ->addUsingAlias(MenuExtrasTableMap::COL_MENU_EXTRAID, $menuesPossibleExtras->getMenuExtraid(), $comparison);
        } elseif ($menuesPossibleExtras instanceof ObjectCollection) {
            return $this
                ->useMenuesPossibleExtrasQuery()
                ->filterByPrimaryKeys($menuesPossibleExtras->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuesPossibleExtras() only accepts arguments of type \API\Models\Menues\MenuesPossibleExtras or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuesPossibleExtras relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function joinMenuesPossibleExtras($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuesPossibleExtras');

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
            $this->addJoinObject($join, 'MenuesPossibleExtras');
        }

        return $this;
    }

    /**
     * Use the MenuesPossibleExtras relation MenuesPossibleExtras object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menues\MenuesPossibleExtrasQuery A secondary query class using the current class as primary query
     */
    public function useMenuesPossibleExtrasQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuesPossibleExtras($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuesPossibleExtras', '\API\Models\Menues\MenuesPossibleExtrasQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuExtras $menuExtras Object to remove from the list of results
     *
     * @return $this|ChildMenuExtrasQuery The current query, for fluid interface
     */
    public function prune($menuExtras = null)
    {
        if ($menuExtras) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuExtrasTableMap::COL_MENU_EXTRAID), $menuExtras->getMenuExtraid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuExtrasTableMap::COL_EVENTID), $menuExtras->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_extras table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuExtrasTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuExtrasTableMap::clearInstancePool();
            MenuExtrasTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuExtrasTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuExtrasTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuExtrasTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuExtrasTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuExtrasQuery
