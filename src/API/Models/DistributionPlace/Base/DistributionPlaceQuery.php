<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlace as ChildDistributionPlace;
use API\Models\DistributionPlace\DistributionPlaceQuery as ChildDistributionPlaceQuery;
use API\Models\DistributionPlace\Map\DistributionPlaceTableMap;
use API\Models\Event\Event;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distribution_place' table.
 *
 * @method ChildDistributionPlaceQuery orderByDistributionPlaceid($order = Criteria::ASC) Order by the distribution_placeid column
 * @method ChildDistributionPlaceQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method ChildDistributionPlaceQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method ChildDistributionPlaceQuery groupByDistributionPlaceid() Group by the distribution_placeid column
 * @method ChildDistributionPlaceQuery groupByEventid() Group by the eventid column
 * @method ChildDistributionPlaceQuery groupByName() Group by the name column
 *
 * @method ChildDistributionPlaceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ChildDistributionPlaceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ChildDistributionPlaceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ChildDistributionPlaceQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method ChildDistributionPlaceQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method ChildDistributionPlaceQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method ChildDistributionPlaceQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method ChildDistributionPlaceQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method ChildDistributionPlaceQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method ChildDistributionPlaceQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method ChildDistributionPlaceQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method ChildDistributionPlaceQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method ChildDistributionPlaceQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method ChildDistributionPlaceQuery leftJoinDistributionPlaceGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlaceGroup relation
 * @method ChildDistributionPlaceQuery rightJoinDistributionPlaceGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlaceGroup relation
 * @method ChildDistributionPlaceQuery innerJoinDistributionPlaceGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlaceGroup relation
 *
 * @method ChildDistributionPlaceQuery joinWithDistributionPlaceGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlaceGroup relation
 *
 * @method ChildDistributionPlaceQuery leftJoinWithDistributionPlaceGroup() Adds a LEFT JOIN clause and with to the query using the DistributionPlaceGroup relation
 * @method ChildDistributionPlaceQuery rightJoinWithDistributionPlaceGroup() Adds a RIGHT JOIN clause and with to the query using the DistributionPlaceGroup relation
 * @method ChildDistributionPlaceQuery innerJoinWithDistributionPlaceGroup() Adds a INNER JOIN clause and with to the query using the DistributionPlaceGroup relation
 *
 * @method ChildDistributionPlaceQuery leftJoinDistributionPlaceUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlaceUser relation
 * @method ChildDistributionPlaceQuery rightJoinDistributionPlaceUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlaceUser relation
 * @method ChildDistributionPlaceQuery innerJoinDistributionPlaceUser($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlaceUser relation
 *
 * @method ChildDistributionPlaceQuery joinWithDistributionPlaceUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlaceUser relation
 *
 * @method ChildDistributionPlaceQuery leftJoinWithDistributionPlaceUser() Adds a LEFT JOIN clause and with to the query using the DistributionPlaceUser relation
 * @method ChildDistributionPlaceQuery rightJoinWithDistributionPlaceUser() Adds a RIGHT JOIN clause and with to the query using the DistributionPlaceUser relation
 * @method ChildDistributionPlaceQuery innerJoinWithDistributionPlaceUser() Adds a INNER JOIN clause and with to the query using the DistributionPlaceUser relation
 *
 * @method \API\Models\Event\EventQuery|\API\Models\DistributionPlace\DistributionPlaceGroupQuery|\API\Models\DistributionPlace\DistributionPlaceUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method ChildDistributionPlace findOne(ConnectionInterface $con = null) Return the first ChildDistributionPlace matching the query
 * @method ChildDistributionPlace findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionPlace matching the query, or a new ChildDistributionPlace object populated from the query conditions when no match is found
 *
 * @method ChildDistributionPlace findOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlace filtered by the distribution_placeid column
 * @method ChildDistributionPlace findOneByEventid(int $eventid) Return the first ChildDistributionPlace filtered by the eventid column
 * @method ChildDistributionPlace findOneByName(string $name) Return the first ChildDistributionPlace filtered by the name column *

 * @method ChildDistributionPlace requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionPlace by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildDistributionPlace requireOne(ConnectionInterface $con = null) Return the first ChildDistributionPlace matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildDistributionPlace requireOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlace filtered by the distribution_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildDistributionPlace requireOneByEventid(int $eventid) Return the first ChildDistributionPlace filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildDistributionPlace requireOneByName(string $name) Return the first ChildDistributionPlace filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildDistributionPlace[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionPlace objects based on current ModelCriteria
 * @method ChildDistributionPlace[]|ObjectCollection findByDistributionPlaceid(int $distribution_placeid) Return ChildDistributionPlace objects filtered by the distribution_placeid column
 * @method ChildDistributionPlace[]|ObjectCollection findByEventid(int $eventid) Return ChildDistributionPlace objects filtered by the eventid column
 * @method ChildDistributionPlace[]|ObjectCollection findByName(string $name) Return ChildDistributionPlace objects filtered by the name column
 * @method ChildDistributionPlace[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class DistributionPlaceQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\DistributionPlace\Base\DistributionPlaceQuery object.
     *
     * @param string $dbName     The database name
     * @param string $modelName  The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\DistributionPlace\\DistributionPlace', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionPlaceQuery object.
     *
     * @param string   $modelAlias The alias of a model in the query
     * @param Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildDistributionPlaceQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionPlaceQuery) {
            return $criteria;
        }
        $query = new ChildDistributionPlaceQuery();
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
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionPlace|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if ($this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionPlaceTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlace A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distribution_placeid, eventid, name FROM distribution_place WHERE distribution_placeid = :p0';
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
            /**
 * @var ChildDistributionPlace $obj
*/
            $obj = new ChildDistributionPlace();
            $obj->hydrate($row);
            DistributionPlaceTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildDistributionPlace|array|mixed the result, formatted by the current formatter
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
     *
     * @param array               $keys Primary keys to use for the query
     * @param ConnectionInterface $con  an optional connection object
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the distribution_placeid column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionPlaceid(1234); // WHERE distribution_placeid = 1234
     * $query->filterByDistributionPlaceid(array(12, 34)); // WHERE distribution_placeid IN (12, 34)
     * $query->filterByDistributionPlaceid(array('min' => 12)); // WHERE distribution_placeid > 12
     * </code>
     *
     * @param mixed  $distributionPlaceid The value to use as filter.
     *                                        Use scalar values for
     *                                        equality. Use array values
     *                                        for in_array() equivalent.
     *                                        Use associative array('min'
     *                                        => $minValue, 'max' =>
     *                                        $maxValue) for intervals.
     * @param string $comparison          Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceid($distributionPlaceid = null, $comparison = null)
    {
        if (is_array($distributionPlaceid)) {
            $useMinMax = false;
            if (isset($distributionPlaceid['min'])) {
                $this->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionPlaceid['max'])) {
                $this->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid, $comparison);
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
     * @see filterByEvent()
     *
     * @param mixed  $eventid    The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(DistributionPlaceTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(DistributionPlaceTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @param string $name       The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Event object
     *
     * @param \API\Models\Event\Event|ObjectCollection $event      The related object(s) to use as filter
     * @param string                                   $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\Event\Event) {
            return $this
                ->addUsingAlias(DistributionPlaceTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \API\Models\Event\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlaceGroup object
     *
     * @param \API\Models\DistributionPlace\DistributionPlaceGroup|ObjectCollection $distributionPlaceGroup the related object to use as filter
     * @param string                                                                $comparison             Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceGroup($distributionPlaceGroup, $comparison = null)
    {
        if ($distributionPlaceGroup instanceof \API\Models\DistributionPlace\DistributionPlaceGroup) {
            return $this
                ->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceGroup->getDistributionPlaceid(), $comparison);
        } elseif ($distributionPlaceGroup instanceof ObjectCollection) {
            return $this
                ->useDistributionPlaceGroupQuery()
                ->filterByPrimaryKeys($distributionPlaceGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionPlaceGroup() only accepts arguments of type \API\Models\DistributionPlace\DistributionPlaceGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlaceGroup relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function joinDistributionPlaceGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlaceGroup');

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
            $this->addJoinObject($join, 'DistributionPlaceGroup');
        }

        return $this;
    }

    /**
     * Use the DistributionPlaceGroup relation DistributionPlaceGroup object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionPlaceGroupQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlaceGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlaceGroup', '\API\Models\DistributionPlace\DistributionPlaceGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlaceUser object
     *
     * @param \API\Models\DistributionPlace\DistributionPlaceUser|ObjectCollection $distributionPlaceUser the related object to use as filter
     * @param string                                                               $comparison            Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceUser($distributionPlaceUser, $comparison = null)
    {
        if ($distributionPlaceUser instanceof \API\Models\DistributionPlace\DistributionPlaceUser) {
            return $this
                ->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceUser->getDistributionPlaceid(), $comparison);
        } elseif ($distributionPlaceUser instanceof ObjectCollection) {
            return $this
                ->useDistributionPlaceUserQuery()
                ->filterByPrimaryKeys($distributionPlaceUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionPlaceUser() only accepts arguments of type \API\Models\DistributionPlace\DistributionPlaceUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlaceUser relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function joinDistributionPlaceUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlaceUser');

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
            $this->addJoinObject($join, 'DistributionPlaceUser');
        }

        return $this;
    }

    /**
     * Use the DistributionPlaceUser relation DistributionPlaceUser object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionPlaceUserQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlaceUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlaceUser', '\API\Models\DistributionPlace\DistributionPlaceUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param ChildDistributionPlace $distributionPlace Object to remove from the list of results
     *
     * @return $this|ChildDistributionPlaceQuery The current query, for fluid interface
     */
    public function prune($distributionPlace = null)
    {
        if ($distributionPlace) {
            $this->addUsingAlias(DistributionPlaceTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->getDistributionPlaceid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distribution_place table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con) {
                $affectedRows = 0; // initialize var to track total num of affected rows
                $affectedRows += parent::doDeleteAll($con);
                // Because this db requires some delete cascade/set null emulation, we have to
                // clear the cached instance *after* the emulation has happened (since
                // instances get re-added by the select statement contained therein).
                DistributionPlaceTableMap::clearInstancePool();
                DistributionPlaceTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionPlaceTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con, $criteria) {
                $affectedRows = 0; // initialize var to track total num of affected rows

                DistributionPlaceTableMap::removeInstanceFromPool($criteria);

                $affectedRows += ModelCriteria::delete($con);
                DistributionPlaceTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }
} // DistributionPlaceQuery
