<?php

namespace API\Models\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\Event\Event;
use API\Models\Invoice\Customer as ChildCustomer;
use API\Models\Invoice\CustomerQuery as ChildCustomerQuery;
use API\Models\Invoice\Map\CustomerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer' table.
 *
 *
 *
 * @method     ChildCustomerQuery orderByCustomerid($order = Criteria::ASC) Order by the customerid column
 * @method     ChildCustomerQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildCustomerQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildCustomerQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildCustomerQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildCustomerQuery orderByAddress2($order = Criteria::ASC) Order by the address2 column
 * @method     ChildCustomerQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildCustomerQuery orderByZip($order = Criteria::ASC) Order by the zip column
 * @method     ChildCustomerQuery orderByTaxIdentificationNr($order = Criteria::ASC) Order by the tax_identification_nr column
 * @method     ChildCustomerQuery orderByActive($order = Criteria::ASC) Order by the active column
 *
 * @method     ChildCustomerQuery groupByCustomerid() Group by the customerid column
 * @method     ChildCustomerQuery groupByEventid() Group by the eventid column
 * @method     ChildCustomerQuery groupByTitle() Group by the title column
 * @method     ChildCustomerQuery groupByName() Group by the name column
 * @method     ChildCustomerQuery groupByAddress() Group by the address column
 * @method     ChildCustomerQuery groupByAddress2() Group by the address2 column
 * @method     ChildCustomerQuery groupByCity() Group by the city column
 * @method     ChildCustomerQuery groupByZip() Group by the zip column
 * @method     ChildCustomerQuery groupByTaxIdentificationNr() Group by the tax_identification_nr column
 * @method     ChildCustomerQuery groupByActive() Group by the active column
 *
 * @method     ChildCustomerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCustomerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCustomerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCustomerQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildCustomerQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildCustomerQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildCustomerQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildCustomerQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildCustomerQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildCustomerQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildCustomerQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method     ChildCustomerQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method     ChildCustomerQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method     ChildCustomerQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method     ChildCustomerQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method     ChildCustomerQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method     ChildCustomerQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method     \API\Models\Event\EventQuery|\API\Models\Invoice\InvoiceQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCustomer findOne(ConnectionInterface $con = null) Return the first ChildCustomer matching the query
 * @method     ChildCustomer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomer matching the query, or a new ChildCustomer object populated from the query conditions when no match is found
 *
 * @method     ChildCustomer findOneByCustomerid(int $customerid) Return the first ChildCustomer filtered by the customerid column
 * @method     ChildCustomer findOneByEventid(int $eventid) Return the first ChildCustomer filtered by the eventid column
 * @method     ChildCustomer findOneByTitle(string $title) Return the first ChildCustomer filtered by the title column
 * @method     ChildCustomer findOneByName(string $name) Return the first ChildCustomer filtered by the name column
 * @method     ChildCustomer findOneByAddress(string $address) Return the first ChildCustomer filtered by the address column
 * @method     ChildCustomer findOneByAddress2(string $address2) Return the first ChildCustomer filtered by the address2 column
 * @method     ChildCustomer findOneByCity(string $city) Return the first ChildCustomer filtered by the city column
 * @method     ChildCustomer findOneByZip(string $zip) Return the first ChildCustomer filtered by the zip column
 * @method     ChildCustomer findOneByTaxIdentificationNr(string $tax_identification_nr) Return the first ChildCustomer filtered by the tax_identification_nr column
 * @method     ChildCustomer findOneByActive(boolean $active) Return the first ChildCustomer filtered by the active column *

 * @method     ChildCustomer requirePk($key, ConnectionInterface $con = null) Return the ChildCustomer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOne(ConnectionInterface $con = null) Return the first ChildCustomer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCustomer requireOneByCustomerid(int $customerid) Return the first ChildCustomer filtered by the customerid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByEventid(int $eventid) Return the first ChildCustomer filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByTitle(string $title) Return the first ChildCustomer filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByName(string $name) Return the first ChildCustomer filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByAddress(string $address) Return the first ChildCustomer filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByAddress2(string $address2) Return the first ChildCustomer filtered by the address2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByCity(string $city) Return the first ChildCustomer filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByZip(string $zip) Return the first ChildCustomer filtered by the zip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByTaxIdentificationNr(string $tax_identification_nr) Return the first ChildCustomer filtered by the tax_identification_nr column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByActive(boolean $active) Return the first ChildCustomer filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCustomer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCustomer objects based on current ModelCriteria
 * @method     ChildCustomer[]|ObjectCollection findByCustomerid(int $customerid) Return ChildCustomer objects filtered by the customerid column
 * @method     ChildCustomer[]|ObjectCollection findByEventid(int $eventid) Return ChildCustomer objects filtered by the eventid column
 * @method     ChildCustomer[]|ObjectCollection findByTitle(string $title) Return ChildCustomer objects filtered by the title column
 * @method     ChildCustomer[]|ObjectCollection findByName(string $name) Return ChildCustomer objects filtered by the name column
 * @method     ChildCustomer[]|ObjectCollection findByAddress(string $address) Return ChildCustomer objects filtered by the address column
 * @method     ChildCustomer[]|ObjectCollection findByAddress2(string $address2) Return ChildCustomer objects filtered by the address2 column
 * @method     ChildCustomer[]|ObjectCollection findByCity(string $city) Return ChildCustomer objects filtered by the city column
 * @method     ChildCustomer[]|ObjectCollection findByZip(string $zip) Return ChildCustomer objects filtered by the zip column
 * @method     ChildCustomer[]|ObjectCollection findByTaxIdentificationNr(string $tax_identification_nr) Return ChildCustomer objects filtered by the tax_identification_nr column
 * @method     ChildCustomer[]|ObjectCollection findByActive(boolean $active) Return ChildCustomer objects filtered by the active column
 * @method     ChildCustomer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CustomerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Invoice\Base\CustomerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Invoice\\Customer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCustomerQuery) {
            return $criteria;
        }
        $query = new ChildCustomerQuery();
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
     * @return ChildCustomer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CustomerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCustomer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT customerid, eventid, title, name, address, address2, city, zip, tax_identification_nr, active FROM customer WHERE customerid = :p0';
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
            /** @var ChildCustomer $obj */
            $obj = new ChildCustomer();
            $obj->hydrate($row);
            CustomerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCustomer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $keys, Criteria::IN);
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
     * @param     mixed $customerid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByCustomerid($customerid = null, $comparison = null)
    {
        if (is_array($customerid)) {
            $useMinMax = false;
            if (isset($customerid['min'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $customerid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerid['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $customerid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $customerid, $comparison);
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
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(CustomerTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_EVENTID, $eventid, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the address2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress2('fooValue');   // WHERE address2 = 'fooValue'
     * $query->filterByAddress2('%fooValue%', Criteria::LIKE); // WHERE address2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address2 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByAddress2($address2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address2)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_ADDRESS2, $address2, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the zip column
     *
     * Example usage:
     * <code>
     * $query->filterByZip('fooValue');   // WHERE zip = 'fooValue'
     * $query->filterByZip('%fooValue%', Criteria::LIKE); // WHERE zip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zip The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByZip($zip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zip)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_ZIP, $zip, $comparison);
    }

    /**
     * Filter the query on the tax_identification_nr column
     *
     * Example usage:
     * <code>
     * $query->filterByTaxIdentificationNr('fooValue');   // WHERE tax_identification_nr = 'fooValue'
     * $query->filterByTaxIdentificationNr('%fooValue%', Criteria::LIKE); // WHERE tax_identification_nr LIKE '%fooValue%'
     * </code>
     *
     * @param     string $taxIdentificationNr The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByTaxIdentificationNr($taxIdentificationNr = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($taxIdentificationNr)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerTableMap::COL_TAX_IDENTIFICATION_NR, $taxIdentificationNr, $comparison);
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
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CustomerTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(CustomerTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CustomerTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
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
     * @return $this|ChildCustomerQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCustomerQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $invoice->getCustomerid(), $comparison);
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
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function joinInvoice($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useInvoiceQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInvoice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoice', '\API\Models\Invoice\InvoiceQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCustomer $customer Object to remove from the list of results
     *
     * @return $this|ChildCustomerQuery The current query, for fluid interface
     */
    public function prune($customer = null)
    {
        if ($customer) {
            $this->addUsingAlias(CustomerTableMap::COL_CUSTOMERID, $customer->getCustomerid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CustomerTableMap::clearInstancePool();
            CustomerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CustomerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CustomerQuery
