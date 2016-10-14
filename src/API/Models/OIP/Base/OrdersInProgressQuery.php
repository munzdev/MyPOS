<?php

namespace API\Models\OIP\Base;

use \Exception;
use \PDO;
use API\Models\Menues\MenuGroupes;
use API\Models\OIP\OrdersInProgress as ChildOrdersInProgress;
use API\Models\OIP\OrdersInProgressQuery as ChildOrdersInProgressQuery;
use API\Models\OIP\Map\OrdersInProgressTableMap;
use API\Models\Ordering\Orders;
use API\Models\User\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'orders_in_progress' table.
 *
 *
 *
 * @method     ChildOrdersInProgressQuery orderByOrdersInProgressid($order = Criteria::ASC) Order by the orders_in_progressid column
 * @method     ChildOrdersInProgressQuery orderByOrderid($order = Criteria::ASC) Order by the orderid column
 * @method     ChildOrdersInProgressQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildOrdersInProgressQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildOrdersInProgressQuery orderByBegin($order = Criteria::ASC) Order by the begin column
 * @method     ChildOrdersInProgressQuery orderByDone($order = Criteria::ASC) Order by the done column
 *
 * @method     ChildOrdersInProgressQuery groupByOrdersInProgressid() Group by the orders_in_progressid column
 * @method     ChildOrdersInProgressQuery groupByOrderid() Group by the orderid column
 * @method     ChildOrdersInProgressQuery groupByUserid() Group by the userid column
 * @method     ChildOrdersInProgressQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildOrdersInProgressQuery groupByBegin() Group by the begin column
 * @method     ChildOrdersInProgressQuery groupByDone() Group by the done column
 *
 * @method     ChildOrdersInProgressQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrdersInProgressQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrdersInProgressQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrdersInProgressQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrdersInProgressQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrdersInProgressQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrdersInProgressQuery leftJoinMenuGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildOrdersInProgressQuery rightJoinMenuGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildOrdersInProgressQuery innerJoinMenuGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroupes relation
 *
 * @method     ChildOrdersInProgressQuery joinWithMenuGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinWithMenuGroupes() Adds a LEFT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildOrdersInProgressQuery rightJoinWithMenuGroupes() Adds a RIGHT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildOrdersInProgressQuery innerJoinWithMenuGroupes() Adds a INNER JOIN clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinOrders($relationAlias = null) Adds a LEFT JOIN clause to the query using the Orders relation
 * @method     ChildOrdersInProgressQuery rightJoinOrders($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Orders relation
 * @method     ChildOrdersInProgressQuery innerJoinOrders($relationAlias = null) Adds a INNER JOIN clause to the query using the Orders relation
 *
 * @method     ChildOrdersInProgressQuery joinWithOrders($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Orders relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinWithOrders() Adds a LEFT JOIN clause and with to the query using the Orders relation
 * @method     ChildOrdersInProgressQuery rightJoinWithOrders() Adds a RIGHT JOIN clause and with to the query using the Orders relation
 * @method     ChildOrdersInProgressQuery innerJoinWithOrders() Adds a INNER JOIN clause and with to the query using the Orders relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildOrdersInProgressQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildOrdersInProgressQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildOrdersInProgressQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildOrdersInProgressQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildOrdersInProgressQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinOrdersInProgressRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersInProgressQuery rightJoinOrdersInProgressRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersInProgressQuery innerJoinOrdersInProgressRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersInProgressRecieved relation
 *
 * @method     ChildOrdersInProgressQuery joinWithOrdersInProgressRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersInProgressRecieved relation
 *
 * @method     ChildOrdersInProgressQuery leftJoinWithOrdersInProgressRecieved() Adds a LEFT JOIN clause and with to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersInProgressQuery rightJoinWithOrdersInProgressRecieved() Adds a RIGHT JOIN clause and with to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersInProgressQuery innerJoinWithOrdersInProgressRecieved() Adds a INNER JOIN clause and with to the query using the OrdersInProgressRecieved relation
 *
 * @method     \API\Models\Menues\MenuGroupesQuery|\API\Models\Ordering\OrdersQuery|\API\Models\User\UsersQuery|\API\Models\OIP\OrdersInProgressRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrdersInProgress findOne(ConnectionInterface $con = null) Return the first ChildOrdersInProgress matching the query
 * @method     ChildOrdersInProgress findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrdersInProgress matching the query, or a new ChildOrdersInProgress object populated from the query conditions when no match is found
 *
 * @method     ChildOrdersInProgress findOneByOrdersInProgressid(int $orders_in_progressid) Return the first ChildOrdersInProgress filtered by the orders_in_progressid column
 * @method     ChildOrdersInProgress findOneByOrderid(int $orderid) Return the first ChildOrdersInProgress filtered by the orderid column
 * @method     ChildOrdersInProgress findOneByUserid(int $userid) Return the first ChildOrdersInProgress filtered by the userid column
 * @method     ChildOrdersInProgress findOneByMenuGroupid(int $menu_groupid) Return the first ChildOrdersInProgress filtered by the menu_groupid column
 * @method     ChildOrdersInProgress findOneByBegin(string $begin) Return the first ChildOrdersInProgress filtered by the begin column
 * @method     ChildOrdersInProgress findOneByDone(string $done) Return the first ChildOrdersInProgress filtered by the done column *

 * @method     ChildOrdersInProgress requirePk($key, ConnectionInterface $con = null) Return the ChildOrdersInProgress by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgress requireOne(ConnectionInterface $con = null) Return the first ChildOrdersInProgress matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersInProgress requireOneByOrdersInProgressid(int $orders_in_progressid) Return the first ChildOrdersInProgress filtered by the orders_in_progressid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgress requireOneByOrderid(int $orderid) Return the first ChildOrdersInProgress filtered by the orderid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgress requireOneByUserid(int $userid) Return the first ChildOrdersInProgress filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgress requireOneByMenuGroupid(int $menu_groupid) Return the first ChildOrdersInProgress filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgress requireOneByBegin(string $begin) Return the first ChildOrdersInProgress filtered by the begin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersInProgress requireOneByDone(string $done) Return the first ChildOrdersInProgress filtered by the done column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersInProgress[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrdersInProgress objects based on current ModelCriteria
 * @method     ChildOrdersInProgress[]|ObjectCollection findByOrdersInProgressid(int $orders_in_progressid) Return ChildOrdersInProgress objects filtered by the orders_in_progressid column
 * @method     ChildOrdersInProgress[]|ObjectCollection findByOrderid(int $orderid) Return ChildOrdersInProgress objects filtered by the orderid column
 * @method     ChildOrdersInProgress[]|ObjectCollection findByUserid(int $userid) Return ChildOrdersInProgress objects filtered by the userid column
 * @method     ChildOrdersInProgress[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildOrdersInProgress objects filtered by the menu_groupid column
 * @method     ChildOrdersInProgress[]|ObjectCollection findByBegin(string $begin) Return ChildOrdersInProgress objects filtered by the begin column
 * @method     ChildOrdersInProgress[]|ObjectCollection findByDone(string $done) Return ChildOrdersInProgress objects filtered by the done column
 * @method     ChildOrdersInProgress[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrdersInProgressQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\OIP\Base\OrdersInProgressQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\OIP\\OrdersInProgress', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrdersInProgressQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrdersInProgressQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrdersInProgressQuery) {
            return $criteria;
        }
        $query = new ChildOrdersInProgressQuery();
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
     * @param array[$orders_in_progressid, $orderid, $userid, $menu_groupid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildOrdersInProgress|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrdersInProgressTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrdersInProgressTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2]), (null === $key[3] || is_scalar($key[3]) || is_callable([$key[3], '__toString']) ? (string) $key[3] : $key[3])]))))) {
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
     * @return ChildOrdersInProgress A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT orders_in_progressid, orderid, userid, menu_groupid, begin, done FROM orders_in_progress WHERE orders_in_progressid = :p0 AND orderid = :p1 AND userid = :p2 AND menu_groupid = :p3';
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
            /** @var ChildOrdersInProgress $obj */
            $obj = new ChildOrdersInProgress();
            $obj->hydrate($row);
            OrdersInProgressTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2]), (null === $key[3] || is_scalar($key[3]) || is_callable([$key[3], '__toString']) ? (string) $key[3] : $key[3])]));
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
     * @return ChildOrdersInProgress|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(OrdersInProgressTableMap::COL_USERID, $key[2], Criteria::EQUAL);
        $this->addUsingAlias(OrdersInProgressTableMap::COL_MENU_GROUPID, $key[3], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(OrdersInProgressTableMap::COL_ORDERID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(OrdersInProgressTableMap::COL_USERID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $cton3 = $this->getNewCriterion(OrdersInProgressTableMap::COL_MENU_GROUPID, $key[3], Criteria::EQUAL);
            $cton0->addAnd($cton3);
            $this->addOr($cton0);
        }

        return $this;
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
     * @param     mixed $ordersInProgressid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgressid($ordersInProgressid = null, $comparison = null)
    {
        if (is_array($ordersInProgressid)) {
            $useMinMax = false;
            if (isset($ordersInProgressid['min'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersInProgressid['max'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressid, $comparison);
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
     * @see       filterByOrders()
     *
     * @param     mixed $orderid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByOrderid($orderid = null, $comparison = null)
    {
        if (is_array($orderid)) {
            $useMinMax = false;
            if (isset($orderid['min'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERID, $orderid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderid['max'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERID, $orderid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressTableMap::COL_ORDERID, $orderid, $comparison);
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
     * @see       filterByUsers()
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressTableMap::COL_USERID, $userid, $comparison);
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
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query on the begin column
     *
     * Example usage:
     * <code>
     * $query->filterByBegin('2011-03-14'); // WHERE begin = '2011-03-14'
     * $query->filterByBegin('now'); // WHERE begin = '2011-03-14'
     * $query->filterByBegin(array('max' => 'yesterday')); // WHERE begin > '2011-03-13'
     * </code>
     *
     * @param     mixed $begin The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByBegin($begin = null, $comparison = null)
    {
        if (is_array($begin)) {
            $useMinMax = false;
            if (isset($begin['min'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_BEGIN, $begin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($begin['max'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_BEGIN, $begin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressTableMap::COL_BEGIN, $begin, $comparison);
    }

    /**
     * Filter the query on the done column
     *
     * Example usage:
     * <code>
     * $query->filterByDone('2011-03-14'); // WHERE done = '2011-03-14'
     * $query->filterByDone('now'); // WHERE done = '2011-03-14'
     * $query->filterByDone(array('max' => 'yesterday')); // WHERE done > '2011-03-13'
     * </code>
     *
     * @param     mixed $done The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByDone($done = null, $comparison = null)
    {
        if (is_array($done)) {
            $useMinMax = false;
            if (isset($done['min'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_DONE, $done['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($done['max'])) {
                $this->addUsingAlias(OrdersInProgressTableMap::COL_DONE, $done['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersInProgressTableMap::COL_DONE, $done, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menues\MenuGroupes object
     *
     * @param \API\Models\Menues\MenuGroupes|ObjectCollection $menuGroupes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByMenuGroupes($menuGroupes, $comparison = null)
    {
        if ($menuGroupes instanceof \API\Models\Menues\MenuGroupes) {
            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_MENU_GROUPID, $menuGroupes->getMenuGroupid(), $comparison);
        } elseif ($menuGroupes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_MENU_GROUPID, $menuGroupes->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroupes() only accepts arguments of type \API\Models\Menues\MenuGroupes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroupes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
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
     * @return \API\Models\Menues\MenuGroupesQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroupes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroupes', '\API\Models\Menues\MenuGroupesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\Orders object
     *
     * @param \API\Models\Ordering\Orders|ObjectCollection $orders The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByOrders($orders, $comparison = null)
    {
        if ($orders instanceof \API\Models\Ordering\Orders) {
            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_ORDERID, $orders->getOrderid(), $comparison);
        } elseif ($orders instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_ORDERID, $orders->toKeyValue('Orderid', 'Orderid'), $comparison);
        } else {
            throw new PropelException('filterByOrders() only accepts arguments of type \API\Models\Ordering\Orders or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Orders relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function joinOrders($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Orders');

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
            $this->addJoinObject($join, 'Orders');
        }

        return $this;
    }

    /**
     * Use the Orders relation Orders object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrdersQuery A secondary query class using the current class as primary query
     */
    public function useOrdersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrders($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Orders', '\API\Models\Ordering\OrdersQuery');
    }

    /**
     * Filter the query by a related \API\Models\User\Users object
     *
     * @param \API\Models\User\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \API\Models\User\Users) {
            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_USERID, $users->getUserid(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_USERID, $users->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \API\Models\User\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function joinUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Users');

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
            $this->addJoinObject($join, 'Users');
        }

        return $this;
    }

    /**
     * Use the Users relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\User\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\API\Models\User\UsersQuery');
    }

    /**
     * Filter the query by a related \API\Models\OIP\OrdersInProgressRecieved object
     *
     * @param \API\Models\OIP\OrdersInProgressRecieved|ObjectCollection $ordersInProgressRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgressRecieved($ordersInProgressRecieved, $comparison = null)
    {
        if ($ordersInProgressRecieved instanceof \API\Models\OIP\OrdersInProgressRecieved) {
            return $this
                ->addUsingAlias(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID, $ordersInProgressRecieved->getOrdersInProgressid(), $comparison);
        } elseif ($ordersInProgressRecieved instanceof ObjectCollection) {
            return $this
                ->useOrdersInProgressRecievedQuery()
                ->filterByPrimaryKeys($ordersInProgressRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersInProgressRecieved() only accepts arguments of type \API\Models\OIP\OrdersInProgressRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersInProgressRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
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
     * @return \API\Models\OIP\OrdersInProgressRecievedQuery A secondary query class using the current class as primary query
     */
    public function useOrdersInProgressRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersInProgressRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersInProgressRecieved', '\API\Models\OIP\OrdersInProgressRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrdersInProgress $ordersInProgress Object to remove from the list of results
     *
     * @return $this|ChildOrdersInProgressQuery The current query, for fluid interface
     */
    public function prune($ordersInProgress = null)
    {
        if ($ordersInProgress) {
            $this->addCond('pruneCond0', $this->getAliasedColName(OrdersInProgressTableMap::COL_ORDERS_IN_PROGRESSID), $ordersInProgress->getOrdersInProgressid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(OrdersInProgressTableMap::COL_ORDERID), $ordersInProgress->getOrderid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(OrdersInProgressTableMap::COL_USERID), $ordersInProgress->getUserid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond3', $this->getAliasedColName(OrdersInProgressTableMap::COL_MENU_GROUPID), $ordersInProgress->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2', 'pruneCond3'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the orders_in_progress table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersInProgressTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrdersInProgressTableMap::clearInstancePool();
            OrdersInProgressTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersInProgressTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrdersInProgressTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrdersInProgressTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrdersInProgressTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrdersInProgressQuery
