<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceGroup;
use API\Models\Menu\MenuGroup as ChildMenuGroup;
use API\Models\Menu\MenuGroupQuery as ChildMenuGroupQuery;
use API\Models\Menu\Map\MenuGroupTableMap;
use API\Models\OIP\OrderInProgress;
use API\Models\Ordering\OrderDetail;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_group' table.
 *
 *
 *
 * @method     ChildMenuGroupQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildMenuGroupQuery orderByMenuTypeid($order = Criteria::ASC) Order by the menu_typeid column
 * @method     ChildMenuGroupQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildMenuGroupQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildMenuGroupQuery groupByMenuTypeid() Group by the menu_typeid column
 * @method     ChildMenuGroupQuery groupByName() Group by the name column
 *
 * @method     ChildMenuGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuGroupQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuGroupQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuGroupQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuGroupQuery leftJoinMenuType($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuType relation
 * @method     ChildMenuGroupQuery rightJoinMenuType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuType relation
 * @method     ChildMenuGroupQuery innerJoinMenuType($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuType relation
 *
 * @method     ChildMenuGroupQuery joinWithMenuType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuType relation
 *
 * @method     ChildMenuGroupQuery leftJoinWithMenuType() Adds a LEFT JOIN clause and with to the query using the MenuType relation
 * @method     ChildMenuGroupQuery rightJoinWithMenuType() Adds a RIGHT JOIN clause and with to the query using the MenuType relation
 * @method     ChildMenuGroupQuery innerJoinWithMenuType() Adds a INNER JOIN clause and with to the query using the MenuType relation
 *
 * @method     ChildMenuGroupQuery leftJoinDistributionPlaceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlaceGroup relation
 * @method     ChildMenuGroupQuery rightJoinDistributionPlaceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlaceGroup relation
 * @method     ChildMenuGroupQuery innerJoinDistributionPlaceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlaceGroup relation
 *
 * @method     ChildMenuGroupQuery joinWithDistributionPlaceGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlaceGroup relation
 *
 * @method     ChildMenuGroupQuery leftJoinWithDistributionPlaceGroup() Adds a LEFT JOIN clause and with to the query using the DistributionPlaceGroup relation
 * @method     ChildMenuGroupQuery rightJoinWithDistributionPlaceGroup() Adds a RIGHT JOIN clause and with to the query using the DistributionPlaceGroup relation
 * @method     ChildMenuGroupQuery innerJoinWithDistributionPlaceGroup() Adds a INNER JOIN clause and with to the query using the DistributionPlaceGroup relation
 *
 * @method     ChildMenuGroupQuery leftJoinMenu($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menu relation
 * @method     ChildMenuGroupQuery rightJoinMenu($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menu relation
 * @method     ChildMenuGroupQuery innerJoinMenu($relationAlias = null) Adds a INNER JOIN clause to the query using the Menu relation
 *
 * @method     ChildMenuGroupQuery joinWithMenu($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menu relation
 *
 * @method     ChildMenuGroupQuery leftJoinWithMenu() Adds a LEFT JOIN clause and with to the query using the Menu relation
 * @method     ChildMenuGroupQuery rightJoinWithMenu() Adds a RIGHT JOIN clause and with to the query using the Menu relation
 * @method     ChildMenuGroupQuery innerJoinWithMenu() Adds a INNER JOIN clause and with to the query using the Menu relation
 *
 * @method     ChildMenuGroupQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildMenuGroupQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildMenuGroupQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildMenuGroupQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildMenuGroupQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildMenuGroupQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildMenuGroupQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     ChildMenuGroupQuery leftJoinOrderInProgress($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgress relation
 * @method     ChildMenuGroupQuery rightJoinOrderInProgress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgress relation
 * @method     ChildMenuGroupQuery innerJoinOrderInProgress($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgress relation
 *
 * @method     ChildMenuGroupQuery joinWithOrderInProgress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgress relation
 *
 * @method     ChildMenuGroupQuery leftJoinWithOrderInProgress() Adds a LEFT JOIN clause and with to the query using the OrderInProgress relation
 * @method     ChildMenuGroupQuery rightJoinWithOrderInProgress() Adds a RIGHT JOIN clause and with to the query using the OrderInProgress relation
 * @method     ChildMenuGroupQuery innerJoinWithOrderInProgress() Adds a INNER JOIN clause and with to the query using the OrderInProgress relation
 *
 * @method     \API\Models\Menu\MenuTypeQuery|\API\Models\DistributionPlace\DistributionPlaceGroupQuery|\API\Models\Menu\MenuQuery|\API\Models\Ordering\OrderDetailQuery|\API\Models\OIP\OrderInProgressQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuGroup findOne(ConnectionInterface $con = null) Return the first ChildMenuGroup matching the query
 * @method     ChildMenuGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuGroup matching the query, or a new ChildMenuGroup object populated from the query conditions when no match is found
 *
 * @method     ChildMenuGroup findOneByMenuGroupid(int $menu_groupid) Return the first ChildMenuGroup filtered by the menu_groupid column
 * @method     ChildMenuGroup findOneByMenuTypeid(int $menu_typeid) Return the first ChildMenuGroup filtered by the menu_typeid column
 * @method     ChildMenuGroup findOneByName(string $name) Return the first ChildMenuGroup filtered by the name column *

 * @method     ChildMenuGroup requirePk($key, ConnectionInterface $con = null) Return the ChildMenuGroup by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuGroup requireOne(ConnectionInterface $con = null) Return the first ChildMenuGroup matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuGroup requireOneByMenuGroupid(int $menu_groupid) Return the first ChildMenuGroup filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuGroup requireOneByMenuTypeid(int $menu_typeid) Return the first ChildMenuGroup filtered by the menu_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuGroup requireOneByName(string $name) Return the first ChildMenuGroup filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuGroup[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuGroup objects based on current ModelCriteria
 * @method     ChildMenuGroup[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildMenuGroup objects filtered by the menu_groupid column
 * @method     ChildMenuGroup[]|ObjectCollection findByMenuTypeid(int $menu_typeid) Return ChildMenuGroup objects filtered by the menu_typeid column
 * @method     ChildMenuGroup[]|ObjectCollection findByName(string $name) Return ChildMenuGroup objects filtered by the name column
 * @method     ChildMenuGroup[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuGroupQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menu\Base\MenuGroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menu\\MenuGroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuGroupQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuGroupQuery) {
            return $criteria;
        }
        $query = new ChildMenuGroupQuery();
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
     * @param array[$menu_groupid, $menu_typeid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuGroupTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuGroupTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenuGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_groupid, menu_typeid, name FROM menu_group WHERE menu_groupid = :p0 AND menu_typeid = :p1';
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
            /** @var ChildMenuGroup $obj */
            $obj = new ChildMenuGroup();
            $obj->hydrate($row);
            MenuGroupTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenuGroup|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuGroupTableMap::COL_MENU_TYPEID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuGroupTableMap::COL_MENU_GROUPID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuGroupTableMap::COL_MENU_TYPEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @param     mixed $menuGroupid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
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
     * @see       filterByMenuType()
     *
     * @param     mixed $menuTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByMenuTypeid($menuTypeid = null, $comparison = null)
    {
        if (is_array($menuTypeid)) {
            $useMinMax = false;
            if (isset($menuTypeid['min'])) {
                $this->addUsingAlias(MenuGroupTableMap::COL_MENU_TYPEID, $menuTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuTypeid['max'])) {
                $this->addUsingAlias(MenuGroupTableMap::COL_MENU_TYPEID, $menuTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuGroupTableMap::COL_MENU_TYPEID, $menuTypeid, $comparison);
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
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuGroupTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuType object
     *
     * @param \API\Models\Menu\MenuType|ObjectCollection $menuType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByMenuType($menuType, $comparison = null)
    {
        if ($menuType instanceof \API\Models\Menu\MenuType) {
            return $this
                ->addUsingAlias(MenuGroupTableMap::COL_MENU_TYPEID, $menuType->getMenuTypeid(), $comparison);
        } elseif ($menuType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuGroupTableMap::COL_MENU_TYPEID, $menuType->toKeyValue('MenuTypeid', 'MenuTypeid'), $comparison);
        } else {
            throw new PropelException('filterByMenuType() only accepts arguments of type \API\Models\Menu\MenuType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function joinMenuType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuType');

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
            $this->addJoinObject($join, 'MenuType');
        }

        return $this;
    }

    /**
     * Use the MenuType relation MenuType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuTypeQuery A secondary query class using the current class as primary query
     */
    public function useMenuTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuType', '\API\Models\Menu\MenuTypeQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlaceGroup object
     *
     * @param \API\Models\DistributionPlace\DistributionPlaceGroup|ObjectCollection $distributionPlaceGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceGroup($distributionPlaceGroup, $comparison = null)
    {
        if ($distributionPlaceGroup instanceof \API\Models\DistributionPlace\DistributionPlaceGroup) {
            return $this
                ->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $distributionPlaceGroup->getMenuGroupid(), $comparison);
        } elseif ($distributionPlaceGroup instanceof ObjectCollection) {
            return $this
                ->useDistributionPlaceGroupQuery()
                ->filterByPrimaryKeys($distributionPlaceGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionPlaceGroup() only accepts arguments of type \API\Models\DistributionPlace\DistributionPlaceGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlaceGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function joinDistributionPlaceGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlaceGroup');

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
            $this->addJoinObject($join, 'DistributionPlaceGroup');
        }

        return $this;
    }

    /**
     * Use the DistributionPlaceGroup relation DistributionPlaceGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionPlaceGroupQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlaceGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlaceGroup', '\API\Models\DistributionPlace\DistributionPlaceGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\Menu object
     *
     * @param \API\Models\Menu\Menu|ObjectCollection $menu the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = null)
    {
        if ($menu instanceof \API\Models\Menu\Menu) {
            return $this
                ->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $menu->getMenuGroupid(), $comparison);
        } elseif ($menu instanceof ObjectCollection) {
            return $this
                ->useMenuQuery()
                ->filterByPrimaryKeys($menu->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenu() only accepts arguments of type \API\Models\Menu\Menu or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menu relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function joinMenu($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Menu');

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
            $this->addJoinObject($join, 'Menu');
        }

        return $this;
    }

    /**
     * Use the Menu relation Menu object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuQuery A secondary query class using the current class as primary query
     */
    public function useMenuQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenu($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menu', '\API\Models\Menu\MenuQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrderDetail object
     *
     * @param \API\Models\Ordering\OrderDetail|ObjectCollection $orderDetail the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $orderDetail->getMenuGroupid(), $comparison);
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
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\OIP\OrderInProgress object
     *
     * @param \API\Models\OIP\OrderInProgress|ObjectCollection $orderInProgress the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByOrderInProgress($orderInProgress, $comparison = null)
    {
        if ($orderInProgress instanceof \API\Models\OIP\OrderInProgress) {
            return $this
                ->addUsingAlias(MenuGroupTableMap::COL_MENU_GROUPID, $orderInProgress->getMenuGroupid(), $comparison);
        } elseif ($orderInProgress instanceof ObjectCollection) {
            return $this
                ->useOrderInProgressQuery()
                ->filterByPrimaryKeys($orderInProgress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderInProgress() only accepts arguments of type \API\Models\OIP\OrderInProgress or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderInProgress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function joinOrderInProgress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderInProgress');

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
            $this->addJoinObject($join, 'OrderInProgress');
        }

        return $this;
    }

    /**
     * Use the OrderInProgress relation OrderInProgress object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\OIP\OrderInProgressQuery A secondary query class using the current class as primary query
     */
    public function useOrderInProgressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderInProgress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderInProgress', '\API\Models\OIP\OrderInProgressQuery');
    }

    /**
     * Filter the query by a related DistributionPlace object
     * using the distribution_place_group table as cross reference
     *
     * @param DistributionPlace $distributionPlace the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuGroupQuery The current query, for fluid interface
     */
    public function filterByDistributionPlace($distributionPlace, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useDistributionPlaceGroupQuery()
            ->filterByDistributionPlace($distributionPlace, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuGroup $menuGroup Object to remove from the list of results
     *
     * @return $this|ChildMenuGroupQuery The current query, for fluid interface
     */
    public function prune($menuGroup = null)
    {
        if ($menuGroup) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuGroupTableMap::COL_MENU_GROUPID), $menuGroup->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuGroupTableMap::COL_MENU_TYPEID), $menuGroup->getMenuTypeid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_group table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuGroupTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuGroupTableMap::clearInstancePool();
            MenuGroupTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuGroupTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuGroupTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuGroupTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuGroupTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuGroupQuery
