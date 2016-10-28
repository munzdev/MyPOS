<?php

namespace API\Models\OIP\Base;

use \Exception;
use \PDO;
use API\Models\OIP\DistributionGivingOut as ChildDistributionGivingOut;
use API\Models\OIP\DistributionGivingOutQuery as ChildDistributionGivingOutQuery;
use API\Models\OIP\Map\DistributionGivingOutTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distribution_giving_out' table.
 *
 *
 *
 * @method     ChildDistributionGivingOutQuery orderByDistributionGivingOutid($order = Criteria::ASC) Order by the distribution_giving_outid column
 * @method     ChildDistributionGivingOutQuery orderByDate($order = Criteria::ASC) Order by the date column
 *
 * @method     ChildDistributionGivingOutQuery groupByDistributionGivingOutid() Group by the distribution_giving_outid column
 * @method     ChildDistributionGivingOutQuery groupByDate() Group by the date column
 *
 * @method     ChildDistributionGivingOutQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionGivingOutQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionGivingOutQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionGivingOutQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionGivingOutQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionGivingOutQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionGivingOutQuery leftJoinOrderInProgressRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgressRecieved relation
 * @method     ChildDistributionGivingOutQuery rightJoinOrderInProgressRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgressRecieved relation
 * @method     ChildDistributionGivingOutQuery innerJoinOrderInProgressRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgressRecieved relation
 *
 * @method     ChildDistributionGivingOutQuery joinWithOrderInProgressRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgressRecieved relation
 *
 * @method     ChildDistributionGivingOutQuery leftJoinWithOrderInProgressRecieved() Adds a LEFT JOIN clause and with to the query using the OrderInProgressRecieved relation
 * @method     ChildDistributionGivingOutQuery rightJoinWithOrderInProgressRecieved() Adds a RIGHT JOIN clause and with to the query using the OrderInProgressRecieved relation
 * @method     ChildDistributionGivingOutQuery innerJoinWithOrderInProgressRecieved() Adds a INNER JOIN clause and with to the query using the OrderInProgressRecieved relation
 *
 * @method     \API\Models\OIP\OrderInProgressRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionGivingOut findOne(ConnectionInterface $con = null) Return the first ChildDistributionGivingOut matching the query
 * @method     ChildDistributionGivingOut findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionGivingOut matching the query, or a new ChildDistributionGivingOut object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionGivingOut findOneByDistributionGivingOutid(int $distribution_giving_outid) Return the first ChildDistributionGivingOut filtered by the distribution_giving_outid column
 * @method     ChildDistributionGivingOut findOneByDate(string $date) Return the first ChildDistributionGivingOut filtered by the date column *

 * @method     ChildDistributionGivingOut requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionGivingOut by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionGivingOut requireOne(ConnectionInterface $con = null) Return the first ChildDistributionGivingOut matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionGivingOut requireOneByDistributionGivingOutid(int $distribution_giving_outid) Return the first ChildDistributionGivingOut filtered by the distribution_giving_outid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionGivingOut requireOneByDate(string $date) Return the first ChildDistributionGivingOut filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionGivingOut[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionGivingOut objects based on current ModelCriteria
 * @method     ChildDistributionGivingOut[]|ObjectCollection findByDistributionGivingOutid(int $distribution_giving_outid) Return ChildDistributionGivingOut objects filtered by the distribution_giving_outid column
 * @method     ChildDistributionGivingOut[]|ObjectCollection findByDate(string $date) Return ChildDistributionGivingOut objects filtered by the date column
 * @method     ChildDistributionGivingOut[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionGivingOutQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\OIP\Base\DistributionGivingOutQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\OIP\\DistributionGivingOut', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionGivingOutQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionGivingOutQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionGivingOutQuery) {
            return $criteria;
        }
        $query = new ChildDistributionGivingOutQuery();
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
     * @return ChildDistributionGivingOut|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionGivingOutTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionGivingOutTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildDistributionGivingOut A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distribution_giving_outid, date FROM distribution_giving_out WHERE distribution_giving_outid = :p0';
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
            /** @var ChildDistributionGivingOut $obj */
            $obj = new ChildDistributionGivingOut();
            $obj->hydrate($row);
            DistributionGivingOutTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildDistributionGivingOut|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the distribution_giving_outid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionGivingOutid(1234); // WHERE distribution_giving_outid = 1234
     * $query->filterByDistributionGivingOutid(array(12, 34)); // WHERE distribution_giving_outid IN (12, 34)
     * $query->filterByDistributionGivingOutid(array('min' => 12)); // WHERE distribution_giving_outid > 12
     * </code>
     *
     * @param     mixed $distributionGivingOutid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function filterByDistributionGivingOutid($distributionGivingOutid = null, $comparison = null)
    {
        if (is_array($distributionGivingOutid)) {
            $useMinMax = false;
            if (isset($distributionGivingOutid['min'])) {
                $this->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOutid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionGivingOutid['max'])) {
                $this->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOutid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOutid, $comparison);
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
     * @return $this|ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(DistributionGivingOutTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(DistributionGivingOutTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionGivingOutTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\OIP\OrderInProgressRecieved object
     *
     * @param \API\Models\OIP\OrderInProgressRecieved|ObjectCollection $orderInProgressRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function filterByOrderInProgressRecieved($orderInProgressRecieved, $comparison = null)
    {
        if ($orderInProgressRecieved instanceof \API\Models\OIP\OrderInProgressRecieved) {
            return $this
                ->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $orderInProgressRecieved->getDistributionGivingOutid(), $comparison);
        } elseif ($orderInProgressRecieved instanceof ObjectCollection) {
            return $this
                ->useOrderInProgressRecievedQuery()
                ->filterByPrimaryKeys($orderInProgressRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderInProgressRecieved() only accepts arguments of type \API\Models\OIP\OrderInProgressRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderInProgressRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function joinOrderInProgressRecieved($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderInProgressRecieved');

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
            $this->addJoinObject($join, 'OrderInProgressRecieved');
        }

        return $this;
    }

    /**
     * Use the OrderInProgressRecieved relation OrderInProgressRecieved object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\OIP\OrderInProgressRecievedQuery A secondary query class using the current class as primary query
     */
    public function useOrderInProgressRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderInProgressRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderInProgressRecieved', '\API\Models\OIP\OrderInProgressRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionGivingOut $distributionGivingOut Object to remove from the list of results
     *
     * @return $this|ChildDistributionGivingOutQuery The current query, for fluid interface
     */
    public function prune($distributionGivingOut = null)
    {
        if ($distributionGivingOut) {
            $this->addUsingAlias(DistributionGivingOutTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOut->getDistributionGivingOutid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distribution_giving_out table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionGivingOutTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionGivingOutTableMap::clearInstancePool();
            DistributionGivingOutTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionGivingOutTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionGivingOutTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionGivingOutTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionGivingOutTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionGivingOutQuery
