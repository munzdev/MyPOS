<?php

namespace Model\DistributionPlace\Base;

use \Exception;
use \PDO;
use Model\DistributionPlace\DistributionsPlacesGroupes as ChildDistributionsPlacesGroupes;
use Model\DistributionPlace\DistributionsPlacesGroupesQuery as ChildDistributionsPlacesGroupesQuery;
use Model\DistributionPlace\Map\DistributionsPlacesGroupesTableMap;
use Model\Menues\MenuGroupes;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distributions_places_groupes' table.
 *
 *
 *
 * @method     ChildDistributionsPlacesGroupesQuery orderByDistributionsPlaceid($order = Criteria::ASC) Order by the distributions_placeid column
 * @method     ChildDistributionsPlacesGroupesQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 *
 * @method     ChildDistributionsPlacesGroupesQuery groupByDistributionsPlaceid() Group by the distributions_placeid column
 * @method     ChildDistributionsPlacesGroupesQuery groupByMenuGroupid() Group by the menu_groupid column
 *
 * @method     ChildDistributionsPlacesGroupesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionsPlacesGroupesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionsPlacesGroupesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionsPlacesGroupesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesGroupesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesGroupesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionsPlacesGroupesQuery leftJoinDistributionsPlaces($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesGroupesQuery rightJoinDistributionsPlaces($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesGroupesQuery innerJoinDistributionsPlaces($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesGroupesQuery joinWithDistributionsPlaces($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesGroupesQuery leftJoinWithDistributionsPlaces() Adds a LEFT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesGroupesQuery rightJoinWithDistributionsPlaces() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesGroupesQuery innerJoinWithDistributionsPlaces() Adds a INNER JOIN clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesGroupesQuery leftJoinMenuGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesGroupesQuery rightJoinMenuGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesGroupesQuery innerJoinMenuGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroupes relation
 *
 * @method     ChildDistributionsPlacesGroupesQuery joinWithMenuGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildDistributionsPlacesGroupesQuery leftJoinWithMenuGroupes() Adds a LEFT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesGroupesQuery rightJoinWithMenuGroupes() Adds a RIGHT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesGroupesQuery innerJoinWithMenuGroupes() Adds a INNER JOIN clause and with to the query using the MenuGroupes relation
 *
 * @method     \Model\DistributionPlace\DistributionsPlacesQuery|\Model\Menues\MenuGroupesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionsPlacesGroupes findOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesGroupes matching the query
 * @method     ChildDistributionsPlacesGroupes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesGroupes matching the query, or a new ChildDistributionsPlacesGroupes object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionsPlacesGroupes findOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlacesGroupes filtered by the distributions_placeid column
 * @method     ChildDistributionsPlacesGroupes findOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionsPlacesGroupes filtered by the menu_groupid column *

 * @method     ChildDistributionsPlacesGroupes requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionsPlacesGroupes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesGroupes requireOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesGroupes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlacesGroupes requireOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlacesGroupes filtered by the distributions_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesGroupes requireOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionsPlacesGroupes filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlacesGroupes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionsPlacesGroupes objects based on current ModelCriteria
 * @method     ChildDistributionsPlacesGroupes[]|ObjectCollection findByDistributionsPlaceid(int $distributions_placeid) Return ChildDistributionsPlacesGroupes objects filtered by the distributions_placeid column
 * @method     ChildDistributionsPlacesGroupes[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildDistributionsPlacesGroupes objects filtered by the menu_groupid column
 * @method     ChildDistributionsPlacesGroupes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionsPlacesGroupesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\DistributionPlace\Base\DistributionsPlacesGroupesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\DistributionPlace\\DistributionsPlacesGroupes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionsPlacesGroupesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionsPlacesGroupesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionsPlacesGroupesQuery) {
            return $criteria;
        }
        $query = new ChildDistributionsPlacesGroupesQuery();
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
     * @param array[$distributions_placeid, $menu_groupid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionsPlacesGroupes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionsPlacesGroupesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionsPlacesGroupesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildDistributionsPlacesGroupes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distributions_placeid, menu_groupid FROM distributions_places_groupes WHERE distributions_placeid = :p0 AND menu_groupid = :p1';
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
            /** @var ChildDistributionsPlacesGroupes $obj */
            $obj = new ChildDistributionsPlacesGroupes();
            $obj->hydrate($row);
            DistributionsPlacesGroupesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildDistributionsPlacesGroupes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the distributions_placeid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionsPlaceid(1234); // WHERE distributions_placeid = 1234
     * $query->filterByDistributionsPlaceid(array(12, 34)); // WHERE distributions_placeid IN (12, 34)
     * $query->filterByDistributionsPlaceid(array('min' => 12)); // WHERE distributions_placeid > 12
     * </code>
     *
     * @see       filterByDistributionsPlaces()
     *
     * @param     mixed $distributionsPlaceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaceid($distributionsPlaceid = null, $comparison = null)
    {
        if (is_array($distributionsPlaceid)) {
            $useMinMax = false;
            if (isset($distributionsPlaceid['min'])) {
                $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionsPlaceid['max'])) {
                $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid, $comparison);
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
     * @see       filterByMenuGroupes()
     *
     * @param     mixed $menuGroupid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query by a related \Model\DistributionPlace\DistributionsPlaces object
     *
     * @param \Model\DistributionPlace\DistributionsPlaces|ObjectCollection $distributionsPlaces The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaces($distributionsPlaces, $comparison = null)
    {
        if ($distributionsPlaces instanceof \Model\DistributionPlace\DistributionsPlaces) {
            return $this
                ->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaces->getDistributionsPlaceid(), $comparison);
        } elseif ($distributionsPlaces instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaces->toKeyValue('DistributionsPlaceid', 'DistributionsPlaceid'), $comparison);
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
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Menues\MenuGroupes object
     *
     * @param \Model\Menues\MenuGroupes|ObjectCollection $menuGroupes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupes($menuGroupes, $comparison = null)
    {
        if ($menuGroupes instanceof \Model\Menues\MenuGroupes) {
            return $this
                ->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $menuGroupes->getMenuGroupid(), $comparison);
        } elseif ($menuGroupes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID, $menuGroupes->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
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
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
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
     * @param   ChildDistributionsPlacesGroupes $distributionsPlacesGroupes Object to remove from the list of results
     *
     * @return $this|ChildDistributionsPlacesGroupesQuery The current query, for fluid interface
     */
    public function prune($distributionsPlacesGroupes = null)
    {
        if ($distributionsPlacesGroupes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionsPlacesGroupesTableMap::COL_DISTRIBUTIONS_PLACEID), $distributionsPlacesGroupes->getDistributionsPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionsPlacesGroupesTableMap::COL_MENU_GROUPID), $distributionsPlacesGroupes->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distributions_places_groupes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesGroupesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionsPlacesGroupesTableMap::clearInstancePool();
            DistributionsPlacesGroupesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesGroupesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionsPlacesGroupesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionsPlacesGroupesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionsPlacesGroupesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionsPlacesGroupesQuery
