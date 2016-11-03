<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Menu\MenuExtra as ChildMenuExtra;
use API\Models\Menu\MenuExtraQuery as ChildMenuExtraQuery;
use API\Models\Menu\Map\MenuExtraTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_extra' table.
 *
 *
 *
 * @method     ChildMenuExtraQuery orderByMenuExtraid($order = Criteria::ASC) Order by the menu_extraid column
 * @method     ChildMenuExtraQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildMenuExtraQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuExtraQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildMenuExtraQuery orderByAvailabilityAmount($order = Criteria::ASC) Order by the availability_amount column
 *
 * @method     ChildMenuExtraQuery groupByMenuExtraid() Group by the menu_extraid column
 * @method     ChildMenuExtraQuery groupByEventid() Group by the eventid column
 * @method     ChildMenuExtraQuery groupByName() Group by the name column
 * @method     ChildMenuExtraQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildMenuExtraQuery groupByAvailabilityAmount() Group by the availability_amount column
 *
 * @method     ChildMenuExtraQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuExtraQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuExtraQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuExtraQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuExtraQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuExtraQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuExtraQuery leftJoinAvailability($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availability relation
 * @method     ChildMenuExtraQuery rightJoinAvailability($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availability relation
 * @method     ChildMenuExtraQuery innerJoinAvailability($relationAlias = null) Adds a INNER JOIN clause to the query using the Availability relation
 *
 * @method     ChildMenuExtraQuery joinWithAvailability($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availability relation
 *
 * @method     ChildMenuExtraQuery leftJoinWithAvailability() Adds a LEFT JOIN clause and with to the query using the Availability relation
 * @method     ChildMenuExtraQuery rightJoinWithAvailability() Adds a RIGHT JOIN clause and with to the query using the Availability relation
 * @method     ChildMenuExtraQuery innerJoinWithAvailability() Adds a INNER JOIN clause and with to the query using the Availability relation
 *
 * @method     ChildMenuExtraQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildMenuExtraQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildMenuExtraQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildMenuExtraQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildMenuExtraQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildMenuExtraQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildMenuExtraQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildMenuExtraQuery leftJoinMenuPossibleExtra($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuPossibleExtra relation
 * @method     ChildMenuExtraQuery rightJoinMenuPossibleExtra($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuPossibleExtra relation
 * @method     ChildMenuExtraQuery innerJoinMenuPossibleExtra($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuPossibleExtra relation
 *
 * @method     ChildMenuExtraQuery joinWithMenuPossibleExtra($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuPossibleExtra relation
 *
 * @method     ChildMenuExtraQuery leftJoinWithMenuPossibleExtra() Adds a LEFT JOIN clause and with to the query using the MenuPossibleExtra relation
 * @method     ChildMenuExtraQuery rightJoinWithMenuPossibleExtra() Adds a RIGHT JOIN clause and with to the query using the MenuPossibleExtra relation
 * @method     ChildMenuExtraQuery innerJoinWithMenuPossibleExtra() Adds a INNER JOIN clause and with to the query using the MenuPossibleExtra relation
 *
 * @method     \API\Models\Menu\AvailabilityQuery|\API\Models\Event\EventQuery|\API\Models\Menu\MenuPossibleExtraQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuExtra findOne(ConnectionInterface $con = null) Return the first ChildMenuExtra matching the query
 * @method     ChildMenuExtra findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuExtra matching the query, or a new ChildMenuExtra object populated from the query conditions when no match is found
 *
 * @method     ChildMenuExtra findOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuExtra filtered by the menu_extraid column
 * @method     ChildMenuExtra findOneByEventid(int $eventid) Return the first ChildMenuExtra filtered by the eventid column
 * @method     ChildMenuExtra findOneByName(string $name) Return the first ChildMenuExtra filtered by the name column
 * @method     ChildMenuExtra findOneByAvailabilityid(int $availabilityid) Return the first ChildMenuExtra filtered by the availabilityid column
 * @method     ChildMenuExtra findOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenuExtra filtered by the availability_amount column *

 * @method     ChildMenuExtra requirePk($key, ConnectionInterface $con = null) Return the ChildMenuExtra by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtra requireOne(ConnectionInterface $con = null) Return the first ChildMenuExtra matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuExtra requireOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuExtra filtered by the menu_extraid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtra requireOneByEventid(int $eventid) Return the first ChildMenuExtra filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtra requireOneByName(string $name) Return the first ChildMenuExtra filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtra requireOneByAvailabilityid(int $availabilityid) Return the first ChildMenuExtra filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuExtra requireOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenuExtra filtered by the availability_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuExtra[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuExtra objects based on current ModelCriteria
 * @method     ChildMenuExtra[]|ObjectCollection findByMenuExtraid(int $menu_extraid) Return ChildMenuExtra objects filtered by the menu_extraid column
 * @method     ChildMenuExtra[]|ObjectCollection findByEventid(int $eventid) Return ChildMenuExtra objects filtered by the eventid column
 * @method     ChildMenuExtra[]|ObjectCollection findByName(string $name) Return ChildMenuExtra objects filtered by the name column
 * @method     ChildMenuExtra[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildMenuExtra objects filtered by the availabilityid column
 * @method     ChildMenuExtra[]|ObjectCollection findByAvailabilityAmount(int $availability_amount) Return ChildMenuExtra objects filtered by the availability_amount column
 * @method     ChildMenuExtra[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuExtraQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menu\Base\MenuExtraQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menu\\MenuExtra', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuExtraQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuExtraQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuExtraQuery) {
            return $criteria;
        }
        $query = new ChildMenuExtraQuery();
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
     * @return ChildMenuExtra|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuExtraTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuExtraTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenuExtra A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_extraid, eventid, name, availabilityid, availability_amount FROM menu_extra WHERE menu_extraid = :p0 AND eventid = :p1';
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
            /** @var ChildMenuExtra $obj */
            $obj = new ChildMenuExtra();
            $obj->hydrate($row);
            MenuExtraTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenuExtra|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuExtraTableMap::COL_MENU_EXTRAID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuExtraTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuExtraTableMap::COL_MENU_EXTRAID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuExtraTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
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
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByMenuExtraid($menuExtraid = null, $comparison = null)
    {
        if (is_array($menuExtraid)) {
            $useMinMax = false;
            if (isset($menuExtraid['min'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_MENU_EXTRAID, $menuExtraid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuExtraid['max'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_MENU_EXTRAID, $menuExtraid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtraTableMap::COL_MENU_EXTRAID, $menuExtraid, $comparison);
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
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtraTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtraTableMap::COL_NAME, $name, $comparison);
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
     * @see       filterByAvailability()
     *
     * @param     mixed $availabilityid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
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
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByAvailabilityAmount($availabilityAmount = null, $comparison = null)
    {
        if (is_array($availabilityAmount)) {
            $useMinMax = false;
            if (isset($availabilityAmount['min'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityAmount['max'])) {
                $this->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menu\Availability object
     *
     * @param \API\Models\Menu\Availability|ObjectCollection $availability The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByAvailability($availability, $comparison = null)
    {
        if ($availability instanceof \API\Models\Menu\Availability) {
            return $this
                ->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITYID, $availability->getAvailabilityid(), $comparison);
        } elseif ($availability instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuExtraTableMap::COL_AVAILABILITYID, $availability->toKeyValue('PrimaryKey', 'Availabilityid'), $comparison);
        } else {
            throw new PropelException('filterByAvailability() only accepts arguments of type \API\Models\Menu\Availability or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availability relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function joinAvailability($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Availability');

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
            $this->addJoinObject($join, 'Availability');
        }

        return $this;
    }

    /**
     * Use the Availability relation Availability object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\AvailabilityQuery A secondary query class using the current class as primary query
     */
    public function useAvailabilityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAvailability($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Availability', '\API\Models\Menu\AvailabilityQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(MenuExtraTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuExtraTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
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
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Menu\MenuPossibleExtra object
     *
     * @param \API\Models\Menu\MenuPossibleExtra|ObjectCollection $menuPossibleExtra the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleExtra($menuPossibleExtra, $comparison = null)
    {
        if ($menuPossibleExtra instanceof \API\Models\Menu\MenuPossibleExtra) {
            return $this
                ->addUsingAlias(MenuExtraTableMap::COL_MENU_EXTRAID, $menuPossibleExtra->getMenuExtraid(), $comparison);
        } elseif ($menuPossibleExtra instanceof ObjectCollection) {
            return $this
                ->useMenuPossibleExtraQuery()
                ->filterByPrimaryKeys($menuPossibleExtra->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuPossibleExtra() only accepts arguments of type \API\Models\Menu\MenuPossibleExtra or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuPossibleExtra relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function joinMenuPossibleExtra($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuPossibleExtra');

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
            $this->addJoinObject($join, 'MenuPossibleExtra');
        }

        return $this;
    }

    /**
     * Use the MenuPossibleExtra relation MenuPossibleExtra object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuPossibleExtraQuery A secondary query class using the current class as primary query
     */
    public function useMenuPossibleExtraQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuPossibleExtra($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuPossibleExtra', '\API\Models\Menu\MenuPossibleExtraQuery');
    }

    /**
     * Filter the query by a related Menu object
     * using the menu_possible_extra table as cross reference
     *
     * @param Menu $menu the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuExtraQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useMenuPossibleExtraQuery()
            ->filterByMenu($menu, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuExtra $menuExtra Object to remove from the list of results
     *
     * @return $this|ChildMenuExtraQuery The current query, for fluid interface
     */
    public function prune($menuExtra = null)
    {
        if ($menuExtra) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuExtraTableMap::COL_MENU_EXTRAID), $menuExtra->getMenuExtraid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuExtraTableMap::COL_EVENTID), $menuExtra->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_extra table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuExtraTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuExtraTableMap::clearInstancePool();
            MenuExtraTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuExtraTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuExtraTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuExtraTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuExtraTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuExtraQuery
