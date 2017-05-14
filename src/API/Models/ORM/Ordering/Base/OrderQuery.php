<?php

namespace API\Models\ORM\Ordering\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Event\EventTable;
use API\Models\ORM\OIP\OrderInProgress;
use API\Models\ORM\Ordering\Order as ChildOrder;
use API\Models\ORM\Ordering\OrderQuery as ChildOrderQuery;
use API\Models\ORM\Ordering\Map\OrderTableMap;
use API\Models\ORM\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'order' table.
 *
 * 
 *
 * @method     ChildOrderQuery orderByOrderid($order = Criteria::ASC) Order by the orderid column
 * @method     ChildOrderQuery orderByEventTableid($order = Criteria::ASC) Order by the event_tableid column
 * @method     ChildOrderQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildOrderQuery orderByOrdertime($order = Criteria::ASC) Order by the ordertime column
 * @method     ChildOrderQuery orderByPriority($order = Criteria::ASC) Order by the priority column
 * @method     ChildOrderQuery orderByDistributionFinished($order = Criteria::ASC) Order by the distribution_finished column
 * @method     ChildOrderQuery orderByInvoiceFinished($order = Criteria::ASC) Order by the invoice_finished column
 * @method     ChildOrderQuery orderByCancellation($order = Criteria::ASC) Order by the cancellation column
 * @method     ChildOrderQuery orderByCancellationCreatedByUserid($order = Criteria::ASC) Order by the cancellation_created_by_userid column
 *
 * @method     ChildOrderQuery groupByOrderid() Group by the orderid column
 * @method     ChildOrderQuery groupByEventTableid() Group by the event_tableid column
 * @method     ChildOrderQuery groupByUserid() Group by the userid column
 * @method     ChildOrderQuery groupByOrdertime() Group by the ordertime column
 * @method     ChildOrderQuery groupByPriority() Group by the priority column
 * @method     ChildOrderQuery groupByDistributionFinished() Group by the distribution_finished column
 * @method     ChildOrderQuery groupByInvoiceFinished() Group by the invoice_finished column
 * @method     ChildOrderQuery groupByCancellation() Group by the cancellation column
 * @method     ChildOrderQuery groupByCancellationCreatedByUserid() Group by the cancellation_created_by_userid column
 *
 * @method     ChildOrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrderQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrderQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrderQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrderQuery leftJoinUserRelatedByCancellationCreatedByUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByCancellationCreatedByUserid relation
 * @method     ChildOrderQuery rightJoinUserRelatedByCancellationCreatedByUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByCancellationCreatedByUserid relation
 * @method     ChildOrderQuery innerJoinUserRelatedByCancellationCreatedByUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByCancellationCreatedByUserid relation
 *
 * @method     ChildOrderQuery joinWithUserRelatedByCancellationCreatedByUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRelatedByCancellationCreatedByUserid relation
 *
 * @method     ChildOrderQuery leftJoinWithUserRelatedByCancellationCreatedByUserid() Adds a LEFT JOIN clause and with to the query using the UserRelatedByCancellationCreatedByUserid relation
 * @method     ChildOrderQuery rightJoinWithUserRelatedByCancellationCreatedByUserid() Adds a RIGHT JOIN clause and with to the query using the UserRelatedByCancellationCreatedByUserid relation
 * @method     ChildOrderQuery innerJoinWithUserRelatedByCancellationCreatedByUserid() Adds a INNER JOIN clause and with to the query using the UserRelatedByCancellationCreatedByUserid relation
 *
 * @method     ChildOrderQuery leftJoinEventTable($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTable relation
 * @method     ChildOrderQuery rightJoinEventTable($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTable relation
 * @method     ChildOrderQuery innerJoinEventTable($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTable relation
 *
 * @method     ChildOrderQuery joinWithEventTable($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventTable relation
 *
 * @method     ChildOrderQuery leftJoinWithEventTable() Adds a LEFT JOIN clause and with to the query using the EventTable relation
 * @method     ChildOrderQuery rightJoinWithEventTable() Adds a RIGHT JOIN clause and with to the query using the EventTable relation
 * @method     ChildOrderQuery innerJoinWithEventTable() Adds a INNER JOIN clause and with to the query using the EventTable relation
 *
 * @method     ChildOrderQuery leftJoinUserRelatedByUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUserid relation
 * @method     ChildOrderQuery rightJoinUserRelatedByUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUserid relation
 * @method     ChildOrderQuery innerJoinUserRelatedByUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUserid relation
 *
 * @method     ChildOrderQuery joinWithUserRelatedByUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRelatedByUserid relation
 *
 * @method     ChildOrderQuery leftJoinWithUserRelatedByUserid() Adds a LEFT JOIN clause and with to the query using the UserRelatedByUserid relation
 * @method     ChildOrderQuery rightJoinWithUserRelatedByUserid() Adds a RIGHT JOIN clause and with to the query using the UserRelatedByUserid relation
 * @method     ChildOrderQuery innerJoinWithUserRelatedByUserid() Adds a INNER JOIN clause and with to the query using the UserRelatedByUserid relation
 *
 * @method     ChildOrderQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildOrderQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildOrderQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildOrderQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildOrderQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildOrderQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildOrderQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     ChildOrderQuery leftJoinOrderInProgress($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgress relation
 * @method     ChildOrderQuery rightJoinOrderInProgress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgress relation
 * @method     ChildOrderQuery innerJoinOrderInProgress($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgress relation
 *
 * @method     ChildOrderQuery joinWithOrderInProgress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgress relation
 *
 * @method     ChildOrderQuery leftJoinWithOrderInProgress() Adds a LEFT JOIN clause and with to the query using the OrderInProgress relation
 * @method     ChildOrderQuery rightJoinWithOrderInProgress() Adds a RIGHT JOIN clause and with to the query using the OrderInProgress relation
 * @method     ChildOrderQuery innerJoinWithOrderInProgress() Adds a INNER JOIN clause and with to the query using the OrderInProgress relation
 *
 * @method     \API\Models\ORM\User\UserQuery|\API\Models\ORM\Event\EventTableQuery|\API\Models\ORM\Ordering\OrderDetailQuery|\API\Models\ORM\OIP\OrderInProgressQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrder findOne(ConnectionInterface $con = null) Return the first ChildOrder matching the query
 * @method     ChildOrder findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrder matching the query, or a new ChildOrder object populated from the query conditions when no match is found
 *
 * @method     ChildOrder findOneByOrderid(int $orderid) Return the first ChildOrder filtered by the orderid column
 * @method     ChildOrder findOneByEventTableid(int $event_tableid) Return the first ChildOrder filtered by the event_tableid column
 * @method     ChildOrder findOneByUserid(int $userid) Return the first ChildOrder filtered by the userid column
 * @method     ChildOrder findOneByOrdertime(string $ordertime) Return the first ChildOrder filtered by the ordertime column
 * @method     ChildOrder findOneByPriority(int $priority) Return the first ChildOrder filtered by the priority column
 * @method     ChildOrder findOneByDistributionFinished(string $distribution_finished) Return the first ChildOrder filtered by the distribution_finished column
 * @method     ChildOrder findOneByInvoiceFinished(string $invoice_finished) Return the first ChildOrder filtered by the invoice_finished column
 * @method     ChildOrder findOneByCancellation(string $cancellation) Return the first ChildOrder filtered by the cancellation column
 * @method     ChildOrder findOneByCancellationCreatedByUserid(int $cancellation_created_by_userid) Return the first ChildOrder filtered by the cancellation_created_by_userid column *

 * @method     ChildOrder requirePk($key, ConnectionInterface $con = null) Return the ChildOrder by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOne(ConnectionInterface $con = null) Return the first ChildOrder matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrder requireOneByOrderid(int $orderid) Return the first ChildOrder filtered by the orderid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByEventTableid(int $event_tableid) Return the first ChildOrder filtered by the event_tableid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUserid(int $userid) Return the first ChildOrder filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByOrdertime(string $ordertime) Return the first ChildOrder filtered by the ordertime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPriority(int $priority) Return the first ChildOrder filtered by the priority column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByDistributionFinished(string $distribution_finished) Return the first ChildOrder filtered by the distribution_finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByInvoiceFinished(string $invoice_finished) Return the first ChildOrder filtered by the invoice_finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCancellation(string $cancellation) Return the first ChildOrder filtered by the cancellation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCancellationCreatedByUserid(int $cancellation_created_by_userid) Return the first ChildOrder filtered by the cancellation_created_by_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrder[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrder objects based on current ModelCriteria
 * @method     ChildOrder[]|ObjectCollection findByOrderid(int $orderid) Return ChildOrder objects filtered by the orderid column
 * @method     ChildOrder[]|ObjectCollection findByEventTableid(int $event_tableid) Return ChildOrder objects filtered by the event_tableid column
 * @method     ChildOrder[]|ObjectCollection findByUserid(int $userid) Return ChildOrder objects filtered by the userid column
 * @method     ChildOrder[]|ObjectCollection findByOrdertime(string $ordertime) Return ChildOrder objects filtered by the ordertime column
 * @method     ChildOrder[]|ObjectCollection findByPriority(int $priority) Return ChildOrder objects filtered by the priority column
 * @method     ChildOrder[]|ObjectCollection findByDistributionFinished(string $distribution_finished) Return ChildOrder objects filtered by the distribution_finished column
 * @method     ChildOrder[]|ObjectCollection findByInvoiceFinished(string $invoice_finished) Return ChildOrder objects filtered by the invoice_finished column
 * @method     ChildOrder[]|ObjectCollection findByCancellation(string $cancellation) Return ChildOrder objects filtered by the cancellation column
 * @method     ChildOrder[]|ObjectCollection findByCancellationCreatedByUserid(int $cancellation_created_by_userid) Return ChildOrder objects filtered by the cancellation_created_by_userid column
 * @method     ChildOrder[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrderQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Ordering\Base\OrderQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Ordering\\Order', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrderQuery) {
            return $criteria;
        }
        $query = new ChildOrderQuery();
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
     * @return ChildOrder|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrderTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrderTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `orderid`, `event_tableid`, `userid`, `ordertime`, `priority`, `distribution_finished`, `invoice_finished`, `cancellation`, `cancellation_created_by_userid` FROM `order` WHERE `orderid` = :p0';
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
            /** @var ChildOrder $obj */
            $obj = new ChildOrder();
            $obj->hydrate($row);
            OrderTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOrder|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OrderTableMap::COL_ORDERID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OrderTableMap::COL_ORDERID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the orderid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderid(1234); // WHERE orderid = 1234
     * $query->filterByOrderid(array(12, 34)); // WHERE orderid IN (12, 34)
     * $query->filterByOrderid(array('min' => 12)); // WHERE orderid > 12
     * </code>
     *
     * @param     mixed $orderid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByOrderid($orderid = null, $comparison = null)
    {
        if (is_array($orderid)) {
            $useMinMax = false;
            if (isset($orderid['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDERID, $orderid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderid['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDERID, $orderid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDERID, $orderid, $comparison);
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByEventTableid($eventTableid = null, $comparison = null)
    {
        if (is_array($eventTableid)) {
            $useMinMax = false;
            if (isset($eventTableid['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_EVENT_TABLEID, $eventTableid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventTableid['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_EVENT_TABLEID, $eventTableid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_EVENT_TABLEID, $eventTableid, $comparison);
    }

    /**
     * Filter the query on the userid column
     *
     * Example usage:
     * <code>
     * $query->filterByUserid(1234); // WHERE userid = 1234
     * $query->filterByUserid(array(12, 34)); // WHERE userid IN (12, 34)
     * $query->filterByUserid(array('min' => 12)); // WHERE userid > 12
     * </code>
     *
     * @see       filterByUserRelatedByUserid()
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the ordertime column
     *
     * Example usage:
     * <code>
     * $query->filterByOrdertime('2011-03-14'); // WHERE ordertime = '2011-03-14'
     * $query->filterByOrdertime('now'); // WHERE ordertime = '2011-03-14'
     * $query->filterByOrdertime(array('max' => 'yesterday')); // WHERE ordertime > '2011-03-13'
     * </code>
     *
     * @param     mixed $ordertime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByOrdertime($ordertime = null, $comparison = null)
    {
        if (is_array($ordertime)) {
            $useMinMax = false;
            if (isset($ordertime['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDERTIME, $ordertime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordertime['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDERTIME, $ordertime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDERTIME, $ordertime, $comparison);
    }

    /**
     * Filter the query on the priority column
     *
     * Example usage:
     * <code>
     * $query->filterByPriority(1234); // WHERE priority = 1234
     * $query->filterByPriority(array(12, 34)); // WHERE priority IN (12, 34)
     * $query->filterByPriority(array('min' => 12)); // WHERE priority > 12
     * </code>
     *
     * @param     mixed $priority The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPriority($priority = null, $comparison = null)
    {
        if (is_array($priority)) {
            $useMinMax = false;
            if (isset($priority['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_PRIORITY, $priority['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($priority['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_PRIORITY, $priority['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_PRIORITY, $priority, $comparison);
    }

    /**
     * Filter the query on the distribution_finished column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionFinished('2011-03-14'); // WHERE distribution_finished = '2011-03-14'
     * $query->filterByDistributionFinished('now'); // WHERE distribution_finished = '2011-03-14'
     * $query->filterByDistributionFinished(array('max' => 'yesterday')); // WHERE distribution_finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $distributionFinished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByDistributionFinished($distributionFinished = null, $comparison = null)
    {
        if (is_array($distributionFinished)) {
            $useMinMax = false;
            if (isset($distributionFinished['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_DISTRIBUTION_FINISHED, $distributionFinished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionFinished['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_DISTRIBUTION_FINISHED, $distributionFinished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_DISTRIBUTION_FINISHED, $distributionFinished, $comparison);
    }

    /**
     * Filter the query on the invoice_finished column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceFinished('2011-03-14'); // WHERE invoice_finished = '2011-03-14'
     * $query->filterByInvoiceFinished('now'); // WHERE invoice_finished = '2011-03-14'
     * $query->filterByInvoiceFinished(array('max' => 'yesterday')); // WHERE invoice_finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $invoiceFinished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByInvoiceFinished($invoiceFinished = null, $comparison = null)
    {
        if (is_array($invoiceFinished)) {
            $useMinMax = false;
            if (isset($invoiceFinished['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_INVOICE_FINISHED, $invoiceFinished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceFinished['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_INVOICE_FINISHED, $invoiceFinished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_INVOICE_FINISHED, $invoiceFinished, $comparison);
    }

    /**
     * Filter the query on the cancellation column
     *
     * Example usage:
     * <code>
     * $query->filterByCancellation('2011-03-14'); // WHERE cancellation = '2011-03-14'
     * $query->filterByCancellation('now'); // WHERE cancellation = '2011-03-14'
     * $query->filterByCancellation(array('max' => 'yesterday')); // WHERE cancellation > '2011-03-13'
     * </code>
     *
     * @param     mixed $cancellation The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCancellation($cancellation = null, $comparison = null)
    {
        if (is_array($cancellation)) {
            $useMinMax = false;
            if (isset($cancellation['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_CANCELLATION, $cancellation['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cancellation['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_CANCELLATION, $cancellation['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_CANCELLATION, $cancellation, $comparison);
    }

    /**
     * Filter the query on the cancellation_created_by_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByCancellationCreatedByUserid(1234); // WHERE cancellation_created_by_userid = 1234
     * $query->filterByCancellationCreatedByUserid(array(12, 34)); // WHERE cancellation_created_by_userid IN (12, 34)
     * $query->filterByCancellationCreatedByUserid(array('min' => 12)); // WHERE cancellation_created_by_userid > 12
     * </code>
     *
     * @see       filterByUserRelatedByCancellationCreatedByUserid()
     *
     * @param     mixed $cancellationCreatedByUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCancellationCreatedByUserid($cancellationCreatedByUserid = null, $comparison = null)
    {
        if (is_array($cancellationCreatedByUserid)) {
            $useMinMax = false;
            if (isset($cancellationCreatedByUserid['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID, $cancellationCreatedByUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cancellationCreatedByUserid['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID, $cancellationCreatedByUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID, $cancellationCreatedByUserid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\User\User object
     *
     * @param \API\Models\ORM\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByCancellationCreatedByUserid($user, $comparison = null)
    {
        if ($user instanceof \API\Models\ORM\User\User) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderTableMap::COL_CANCELLATION_CREATED_BY_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByCancellationCreatedByUserid() only accepts arguments of type \API\Models\ORM\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByCancellationCreatedByUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function joinUserRelatedByCancellationCreatedByUserid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByCancellationCreatedByUserid');

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
            $this->addJoinObject($join, 'UserRelatedByCancellationCreatedByUserid');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByCancellationCreatedByUserid relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByCancellationCreatedByUseridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByCancellationCreatedByUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByCancellationCreatedByUserid', '\API\Models\ORM\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Event\EventTable object
     *
     * @param \API\Models\ORM\Event\EventTable|ObjectCollection $eventTable The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderQuery The current query, for fluid interface
     */
    public function filterByEventTable($eventTable, $comparison = null)
    {
        if ($eventTable instanceof \API\Models\ORM\Event\EventTable) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_EVENT_TABLEID, $eventTable->getEventTableid(), $comparison);
        } elseif ($eventTable instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderTableMap::COL_EVENT_TABLEID, $eventTable->toKeyValue('PrimaryKey', 'EventTableid'), $comparison);
        } else {
            throw new PropelException('filterByEventTable() only accepts arguments of type \API\Models\ORM\Event\EventTable or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventTable relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Event\EventTableQuery A secondary query class using the current class as primary query
     */
    public function useEventTableQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventTable($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventTable', '\API\Models\ORM\Event\EventTableQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\User\User object
     *
     * @param \API\Models\ORM\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByUserid($user, $comparison = null)
    {
        if ($user instanceof \API\Models\ORM\User\User) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByUserid() only accepts arguments of type \API\Models\ORM\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function joinUserRelatedByUserid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByUserid');

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
            $this->addJoinObject($join, 'UserRelatedByUserid');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByUserid relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByUseridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelatedByUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUserid', '\API\Models\ORM\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\OrderDetail object
     *
     * @param \API\Models\ORM\Ordering\OrderDetail|ObjectCollection $orderDetail the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\ORM\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_ORDERID, $orderDetail->getOrderid(), $comparison);
        } elseif ($orderDetail instanceof ObjectCollection) {
            return $this
                ->useOrderDetailQuery()
                ->filterByPrimaryKeys($orderDetail->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
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
     * @param \API\Models\ORM\OIP\OrderInProgress|ObjectCollection $orderInProgress the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderQuery The current query, for fluid interface
     */
    public function filterByOrderInProgress($orderInProgress, $comparison = null)
    {
        if ($orderInProgress instanceof \API\Models\ORM\OIP\OrderInProgress) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_ORDERID, $orderInProgress->getOrderid(), $comparison);
        } elseif ($orderInProgress instanceof ObjectCollection) {
            return $this
                ->useOrderInProgressQuery()
                ->filterByPrimaryKeys($orderInProgress->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildOrder $order Object to remove from the list of results
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function prune($order = null)
    {
        if ($order) {
            $this->addUsingAlias(OrderTableMap::COL_ORDERID, $order->getOrderid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the order table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrderTableMap::clearInstancePool();
            OrderTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrderTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            OrderTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            OrderTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrderQuery
