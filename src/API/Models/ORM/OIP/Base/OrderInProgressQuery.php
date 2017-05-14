<?php

namespace API\Models\ORM\OIP\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Menu\MenuGroup;
use API\Models\ORM\OIP\OrderInProgress as ChildOrderInProgress;
use API\Models\ORM\OIP\OrderInProgressQuery as ChildOrderInProgressQuery;
use API\Models\ORM\OIP\Map\OrderInProgressTableMap;
use API\Models\ORM\Ordering\Order;
use API\Models\ORM\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'order_in_progress' table.
 *
 * 
 *
 * @method     ChildOrderInProgressQuery orderByOrderInProgressid($order = Criteria::ASC) Order by the order_in_progressid column
 * @method     ChildOrderInProgressQuery orderByOrderid($order = Criteria::ASC) Order by the orderid column
 * @method     ChildOrderInProgressQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildOrderInProgressQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildOrderInProgressQuery orderByBegin($order = Criteria::ASC) Order by the begin column
 * @method     ChildOrderInProgressQuery orderByDone($order = Criteria::ASC) Order by the done column
 *
 * @method     ChildOrderInProgressQuery groupByOrderInProgressid() Group by the order_in_progressid column
 * @method     ChildOrderInProgressQuery groupByOrderid() Group by the orderid column
 * @method     ChildOrderInProgressQuery groupByUserid() Group by the userid column
 * @method     ChildOrderInProgressQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildOrderInProgressQuery groupByBegin() Group by the begin column
 * @method     ChildOrderInProgressQuery groupByDone() Group by the done column
 *
 * @method     ChildOrderInProgressQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrderInProgressQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrderInProgressQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrderInProgressQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrderInProgressQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrderInProgressQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrderInProgressQuery leftJoinMenuGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroup relation
 * @method     ChildOrderInProgressQuery rightJoinMenuGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroup relation
 * @method     ChildOrderInProgressQuery innerJoinMenuGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroup relation
 *
 * @method     ChildOrderInProgressQuery joinWithMenuGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroup relation
 *
 * @method     ChildOrderInProgressQuery leftJoinWithMenuGroup() Adds a LEFT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildOrderInProgressQuery rightJoinWithMenuGroup() Adds a RIGHT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildOrderInProgressQuery innerJoinWithMenuGroup() Adds a INNER JOIN clause and with to the query using the MenuGroup relation
 *
 * @method     ChildOrderInProgressQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildOrderInProgressQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildOrderInProgressQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildOrderInProgressQuery joinWithOrder($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Order relation
 *
 * @method     ChildOrderInProgressQuery leftJoinWithOrder() Adds a LEFT JOIN clause and with to the query using the Order relation
 * @method     ChildOrderInProgressQuery rightJoinWithOrder() Adds a RIGHT JOIN clause and with to the query using the Order relation
 * @method     ChildOrderInProgressQuery innerJoinWithOrder() Adds a INNER JOIN clause and with to the query using the Order relation
 *
 * @method     ChildOrderInProgressQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildOrderInProgressQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildOrderInProgressQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildOrderInProgressQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildOrderInProgressQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildOrderInProgressQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildOrderInProgressQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildOrderInProgressQuery leftJoinOrderInProgressRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderInProgressQuery rightJoinOrderInProgressRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderInProgressQuery innerJoinOrderInProgressRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgressRecieved relation
 *
 * @method     ChildOrderInProgressQuery joinWithOrderInProgressRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgressRecieved relation
 *
 * @method     ChildOrderInProgressQuery leftJoinWithOrderInProgressRecieved() Adds a LEFT JOIN clause and with to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderInProgressQuery rightJoinWithOrderInProgressRecieved() Adds a RIGHT JOIN clause and with to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderInProgressQuery innerJoinWithOrderInProgressRecieved() Adds a INNER JOIN clause and with to the query using the OrderInProgressRecieved relation
 *
 * @method     \API\Models\ORM\Menu\MenuGroupQuery|\API\Models\ORM\Ordering\OrderQuery|\API\Models\ORM\User\UserQuery|\API\Models\ORM\OIP\OrderInProgressRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrderInProgress findOne(ConnectionInterface $con = null) Return the first ChildOrderInProgress matching the query
 * @method     ChildOrderInProgress findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrderInProgress matching the query, or a new ChildOrderInProgress object populated from the query conditions when no match is found
 *
 * @method     ChildOrderInProgress findOneByOrderInProgressid(int $order_in_progressid) Return the first ChildOrderInProgress filtered by the order_in_progressid column
 * @method     ChildOrderInProgress findOneByOrderid(int $orderid) Return the first ChildOrderInProgress filtered by the orderid column
 * @method     ChildOrderInProgress findOneByUserid(int $userid) Return the first ChildOrderInProgress filtered by the userid column
 * @method     ChildOrderInProgress findOneByMenuGroupid(int $menu_groupid) Return the first ChildOrderInProgress filtered by the menu_groupid column
 * @method     ChildOrderInProgress findOneByBegin(string $begin) Return the first ChildOrderInProgress filtered by the begin column
 * @method     ChildOrderInProgress findOneByDone(string $done) Return the first ChildOrderInProgress filtered by the done column *

 * @method     ChildOrderInProgress requirePk($key, ConnectionInterface $con = null) Return the ChildOrderInProgress by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgress requireOne(ConnectionInterface $con = null) Return the first ChildOrderInProgress matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderInProgress requireOneByOrderInProgressid(int $order_in_progressid) Return the first ChildOrderInProgress filtered by the order_in_progressid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgress requireOneByOrderid(int $orderid) Return the first ChildOrderInProgress filtered by the orderid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgress requireOneByUserid(int $userid) Return the first ChildOrderInProgress filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgress requireOneByMenuGroupid(int $menu_groupid) Return the first ChildOrderInProgress filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgress requireOneByBegin(string $begin) Return the first ChildOrderInProgress filtered by the begin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderInProgress requireOneByDone(string $done) Return the first ChildOrderInProgress filtered by the done column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderInProgress[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrderInProgress objects based on current ModelCriteria
 * @method     ChildOrderInProgress[]|ObjectCollection findByOrderInProgressid(int $order_in_progressid) Return ChildOrderInProgress objects filtered by the order_in_progressid column
 * @method     ChildOrderInProgress[]|ObjectCollection findByOrderid(int $orderid) Return ChildOrderInProgress objects filtered by the orderid column
 * @method     ChildOrderInProgress[]|ObjectCollection findByUserid(int $userid) Return ChildOrderInProgress objects filtered by the userid column
 * @method     ChildOrderInProgress[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildOrderInProgress objects filtered by the menu_groupid column
 * @method     ChildOrderInProgress[]|ObjectCollection findByBegin(string $begin) Return ChildOrderInProgress objects filtered by the begin column
 * @method     ChildOrderInProgress[]|ObjectCollection findByDone(string $done) Return ChildOrderInProgress objects filtered by the done column
 * @method     ChildOrderInProgress[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrderInProgressQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\OIP\Base\OrderInProgressQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\OIP\\OrderInProgress', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderInProgressQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderInProgressQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrderInProgressQuery) {
            return $criteria;
        }
        $query = new ChildOrderInProgressQuery();
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
     * @return ChildOrderInProgress|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrderInProgressTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOrderInProgress A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT order_in_progressid, orderid, userid, menu_groupid, begin, done FROM order_in_progress WHERE order_in_progressid = :p0';
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
            /** @var ChildOrderInProgress $obj */
            $obj = new ChildOrderInProgress();
            $obj->hydrate($row);
            OrderInProgressTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOrderInProgress|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $keys, Criteria::IN);
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
     * @param     mixed $orderInProgressid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByOrderInProgressid($orderInProgressid = null, $comparison = null)
    {
        if (is_array($orderInProgressid)) {
            $useMinMax = false;
            if (isset($orderInProgressid['min'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderInProgressid['max'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressid, $comparison);
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
     * @see       filterByOrder()
     *
     * @param     mixed $orderid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByOrderid($orderid = null, $comparison = null)
    {
        if (is_array($orderid)) {
            $useMinMax = false;
            if (isset($orderid['min'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_ORDERID, $orderid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderid['max'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_ORDERID, $orderid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressTableMap::COL_ORDERID, $orderid, $comparison);
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
     * @see       filterByUser()
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressTableMap::COL_USERID, $userid, $comparison);
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
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
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
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByBegin($begin = null, $comparison = null)
    {
        if (is_array($begin)) {
            $useMinMax = false;
            if (isset($begin['min'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_BEGIN, $begin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($begin['max'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_BEGIN, $begin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressTableMap::COL_BEGIN, $begin, $comparison);
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
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByDone($done = null, $comparison = null)
    {
        if (is_array($done)) {
            $useMinMax = false;
            if (isset($done['min'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_DONE, $done['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($done['max'])) {
                $this->addUsingAlias(OrderInProgressTableMap::COL_DONE, $done['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderInProgressTableMap::COL_DONE, $done, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\MenuGroup object
     *
     * @param \API\Models\ORM\Menu\MenuGroup|ObjectCollection $menuGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByMenuGroup($menuGroup, $comparison = null)
    {
        if ($menuGroup instanceof \API\Models\ORM\Menu\MenuGroup) {
            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_MENU_GROUPID, $menuGroup->getMenuGroupid(), $comparison);
        } elseif ($menuGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_MENU_GROUPID, $menuGroup->toKeyValue('PrimaryKey', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroup() only accepts arguments of type \API\Models\ORM\Menu\MenuGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Menu\MenuGroupQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroup', '\API\Models\ORM\Menu\MenuGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\Order object
     *
     * @param \API\Models\ORM\Ordering\Order|ObjectCollection $order The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByOrder($order, $comparison = null)
    {
        if ($order instanceof \API\Models\ORM\Ordering\Order) {
            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_ORDERID, $order->getOrderid(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_ORDERID, $order->toKeyValue('PrimaryKey', 'Orderid'), $comparison);
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \API\Models\ORM\Ordering\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function joinOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Ordering\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\API\Models\ORM\Ordering\OrderQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\User\User object
     *
     * @param \API\Models\ORM\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\ORM\User\User) {
            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\ORM\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\ORM\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\OIP\OrderInProgressRecieved object
     *
     * @param \API\Models\ORM\OIP\OrderInProgressRecieved|ObjectCollection $orderInProgressRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function filterByOrderInProgressRecieved($orderInProgressRecieved, $comparison = null)
    {
        if ($orderInProgressRecieved instanceof \API\Models\ORM\OIP\OrderInProgressRecieved) {
            return $this
                ->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgressRecieved->getOrderInProgressid(), $comparison);
        } elseif ($orderInProgressRecieved instanceof ObjectCollection) {
            return $this
                ->useOrderInProgressRecievedQuery()
                ->filterByPrimaryKeys($orderInProgressRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderInProgressRecieved() only accepts arguments of type \API\Models\ORM\OIP\OrderInProgressRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderInProgressRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\OIP\OrderInProgressRecievedQuery A secondary query class using the current class as primary query
     */
    public function useOrderInProgressRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderInProgressRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderInProgressRecieved', '\API\Models\ORM\OIP\OrderInProgressRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrderInProgress $orderInProgress Object to remove from the list of results
     *
     * @return $this|ChildOrderInProgressQuery The current query, for fluid interface
     */
    public function prune($orderInProgress = null)
    {
        if ($orderInProgress) {
            $this->addUsingAlias(OrderInProgressTableMap::COL_ORDER_IN_PROGRESSID, $orderInProgress->getOrderInProgressid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the order_in_progress table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrderInProgressTableMap::clearInstancePool();
            OrderInProgressTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderInProgressTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrderInProgressTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            OrderInProgressTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            OrderInProgressTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrderInProgressQuery
