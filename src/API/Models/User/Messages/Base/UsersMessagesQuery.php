<?php

namespace API\Models\User\Messages\Base;

use \Exception;
use \PDO;
use API\Models\Event\EventsUser;
use API\Models\User\Messages\UsersMessages as ChildUsersMessages;
use API\Models\User\Messages\UsersMessagesQuery as ChildUsersMessagesQuery;
use API\Models\User\Messages\Map\UsersMessagesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'users_messages' table.
 *
 *
 *
 * @method     ChildUsersMessagesQuery orderByUsersMessageid($order = Criteria::ASC) Order by the users_messageid column
 * @method     ChildUsersMessagesQuery orderByFromEventsUserid($order = Criteria::ASC) Order by the from_events_userid column
 * @method     ChildUsersMessagesQuery orderByToEventsUserid($order = Criteria::ASC) Order by the to_events_userid column
 * @method     ChildUsersMessagesQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildUsersMessagesQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildUsersMessagesQuery orderByReaded($order = Criteria::ASC) Order by the readed column
 *
 * @method     ChildUsersMessagesQuery groupByUsersMessageid() Group by the users_messageid column
 * @method     ChildUsersMessagesQuery groupByFromEventsUserid() Group by the from_events_userid column
 * @method     ChildUsersMessagesQuery groupByToEventsUserid() Group by the to_events_userid column
 * @method     ChildUsersMessagesQuery groupByMessage() Group by the message column
 * @method     ChildUsersMessagesQuery groupByDate() Group by the date column
 * @method     ChildUsersMessagesQuery groupByReaded() Group by the readed column
 *
 * @method     ChildUsersMessagesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUsersMessagesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUsersMessagesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUsersMessagesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUsersMessagesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUsersMessagesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUsersMessagesQuery leftJoinEventsUserRelatedByFromEventsUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsUserRelatedByFromEventsUserid relation
 * @method     ChildUsersMessagesQuery rightJoinEventsUserRelatedByFromEventsUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsUserRelatedByFromEventsUserid relation
 * @method     ChildUsersMessagesQuery innerJoinEventsUserRelatedByFromEventsUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsUserRelatedByFromEventsUserid relation
 *
 * @method     ChildUsersMessagesQuery joinWithEventsUserRelatedByFromEventsUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsUserRelatedByFromEventsUserid relation
 *
 * @method     ChildUsersMessagesQuery leftJoinWithEventsUserRelatedByFromEventsUserid() Adds a LEFT JOIN clause and with to the query using the EventsUserRelatedByFromEventsUserid relation
 * @method     ChildUsersMessagesQuery rightJoinWithEventsUserRelatedByFromEventsUserid() Adds a RIGHT JOIN clause and with to the query using the EventsUserRelatedByFromEventsUserid relation
 * @method     ChildUsersMessagesQuery innerJoinWithEventsUserRelatedByFromEventsUserid() Adds a INNER JOIN clause and with to the query using the EventsUserRelatedByFromEventsUserid relation
 *
 * @method     ChildUsersMessagesQuery leftJoinEventsUserRelatedByToEventsUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsUserRelatedByToEventsUserid relation
 * @method     ChildUsersMessagesQuery rightJoinEventsUserRelatedByToEventsUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsUserRelatedByToEventsUserid relation
 * @method     ChildUsersMessagesQuery innerJoinEventsUserRelatedByToEventsUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsUserRelatedByToEventsUserid relation
 *
 * @method     ChildUsersMessagesQuery joinWithEventsUserRelatedByToEventsUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsUserRelatedByToEventsUserid relation
 *
 * @method     ChildUsersMessagesQuery leftJoinWithEventsUserRelatedByToEventsUserid() Adds a LEFT JOIN clause and with to the query using the EventsUserRelatedByToEventsUserid relation
 * @method     ChildUsersMessagesQuery rightJoinWithEventsUserRelatedByToEventsUserid() Adds a RIGHT JOIN clause and with to the query using the EventsUserRelatedByToEventsUserid relation
 * @method     ChildUsersMessagesQuery innerJoinWithEventsUserRelatedByToEventsUserid() Adds a INNER JOIN clause and with to the query using the EventsUserRelatedByToEventsUserid relation
 *
 * @method     \API\Models\Event\EventsUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUsersMessages findOne(ConnectionInterface $con = null) Return the first ChildUsersMessages matching the query
 * @method     ChildUsersMessages findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUsersMessages matching the query, or a new ChildUsersMessages object populated from the query conditions when no match is found
 *
 * @method     ChildUsersMessages findOneByUsersMessageid(int $users_messageid) Return the first ChildUsersMessages filtered by the users_messageid column
 * @method     ChildUsersMessages findOneByFromEventsUserid(int $from_events_userid) Return the first ChildUsersMessages filtered by the from_events_userid column
 * @method     ChildUsersMessages findOneByToEventsUserid(int $to_events_userid) Return the first ChildUsersMessages filtered by the to_events_userid column
 * @method     ChildUsersMessages findOneByMessage(string $message) Return the first ChildUsersMessages filtered by the message column
 * @method     ChildUsersMessages findOneByDate(string $date) Return the first ChildUsersMessages filtered by the date column
 * @method     ChildUsersMessages findOneByReaded(boolean $readed) Return the first ChildUsersMessages filtered by the readed column *

 * @method     ChildUsersMessages requirePk($key, ConnectionInterface $con = null) Return the ChildUsersMessages by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersMessages requireOne(ConnectionInterface $con = null) Return the first ChildUsersMessages matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsersMessages requireOneByUsersMessageid(int $users_messageid) Return the first ChildUsersMessages filtered by the users_messageid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersMessages requireOneByFromEventsUserid(int $from_events_userid) Return the first ChildUsersMessages filtered by the from_events_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersMessages requireOneByToEventsUserid(int $to_events_userid) Return the first ChildUsersMessages filtered by the to_events_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersMessages requireOneByMessage(string $message) Return the first ChildUsersMessages filtered by the message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersMessages requireOneByDate(string $date) Return the first ChildUsersMessages filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersMessages requireOneByReaded(boolean $readed) Return the first ChildUsersMessages filtered by the readed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsersMessages[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUsersMessages objects based on current ModelCriteria
 * @method     ChildUsersMessages[]|ObjectCollection findByUsersMessageid(int $users_messageid) Return ChildUsersMessages objects filtered by the users_messageid column
 * @method     ChildUsersMessages[]|ObjectCollection findByFromEventsUserid(int $from_events_userid) Return ChildUsersMessages objects filtered by the from_events_userid column
 * @method     ChildUsersMessages[]|ObjectCollection findByToEventsUserid(int $to_events_userid) Return ChildUsersMessages objects filtered by the to_events_userid column
 * @method     ChildUsersMessages[]|ObjectCollection findByMessage(string $message) Return ChildUsersMessages objects filtered by the message column
 * @method     ChildUsersMessages[]|ObjectCollection findByDate(string $date) Return ChildUsersMessages objects filtered by the date column
 * @method     ChildUsersMessages[]|ObjectCollection findByReaded(boolean $readed) Return ChildUsersMessages objects filtered by the readed column
 * @method     ChildUsersMessages[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UsersMessagesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\User\Messages\Base\UsersMessagesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\User\\Messages\\UsersMessages', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUsersMessagesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUsersMessagesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUsersMessagesQuery) {
            return $criteria;
        }
        $query = new ChildUsersMessagesQuery();
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
     * @param array[$users_messageid, $to_events_userid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUsersMessages|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UsersMessagesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UsersMessagesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildUsersMessages A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT users_messageid, from_events_userid, to_events_userid, message, date, readed FROM users_messages WHERE users_messageid = :p0 AND to_events_userid = :p1';
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
            /** @var ChildUsersMessages $obj */
            $obj = new ChildUsersMessages();
            $obj->hydrate($row);
            UsersMessagesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildUsersMessages|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UsersMessagesTableMap::COL_USERS_MESSAGEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UsersMessagesTableMap::COL_USERS_MESSAGEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the users_messageid column
     *
     * Example usage:
     * <code>
     * $query->filterByUsersMessageid(1234); // WHERE users_messageid = 1234
     * $query->filterByUsersMessageid(array(12, 34)); // WHERE users_messageid IN (12, 34)
     * $query->filterByUsersMessageid(array('min' => 12)); // WHERE users_messageid > 12
     * </code>
     *
     * @param     mixed $usersMessageid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByUsersMessageid($usersMessageid = null, $comparison = null)
    {
        if (is_array($usersMessageid)) {
            $useMinMax = false;
            if (isset($usersMessageid['min'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_USERS_MESSAGEID, $usersMessageid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($usersMessageid['max'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_USERS_MESSAGEID, $usersMessageid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersMessagesTableMap::COL_USERS_MESSAGEID, $usersMessageid, $comparison);
    }

    /**
     * Filter the query on the from_events_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByFromEventsUserid(1234); // WHERE from_events_userid = 1234
     * $query->filterByFromEventsUserid(array(12, 34)); // WHERE from_events_userid IN (12, 34)
     * $query->filterByFromEventsUserid(array('min' => 12)); // WHERE from_events_userid > 12
     * </code>
     *
     * @see       filterByEventsUserRelatedByFromEventsUserid()
     *
     * @param     mixed $fromEventsUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByFromEventsUserid($fromEventsUserid = null, $comparison = null)
    {
        if (is_array($fromEventsUserid)) {
            $useMinMax = false;
            if (isset($fromEventsUserid['min'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_FROM_EVENTS_USERID, $fromEventsUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fromEventsUserid['max'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_FROM_EVENTS_USERID, $fromEventsUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersMessagesTableMap::COL_FROM_EVENTS_USERID, $fromEventsUserid, $comparison);
    }

    /**
     * Filter the query on the to_events_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByToEventsUserid(1234); // WHERE to_events_userid = 1234
     * $query->filterByToEventsUserid(array(12, 34)); // WHERE to_events_userid IN (12, 34)
     * $query->filterByToEventsUserid(array('min' => 12)); // WHERE to_events_userid > 12
     * </code>
     *
     * @see       filterByEventsUserRelatedByToEventsUserid()
     *
     * @param     mixed $toEventsUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByToEventsUserid($toEventsUserid = null, $comparison = null)
    {
        if (is_array($toEventsUserid)) {
            $useMinMax = false;
            if (isset($toEventsUserid['min'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $toEventsUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($toEventsUserid['max'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $toEventsUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $toEventsUserid, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%', Criteria::LIKE); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersMessagesTableMap::COL_MESSAGE, $message, $comparison);
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
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(UsersMessagesTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersMessagesTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the readed column
     *
     * Example usage:
     * <code>
     * $query->filterByReaded(true); // WHERE readed = true
     * $query->filterByReaded('yes'); // WHERE readed = true
     * </code>
     *
     * @param     boolean|string $readed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByReaded($readed = null, $comparison = null)
    {
        if (is_string($readed)) {
            $readed = in_array(strtolower($readed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UsersMessagesTableMap::COL_READED, $readed, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\EventsUser object
     *
     * @param \API\Models\Event\EventsUser|ObjectCollection $eventsUser The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByEventsUserRelatedByFromEventsUserid($eventsUser, $comparison = null)
    {
        if ($eventsUser instanceof \API\Models\Event\EventsUser) {
            return $this
                ->addUsingAlias(UsersMessagesTableMap::COL_FROM_EVENTS_USERID, $eventsUser->getEventsUserid(), $comparison);
        } elseif ($eventsUser instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UsersMessagesTableMap::COL_FROM_EVENTS_USERID, $eventsUser->toKeyValue('EventsUserid', 'EventsUserid'), $comparison);
        } else {
            throw new PropelException('filterByEventsUserRelatedByFromEventsUserid() only accepts arguments of type \API\Models\Event\EventsUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsUserRelatedByFromEventsUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function joinEventsUserRelatedByFromEventsUserid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsUserRelatedByFromEventsUserid');

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
            $this->addJoinObject($join, 'EventsUserRelatedByFromEventsUserid');
        }

        return $this;
    }

    /**
     * Use the EventsUserRelatedByFromEventsUserid relation EventsUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventsUserQuery A secondary query class using the current class as primary query
     */
    public function useEventsUserRelatedByFromEventsUseridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEventsUserRelatedByFromEventsUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsUserRelatedByFromEventsUserid', '\API\Models\Event\EventsUserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventsUser object
     *
     * @param \API\Models\Event\EventsUser|ObjectCollection $eventsUser The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function filterByEventsUserRelatedByToEventsUserid($eventsUser, $comparison = null)
    {
        if ($eventsUser instanceof \API\Models\Event\EventsUser) {
            return $this
                ->addUsingAlias(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $eventsUser->getEventsUserid(), $comparison);
        } elseif ($eventsUser instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UsersMessagesTableMap::COL_TO_EVENTS_USERID, $eventsUser->toKeyValue('EventsUserid', 'EventsUserid'), $comparison);
        } else {
            throw new PropelException('filterByEventsUserRelatedByToEventsUserid() only accepts arguments of type \API\Models\Event\EventsUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsUserRelatedByToEventsUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function joinEventsUserRelatedByToEventsUserid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsUserRelatedByToEventsUserid');

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
            $this->addJoinObject($join, 'EventsUserRelatedByToEventsUserid');
        }

        return $this;
    }

    /**
     * Use the EventsUserRelatedByToEventsUserid relation EventsUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventsUserQuery A secondary query class using the current class as primary query
     */
    public function useEventsUserRelatedByToEventsUseridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsUserRelatedByToEventsUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsUserRelatedByToEventsUserid', '\API\Models\Event\EventsUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUsersMessages $usersMessages Object to remove from the list of results
     *
     * @return $this|ChildUsersMessagesQuery The current query, for fluid interface
     */
    public function prune($usersMessages = null)
    {
        if ($usersMessages) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UsersMessagesTableMap::COL_USERS_MESSAGEID), $usersMessages->getUsersMessageid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UsersMessagesTableMap::COL_TO_EVENTS_USERID), $usersMessages->getToEventsUserid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users_messages table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersMessagesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UsersMessagesTableMap::clearInstancePool();
            UsersMessagesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersMessagesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UsersMessagesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UsersMessagesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UsersMessagesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UsersMessagesQuery
