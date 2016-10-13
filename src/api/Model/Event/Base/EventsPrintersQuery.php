<?php

namespace Model\Event\Base;

use \Exception;
use \PDO;
use Model\DistributionPlace\DistributionsPlacesUsers;
use Model\Event\EventsPrinters as ChildEventsPrinters;
use Model\Event\EventsPrintersQuery as ChildEventsPrintersQuery;
use Model\Event\Map\EventsPrintersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'events_printers' table.
 *
 *
 *
 * @method     ChildEventsPrintersQuery orderByEventsPrinterid($order = Criteria::ASC) Order by the events_printerid column
 * @method     ChildEventsPrintersQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildEventsPrintersQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildEventsPrintersQuery orderByIp($order = Criteria::ASC) Order by the ip column
 * @method     ChildEventsPrintersQuery orderByPort($order = Criteria::ASC) Order by the port column
 * @method     ChildEventsPrintersQuery orderByDefault($order = Criteria::ASC) Order by the default column
 * @method     ChildEventsPrintersQuery orderByCharactersPerRow($order = Criteria::ASC) Order by the characters_per_row column
 *
 * @method     ChildEventsPrintersQuery groupByEventsPrinterid() Group by the events_printerid column
 * @method     ChildEventsPrintersQuery groupByEventid() Group by the eventid column
 * @method     ChildEventsPrintersQuery groupByName() Group by the name column
 * @method     ChildEventsPrintersQuery groupByIp() Group by the ip column
 * @method     ChildEventsPrintersQuery groupByPort() Group by the port column
 * @method     ChildEventsPrintersQuery groupByDefault() Group by the default column
 * @method     ChildEventsPrintersQuery groupByCharactersPerRow() Group by the characters_per_row column
 *
 * @method     ChildEventsPrintersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventsPrintersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventsPrintersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventsPrintersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventsPrintersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventsPrintersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventsPrintersQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildEventsPrintersQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildEventsPrintersQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildEventsPrintersQuery joinWithEvents($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Events relation
 *
 * @method     ChildEventsPrintersQuery leftJoinWithEvents() Adds a LEFT JOIN clause and with to the query using the Events relation
 * @method     ChildEventsPrintersQuery rightJoinWithEvents() Adds a RIGHT JOIN clause and with to the query using the Events relation
 * @method     ChildEventsPrintersQuery innerJoinWithEvents() Adds a INNER JOIN clause and with to the query using the Events relation
 *
 * @method     ChildEventsPrintersQuery leftJoinDistributionsPlacesUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlacesUsers relation
 * @method     ChildEventsPrintersQuery rightJoinDistributionsPlacesUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlacesUsers relation
 * @method     ChildEventsPrintersQuery innerJoinDistributionsPlacesUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildEventsPrintersQuery joinWithDistributionsPlacesUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildEventsPrintersQuery leftJoinWithDistributionsPlacesUsers() Adds a LEFT JOIN clause and with to the query using the DistributionsPlacesUsers relation
 * @method     ChildEventsPrintersQuery rightJoinWithDistributionsPlacesUsers() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlacesUsers relation
 * @method     ChildEventsPrintersQuery innerJoinWithDistributionsPlacesUsers() Adds a INNER JOIN clause and with to the query using the DistributionsPlacesUsers relation
 *
 * @method     \Model\Event\EventsQuery|\Model\DistributionPlace\DistributionsPlacesUsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventsPrinters findOne(ConnectionInterface $con = null) Return the first ChildEventsPrinters matching the query
 * @method     ChildEventsPrinters findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventsPrinters matching the query, or a new ChildEventsPrinters object populated from the query conditions when no match is found
 *
 * @method     ChildEventsPrinters findOneByEventsPrinterid(int $events_printerid) Return the first ChildEventsPrinters filtered by the events_printerid column
 * @method     ChildEventsPrinters findOneByEventid(int $eventid) Return the first ChildEventsPrinters filtered by the eventid column
 * @method     ChildEventsPrinters findOneByName(string $name) Return the first ChildEventsPrinters filtered by the name column
 * @method     ChildEventsPrinters findOneByIp(string $ip) Return the first ChildEventsPrinters filtered by the ip column
 * @method     ChildEventsPrinters findOneByPort(int $port) Return the first ChildEventsPrinters filtered by the port column
 * @method     ChildEventsPrinters findOneByDefault(boolean $default) Return the first ChildEventsPrinters filtered by the default column
 * @method     ChildEventsPrinters findOneByCharactersPerRow(int $characters_per_row) Return the first ChildEventsPrinters filtered by the characters_per_row column *

 * @method     ChildEventsPrinters requirePk($key, ConnectionInterface $con = null) Return the ChildEventsPrinters by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOne(ConnectionInterface $con = null) Return the first ChildEventsPrinters matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventsPrinters requireOneByEventsPrinterid(int $events_printerid) Return the first ChildEventsPrinters filtered by the events_printerid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOneByEventid(int $eventid) Return the first ChildEventsPrinters filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOneByName(string $name) Return the first ChildEventsPrinters filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOneByIp(string $ip) Return the first ChildEventsPrinters filtered by the ip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOneByPort(int $port) Return the first ChildEventsPrinters filtered by the port column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOneByDefault(boolean $default) Return the first ChildEventsPrinters filtered by the default column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsPrinters requireOneByCharactersPerRow(int $characters_per_row) Return the first ChildEventsPrinters filtered by the characters_per_row column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventsPrinters[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventsPrinters objects based on current ModelCriteria
 * @method     ChildEventsPrinters[]|ObjectCollection findByEventsPrinterid(int $events_printerid) Return ChildEventsPrinters objects filtered by the events_printerid column
 * @method     ChildEventsPrinters[]|ObjectCollection findByEventid(int $eventid) Return ChildEventsPrinters objects filtered by the eventid column
 * @method     ChildEventsPrinters[]|ObjectCollection findByName(string $name) Return ChildEventsPrinters objects filtered by the name column
 * @method     ChildEventsPrinters[]|ObjectCollection findByIp(string $ip) Return ChildEventsPrinters objects filtered by the ip column
 * @method     ChildEventsPrinters[]|ObjectCollection findByPort(int $port) Return ChildEventsPrinters objects filtered by the port column
 * @method     ChildEventsPrinters[]|ObjectCollection findByDefault(boolean $default) Return ChildEventsPrinters objects filtered by the default column
 * @method     ChildEventsPrinters[]|ObjectCollection findByCharactersPerRow(int $characters_per_row) Return ChildEventsPrinters objects filtered by the characters_per_row column
 * @method     ChildEventsPrinters[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventsPrintersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Event\Base\EventsPrintersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Event\\EventsPrinters', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventsPrintersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventsPrintersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventsPrintersQuery) {
            return $criteria;
        }
        $query = new ChildEventsPrintersQuery();
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
     * @param array[$events_printerid, $eventid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildEventsPrinters|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventsPrintersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventsPrintersTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildEventsPrinters A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT events_printerid, eventid, name, ip, port, default, characters_per_row FROM events_printers WHERE events_printerid = :p0 AND eventid = :p1';
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
            /** @var ChildEventsPrinters $obj */
            $obj = new ChildEventsPrinters();
            $obj->hydrate($row);
            EventsPrintersTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildEventsPrinters|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(EventsPrintersTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the events_printerid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventsPrinterid(1234); // WHERE events_printerid = 1234
     * $query->filterByEventsPrinterid(array(12, 34)); // WHERE events_printerid IN (12, 34)
     * $query->filterByEventsPrinterid(array('min' => 12)); // WHERE events_printerid > 12
     * </code>
     *
     * @param     mixed $eventsPrinterid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByEventsPrinterid($eventsPrinterid = null, $comparison = null)
    {
        if (is_array($eventsPrinterid)) {
            $useMinMax = false;
            if (isset($eventsPrinterid['min'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $eventsPrinterid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventsPrinterid['max'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $eventsPrinterid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $eventsPrinterid, $comparison);
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
     * @see       filterByEvents()
     *
     * @param     mixed $eventid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the ip column
     *
     * Example usage:
     * <code>
     * $query->filterByIp('fooValue');   // WHERE ip = 'fooValue'
     * $query->filterByIp('%fooValue%', Criteria::LIKE); // WHERE ip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ip The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByIp($ip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ip)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_IP, $ip, $comparison);
    }

    /**
     * Filter the query on the port column
     *
     * Example usage:
     * <code>
     * $query->filterByPort(1234); // WHERE port = 1234
     * $query->filterByPort(array(12, 34)); // WHERE port IN (12, 34)
     * $query->filterByPort(array('min' => 12)); // WHERE port > 12
     * </code>
     *
     * @param     mixed $port The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByPort($port = null, $comparison = null)
    {
        if (is_array($port)) {
            $useMinMax = false;
            if (isset($port['min'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_PORT, $port['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($port['max'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_PORT, $port['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_PORT, $port, $comparison);
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
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByDefault($default = null, $comparison = null)
    {
        if (is_string($default)) {
            $default = in_array(strtolower($default), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_DEFAULT, $default, $comparison);
    }

    /**
     * Filter the query on the characters_per_row column
     *
     * Example usage:
     * <code>
     * $query->filterByCharactersPerRow(1234); // WHERE characters_per_row = 1234
     * $query->filterByCharactersPerRow(array(12, 34)); // WHERE characters_per_row IN (12, 34)
     * $query->filterByCharactersPerRow(array('min' => 12)); // WHERE characters_per_row > 12
     * </code>
     *
     * @param     mixed $charactersPerRow The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByCharactersPerRow($charactersPerRow = null, $comparison = null)
    {
        if (is_array($charactersPerRow)) {
            $useMinMax = false;
            if (isset($charactersPerRow['min'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_CHARACTERS_PER_ROW, $charactersPerRow['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($charactersPerRow['max'])) {
                $this->addUsingAlias(EventsPrintersTableMap::COL_CHARACTERS_PER_ROW, $charactersPerRow['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsPrintersTableMap::COL_CHARACTERS_PER_ROW, $charactersPerRow, $comparison);
    }

    /**
     * Filter the query by a related \Model\Event\Events object
     *
     * @param \Model\Event\Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \Model\Event\Events) {
            return $this
                ->addUsingAlias(EventsPrintersTableMap::COL_EVENTID, $events->getEventid(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventsPrintersTableMap::COL_EVENTID, $events->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type \Model\Event\Events or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function joinEvents($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Events');

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
            $this->addJoinObject($join, 'Events');
        }

        return $this;
    }

    /**
     * Use the Events relation Events object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Event\EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\Model\Event\EventsQuery');
    }

    /**
     * Filter the query by a related \Model\DistributionPlace\DistributionsPlacesUsers object
     *
     * @param \Model\DistributionPlace\DistributionsPlacesUsers|ObjectCollection $distributionsPlacesUsers the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlacesUsers($distributionsPlacesUsers, $comparison = null)
    {
        if ($distributionsPlacesUsers instanceof \Model\DistributionPlace\DistributionsPlacesUsers) {
            return $this
                ->addUsingAlias(EventsPrintersTableMap::COL_EVENTS_PRINTERID, $distributionsPlacesUsers->getEventsPrinterid(), $comparison);
        } elseif ($distributionsPlacesUsers instanceof ObjectCollection) {
            return $this
                ->useDistributionsPlacesUsersQuery()
                ->filterByPrimaryKeys($distributionsPlacesUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionsPlacesUsers() only accepts arguments of type \Model\DistributionPlace\DistributionsPlacesUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlacesUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function joinDistributionsPlacesUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsPlacesUsers');

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
            $this->addJoinObject($join, 'DistributionsPlacesUsers');
        }

        return $this;
    }

    /**
     * Use the DistributionsPlacesUsers relation DistributionsPlacesUsers object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\DistributionPlace\DistributionsPlacesUsersQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlacesUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlacesUsers', '\Model\DistributionPlace\DistributionsPlacesUsersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventsPrinters $eventsPrinters Object to remove from the list of results
     *
     * @return $this|ChildEventsPrintersQuery The current query, for fluid interface
     */
    public function prune($eventsPrinters = null)
    {
        if ($eventsPrinters) {
            $this->addCond('pruneCond0', $this->getAliasedColName(EventsPrintersTableMap::COL_EVENTS_PRINTERID), $eventsPrinters->getEventsPrinterid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(EventsPrintersTableMap::COL_EVENTID), $eventsPrinters->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the events_printers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsPrintersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventsPrintersTableMap::clearInstancePool();
            EventsPrintersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsPrintersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventsPrintersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventsPrintersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventsPrintersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventsPrintersQuery
