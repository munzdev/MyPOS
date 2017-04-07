<?php

namespace API\Models\ORM\User\Message\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Event\EventUser;
use API\Models\ORM\User\Message\UserMessage as ChildUserMessage;
use API\Models\ORM\User\Message\UserMessageQuery as ChildUserMessageQuery;
use API\Models\ORM\User\Message\Map\UserMessageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user_message' table.
 *
 *
 *
 * @method     ChildUserMessageQuery orderByUserMessageid($order = Criteria::ASC) Order by the user_messageid column
 * @method     ChildUserMessageQuery orderByFromEventUserid($order = Criteria::ASC) Order by the from_event_userid column
 * @method     ChildUserMessageQuery orderByToEventUserid($order = Criteria::ASC) Order by the to_event_userid column
 * @method     ChildUserMessageQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildUserMessageQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildUserMessageQuery orderByReaded($order = Criteria::ASC) Order by the readed column
 *
 * @method     ChildUserMessageQuery groupByUserMessageid() Group by the user_messageid column
 * @method     ChildUserMessageQuery groupByFromEventUserid() Group by the from_event_userid column
 * @method     ChildUserMessageQuery groupByToEventUserid() Group by the to_event_userid column
 * @method     ChildUserMessageQuery groupByMessage() Group by the message column
 * @method     ChildUserMessageQuery groupByDate() Group by the date column
 * @method     ChildUserMessageQuery groupByReaded() Group by the readed column
 *
 * @method     ChildUserMessageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserMessageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserMessageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserMessageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserMessageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserMessageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserMessageQuery leftJoinEventUserRelatedByFromEventUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventUserRelatedByFromEventUserid relation
 * @method     ChildUserMessageQuery rightJoinEventUserRelatedByFromEventUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventUserRelatedByFromEventUserid relation
 * @method     ChildUserMessageQuery innerJoinEventUserRelatedByFromEventUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the EventUserRelatedByFromEventUserid relation
 *
 * @method     ChildUserMessageQuery joinWithEventUserRelatedByFromEventUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventUserRelatedByFromEventUserid relation
 *
 * @method     ChildUserMessageQuery leftJoinWithEventUserRelatedByFromEventUserid() Adds a LEFT JOIN clause and with to the query using the EventUserRelatedByFromEventUserid relation
 * @method     ChildUserMessageQuery rightJoinWithEventUserRelatedByFromEventUserid() Adds a RIGHT JOIN clause and with to the query using the EventUserRelatedByFromEventUserid relation
 * @method     ChildUserMessageQuery innerJoinWithEventUserRelatedByFromEventUserid() Adds a INNER JOIN clause and with to the query using the EventUserRelatedByFromEventUserid relation
 *
 * @method     ChildUserMessageQuery leftJoinEventUserRelatedByToEventUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventUserRelatedByToEventUserid relation
 * @method     ChildUserMessageQuery rightJoinEventUserRelatedByToEventUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventUserRelatedByToEventUserid relation
 * @method     ChildUserMessageQuery innerJoinEventUserRelatedByToEventUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the EventUserRelatedByToEventUserid relation
 *
 * @method     ChildUserMessageQuery joinWithEventUserRelatedByToEventUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventUserRelatedByToEventUserid relation
 *
 * @method     ChildUserMessageQuery leftJoinWithEventUserRelatedByToEventUserid() Adds a LEFT JOIN clause and with to the query using the EventUserRelatedByToEventUserid relation
 * @method     ChildUserMessageQuery rightJoinWithEventUserRelatedByToEventUserid() Adds a RIGHT JOIN clause and with to the query using the EventUserRelatedByToEventUserid relation
 * @method     ChildUserMessageQuery innerJoinWithEventUserRelatedByToEventUserid() Adds a INNER JOIN clause and with to the query using the EventUserRelatedByToEventUserid relation
 *
 * @method     \API\Models\ORM\Event\EventUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserMessage findOne(ConnectionInterface $con = null) Return the first ChildUserMessage matching the query
 * @method     ChildUserMessage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserMessage matching the query, or a new ChildUserMessage object populated from the query conditions when no match is found
 *
 * @method     ChildUserMessage findOneByUserMessageid(int $user_messageid) Return the first ChildUserMessage filtered by the user_messageid column
 * @method     ChildUserMessage findOneByFromEventUserid(int $from_event_userid) Return the first ChildUserMessage filtered by the from_event_userid column
 * @method     ChildUserMessage findOneByToEventUserid(int $to_event_userid) Return the first ChildUserMessage filtered by the to_event_userid column
 * @method     ChildUserMessage findOneByMessage(string $message) Return the first ChildUserMessage filtered by the message column
 * @method     ChildUserMessage findOneByDate(string $date) Return the first ChildUserMessage filtered by the date column
 * @method     ChildUserMessage findOneByReaded(boolean $readed) Return the first ChildUserMessage filtered by the readed column *

 * @method     ChildUserMessage requirePk($key, ConnectionInterface $con = null) Return the ChildUserMessage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserMessage requireOne(ConnectionInterface $con = null) Return the first ChildUserMessage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserMessage requireOneByUserMessageid(int $user_messageid) Return the first ChildUserMessage filtered by the user_messageid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserMessage requireOneByFromEventUserid(int $from_event_userid) Return the first ChildUserMessage filtered by the from_event_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserMessage requireOneByToEventUserid(int $to_event_userid) Return the first ChildUserMessage filtered by the to_event_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserMessage requireOneByMessage(string $message) Return the first ChildUserMessage filtered by the message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserMessage requireOneByDate(string $date) Return the first ChildUserMessage filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserMessage requireOneByReaded(boolean $readed) Return the first ChildUserMessage filtered by the readed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserMessage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserMessage objects based on current ModelCriteria
 * @method     ChildUserMessage[]|ObjectCollection findByUserMessageid(int $user_messageid) Return ChildUserMessage objects filtered by the user_messageid column
 * @method     ChildUserMessage[]|ObjectCollection findByFromEventUserid(int $from_event_userid) Return ChildUserMessage objects filtered by the from_event_userid column
 * @method     ChildUserMessage[]|ObjectCollection findByToEventUserid(int $to_event_userid) Return ChildUserMessage objects filtered by the to_event_userid column
 * @method     ChildUserMessage[]|ObjectCollection findByMessage(string $message) Return ChildUserMessage objects filtered by the message column
 * @method     ChildUserMessage[]|ObjectCollection findByDate(string $date) Return ChildUserMessage objects filtered by the date column
 * @method     ChildUserMessage[]|ObjectCollection findByReaded(boolean $readed) Return ChildUserMessage objects filtered by the readed column
 * @method     ChildUserMessage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserMessageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\User\Message\Base\UserMessageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\User\\Message\\UserMessage', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserMessageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserMessageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserMessageQuery) {
            return $criteria;
        }
        $query = new ChildUserMessageQuery();
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
     * @return ChildUserMessage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserMessageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserMessageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUserMessage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT user_messageid, from_event_userid, to_event_userid, message, date, readed FROM user_message WHERE user_messageid = :p0';
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
            /** @var ChildUserMessage $obj */
            $obj = new ChildUserMessage();
            $obj->hydrate($row);
            UserMessageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUserMessage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserMessageTableMap::COL_USER_MESSAGEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserMessageTableMap::COL_USER_MESSAGEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the user_messageid column
     *
     * Example usage:
     * <code>
     * $query->filterByUserMessageid(1234); // WHERE user_messageid = 1234
     * $query->filterByUserMessageid(array(12, 34)); // WHERE user_messageid IN (12, 34)
     * $query->filterByUserMessageid(array('min' => 12)); // WHERE user_messageid > 12
     * </code>
     *
     * @param     mixed $userMessageid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByUserMessageid($userMessageid = null, $comparison = null)
    {
        if (is_array($userMessageid)) {
            $useMinMax = false;
            if (isset($userMessageid['min'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_USER_MESSAGEID, $userMessageid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userMessageid['max'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_USER_MESSAGEID, $userMessageid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserMessageTableMap::COL_USER_MESSAGEID, $userMessageid, $comparison);
    }

    /**
     * Filter the query on the from_event_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByFromEventUserid(1234); // WHERE from_event_userid = 1234
     * $query->filterByFromEventUserid(array(12, 34)); // WHERE from_event_userid IN (12, 34)
     * $query->filterByFromEventUserid(array('min' => 12)); // WHERE from_event_userid > 12
     * </code>
     *
     * @see       filterByEventUserRelatedByFromEventUserid()
     *
     * @param     mixed $fromEventUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByFromEventUserid($fromEventUserid = null, $comparison = null)
    {
        if (is_array($fromEventUserid)) {
            $useMinMax = false;
            if (isset($fromEventUserid['min'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_FROM_EVENT_USERID, $fromEventUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fromEventUserid['max'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_FROM_EVENT_USERID, $fromEventUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserMessageTableMap::COL_FROM_EVENT_USERID, $fromEventUserid, $comparison);
    }

    /**
     * Filter the query on the to_event_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByToEventUserid(1234); // WHERE to_event_userid = 1234
     * $query->filterByToEventUserid(array(12, 34)); // WHERE to_event_userid IN (12, 34)
     * $query->filterByToEventUserid(array('min' => 12)); // WHERE to_event_userid > 12
     * </code>
     *
     * @see       filterByEventUserRelatedByToEventUserid()
     *
     * @param     mixed $toEventUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByToEventUserid($toEventUserid = null, $comparison = null)
    {
        if (is_array($toEventUserid)) {
            $useMinMax = false;
            if (isset($toEventUserid['min'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_TO_EVENT_USERID, $toEventUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($toEventUserid['max'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_TO_EVENT_USERID, $toEventUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserMessageTableMap::COL_TO_EVENT_USERID, $toEventUserid, $comparison);
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
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserMessageTableMap::COL_MESSAGE, $message, $comparison);
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
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(UserMessageTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserMessageTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByReaded($readed = null, $comparison = null)
    {
        if (is_string($readed)) {
            $readed = in_array(strtolower($readed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserMessageTableMap::COL_READED, $readed, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Event\EventUser object
     *
     * @param \API\Models\ORM\Event\EventUser|ObjectCollection $eventUser The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByEventUserRelatedByFromEventUserid($eventUser, $comparison = null)
    {
        if ($eventUser instanceof \API\Models\ORM\Event\EventUser) {
            return $this
                ->addUsingAlias(UserMessageTableMap::COL_FROM_EVENT_USERID, $eventUser->getEventUserid(), $comparison);
        } elseif ($eventUser instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserMessageTableMap::COL_FROM_EVENT_USERID, $eventUser->toKeyValue('PrimaryKey', 'EventUserid'), $comparison);
        } else {
            throw new PropelException('filterByEventUserRelatedByFromEventUserid() only accepts arguments of type \API\Models\ORM\Event\EventUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventUserRelatedByFromEventUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function joinEventUserRelatedByFromEventUserid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventUserRelatedByFromEventUserid');

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
            $this->addJoinObject($join, 'EventUserRelatedByFromEventUserid');
        }

        return $this;
    }

    /**
     * Use the EventUserRelatedByFromEventUserid relation EventUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Event\EventUserQuery A secondary query class using the current class as primary query
     */
    public function useEventUserRelatedByFromEventUseridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEventUserRelatedByFromEventUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventUserRelatedByFromEventUserid', '\API\Models\ORM\Event\EventUserQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Event\EventUser object
     *
     * @param \API\Models\ORM\Event\EventUser|ObjectCollection $eventUser The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserMessageQuery The current query, for fluid interface
     */
    public function filterByEventUserRelatedByToEventUserid($eventUser, $comparison = null)
    {
        if ($eventUser instanceof \API\Models\ORM\Event\EventUser) {
            return $this
                ->addUsingAlias(UserMessageTableMap::COL_TO_EVENT_USERID, $eventUser->getEventUserid(), $comparison);
        } elseif ($eventUser instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserMessageTableMap::COL_TO_EVENT_USERID, $eventUser->toKeyValue('PrimaryKey', 'EventUserid'), $comparison);
        } else {
            throw new PropelException('filterByEventUserRelatedByToEventUserid() only accepts arguments of type \API\Models\ORM\Event\EventUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventUserRelatedByToEventUserid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function joinEventUserRelatedByToEventUserid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventUserRelatedByToEventUserid');

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
            $this->addJoinObject($join, 'EventUserRelatedByToEventUserid');
        }

        return $this;
    }

    /**
     * Use the EventUserRelatedByToEventUserid relation EventUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Event\EventUserQuery A secondary query class using the current class as primary query
     */
    public function useEventUserRelatedByToEventUseridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventUserRelatedByToEventUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventUserRelatedByToEventUserid', '\API\Models\ORM\Event\EventUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserMessage $userMessage Object to remove from the list of results
     *
     * @return $this|ChildUserMessageQuery The current query, for fluid interface
     */
    public function prune($userMessage = null)
    {
        if ($userMessage) {
            $this->addUsingAlias(UserMessageTableMap::COL_USER_MESSAGEID, $userMessage->getUserMessageid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_message table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserMessageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserMessageTableMap::clearInstancePool();
            UserMessageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserMessageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserMessageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserMessageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserMessageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserMessageQuery
