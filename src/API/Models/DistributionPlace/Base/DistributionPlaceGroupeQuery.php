<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceGroupe as ChildDistributionPlaceGroupe;
use API\Models\DistributionPlace\DistributionPlaceGroupeQuery as ChildDistributionPlaceGroupeQuery;
use API\Models\DistributionPlace\Map\DistributionPlaceGroupeTableMap;
use API\Models\Menu\MenuGroup;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distribution_place_groupe' table.
 *
 *
 *
 * @method     ChildDistributionPlaceGroupeQuery orderByDistributionPlaceid($order = Criteria::ASC) Order by the distribution_placeid column
 * @method     ChildDistributionPlaceGroupeQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 *
 * @method     ChildDistributionPlaceGroupeQuery groupByDistributionPlaceid() Group by the distribution_placeid column
 * @method     ChildDistributionPlaceGroupeQuery groupByMenuGroupid() Group by the menu_groupid column
 *
 * @method     ChildDistributionPlaceGroupeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionPlaceGroupeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionPlaceGroupeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionPlaceGroupeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionPlaceGroupeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionPlaceGroupeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionPlaceGroupeQuery leftJoinDistributionPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupeQuery rightJoinDistributionPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupeQuery innerJoinDistributionPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceGroupeQuery joinWithDistributionPlace($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceGroupeQuery leftJoinWithDistributionPlace() Adds a LEFT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupeQuery rightJoinWithDistributionPlace() Adds a RIGHT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceGroupeQuery innerJoinWithDistributionPlace() Adds a INNER JOIN clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceGroupeQuery leftJoinMenuGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupeQuery rightJoinMenuGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupeQuery innerJoinMenuGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceGroupeQuery joinWithMenuGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroup relation
 *
 * @method     ChildDistributionPlaceGroupeQuery leftJoinWithMenuGroup() Adds a LEFT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupeQuery rightJoinWithMenuGroup() Adds a RIGHT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildDistributionPlaceGroupeQuery innerJoinWithMenuGroup() Adds a INNER JOIN clause and with to the query using the MenuGroup relation
 *
 * @method     \API\Models\DistributionPlace\DistributionPlaceQuery|\API\Models\Menu\MenuGroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionPlaceGroupe findOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceGroupe matching the query
 * @method     ChildDistributionPlaceGroupe findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionPlaceGroupe matching the query, or a new ChildDistributionPlaceGroupe object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionPlaceGroupe findOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceGroupe filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceGroupe findOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionPlaceGroupe filtered by the menu_groupid column *

 * @method     ChildDistributionPlaceGroupe requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionPlaceGroupe by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceGroupe requireOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceGroupe matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceGroupe requireOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceGroupe filtered by the distribution_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceGroupe requireOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionPlaceGroupe filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceGroupe[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionPlaceGroupe objects based on current ModelCriteria
 * @method     ChildDistributionPlaceGroupe[]|ObjectCollection findByDistributionPlaceid(int $distribution_placeid) Return ChildDistributionPlaceGroupe objects filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceGroupe[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildDistributionPlaceGroupe objects filtered by the menu_groupid column
 * @method     ChildDistributionPlaceGroupe[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionPlaceGroupeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\DistributionPlace\Base\DistributionPlaceGroupeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\DistributionPlace\\DistributionPlaceGroupe', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionPlaceGroupeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionPlaceGroupeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionPlaceGroupeQuery) {
            return $criteria;
        }
        $query = new ChildDistributionPlaceGroupeQuery();
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
     * @param array[$distribution_placeid, $menu_groupid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionPlaceGroupe|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionPlaceGroupeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionPlaceGroupeTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildDistributionPlaceGroupe A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distribution_placeid, menu_groupid FROM distribution_place_groupe WHERE distribution_placeid = :p0 AND menu_groupid = :p1';
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
            /** @var ChildDistributionPlaceGroupe $obj */
            $obj = new ChildDistributionPlaceGroupe();
            $obj->hydrate($row);
            DistributionPlaceGroupeTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildDistributionPlaceGroupe|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceid($distributionPlaceid = null, $comparison = null)
    {
        if (is_array($distributionPlaceid)) {
            $useMinMax = false;
            if (isset($distributionPlaceid['min'])) {
                $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionPlaceid['max'])) {
                $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid, $comparison);
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
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlace object
     *
     * @param \API\Models\DistributionPlace\DistributionPlace|ObjectCollection $distributionPlace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function filterByDistributionPlace($distributionPlace, $comparison = null)
    {
        if ($distributionPlace instanceof \API\Models\DistributionPlace\DistributionPlace) {
            return $this
                ->addUsingAlias(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->getDistributionPlaceid(), $comparison);
        } elseif ($distributionPlace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->toKeyValue('DistributionPlaceid', 'DistributionPlaceid'), $comparison);
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
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Menu\MenuGroup object
     *
     * @param \API\Models\Menu\MenuGroup|ObjectCollection $menuGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function filterByMenuGroup($menuGroup, $comparison = null)
    {
        if ($menuGroup instanceof \API\Models\Menu\MenuGroup) {
            return $this
                ->addUsingAlias(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $menuGroup->getMenuGroupid(), $comparison);
        } elseif ($menuGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID, $menuGroup->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
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
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildDistributionPlaceGroupe $distributionPlaceGroupe Object to remove from the list of results
     *
     * @return $this|ChildDistributionPlaceGroupeQuery The current query, for fluid interface
     */
    public function prune($distributionPlaceGroupe = null)
    {
        if ($distributionPlaceGroupe) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionPlaceGroupeTableMap::COL_DISTRIBUTION_PLACEID), $distributionPlaceGroupe->getDistributionPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionPlaceGroupeTableMap::COL_MENU_GROUPID), $distributionPlaceGroupe->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distribution_place_groupe table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceGroupeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionPlaceGroupeTableMap::clearInstancePool();
            DistributionPlaceGroupeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceGroupeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionPlaceGroupeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionPlaceGroupeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionPlaceGroupeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionPlaceGroupeQuery
