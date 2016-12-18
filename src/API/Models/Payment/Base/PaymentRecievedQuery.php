<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Invoice\Invoice;
use API\Models\Payment\PaymentRecieved as ChildPaymentRecieved;
use API\Models\Payment\PaymentRecievedQuery as ChildPaymentRecievedQuery;
use API\Models\Payment\Map\PaymentRecievedTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payment_recieved' table.
 *
 *
 *
 * @method     ChildPaymentRecievedQuery orderByPaymentRecievedid($order = Criteria::ASC) Order by the payment_recievedid column
 * @method     ChildPaymentRecievedQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildPaymentRecievedQuery orderByPaymentTypeid($order = Criteria::ASC) Order by the payment_typeid column
 * @method     ChildPaymentRecievedQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildPaymentRecievedQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 *
 * @method     ChildPaymentRecievedQuery groupByPaymentRecievedid() Group by the payment_recievedid column
 * @method     ChildPaymentRecievedQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildPaymentRecievedQuery groupByPaymentTypeid() Group by the payment_typeid column
 * @method     ChildPaymentRecievedQuery groupByDate() Group by the date column
 * @method     ChildPaymentRecievedQuery groupByAmount() Group by the amount column
 *
 * @method     ChildPaymentRecievedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentRecievedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentRecievedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentRecievedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentRecievedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentRecievedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentRecievedQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method     ChildPaymentRecievedQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method     ChildPaymentRecievedQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method     ChildPaymentRecievedQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method     ChildPaymentRecievedQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method     ChildPaymentRecievedQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method     ChildPaymentRecievedQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method     ChildPaymentRecievedQuery leftJoinPaymentType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentType relation
 * @method     ChildPaymentRecievedQuery rightJoinPaymentType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentType relation
 * @method     ChildPaymentRecievedQuery innerJoinPaymentType($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentType relation
 *
 * @method     ChildPaymentRecievedQuery joinWithPaymentType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentType relation
 *
 * @method     ChildPaymentRecievedQuery leftJoinWithPaymentType() Adds a LEFT JOIN clause and with to the query using the PaymentType relation
 * @method     ChildPaymentRecievedQuery rightJoinWithPaymentType() Adds a RIGHT JOIN clause and with to the query using the PaymentType relation
 * @method     ChildPaymentRecievedQuery innerJoinWithPaymentType() Adds a INNER JOIN clause and with to the query using the PaymentType relation
 *
 * @method     ChildPaymentRecievedQuery leftJoinPaymentCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentCoupon relation
 * @method     ChildPaymentRecievedQuery rightJoinPaymentCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentCoupon relation
 * @method     ChildPaymentRecievedQuery innerJoinPaymentCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentCoupon relation
 *
 * @method     ChildPaymentRecievedQuery joinWithPaymentCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentCoupon relation
 *
 * @method     ChildPaymentRecievedQuery leftJoinWithPaymentCoupon() Adds a LEFT JOIN clause and with to the query using the PaymentCoupon relation
 * @method     ChildPaymentRecievedQuery rightJoinWithPaymentCoupon() Adds a RIGHT JOIN clause and with to the query using the PaymentCoupon relation
 * @method     ChildPaymentRecievedQuery innerJoinWithPaymentCoupon() Adds a INNER JOIN clause and with to the query using the PaymentCoupon relation
 *
 * @method     \API\Models\Invoice\InvoiceQuery|\API\Models\Payment\PaymentTypeQuery|\API\Models\Payment\PaymentCouponQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPaymentRecieved findOne(ConnectionInterface $con = null) Return the first ChildPaymentRecieved matching the query
 * @method     ChildPaymentRecieved findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPaymentRecieved matching the query, or a new ChildPaymentRecieved object populated from the query conditions when no match is found
 *
 * @method     ChildPaymentRecieved findOneByPaymentRecievedid(int $payment_recievedid) Return the first ChildPaymentRecieved filtered by the payment_recievedid column
 * @method     ChildPaymentRecieved findOneByInvoiceid(int $invoiceid) Return the first ChildPaymentRecieved filtered by the invoiceid column
 * @method     ChildPaymentRecieved findOneByPaymentTypeid(int $payment_typeid) Return the first ChildPaymentRecieved filtered by the payment_typeid column
 * @method     ChildPaymentRecieved findOneByDate(string $date) Return the first ChildPaymentRecieved filtered by the date column
 * @method     ChildPaymentRecieved findOneByAmount(string $amount) Return the first ChildPaymentRecieved filtered by the amount column *

 * @method     ChildPaymentRecieved requirePk($key, ConnectionInterface $con = null) Return the ChildPaymentRecieved by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentRecieved requireOne(ConnectionInterface $con = null) Return the first ChildPaymentRecieved matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentRecieved requireOneByPaymentRecievedid(int $payment_recievedid) Return the first ChildPaymentRecieved filtered by the payment_recievedid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentRecieved requireOneByInvoiceid(int $invoiceid) Return the first ChildPaymentRecieved filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentRecieved requireOneByPaymentTypeid(int $payment_typeid) Return the first ChildPaymentRecieved filtered by the payment_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentRecieved requireOneByDate(string $date) Return the first ChildPaymentRecieved filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentRecieved requireOneByAmount(string $amount) Return the first ChildPaymentRecieved filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentRecieved[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPaymentRecieved objects based on current ModelCriteria
 * @method     ChildPaymentRecieved[]|ObjectCollection findByPaymentRecievedid(int $payment_recievedid) Return ChildPaymentRecieved objects filtered by the payment_recievedid column
 * @method     ChildPaymentRecieved[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildPaymentRecieved objects filtered by the invoiceid column
 * @method     ChildPaymentRecieved[]|ObjectCollection findByPaymentTypeid(int $payment_typeid) Return ChildPaymentRecieved objects filtered by the payment_typeid column
 * @method     ChildPaymentRecieved[]|ObjectCollection findByDate(string $date) Return ChildPaymentRecieved objects filtered by the date column
 * @method     ChildPaymentRecieved[]|ObjectCollection findByAmount(string $amount) Return ChildPaymentRecieved objects filtered by the amount column
 * @method     ChildPaymentRecieved[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentRecievedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\PaymentRecievedQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\PaymentRecieved', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentRecievedQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentRecievedQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentRecievedQuery) {
            return $criteria;
        }
        $query = new ChildPaymentRecievedQuery();
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
     * @return ChildPaymentRecieved|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentRecievedTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentRecievedTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPaymentRecieved A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT payment_recievedid, invoiceid, payment_typeid, date, amount FROM payment_recieved WHERE payment_recievedid = :p0';
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
            /** @var ChildPaymentRecieved $obj */
            $obj = new ChildPaymentRecieved();
            $obj->hydrate($row);
            PaymentRecievedTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPaymentRecieved|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $keys, Criteria::IN);
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
     * @param     mixed $paymentRecievedid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByPaymentRecievedid($paymentRecievedid = null, $comparison = null)
    {
        if (is_array($paymentRecievedid)) {
            $useMinMax = false;
            if (isset($paymentRecievedid['min'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecievedid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentRecievedid['max'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecievedid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecievedid, $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_INVOICEID, $invoiceid, $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByPaymentTypeid($paymentTypeid = null, $comparison = null)
    {
        if (is_array($paymentTypeid)) {
            $useMinMax = false;
            if (isset($paymentTypeid['min'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentTypeid['max'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_TYPEID, $paymentTypeid, $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(PaymentRecievedTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentRecievedTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(PaymentRecievedTableMap::COL_INVOICEID, $invoice->getInvoiceid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentRecievedTableMap::COL_INVOICEID, $invoice->toKeyValue('PrimaryKey', 'Invoiceid'), $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
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
     * @return ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByPaymentType($paymentType, $comparison = null)
    {
        if ($paymentType instanceof \API\Models\Payment\PaymentType) {
            return $this
                ->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_TYPEID, $paymentType->getPaymentTypeid(), $comparison);
        } elseif ($paymentType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_TYPEID, $paymentType->toKeyValue('PrimaryKey', 'PaymentTypeid'), $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
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
     * @return ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function filterByPaymentCoupon($paymentCoupon, $comparison = null)
    {
        if ($paymentCoupon instanceof \API\Models\Payment\PaymentCoupon) {
            return $this
                ->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $paymentCoupon->getPaymentRecievedid(), $comparison);
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
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
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
     * @return ChildPaymentRecievedQuery The current query, for fluid interface
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
     * @param   ChildPaymentRecieved $paymentRecieved Object to remove from the list of results
     *
     * @return $this|ChildPaymentRecievedQuery The current query, for fluid interface
     */
    public function prune($paymentRecieved = null)
    {
        if ($paymentRecieved) {
            $this->addUsingAlias(PaymentRecievedTableMap::COL_PAYMENT_RECIEVEDID, $paymentRecieved->getPaymentRecievedid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payment_recieved table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentRecievedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentRecievedTableMap::clearInstancePool();
            PaymentRecievedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentRecievedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentRecievedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentRecievedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentRecievedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentRecievedQuery
