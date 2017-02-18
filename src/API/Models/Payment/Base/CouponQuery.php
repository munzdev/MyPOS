<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Payment\Coupon as ChildCoupon;
use API\Models\Payment\CouponQuery as ChildCouponQuery;
use API\Models\Payment\Map\CouponTableMap;
use API\Models\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'coupon' table.
 *
 * @method ChildCouponQuery orderByCouponid($order = Criteria::ASC) Order by the couponid column
 * @method ChildCouponQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method ChildCouponQuery orderByCreatedByUserid($order = Criteria::ASC) Order by the created_by_userid column
 * @method ChildCouponQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method ChildCouponQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method ChildCouponQuery orderByValue($order = Criteria::ASC) Order by the value column
 *
 * @method ChildCouponQuery groupByCouponid() Group by the couponid column
 * @method ChildCouponQuery groupByEventid() Group by the eventid column
 * @method ChildCouponQuery groupByCreatedByUserid() Group by the created_by_userid column
 * @method ChildCouponQuery groupByCode() Group by the code column
 * @method ChildCouponQuery groupByCreated() Group by the created column
 * @method ChildCouponQuery groupByValue() Group by the value column
 *
 * @method ChildCouponQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ChildCouponQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ChildCouponQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ChildCouponQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method ChildCouponQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method ChildCouponQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method ChildCouponQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method ChildCouponQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method ChildCouponQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method ChildCouponQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method ChildCouponQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method ChildCouponQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method ChildCouponQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method ChildCouponQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method ChildCouponQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method ChildCouponQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method ChildCouponQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method ChildCouponQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method ChildCouponQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method ChildCouponQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method ChildCouponQuery leftJoinPaymentCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentCoupon relation
 * @method ChildCouponQuery rightJoinPaymentCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentCoupon relation
 * @method ChildCouponQuery innerJoinPaymentCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentCoupon relation
 *
 * @method ChildCouponQuery joinWithPaymentCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentCoupon relation
 *
 * @method ChildCouponQuery leftJoinWithPaymentCoupon() Adds a LEFT JOIN clause and with to the query using the PaymentCoupon relation
 * @method ChildCouponQuery rightJoinWithPaymentCoupon() Adds a RIGHT JOIN clause and with to the query using the PaymentCoupon relation
 * @method ChildCouponQuery innerJoinWithPaymentCoupon() Adds a INNER JOIN clause and with to the query using the PaymentCoupon relation
 *
 * @method \API\Models\Event\EventQuery|\API\Models\User\UserQuery|\API\Models\Payment\PaymentCouponQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method ChildCoupon findOne(ConnectionInterface $con = null) Return the first ChildCoupon matching the query
 * @method ChildCoupon findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCoupon matching the query, or a new ChildCoupon object populated from the query conditions when no match is found
 *
 * @method ChildCoupon findOneByCouponid(int $couponid) Return the first ChildCoupon filtered by the couponid column
 * @method ChildCoupon findOneByEventid(int $eventid) Return the first ChildCoupon filtered by the eventid column
 * @method ChildCoupon findOneByCreatedByUserid(int $created_by_userid) Return the first ChildCoupon filtered by the created_by_userid column
 * @method ChildCoupon findOneByCode(string $code) Return the first ChildCoupon filtered by the code column
 * @method ChildCoupon findOneByCreated(string $created) Return the first ChildCoupon filtered by the created column
 * @method ChildCoupon findOneByValue(string $value) Return the first ChildCoupon filtered by the value column *

 * @method ChildCoupon requirePk($key, ConnectionInterface $con = null) Return the ChildCoupon by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildCoupon requireOne(ConnectionInterface $con = null) Return the first ChildCoupon matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildCoupon requireOneByCouponid(int $couponid) Return the first ChildCoupon filtered by the couponid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildCoupon requireOneByEventid(int $eventid) Return the first ChildCoupon filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildCoupon requireOneByCreatedByUserid(int $created_by_userid) Return the first ChildCoupon filtered by the created_by_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildCoupon requireOneByCode(string $code) Return the first ChildCoupon filtered by the code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildCoupon requireOneByCreated(string $created) Return the first ChildCoupon filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildCoupon requireOneByValue(string $value) Return the first ChildCoupon filtered by the value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildCoupon[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCoupon objects based on current ModelCriteria
 * @method ChildCoupon[]|ObjectCollection findByCouponid(int $couponid) Return ChildCoupon objects filtered by the couponid column
 * @method ChildCoupon[]|ObjectCollection findByEventid(int $eventid) Return ChildCoupon objects filtered by the eventid column
 * @method ChildCoupon[]|ObjectCollection findByCreatedByUserid(int $created_by_userid) Return ChildCoupon objects filtered by the created_by_userid column
 * @method ChildCoupon[]|ObjectCollection findByCode(string $code) Return ChildCoupon objects filtered by the code column
 * @method ChildCoupon[]|ObjectCollection findByCreated(string $created) Return ChildCoupon objects filtered by the created column
 * @method ChildCoupon[]|ObjectCollection findByValue(string $value) Return ChildCoupon objects filtered by the value column
 * @method ChildCoupon[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CouponQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\CouponQuery object.
     *
     * @param string $dbName     The database name
     * @param string $modelName  The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\Coupon', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCouponQuery object.
     *
     * @param string   $modelAlias The alias of a model in the query
     * @param Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildCouponQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCouponQuery) {
            return $criteria;
        }
        $query = new ChildCouponQuery();
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
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCoupon|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CouponTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if ($this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CouponTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCoupon A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT couponid, eventid, created_by_userid, code, created, value FROM coupon WHERE couponid = :p0';
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
            /**
 * @var ChildCoupon $obj
*/
            $obj = new ChildCoupon();
            $obj->hydrate($row);
            CouponTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildCoupon|array|mixed the result, formatted by the current formatter
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
     *
     * @param array               $keys Primary keys to use for the query
     * @param ConnectionInterface $con  an optional connection object
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(CouponTableMap::COL_COUPONID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(CouponTableMap::COL_COUPONID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the couponid column
     *
     * Example usage:
     * <code>
     * $query->filterByCouponid(1234); // WHERE couponid = 1234
     * $query->filterByCouponid(array(12, 34)); // WHERE couponid IN (12, 34)
     * $query->filterByCouponid(array('min' => 12)); // WHERE couponid > 12
     * </code>
     *
     * @param mixed  $couponid   The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByCouponid($couponid = null, $comparison = null)
    {
        if (is_array($couponid)) {
            $useMinMax = false;
            if (isset($couponid['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPONID, $couponid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($couponid['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPONID, $couponid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CouponTableMap::COL_COUPONID, $couponid, $comparison);
    }

    /**
     * Filter the query on the eventid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventid(1234); // WHERE eventid = 1234
     * $query->filterByEventid(array(12, 34)); // WHERE eventid IN (12, 34)
     * $query->filterByEventid(array('min' => 12)); // WHERE eventid > 12
     * </code>
     *
     * @see filterByEvent()
     *
     * @param mixed  $eventid    The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CouponTableMap::COL_EVENTID, $eventid, $comparison);
    }

    /**
     * Filter the query on the created_by_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedByUserid(1234); // WHERE created_by_userid = 1234
     * $query->filterByCreatedByUserid(array(12, 34)); // WHERE created_by_userid IN (12, 34)
     * $query->filterByCreatedByUserid(array('min' => 12)); // WHERE created_by_userid > 12
     * </code>
     *
     * @see filterByUser()
     *
     * @param mixed  $createdByUserid The value to use as filter.
     *                                    Use scalar values for
     *                                    equality. Use array values
     *                                    for in_array() equivalent.
     *                                    Use associative array('min'
     *                                    => $minValue, 'max' =>
     *                                    $maxValue) for intervals.
     * @param string $comparison      Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByCreatedByUserid($createdByUserid = null, $comparison = null)
    {
        if (is_array($createdByUserid)) {
            $useMinMax = false;
            if (isset($createdByUserid['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_CREATED_BY_USERID, $createdByUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdByUserid['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_CREATED_BY_USERID, $createdByUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CouponTableMap::COL_CREATED_BY_USERID, $createdByUserid, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%', Criteria::LIKE); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param string $code       The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CouponTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated('2011-03-14'); // WHERE created = '2011-03-14'
     * $query->filterByCreated('now'); // WHERE created = '2011-03-14'
     * $query->filterByCreated(array('max' => 'yesterday')); // WHERE created > '2011-03-13'
     * </code>
     *
     * @param mixed  $created    The value to use as filter.
     *                           Values can be integers
     *                           (unix timestamps), DateTime
     *                           objects, or strings. Empty
     *                           strings are treated as
     *                           NULL. Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CouponTableMap::COL_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue(1234); // WHERE value = 1234
     * $query->filterByValue(array(12, 34)); // WHERE value IN (12, 34)
     * $query->filterByValue(array('min' => 12)); // WHERE value > 12
     * </code>
     *
     * @param mixed  $value      The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CouponTableMap::COL_VALUE, $value, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event      The related object(s) to use as filter
     * @param string                                   $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCouponQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(CouponTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CouponTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \API\Models\Event\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

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
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\API\Models\Event\EventQuery');
    }

    /**
     * Filter the query by a related \API\Models\User\User object
     *
     * @param \API\Models\User\User|ObjectCollection $user       The related object(s) to use as filter
     * @param string                                 $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCouponQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\User\User) {
            return $this
                ->addUsingAlias(CouponTableMap::COL_CREATED_BY_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CouponTableMap::COL_CREATED_BY_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentCoupon object
     *
     * @param \API\Models\Payment\PaymentCoupon|ObjectCollection $paymentCoupon the related object to use as filter
     * @param string                                             $comparison    Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCouponQuery The current query, for fluid interface
     */
    public function filterByPaymentCoupon($paymentCoupon, $comparison = null)
    {
        if ($paymentCoupon instanceof \API\Models\Payment\PaymentCoupon) {
            return $this
                ->addUsingAlias(CouponTableMap::COL_COUPONID, $paymentCoupon->getCouponid(), $comparison);
        } elseif ($paymentCoupon instanceof ObjectCollection) {
            return $this
                ->usePaymentCouponQuery()
                ->filterByPrimaryKeys($paymentCoupon->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPaymentCoupon() only accepts arguments of type \API\Models\Payment\PaymentCoupon or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentCoupon relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function joinPaymentCoupon($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentCoupon');

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
            $this->addJoinObject($join, 'PaymentCoupon');
        }

        return $this;
    }

    /**
     * Use the PaymentCoupon relation PaymentCoupon object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentCouponQuery A secondary query class using the current class as primary query
     */
    public function usePaymentCouponQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentCoupon($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentCoupon', '\API\Models\Payment\PaymentCouponQuery');
    }

    /**
     * Filter the query by a related PaymentRecieved object
     * using the payment_coupon table as cross reference
     *
     * @param PaymentRecieved $paymentRecieved the related object to use as filter
     * @param string          $comparison      Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCouponQuery The current query, for fluid interface
     */
    public function filterByPaymentRecieved($paymentRecieved, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePaymentCouponQuery()
            ->filterByPaymentRecieved($paymentRecieved, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param ChildCoupon $coupon Object to remove from the list of results
     *
     * @return $this|ChildCouponQuery The current query, for fluid interface
     */
    public function prune($coupon = null)
    {
        if ($coupon) {
            $this->addUsingAlias(CouponTableMap::COL_COUPONID, $coupon->getCouponid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the coupon table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con) {
                $affectedRows = 0; // initialize var to track total num of affected rows
                $affectedRows += parent::doDeleteAll($con);
                // Because this db requires some delete cascade/set null emulation, we have to
                // clear the cached instance *after* the emulation has happened (since
                // instances get re-added by the select statement contained therein).
                CouponTableMap::clearInstancePool();
                CouponTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CouponTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con, $criteria) {
                $affectedRows = 0; // initialize var to track total num of affected rows

                CouponTableMap::removeInstanceFromPool($criteria);

                $affectedRows += ModelCriteria::delete($con);
                CouponTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }
} // CouponQuery
