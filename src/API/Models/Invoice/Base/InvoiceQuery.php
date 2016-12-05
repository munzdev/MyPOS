<?php

namespace API\Models\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Invoice\Invoice as ChildInvoice;
use API\Models\Invoice\InvoiceQuery as ChildInvoiceQuery;
use API\Models\Invoice\Map\InvoiceTableMap;
use API\Models\Payment\Payment;
use API\Models\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoice' table.
 *
 *
 *
 * @method     ChildInvoiceQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildInvoiceQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildInvoiceQuery orderByCashierUserid($order = Criteria::ASC) Order by the cashier_userid column
 * @method     ChildInvoiceQuery orderByCustomerid($order = Criteria::ASC) Order by the customerid column
 * @method     ChildInvoiceQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildInvoiceQuery orderByCanceled($order = Criteria::ASC) Order by the canceled column
 * @method     ChildInvoiceQuery orderByPaymentFinished($order = Criteria::ASC) Order by the payment_finished column
 *
 * @method     ChildInvoiceQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildInvoiceQuery groupByEventid() Group by the eventid column
 * @method     ChildInvoiceQuery groupByCashierUserid() Group by the cashier_userid column
 * @method     ChildInvoiceQuery groupByCustomerid() Group by the customerid column
 * @method     ChildInvoiceQuery groupByDate() Group by the date column
 * @method     ChildInvoiceQuery groupByCanceled() Group by the canceled column
 * @method     ChildInvoiceQuery groupByPaymentFinished() Group by the payment_finished column
 *
 * @method     ChildInvoiceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvoiceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvoiceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvoiceQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvoiceQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvoiceQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvoiceQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildInvoiceQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildInvoiceQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildInvoiceQuery joinWithCustomer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Customer relation
 *
 * @method     ChildInvoiceQuery leftJoinWithCustomer() Adds a LEFT JOIN clause and with to the query using the Customer relation
 * @method     ChildInvoiceQuery rightJoinWithCustomer() Adds a RIGHT JOIN clause and with to the query using the Customer relation
 * @method     ChildInvoiceQuery innerJoinWithCustomer() Adds a INNER JOIN clause and with to the query using the Customer relation
 *
 * @method     ChildInvoiceQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildInvoiceQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildInvoiceQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildInvoiceQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildInvoiceQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildInvoiceQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildInvoiceQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildInvoiceQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildInvoiceQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildInvoiceQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildInvoiceQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildInvoiceQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildInvoiceQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildInvoiceQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildInvoiceQuery leftJoinInvoiceItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery rightJoinInvoiceItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery innerJoinInvoiceItem($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceItem relation
 *
 * @method     ChildInvoiceQuery joinWithInvoiceItem($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceItem relation
 *
 * @method     ChildInvoiceQuery leftJoinWithInvoiceItem() Adds a LEFT JOIN clause and with to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery rightJoinWithInvoiceItem() Adds a RIGHT JOIN clause and with to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery innerJoinWithInvoiceItem() Adds a INNER JOIN clause and with to the query using the InvoiceItem relation
 *
 * @method     ChildInvoiceQuery leftJoinPayment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payment relation
 * @method     ChildInvoiceQuery rightJoinPayment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payment relation
 * @method     ChildInvoiceQuery innerJoinPayment($relationAlias = null) Adds a INNER JOIN clause to the query using the Payment relation
 *
 * @method     ChildInvoiceQuery joinWithPayment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payment relation
 *
 * @method     ChildInvoiceQuery leftJoinWithPayment() Adds a LEFT JOIN clause and with to the query using the Payment relation
 * @method     ChildInvoiceQuery rightJoinWithPayment() Adds a RIGHT JOIN clause and with to the query using the Payment relation
 * @method     ChildInvoiceQuery innerJoinWithPayment() Adds a INNER JOIN clause and with to the query using the Payment relation
 *
 * @method     \API\Models\Invoice\CustomerQuery|\API\Models\Event\EventQuery|\API\Models\User\UserQuery|\API\Models\Invoice\InvoiceItemQuery|\API\Models\Payment\PaymentQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvoice findOne(ConnectionInterface $con = null) Return the first ChildInvoice matching the query
 * @method     ChildInvoice findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoice matching the query, or a new ChildInvoice object populated from the query conditions when no match is found
 *
 * @method     ChildInvoice findOneByInvoiceid(int $invoiceid) Return the first ChildInvoice filtered by the invoiceid column
 * @method     ChildInvoice findOneByEventid(int $eventid) Return the first ChildInvoice filtered by the eventid column
 * @method     ChildInvoice findOneByCashierUserid(int $cashier_userid) Return the first ChildInvoice filtered by the cashier_userid column
 * @method     ChildInvoice findOneByCustomerid(int $customerid) Return the first ChildInvoice filtered by the customerid column
 * @method     ChildInvoice findOneByDate(string $date) Return the first ChildInvoice filtered by the date column
 * @method     ChildInvoice findOneByCanceled(string $canceled) Return the first ChildInvoice filtered by the canceled column
 * @method     ChildInvoice findOneByPaymentFinished(string $payment_finished) Return the first ChildInvoice filtered by the payment_finished column *

 * @method     ChildInvoice requirePk($key, ConnectionInterface $con = null) Return the ChildInvoice by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOne(ConnectionInterface $con = null) Return the first ChildInvoice matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoice requireOneByInvoiceid(int $invoiceid) Return the first ChildInvoice filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByEventid(int $eventid) Return the first ChildInvoice filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByCashierUserid(int $cashier_userid) Return the first ChildInvoice filtered by the cashier_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByCustomerid(int $customerid) Return the first ChildInvoice filtered by the customerid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByDate(string $date) Return the first ChildInvoice filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByCanceled(string $canceled) Return the first ChildInvoice filtered by the canceled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByPaymentFinished(string $payment_finished) Return the first ChildInvoice filtered by the payment_finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoice[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoice objects based on current ModelCriteria
 * @method     ChildInvoice[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildInvoice objects filtered by the invoiceid column
 * @method     ChildInvoice[]|ObjectCollection findByEventid(int $eventid) Return ChildInvoice objects filtered by the eventid column
 * @method     ChildInvoice[]|ObjectCollection findByCashierUserid(int $cashier_userid) Return ChildInvoice objects filtered by the cashier_userid column
 * @method     ChildInvoice[]|ObjectCollection findByCustomerid(int $customerid) Return ChildInvoice objects filtered by the customerid column
 * @method     ChildInvoice[]|ObjectCollection findByDate(string $date) Return ChildInvoice objects filtered by the date column
 * @method     ChildInvoice[]|ObjectCollection findByCanceled(string $canceled) Return ChildInvoice objects filtered by the canceled column
 * @method     ChildInvoice[]|ObjectCollection findByPaymentFinished(string $payment_finished) Return ChildInvoice objects filtered by the payment_finished column
 * @method     ChildInvoice[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InvoiceQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Invoice\Base\InvoiceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Invoice\\Invoice', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoiceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvoiceQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoiceQuery) {
            return $criteria;
        }
        $query = new ChildInvoiceQuery();
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
     * @return ChildInvoice|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoiceTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoiceTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInvoice A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoiceid, eventid, cashier_userid, customerid, date, canceled, payment_finished FROM invoice WHERE invoiceid = :p0';
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
            /** @var ChildInvoice $obj */
            $obj = new ChildInvoice();
            $obj->hydrate($row);
            InvoiceTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInvoice|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $keys, Criteria::IN);
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
     * @param     mixed $invoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceid, $comparison);
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
     * @see       filterByEvent()
     *
     * @param     mixed $eventid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_EVENTID, $eventid, $comparison);
    }

    /**
     * Filter the query on the cashier_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByCashierUserid(1234); // WHERE cashier_userid = 1234
     * $query->filterByCashierUserid(array(12, 34)); // WHERE cashier_userid IN (12, 34)
     * $query->filterByCashierUserid(array('min' => 12)); // WHERE cashier_userid > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $cashierUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByCashierUserid($cashierUserid = null, $comparison = null)
    {
        if (is_array($cashierUserid)) {
            $useMinMax = false;
            if (isset($cashierUserid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CASHIER_USERID, $cashierUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cashierUserid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CASHIER_USERID, $cashierUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_CASHIER_USERID, $cashierUserid, $comparison);
    }

    /**
     * Filter the query on the customerid column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerid(1234); // WHERE customerid = 1234
     * $query->filterByCustomerid(array(12, 34)); // WHERE customerid IN (12, 34)
     * $query->filterByCustomerid(array('min' => 12)); // WHERE customerid > 12
     * </code>
     *
     * @see       filterByCustomer()
     *
     * @param     mixed $customerid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByCustomerid($customerid = null, $comparison = null)
    {
        if (is_array($customerid)) {
            $useMinMax = false;
            if (isset($customerid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CUSTOMERID, $customerid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CUSTOMERID, $customerid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_CUSTOMERID, $customerid, $comparison);
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByCanceled($canceled = null, $comparison = null)
    {
        if (is_array($canceled)) {
            $useMinMax = false;
            if (isset($canceled['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CANCELED, $canceled['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canceled['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CANCELED, $canceled['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_CANCELED, $canceled, $comparison);
    }

    /**
     * Filter the query on the payment_finished column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentFinished('2011-03-14'); // WHERE payment_finished = '2011-03-14'
     * $query->filterByPaymentFinished('now'); // WHERE payment_finished = '2011-03-14'
     * $query->filterByPaymentFinished(array('max' => 'yesterday')); // WHERE payment_finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $paymentFinished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPaymentFinished($paymentFinished = null, $comparison = null)
    {
        if (is_array($paymentFinished)) {
            $useMinMax = false;
            if (isset($paymentFinished['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_PAYMENT_FINISHED, $paymentFinished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentFinished['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_PAYMENT_FINISHED, $paymentFinished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_PAYMENT_FINISHED, $paymentFinished, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Customer object
     *
     * @param \API\Models\Invoice\Customer|ObjectCollection $customer The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByCustomer($customer, $comparison = null)
    {
        if ($customer instanceof \API\Models\Invoice\Customer) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CUSTOMERID, $customer->getCustomerid(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CUSTOMERID, $customer->toKeyValue('PrimaryKey', 'Customerid'), $comparison);
        } else {
            throw new PropelException('filterByCustomer() only accepts arguments of type \API\Models\Invoice\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Customer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinCustomer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Customer');

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
            $this->addJoinObject($join, 'Customer');
        }

        return $this;
    }

    /**
     * Use the Customer relation Customer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\API\Models\Invoice\CustomerQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \API\Models\Event\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * @param \API\Models\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\User\User) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CASHIER_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CASHIER_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
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
     * @return \API\Models\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\InvoiceItem object
     *
     * @param \API\Models\Invoice\InvoiceItem|ObjectCollection $invoiceItem the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceItem($invoiceItem, $comparison = null)
    {
        if ($invoiceItem instanceof \API\Models\Invoice\InvoiceItem) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceItem->getInvoiceid(), $comparison);
        } elseif ($invoiceItem instanceof ObjectCollection) {
            return $this
                ->useInvoiceItemQuery()
                ->filterByPrimaryKeys($invoiceItem->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceItem() only accepts arguments of type \API\Models\Invoice\InvoiceItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinInvoiceItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceItem');

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
            $this->addJoinObject($join, 'InvoiceItem');
        }

        return $this;
    }

    /**
     * Use the InvoiceItem relation InvoiceItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceItemQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceItem', '\API\Models\Invoice\InvoiceItemQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\Payment object
     *
     * @param \API\Models\Payment\Payment|ObjectCollection $payment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPayment($payment, $comparison = null)
    {
        if ($payment instanceof \API\Models\Payment\Payment) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $payment->getInvoiceid(), $comparison);
        } elseif ($payment instanceof ObjectCollection) {
            return $this
                ->usePaymentQuery()
                ->filterByPrimaryKeys($payment->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildInvoice $invoice Object to remove from the list of results
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function prune($invoice = null)
    {
        if ($invoice) {
            $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoice->getInvoiceid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoice table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvoiceTableMap::clearInstancePool();
            InvoiceTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoiceTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvoiceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvoiceTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InvoiceQuery
