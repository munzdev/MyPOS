<?php

namespace API\Models\ORM\OIP\Base;

use \Exception;
use \PDO;
use API\Models\ORM\OIP\OrderInProgressRecieved as ChildOrderInProgressRecieved;
use API\Models\ORM\OIP\OrderInProgressRecievedQuery as ChildOrderInProgressRecievedQuery;
use API\Models\ORM\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\ORM\Ordering\OrderDetail;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'order_in_progress_recieved' table.
 *
 *
 *
 * @method     ChildOrderInProgressRecievedQuery orderByOrderInProgressRecievedid($order = Criteria::ASC) Order by the order_in_progress_recievedid column
 * @method     ChildOrderInProgressRecievedQuery orderByOrderDetailid($order = Criteria::ASC) Order by the order_detailid column
 * @method     ChildOrderInProgressRecievedQuery orderByOrderInProgressid($order = Criteria::ASC) Order by the order_in_progressid column
 * @method     ChildOrderInProgressRecievedQuery orderByDistributionGivingOutid($order = Criteria::ASC) Order by the distribution_giving_outid column
 * @method     ChildOrderInProgressRecievedQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 *
 * @method     ChildOrderInProgressRecievedQuery groupByOrderInProgressRecievedid() Group by the order_in_progress_recievedid column
 * @method     ChildOrderInProgressRecievedQuery groupByOrderDetailid() Group by the order_detailid column
 * @method     ChildOrderInProgressRecievedQuery groupByOrderInProgressid() Group by the order_in_progressid column
 * @method     ChildOrderInProgressRecievedQuery groupByDistributionGivingOutid() Group by the distribution_giving_outid column
 * @method     ChildOrderInProgressRecievedQuery groupByAmount() Group by the amount column
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrderInProgressRecievedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrderInProgressRecievedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrderInProgressRecievedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrderInProgressRecievedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildOrderInProgressRecievedQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildOrderInProgressRecievedQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildOrderInProgressRecievedQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildOrderInProgressRecievedQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildOrderInProgressRecievedQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinOrderInProgress($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgress relation
 * @method     ChildOrderInProgressRecievedQuery rightJoinOrderInProgress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgress relation
 * @method     ChildOrderInProgressRecievedQuery innerJoinOrderInProgress($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgress relation
 *
 * @method     ChildOrderInProgressRecievedQuery joinWithOrderInProgress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgress relation
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinWithOrderInProgress() Adds a LEFT JOIN clause and with to the query using the OrderInProgress relation
 * @method     ChildOrderInProgressRecievedQuery rightJoinWithOrderInProgress() Adds a RIGHT JOIN clause and with to the query using the OrderInProgress relation
 * @method     ChildOrderInProgressRecievedQuery innerJoinWithOrderInProgress() Adds a INNER JOIN clause and with to the query using the OrderInProgress relation
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinDistributionGivingOut($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionGivingOut relation
 * @method     ChildOrderInProgressRecievedQuery rightJoinDistributionGivingOut($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionGivingOut relation
 * @method     ChildOrderInProgressRecievedQuery innerJoinDistributionGivingOut($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionGivingOut relation
 *
 * @method     ChildOrderInProgressRecievedQuery joinWithDistributionGivingOut($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionGivingOut relation
 *
 * @method     ChildOrderInProgressRecievedQuery leftJoinWithDistributionGivingOut() Adds a LEFT JOIN clause and with to the query using the DistributionGivingOut relation
 * @method     ChildOrderInProgressRecievedQuery rightJoinWithDistributionGivingOut() Adds a RIGHT JOIN clause and with to the query using the DistributionGivingOut relation
 * @method     ChildOrderInProgressRecievedQuery innerJoinWithDistributionGivingOut() Adds a INNER JOIN clause and with to the query using the DistributionGivingOut relation
 *
 * @method     \API\Models\ORM\Ordering\OrderDetailQuery|\API\Models\ORM\OIP\OrderInProgressQuery|\API\Models\ORM\OIP\DistributionGivingOutQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrderInProgressRecieved findOne(ConnectionInterface $con = null) Return the first ChildOrderInProgressRecieved matching the query
 * @method     ChildOrderInProgressRecieved findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrderInProgressRecieved matching the query, or a new ChildOrderInProgressRecieved object populated from the query conditions when no match is found
 *
 * @method     ChildOrderInProgressRecieved findOneByOrderInProgressRecievedid(int $order_in_progress_recievedid) Return the first ChildOrderInProgressRecieved filtered by the order_in_progress_recievedid column
 * @method     ChildOrderInProgressRecieved findOneByOrderDetailid(int $order_detailid) Return the first ChildOrderInProgressRecieved filtered by the order_detailid column
 * @method     ChildOrderInProgressRecieved findOneByOrderInProgressid(int $order_in_progressid) Return the first ChildOrderInProgressRecieved filtered by the order_in_progressid column
 * @method     ChildOrderInProgressRecieved findOneByDistributionGivingOutid(int $distribution_giving_outid) Return the first ChildOrderInProgressRecieved filtered by the distribution_giving_outid column
 * @method     ChildOrderInProgressRecieved findOneByAmount(int $amount) Return the first ChildOrderInProgressRecieved filtered by the amount column *

 * @method     ChildOrderInProgressRecieved requirePk($key, ConnectionInterface $con = null) Return the ChildOrderInProgressRecieved by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgressRecieved requireOne(ConnectionInterface $con = null) Return the first ChildOrderInProgressRecieved matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderInProgressRecieved requireOneByOrderInProgressRecievedid(int $order_in_progress_recievedid) Return the first ChildOrderInProgressRecieved filtered by the order_in_progress_recievedid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgressRecieved requireOneByOrderDetailid(int $order_detailid) Return the first ChildOrderInProgressRecieved filtered by the order_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgressRecieved requireOneByOrderInProgressid(int $order_in_progressid) Return the first ChildOrderInProgressRecieved filtered by the order_in_progressid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgressRecieved requireOneByDistributionGivingOutid(int $distribution_giving_outid) Return the first ChildOrderInProgressRecieved filtered by the distribution_giving_outid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgressRecieved requireOneByAmount(int $amount) Return the first ChildOrderInProgressRecieved filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderInProgressRecieved[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrderInProgressRecieved objects based on current ModelCriteria
 * @method     ChildOrderInProgressRecieved[]|ObjectCollection findByOrderInProgressRecievedid(int $order_in_progress_recievedid) Return ChildOrderInProgressRecieved objects filtered by the order_in_progress_recievedid column
 * @method     ChildOrderInProgressRecieved[]|ObjectCollection findByOrderDetailid(int $order_detailid) Return ChildOrderInProgressRecieved objects filtered by the order_detailid column
 * @method     ChildOrderInProgressRecieved[]|ObjectCollection findByOrderInProgressid(int $order_in_progressid) Return ChildOrderInProgressRecieved objects filtered by the order_in_progressid column
 * @method     ChildOrderInProgressRecieved[]|ObjectCollection findByDistributionGivingOutid(int $distribution_giving_outid) Return ChildOrderInProgressRecieved objects filtered by the distribution_giving_outid column
 * @method     ChildOrderInProgressRecieved[]|ObjectCollection findByAmount(int $amount) Return ChildOrderInProgressRecieved objects filtered by the amount column
 * @method     ChildOrderInProgressRecieved[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrderInProgressRecievedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\OIP\Base\OrderInProgressRecievedQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\OIP\\OrderInProgressRecieved', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderInProgressRecievedQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderInProgressRecievedQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrderInProgressRecievedQuery) {
            return $criteria;
        }
        $query = new ChildOrderInProgressRecievedQuery();
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
     * @return ChildOrderInProgressRecieved|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrderInProgressRecievedTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrderInProgressRecievedTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOrderInProgressRecieved A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT order_in_progress_recievedid, order_detailid, order_in_progressid, distribution_giving_outid, amount FROM order_in_progress_recieved WHERE order_in_progress_recievedid = :p0';
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
            /** @var ChildOrderInProgressRecieved $obj */
            $obj = new ChildOrderInProgressRecieved();
            $obj->hydrate($row);
            OrderInProgressRecievedTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOrderInProgressRecieved|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESS_RECIEVEDID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESS_RECIEVEDID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the order_in_progress_recievedid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderInProgressRecievedid(1234); // WHERE order_in_progress_recievedid = 1234
     * $query->filterByOrderInProgressRecievedid(array(12, 34)); // WHERE order_in_progress_recievedid IN (12, 34)
     * $query->filterByOrderInProgressRecievedid(array('min' => 12)); // WHERE order_in_progress_recievedid > 12
     * </code>
     *
     * @param     mixed $orderInProgressRecievedid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrderInProgressRecievedid($orderInProgressRecievedid = null, $comparison = null)
    {
        if (is_array($orderInProgressRecievedid)) {
            $useMinMax = false;
            if (isset($orderInProgressRecievedid['min'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESS_RECIEVEDID, $orderInProgressRecievedid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderInProgressRecievedid['max'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESS_RECIEVEDID, $orderInProgressRecievedid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESS_RECIEVEDID, $orderInProgressRecievedid, $comparison);
    }

    /**
     * Filter the query on the order_detailid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderDetailid(1234); // WHERE order_detailid = 1234
     * $query->filterByOrderDetailid(array(12, 34)); // WHERE order_detailid IN (12, 34)
     * $query->filterByOrderDetailid(array('min' => 12)); // WHERE order_detailid > 12
     * </code>
     *
     * @see       filterByOrderDetail()
     *
     * @param     mixed $orderDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrderDetailid($orderDetailid = null, $comparison = null)
    {
        if (is_array($orderDetailid)) {
            $useMinMax = false;
            if (isset($orderDetailid['min'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_DETAILID, $orderDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderDetailid['max'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_DETAILID, $orderDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_DETAILID, $orderDetailid, $comparison);
    }

    /**
     * Filter the query on the order_in_progressid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderInProgressid(1234); // WHERE order_in_progressid = 1234
     * $query->filterByOrderInProgressid(array(12, 34)); // WHERE order_in_progressid IN (12, 34)
     * $query->filterByOrderInProgressid(array('min' => 12)); // WHERE order_in_progressid > 12
     * </code>
     *
     * @see       filterByOrderInProgress()
     *
     * @param     mixed $orderInProgressid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrderInProgressid($orderInProgressid = null, $comparison = null)
    {
        if (is_array($orderInProgressid)) {
            $useMinMax = false;
            if (isset($orderInProgressid['min'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderInProgressid['max'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressid, $comparison);
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
     * @see       filterByDistributionGivingOut()
     *
     * @param     mixed $distributionGivingOutid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByDistributionGivingOutid($distributionGivingOutid = null, $comparison = null)
    {
        if (is_array($distributionGivingOutid)) {
            $useMinMax = false;
            if (isset($distributionGivingOutid['min'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOutid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionGivingOutid['max'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOutid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOutid, $comparison);
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
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\OrderDetail object
     *
     * @param \API\Models\ORM\Ordering\OrderDetail|ObjectCollection $orderDetail The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\ORM\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_DETAILID, $orderDetail->getOrderDetailid(), $comparison);
        } elseif ($orderDetail instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_DETAILID, $orderDetail->toKeyValue('PrimaryKey', 'OrderDetailid'), $comparison);
        } else {
            throw new PropelException('filterByOrderDetail() only accepts arguments of type \API\Models\ORM\Ordering\OrderDetail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function joinOrderDetail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return \API\Models\ORM\Ordering\OrderDetailQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderDetail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetail', '\API\Models\ORM\Ordering\OrderDetailQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\OIP\OrderInProgress object
     *
     * @param \API\Models\ORM\OIP\OrderInProgress|ObjectCollection $orderInProgress The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByOrderInProgress($orderInProgress, $comparison = null)
    {
        if ($orderInProgress instanceof \API\Models\ORM\OIP\OrderInProgress) {
            return $this
                ->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgress->getOrderInProgressid(), $comparison);
        } elseif ($orderInProgress instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgress->toKeyValue('PrimaryKey', 'OrderInProgressid'), $comparison);
        } else {
            throw new PropelException('filterByOrderInProgress() only accepts arguments of type \API\Models\ORM\OIP\OrderInProgress or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderInProgress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\OIP\OrderInProgressQuery A secondary query class using the current class as primary query
     */
    public function useOrderInProgressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderInProgress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderInProgress', '\API\Models\ORM\OIP\OrderInProgressQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\OIP\DistributionGivingOut object
     *
     * @param \API\Models\ORM\OIP\DistributionGivingOut|ObjectCollection $distributionGivingOut The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function filterByDistributionGivingOut($distributionGivingOut, $comparison = null)
    {
        if ($distributionGivingOut instanceof \API\Models\ORM\OIP\DistributionGivingOut) {
            return $this
                ->addUsingAlias(OrderInProgressRecievedTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOut->getDistributionGivingOutid(), $comparison);
        } elseif ($distributionGivingOut instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderInProgressRecievedTableMap::COL_DISTRIBUTION_GIVING_OUTID, $distributionGivingOut->toKeyValue('PrimaryKey', 'DistributionGivingOutid'), $comparison);
        } else {
            throw new PropelException('filterByDistributionGivingOut() only accepts arguments of type \API\Models\ORM\OIP\DistributionGivingOut or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionGivingOut relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function joinDistributionGivingOut($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionGivingOut');

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
            $this->addJoinObject($join, 'DistributionGivingOut');
        }

        return $this;
    }

    /**
     * Use the DistributionGivingOut relation DistributionGivingOut object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\OIP\DistributionGivingOutQuery A secondary query class using the current class as primary query
     */
    public function useDistributionGivingOutQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionGivingOut($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionGivingOut', '\API\Models\ORM\OIP\DistributionGivingOutQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrderInProgressRecieved $orderInProgressRecieved Object to remove from the list of results
     *
     * @return $this|ChildOrderInProgressRecievedQuery The current query, for fluid interface
     */
    public function prune($orderInProgressRecieved = null)
    {
        if ($orderInProgressRecieved) {
            $this->addUsingAlias(OrderInProgressRecievedTableMap::COL_ORDER_IN_PROGRESS_RECIEVEDID, $orderInProgressRecieved->getOrderInProgressRecievedid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the order_in_progress_recieved table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressRecievedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrderInProgressRecievedTableMap::clearInstancePool();
            OrderInProgressRecievedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressRecievedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrderInProgressRecievedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrderInProgressRecievedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrderInProgressRecievedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrderInProgressRecievedQuery
