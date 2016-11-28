<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceUser as ChildDistributionPlaceUser;
use API\Models\DistributionPlace\DistributionPlaceUserQuery as ChildDistributionPlaceUserQuery;
use API\Models\DistributionPlace\Map\DistributionPlaceUserTableMap;
use API\Models\Event\EventPrinter;
use API\Models\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distribution_place_user' table.
 *
 *
 *
 * @method     ChildDistributionPlaceUserQuery orderByDistributionPlaceid($order = Criteria::ASC) Order by the distribution_placeid column
 * @method     ChildDistributionPlaceUserQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildDistributionPlaceUserQuery orderByEventPrinterid($order = Criteria::ASC) Order by the event_printerid column
 *
 * @method     ChildDistributionPlaceUserQuery groupByDistributionPlaceid() Group by the distribution_placeid column
 * @method     ChildDistributionPlaceUserQuery groupByUserid() Group by the userid column
 * @method     ChildDistributionPlaceUserQuery groupByEventPrinterid() Group by the event_printerid column
 *
 * @method     ChildDistributionPlaceUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionPlaceUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionPlaceUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionPlaceUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionPlaceUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinDistributionPlace($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceUserQuery rightJoinDistributionPlace($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceUserQuery innerJoinDistributionPlace($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceUserQuery joinWithDistributionPlace($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinWithDistributionPlace() Adds a LEFT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceUserQuery rightJoinWithDistributionPlace() Adds a RIGHT JOIN clause and with to the query using the DistributionPlace relation
 * @method     ChildDistributionPlaceUserQuery innerJoinWithDistributionPlace() Adds a INNER JOIN clause and with to the query using the DistributionPlace relation
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildDistributionPlaceUserQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildDistributionPlaceUserQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildDistributionPlaceUserQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildDistributionPlaceUserQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildDistributionPlaceUserQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinEventPrinter($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventPrinter relation
 * @method     ChildDistributionPlaceUserQuery rightJoinEventPrinter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventPrinter relation
 * @method     ChildDistributionPlaceUserQuery innerJoinEventPrinter($relationAlias = null) Adds a INNER JOIN clause to the query using the EventPrinter relation
 *
 * @method     ChildDistributionPlaceUserQuery joinWithEventPrinter($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventPrinter relation
 *
 * @method     ChildDistributionPlaceUserQuery leftJoinWithEventPrinter() Adds a LEFT JOIN clause and with to the query using the EventPrinter relation
 * @method     ChildDistributionPlaceUserQuery rightJoinWithEventPrinter() Adds a RIGHT JOIN clause and with to the query using the EventPrinter relation
 * @method     ChildDistributionPlaceUserQuery innerJoinWithEventPrinter() Adds a INNER JOIN clause and with to the query using the EventPrinter relation
 *
 * @method     \API\Models\DistributionPlace\DistributionPlaceQuery|\API\Models\User\UserQuery|\API\Models\Event\EventPrinterQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionPlaceUser findOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceUser matching the query
 * @method     ChildDistributionPlaceUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionPlaceUser matching the query, or a new ChildDistributionPlaceUser object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionPlaceUser findOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceUser filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceUser findOneByUserid(int $userid) Return the first ChildDistributionPlaceUser filtered by the userid column
 * @method     ChildDistributionPlaceUser findOneByEventPrinterid(int $event_printerid) Return the first ChildDistributionPlaceUser filtered by the event_printerid column *

 * @method     ChildDistributionPlaceUser requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionPlaceUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceUser requireOne(ConnectionInterface $con = null) Return the first ChildDistributionPlaceUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceUser requireOneByDistributionPlaceid(int $distribution_placeid) Return the first ChildDistributionPlaceUser filtered by the distribution_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceUser requireOneByUserid(int $userid) Return the first ChildDistributionPlaceUser filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionPlaceUser requireOneByEventPrinterid(int $event_printerid) Return the first ChildDistributionPlaceUser filtered by the event_printerid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionPlaceUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionPlaceUser objects based on current ModelCriteria
 * @method     ChildDistributionPlaceUser[]|ObjectCollection findByDistributionPlaceid(int $distribution_placeid) Return ChildDistributionPlaceUser objects filtered by the distribution_placeid column
 * @method     ChildDistributionPlaceUser[]|ObjectCollection findByUserid(int $userid) Return ChildDistributionPlaceUser objects filtered by the userid column
 * @method     ChildDistributionPlaceUser[]|ObjectCollection findByEventPrinterid(int $event_printerid) Return ChildDistributionPlaceUser objects filtered by the event_printerid column
 * @method     ChildDistributionPlaceUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionPlaceUserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\DistributionPlace\Base\DistributionPlaceUserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\DistributionPlace\\DistributionPlaceUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionPlaceUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionPlaceUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionPlaceUserQuery) {
            return $criteria;
        }
        $query = new ChildDistributionPlaceUserQuery();
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
     * @param array[$distribution_placeid, $userid, $event_printerid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionPlaceUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionPlaceUserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionPlaceUserTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildDistributionPlaceUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distribution_placeid, userid, event_printerid FROM distribution_place_user WHERE distribution_placeid = :p0 AND userid = :p1 AND event_printerid = :p2';
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
            /** @var ChildDistributionPlaceUser $obj */
            $obj = new ChildDistributionPlaceUser();
            $obj->hydrate($row);
            DistributionPlaceUserTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildDistributionPlaceUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionPlaceUserTableMap::COL_USERID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionPlaceUserTableMap::COL_USERID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByDistributionPlace()
     *
     * @param     mixed $distributionPlaceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceid($distributionPlaceid = null, $comparison = null)
    {
        if (is_array($distributionPlaceid)) {
            $useMinMax = false;
            if (isset($distributionPlaceid['min'])) {
                $this->addUsingAlias(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionPlaceid['max'])) {
                $this->addUsingAlias(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlaceid, $comparison);
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
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(DistributionPlaceUserTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(DistributionPlaceUserTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceUserTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the event_printerid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventPrinterid(1234); // WHERE event_printerid = 1234
     * $query->filterByEventPrinterid(array(12, 34)); // WHERE event_printerid IN (12, 34)
     * $query->filterByEventPrinterid(array('min' => 12)); // WHERE event_printerid > 12
     * </code>
     *
     * @see       filterByEventPrinter()
     *
     * @param     mixed $eventPrinterid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByEventPrinterid($eventPrinterid = null, $comparison = null)
    {
        if (is_array($eventPrinterid)) {
            $useMinMax = false;
            if (isset($eventPrinterid['min'])) {
                $this->addUsingAlias(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $eventPrinterid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventPrinterid['max'])) {
                $this->addUsingAlias(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $eventPrinterid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $eventPrinterid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlace object
     *
     * @param \API\Models\DistributionPlace\DistributionPlace|ObjectCollection $distributionPlace The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByDistributionPlace($distributionPlace, $comparison = null)
    {
        if ($distributionPlace instanceof \API\Models\DistributionPlace\DistributionPlace) {
            return $this
                ->addUsingAlias(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->getDistributionPlaceid(), $comparison);
        } elseif ($distributionPlace instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID, $distributionPlace->toKeyValue('PrimaryKey', 'DistributionPlaceid'), $comparison);
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
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\User\User object
     *
     * @param \API\Models\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\User\User) {
            return $this
                ->addUsingAlias(DistributionPlaceUserTableMap::COL_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceUserTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
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
     * @return \API\Models\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventPrinter object
     *
     * @param \API\Models\Event\EventPrinter|ObjectCollection $eventPrinter The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function filterByEventPrinter($eventPrinter, $comparison = null)
    {
        if ($eventPrinter instanceof \API\Models\Event\EventPrinter) {
            return $this
                ->addUsingAlias(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $eventPrinter->getEventPrinterid(), $comparison);
        } elseif ($eventPrinter instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID, $eventPrinter->toKeyValue('PrimaryKey', 'EventPrinterid'), $comparison);
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
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildDistributionPlaceUser $distributionPlaceUser Object to remove from the list of results
     *
     * @return $this|ChildDistributionPlaceUserQuery The current query, for fluid interface
     */
    public function prune($distributionPlaceUser = null)
    {
        if ($distributionPlaceUser) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionPlaceUserTableMap::COL_DISTRIBUTION_PLACEID), $distributionPlaceUser->getDistributionPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionPlaceUserTableMap::COL_USERID), $distributionPlaceUser->getUserid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(DistributionPlaceUserTableMap::COL_EVENT_PRINTERID), $distributionPlaceUser->getEventPrinterid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distribution_place_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceUserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionPlaceUserTableMap::clearInstancePool();
            DistributionPlaceUserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionPlaceUserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionPlaceUserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionPlaceUserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionPlaceUserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionPlaceUserQuery
