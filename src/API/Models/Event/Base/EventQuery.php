<?php

namespace API\Models\Event\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlace;
use API\Models\Event\Event as ChildEvent;
use API\Models\Event\EventQuery as ChildEventQuery;
use API\Models\Event\Map\EventTableMap;
use API\Models\Invoice\Customer;
use API\Models\Invoice\Invoice;
use API\Models\Menu\MenuExtra;
use API\Models\Menu\MenuSize;
use API\Models\Menu\MenuType;
use API\Models\Payment\Coupon;
use API\Models\Payment\PaymentWarningType;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event' table.
 *
 *
 *
 * @method     ChildEventQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildEventQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildEventQuery orderByActive($order = Criteria::ASC) Order by the active column
 *
 * @method     ChildEventQuery groupByEventid() Group by the eventid column
 * @method     ChildEventQuery groupByName() Group by the name column
 * @method     ChildEventQuery groupByDate() Group by the date column
 * @method     ChildEventQuery groupByActive() Group by the active column
 *
 * @method     ChildEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventQuery leftJoinCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupon relation
 * @method     ChildEventQuery rightJoinCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupon relation
 * @method     ChildEventQuery innerJoinCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupon relation
 *
 * @method     ChildEventQuery joinWithCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupon relation
 *
 * @method     ChildEventQuery leftJoinWithCoupon() Adds a LEFT JOIN clause and with to the query using the Coupon relation
 * @method     ChildEventQuery rightJoinWithCoupon() Adds a RIGHT JOIN clause and with to the query using the Coupon relation
 * @method     ChildEventQuery innerJoinWithCoupon() Adds a INNER JOIN clause and with to the query using the Coupon relation
 *
 * @method     ChildEventQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildEventQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildEventQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildEventQuery joinWithCustomer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Customer relation
 *
 * @method     ChildEventQuery leftJoinWithCustomer() Adds a LEFT JOIN clause and with to the query using the Customer relation
 * @method     ChildEventQuery rightJoinWithCustomer() Adds a RIGHT JOIN clause and with to the query using the Customer relation
 * @method     ChildEventQuery innerJoinWithCustomer() Adds a INNER JOIN clause and with to the query using the Customer relation
 *
 * @method     ChildEventQuery leftJoinDistributionPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildEventQuery rightJoinDistributionPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildEventQuery innerJoinDistributionPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlace relation
 *
 * @method     ChildEventQuery joinWithDistributionPlace($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildEventQuery leftJoinWithDistributionPlace() Adds a LEFT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildEventQuery rightJoinWithDistributionPlace() Adds a RIGHT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildEventQuery innerJoinWithDistributionPlace() Adds a INNER JOIN clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildEventQuery leftJoinEventPrinter($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventPrinter relation
 * @method     ChildEventQuery rightJoinEventPrinter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventPrinter relation
 * @method     ChildEventQuery innerJoinEventPrinter($relationAlias = null) Adds a INNER JOIN clause to the query using the EventPrinter relation
 *
 * @method     ChildEventQuery joinWithEventPrinter($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventPrinter relation
 *
 * @method     ChildEventQuery leftJoinWithEventPrinter() Adds a LEFT JOIN clause and with to the query using the EventPrinter relation
 * @method     ChildEventQuery rightJoinWithEventPrinter() Adds a RIGHT JOIN clause and with to the query using the EventPrinter relation
 * @method     ChildEventQuery innerJoinWithEventPrinter() Adds a INNER JOIN clause and with to the query using the EventPrinter relation
 *
 * @method     ChildEventQuery leftJoinEventTable($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventTable relation
 * @method     ChildEventQuery rightJoinEventTable($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventTable relation
 * @method     ChildEventQuery innerJoinEventTable($relationAlias = null) Adds a INNER JOIN clause to the query using the EventTable relation
 *
 * @method     ChildEventQuery joinWithEventTable($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventTable relation
 *
 * @method     ChildEventQuery leftJoinWithEventTable() Adds a LEFT JOIN clause and with to the query using the EventTable relation
 * @method     ChildEventQuery rightJoinWithEventTable() Adds a RIGHT JOIN clause and with to the query using the EventTable relation
 * @method     ChildEventQuery innerJoinWithEventTable() Adds a INNER JOIN clause and with to the query using the EventTable relation
 *
 * @method     ChildEventQuery leftJoinEventUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventUser relation
 * @method     ChildEventQuery rightJoinEventUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventUser relation
 * @method     ChildEventQuery innerJoinEventUser($relationAlias = null) Adds a INNER JOIN clause to the query using the EventUser relation
 *
 * @method     ChildEventQuery joinWithEventUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventUser relation
 *
 * @method     ChildEventQuery leftJoinWithEventUser() Adds a LEFT JOIN clause and with to the query using the EventUser relation
 * @method     ChildEventQuery rightJoinWithEventUser() Adds a RIGHT JOIN clause and with to the query using the EventUser relation
 * @method     ChildEventQuery innerJoinWithEventUser() Adds a INNER JOIN clause and with to the query using the EventUser relation
 *
 * @method     ChildEventQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method     ChildEventQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method     ChildEventQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method     ChildEventQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method     ChildEventQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method     ChildEventQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method     ChildEventQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method     ChildEventQuery leftJoinMenuExtra($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuExtra relation
 * @method     ChildEventQuery rightJoinMenuExtra($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuExtra relation
 * @method     ChildEventQuery innerJoinMenuExtra($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuExtra relation
 *
 * @method     ChildEventQuery joinWithMenuExtra($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuExtra relation
 *
 * @method     ChildEventQuery leftJoinWithMenuExtra() Adds a LEFT JOIN clause and with to the query using the MenuExtra relation
 * @method     ChildEventQuery rightJoinWithMenuExtra() Adds a RIGHT JOIN clause and with to the query using the MenuExtra relation
 * @method     ChildEventQuery innerJoinWithMenuExtra() Adds a INNER JOIN clause and with to the query using the MenuExtra relation
 *
 * @method     ChildEventQuery leftJoinMenuSize($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuSize relation
 * @method     ChildEventQuery rightJoinMenuSize($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuSize relation
 * @method     ChildEventQuery innerJoinMenuSize($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuSize relation
 *
 * @method     ChildEventQuery joinWithMenuSize($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuSize relation
 *
 * @method     ChildEventQuery leftJoinWithMenuSize() Adds a LEFT JOIN clause and with to the query using the MenuSize relation
 * @method     ChildEventQuery rightJoinWithMenuSize() Adds a RIGHT JOIN clause and with to the query using the MenuSize relation
 * @method     ChildEventQuery innerJoinWithMenuSize() Adds a INNER JOIN clause and with to the query using the MenuSize relation
 *
 * @method     ChildEventQuery leftJoinMenuType($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuType relation
 * @method     ChildEventQuery rightJoinMenuType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuType relation
 * @method     ChildEventQuery innerJoinMenuType($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuType relation
 *
 * @method     ChildEventQuery joinWithMenuType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuType relation
 *
 * @method     ChildEventQuery leftJoinWithMenuType() Adds a LEFT JOIN clause and with to the query using the MenuType relation
 * @method     ChildEventQuery rightJoinWithMenuType() Adds a RIGHT JOIN clause and with to the query using the MenuType relation
 * @method     ChildEventQuery innerJoinWithMenuType() Adds a INNER JOIN clause and with to the query using the MenuType relation
 *
 * @method     ChildEventQuery leftJoinPaymentWarningType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentWarningType relation
 * @method     ChildEventQuery rightJoinPaymentWarningType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentWarningType relation
 * @method     ChildEventQuery innerJoinPaymentWarningType($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentWarningType relation
 *
 * @method     ChildEventQuery joinWithPaymentWarningType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentWarningType relation
 *
 * @method     ChildEventQuery leftJoinWithPaymentWarningType() Adds a LEFT JOIN clause and with to the query using the PaymentWarningType relation
 * @method     ChildEventQuery rightJoinWithPaymentWarningType() Adds a RIGHT JOIN clause and with to the query using the PaymentWarningType relation
 * @method     ChildEventQuery innerJoinWithPaymentWarningType() Adds a INNER JOIN clause and with to the query using the PaymentWarningType relation
 *
 * @method     \API\Models\Payment\CouponQuery|\API\Models\Invoice\CustomerQuery|\API\Models\DistributionPlace\DistributionPlaceQuery|\API\Models\Event\EventPrinterQuery|\API\Models\Event\EventTableQuery|\API\Models\Event\EventUserQuery|\API\Models\Invoice\InvoiceQuery|\API\Models\Menu\MenuExtraQuery|\API\Models\Menu\MenuSizeQuery|\API\Models\Menu\MenuTypeQuery|\API\Models\Payment\PaymentWarningTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEvent findOne(ConnectionInterface $con = null) Return the first ChildEvent matching the query
 * @method     ChildEvent findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEvent matching the query, or a new ChildEvent object populated from the query conditions when no match is found
 *
 * @method     ChildEvent findOneByEventid(int $eventid) Return the first ChildEvent filtered by the eventid column
 * @method     ChildEvent findOneByName(string $name) Return the first ChildEvent filtered by the name column
 * @method     ChildEvent findOneByDate(string $date) Return the first ChildEvent filtered by the date column
 * @method     ChildEvent findOneByActive(boolean $active) Return the first ChildEvent filtered by the active column *

 * @method     ChildEvent requirePk($key, ConnectionInterface $con = null) Return the ChildEvent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOne(ConnectionInterface $con = null) Return the first ChildEvent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent requireOneByEventid(int $eventid) Return the first ChildEvent filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByName(string $name) Return the first ChildEvent filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDate(string $date) Return the first ChildEvent filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByActive(boolean $active) Return the first ChildEvent filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 * @method     ChildEvent[]|ObjectCollection findByEventid(int $eventid) Return ChildEvent objects filtered by the eventid column
 * @method     ChildEvent[]|ObjectCollection findByName(string $name) Return ChildEvent objects filtered by the name column
 * @method     ChildEvent[]|ObjectCollection findByDate(string $date) Return ChildEvent objects filtered by the date column
 * @method     ChildEvent[]|ObjectCollection findByActive(boolean $active) Return ChildEvent objects filtered by the active column
 * @method     ChildEvent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Event\Base\EventQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Event\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventQuery) {
            return $criteria;
        }
        $query = new ChildEventQuery();
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEvent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT eventid, name, date, active FROM event WHERE eventid = :p0';
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
            /** @var ChildEvent $obj */
            $obj = new ChildEvent();
            $obj->hydrate($row);
            EventTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventTableMap::COL_EVENTID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventTableMap::COL_EVENTID, $keys, Criteria::IN);
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
     * @param     mixed $eventid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_EVENTID, $eventid, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(EventTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EventTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(true); // WHERE active = true
     * $query->filterByActive('yes'); // WHERE active = true
     * </code>
     *
     * @param     boolean|string $active The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Payment\Coupon object
     *
     * @param \API\Models\Payment\Coupon|ObjectCollection $coupon the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByCoupon($coupon, $comparison = null)
    {
        if ($coupon instanceof \API\Models\Payment\Coupon) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $coupon->getEventid(), $comparison);
        } elseif ($coupon instanceof ObjectCollection) {
            return $this
                ->useCouponQuery()
                ->filterByPrimaryKeys($coupon->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildEventQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Invoice\Customer object
     *
     * @param \API\Models\Invoice\Customer|ObjectCollection $customer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByCustomer($customer, $comparison = null)
    {
        if ($customer instanceof \API\Models\Invoice\Customer) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $customer->getEventid(), $comparison);
        } elseif ($customer instanceof ObjectCollection) {
            return $this
                ->useCustomerQuery()
                ->filterByPrimaryKeys($customer->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinCustomer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\API\Models\Invoice\CustomerQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlace object
     *
     * @param \API\Models\DistributionPlace\DistributionPlace|ObjectCollection $distributionPlace the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByDistributionPlace($distributionPlace, $comparison = null)
    {
        if ($distributionPlace instanceof \API\Models\DistributionPlace\DistributionPlace) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $distributionPlace->getEventid(), $comparison);
        } elseif ($distributionPlace instanceof ObjectCollection) {
            return $this
                ->useDistributionPlaceQuery()
                ->filterByPrimaryKeys($distributionPlace->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionPlace() only accepts arguments of type \API\Models\DistributionPlace\DistributionPlace or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlace relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinDistributionPlace($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlace');

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
            $this->addJoinObject($join, 'DistributionPlace');
        }

        return $this;
    }

    /**
     * Use the DistributionPlace relation DistributionPlace object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionPlaceQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlace($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlace', '\API\Models\DistributionPlace\DistributionPlaceQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventPrinter object
     *
     * @param \API\Models\Event\EventPrinter|ObjectCollection $eventPrinter the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventPrinter($eventPrinter, $comparison = null)
    {
        if ($eventPrinter instanceof \API\Models\Event\EventPrinter) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $eventPrinter->getEventid(), $comparison);
        } elseif ($eventPrinter instanceof ObjectCollection) {
            return $this
                ->useEventPrinterQuery()
                ->filterByPrimaryKeys($eventPrinter->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventPrinter() only accepts arguments of type \API\Models\Event\EventPrinter or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventPrinter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinEventPrinter($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventPrinter');

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
            $this->addJoinObject($join, 'EventPrinter');
        }

        return $this;
    }

    /**
     * Use the EventPrinter relation EventPrinter object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventPrinterQuery A secondary query class using the current class as primary query
     */
    public function useEventPrinterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventPrinter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventPrinter', '\API\Models\Event\EventPrinterQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventTable object
     *
     * @param \API\Models\Event\EventTable|ObjectCollection $eventTable the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventTable($eventTable, $comparison = null)
    {
        if ($eventTable instanceof \API\Models\Event\EventTable) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $eventTable->getEventid(), $comparison);
        } elseif ($eventTable instanceof ObjectCollection) {
            return $this
                ->useEventTableQuery()
                ->filterByPrimaryKeys($eventTable->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventTable() only accepts arguments of type \API\Models\Event\EventTable or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventTable relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinEventTable($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventTable');

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
            $this->addJoinObject($join, 'EventTable');
        }

        return $this;
    }

    /**
     * Use the EventTable relation EventTable object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventTableQuery A secondary query class using the current class as primary query
     */
    public function useEventTableQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventTable($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventTable', '\API\Models\Event\EventTableQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventUser object
     *
     * @param \API\Models\Event\EventUser|ObjectCollection $eventUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventUser($eventUser, $comparison = null)
    {
        if ($eventUser instanceof \API\Models\Event\EventUser) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $eventUser->getEventid(), $comparison);
        } elseif ($eventUser instanceof ObjectCollection) {
            return $this
                ->useEventUserQuery()
                ->filterByPrimaryKeys($eventUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventUser() only accepts arguments of type \API\Models\Event\EventUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinEventUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventUser');

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
            $this->addJoinObject($join, 'EventUser');
        }

        return $this;
    }

    /**
     * Use the EventUser relation EventUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventUserQuery A secondary query class using the current class as primary query
     */
    public function useEventUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventUser', '\API\Models\Event\EventUserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $invoice->getEventid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            return $this
                ->useInvoiceQuery()
                ->filterByPrimaryKeys($invoice->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildEventQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Menu\MenuExtra object
     *
     * @param \API\Models\Menu\MenuExtra|ObjectCollection $menuExtra the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByMenuExtra($menuExtra, $comparison = null)
    {
        if ($menuExtra instanceof \API\Models\Menu\MenuExtra) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $menuExtra->getEventid(), $comparison);
        } elseif ($menuExtra instanceof ObjectCollection) {
            return $this
                ->useMenuExtraQuery()
                ->filterByPrimaryKeys($menuExtra->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuExtra() only accepts arguments of type \API\Models\Menu\MenuExtra or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuExtra relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinMenuExtra($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuExtra');

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
            $this->addJoinObject($join, 'MenuExtra');
        }

        return $this;
    }

    /**
     * Use the MenuExtra relation MenuExtra object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuExtraQuery A secondary query class using the current class as primary query
     */
    public function useMenuExtraQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuExtra($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuExtra', '\API\Models\Menu\MenuExtraQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuSize object
     *
     * @param \API\Models\Menu\MenuSize|ObjectCollection $menuSize the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByMenuSize($menuSize, $comparison = null)
    {
        if ($menuSize instanceof \API\Models\Menu\MenuSize) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $menuSize->getEventid(), $comparison);
        } elseif ($menuSize instanceof ObjectCollection) {
            return $this
                ->useMenuSizeQuery()
                ->filterByPrimaryKeys($menuSize->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuSize() only accepts arguments of type \API\Models\Menu\MenuSize or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuSize relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinMenuSize($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuSize');

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
            $this->addJoinObject($join, 'MenuSize');
        }

        return $this;
    }

    /**
     * Use the MenuSize relation MenuSize object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuSizeQuery A secondary query class using the current class as primary query
     */
    public function useMenuSizeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuSize($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuSize', '\API\Models\Menu\MenuSizeQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuType object
     *
     * @param \API\Models\Menu\MenuType|ObjectCollection $menuType the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByMenuType($menuType, $comparison = null)
    {
        if ($menuType instanceof \API\Models\Menu\MenuType) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $menuType->getEventid(), $comparison);
        } elseif ($menuType instanceof ObjectCollection) {
            return $this
                ->useMenuTypeQuery()
                ->filterByPrimaryKeys($menuType->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuType() only accepts arguments of type \API\Models\Menu\MenuType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinMenuType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuType');

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
            $this->addJoinObject($join, 'MenuType');
        }

        return $this;
    }

    /**
     * Use the MenuType relation MenuType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuTypeQuery A secondary query class using the current class as primary query
     */
    public function useMenuTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuType', '\API\Models\Menu\MenuTypeQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentWarningType object
     *
     * @param \API\Models\Payment\PaymentWarningType|ObjectCollection $paymentWarningType the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByPaymentWarningType($paymentWarningType, $comparison = null)
    {
        if ($paymentWarningType instanceof \API\Models\Payment\PaymentWarningType) {
            return $this
                ->addUsingAlias(EventTableMap::COL_EVENTID, $paymentWarningType->getEventid(), $comparison);
        } elseif ($paymentWarningType instanceof ObjectCollection) {
            return $this
                ->usePaymentWarningTypeQuery()
                ->filterByPrimaryKeys($paymentWarningType->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildEventQuery The current query, for fluid interface
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
     * @param   ChildEvent $event Object to remove from the list of results
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventTableMap::COL_EVENTID, $event->getEventid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTableMap::clearInstancePool();
            EventTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventQuery
