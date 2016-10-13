<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\Menues\Availabilitys as ChildAvailabilitys;
use Model\Menues\AvailabilitysQuery as ChildAvailabilitysQuery;
use Model\Menues\Map\AvailabilitysTableMap;
use Model\Ordering\OrdersDetails;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'availabilitys' table.
 *
 *
 *
 * @method     ChildAvailabilitysQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildAvailabilitysQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildAvailabilitysQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildAvailabilitysQuery groupByName() Group by the name column
 *
 * @method     ChildAvailabilitysQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAvailabilitysQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAvailabilitysQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAvailabilitysQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAvailabilitysQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAvailabilitysQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAvailabilitysQuery leftJoinMenuExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuExtras relation
 * @method     ChildAvailabilitysQuery rightJoinMenuExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuExtras relation
 * @method     ChildAvailabilitysQuery innerJoinMenuExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuExtras relation
 *
 * @method     ChildAvailabilitysQuery joinWithMenuExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuExtras relation
 *
 * @method     ChildAvailabilitysQuery leftJoinWithMenuExtras() Adds a LEFT JOIN clause and with to the query using the MenuExtras relation
 * @method     ChildAvailabilitysQuery rightJoinWithMenuExtras() Adds a RIGHT JOIN clause and with to the query using the MenuExtras relation
 * @method     ChildAvailabilitysQuery innerJoinWithMenuExtras() Adds a INNER JOIN clause and with to the query using the MenuExtras relation
 *
 * @method     ChildAvailabilitysQuery leftJoinMenues($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menues relation
 * @method     ChildAvailabilitysQuery rightJoinMenues($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menues relation
 * @method     ChildAvailabilitysQuery innerJoinMenues($relationAlias = null) Adds a INNER JOIN clause to the query using the Menues relation
 *
 * @method     ChildAvailabilitysQuery joinWithMenues($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menues relation
 *
 * @method     ChildAvailabilitysQuery leftJoinWithMenues() Adds a LEFT JOIN clause and with to the query using the Menues relation
 * @method     ChildAvailabilitysQuery rightJoinWithMenues() Adds a RIGHT JOIN clause and with to the query using the Menues relation
 * @method     ChildAvailabilitysQuery innerJoinWithMenues() Adds a INNER JOIN clause and with to the query using the Menues relation
 *
 * @method     ChildAvailabilitysQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildAvailabilitysQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildAvailabilitysQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildAvailabilitysQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildAvailabilitysQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildAvailabilitysQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildAvailabilitysQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     \Model\Menues\MenuExtrasQuery|\Model\Menues\MenuesQuery|\Model\Ordering\OrdersDetailsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAvailabilitys findOne(ConnectionInterface $con = null) Return the first ChildAvailabilitys matching the query
 * @method     ChildAvailabilitys findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAvailabilitys matching the query, or a new ChildAvailabilitys object populated from the query conditions when no match is found
 *
 * @method     ChildAvailabilitys findOneByAvailabilityid(int $availabilityid) Return the first ChildAvailabilitys filtered by the availabilityid column
 * @method     ChildAvailabilitys findOneByName(string $name) Return the first ChildAvailabilitys filtered by the name column *

 * @method     ChildAvailabilitys requirePk($key, ConnectionInterface $con = null) Return the ChildAvailabilitys by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAvailabilitys requireOne(ConnectionInterface $con = null) Return the first ChildAvailabilitys matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAvailabilitys requireOneByAvailabilityid(int $availabilityid) Return the first ChildAvailabilitys filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAvailabilitys requireOneByName(string $name) Return the first ChildAvailabilitys filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAvailabilitys[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAvailabilitys objects based on current ModelCriteria
 * @method     ChildAvailabilitys[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildAvailabilitys objects filtered by the availabilityid column
 * @method     ChildAvailabilitys[]|ObjectCollection findByName(string $name) Return ChildAvailabilitys objects filtered by the name column
 * @method     ChildAvailabilitys[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AvailabilitysQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Menues\Base\AvailabilitysQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Menues\\Availabilitys', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAvailabilitysQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAvailabilitysQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAvailabilitysQuery) {
            return $criteria;
        }
        $query = new ChildAvailabilitysQuery();
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
     * @return ChildAvailabilitys|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AvailabilitysTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AvailabilitysTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAvailabilitys A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT availabilityid, name FROM availabilitys WHERE availabilityid = :p0';
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
            /** @var ChildAvailabilitys $obj */
            $obj = new ChildAvailabilitys();
            $obj->hydrate($row);
            AvailabilitysTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAvailabilitys|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $keys, Criteria::IN);
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
     * @param     mixed $availabilityid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
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
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AvailabilitysTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \Model\Menues\MenuExtras object
     *
     * @param \Model\Menues\MenuExtras|ObjectCollection $menuExtras the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByMenuExtras($menuExtras, $comparison = null)
    {
        if ($menuExtras instanceof \Model\Menues\MenuExtras) {
            return $this
                ->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $menuExtras->getAvailabilityid(), $comparison);
        } elseif ($menuExtras instanceof ObjectCollection) {
            return $this
                ->useMenuExtrasQuery()
                ->filterByPrimaryKeys($menuExtras->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuExtras() only accepts arguments of type \Model\Menues\MenuExtras or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuExtras relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function joinMenuExtras($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuExtras');

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
            $this->addJoinObject($join, 'MenuExtras');
        }

        return $this;
    }

    /**
     * Use the MenuExtras relation MenuExtras object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuExtrasQuery A secondary query class using the current class as primary query
     */
    public function useMenuExtrasQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuExtras($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuExtras', '\Model\Menues\MenuExtrasQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\Menues object
     *
     * @param \Model\Menues\Menues|ObjectCollection $menues the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByMenues($menues, $comparison = null)
    {
        if ($menues instanceof \Model\Menues\Menues) {
            return $this
                ->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $menues->getAvailabilityid(), $comparison);
        } elseif ($menues instanceof ObjectCollection) {
            return $this
                ->useMenuesQuery()
                ->filterByPrimaryKeys($menues->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenues() only accepts arguments of type \Model\Menues\Menues or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menues relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function joinMenues($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Menues');

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
            $this->addJoinObject($join, 'Menues');
        }

        return $this;
    }

    /**
     * Use the Menues relation Menues object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuesQuery A secondary query class using the current class as primary query
     */
    public function useMenuesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenues($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menues', '\Model\Menues\MenuesQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetails object
     *
     * @param \Model\Ordering\OrdersDetails|ObjectCollection $ordersDetails the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \Model\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $ordersDetails->getAvailabilityid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            return $this
                ->useOrdersDetailsQuery()
                ->filterByPrimaryKeys($ordersDetails->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersDetails() only accepts arguments of type \Model\Ordering\OrdersDetails or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersDetails relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
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
     * @return \Model\Ordering\OrdersDetailsQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\Model\Ordering\OrdersDetailsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAvailabilitys $availabilitys Object to remove from the list of results
     *
     * @return $this|ChildAvailabilitysQuery The current query, for fluid interface
     */
    public function prune($availabilitys = null)
    {
        if ($availabilitys) {
            $this->addUsingAlias(AvailabilitysTableMap::COL_AVAILABILITYID, $availabilitys->getAvailabilityid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the availabilitys table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AvailabilitysTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AvailabilitysTableMap::clearInstancePool();
            AvailabilitysTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AvailabilitysTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AvailabilitysTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AvailabilitysTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AvailabilitysTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AvailabilitysQuery
