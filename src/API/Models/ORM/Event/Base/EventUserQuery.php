<?php

namespace API\Models\ORM\Event\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Event\EventUser as ChildEventUser;
use API\Models\ORM\Event\EventUserQuery as ChildEventUserQuery;
use API\Models\ORM\Event\Map\EventUserTableMap;
use API\Models\ORM\User\User;
use API\Models\ORM\User\Message\UserMessage;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event_user' table.
 *
 *
 *
 * @method     ChildEventUserQuery orderByEventUserid($order = Criteria::ASC) Order by the event_userid column
 * @method     ChildEventUserQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildEventUserQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildEventUserQuery orderByUserRoles($order = Criteria::ASC) Order by the user_roles column
 * @method     ChildEventUserQuery orderByBeginMoney($order = Criteria::ASC) Order by the begin_money column
 *
 * @method     ChildEventUserQuery groupByEventUserid() Group by the event_userid column
 * @method     ChildEventUserQuery groupByEventid() Group by the eventid column
 * @method     ChildEventUserQuery groupByUserid() Group by the userid column
 * @method     ChildEventUserQuery groupByUserRoles() Group by the user_roles column
 * @method     ChildEventUserQuery groupByBeginMoney() Group by the begin_money column
 *
 * @method     ChildEventUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventUserQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildEventUserQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildEventUserQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildEventUserQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildEventUserQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildEventUserQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildEventUserQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildEventUserQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildEventUserQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildEventUserQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildEventUserQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildEventUserQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildEventUserQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildEventUserQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildEventUserQuery leftJoinUserMessageRelatedByFromEventUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserMessageRelatedByFromEventUserid relation
 * @method     ChildEventUserQuery rightJoinUserMessageRelatedByFromEventUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserMessageRelatedByFromEventUserid relation
 * @method     ChildEventUserQuery innerJoinUserMessageRelatedByFromEventUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the UserMessageRelatedByFromEventUserid relation
 *
 * @method     ChildEventUserQuery joinWithUserMessageRelatedByFromEventUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserMessageRelatedByFromEventUserid relation
 *
 * @method     ChildEventUserQuery leftJoinWithUserMessageRelatedByFromEventUserid() Adds a LEFT JOIN clause and with to the query using the UserMessageRelatedByFromEventUserid relation
 * @method     ChildEventUserQuery rightJoinWithUserMessageRelatedByFromEventUserid() Adds a RIGHT JOIN clause and with to the query using the UserMessageRelatedByFromEventUserid relation
 * @method     ChildEventUserQuery innerJoinWithUserMessageRelatedByFromEventUserid() Adds a INNER JOIN clause and with to the query using the UserMessageRelatedByFromEventUserid relation
 *
 * @method     ChildEventUserQuery leftJoinUserMessageRelatedByToEventUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserMessageRelatedByToEventUserid relation
 * @method     ChildEventUserQuery rightJoinUserMessageRelatedByToEventUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserMessageRelatedByToEventUserid relation
 * @method     ChildEventUserQuery innerJoinUserMessageRelatedByToEventUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the UserMessageRelatedByToEventUserid relation
 *
 * @method     ChildEventUserQuery joinWithUserMessageRelatedByToEventUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserMessageRelatedByToEventUserid relation
 *
 * @method     ChildEventUserQuery leftJoinWithUserMessageRelatedByToEventUserid() Adds a LEFT JOIN clause and with to the query using the UserMessageRelatedByToEventUserid relation
 * @method     ChildEventUserQuery rightJoinWithUserMessageRelatedByToEventUserid() Adds a RIGHT JOIN clause and with to the query using the UserMessageRelatedByToEventUserid relation
 * @method     ChildEventUserQuery innerJoinWithUserMessageRelatedByToEventUserid() Adds a INNER JOIN clause and with to the query using the UserMessageRelatedByToEventUserid relation
 *
 * @method     \API\Models\ORM\Event\EventQuery|\API\Models\ORM\User\UserQuery|\API\Models\ORM\User\Message\UserMessageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventUser findOne(ConnectionInterface $con = null) Return the first ChildEventUser matching the query
 * @method     ChildEventUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventUser matching the query, or a new ChildEventUser object populated from the query conditions when no match is found
 *
 * @method     ChildEventUser findOneByEventUserid(int $event_userid) Return the first ChildEventUser filtered by the event_userid column
 * @method     ChildEventUser findOneByEventid(int $eventid) Return the first ChildEventUser filtered by the eventid column
 * @method     ChildEventUser findOneByUserid(int $userid) Return the first ChildEventUser filtered by the userid column
 * @method     ChildEventUser findOneByUserRoles(int $user_roles) Return the first ChildEventUser filtered by the user_roles column
 * @method     ChildEventUser findOneByBeginMoney(string $begin_money) Return the first ChildEventUser filtered by the begin_money column *

 * @method     ChildEventUser requirePk($key, ConnectionInterface $con = null) Return the ChildEventUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUser requireOne(ConnectionInterface $con = null) Return the first ChildEventUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventUser requireOneByEventUserid(int $event_userid) Return the first ChildEventUser filtered by the event_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUser requireOneByEventid(int $eventid) Return the first ChildEventUser filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUser requireOneByUserid(int $userid) Return the first ChildEventUser filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUser requireOneByUserRoles(int $user_roles) Return the first ChildEventUser filtered by the user_roles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUser requireOneByBeginMoney(string $begin_money) Return the first ChildEventUser filtered by the begin_money column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventUser objects based on current ModelCriteria
 * @method     ChildEventUser[]|ObjectCollection findByEventUserid(int $event_userid) Return ChildEventUser objects filtered by the event_userid column
 * @method     ChildEventUser[]|ObjectCollection findByEventid(int $eventid) Return ChildEventUser objects filtered by the eventid column
 * @method     ChildEventUser[]|ObjectCollection findByUserid(int $userid) Return ChildEventUser objects filtered by the userid column
 * @method     ChildEventUser[]|ObjectCollection findByUserRoles(int $user_roles) Return ChildEventUser objects filtered by the user_roles column
 * @method     ChildEventUser[]|ObjectCollection findByBeginMoney(string $begin_money) Return ChildEventUser objects filtered by the begin_money column
 * @method     ChildEventUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventUserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Event\Base\EventUserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Event\\EventUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventUserQuery) {
            return $criteria;
        }
        $query = new ChildEventUserQuery();
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
     * @return ChildEventUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventUserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventUserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEventUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT event_userid, eventid, userid, user_roles, begin_money FROM event_user WHERE event_userid = :p0';
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
            /** @var ChildEventUser $obj */
            $obj = new ChildEventUser();
            $obj->hydrate($row);
            EventUserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEventUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the event_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventUserid(1234); // WHERE event_userid = 1234
     * $query->filterByEventUserid(array(12, 34)); // WHERE event_userid IN (12, 34)
     * $query->filterByEventUserid(array('min' => 12)); // WHERE event_userid > 12
     * </code>
     *
     * @param     mixed $eventUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByEventUserid($eventUserid = null, $comparison = null)
    {
        if (is_array($eventUserid)) {
            $useMinMax = false;
            if (isset($eventUserid['min'])) {
                $this->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $eventUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventUserid['max'])) {
                $this->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $eventUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $eventUserid, $comparison);
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
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(EventUserTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(EventUserTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUserTableMap::COL_EVENTID, $eventid, $comparison);
    }

    /**
     * Filter the query on the userid column
     *
     * Example usage:
     * <code>
     * $query->filterByUserid(1234); // WHERE userid = 1234
     * $query->filterByUserid(array(12, 34)); // WHERE userid IN (12, 34)
     * $query->filterByUserid(array('min' => 12)); // WHERE userid > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(EventUserTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(EventUserTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUserTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the user_roles column
     *
     * Example usage:
     * <code>
     * $query->filterByUserRoles(1234); // WHERE user_roles = 1234
     * $query->filterByUserRoles(array(12, 34)); // WHERE user_roles IN (12, 34)
     * $query->filterByUserRoles(array('min' => 12)); // WHERE user_roles > 12
     * </code>
     *
     * @param     mixed $userRoles The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByUserRoles($userRoles = null, $comparison = null)
    {
        if (is_array($userRoles)) {
            $useMinMax = false;
            if (isset($userRoles['min'])) {
                $this->addUsingAlias(EventUserTableMap::COL_USER_ROLES, $userRoles['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userRoles['max'])) {
                $this->addUsingAlias(EventUserTableMap::COL_USER_ROLES, $userRoles['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUserTableMap::COL_USER_ROLES, $userRoles, $comparison);
    }

    /**
     * Filter the query on the begin_money column
     *
     * Example usage:
     * <code>
     * $query->filterByBeginMoney(1234); // WHERE begin_money = 1234
     * $query->filterByBeginMoney(array(12, 34)); // WHERE begin_money IN (12, 34)
     * $query->filterByBeginMoney(array('min' => 12)); // WHERE begin_money > 12
     * </code>
     *
     * @param     mixed $beginMoney The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByBeginMoney($beginMoney = null, $comparison = null)
    {
        if (is_array($beginMoney)) {
            $useMinMax = false;
            if (isset($beginMoney['min'])) {
                $this->addUsingAlias(EventUserTableMap::COL_BEGIN_MONEY, $beginMoney['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($beginMoney['max'])) {
                $this->addUsingAlias(EventUserTableMap::COL_BEGIN_MONEY, $beginMoney['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUserTableMap::COL_BEGIN_MONEY, $beginMoney, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Event\Event object
     *
     * @param \API\Models\ORM\Event\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \API\Models\ORM\Event\Event) {
            return $this
                ->addUsingAlias(EventUserTableMap::COL_EVENTID, $event->getEventid(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventUserTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
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
     * @return $this|ChildEventUserQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\ORM\User\User object
     *
     * @param \API\Models\ORM\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\ORM\User\User) {
            return $this
                ->addUsingAlias(EventUserTableMap::COL_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventUserTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\ORM\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\ORM\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\User\Message\UserMessage object
     *
     * @param \API\Models\ORM\User\Message\UserMessage|ObjectCollection $userMessage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByUserMessageRelatedByFromEventUserid($userMessage, $comparison = null)
    {
        if ($userMessage instanceof \API\Models\ORM\User\Message\UserMessage) {
            return $this
                ->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $userMessage->getFromEventUserid(), $comparison);
        } elseif ($userMessage instanceof ObjectCollection) {
            return $this
                ->useUserMessageRelatedByFromEventUseridQuery()
                ->filterByPrimaryKeys($userMessage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserMessageRelatedByFromEventUserid() only accepts arguments of type \API\Models\ORM\User\Message\UserMessage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserMessageRelatedByFromEventUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function joinUserMessageRelatedByFromEventUserid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserMessageRelatedByFromEventUserid');

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
            $this->addJoinObject($join, 'UserMessageRelatedByFromEventUserid');
        }

        return $this;
    }

    /**
     * Use the UserMessageRelatedByFromEventUserid relation UserMessage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\User\Message\UserMessageQuery A secondary query class using the current class as primary query
     */
    public function useUserMessageRelatedByFromEventUseridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserMessageRelatedByFromEventUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserMessageRelatedByFromEventUserid', '\API\Models\ORM\User\Message\UserMessageQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\User\Message\UserMessage object
     *
     * @param \API\Models\ORM\User\Message\UserMessage|ObjectCollection $userMessage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventUserQuery The current query, for fluid interface
     */
    public function filterByUserMessageRelatedByToEventUserid($userMessage, $comparison = null)
    {
        if ($userMessage instanceof \API\Models\ORM\User\Message\UserMessage) {
            return $this
                ->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $userMessage->getToEventUserid(), $comparison);
        } elseif ($userMessage instanceof ObjectCollection) {
            return $this
                ->useUserMessageRelatedByToEventUseridQuery()
                ->filterByPrimaryKeys($userMessage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserMessageRelatedByToEventUserid() only accepts arguments of type \API\Models\ORM\User\Message\UserMessage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserMessageRelatedByToEventUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function joinUserMessageRelatedByToEventUserid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserMessageRelatedByToEventUserid');

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
            $this->addJoinObject($join, 'UserMessageRelatedByToEventUserid');
        }

        return $this;
    }

    /**
     * Use the UserMessageRelatedByToEventUserid relation UserMessage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\User\Message\UserMessageQuery A secondary query class using the current class as primary query
     */
    public function useUserMessageRelatedByToEventUseridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserMessageRelatedByToEventUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserMessageRelatedByToEventUserid', '\API\Models\ORM\User\Message\UserMessageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventUser $eventUser Object to remove from the list of results
     *
     * @return $this|ChildEventUserQuery The current query, for fluid interface
     */
    public function prune($eventUser = null)
    {
        if ($eventUser) {
            $this->addUsingAlias(EventUserTableMap::COL_EVENT_USERID, $eventUser->getEventUserid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventUserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventUserTableMap::clearInstancePool();
            EventUserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventUserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventUserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventUserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventUserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventUserQuery
