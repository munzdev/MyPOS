<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Invoice\Invoices;
use API\Models\Payment\Payments as ChildPayments;
use API\Models\Payment\PaymentsQuery as ChildPaymentsQuery;
use API\Models\Payment\Map\PaymentsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payments' table.
 *
 *
 *
 * @method     ChildPaymentsQuery orderByPaymentid($order = Criteria::ASC) Order by the paymentid column
 * @method     ChildPaymentsQuery orderByPaymentTypeid($order = Criteria::ASC) Order by the payment_typeid column
 * @method     ChildPaymentsQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildPaymentsQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildPaymentsQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildPaymentsQuery orderByCanceled($order = Criteria::ASC) Order by the canceled column
 *
 * @method     ChildPaymentsQuery groupByPaymentid() Group by the paymentid column
 * @method     ChildPaymentsQuery groupByPaymentTypeid() Group by the payment_typeid column
 * @method     ChildPaymentsQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildPaymentsQuery groupByDate() Group by the date column
 * @method     ChildPaymentsQuery groupByAmount() Group by the amount column
 * @method     ChildPaymentsQuery groupByCanceled() Group by the canceled column
 *
 * @method     ChildPaymentsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentsQuery leftJoinInvoices($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoices relation
 * @method     ChildPaymentsQuery rightJoinInvoices($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoices relation
 * @method     ChildPaymentsQuery innerJoinInvoices($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoices relation
 *
 * @method     ChildPaymentsQuery joinWithInvoices($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoices relation
 *
 * @method     ChildPaymentsQuery leftJoinWithInvoices() Adds a LEFT JOIN clause and with to the query using the Invoices relation
 * @method     ChildPaymentsQuery rightJoinWithInvoices() Adds a RIGHT JOIN clause and with to the query using the Invoices relation
 * @method     ChildPaymentsQuery innerJoinWithInvoices() Adds a INNER JOIN clause and with to the query using the Invoices relation
 *
 * @method     ChildPaymentsQuery leftJoinPaymentTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentTypes relation
 * @method     ChildPaymentsQuery rightJoinPaymentTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentTypes relation
 * @method     ChildPaymentsQuery innerJoinPaymentTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentTypes relation
 *
 * @method     ChildPaymentsQuery joinWithPaymentTypes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentTypes relation
 *
 * @method     ChildPaymentsQuery leftJoinWithPaymentTypes() Adds a LEFT JOIN clause and with to the query using the PaymentTypes relation
 * @method     ChildPaymentsQuery rightJoinWithPaymentTypes() Adds a RIGHT JOIN clause and with to the query using the PaymentTypes relation
 * @method     ChildPaymentsQuery innerJoinWithPaymentTypes() Adds a INNER JOIN clause and with to the query using the PaymentTypes relation
 *
 * @method     ChildPaymentsQuery leftJoinPaymentsCoupons($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentsCoupons relation
 * @method     ChildPaymentsQuery rightJoinPaymentsCoupons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentsCoupons relation
 * @method     ChildPaymentsQuery innerJoinPaymentsCoupons($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentsCoupons relation
 *
 * @method     ChildPaymentsQuery joinWithPaymentsCoupons($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentsCoupons relation
 *
 * @method     ChildPaymentsQuery leftJoinWithPaymentsCoupons() Adds a LEFT JOIN clause and with to the query using the PaymentsCoupons relation
 * @method     ChildPaymentsQuery rightJoinWithPaymentsCoupons() Adds a RIGHT JOIN clause and with to the query using the PaymentsCoupons relation
 * @method     ChildPaymentsQuery innerJoinWithPaymentsCoupons() Adds a INNER JOIN clause and with to the query using the PaymentsCoupons relation
 *
 * @method     \API\Models\Invoice\InvoicesQuery|\API\Models\Payment\PaymentTypesQuery|\API\Models\Payment\PaymentsCouponsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPayments findOne(ConnectionInterface $con = null) Return the first ChildPayments matching the query
 * @method     ChildPayments findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPayments matching the query, or a new ChildPayments object populated from the query conditions when no match is found
 *
 * @method     ChildPayments findOneByPaymentid(int $paymentid) Return the first ChildPayments filtered by the paymentid column
 * @method     ChildPayments findOneByPaymentTypeid(int $payment_typeid) Return the first ChildPayments filtered by the payment_typeid column
 * @method     ChildPayments findOneByInvoiceid(int $invoiceid) Return the first ChildPayments filtered by the invoiceid column
 * @method     ChildPayments findOneByDate(string $date) Return the first ChildPayments filtered by the date column
 * @method     ChildPayments findOneByAmount(string $amount) Return the first ChildPayments filtered by the amount column
 * @method     ChildPayments findOneByCanceled(string $canceled) Return the first ChildPayments filtered by the canceled column *

 * @method     ChildPayments requirePk($key, ConnectionInterface $con = null) Return the ChildPayments by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayments requireOne(ConnectionInterface $con = null) Return the first ChildPayments matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPayments requireOneByPaymentid(int $paymentid) Return the first ChildPayments filtered by the paymentid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayments requireOneByPaymentTypeid(int $payment_typeid) Return the first ChildPayments filtered by the payment_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayments requireOneByInvoiceid(int $invoiceid) Return the first ChildPayments filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayments requireOneByDate(string $date) Return the first ChildPayments filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayments requireOneByAmount(string $amount) Return the first ChildPayments filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayments requireOneByCanceled(string $canceled) Return the first ChildPayments filtered by the canceled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPayments[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPayments objects based on current ModelCriteria
 * @method     ChildPayments[]|ObjectCollection findByPaymentid(int $paymentid) Return ChildPayments objects filtered by the paymentid column
 * @method     ChildPayments[]|ObjectCollection findByPaymentTypeid(int $payment_typeid) Return ChildPayments objects filtered by the payment_typeid column
 * @method     ChildPayments[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildPayments objects filtered by the invoiceid column
 * @method     ChildPayments[]|ObjectCollection findByDate(string $date) Return ChildPayments objects filtered by the date column
 * @method     ChildPayments[]|ObjectCollection findByAmount(string $amount) Return ChildPayments objects filtered by the amount column
 * @method     ChildPayments[]|ObjectCollection findByCanceled(string $canceled) Return ChildPayments objects filtered by the canceled column
 * @method     ChildPayments[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\PaymentsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\Payments', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentsQuery) {
            return $criteria;
        }
        $query = new ChildPaymentsQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$paymentid, $payment_typeid, $invoiceid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPayments|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildPayments A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT paymentid, payment_typeid, invoiceid, date, amount, canceled FROM payments WHERE paymentid = :p0 AND payment_typeid = :p1 AND invoiceid = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPayments $obj */
            $obj = new ChildPayments();
            $obj->hydrate($row);
            PaymentsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildPayments|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PaymentsTableMap::COL_PAYMENTID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PaymentsTableMap::COL_PAYMENT_TYPEID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(PaymentsTableMap::COL_INVOICEID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PaymentsTableMap::COL_PAYMENTID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PaymentsTableMap::COL_PAYMENT_TYPEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(PaymentsTableMap::COL_INVOICEID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
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
     * @param     mixed $paymentid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByPaymentid($paymentid = null, $comparison = null)
    {
        if (is_array($paymentid)) {
            $useMinMax = false;
            if (isset($paymentid['min'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_PAYMENTID, $paymentid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentid['max'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_PAYMENTID, $paymentid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsTableMap::COL_PAYMENTID, $paymentid, $comparison);
    }

    /**
     * Filter the query on the payment_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentTypeid(1234); // WHERE payment_typeid = 1234
     * $query->filterByPaymentTypeid(array(12, 34)); // WHERE payment_typeid IN (12, 34)
     * $query->filterByPaymentTypeid(array('min' => 12)); // WHERE payment_typeid > 12
     * </code>
     *
     * @see       filterByPaymentTypes()
     *
     * @param     mixed $paymentTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByPaymentTypeid($paymentTypeid = null, $comparison = null)
    {
        if (is_array($paymentTypeid)) {
            $useMinMax = false;
            if (isset($paymentTypeid['min'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentTypeid['max'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsTableMap::COL_PAYMENT_TYPEID, $paymentTypeid, $comparison);
    }

    /**
     * Filter the query on the invoiceid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceid(1234); // WHERE invoiceid = 1234
     * $query->filterByInvoiceid(array(12, 34)); // WHERE invoiceid IN (12, 34)
     * $query->filterByInvoiceid(array('min' => 12)); // WHERE invoiceid > 12
     * </code>
     *
     * @see       filterByInvoices()
     *
     * @param     mixed $invoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsTableMap::COL_INVOICEID, $invoiceid, $comparison);
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
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the canceled column
     *
     * Example usage:
     * <code>
     * $query->filterByCanceled('2011-03-14'); // WHERE canceled = '2011-03-14'
     * $query->filterByCanceled('now'); // WHERE canceled = '2011-03-14'
     * $query->filterByCanceled(array('max' => 'yesterday')); // WHERE canceled > '2011-03-13'
     * </code>
     *
     * @param     mixed $canceled The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByCanceled($canceled = null, $comparison = null)
    {
        if (is_array($canceled)) {
            $useMinMax = false;
            if (isset($canceled['min'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_CANCELED, $canceled['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canceled['max'])) {
                $this->addUsingAlias(PaymentsTableMap::COL_CANCELED, $canceled['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentsTableMap::COL_CANCELED, $canceled, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoices object
     *
     * @param \API\Models\Invoice\Invoices|ObjectCollection $invoices The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByInvoices($invoices, $comparison = null)
    {
        if ($invoices instanceof \API\Models\Invoice\Invoices) {
            return $this
                ->addUsingAlias(PaymentsTableMap::COL_INVOICEID, $invoices->getInvoiceid(), $comparison);
        } elseif ($invoices instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentsTableMap::COL_INVOICEID, $invoices->toKeyValue('Invoiceid', 'Invoiceid'), $comparison);
        } else {
            throw new PropelException('filterByInvoices() only accepts arguments of type \API\Models\Invoice\Invoices or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invoices relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function joinInvoices($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Invoices');

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
            $this->addJoinObject($join, 'Invoices');
        }

        return $this;
    }

    /**
     * Use the Invoices relation Invoices object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoicesQuery A secondary query class using the current class as primary query
     */
    public function useInvoicesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoices($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoices', '\API\Models\Invoice\InvoicesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentTypes object
     *
     * @param \API\Models\Payment\PaymentTypes|ObjectCollection $paymentTypes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByPaymentTypes($paymentTypes, $comparison = null)
    {
        if ($paymentTypes instanceof \API\Models\Payment\PaymentTypes) {
            return $this
                ->addUsingAlias(PaymentsTableMap::COL_PAYMENT_TYPEID, $paymentTypes->getIdpaymentTypeid(), $comparison);
        } elseif ($paymentTypes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentsTableMap::COL_PAYMENT_TYPEID, $paymentTypes->toKeyValue('PrimaryKey', 'IdpaymentTypeid'), $comparison);
        } else {
            throw new PropelException('filterByPaymentTypes() only accepts arguments of type \API\Models\Payment\PaymentTypes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function joinPaymentTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentTypes');

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
            $this->addJoinObject($join, 'PaymentTypes');
        }

        return $this;
    }

    /**
     * Use the PaymentTypes relation PaymentTypes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentTypesQuery A secondary query class using the current class as primary query
     */
    public function usePaymentTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentTypes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentTypes', '\API\Models\Payment\PaymentTypesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentsCoupons object
     *
     * @param \API\Models\Payment\PaymentsCoupons|ObjectCollection $paymentsCoupons the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPaymentsQuery The current query, for fluid interface
     */
    public function filterByPaymentsCoupons($paymentsCoupons, $comparison = null)
    {
        if ($paymentsCoupons instanceof \API\Models\Payment\PaymentsCoupons) {
            return $this
                ->addUsingAlias(PaymentsTableMap::COL_PAYMENTID, $paymentsCoupons->getPaymentid(), $comparison);
        } elseif ($paymentsCoupons instanceof ObjectCollection) {
            return $this
                ->usePaymentsCouponsQuery()
                ->filterByPrimaryKeys($paymentsCoupons->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPaymentsCoupons() only accepts arguments of type \API\Models\Payment\PaymentsCoupons or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentsCoupons relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function joinPaymentsCoupons($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentsCoupons');

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
            $this->addJoinObject($join, 'PaymentsCoupons');
        }

        return $this;
    }

    /**
     * Use the PaymentsCoupons relation PaymentsCoupons object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentsCouponsQuery A secondary query class using the current class as primary query
     */
    public function usePaymentsCouponsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentsCoupons($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentsCoupons', '\API\Models\Payment\PaymentsCouponsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPayments $payments Object to remove from the list of results
     *
     * @return $this|ChildPaymentsQuery The current query, for fluid interface
     */
    public function prune($payments = null)
    {
        if ($payments) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PaymentsTableMap::COL_PAYMENTID), $payments->getPaymentid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PaymentsTableMap::COL_PAYMENT_TYPEID), $payments->getPaymentTypeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(PaymentsTableMap::COL_INVOICEID), $payments->getInvoiceid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payments table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentsTableMap::clearInstancePool();
            PaymentsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentsQuery
