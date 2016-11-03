<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Invoice\Invoice;
use API\Models\Payment\Payment as ChildPayment;
use API\Models\Payment\PaymentQuery as ChildPaymentQuery;
use API\Models\Payment\Map\PaymentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payment' table.
 *
 *
 *
 * @method     ChildPaymentQuery orderByPaymentid($order = Criteria::ASC) Order by the paymentid column
 * @method     ChildPaymentQuery orderByPaymentTypeid($order = Criteria::ASC) Order by the payment_typeid column
 * @method     ChildPaymentQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildPaymentQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildPaymentQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildPaymentQuery orderByCanceled($order = Criteria::ASC) Order by the canceled column
 *
 * @method     ChildPaymentQuery groupByPaymentid() Group by the paymentid column
 * @method     ChildPaymentQuery groupByPaymentTypeid() Group by the payment_typeid column
 * @method     ChildPaymentQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildPaymentQuery groupByDate() Group by the date column
 * @method     ChildPaymentQuery groupByAmount() Group by the amount column
 * @method     ChildPaymentQuery groupByCanceled() Group by the canceled column
 *
 * @method     ChildPaymentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method     ChildPaymentQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method     ChildPaymentQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method     ChildPaymentQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method     ChildPaymentQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method     ChildPaymentQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method     ChildPaymentQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method     ChildPaymentQuery leftJoinPaymentType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentType relation
 * @method     ChildPaymentQuery rightJoinPaymentType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentType relation
 * @method     ChildPaymentQuery innerJoinPaymentType($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentType relation
 *
 * @method     ChildPaymentQuery joinWithPaymentType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentType relation
 *
 * @method     ChildPaymentQuery leftJoinWithPaymentType() Adds a LEFT JOIN clause and with to the query using the PaymentType relation
 * @method     ChildPaymentQuery rightJoinWithPaymentType() Adds a RIGHT JOIN clause and with to the query using the PaymentType relation
 * @method     ChildPaymentQuery innerJoinWithPaymentType() Adds a INNER JOIN clause and with to the query using the PaymentType relation
 *
 * @method     ChildPaymentQuery leftJoinPaymentCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentCoupon relation
 * @method     ChildPaymentQuery rightJoinPaymentCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentCoupon relation
 * @method     ChildPaymentQuery innerJoinPaymentCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentCoupon relation
 *
 * @method     ChildPaymentQuery joinWithPaymentCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentCoupon relation
 *
 * @method     ChildPaymentQuery leftJoinWithPaymentCoupon() Adds a LEFT JOIN clause and with to the query using the PaymentCoupon relation
 * @method     ChildPaymentQuery rightJoinWithPaymentCoupon() Adds a RIGHT JOIN clause and with to the query using the PaymentCoupon relation
 * @method     ChildPaymentQuery innerJoinWithPaymentCoupon() Adds a INNER JOIN clause and with to the query using the PaymentCoupon relation
 *
 * @method     \API\Models\Invoice\InvoiceQuery|\API\Models\Payment\PaymentTypeQuery|\API\Models\Payment\PaymentCouponQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPayment findOne(ConnectionInterface $con = null) Return the first ChildPayment matching the query
 * @method     ChildPayment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPayment matching the query, or a new ChildPayment object populated from the query conditions when no match is found
 *
 * @method     ChildPayment findOneByPaymentid(int $paymentid) Return the first ChildPayment filtered by the paymentid column
 * @method     ChildPayment findOneByPaymentTypeid(int $payment_typeid) Return the first ChildPayment filtered by the payment_typeid column
 * @method     ChildPayment findOneByInvoiceid(int $invoiceid) Return the first ChildPayment filtered by the invoiceid column
 * @method     ChildPayment findOneByDate(string $date) Return the first ChildPayment filtered by the date column
 * @method     ChildPayment findOneByAmount(string $amount) Return the first ChildPayment filtered by the amount column
 * @method     ChildPayment findOneByCanceled(string $canceled) Return the first ChildPayment filtered by the canceled column *

 * @method     ChildPayment requirePk($key, ConnectionInterface $con = null) Return the ChildPayment by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOne(ConnectionInterface $con = null) Return the first ChildPayment matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPayment requireOneByPaymentid(int $paymentid) Return the first ChildPayment filtered by the paymentid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByPaymentTypeid(int $payment_typeid) Return the first ChildPayment filtered by the payment_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByInvoiceid(int $invoiceid) Return the first ChildPayment filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByDate(string $date) Return the first ChildPayment filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByAmount(string $amount) Return the first ChildPayment filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByCanceled(string $canceled) Return the first ChildPayment filtered by the canceled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPayment[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPayment objects based on current ModelCriteria
 * @method     ChildPayment[]|ObjectCollection findByPaymentid(int $paymentid) Return ChildPayment objects filtered by the paymentid column
 * @method     ChildPayment[]|ObjectCollection findByPaymentTypeid(int $payment_typeid) Return ChildPayment objects filtered by the payment_typeid column
 * @method     ChildPayment[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildPayment objects filtered by the invoiceid column
 * @method     ChildPayment[]|ObjectCollection findByDate(string $date) Return ChildPayment objects filtered by the date column
 * @method     ChildPayment[]|ObjectCollection findByAmount(string $amount) Return ChildPayment objects filtered by the amount column
 * @method     ChildPayment[]|ObjectCollection findByCanceled(string $canceled) Return ChildPayment objects filtered by the canceled column
 * @method     ChildPayment[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\PaymentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\Payment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentQuery) {
            return $criteria;
        }
        $query = new ChildPaymentQuery();
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
     * @return ChildPayment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildPayment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT paymentid, payment_typeid, invoiceid, date, amount, canceled FROM payment WHERE paymentid = :p0 AND payment_typeid = :p1 AND invoiceid = :p2';
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
            /** @var ChildPayment $obj */
            $obj = new ChildPayment();
            $obj->hydrate($row);
            PaymentTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildPayment|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PaymentTableMap::COL_PAYMENTID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_TYPEID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(PaymentTableMap::COL_INVOICEID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PaymentTableMap::COL_PAYMENTID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PaymentTableMap::COL_PAYMENT_TYPEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(PaymentTableMap::COL_INVOICEID, $key[2], Criteria::EQUAL);
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPaymentid($paymentid = null, $comparison = null)
    {
        if (is_array($paymentid)) {
            $useMinMax = false;
            if (isset($paymentid['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENTID, $paymentid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentid['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENTID, $paymentid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENTID, $paymentid, $comparison);
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
     * @see       filterByPaymentType()
     *
     * @param     mixed $paymentTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPaymentTypeid($paymentTypeid = null, $comparison = null)
    {
        if (is_array($paymentTypeid)) {
            $useMinMax = false;
            if (isset($paymentTypeid['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentTypeid['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_TYPEID, $paymentTypeid, $comparison);
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
     * @see       filterByInvoice()
     *
     * @param     mixed $invoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_INVOICEID, $invoiceid, $comparison);
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_AMOUNT, $amount, $comparison);
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByCanceled($canceled = null, $comparison = null)
    {
        if (is_array($canceled)) {
            $useMinMax = false;
            if (isset($canceled['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_CANCELED, $canceled['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canceled['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_CANCELED, $canceled['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_CANCELED, $canceled, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(PaymentTableMap::COL_INVOICEID, $invoice->getInvoiceid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentTableMap::COL_INVOICEID, $invoice->toKeyValue('Invoiceid', 'Invoiceid'), $comparison);
        } else {
            throw new PropelException('filterByInvoice() only accepts arguments of type \API\Models\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invoice relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function joinInvoice($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Invoice');

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
            $this->addJoinObject($join, 'Invoice');
        }

        return $this;
    }

    /**
     * Use the Invoice relation Invoice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoice', '\API\Models\Invoice\InvoiceQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentType object
     *
     * @param \API\Models\Payment\PaymentType|ObjectCollection $paymentType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPaymentType($paymentType, $comparison = null)
    {
        if ($paymentType instanceof \API\Models\Payment\PaymentType) {
            return $this
                ->addUsingAlias(PaymentTableMap::COL_PAYMENT_TYPEID, $paymentType->getPaymentTypeid(), $comparison);
        } elseif ($paymentType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentTableMap::COL_PAYMENT_TYPEID, $paymentType->toKeyValue('PrimaryKey', 'PaymentTypeid'), $comparison);
        } else {
            throw new PropelException('filterByPaymentType() only accepts arguments of type \API\Models\Payment\PaymentType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function joinPaymentType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentType');

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
            $this->addJoinObject($join, 'PaymentType');
        }

        return $this;
    }

    /**
     * Use the PaymentType relation PaymentType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentTypeQuery A secondary query class using the current class as primary query
     */
    public function usePaymentTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentType', '\API\Models\Payment\PaymentTypeQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentCoupon object
     *
     * @param \API\Models\Payment\PaymentCoupon|ObjectCollection $paymentCoupon the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPaymentCoupon($paymentCoupon, $comparison = null)
    {
        if ($paymentCoupon instanceof \API\Models\Payment\PaymentCoupon) {
            return $this
                ->addUsingAlias(PaymentTableMap::COL_PAYMENTID, $paymentCoupon->getPaymentid(), $comparison);
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
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related Coupon object
     * using the payment_coupon table as cross reference
     *
     * @param Coupon $coupon the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByCoupon($coupon, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePaymentCouponQuery()
            ->filterByCoupon($coupon, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPayment $payment Object to remove from the list of results
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function prune($payment = null)
    {
        if ($payment) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PaymentTableMap::COL_PAYMENTID), $payment->getPaymentid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PaymentTableMap::COL_PAYMENT_TYPEID), $payment->getPaymentTypeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(PaymentTableMap::COL_INVOICEID), $payment->getInvoiceid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentTableMap::clearInstancePool();
            PaymentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentQuery
