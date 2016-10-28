<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceTable as ChildDistributionPlaceTable;
use API\Models\DistributionPlace\DistributionPlaceTableQuery as ChildDistributionPlaceTableQuery;
use API\Models\DistributionPlace\Map\DistributionPlaceTableTableMap;
use API\Models\Event\EventTable;
use API\Models\Menu\MenuGroup;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distribution_place_table' table.
 *
 *
 *
 * @method     ChildDistributionPlaceTableQuery orderByEventTableid($order = Criteria::ASC) Order by the event_tableid column
 * @method     ChildDistributionPlaceTableQuery orderByDistributionPlaceid($order = Criteria::ASC) Order by the distribution_placeid column
 * @method     ChildDistributionPlaceTableQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 *
 * @method     ChildDistributionPlaceTableQuery groupByEventTableid() Group by the event_tableid column
 * @method     ChildDistributionPlaceTableQuery groupByDistributionPlaceid() Group by the distribution_placeid column
 * @method     ChildDistributionPlaceTableQuery groupByMenuGroupid() Group by the menu_groupid column
 *
 * @method     ChildDistributionPlaceTableQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionPlaceTableQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionPlaceTableQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionPlaceTableQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionPlaceTableQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinMenuGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceTableQuery rightJoinMenuGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceTableQuery innerJoinMenuGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceTableQuery joinWithMenuGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinWithMenuGroup() Adds a LEFT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceTableQuery rightJoinWithMenuGroup() Adds a RIGHT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceTableQuery innerJoinWithMenuGroup() Adds a INNER JOIN clause and with to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinDistributionPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceTableQuery rightJoinDistributionPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceTableQuery innerJoinDistributionPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceTableQuery joinWithDistributionPlace($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinWithDistributionPlace() Adds a LEFT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceTableQuery rightJoinWithDistributionPlace() Adds a RIGHT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceTableQuery innerJoinWithDistributionPlace() Adds a INNER JOIN clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinEventTable($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTable relation
 * @method     ChildDistributionPlaceTableQuery rightJoinEventTable($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTable relation
 * @method     ChildDistributionPlaceTableQuery innerJoinEventTable($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTable relation
 *
 * @method     ChildDistributionPlaceTableQuery joinWithEventTable($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventTable relation
 *
 * @method     ChildDistributionPlaceTableQuery leftJoinWithEventTable() Adds a LEFT JOIN clause and with to the query using the EventTable relation
 * @method     ChildDistributionPlaceTableQuery rightJoinWithEventTable() Adds a RIGHT JOIN clause and with to the query using the EventTable relation
 * @method     ChildDistributionPlaceTableQuery innerJoinWithEventTable() Adds a INNER JOIN clause and with to the query using the EventTable relation
 *
 * @method     \API\Models\Menu\MenuGroupQuery|\API\Models\DistributionPlace\DistributionPlaceQuery|\API\Models\Event\EventTableQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionPlaceTable findOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceTable matching the query
 * @method     ChildDistributionPlaceTable findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionPlaceTable matching the query, or a new ChildDistributionPlaceTable object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionPlaceTable findOneByEventTableid(int $event_tableid) Return the first ChildDistributionPlaceTable filtered by the event_tableid column
 * @method     ChildDistributionPlaceTable findOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceTable filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceTable findOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionPlaceTable filtered by the menu_groupid column *

 * @method     ChildDistributionPlaceTable requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionPlaceTable by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceTable requireOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceTable matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceTable requireOneByEventTableid(int $event_tableid) Return the first ChildDistributionPlaceTable filtered by the event_tableid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceTable requireOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceTable filtered by the distribution_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceTable requireOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionPlaceTable filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceTable[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionPlaceTable objects based on current ModelCriteria
 * @method     ChildDistributionPlaceTable[]|ObjectCollection findByEventTableid(int $event_tableid) Return ChildDistributionPlaceTable objects filtered by the event_tableid column
 * @method     ChildDistributionPlaceTable[]|ObjectCollection findByDistributionPlaceid(int $distribution_placeid) Return ChildDistributionPlaceTable objects filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceTable[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildDistributionPlaceTable objects filtered by the menu_groupid column
 * @method     ChildDistributionPlaceTable[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionPlaceTableQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\DistributionPlace\Base\DistributionPlaceTableQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\DistributionPlace\\DistributionPlaceTable', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionPlaceTableQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionPlaceTableQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionPlaceTableQuery) {
            return $criteria;
        }
        $query = new ChildDistributionPlaceTableQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$event_tableid, $distribution_placeid, $menu_groupid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionPlaceTable|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionPlaceTableTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionPlaceTableTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildDistributionPlaceTable A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT event_tableid, distribution_placeid, menu_groupid FROM distribution_place_table WHERE event_tableid = :p0 AND distribution_placeid = :p1 AND menu_groupid = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildDistributionPlaceTable $obj */
            $obj = new ChildDistributionPlaceTable();
            $obj->hydrate($row);
            DistributionPlaceTableTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildDistributionPlaceTable|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the event_tableid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventTableid(1234); // WHERE event_tableid = 1234
     * $query->filterByEventTableid(array(12, 34)); // WHERE event_tableid IN (12, 34)
     * $query->filterByEventTableid(array('min' => 12)); // WHERE event_tableid > 12
     * </code>
     *
     * @see       filterByEventTable()
     *
     * @param     mixed $eventTableid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByEventTableid($eventTableid = null, $comparison = null)
    {
        if (is_array($eventTableid)) {
            $useMinMax = false;
            if (isset($eventTableid['min'])) {
                $this->addUsingAlias(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $eventTableid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventTableid['max'])) {
                $this->addUsingAlias(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $eventTableid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $eventTableid, $comparison);
    }

    /**
     * Filter the query on the distribution_placeid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionPlaceid(1234); // WHERE distribution_placeid = 1234
     * $query->filterByDistributionPlaceid(array(12, 34)); // WHERE distribution_placeid IN (12, 34)
     * $query->filterByDistributionPlaceid(array('min' => 12)); // WHERE distribution_placeid > 12
     * </code>
     *
     * @see       filterByDistributionPlace()
     *
     * @param     mixed $distributionPlaceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceid($distributionPlaceid = null, $comparison = null)
    {
        if (is_array($distributionPlaceid)) {
            $useMinMax = false;
            if (isset($distributionPlaceid['min'])) {
                $this->addUsingAlias(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionPlaceid['max'])) {
                $this->addUsingAlias(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid, $comparison);
    }

    /**
     * Filter the query on the menu_groupid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuGroupid(1234); // WHERE menu_groupid = 1234
     * $query->filterByMenuGroupid(array(12, 34)); // WHERE menu_groupid IN (12, 34)
     * $query->filterByMenuGroupid(array('min' => 12)); // WHERE menu_groupid > 12
     * </code>
     *
     * @see       filterByMenuGroup()
     *
     * @param     mixed $menuGroupid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuGroup object
     *
     * @param \API\Models\Menu\MenuGroup|ObjectCollection $menuGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByMenuGroup($menuGroup, $comparison = null)
    {
        if ($menuGroup instanceof \API\Models\Menu\MenuGroup) {
            return $this
                ->addUsingAlias(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $menuGroup->getMenuGroupid(), $comparison);
        } elseif ($menuGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceTableTableMap::COL_MENU_GROUPID, $menuGroup->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroup() only accepts arguments of type \API\Models\Menu\MenuGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function joinMenuGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuGroup');

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
            $this->addJoinObject($join, 'MenuGroup');
        }

        return $this;
    }

    /**
     * Use the MenuGroup relation MenuGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuGroupQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroup', '\API\Models\Menu\MenuGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlace object
     *
     * @param \API\Models\DistributionPlace\DistributionPlace|ObjectCollection $distributionPlace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByDistributionPlace($distributionPlace, $comparison = null)
    {
        if ($distributionPlace instanceof \API\Models\DistributionPlace\DistributionPlace) {
            return $this
                ->addUsingAlias(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->getDistributionPlaceid(), $comparison);
        } elseif ($distributionPlace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->toKeyValue('DistributionPlaceid', 'DistributionPlaceid'), $comparison);
        } else {
            throw new PropelException('filterByDistributionPlace() only accepts arguments of type \API\Models\DistributionPlace\DistributionPlace or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlace relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function joinDistributionPlace($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlace');

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
            $this->addJoinObject($join, 'DistributionPlace');
        }

        return $this;
    }

    /**
     * Use the DistributionPlace relation DistributionPlace object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionPlaceQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlace', '\API\Models\DistributionPlace\DistributionPlaceQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventTable object
     *
     * @param \API\Models\Event\EventTable|ObjectCollection $eventTable The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function filterByEventTable($eventTable, $comparison = null)
    {
        if ($eventTable instanceof \API\Models\Event\EventTable) {
            return $this
                ->addUsingAlias(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $eventTable->getEventTableid(), $comparison);
        } elseif ($eventTable instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceTableTableMap::COL_EVENT_TABLEID, $eventTable->toKeyValue('EventTableid', 'EventTableid'), $comparison);
        } else {
            throw new PropelException('filterByEventTable() only accepts arguments of type \API\Models\Event\EventTable or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventTable relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function joinEventTable($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventTable');

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
            $this->addJoinObject($join, 'EventTable');
        }

        return $this;
    }

    /**
     * Use the EventTable relation EventTable object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventTableQuery A secondary query class using the current class as primary query
     */
    public function useEventTableQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventTable($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventTable', '\API\Models\Event\EventTableQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionPlaceTable $distributionPlaceTable Object to remove from the list of results
     *
     * @return $this|ChildDistributionPlaceTableQuery The current query, for fluid interface
     */
    public function prune($distributionPlaceTable = null)
    {
        if ($distributionPlaceTable) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionPlaceTableTableMap::COL_EVENT_TABLEID), $distributionPlaceTable->getEventTableid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionPlaceTableTableMap::COL_DISTRIBUTION_PLACEID), $distributionPlaceTable->getDistributionPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(DistributionPlaceTableTableMap::COL_MENU_GROUPID), $distributionPlaceTable->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distribution_place_table table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionPlaceTableTableMap::clearInstancePool();
            DistributionPlaceTableTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionPlaceTableTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionPlaceTableTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionPlaceTableTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionPlaceTableQuery
