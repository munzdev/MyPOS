<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlaces as ChildDistributionsPlaces;
use API\Models\DistributionPlace\DistributionsPlacesQuery as ChildDistributionsPlacesQuery;
use API\Models\DistributionPlace\Map\DistributionsPlacesTableMap;
use API\Models\Event\Events;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distributions_places' table.
 *
 *
 *
 * @method     ChildDistributionsPlacesQuery orderByDistributionsPlaceid($order = Criteria::ASC) Order by the distributions_placeid column
 * @method     ChildDistributionsPlacesQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildDistributionsPlacesQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildDistributionsPlacesQuery groupByDistributionsPlaceid() Group by the distributions_placeid column
 * @method     ChildDistributionsPlacesQuery groupByEventid() Group by the eventid column
 * @method     ChildDistributionsPlacesQuery groupByName() Group by the name column
 *
 * @method     ChildDistributionsPlacesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionsPlacesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionsPlacesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionsPlacesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionsPlacesQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildDistributionsPlacesQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildDistributionsPlacesQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildDistributionsPlacesQuery joinWithEvents($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Events relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinWithEvents() Adds a LEFT JOIN clause and with to the query using the Events relation
 * @method     ChildDistributionsPlacesQuery rightJoinWithEvents() Adds a RIGHT JOIN clause and with to the query using the Events relation
 * @method     ChildDistributionsPlacesQuery innerJoinWithEvents() Adds a INNER JOIN clause and with to the query using the Events relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinDistributionsPlacesGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlacesGroupes relation
 * @method     ChildDistributionsPlacesQuery rightJoinDistributionsPlacesGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlacesGroupes relation
 * @method     ChildDistributionsPlacesQuery innerJoinDistributionsPlacesGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlacesGroupes relation
 *
 * @method     ChildDistributionsPlacesQuery joinWithDistributionsPlacesGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlacesGroupes relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinWithDistributionsPlacesGroupes() Adds a LEFT JOIN clause and with to the query using the DistributionsPlacesGroupes relation
 * @method     ChildDistributionsPlacesQuery rightJoinWithDistributionsPlacesGroupes() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlacesGroupes relation
 * @method     ChildDistributionsPlacesQuery innerJoinWithDistributionsPlacesGroupes() Adds a INNER JOIN clause and with to the query using the DistributionsPlacesGroupes relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinDistributionsPlacesTables($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlacesTables relation
 * @method     ChildDistributionsPlacesQuery rightJoinDistributionsPlacesTables($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlacesTables relation
 * @method     ChildDistributionsPlacesQuery innerJoinDistributionsPlacesTables($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlacesTables relation
 *
 * @method     ChildDistributionsPlacesQuery joinWithDistributionsPlacesTables($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlacesTables relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinWithDistributionsPlacesTables() Adds a LEFT JOIN clause and with to the query using the DistributionsPlacesTables relation
 * @method     ChildDistributionsPlacesQuery rightJoinWithDistributionsPlacesTables() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlacesTables relation
 * @method     ChildDistributionsPlacesQuery innerJoinWithDistributionsPlacesTables() Adds a INNER JOIN clause and with to the query using the DistributionsPlacesTables relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinDistributionsPlacesUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlacesUsers relation
 * @method     ChildDistributionsPlacesQuery rightJoinDistributionsPlacesUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlacesUsers relation
 * @method     ChildDistributionsPlacesQuery innerJoinDistributionsPlacesUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildDistributionsPlacesQuery joinWithDistributionsPlacesUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildDistributionsPlacesQuery leftJoinWithDistributionsPlacesUsers() Adds a LEFT JOIN clause and with to the query using the DistributionsPlacesUsers relation
 * @method     ChildDistributionsPlacesQuery rightJoinWithDistributionsPlacesUsers() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlacesUsers relation
 * @method     ChildDistributionsPlacesQuery innerJoinWithDistributionsPlacesUsers() Adds a INNER JOIN clause and with to the query using the DistributionsPlacesUsers relation
 *
 * @method     \API\Models\Event\EventsQuery|\API\Models\DistributionPlace\DistributionsPlacesGroupesQuery|\API\Models\DistributionPlace\DistributionsPlacesTablesQuery|\API\Models\DistributionPlace\DistributionsPlacesUsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionsPlaces findOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlaces matching the query
 * @method     ChildDistributionsPlaces findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionsPlaces matching the query, or a new ChildDistributionsPlaces object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionsPlaces findOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlaces filtered by the distributions_placeid column
 * @method     ChildDistributionsPlaces findOneByEventid(int $eventid) Return the first ChildDistributionsPlaces filtered by the eventid column
 * @method     ChildDistributionsPlaces findOneByName(string $name) Return the first ChildDistributionsPlaces filtered by the name column *

 * @method     ChildDistributionsPlaces requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionsPlaces by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlaces requireOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlaces matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlaces requireOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlaces filtered by the distributions_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlaces requireOneByEventid(int $eventid) Return the first ChildDistributionsPlaces filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlaces requireOneByName(string $name) Return the first ChildDistributionsPlaces filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlaces[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionsPlaces objects based on current ModelCriteria
 * @method     ChildDistributionsPlaces[]|ObjectCollection findByDistributionsPlaceid(int $distributions_placeid) Return ChildDistributionsPlaces objects filtered by the distributions_placeid column
 * @method     ChildDistributionsPlaces[]|ObjectCollection findByEventid(int $eventid) Return ChildDistributionsPlaces objects filtered by the eventid column
 * @method     ChildDistributionsPlaces[]|ObjectCollection findByName(string $name) Return ChildDistributionsPlaces objects filtered by the name column
 * @method     ChildDistributionsPlaces[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionsPlacesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\DistributionPlace\Base\DistributionsPlacesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\DistributionPlace\\DistributionsPlaces', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionsPlacesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionsPlacesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionsPlacesQuery) {
            return $criteria;
        }
        $query = new ChildDistributionsPlacesQuery();
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
     * @param array[$distributions_placeid, $eventid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionsPlaces|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionsPlacesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionsPlacesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildDistributionsPlaces A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distributions_placeid, eventid, name FROM distributions_places WHERE distributions_placeid = :p0 AND eventid = :p1';
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
            /** @var ChildDistributionsPlaces $obj */
            $obj = new ChildDistributionsPlaces();
            $obj->hydrate($row);
            DistributionsPlacesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildDistributionsPlaces|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionsPlacesTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionsPlacesTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the distributions_placeid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionsPlaceid(1234); // WHERE distributions_placeid = 1234
     * $query->filterByDistributionsPlaceid(array(12, 34)); // WHERE distributions_placeid IN (12, 34)
     * $query->filterByDistributionsPlaceid(array('min' => 12)); // WHERE distributions_placeid > 12
     * </code>
     *
     * @param     mixed $distributionsPlaceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaceid($distributionsPlaceid = null, $comparison = null)
    {
        if (is_array($distributionsPlaceid)) {
            $useMinMax = false;
            if (isset($distributionsPlaceid['min'])) {
                $this->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionsPlaceid['max'])) {
                $this->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid, $comparison);
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
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(DistributionsPlacesTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(DistributionsPlacesTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Events object
     *
     * @param \API\Models\Event\Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \API\Models\Event\Events) {
            return $this
                ->addUsingAlias(DistributionsPlacesTableMap::COL_EVENTID, $events->getEventid(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesTableMap::COL_EVENTID, $events->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvents() only accepts arguments of type \API\Models\Event\Events or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Events relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
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
     * @return \API\Models\Event\EventsQuery A secondary query class using the current class as primary query
     */
    public function useEventsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvents($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Events', '\API\Models\Event\EventsQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionsPlacesGroupes object
     *
     * @param \API\Models\DistributionPlace\DistributionsPlacesGroupes|ObjectCollection $distributionsPlacesGroupes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlacesGroupes($distributionsPlacesGroupes, $comparison = null)
    {
        if ($distributionsPlacesGroupes instanceof \API\Models\DistributionPlace\DistributionsPlacesGroupes) {
            return $this
                ->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlacesGroupes->getDistributionsPlaceid(), $comparison);
        } elseif ($distributionsPlacesGroupes instanceof ObjectCollection) {
            return $this
                ->useDistributionsPlacesGroupesQuery()
                ->filterByPrimaryKeys($distributionsPlacesGroupes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionsPlacesGroupes() only accepts arguments of type \API\Models\DistributionPlace\DistributionsPlacesGroupes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlacesGroupes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function joinDistributionsPlacesGroupes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsPlacesGroupes');

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
            $this->addJoinObject($join, 'DistributionsPlacesGroupes');
        }

        return $this;
    }

    /**
     * Use the DistributionsPlacesGroupes relation DistributionsPlacesGroupes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionsPlacesGroupesQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesGroupesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlacesGroupes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlacesGroupes', '\API\Models\DistributionPlace\DistributionsPlacesGroupesQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionsPlacesTables object
     *
     * @param \API\Models\DistributionPlace\DistributionsPlacesTables|ObjectCollection $distributionsPlacesTables the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlacesTables($distributionsPlacesTables, $comparison = null)
    {
        if ($distributionsPlacesTables instanceof \API\Models\DistributionPlace\DistributionsPlacesTables) {
            return $this
                ->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlacesTables->getDistributionsPlaceid(), $comparison);
        } elseif ($distributionsPlacesTables instanceof ObjectCollection) {
            return $this
                ->useDistributionsPlacesTablesQuery()
                ->filterByPrimaryKeys($distributionsPlacesTables->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionsPlacesTables() only accepts arguments of type \API\Models\DistributionPlace\DistributionsPlacesTables or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlacesTables relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function joinDistributionsPlacesTables($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsPlacesTables');

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
            $this->addJoinObject($join, 'DistributionsPlacesTables');
        }

        return $this;
    }

    /**
     * Use the DistributionsPlacesTables relation DistributionsPlacesTables object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionsPlacesTablesQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesTablesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlacesTables($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlacesTables', '\API\Models\DistributionPlace\DistributionsPlacesTablesQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionsPlacesUsers object
     *
     * @param \API\Models\DistributionPlace\DistributionsPlacesUsers|ObjectCollection $distributionsPlacesUsers the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlacesUsers($distributionsPlacesUsers, $comparison = null)
    {
        if ($distributionsPlacesUsers instanceof \API\Models\DistributionPlace\DistributionsPlacesUsers) {
            return $this
                ->addUsingAlias(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlacesUsers->getDistributionsPlaceid(), $comparison);
        } elseif ($distributionsPlacesUsers instanceof ObjectCollection) {
            return $this
                ->useDistributionsPlacesUsersQuery()
                ->filterByPrimaryKeys($distributionsPlacesUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionsPlacesUsers() only accepts arguments of type \API\Models\DistributionPlace\DistributionsPlacesUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlacesUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
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
     * @return \API\Models\DistributionPlace\DistributionsPlacesUsersQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlacesUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlacesUsers', '\API\Models\DistributionPlace\DistributionsPlacesUsersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionsPlaces $distributionsPlaces Object to remove from the list of results
     *
     * @return $this|ChildDistributionsPlacesQuery The current query, for fluid interface
     */
    public function prune($distributionsPlaces = null)
    {
        if ($distributionsPlaces) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionsPlacesTableMap::COL_DISTRIBUTIONS_PLACEID), $distributionsPlaces->getDistributionsPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionsPlacesTableMap::COL_EVENTID), $distributionsPlaces->getEventid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distributions_places table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionsPlacesTableMap::clearInstancePool();
            DistributionsPlacesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionsPlacesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionsPlacesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionsPlacesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionsPlacesQuery
