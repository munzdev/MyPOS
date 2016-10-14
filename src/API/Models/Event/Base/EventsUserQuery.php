<?php

namespace API\Models\Event\Base;

use \Exception;
use \PDO;
use API\Models\Event\EventsUser as ChildEventsUser;
use API\Models\Event\EventsUserQuery as ChildEventsUserQuery;
use API\Models\Event\Map\EventsUserTableMap;
use API\Models\User\Users;
use API\Models\User\Messages\UsersMessages;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'events_user' table.
 *
 *
 *
 * @method     ChildEventsUserQuery orderByEventsUserid($order = Criteria::ASC) Order by the events_userid column
 * @method     ChildEventsUserQuery orderByEventid($order = Criteria::ASC) Order by the eventid column
 * @method     ChildEventsUserQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildEventsUserQuery orderByUserRoles($order = Criteria::ASC) Order by the user_roles column
 * @method     ChildEventsUserQuery orderByBeginMoney($order = Criteria::ASC) Order by the begin_money column
 *
 * @method     ChildEventsUserQuery groupByEventsUserid() Group by the events_userid column
 * @method     ChildEventsUserQuery groupByEventid() Group by the eventid column
 * @method     ChildEventsUserQuery groupByUserid() Group by the userid column
 * @method     ChildEventsUserQuery groupByUserRoles() Group by the user_roles column
 * @method     ChildEventsUserQuery groupByBeginMoney() Group by the begin_money column
 *
 * @method     ChildEventsUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventsUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventsUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventsUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventsUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventsUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventsUserQuery leftJoinEvents($relationAlias = null) Adds a LEFT JOIN clause to the query using the Events relation
 * @method     ChildEventsUserQuery rightJoinEvents($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Events relation
 * @method     ChildEventsUserQuery innerJoinEvents($relationAlias = null) Adds a INNER JOIN clause to the query using the Events relation
 *
 * @method     ChildEventsUserQuery joinWithEvents($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Events relation
 *
 * @method     ChildEventsUserQuery leftJoinWithEvents() Adds a LEFT JOIN clause and with to the query using the Events relation
 * @method     ChildEventsUserQuery rightJoinWithEvents() Adds a RIGHT JOIN clause and with to the query using the Events relation
 * @method     ChildEventsUserQuery innerJoinWithEvents() Adds a INNER JOIN clause and with to the query using the Events relation
 *
 * @method     ChildEventsUserQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildEventsUserQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildEventsUserQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildEventsUserQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildEventsUserQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildEventsUserQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildEventsUserQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildEventsUserQuery leftJoinUsersMessagesRelatedByFromEventsUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the UsersMessagesRelatedByFromEventsUserid relation
 * @method     ChildEventsUserQuery rightJoinUsersMessagesRelatedByFromEventsUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UsersMessagesRelatedByFromEventsUserid relation
 * @method     ChildEventsUserQuery innerJoinUsersMessagesRelatedByFromEventsUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the UsersMessagesRelatedByFromEventsUserid relation
 *
 * @method     ChildEventsUserQuery joinWithUsersMessagesRelatedByFromEventsUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UsersMessagesRelatedByFromEventsUserid relation
 *
 * @method     ChildEventsUserQuery leftJoinWithUsersMessagesRelatedByFromEventsUserid() Adds a LEFT JOIN clause and with to the query using the UsersMessagesRelatedByFromEventsUserid relation
 * @method     ChildEventsUserQuery rightJoinWithUsersMessagesRelatedByFromEventsUserid() Adds a RIGHT JOIN clause and with to the query using the UsersMessagesRelatedByFromEventsUserid relation
 * @method     ChildEventsUserQuery innerJoinWithUsersMessagesRelatedByFromEventsUserid() Adds a INNER JOIN clause and with to the query using the UsersMessagesRelatedByFromEventsUserid relation
 *
 * @method     ChildEventsUserQuery leftJoinUsersMessagesRelatedByToEventsUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the UsersMessagesRelatedByToEventsUserid relation
 * @method     ChildEventsUserQuery rightJoinUsersMessagesRelatedByToEventsUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UsersMessagesRelatedByToEventsUserid relation
 * @method     ChildEventsUserQuery innerJoinUsersMessagesRelatedByToEventsUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the UsersMessagesRelatedByToEventsUserid relation
 *
 * @method     ChildEventsUserQuery joinWithUsersMessagesRelatedByToEventsUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UsersMessagesRelatedByToEventsUserid relation
 *
 * @method     ChildEventsUserQuery leftJoinWithUsersMessagesRelatedByToEventsUserid() Adds a LEFT JOIN clause and with to the query using the UsersMessagesRelatedByToEventsUserid relation
 * @method     ChildEventsUserQuery rightJoinWithUsersMessagesRelatedByToEventsUserid() Adds a RIGHT JOIN clause and with to the query using the UsersMessagesRelatedByToEventsUserid relation
 * @method     ChildEventsUserQuery innerJoinWithUsersMessagesRelatedByToEventsUserid() Adds a INNER JOIN clause and with to the query using the UsersMessagesRelatedByToEventsUserid relation
 *
 * @method     \API\Models\Event\EventsQuery|\API\Models\User\UsersQuery|\API\Models\User\Messages\UsersMessagesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventsUser findOne(ConnectionInterface $con = null) Return the first ChildEventsUser matching the query
 * @method     ChildEventsUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventsUser matching the query, or a new ChildEventsUser object populated from the query conditions when no match is found
 *
 * @method     ChildEventsUser findOneByEventsUserid(int $events_userid) Return the first ChildEventsUser filtered by the events_userid column
 * @method     ChildEventsUser findOneByEventid(int $eventid) Return the first ChildEventsUser filtered by the eventid column
 * @method     ChildEventsUser findOneByUserid(int $userid) Return the first ChildEventsUser filtered by the userid column
 * @method     ChildEventsUser findOneByUserRoles(int $user_roles) Return the first ChildEventsUser filtered by the user_roles column
 * @method     ChildEventsUser findOneByBeginMoney(string $begin_money) Return the first ChildEventsUser filtered by the begin_money column *

 * @method     ChildEventsUser requirePk($key, ConnectionInterface $con = null) Return the ChildEventsUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsUser requireOne(ConnectionInterface $con = null) Return the first ChildEventsUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventsUser requireOneByEventsUserid(int $events_userid) Return the first ChildEventsUser filtered by the events_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsUser requireOneByEventid(int $eventid) Return the first ChildEventsUser filtered by the eventid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsUser requireOneByUserid(int $userid) Return the first ChildEventsUser filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsUser requireOneByUserRoles(int $user_roles) Return the first ChildEventsUser filtered by the user_roles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventsUser requireOneByBeginMoney(string $begin_money) Return the first ChildEventsUser filtered by the begin_money column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventsUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventsUser objects based on current ModelCriteria
 * @method     ChildEventsUser[]|ObjectCollection findByEventsUserid(int $events_userid) Return ChildEventsUser objects filtered by the events_userid column
 * @method     ChildEventsUser[]|ObjectCollection findByEventid(int $eventid) Return ChildEventsUser objects filtered by the eventid column
 * @method     ChildEventsUser[]|ObjectCollection findByUserid(int $userid) Return ChildEventsUser objects filtered by the userid column
 * @method     ChildEventsUser[]|ObjectCollection findByUserRoles(int $user_roles) Return ChildEventsUser objects filtered by the user_roles column
 * @method     ChildEventsUser[]|ObjectCollection findByBeginMoney(string $begin_money) Return ChildEventsUser objects filtered by the begin_money column
 * @method     ChildEventsUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventsUserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Event\Base\EventsUserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Event\\EventsUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventsUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventsUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventsUserQuery) {
            return $criteria;
        }
        $query = new ChildEventsUserQuery();
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
     * @param array[$events_userid, $eventid, $userid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildEventsUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventsUserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventsUserTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildEventsUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT events_userid, eventid, userid, user_roles, begin_money FROM events_user WHERE events_userid = :p0 AND eventid = :p1 AND userid = :p2';
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
            /** @var ChildEventsUser $obj */
            $obj = new ChildEventsUser();
            $obj->hydrate($row);
            EventsUserTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildEventsUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(EventsUserTableMap::COL_EVENTS_USERID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(EventsUserTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(EventsUserTableMap::COL_USERID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(EventsUserTableMap::COL_EVENTS_USERID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(EventsUserTableMap::COL_EVENTID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(EventsUserTableMap::COL_USERID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the events_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventsUserid(1234); // WHERE events_userid = 1234
     * $query->filterByEventsUserid(array(12, 34)); // WHERE events_userid IN (12, 34)
     * $query->filterByEventsUserid(array('min' => 12)); // WHERE events_userid > 12
     * </code>
     *
     * @param     mixed $eventsUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByEventsUserid($eventsUserid = null, $comparison = null)
    {
        if (is_array($eventsUserid)) {
            $useMinMax = false;
            if (isset($eventsUserid['min'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_EVENTS_USERID, $eventsUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventsUserid['max'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_EVENTS_USERID, $eventsUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsUserTableMap::COL_EVENTS_USERID, $eventsUserid, $comparison);
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
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByEventid($eventid = null, $comparison = null)
    {
        if (is_array($eventid)) {
            $useMinMax = false;
            if (isset($eventid['min'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_EVENTID, $eventid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventid['max'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_EVENTID, $eventid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsUserTableMap::COL_EVENTID, $eventid, $comparison);
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
     * @see       filterByUsers()
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsUserTableMap::COL_USERID, $userid, $comparison);
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
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByUserRoles($userRoles = null, $comparison = null)
    {
        if (is_array($userRoles)) {
            $useMinMax = false;
            if (isset($userRoles['min'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_USER_ROLES, $userRoles['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userRoles['max'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_USER_ROLES, $userRoles['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsUserTableMap::COL_USER_ROLES, $userRoles, $comparison);
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
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByBeginMoney($beginMoney = null, $comparison = null)
    {
        if (is_array($beginMoney)) {
            $useMinMax = false;
            if (isset($beginMoney['min'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_BEGIN_MONEY, $beginMoney['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($beginMoney['max'])) {
                $this->addUsingAlias(EventsUserTableMap::COL_BEGIN_MONEY, $beginMoney['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventsUserTableMap::COL_BEGIN_MONEY, $beginMoney, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\Events object
     *
     * @param \API\Models\Event\Events|ObjectCollection $events The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByEvents($events, $comparison = null)
    {
        if ($events instanceof \API\Models\Event\Events) {
            return $this
                ->addUsingAlias(EventsUserTableMap::COL_EVENTID, $events->getEventid(), $comparison);
        } elseif ($events instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventsUserTableMap::COL_EVENTID, $events->toKeyValue('PrimaryKey', 'Eventid'), $comparison);
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
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\User\Users object
     *
     * @param \API\Models\User\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \API\Models\User\Users) {
            return $this
                ->addUsingAlias(EventsUserTableMap::COL_USERID, $users->getUserid(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventsUserTableMap::COL_USERID, $users->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \API\Models\User\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function joinUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Users');

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
            $this->addJoinObject($join, 'Users');
        }

        return $this;
    }

    /**
     * Use the Users relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\User\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\API\Models\User\UsersQuery');
    }

    /**
     * Filter the query by a related \API\Models\User\Messages\UsersMessages object
     *
     * @param \API\Models\User\Messages\UsersMessages|ObjectCollection $usersMessages the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByUsersMessagesRelatedByFromEventsUserid($usersMessages, $comparison = null)
    {
        if ($usersMessages instanceof \API\Models\User\Messages\UsersMessages) {
            return $this
                ->addUsingAlias(EventsUserTableMap::COL_EVENTS_USERID, $usersMessages->getFromEventsUserid(), $comparison);
        } elseif ($usersMessages instanceof ObjectCollection) {
            return $this
                ->useUsersMessagesRelatedByFromEventsUseridQuery()
                ->filterByPrimaryKeys($usersMessages->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUsersMessagesRelatedByFromEventsUserid() only accepts arguments of type \API\Models\User\Messages\UsersMessages or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UsersMessagesRelatedByFromEventsUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function joinUsersMessagesRelatedByFromEventsUserid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UsersMessagesRelatedByFromEventsUserid');

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
            $this->addJoinObject($join, 'UsersMessagesRelatedByFromEventsUserid');
        }

        return $this;
    }

    /**
     * Use the UsersMessagesRelatedByFromEventsUserid relation UsersMessages object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\User\Messages\UsersMessagesQuery A secondary query class using the current class as primary query
     */
    public function useUsersMessagesRelatedByFromEventsUseridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUsersMessagesRelatedByFromEventsUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UsersMessagesRelatedByFromEventsUserid', '\API\Models\User\Messages\UsersMessagesQuery');
    }

    /**
     * Filter the query by a related \API\Models\User\Messages\UsersMessages object
     *
     * @param \API\Models\User\Messages\UsersMessages|ObjectCollection $usersMessages the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventsUserQuery The current query, for fluid interface
     */
    public function filterByUsersMessagesRelatedByToEventsUserid($usersMessages, $comparison = null)
    {
        if ($usersMessages instanceof \API\Models\User\Messages\UsersMessages) {
            return $this
                ->addUsingAlias(EventsUserTableMap::COL_EVENTS_USERID, $usersMessages->getToEventsUserid(), $comparison);
        } elseif ($usersMessages instanceof ObjectCollection) {
            return $this
                ->useUsersMessagesRelatedByToEventsUseridQuery()
                ->filterByPrimaryKeys($usersMessages->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUsersMessagesRelatedByToEventsUserid() only accepts arguments of type \API\Models\User\Messages\UsersMessages or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UsersMessagesRelatedByToEventsUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function joinUsersMessagesRelatedByToEventsUserid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UsersMessagesRelatedByToEventsUserid');

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
            $this->addJoinObject($join, 'UsersMessagesRelatedByToEventsUserid');
        }

        return $this;
    }

    /**
     * Use the UsersMessagesRelatedByToEventsUserid relation UsersMessages object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\User\Messages\UsersMessagesQuery A secondary query class using the current class as primary query
     */
    public function useUsersMessagesRelatedByToEventsUseridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsersMessagesRelatedByToEventsUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UsersMessagesRelatedByToEventsUserid', '\API\Models\User\Messages\UsersMessagesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventsUser $eventsUser Object to remove from the list of results
     *
     * @return $this|ChildEventsUserQuery The current query, for fluid interface
     */
    public function prune($eventsUser = null)
    {
        if ($eventsUser) {
            $this->addCond('pruneCond0', $this->getAliasedColName(EventsUserTableMap::COL_EVENTS_USERID), $eventsUser->getEventsUserid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(EventsUserTableMap::COL_EVENTID), $eventsUser->getEventid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(EventsUserTableMap::COL_USERID), $eventsUser->getUserid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the events_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventsUserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventsUserTableMap::clearInstancePool();
            EventsUserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventsUserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventsUserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventsUserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventsUserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // EventsUserQuery
