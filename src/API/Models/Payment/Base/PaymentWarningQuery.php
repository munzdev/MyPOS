<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Payment\PaymentWarning as ChildPaymentWarning;
use API\Models\Payment\PaymentWarningQuery as ChildPaymentWarningQuery;
use API\Models\Payment\Map\PaymentWarningTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payment_warning' table.
 *
 *
 *
 * @method     ChildPaymentWarningQuery orderByPaymentWarningid($order = Criteria::ASC) Order by the payment_warningid column
 * @method     ChildPaymentWarningQuery orderByPaymentid($order = Criteria::ASC) Order by the paymentid column
 * @method     ChildPaymentWarningQuery orderByPaymentWarningTypeid($order = Criteria::ASC) Order by the payment_warning_typeid column
 * @method     ChildPaymentWarningQuery orderByWarningDate($order = Criteria::ASC) Order by the warning_date column
 * @method     ChildPaymentWarningQuery orderByMaturityDate($order = Criteria::ASC) Order by the maturity_date column
 * @method     ChildPaymentWarningQuery orderByWarningValue($order = Criteria::ASC) Order by the warning_value column
 *
 * @method     ChildPaymentWarningQuery groupByPaymentWarningid() Group by the payment_warningid column
 * @method     ChildPaymentWarningQuery groupByPaymentid() Group by the paymentid column
 * @method     ChildPaymentWarningQuery groupByPaymentWarningTypeid() Group by the payment_warning_typeid column
 * @method     ChildPaymentWarningQuery groupByWarningDate() Group by the warning_date column
 * @method     ChildPaymentWarningQuery groupByMaturityDate() Group by the maturity_date column
 * @method     ChildPaymentWarningQuery groupByWarningValue() Group by the warning_value column
 *
 * @method     ChildPaymentWarningQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentWarningQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentWarningQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentWarningQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentWarningQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentWarningQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentWarningQuery leftJoinPayment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payment relation
 * @method     ChildPaymentWarningQuery rightJoinPayment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payment relation
 * @method     ChildPaymentWarningQuery innerJoinPayment($relationAlias = null) Adds a INNER JOIN clause to the query using the Payment relation
 *
 * @method     ChildPaymentWarningQuery joinWithPayment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payment relation
 *
 * @method     ChildPaymentWarningQuery leftJoinWithPayment() Adds a LEFT JOIN clause and with to the query using the Payment relation
 * @method     ChildPaymentWarningQuery rightJoinWithPayment() Adds a RIGHT JOIN clause and with to the query using the Payment relation
 * @method     ChildPaymentWarningQuery innerJoinWithPayment() Adds a INNER JOIN clause and with to the query using the Payment relation
 *
 * @method     ChildPaymentWarningQuery leftJoinPaymentWarningType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentWarningType relation
 * @method     ChildPaymentWarningQuery rightJoinPaymentWarningType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentWarningType relation
 * @method     ChildPaymentWarningQuery innerJoinPaymentWarningType($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentWarningType relation
 *
 * @method     ChildPaymentWarningQuery joinWithPaymentWarningType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentWarningType relation
 *
 * @method     ChildPaymentWarningQuery leftJoinWithPaymentWarningType() Adds a LEFT JOIN clause and with to the query using the PaymentWarningType relation
 * @method     ChildPaymentWarningQuery rightJoinWithPaymentWarningType() Adds a RIGHT JOIN clause and with to the query using the PaymentWarningType relation
 * @method     ChildPaymentWarningQuery innerJoinWithPaymentWarningType() Adds a INNER JOIN clause and with to the query using the PaymentWarningType relation
 *
 * @method     \API\Models\Payment\PaymentQuery|\API\Models\Payment\PaymentWarningTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPaymentWarning findOne(ConnectionInterface $con = null) Return the first ChildPaymentWarning matching the query
 * @method     ChildPaymentWarning findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPaymentWarning matching the query, or a new ChildPaymentWarning object populated from the query conditions when no match is found
 *
 * @method     ChildPaymentWarning findOneByPaymentWarningid(int $payment_warningid) Return the first ChildPaymentWarning filtered by the payment_warningid column
 * @method     ChildPaymentWarning findOneByPaymentid(int $paymentid) Return the first ChildPaymentWarning filtered by the paymentid column
 * @method     ChildPaymentWarning findOneByPaymentWarningTypeid(int $payment_warning_typeid) Return the first ChildPaymentWarning filtered by the payment_warning_typeid column
 * @method     ChildPaymentWarning findOneByWarningDate(string $warning_date) Return the first ChildPaymentWarning filtered by the warning_date column
 * @method     ChildPaymentWarning findOneByMaturityDate(string $maturity_date) Return the first ChildPaymentWarning filtered by the maturity_date column
 * @method     ChildPaymentWarning findOneByWarningValue(string $warning_value) Return the first ChildPaymentWarning filtered by the warning_value column *

 * @method     ChildPaymentWarning requirePk($key, ConnectionInterface $con = null) Return the ChildPaymentWarning by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentWarning requireOne(ConnectionInterface $con = null) Return the first ChildPaymentWarning matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentWarning requireOneByPaymentWarningid(int $payment_warningid) Return the first ChildPaymentWarning filtered by the payment_warningid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentWarning requireOneByPaymentid(int $paymentid) Return the first ChildPaymentWarning filtered by the paymentid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentWarning requireOneByPaymentWarningTypeid(int $payment_warning_typeid) Return the first ChildPaymentWarning filtered by the payment_warning_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentWarning requireOneByWarningDate(string $warning_date) Return the first ChildPaymentWarning filtered by the warning_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentWarning requireOneByMaturityDate(string $maturity_date) Return the first ChildPaymentWarning filtered by the maturity_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentWarning requireOneByWarningValue(string $warning_value) Return the first ChildPaymentWarning filtered by the warning_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentWarning[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPaymentWarning objects based on current ModelCriteria
 * @method     ChildPaymentWarning[]|ObjectCollection findByPaymentWarningid(int $payment_warningid) Return ChildPaymentWarning objects filtered by the payment_warningid column
 * @method     ChildPaymentWarning[]|ObjectCollection findByPaymentid(int $paymentid) Return ChildPaymentWarning objects filtered by the paymentid column
 * @method     ChildPaymentWarning[]|ObjectCollection findByPaymentWarningTypeid(int $payment_warning_typeid) Return ChildPaymentWarning objects filtered by the payment_warning_typeid column
 * @method     ChildPaymentWarning[]|ObjectCollection findByWarningDate(string $warning_date) Return ChildPaymentWarning objects filtered by the warning_date column
 * @method     ChildPaymentWarning[]|ObjectCollection findByMaturityDate(string $maturity_date) Return ChildPaymentWarning objects filtered by the maturity_date column
 * @method     ChildPaymentWarning[]|ObjectCollection findByWarningValue(string $warning_value) Return ChildPaymentWarning objects filtered by the warning_value column
 * @method     ChildPaymentWarning[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentWarningQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\PaymentWarningQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\PaymentWarning', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentWarningQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentWarningQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentWarningQuery) {
            return $criteria;
        }
        $query = new ChildPaymentWarningQuery();
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
     * @return ChildPaymentWarning|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentWarningTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentWarningTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPaymentWarning A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT payment_warningid, paymentid, payment_warning_typeid, warning_date, maturity_date, warning_value FROM payment_warning WHERE payment_warningid = :p0';
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
            /** @var ChildPaymentWarning $obj */
            $obj = new ChildPaymentWarning();
            $obj->hydrate($row);
            PaymentWarningTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPaymentWarning|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNINGID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNINGID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the payment_warningid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentWarningid(1234); // WHERE payment_warningid = 1234
     * $query->filterByPaymentWarningid(array(12, 34)); // WHERE payment_warningid IN (12, 34)
     * $query->filterByPaymentWarningid(array('min' => 12)); // WHERE payment_warningid > 12
     * </code>
     *
     * @param     mixed $paymentWarningid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPaymentWarningid($paymentWarningid = null, $comparison = null)
    {
        if (is_array($paymentWarningid)) {
            $useMinMax = false;
            if (isset($paymentWarningid['min'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNINGID, $paymentWarningid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentWarningid['max'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNINGID, $paymentWarningid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNINGID, $paymentWarningid, $comparison);
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
     * @see       filterByPayment()
     *
     * @param     mixed $paymentid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPaymentid($paymentid = null, $comparison = null)
    {
        if (is_array($paymentid)) {
            $useMinMax = false;
            if (isset($paymentid['min'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENTID, $paymentid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentid['max'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENTID, $paymentid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENTID, $paymentid, $comparison);
    }

    /**
     * Filter the query on the payment_warning_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentWarningTypeid(1234); // WHERE payment_warning_typeid = 1234
     * $query->filterByPaymentWarningTypeid(array(12, 34)); // WHERE payment_warning_typeid IN (12, 34)
     * $query->filterByPaymentWarningTypeid(array('min' => 12)); // WHERE payment_warning_typeid > 12
     * </code>
     *
     * @see       filterByPaymentWarningType()
     *
     * @param     mixed $paymentWarningTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPaymentWarningTypeid($paymentWarningTypeid = null, $comparison = null)
    {
        if (is_array($paymentWarningTypeid)) {
            $useMinMax = false;
            if (isset($paymentWarningTypeid['min'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNING_TYPEID, $paymentWarningTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentWarningTypeid['max'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNING_TYPEID, $paymentWarningTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNING_TYPEID, $paymentWarningTypeid, $comparison);
    }

    /**
     * Filter the query on the warning_date column
     *
     * Example usage:
     * <code>
     * $query->filterByWarningDate('2011-03-14'); // WHERE warning_date = '2011-03-14'
     * $query->filterByWarningDate('now'); // WHERE warning_date = '2011-03-14'
     * $query->filterByWarningDate(array('max' => 'yesterday')); // WHERE warning_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $warningDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByWarningDate($warningDate = null, $comparison = null)
    {
        if (is_array($warningDate)) {
            $useMinMax = false;
            if (isset($warningDate['min'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_WARNING_DATE, $warningDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($warningDate['max'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_WARNING_DATE, $warningDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentWarningTableMap::COL_WARNING_DATE, $warningDate, $comparison);
    }

    /**
     * Filter the query on the maturity_date column
     *
     * Example usage:
     * <code>
     * $query->filterByMaturityDate('2011-03-14'); // WHERE maturity_date = '2011-03-14'
     * $query->filterByMaturityDate('now'); // WHERE maturity_date = '2011-03-14'
     * $query->filterByMaturityDate(array('max' => 'yesterday')); // WHERE maturity_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $maturityDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByMaturityDate($maturityDate = null, $comparison = null)
    {
        if (is_array($maturityDate)) {
            $useMinMax = false;
            if (isset($maturityDate['min'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_MATURITY_DATE, $maturityDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maturityDate['max'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_MATURITY_DATE, $maturityDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentWarningTableMap::COL_MATURITY_DATE, $maturityDate, $comparison);
    }

    /**
     * Filter the query on the warning_value column
     *
     * Example usage:
     * <code>
     * $query->filterByWarningValue(1234); // WHERE warning_value = 1234
     * $query->filterByWarningValue(array(12, 34)); // WHERE warning_value IN (12, 34)
     * $query->filterByWarningValue(array('min' => 12)); // WHERE warning_value > 12
     * </code>
     *
     * @param     mixed $warningValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByWarningValue($warningValue = null, $comparison = null)
    {
        if (is_array($warningValue)) {
            $useMinMax = false;
            if (isset($warningValue['min'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_WARNING_VALUE, $warningValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($warningValue['max'])) {
                $this->addUsingAlias(PaymentWarningTableMap::COL_WARNING_VALUE, $warningValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentWarningTableMap::COL_WARNING_VALUE, $warningValue, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Payment\Payment object
     *
     * @param \API\Models\Payment\Payment|ObjectCollection $payment The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPayment($payment, $comparison = null)
    {
        if ($payment instanceof \API\Models\Payment\Payment) {
            return $this
                ->addUsingAlias(PaymentWarningTableMap::COL_PAYMENTID, $payment->getPaymentid(), $comparison);
        } elseif ($payment instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentWarningTableMap::COL_PAYMENTID, $payment->toKeyValue('PrimaryKey', 'Paymentid'), $comparison);
        } else {
            throw new PropelException('filterByPayment() only accepts arguments of type \API\Models\Payment\Payment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Payment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function joinPayment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Payment');

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
            $this->addJoinObject($join, 'Payment');
        }

        return $this;
    }

    /**
     * Use the Payment relation Payment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentQuery A secondary query class using the current class as primary query
     */
    public function usePaymentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPayment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Payment', '\API\Models\Payment\PaymentQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentWarningType object
     *
     * @param \API\Models\Payment\PaymentWarningType|ObjectCollection $paymentWarningType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function filterByPaymentWarningType($paymentWarningType, $comparison = null)
    {
        if ($paymentWarningType instanceof \API\Models\Payment\PaymentWarningType) {
            return $this
                ->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNING_TYPEID, $paymentWarningType->getPaymentWarningTypeid(), $comparison);
        } elseif ($paymentWarningType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNING_TYPEID, $paymentWarningType->toKeyValue('PrimaryKey', 'PaymentWarningTypeid'), $comparison);
        } else {
            throw new PropelException('filterByPaymentWarningType() only accepts arguments of type \API\Models\Payment\PaymentWarningType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentWarningType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function joinPaymentWarningType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentWarningType');

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
            $this->addJoinObject($join, 'PaymentWarningType');
        }

        return $this;
    }

    /**
     * Use the PaymentWarningType relation PaymentWarningType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentWarningTypeQuery A secondary query class using the current class as primary query
     */
    public function usePaymentWarningTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentWarningType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentWarningType', '\API\Models\Payment\PaymentWarningTypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPaymentWarning $paymentWarning Object to remove from the list of results
     *
     * @return $this|ChildPaymentWarningQuery The current query, for fluid interface
     */
    public function prune($paymentWarning = null)
    {
        if ($paymentWarning) {
            $this->addUsingAlias(PaymentWarningTableMap::COL_PAYMENT_WARNINGID, $paymentWarning->getPaymentWarningid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payment_warning table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentWarningTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentWarningTableMap::clearInstancePool();
            PaymentWarningTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentWarningTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentWarningTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentWarningTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentWarningTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentWarningQuery
