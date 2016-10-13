<?php

namespace Model\OIP\Base;

use \Exception;
use \PDO;
use Model\OIP\DistributionsGivingOuts as ChildDistributionsGivingOuts;
use Model\OIP\DistributionsGivingOutsQuery as ChildDistributionsGivingOutsQuery;
use Model\OIP\Map\DistributionsGivingOutsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distributions_giving_outs' table.
 *
 *
 *
 * @method     ChildDistributionsGivingOutsQuery orderByDistributionsGivingOutid($order = Criteria::ASC) Order by the distributions_giving_outid column
 * @method     ChildDistributionsGivingOutsQuery orderByDate($order = Criteria::ASC) Order by the date column
 *
 * @method     ChildDistributionsGivingOutsQuery groupByDistributionsGivingOutid() Group by the distributions_giving_outid column
 * @method     ChildDistributionsGivingOutsQuery groupByDate() Group by the date column
 *
 * @method     ChildDistributionsGivingOutsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionsGivingOutsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionsGivingOutsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionsGivingOutsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionsGivingOutsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionsGivingOutsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionsGivingOutsQuery leftJoinOrdersInProgressRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersInProgressRecieved relation
 * @method     ChildDistributionsGivingOutsQuery rightJoinOrdersInProgressRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersInProgressRecieved relation
 * @method     ChildDistributionsGivingOutsQuery innerJoinOrdersInProgressRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersInProgressRecieved relation
 *
 * @method     ChildDistributionsGivingOutsQuery joinWithOrdersInProgressRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersInProgressRecieved relation
 *
 * @method     ChildDistributionsGivingOutsQuery leftJoinWithOrdersInProgressRecieved() Adds a LEFT JOIN clause and with to the query using the OrdersInProgressRecieved relation
 * @method     ChildDistributionsGivingOutsQuery rightJoinWithOrdersInProgressRecieved() Adds a RIGHT JOIN clause and with to the query using the OrdersInProgressRecieved relation
 * @method     ChildDistributionsGivingOutsQuery innerJoinWithOrdersInProgressRecieved() Adds a INNER JOIN clause and with to the query using the OrdersInProgressRecieved relation
 *
 * @method     \Model\OIP\OrdersInProgressRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionsGivingOuts findOne(ConnectionInterface $con = null) Return the first ChildDistributionsGivingOuts matching the query
 * @method     ChildDistributionsGivingOuts findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionsGivingOuts matching the query, or a new ChildDistributionsGivingOuts object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionsGivingOuts findOneByDistributionsGivingOutid(int $distributions_giving_outid) Return the first ChildDistributionsGivingOuts filtered by the distributions_giving_outid column
 * @method     ChildDistributionsGivingOuts findOneByDate(string $date) Return the first ChildDistributionsGivingOuts filtered by the date column *

 * @method     ChildDistributionsGivingOuts requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionsGivingOuts by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsGivingOuts requireOne(ConnectionInterface $con = null) Return the first ChildDistributionsGivingOuts matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsGivingOuts requireOneByDistributionsGivingOutid(int $distributions_giving_outid) Return the first ChildDistributionsGivingOuts filtered by the distributions_giving_outid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsGivingOuts requireOneByDate(string $date) Return the first ChildDistributionsGivingOuts filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsGivingOuts[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionsGivingOuts objects based on current ModelCriteria
 * @method     ChildDistributionsGivingOuts[]|ObjectCollection findByDistributionsGivingOutid(int $distributions_giving_outid) Return ChildDistributionsGivingOuts objects filtered by the distributions_giving_outid column
 * @method     ChildDistributionsGivingOuts[]|ObjectCollection findByDate(string $date) Return ChildDistributionsGivingOuts objects filtered by the date column
 * @method     ChildDistributionsGivingOuts[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionsGivingOutsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\OIP\Base\DistributionsGivingOutsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\OIP\\DistributionsGivingOuts', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionsGivingOutsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionsGivingOutsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionsGivingOutsQuery) {
            return $criteria;
        }
        $query = new ChildDistributionsGivingOutsQuery();
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
     * @return ChildDistributionsGivingOuts|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionsGivingOutsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionsGivingOutsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildDistributionsGivingOuts A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distributions_giving_outid, date FROM distributions_giving_outs WHERE distributions_giving_outid = :p0';
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
            /** @var ChildDistributionsGivingOuts $obj */
            $obj = new ChildDistributionsGivingOuts();
            $obj->hydrate($row);
            DistributionsGivingOutsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildDistributionsGivingOuts|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the distributions_giving_outid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionsGivingOutid(1234); // WHERE distributions_giving_outid = 1234
     * $query->filterByDistributionsGivingOutid(array(12, 34)); // WHERE distributions_giving_outid IN (12, 34)
     * $query->filterByDistributionsGivingOutid(array('min' => 12)); // WHERE distributions_giving_outid > 12
     * </code>
     *
     * @param     mixed $distributionsGivingOutid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function filterByDistributionsGivingOutid($distributionsGivingOutid = null, $comparison = null)
    {
        if (is_array($distributionsGivingOutid)) {
            $useMinMax = false;
            if (isset($distributionsGivingOutid['min'])) {
                $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOutid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionsGivingOutid['max'])) {
                $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOutid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOutid, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query by a related \Model\OIP\OrdersInProgressRecieved object
     *
     * @param \Model\OIP\OrdersInProgressRecieved|ObjectCollection $ordersInProgressRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgressRecieved($ordersInProgressRecieved, $comparison = null)
    {
        if ($ordersInProgressRecieved instanceof \Model\OIP\OrdersInProgressRecieved) {
            return $this
                ->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $ordersInProgressRecieved->getDistributionsGivingOutid(), $comparison);
        } elseif ($ordersInProgressRecieved instanceof ObjectCollection) {
            return $this
                ->useOrdersInProgressRecievedQuery()
                ->filterByPrimaryKeys($ordersInProgressRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersInProgressRecieved() only accepts arguments of type \Model\OIP\OrdersInProgressRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersInProgressRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function joinOrdersInProgressRecieved($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersInProgressRecieved');

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
            $this->addJoinObject($join, 'OrdersInProgressRecieved');
        }

        return $this;
    }

    /**
     * Use the OrdersInProgressRecieved relation OrdersInProgressRecieved object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OIP\OrdersInProgressRecievedQuery A secondary query class using the current class as primary query
     */
    public function useOrdersInProgressRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersInProgressRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersInProgressRecieved', '\Model\OIP\OrdersInProgressRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionsGivingOuts $distributionsGivingOuts Object to remove from the list of results
     *
     * @return $this|ChildDistributionsGivingOutsQuery The current query, for fluid interface
     */
    public function prune($distributionsGivingOuts = null)
    {
        if ($distributionsGivingOuts) {
            $this->addUsingAlias(DistributionsGivingOutsTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOuts->getDistributionsGivingOutid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distributions_giving_outs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsGivingOutsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionsGivingOutsTableMap::clearInstancePool();
            DistributionsGivingOutsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsGivingOutsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionsGivingOutsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionsGivingOutsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionsGivingOutsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionsGivingOutsQuery
