<?php

namespace API\Models\ORM\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroup as ChildDistributionPlaceGroup;
use API\Models\ORM\DistributionPlace\DistributionPlaceGroupQuery as ChildDistributionPlaceGroupQuery;
use API\Models\ORM\DistributionPlace\Map\DistributionPlaceGroupTableMap;
use API\Models\ORM\Menu\MenuGroup;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distribution_place_group' table.
 *
 * 
 *
 * @method     ChildDistributionPlaceGroupQuery orderByDistributionPlaceGroupid($order = Criteria::ASC) Order by the distribution_place_groupid column
 * @method     ChildDistributionPlaceGroupQuery orderByDistributionPlaceid($order = Criteria::ASC) Order by the distribution_placeid column
 * @method     ChildDistributionPlaceGroupQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 *
 * @method     ChildDistributionPlaceGroupQuery groupByDistributionPlaceGroupid() Group by the distribution_place_groupid column
 * @method     ChildDistributionPlaceGroupQuery groupByDistributionPlaceid() Group by the distribution_placeid column
 * @method     ChildDistributionPlaceGroupQuery groupByMenuGroupid() Group by the menu_groupid column
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionPlaceGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionPlaceGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionPlaceGroupQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionPlaceGroupQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinDistributionPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupQuery rightJoinDistributionPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupQuery innerJoinDistributionPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceGroupQuery joinWithDistributionPlace($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinWithDistributionPlace() Adds a LEFT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupQuery rightJoinWithDistributionPlace() Adds a RIGHT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupQuery innerJoinWithDistributionPlace() Adds a INNER JOIN clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinMenuGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupQuery rightJoinMenuGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupQuery innerJoinMenuGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceGroupQuery joinWithMenuGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinWithMenuGroup() Adds a LEFT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupQuery rightJoinWithMenuGroup() Adds a RIGHT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupQuery innerJoinWithMenuGroup() Adds a INNER JOIN clause and with to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinDistributionPlaceTable($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlaceTable relation
 * @method     ChildDistributionPlaceGroupQuery rightJoinDistributionPlaceTable($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlaceTable relation
 * @method     ChildDistributionPlaceGroupQuery innerJoinDistributionPlaceTable($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlaceTable relation
 *
 * @method     ChildDistributionPlaceGroupQuery joinWithDistributionPlaceTable($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlaceTable relation
 *
 * @method     ChildDistributionPlaceGroupQuery leftJoinWithDistributionPlaceTable() Adds a LEFT JOIN clause and with to the query using the DistributionPlaceTable relation
 * @method     ChildDistributionPlaceGroupQuery rightJoinWithDistributionPlaceTable() Adds a RIGHT JOIN clause and with to the query using the DistributionPlaceTable relation
 * @method     ChildDistributionPlaceGroupQuery innerJoinWithDistributionPlaceTable() Adds a INNER JOIN clause and with to the query using the DistributionPlaceTable relation
 *
 * @method     \API\Models\ORM\DistributionPlace\DistributionPlaceQuery|\API\Models\ORM\Menu\MenuGroupQuery|\API\Models\ORM\DistributionPlace\DistributionPlaceTableQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionPlaceGroup findOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceGroup matching the query
 * @method     ChildDistributionPlaceGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionPlaceGroup matching the query, or a new ChildDistributionPlaceGroup object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionPlaceGroup findOneByDistributionPlaceGroupid(int $distribution_place_groupid) Return the first ChildDistributionPlaceGroup filtered by the distribution_place_groupid column
 * @method     ChildDistributionPlaceGroup findOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceGroup filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceGroup findOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionPlaceGroup filtered by the menu_groupid column *

 * @method     ChildDistributionPlaceGroup requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionPlaceGroup by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceGroup requireOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceGroup matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceGroup requireOneByDistributionPlaceGroupid(int $distribution_place_groupid) Return the first ChildDistributionPlaceGroup filtered by the distribution_place_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceGroup requireOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceGroup filtered by the distribution_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceGroup requireOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionPlaceGroup filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceGroup[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionPlaceGroup objects based on current ModelCriteria
 * @method     ChildDistributionPlaceGroup[]|ObjectCollection findByDistributionPlaceGroupid(int $distribution_place_groupid) Return ChildDistributionPlaceGroup objects filtered by the distribution_place_groupid column
 * @method     ChildDistributionPlaceGroup[]|ObjectCollection findByDistributionPlaceid(int $distribution_placeid) Return ChildDistributionPlaceGroup objects filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceGroup[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildDistributionPlaceGroup objects filtered by the menu_groupid column
 * @method     ChildDistributionPlaceGroup[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionPlaceGroupQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\DistributionPlace\Base\DistributionPlaceGroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\DistributionPlace\\DistributionPlaceGroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionPlaceGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionPlaceGroupQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionPlaceGroupQuery) {
            return $criteria;
        }
        $query = new ChildDistributionPlaceGroupQuery();
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
     * @return ChildDistributionPlaceGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionPlaceGroupTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionPlaceGroupTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildDistributionPlaceGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `distribution_place_groupid`, `distribution_placeid`, `menu_groupid` FROM `distribution_place_group` WHERE `distribution_place_groupid` = :p0';
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
            /** @var ChildDistributionPlaceGroup $obj */
            $obj = new ChildDistributionPlaceGroup();
            $obj->hydrate($row);
            DistributionPlaceGroupTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildDistributionPlaceGroup|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the distribution_place_groupid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionPlaceGroupid(1234); // WHERE distribution_place_groupid = 1234
     * $query->filterByDistributionPlaceGroupid(array(12, 34)); // WHERE distribution_place_groupid IN (12, 34)
     * $query->filterByDistributionPlaceGroupid(array('min' => 12)); // WHERE distribution_place_groupid > 12
     * </code>
     *
     * @param     mixed $distributionPlaceGroupid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceGroupid($distributionPlaceGroupid = null, $comparison = null)
    {
        if (is_array($distributionPlaceGroupid)) {
            $useMinMax = false;
            if (isset($distributionPlaceGroupid['min'])) {
                $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $distributionPlaceGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionPlaceGroupid['max'])) {
                $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $distributionPlaceGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $distributionPlaceGroupid, $comparison);
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
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceid($distributionPlaceid = null, $comparison = null)
    {
        if (is_array($distributionPlaceid)) {
            $useMinMax = false;
            if (isset($distributionPlaceid['min'])) {
                $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionPlaceid['max'])) {
                $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid, $comparison);
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
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\DistributionPlace\DistributionPlace object
     *
     * @param \API\Models\ORM\DistributionPlace\DistributionPlace|ObjectCollection $distributionPlace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByDistributionPlace($distributionPlace, $comparison = null)
    {
        if ($distributionPlace instanceof \API\Models\ORM\DistributionPlace\DistributionPlace) {
            return $this
                ->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->getDistributionPlaceid(), $comparison);
        } elseif ($distributionPlace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->toKeyValue('PrimaryKey', 'DistributionPlaceid'), $comparison);
        } else {
            throw new PropelException('filterByDistributionPlace() only accepts arguments of type \API\Models\ORM\DistributionPlace\DistributionPlace or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlace relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\DistributionPlace\DistributionPlaceQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlace', '\API\Models\ORM\DistributionPlace\DistributionPlaceQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\MenuGroup object
     *
     * @param \API\Models\ORM\Menu\MenuGroup|ObjectCollection $menuGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByMenuGroup($menuGroup, $comparison = null)
    {
        if ($menuGroup instanceof \API\Models\ORM\Menu\MenuGroup) {
            return $this
                ->addUsingAlias(DistributionPlaceGroupTableMap::COL_MENU_GROUPID, $menuGroup->getMenuGroupid(), $comparison);
        } elseif ($menuGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceGroupTableMap::COL_MENU_GROUPID, $menuGroup->toKeyValue('PrimaryKey', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroup() only accepts arguments of type \API\Models\ORM\Menu\MenuGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Menu\MenuGroupQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroup', '\API\Models\ORM\Menu\MenuGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\DistributionPlace\DistributionPlaceTable object
     *
     * @param \API\Models\ORM\DistributionPlace\DistributionPlaceTable|ObjectCollection $distributionPlaceTable the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceTable($distributionPlaceTable, $comparison = null)
    {
        if ($distributionPlaceTable instanceof \API\Models\ORM\DistributionPlace\DistributionPlaceTable) {
            return $this
                ->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $distributionPlaceTable->getDistributionPlaceGroupid(), $comparison);
        } elseif ($distributionPlaceTable instanceof ObjectCollection) {
            return $this
                ->useDistributionPlaceTableQuery()
                ->filterByPrimaryKeys($distributionPlaceTable->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionPlaceTable() only accepts arguments of type \API\Models\ORM\DistributionPlace\DistributionPlaceTable or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlaceTable relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function joinDistributionPlaceTable($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlaceTable');

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
            $this->addJoinObject($join, 'DistributionPlaceTable');
        }

        return $this;
    }

    /**
     * Use the DistributionPlaceTable relation DistributionPlaceTable object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\DistributionPlace\DistributionPlaceTableQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceTableQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlaceTable($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlaceTable', '\API\Models\ORM\DistributionPlace\DistributionPlaceTableQuery');
    }

    /**
     * Filter the query by a related EventTable object
     * using the distribution_place_table table as cross reference
     *
     * @param EventTable $eventTable the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function filterByEventTable($eventTable, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useDistributionPlaceTableQuery()
            ->filterByEventTable($eventTable, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionPlaceGroup $distributionPlaceGroup Object to remove from the list of results
     *
     * @return $this|ChildDistributionPlaceGroupQuery The current query, for fluid interface
     */
    public function prune($distributionPlaceGroup = null)
    {
        if ($distributionPlaceGroup) {
            $this->addUsingAlias(DistributionPlaceGroupTableMap::COL_DISTRIBUTION_PLACE_GROUPID, $distributionPlaceGroup->getDistributionPlaceGroupid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distribution_place_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceGroupTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionPlaceGroupTableMap::clearInstancePool();
            DistributionPlaceGroupTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceGroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionPlaceGroupTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            DistributionPlaceGroupTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            DistributionPlaceGroupTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionPlaceGroupQuery
