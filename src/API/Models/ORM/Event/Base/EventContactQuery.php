<?php

namespace API\Models\ORM\Event\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Event\EventContact as ChildEventContact;
use API\Models\ORM\Event\EventContactQuery as ChildEventContactQuery;
use API\Models\ORM\Event\Map\EventContactTableMap;
use API\Models\ORM\Invoice\Invoice;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event_contact' table.
 *
 * 
 *
 * @method     ChildEventContactQuery orderByEventContactid($order = Criteria::ASC) Order by the event_contactid column
 * @method     ChildEventContactQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildEventContactQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildEventContactQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventContactQuery orderByContactPerson($order = Criteria::ASC) Order by the contact_person column
 * @method     ChildEventContactQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildEventContactQuery orderByAddress2($order = Criteria::ASC) Order by the address2 column
 * @method     ChildEventContactQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildEventContactQuery orderByZip($order = Criteria::ASC) Order by the zip column
 * @method     ChildEventContactQuery orderByTaxIdentificationNr($order = Criteria::ASC) Order by the tax_identification_nr column
 * @method     ChildEventContactQuery orderByTelephon($order = Criteria::ASC) Order by the telephon column
 * @method     ChildEventContactQuery orderByFax($order = Criteria::ASC) Order by the fax column
 * @method     ChildEventContactQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildEventContactQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildEventContactQuery orderByDefault($order = Criteria::ASC) Order by the default column
 *
 * @method     ChildEventContactQuery groupByEventContactid() Group by the event_contactid column
 * @method     ChildEventContactQuery groupByEventid() Group by the eventid column
 * @method     ChildEventContactQuery groupByTitle() Group by the title column
 * @method     ChildEventContactQuery groupByName() Group by the name column
 * @method     ChildEventContactQuery groupByContactPerson() Group by the contact_person column
 * @method     ChildEventContactQuery groupByAddress() Group by the address column
 * @method     ChildEventContactQuery groupByAddress2() Group by the address2 column
 * @method     ChildEventContactQuery groupByCity() Group by the city column
 * @method     ChildEventContactQuery groupByZip() Group by the zip column
 * @method     ChildEventContactQuery groupByTaxIdentificationNr() Group by the tax_identification_nr column
 * @method     ChildEventContactQuery groupByTelephon() Group by the telephon column
 * @method     ChildEventContactQuery groupByFax() Group by the fax column
 * @method     ChildEventContactQuery groupByEmail() Group by the email column
 * @method     ChildEventContactQuery groupByActive() Group by the active column
 * @method     ChildEventContactQuery groupByDefault() Group by the default column
 *
 * @method     ChildEventContactQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventContactQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventContactQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventContactQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventContactQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventContactQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventContactQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildEventContactQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildEventContactQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildEventContactQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildEventContactQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildEventContactQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildEventContactQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildEventContactQuery leftJoinInvoiceRelatedByCustomerEventContactid($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceRelatedByCustomerEventContactid relation
 * @method     ChildEventContactQuery rightJoinInvoiceRelatedByCustomerEventContactid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceRelatedByCustomerEventContactid relation
 * @method     ChildEventContactQuery innerJoinInvoiceRelatedByCustomerEventContactid($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceRelatedByCustomerEventContactid relation
 *
 * @method     ChildEventContactQuery joinWithInvoiceRelatedByCustomerEventContactid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceRelatedByCustomerEventContactid relation
 *
 * @method     ChildEventContactQuery leftJoinWithInvoiceRelatedByCustomerEventContactid() Adds a LEFT JOIN clause and with to the query using the InvoiceRelatedByCustomerEventContactid relation
 * @method     ChildEventContactQuery rightJoinWithInvoiceRelatedByCustomerEventContactid() Adds a RIGHT JOIN clause and with to the query using the InvoiceRelatedByCustomerEventContactid relation
 * @method     ChildEventContactQuery innerJoinWithInvoiceRelatedByCustomerEventContactid() Adds a INNER JOIN clause and with to the query using the InvoiceRelatedByCustomerEventContactid relation
 *
 * @method     ChildEventContactQuery leftJoinInvoiceRelatedByEventContactid($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceRelatedByEventContactid relation
 * @method     ChildEventContactQuery rightJoinInvoiceRelatedByEventContactid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceRelatedByEventContactid relation
 * @method     ChildEventContactQuery innerJoinInvoiceRelatedByEventContactid($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceRelatedByEventContactid relation
 *
 * @method     ChildEventContactQuery joinWithInvoiceRelatedByEventContactid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceRelatedByEventContactid relation
 *
 * @method     ChildEventContactQuery leftJoinWithInvoiceRelatedByEventContactid() Adds a LEFT JOIN clause and with to the query using the InvoiceRelatedByEventContactid relation
 * @method     ChildEventContactQuery rightJoinWithInvoiceRelatedByEventContactid() Adds a RIGHT JOIN clause and with to the query using the InvoiceRelatedByEventContactid relation
 * @method     ChildEventContactQuery innerJoinWithInvoiceRelatedByEventContactid() Adds a INNER JOIN clause and with to the query using the InvoiceRelatedByEventContactid relation
 *
 * @method     \API\Models\ORM\Event\EventQuery|\API\Models\ORM\Invoice\InvoiceQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventContact findOne(ConnectionInterface $con = null) Return the first ChildEventContact matching the query
 * @method     ChildEventContact findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventContact matching the query, or a new ChildEventContact object populated from the query conditions when no match is found
 *
 * @method     ChildEventContact findOneByEventContactid(int $event_contactid) Return the first ChildEventContact filtered by the event_contactid column
 * @method     ChildEventContact findOneByEventid(int $eventid) Return the first ChildEventContact filtered by the eventid column
 * @method     ChildEventContact findOneByTitle(string $title) Return the first ChildEventContact filtered by the title column
 * @method     ChildEventContact findOneByName(string $name) Return the first ChildEventContact filtered by the name column
 * @method     ChildEventContact findOneByContactPerson(string $contact_person) Return the first ChildEventContact filtered by the contact_person column
 * @method     ChildEventContact findOneByAddress(string $address) Return the first ChildEventContact filtered by the address column
 * @method     ChildEventContact findOneByAddress2(string $address2) Return the first ChildEventContact filtered by the address2 column
 * @method     ChildEventContact findOneByCity(string $city) Return the first ChildEventContact filtered by the city column
 * @method     ChildEventContact findOneByZip(string $zip) Return the first ChildEventContact filtered by the zip column
 * @method     ChildEventContact findOneByTaxIdentificationNr(string $tax_identification_nr) Return the first ChildEventContact filtered by the tax_identification_nr column
 * @method     ChildEventContact findOneByTelephon(string $telephon) Return the first ChildEventContact filtered by the telephon column
 * @method     ChildEventContact findOneByFax(string $fax) Return the first ChildEventContact filtered by the fax column
 * @method     ChildEventContact findOneByEmail(string $email) Return the first ChildEventContact filtered by the email column
 * @method     ChildEventContact findOneByActive(boolean $active) Return the first ChildEventContact filtered by the active column
 * @method     ChildEventContact findOneByDefault(boolean $default) Return the first ChildEventContact filtered by the default column *

 * @method     ChildEventContact requirePk($key, ConnectionInterface $con = null) Return the ChildEventContact by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOne(ConnectionInterface $con = null) Return the first ChildEventContact matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventContact requireOneByEventContactid(int $event_contactid) Return the first ChildEventContact filtered by the event_contactid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByEventid(int $eventid) Return the first ChildEventContact filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByTitle(string $title) Return the first ChildEventContact filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByName(string $name) Return the first ChildEventContact filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByContactPerson(string $contact_person) Return the first ChildEventContact filtered by the contact_person column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByAddress(string $address) Return the first ChildEventContact filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByAddress2(string $address2) Return the first ChildEventContact filtered by the address2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByCity(string $city) Return the first ChildEventContact filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByZip(string $zip) Return the first ChildEventContact filtered by the zip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByTaxIdentificationNr(string $tax_identification_nr) Return the first ChildEventContact filtered by the tax_identification_nr column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByTelephon(string $telephon) Return the first ChildEventContact filtered by the telephon column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByFax(string $fax) Return the first ChildEventContact filtered by the fax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByEmail(string $email) Return the first ChildEventContact filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByActive(boolean $active) Return the first ChildEventContact filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventContact requireOneByDefault(boolean $default) Return the first ChildEventContact filtered by the default column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventContact[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventContact objects based on current ModelCriteria
 * @method     ChildEventContact[]|ObjectCollection findByEventContactid(int $event_contactid) Return ChildEventContact objects filtered by the event_contactid column
 * @method     ChildEventContact[]|ObjectCollection findByEventid(int $eventid) Return ChildEventContact objects filtered by the eventid column
 * @method     ChildEventContact[]|ObjectCollection findByTitle(string $title) Return ChildEventContact objects filtered by the title column
 * @method     ChildEventContact[]|ObjectCollection findByName(string $name) Return ChildEventContact objects filtered by the name column
 * @method     ChildEventContact[]|ObjectCollection findByContactPerson(string $contact_person) Return ChildEventContact objects filtered by the contact_person column
 * @method     ChildEventContact[]|ObjectCollection findByAddress(string $address) Return ChildEventContact objects filtered by the address column
 * @method     ChildEventContact[]|ObjectCollection findByAddress2(string $address2) Return ChildEventContact objects filtered by the address2 column
 * @method     ChildEventContact[]|ObjectCollection findByCity(string $city) Return ChildEventContact objects filtered by the city column
 * @method     ChildEventContact[]|ObjectCollection findByZip(string $zip) Return ChildEventContact objects filtered by the zip column
 * @method     ChildEventContact[]|ObjectCollection findByTaxIdentificationNr(string $tax_identification_nr) Return ChildEventContact objects filtered by the tax_identification_nr column
 * @method     ChildEventContact[]|ObjectCollection findByTelephon(string $telephon) Return ChildEventContact objects filtered by the telephon column
 * @method     ChildEventContact[]|ObjectCollection findByFax(string $fax) Return ChildEventContact objects filtered by the fax column
 * @method     ChildEventContact[]|ObjectCollection findByEmail(string $email) Return ChildEventContact objects filtered by the email column
 * @method     ChildEventContact[]|ObjectCollection findByActive(boolean $active) Return ChildEventContact objects filtered by the active column
 * @method     ChildEventContact[]|ObjectCollection findByDefault(boolean $default) Return ChildEventContact objects filtered by the default column
 * @method     ChildEventContact[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventContactQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Event\Base\EventContactQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Event\\EventContact', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventContactQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventContactQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventContactQuery) {
            return $criteria;
        }
        $query = new ChildEventContactQuery();
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
     * @return ChildEventContact|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventContactTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventContactTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEventContact A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `event_contactid`, `eventid`, `title`, `name`, `contact_person`, `address`, `address2`, `city`, `zip`, `tax_identification_nr`, `telephon`, `fax`, `email`, `active`, `default` FROM `event_contact` WHERE `event_contactid` = :p0';
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
            /** @var ChildEventContact $obj */
            $obj = new ChildEventContact();
            $obj->hydrate($row);
            EventContactTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEventContact|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the event_contactid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventContactid(1234); // WHERE event_contactid = 1234
     * $query->filterByEventContactid(array(12, 34)); // WHERE event_contactid IN (12, 34)
     * $query->filterByEventContactid(array('min' => 12)); // WHERE event_contactid > 12
     * </code>
     *
     * @param     mixed $eventContactid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByEventContactid($eventContactid = null, $comparison = null)
    {
        if (is_array($eventContactid)) {
            $useMinMax = false;
            if (isset($eventContactid['min'])) {
                $this->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $eventContactid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventContactid['max'])) {
                $this->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $eventContactid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $eventContactid, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(EventContactTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(EventContactTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the contact_person column
     *
     * Example usage:
     * <code>
     * $query->filterByContactPerson('fooValue');   // WHERE contact_person = 'fooValue'
     * $query->filterByContactPerson('%fooValue%', Criteria::LIKE); // WHERE contact_person LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contactPerson The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByContactPerson($contactPerson = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contactPerson)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_CONTACT_PERSON, $contactPerson, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_ADDRESS, $address, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByAddress2($address2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address2)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_ADDRESS2, $address2, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_CITY, $city, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByZip($zip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zip)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_ZIP, $zip, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByTaxIdentificationNr($taxIdentificationNr = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($taxIdentificationNr)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_TAX_IDENTIFICATION_NR, $taxIdentificationNr, $comparison);
    }

    /**
     * Filter the query on the telephon column
     *
     * Example usage:
     * <code>
     * $query->filterByTelephon('fooValue');   // WHERE telephon = 'fooValue'
     * $query->filterByTelephon('%fooValue%', Criteria::LIKE); // WHERE telephon LIKE '%fooValue%'
     * </code>
     *
     * @param     string $telephon The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByTelephon($telephon = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($telephon)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_TELEPHON, $telephon, $comparison);
    }

    /**
     * Filter the query on the fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE fax = 'fooValue'
     * $query->filterByFax('%fooValue%', Criteria::LIKE); // WHERE fax LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fax The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByFax($fax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_FAX, $fax, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventContactTableMap::COL_EMAIL, $email, $comparison);
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
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_string($active)) {
            $active = in_array(strtolower($active), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventContactTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query on the default column
     *
     * Example usage:
     * <code>
     * $query->filterByDefault(true); // WHERE default = true
     * $query->filterByDefault('yes'); // WHERE default = true
     * </code>
     *
     * @param     boolean|string $default The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByDefault($default = null, $comparison = null)
    {
        if (is_string($default)) {
            $default = in_array(strtolower($default), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventContactTableMap::COL_DEFAULT, $default, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Event\Event object
     *
     * @param \API\Models\ORM\Event\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\ORM\Event\Event) {
            return $this
                ->addUsingAlias(EventContactTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventContactTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \API\Models\ORM\Event\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Event\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\API\Models\ORM\Event\EventQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Invoice\Invoice object
     *
     * @param \API\Models\ORM\Invoice\Invoice|ObjectCollection $invoice the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByInvoiceRelatedByCustomerEventContactid($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\ORM\Invoice\Invoice) {
            return $this
                ->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $invoice->getCustomerEventContactid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            return $this
                ->useInvoiceRelatedByCustomerEventContactidQuery()
                ->filterByPrimaryKeys($invoice->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceRelatedByCustomerEventContactid() only accepts arguments of type \API\Models\ORM\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceRelatedByCustomerEventContactid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function joinInvoiceRelatedByCustomerEventContactid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceRelatedByCustomerEventContactid');

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
            $this->addJoinObject($join, 'InvoiceRelatedByCustomerEventContactid');
        }

        return $this;
    }

    /**
     * Use the InvoiceRelatedByCustomerEventContactid relation Invoice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceRelatedByCustomerEventContactidQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInvoiceRelatedByCustomerEventContactid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceRelatedByCustomerEventContactid', '\API\Models\ORM\Invoice\InvoiceQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Invoice\Invoice object
     *
     * @param \API\Models\ORM\Invoice\Invoice|ObjectCollection $invoice the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventContactQuery The current query, for fluid interface
     */
    public function filterByInvoiceRelatedByEventContactid($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\ORM\Invoice\Invoice) {
            return $this
                ->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $invoice->getEventContactid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            return $this
                ->useInvoiceRelatedByEventContactidQuery()
                ->filterByPrimaryKeys($invoice->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceRelatedByEventContactid() only accepts arguments of type \API\Models\ORM\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceRelatedByEventContactid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function joinInvoiceRelatedByEventContactid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceRelatedByEventContactid');

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
            $this->addJoinObject($join, 'InvoiceRelatedByEventContactid');
        }

        return $this;
    }

    /**
     * Use the InvoiceRelatedByEventContactid relation Invoice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceRelatedByEventContactidQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceRelatedByEventContactid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceRelatedByEventContactid', '\API\Models\ORM\Invoice\InvoiceQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventContact $eventContact Object to remove from the list of results
     *
     * @return $this|ChildEventContactQuery The current query, for fluid interface
     */
    public function prune($eventContact = null)
    {
        if ($eventContact) {
            $this->addUsingAlias(EventContactTableMap::COL_EVENT_CONTACTID, $eventContact->getEventContactid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event_contact table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventContactTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventContactTableMap::clearInstancePool();
            EventContactTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventContactTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventContactTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            EventContactTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            EventContactTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventContactQuery
