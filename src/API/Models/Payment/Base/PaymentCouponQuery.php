<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Payment\PaymentCoupon as ChildPaymentCoupon;
use API\Models\Payment\PaymentCouponQuery as ChildPaymentCouponQuery;
use API\Models\Payment\Map\PaymentCouponTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payment_coupon' table.
 *
 *
 *
 * @method     ChildPaymentCouponQuery orderByCouponid($order = Criteria::ASC) Order by the couponid column
 * @method     ChildPaymentCouponQuery orderByPaymentRecievedid($order = Criteria::ASC) Order by the payment_recievedid column
 * @method     ChildPaymentCouponQuery orderByValueUsed($order = Criteria::ASC) Order by the value_used column
 *
 * @method     ChildPaymentCouponQuery groupByCouponid() Group by the couponid column
 * @method     ChildPaymentCouponQuery groupByPaymentRecievedid() Group by the payment_recievedid column
 * @method     ChildPaymentCouponQuery groupByValueUsed() Group by the value_used column
 *
 * @method     ChildPaymentCouponQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentCouponQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentCouponQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentCouponQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentCouponQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentCouponQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentCouponQuery leftJoinCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupon relation
 * @method     ChildPaymentCouponQuery rightJoinCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupon relation
 * @method     ChildPaymentCouponQuery innerJoinCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupon relation
 *
 * @method     ChildPaymentCouponQuery joinWithCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupon relation
 *
 * @method     ChildPaymentCouponQuery leftJoinWithCoupon() Adds a LEFT JOIN clause and with to the query using the Coupon relation
 * @method     ChildPaymentCouponQuery rightJoinWithCoupon() Adds a RIGHT JOIN clause and with to the query using the Coupon relation
 * @method     ChildPaymentCouponQuery innerJoinWithCoupon() Adds a INNER JOIN clause and with to the query using the Coupon relation
 *
 * @method     ChildPaymentCouponQuery leftJoinPaymentRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentRecieved relation
 * @method     ChildPaymentCouponQuery rightJoinPaymentRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentRecieved relation
 * @method     ChildPaymentCouponQuery innerJoinPaymentRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentRecieved relation
 *
 * @method     ChildPaymentCouponQuery joinWithPaymentRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentRecieved relation
 *
 * @method     ChildPaymentCouponQuery leftJoinWithPaymentRecieved() Adds a LEFT JOIN clause and with to the query using the PaymentRecieved relation
 * @method     ChildPaymentCouponQuery rightJoinWithPaymentRecieved() Adds a RIGHT JOIN clause and with to the query using the PaymentRecieved relation
 * @method     ChildPaymentCouponQuery innerJoinWithPaymentRecieved() Adds a INNER JOIN clause and with to the query using the PaymentRecieved relation
 *
 * @method     \API\Models\Payment\CouponQuery|\API\Models\Payment\PaymentRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPaymentCoupon findOne(ConnectionInterface $con = null) Return the first ChildPaymentCoupon matching the query
 * @method     ChildPaymentCoupon findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPaymentCoupon matching the query, or a new ChildPaymentCoupon object populated from the query conditions when no match is found
 *
 * @method     ChildPaymentCoupon findOneByCouponid(int $couponid) Return the first ChildPaymentCoupon filtered by the couponid column
 * @method     ChildPaymentCoupon findOneByPaymentRecievedid(int $payment_recievedid) Return the first ChildPaymentCoupon filtered by the payment_recievedid column
 * @method     ChildPaymentCoupon findOneByValueUsed(string $value_used) Return the first ChildPaymentCoupon filtered by the value_used column *

 * @method     ChildPaymentCoupon requirePk($key, ConnectionInterface $con = null) Return the ChildPaymentCoupon by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentCoupon requireOne(ConnectionInterface $con = null) Return the first ChildPaymentCoupon matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentCoupon requireOneByCouponid(int $couponid) Return the first ChildPaymentCoupon filtered by the couponid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentCoupon requireOneByPaymentRecievedid(int $payment_recievedid) Return the first ChildPaymentCoupon filtered by the payment_recievedid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentCoupon requireOneByValueUsed(string $value_used) Return the first ChildPaymentCoupon filtered by the value_used column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentCoupon[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPaymentCoupon objects based on current ModelCriteria
 * @method     ChildPaymentCoupon[]|ObjectCollection findByCouponid(int $couponid) Return ChildPaymentCoupon objects filtered by the couponid column
 * @method     ChildPaymentCoupon[]|ObjectCollection findByPaymentRecievedid(int $payment_recievedid) Return ChildPaymentCoupon objects filtered by the payment_recievedid column
 * @method     ChildPaymentCoupon[]|ObjectCollection findByValueUsed(string $value_used) Return ChildPaymentCoupon objects filtered by the value_used column
 * @method     ChildPaymentCoupon[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentCouponQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\PaymentCouponQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\PaymentCoupon', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentCouponQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentCouponQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentCouponQuery) {
            return $criteria;
        }
        $query = new ChildPaymentCouponQuery();
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
     * @param array[$couponid, $payment_recievedid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPaymentCoupon|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentCouponTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentCouponTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPaymentCoupon A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT couponid, payment_recievedid, value_used FROM payment_coupon WHERE couponid = :p0 AND payment_recievedid = :p1';
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
            /** @var ChildPaymentCoupon $obj */
            $obj = new ChildPaymentCoupon();
            $obj->hydrate($row);
            PaymentCouponTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPaymentCoupon|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PaymentCouponTableMap::COL_COUPONID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PaymentCouponTableMap::COL_COUPONID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByCoupon()
     *
     * @param     mixed $couponid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByCouponid($couponid = null, $comparison = null)
    {
        if (is_array($couponid)) {
            $useMinMax = false;
            if (isset($couponid['min'])) {
                $this->addUsingAlias(PaymentCouponTableMap::COL_COUPONID, $couponid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($couponid['max'])) {
                $this->addUsingAlias(PaymentCouponTableMap::COL_COUPONID, $couponid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentCouponTableMap::COL_COUPONID, $couponid, $comparison);
    }

    /**
     * Filter the query on the payment_recievedid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentRecievedid(1234); // WHERE payment_recievedid = 1234
     * $query->filterByPaymentRecievedid(array(12, 34)); // WHERE payment_recievedid IN (12, 34)
     * $query->filterByPaymentRecievedid(array('min' => 12)); // WHERE payment_recievedid > 12
     * </code>
     *
     * @see       filterByPaymentRecieved()
     *
     * @param     mixed $paymentRecievedid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByPaymentRecievedid($paymentRecievedid = null, $comparison = null)
    {
        if (is_array($paymentRecievedid)) {
            $useMinMax = false;
            if (isset($paymentRecievedid['min'])) {
                $this->addUsingAlias(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecievedid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentRecievedid['max'])) {
                $this->addUsingAlias(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecievedid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecievedid, $comparison);
    }

    /**
     * Filter the query on the value_used column
     *
     * Example usage:
     * <code>
     * $query->filterByValueUsed(1234); // WHERE value_used = 1234
     * $query->filterByValueUsed(array(12, 34)); // WHERE value_used IN (12, 34)
     * $query->filterByValueUsed(array('min' => 12)); // WHERE value_used > 12
     * </code>
     *
     * @param     mixed $valueUsed The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByValueUsed($valueUsed = null, $comparison = null)
    {
        if (is_array($valueUsed)) {
            $useMinMax = false;
            if (isset($valueUsed['min'])) {
                $this->addUsingAlias(PaymentCouponTableMap::COL_VALUE_USED, $valueUsed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($valueUsed['max'])) {
                $this->addUsingAlias(PaymentCouponTableMap::COL_VALUE_USED, $valueUsed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentCouponTableMap::COL_VALUE_USED, $valueUsed, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Payment\Coupon object
     *
     * @param \API\Models\Payment\Coupon|ObjectCollection $coupon The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByCoupon($coupon, $comparison = null)
    {
        if ($coupon instanceof \API\Models\Payment\Coupon) {
            return $this
                ->addUsingAlias(PaymentCouponTableMap::COL_COUPONID, $coupon->getCouponid(), $comparison);
        } elseif ($coupon instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentCouponTableMap::COL_COUPONID, $coupon->toKeyValue('PrimaryKey', 'Couponid'), $comparison);
        } else {
            throw new PropelException('filterByCoupon() only accepts arguments of type \API\Models\Payment\Coupon or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Coupon relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function joinCoupon($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Coupon');

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
            $this->addJoinObject($join, 'Coupon');
        }

        return $this;
    }

    /**
     * Use the Coupon relation Coupon object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\CouponQuery A secondary query class using the current class as primary query
     */
    public function useCouponQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCoupon($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Coupon', '\API\Models\Payment\CouponQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentRecieved object
     *
     * @param \API\Models\Payment\PaymentRecieved|ObjectCollection $paymentRecieved The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function filterByPaymentRecieved($paymentRecieved, $comparison = null)
    {
        if ($paymentRecieved instanceof \API\Models\Payment\PaymentRecieved) {
            return $this
                ->addUsingAlias(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecieved->getPaymentRecievedid(), $comparison);
        } elseif ($paymentRecieved instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecieved->toKeyValue('PrimaryKey', 'PaymentRecievedid'), $comparison);
        } else {
            throw new PropelException('filterByPaymentRecieved() only accepts arguments of type \API\Models\Payment\PaymentRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function joinPaymentRecieved($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentRecieved');

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
            $this->addJoinObject($join, 'PaymentRecieved');
        }

        return $this;
    }

    /**
     * Use the PaymentRecieved relation PaymentRecieved object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentRecievedQuery A secondary query class using the current class as primary query
     */
    public function usePaymentRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentRecieved', '\API\Models\Payment\PaymentRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPaymentCoupon $paymentCoupon Object to remove from the list of results
     *
     * @return $this|ChildPaymentCouponQuery The current query, for fluid interface
     */
    public function prune($paymentCoupon = null)
    {
        if ($paymentCoupon) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PaymentCouponTableMap::COL_COUPONID), $paymentCoupon->getCouponid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PaymentCouponTableMap::COL_PAYMENT_RECIEVEDID), $paymentCoupon->getPaymentRecievedid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payment_coupon table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentCouponTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentCouponTableMap::clearInstancePool();
            PaymentCouponTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentCouponTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentCouponTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentCouponTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentCouponTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentCouponQuery
