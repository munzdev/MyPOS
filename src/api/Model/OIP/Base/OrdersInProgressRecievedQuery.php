<?php

namespace Model\OIP\Base;

use \Exception;
use \PDO;
use Model\OIP\OrdersInProgressRecieved as ChildOrdersInProgressRecieved;
use Model\OIP\OrdersInProgressRecievedQuery as ChildOrdersInProgressRecievedQuery;
use Model\OIP\Map\OrdersInProgressRecievedTableMap;
use Model\Ordering\OrdersDetails;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'orders_in_progress_recieved' table.
 *
 *
 *
 * @method     ChildOrdersInProgressRecievedQuery orderByOrdersInProgressRecievedid($order = Criteria::ASC) Order by the orders_in_progress_recievedid column
 * @method     ChildOrdersInProgressRecievedQuery orderByOrdersDetailid($order = Criteria::ASC) Order by the orders_detailid column
 * @method     ChildOrdersInProgressRecievedQuery orderByOrdersInProgressid($order = Criteria::ASC) Order by the orders_in_progressid column
 * @method     ChildOrdersInProgressRecievedQuery orderByDistributionsGivingOutid($order = Criteria::ASC) Order by the distributions_giving_outid column
 * @method     ChildOrdersInProgressRecievedQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 *
 * @method     ChildOrdersInProgressRecievedQuery groupByOrdersInProgressRecievedid() Group by the orders_in_progress_recievedid column
 * @method     ChildOrdersInProgressRecievedQuery groupByOrdersDetailid() Group by the orders_detailid column
 * @method     ChildOrdersInProgressRecievedQuery groupByOrdersInProgressid() Group by the orders_in_progressid column
 * @method     ChildOrdersInProgressRecievedQuery groupByDistributionsGivingOutid() Group by the distributions_giving_outid column
 * @method     ChildOrdersInProgressRecievedQuery groupByAmount() Group by the amount column
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrdersInProgressRecievedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrdersInProgressRecievedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrdersInProgressRecievedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrdersInProgressRecievedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildOrdersInProgressRecievedQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildOrdersInProgressRecievedQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildOrdersInProgressRecievedQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildOrdersInProgressRecievedQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildOrdersInProgressRecievedQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinOrdersInProgress($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersInProgress relation
 * @method     ChildOrdersInProgressRecievedQuery rightJoinOrdersInProgress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersInProgress relation
 * @method     ChildOrdersInProgressRecievedQuery innerJoinOrdersInProgress($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersInProgress relation
 *
 * @method     ChildOrdersInProgressRecievedQuery joinWithOrdersInProgress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersInProgress relation
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinWithOrdersInProgress() Adds a LEFT JOIN clause and with to the query using the OrdersInProgress relation
 * @method     ChildOrdersInProgressRecievedQuery rightJoinWithOrdersInProgress() Adds a RIGHT JOIN clause and with to the query using the OrdersInProgress relation
 * @method     ChildOrdersInProgressRecievedQuery innerJoinWithOrdersInProgress() Adds a INNER JOIN clause and with to the query using the OrdersInProgress relation
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinDistributionsGivingOuts($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsGivingOuts relation
 * @method     ChildOrdersInProgressRecievedQuery rightJoinDistributionsGivingOuts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsGivingOuts relation
 * @method     ChildOrdersInProgressRecievedQuery innerJoinDistributionsGivingOuts($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsGivingOuts relation
 *
 * @method     ChildOrdersInProgressRecievedQuery joinWithDistributionsGivingOuts($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsGivingOuts relation
 *
 * @method     ChildOrdersInProgressRecievedQuery leftJoinWithDistributionsGivingOuts() Adds a LEFT JOIN clause and with to the query using the DistributionsGivingOuts relation
 * @method     ChildOrdersInProgressRecievedQuery rightJoinWithDistributionsGivingOuts() Adds a RIGHT JOIN clause and with to the query using the DistributionsGivingOuts relation
 * @method     ChildOrdersInProgressRecievedQuery innerJoinWithDistributionsGivingOuts() Adds a INNER JOIN clause and with to the query using the DistributionsGivingOuts relation
 *
 * @method     \Model\Ordering\OrdersDetailsQuery|\Model\OIP\OrdersInProgressQuery|\Model\OIP\DistributionsGivingOutsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrdersInProgressRecieved findOne(ConnectionInterface $con = null) Return the first ChildOrdersInProgressRecieved matching the query
 * @method     ChildOrdersInProgressRecieved findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrdersInProgressRecieved matching the query, or a new ChildOrdersInProgressRecieved object populated from the query conditions when no match is found
 *
 * @method     ChildOrdersInProgressRecieved findOneByOrdersInProgressRecievedid(int $orders_in_progress_recievedid) Return the first ChildOrdersInProgressRecieved filtered by the orders_in_progress_recievedid column
 * @method     ChildOrdersInProgressRecieved findOneByOrdersDetailid(int $orders_detailid) Return the first ChildOrdersInProgressRecieved filtered by the orders_detailid column
 * @method     ChildOrdersInProgressRecieved findOneByOrdersInProgressid(int $orders_in_progressid) Return the first ChildOrdersInProgressRecieved filtered by the orders_in_progressid column
 * @method     ChildOrdersInProgressRecieved findOneByDistributionsGivingOutid(int $distributions_giving_outid) Return the first ChildOrdersInProgressRecieved filtered by the distributions_giving_outid column
 * @method     ChildOrdersInProgressRecieved findOneByAmount(int $amount) Return the first ChildOrdersInProgressRecieved filtered by the amount column *

 * @method     ChildOrdersInProgressRecieved requirePk($key, ConnectionInterface $con = null) Return the ChildOrdersInProgressRecieved by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgressRecieved requireOne(ConnectionInterface $con = null) Return the first ChildOrdersInProgressRecieved matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersInProgressRecieved requireOneByOrdersInProgressRecievedid(int $orders_in_progress_recievedid) Return the first ChildOrdersInProgressRecieved filtered by the orders_in_progress_recievedid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgressRecieved requireOneByOrdersDetailid(int $orders_detailid) Return the first ChildOrdersInProgressRecieved filtered by the orders_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgressRecieved requireOneByOrdersInProgressid(int $orders_in_progressid) Return the first ChildOrdersInProgressRecieved filtered by the orders_in_progressid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgressRecieved requireOneByDistributionsGivingOutid(int $distributions_giving_outid) Return the first ChildOrdersInProgressRecieved filtered by the distributions_giving_outid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgressRecieved requireOneByAmount(int $amount) Return the first ChildOrdersInProgressRecieved filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersInProgressRecieved[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrdersInProgressRecieved objects based on current ModelCriteria
 * @method     ChildOrdersInProgressRecieved[]|ObjectCollection findByOrdersInProgressRecievedid(int $orders_in_progress_recievedid) Return ChildOrdersInProgressRecieved objects filtered by the orders_in_progress_recievedid column
 * @method     ChildOrdersInProgressRecieved[]|ObjectCollection findByOrdersDetailid(int $orders_detailid) Return ChildOrdersInProgressRecieved objects filtered by the orders_detailid column
 * @method     ChildOrdersInProgressRecieved[]|ObjectCollection findByOrdersInProgressid(int $orders_in_progressid) Return ChildOrdersInProgressRecieved objects filtered by the orders_in_progressid column
 * @method     ChildOrdersInProgressRecieved[]|ObjectCollection findByDistributionsGivingOutid(int $distributions_giving_outid) Return ChildOrdersInProgressRecieved objects filtered by the distributions_giving_outid column
 * @method     ChildOrdersInProgressRecieved[]|ObjectCollection findByAmount(int $amount) Return ChildOrdersInProgressRecieved objects filtered by the amount column
 * @method     ChildOrdersInProgressRecieved[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrdersInProgressRecievedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\OIP\Base\OrdersInProgressRecievedQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\OIP\\OrdersInProgressRecieved', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrdersInProgressRecievedQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrdersInProgressRecievedQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrdersInProgressRecievedQuery) {
            return $criteria;
        }
        $query = new ChildOrdersInProgressRecievedQuery();
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
     * $obj = $c->findPk(array(12, 34, 56, 78), $con);
     * </code>
     *
     * @param array[$orders_in_progress_recievedid, $orders_detailid, $orders_in_progressid, $distributions_giving_outid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildOrdersInProgressRecieved|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrdersInProgressRecievedTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrdersInProgressRecievedTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2]), (null === $key[3] || is_scalar($key[3]) || is_callable([$key[3], '__toString']) ? (string) $key[3] : $key[3])]))))) {
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
     * @return ChildOrdersInProgressRecieved A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT orders_in_progress_recievedid, orders_detailid, orders_in_progressid, distributions_giving_outid, amount FROM orders_in_progress_recieved WHERE orders_in_progress_recievedid = :p0 AND orders_detailid = :p1 AND orders_in_progressid = :p2 AND distributions_giving_outid = :p3';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->bindValue(':p3', $key[3], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildOrdersInProgressRecieved $obj */
            $obj = new ChildOrdersInProgressRecieved();
            $obj->hydrate($row);
            OrdersInProgressRecievedTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2]), (null === $key[3] || is_scalar($key[3]) || is_callable([$key[3], '__toString']) ? (string) $key[3] : $key[3])]));
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
     * @return ChildOrdersInProgressRecieved|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $key[2], Criteria::EQUAL);
        $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $key[3], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $cton3 = $this->getNewCriterion(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $key[3], Criteria::EQUAL);
            $cton0->addAnd($cton3);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the orders_in_progress_recievedid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrdersInProgressRecievedid(1234); // WHERE orders_in_progress_recievedid = 1234
     * $query->filterByOrdersInProgressRecievedid(array(12, 34)); // WHERE orders_in_progress_recievedid IN (12, 34)
     * $query->filterByOrdersInProgressRecievedid(array('min' => 12)); // WHERE orders_in_progress_recievedid > 12
     * </code>
     *
     * @param     mixed $ordersInProgressRecievedid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgressRecievedid($ordersInProgressRecievedid = null, $comparison = null)
    {
        if (is_array($ordersInProgressRecievedid)) {
            $useMinMax = false;
            if (isset($ordersInProgressRecievedid['min'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $ordersInProgressRecievedid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersInProgressRecievedid['max'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $ordersInProgressRecievedid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID, $ordersInProgressRecievedid, $comparison);
    }

    /**
     * Filter the query on the orders_detailid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrdersDetailid(1234); // WHERE orders_detailid = 1234
     * $query->filterByOrdersDetailid(array(12, 34)); // WHERE orders_detailid IN (12, 34)
     * $query->filterByOrdersDetailid(array('min' => 12)); // WHERE orders_detailid > 12
     * </code>
     *
     * @see       filterByOrdersDetails()
     *
     * @param     mixed $ordersDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailid($ordersDetailid = null, $comparison = null)
    {
        if (is_array($ordersDetailid)) {
            $useMinMax = false;
            if (isset($ordersDetailid['min'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $ordersDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersDetailid['max'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $ordersDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $ordersDetailid, $comparison);
    }

    /**
     * Filter the query on the orders_in_progressid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrdersInProgressid(1234); // WHERE orders_in_progressid = 1234
     * $query->filterByOrdersInProgressid(array(12, 34)); // WHERE orders_in_progressid IN (12, 34)
     * $query->filterByOrdersInProgressid(array('min' => 12)); // WHERE orders_in_progressid > 12
     * </code>
     *
     * @see       filterByOrdersInProgress()
     *
     * @param     mixed $ordersInProgressid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgressid($ordersInProgressid = null, $comparison = null)
    {
        if (is_array($ordersInProgressid)) {
            $useMinMax = false;
            if (isset($ordersInProgressid['min'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersInProgressid['max'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressid, $comparison);
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
     * @see       filterByDistributionsGivingOuts()
     *
     * @param     mixed $distributionsGivingOutid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByDistributionsGivingOutid($distributionsGivingOutid = null, $comparison = null)
    {
        if (is_array($distributionsGivingOutid)) {
            $useMinMax = false;
            if (isset($distributionsGivingOutid['min'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOutid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionsGivingOutid['max'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOutid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOutid, $comparison);
    }

    /**
     * Filter the query on the amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressRecievedTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetails object
     *
     * @param \Model\Ordering\OrdersDetails|ObjectCollection $ordersDetails The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \Model\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $ordersDetails->getOrdersDetailid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID, $ordersDetails->toKeyValue('OrdersDetailid', 'OrdersDetailid'), $comparison);
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
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function joinOrdersDetails($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\Model\Ordering\OrdersDetailsQuery');
    }

    /**
     * Filter the query by a related \Model\OIP\OrdersInProgress object
     *
     * @param \Model\OIP\OrdersInProgress|ObjectCollection $ordersInProgress The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgress($ordersInProgress, $comparison = null)
    {
        if ($ordersInProgress instanceof \Model\OIP\OrdersInProgress) {
            return $this
                ->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgress->getOrdersInProgressid(), $comparison);
        } elseif ($ordersInProgress instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgress->toKeyValue('OrdersInProgressid', 'OrdersInProgressid'), $comparison);
        } else {
            throw new PropelException('filterByOrdersInProgress() only accepts arguments of type \Model\OIP\OrdersInProgress or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersInProgress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function joinOrdersInProgress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersInProgress');

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
            $this->addJoinObject($join, 'OrdersInProgress');
        }

        return $this;
    }

    /**
     * Use the OrdersInProgress relation OrdersInProgress object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OIP\OrdersInProgressQuery A secondary query class using the current class as primary query
     */
    public function useOrdersInProgressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersInProgress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersInProgress', '\Model\OIP\OrdersInProgressQuery');
    }

    /**
     * Filter the query by a related \Model\OIP\DistributionsGivingOuts object
     *
     * @param \Model\OIP\DistributionsGivingOuts|ObjectCollection $distributionsGivingOuts The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByDistributionsGivingOuts($distributionsGivingOuts, $comparison = null)
    {
        if ($distributionsGivingOuts instanceof \Model\OIP\DistributionsGivingOuts) {
            return $this
                ->addUsingAlias(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOuts->getDistributionsGivingOutid(), $comparison);
        } elseif ($distributionsGivingOuts instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID, $distributionsGivingOuts->toKeyValue('PrimaryKey', 'DistributionsGivingOutid'), $comparison);
        } else {
            throw new PropelException('filterByDistributionsGivingOuts() only accepts arguments of type \Model\OIP\DistributionsGivingOuts or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsGivingOuts relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function joinDistributionsGivingOuts($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsGivingOuts');

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
            $this->addJoinObject($join, 'DistributionsGivingOuts');
        }

        return $this;
    }

    /**
     * Use the DistributionsGivingOuts relation DistributionsGivingOuts object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OIP\DistributionsGivingOutsQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsGivingOutsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsGivingOuts($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsGivingOuts', '\Model\OIP\DistributionsGivingOutsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrdersInProgressRecieved $ordersInProgressRecieved Object to remove from the list of results
     *
     * @return $this|ChildOrdersInProgressRecievedQuery The current query, for fluid interface
     */
    public function prune($ordersInProgressRecieved = null)
    {
        if ($ordersInProgressRecieved) {
            $this->addCond('pruneCond0', $this->getAliasedColName(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESS_RECIEVEDID), $ordersInProgressRecieved->getOrdersInProgressRecievedid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(OrdersInProgressRecievedTableMap::COL_ORDERS_DETAILID), $ordersInProgressRecieved->getOrdersDetailid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(OrdersInProgressRecievedTableMap::COL_ORDERS_IN_PROGRESSID), $ordersInProgressRecieved->getOrdersInProgressid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond3', $this->getAliasedColName(OrdersInProgressRecievedTableMap::COL_DISTRIBUTIONS_GIVING_OUTID), $ordersInProgressRecieved->getDistributionsGivingOutid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2', 'pruneCond3'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the orders_in_progress_recieved table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersInProgressRecievedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrdersInProgressRecievedTableMap::clearInstancePool();
            OrdersInProgressRecievedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersInProgressRecievedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrdersInProgressRecievedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrdersInProgressRecievedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrdersInProgressRecievedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrdersInProgressRecievedQuery
