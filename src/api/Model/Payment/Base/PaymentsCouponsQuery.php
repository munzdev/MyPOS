<?php

namespace Model\Payment\Base;

use \Exception;
use \PDO;
use Model\Payment\PaymentsCoupons as ChildPaymentsCoupons;
use Model\Payment\PaymentsCouponsQuery as ChildPaymentsCouponsQuery;
use Model\Payment\Map\PaymentsCouponsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payments_coupons' table.
 *
 *
 *
 * @method     ChildPaymentsCouponsQuery orderByCouponid($order = Criteria::ASC) Order by the couponid column
 * @method     ChildPaymentsCouponsQuery orderByPaymentid($order = Criteria::ASC) Order by the paymentid column
 * @method     ChildPaymentsCouponsQuery orderByValueUsed($order = Criteria::ASC) Order by the value_used column
 *
 * @method     ChildPaymentsCouponsQuery groupByCouponid() Group by the couponid column
 * @method     ChildPaymentsCouponsQuery groupByPaymentid() Group by the paymentid column
 * @method     ChildPaymentsCouponsQuery groupByValueUsed() Group by the value_used column
 *
 * @method     ChildPaymentsCouponsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentsCouponsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentsCouponsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentsCouponsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentsCouponsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentsCouponsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentsCouponsQuery leftJoinCoupons($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupons relation
 * @method     ChildPaymentsCouponsQuery rightJoinCoupons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupons relation
 * @method     ChildPaymentsCouponsQuery innerJoinCoupons($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupons relation
 *
 * @method     ChildPaymentsCouponsQuery joinWithCoupons($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupons relation
 *
 * @method     ChildPaymentsCouponsQuery leftJoinWithCoupons() Adds a LEFT JOIN clause and with to the query using the Coupons relation
 * @method     ChildPaymentsCouponsQuery rightJoinWithCoupons() Adds a RIGHT JOIN clause and with to the query using the Coupons relation
 * @method     ChildPaymentsCouponsQuery innerJoinWithCoupons() Adds a INNER JOIN clause and with to the query using the Coupons relation
 *
 * @method     ChildPaymentsCouponsQuery leftJoinPayments($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payments relation
 * @method     ChildPaymentsCouponsQuery rightJoinPayments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payments relation
 * @method     ChildPaymentsCouponsQuery innerJoinPayments($relationAlias = null) Adds a INNER JOIN clause to the query using the Payments relation
 *
 * @method     ChildPaymentsCouponsQuery joinWithPayments($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payments relation
 *
 * @method     ChildPaymentsCouponsQuery leftJoinWithPayments() Adds a LEFT JOIN clause and with to the query using the Payments relation
 * @method     ChildPaymentsCouponsQuery rightJoinWithPayments() Adds a RIGHT JOIN clause and with to the query using the Payments relation
 * @method     ChildPaymentsCouponsQuery innerJoinWithPayments() Adds a INNER JOIN clause and with to the query using the Payments relation
 *
 * @method     \Model\Payment\CouponsQuery|\Model\Payment\PaymentsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPaymentsCoupons findOne(ConnectionInterface $con = null) Return the first ChildPaymentsCoupons matching the query
 * @method     ChildPaymentsCoupons findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPaymentsCoupons matching the query, or a new ChildPaymentsCoupons object populated from the query conditions when no match is found
 *
 * @method     ChildPaymentsCoupons findOneByCouponid(int $couponid) Return the first ChildPaymentsCoupons filtered by the couponid column
 * @method     ChildPaymentsCoupons findOneByPaymentid(int $paymentid) Return the first ChildPaymentsCoupons filtered by the paymentid column
 * @method     ChildPaymentsCoupons findOneByValueUsed(string $value_used) Return the first ChildPaymentsCoupons filtered by the value_used column *

 * @method     ChildPaymentsCoupons requirePk($key, ConnectionInterface $con = null) Return the ChildPaymentsCoupons by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentsCoupons requireOne(ConnectionInterface $con = null) Return the first ChildPaymentsCoupons matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentsCoupons requireOneByCouponid(int $couponid) Return the first ChildPaymentsCoupons filtered by the couponid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentsCoupons requireOneByPaymentid(int $paymentid) Return the first ChildPaymentsCoupons filtered by the paymentid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentsCoupons requireOneByValueUsed(string $value_used) Return the first ChildPaymentsCoupons filtered by the value_used column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentsCoupons[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPaymentsCoupons objects based on current ModelCriteria
 * @method     ChildPaymentsCoupons[]|ObjectCollection findByCouponid(int $couponid) Return ChildPaymentsCoupons objects filtered by the couponid column
 * @method     ChildPaymentsCoupons[]|ObjectCollection findByPaymentid(int $paymentid) Return ChildPaymentsCoupons objects filtered by the paymentid column
 * @method     ChildPaymentsCoupons[]|ObjectCollection findByValueUsed(string $value_used) Return ChildPaymentsCoupons objects filtered by the value_used column
 * @method     ChildPaymentsCoupons[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentsCouponsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Payment\Base\PaymentsCouponsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Payment\\PaymentsCoupons', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentsCouponsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentsCouponsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentsCouponsQuery) {
            return $criteria;
        }
        $query = new ChildPaymentsCouponsQuery();
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
     * @param array[$couponid, $paymentid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPaymentsCoupons|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentsCouponsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentsCouponsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPaymentsCoupons A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT couponid, paymentid, value_used FROM payments_coupons WHERE couponid = :p0 AND paymentid = :p1';
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
            /** @var ChildPaymentsCoupons $obj */
            $obj = new ChildPaymentsCoupons();
            $obj->hydrate($row);
            PaymentsCouponsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPaymentsCoupons|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PaymentsCouponsTableMap::COL_COUPONID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PaymentsCouponsTableMap::COL_PAYMENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PaymentsCouponsTableMap::COL_COUPONID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PaymentsCouponsTableMap::COL_PAYMENTID, $key[1], Criteria::EQUAL);
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
     * @see       filterByCoupons()
     *
     * @param     mixed $couponid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByCouponid($couponid = null, $comparison = null)
    {
        if (is_array($couponid)) {
            $useMinMax = false;
            if (isset($couponid['min'])) {
                $this->addUsingAlias(PaymentsCouponsTableMap::COL_COUPONID, $couponid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($couponid['max'])) {
                $this->addUsingAlias(PaymentsCouponsTableMap::COL_COUPONID, $couponid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsCouponsTableMap::COL_COUPONID, $couponid, $comparison);
    }

    /**
     * Filter the query on the paymentid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentid(1234); // WHERE paymentid = 1234
     * $query->filterByPaymentid(array(12, 34)); // WHERE paymentid IN (12, 34)
     * $query->filterByPaymentid(array('min' => 12)); // WHERE paymentid > 12
     * </code>
     *
     * @see       filterByPayments()
     *
     * @param     mixed $paymentid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByPaymentid($paymentid = null, $comparison = null)
    {
        if (is_array($paymentid)) {
            $useMinMax = false;
            if (isset($paymentid['min'])) {
                $this->addUsingAlias(PaymentsCouponsTableMap::COL_PAYMENTID, $paymentid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentid['max'])) {
                $this->addUsingAlias(PaymentsCouponsTableMap::COL_PAYMENTID, $paymentid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsCouponsTableMap::COL_PAYMENTID, $paymentid, $comparison);
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
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByValueUsed($valueUsed = null, $comparison = null)
    {
        if (is_array($valueUsed)) {
            $useMinMax = false;
            if (isset($valueUsed['min'])) {
                $this->addUsingAlias(PaymentsCouponsTableMap::COL_VALUE_USED, $valueUsed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($valueUsed['max'])) {
                $this->addUsingAlias(PaymentsCouponsTableMap::COL_VALUE_USED, $valueUsed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsCouponsTableMap::COL_VALUE_USED, $valueUsed, $comparison);
    }

    /**
     * Filter the query by a related \Model\Payment\Coupons object
     *
     * @param \Model\Payment\Coupons|ObjectCollection $coupons The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByCoupons($coupons, $comparison = null)
    {
        if ($coupons instanceof \Model\Payment\Coupons) {
            return $this
                ->addUsingAlias(PaymentsCouponsTableMap::COL_COUPONID, $coupons->getCouponid(), $comparison);
        } elseif ($coupons instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentsCouponsTableMap::COL_COUPONID, $coupons->toKeyValue('Couponid', 'Couponid'), $comparison);
        } else {
            throw new PropelException('filterByCoupons() only accepts arguments of type \Model\Payment\Coupons or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Coupons relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function joinCoupons($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Coupons');

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
            $this->addJoinObject($join, 'Coupons');
        }

        return $this;
    }

    /**
     * Use the Coupons relation Coupons object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Payment\CouponsQuery A secondary query class using the current class as primary query
     */
    public function useCouponsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCoupons($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Coupons', '\Model\Payment\CouponsQuery');
    }

    /**
     * Filter the query by a related \Model\Payment\Payments object
     *
     * @param \Model\Payment\Payments|ObjectCollection $payments The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function filterByPayments($payments, $comparison = null)
    {
        if ($payments instanceof \Model\Payment\Payments) {
            return $this
                ->addUsingAlias(PaymentsCouponsTableMap::COL_PAYMENTID, $payments->getPaymentid(), $comparison);
        } elseif ($payments instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentsCouponsTableMap::COL_PAYMENTID, $payments->toKeyValue('Paymentid', 'Paymentid'), $comparison);
        } else {
            throw new PropelException('filterByPayments() only accepts arguments of type \Model\Payment\Payments or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Payments relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function joinPayments($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Payments');

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
            $this->addJoinObject($join, 'Payments');
        }

        return $this;
    }

    /**
     * Use the Payments relation Payments object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Payment\PaymentsQuery A secondary query class using the current class as primary query
     */
    public function usePaymentsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPayments($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Payments', '\Model\Payment\PaymentsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPaymentsCoupons $paymentsCoupons Object to remove from the list of results
     *
     * @return $this|ChildPaymentsCouponsQuery The current query, for fluid interface
     */
    public function prune($paymentsCoupons = null)
    {
        if ($paymentsCoupons) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PaymentsCouponsTableMap::COL_COUPONID), $paymentsCoupons->getCouponid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PaymentsCouponsTableMap::COL_PAYMENTID), $paymentsCoupons->getPaymentid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payments_coupons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentsCouponsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentsCouponsTableMap::clearInstancePool();
            PaymentsCouponsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentsCouponsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentsCouponsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentsCouponsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentsCouponsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentsCouponsQuery
