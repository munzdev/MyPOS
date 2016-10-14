<?php

namespace API\Models\DistributionPlace\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlacesUsers as ChildDistributionsPlacesUsers;
use API\Models\DistributionPlace\DistributionsPlacesUsersQuery as ChildDistributionsPlacesUsersQuery;
use API\Models\DistributionPlace\Map\DistributionsPlacesUsersTableMap;
use API\Models\Event\EventsPrinters;
use API\Models\User\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distributions_places_users' table.
 *
 *
 *
 * @method     ChildDistributionsPlacesUsersQuery orderByDistributionsPlaceid($order = Criteria::ASC) Order by the distributions_placeid column
 * @method     ChildDistributionsPlacesUsersQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildDistributionsPlacesUsersQuery orderByEventsPrinterid($order = Criteria::ASC) Order by the events_printerid column
 *
 * @method     ChildDistributionsPlacesUsersQuery groupByDistributionsPlaceid() Group by the distributions_placeid column
 * @method     ChildDistributionsPlacesUsersQuery groupByUserid() Group by the userid column
 * @method     ChildDistributionsPlacesUsersQuery groupByEventsPrinterid() Group by the events_printerid column
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionsPlacesUsersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionsPlacesUsersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesUsersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesUsersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinDistributionsPlaces($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesUsersQuery rightJoinDistributionsPlaces($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesUsersQuery innerJoinDistributionsPlaces($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesUsersQuery joinWithDistributionsPlaces($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinWithDistributionsPlaces() Adds a LEFT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesUsersQuery rightJoinWithDistributionsPlaces() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesUsersQuery innerJoinWithDistributionsPlaces() Adds a INNER JOIN clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildDistributionsPlacesUsersQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildDistributionsPlacesUsersQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildDistributionsPlacesUsersQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildDistributionsPlacesUsersQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildDistributionsPlacesUsersQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinEventsPrinters($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsPrinters relation
 * @method     ChildDistributionsPlacesUsersQuery rightJoinEventsPrinters($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsPrinters relation
 * @method     ChildDistributionsPlacesUsersQuery innerJoinEventsPrinters($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsPrinters relation
 *
 * @method     ChildDistributionsPlacesUsersQuery joinWithEventsPrinters($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsPrinters relation
 *
 * @method     ChildDistributionsPlacesUsersQuery leftJoinWithEventsPrinters() Adds a LEFT JOIN clause and with to the query using the EventsPrinters relation
 * @method     ChildDistributionsPlacesUsersQuery rightJoinWithEventsPrinters() Adds a RIGHT JOIN clause and with to the query using the EventsPrinters relation
 * @method     ChildDistributionsPlacesUsersQuery innerJoinWithEventsPrinters() Adds a INNER JOIN clause and with to the query using the EventsPrinters relation
 *
 * @method     \API\Models\DistributionPlace\DistributionsPlacesQuery|\API\Models\User\UsersQuery|\API\Models\Event\EventsPrintersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionsPlacesUsers findOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesUsers matching the query
 * @method     ChildDistributionsPlacesUsers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesUsers matching the query, or a new ChildDistributionsPlacesUsers object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionsPlacesUsers findOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlacesUsers filtered by the distributions_placeid column
 * @method     ChildDistributionsPlacesUsers findOneByUserid(int $userid) Return the first ChildDistributionsPlacesUsers filtered by the userid column
 * @method     ChildDistributionsPlacesUsers findOneByEventsPrinterid(int $events_printerid) Return the first ChildDistributionsPlacesUsers filtered by the events_printerid column *

 * @method     ChildDistributionsPlacesUsers requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionsPlacesUsers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesUsers requireOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesUsers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlacesUsers requireOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlacesUsers filtered by the distributions_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesUsers requireOneByUserid(int $userid) Return the first ChildDistributionsPlacesUsers filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesUsers requireOneByEventsPrinterid(int $events_printerid) Return the first ChildDistributionsPlacesUsers filtered by the events_printerid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlacesUsers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionsPlacesUsers objects based on current ModelCriteria
 * @method     ChildDistributionsPlacesUsers[]|ObjectCollection findByDistributionsPlaceid(int $distributions_placeid) Return ChildDistributionsPlacesUsers objects filtered by the distributions_placeid column
 * @method     ChildDistributionsPlacesUsers[]|ObjectCollection findByUserid(int $userid) Return ChildDistributionsPlacesUsers objects filtered by the userid column
 * @method     ChildDistributionsPlacesUsers[]|ObjectCollection findByEventsPrinterid(int $events_printerid) Return ChildDistributionsPlacesUsers objects filtered by the events_printerid column
 * @method     ChildDistributionsPlacesUsers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionsPlacesUsersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\DistributionPlace\Base\DistributionsPlacesUsersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\DistributionPlace\\DistributionsPlacesUsers', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionsPlacesUsersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionsPlacesUsersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionsPlacesUsersQuery) {
            return $criteria;
        }
        $query = new ChildDistributionsPlacesUsersQuery();
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
     * @param array[$distributions_placeid, $userid, $events_printerid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionsPlacesUsers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionsPlacesUsersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionsPlacesUsersTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildDistributionsPlacesUsers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT distributions_placeid, userid, events_printerid FROM distributions_places_users WHERE distributions_placeid = :p0 AND userid = :p1 AND events_printerid = :p2';
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
            /** @var ChildDistributionsPlacesUsers $obj */
            $obj = new ChildDistributionsPlacesUsers();
            $obj->hydrate($row);
            DistributionsPlacesUsersTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildDistributionsPlacesUsers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_USERID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionsPlacesUsersTableMap::COL_USERID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
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
     * @see       filterByDistributionsPlaces()
     *
     * @param     mixed $distributionsPlaceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaceid($distributionsPlaceid = null, $comparison = null)
    {
        if (is_array($distributionsPlaceid)) {
            $useMinMax = false;
            if (isset($distributionsPlaceid['min'])) {
                $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionsPlaceid['max'])) {
                $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid, $comparison);
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
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_USERID, $userid, $comparison);
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
     * @see       filterByEventsPrinters()
     *
     * @param     mixed $eventsPrinterid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByEventsPrinterid($eventsPrinterid = null, $comparison = null)
    {
        if (is_array($eventsPrinterid)) {
            $useMinMax = false;
            if (isset($eventsPrinterid['min'])) {
                $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $eventsPrinterid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventsPrinterid['max'])) {
                $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $eventsPrinterid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $eventsPrinterid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionsPlaces object
     *
     * @param \API\Models\DistributionPlace\DistributionsPlaces|ObjectCollection $distributionsPlaces The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaces($distributionsPlaces, $comparison = null)
    {
        if ($distributionsPlaces instanceof \API\Models\DistributionPlace\DistributionsPlaces) {
            return $this
                ->addUsingAlias(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaces->getDistributionsPlaceid(), $comparison);
        } elseif ($distributionsPlaces instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaces->toKeyValue('DistributionsPlaceid', 'DistributionsPlaceid'), $comparison);
        } else {
            throw new PropelException('filterByDistributionsPlaces() only accepts arguments of type \API\Models\DistributionPlace\DistributionsPlaces or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlaces relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function joinDistributionsPlaces($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsPlaces');

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
            $this->addJoinObject($join, 'DistributionsPlaces');
        }

        return $this;
    }

    /**
     * Use the DistributionsPlaces relation DistributionsPlaces object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionsPlacesQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlaces($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlaces', '\API\Models\DistributionPlace\DistributionsPlacesQuery');
    }

    /**
     * Filter the query by a related \API\Models\User\Users object
     *
     * @param \API\Models\User\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \API\Models\User\Users) {
            return $this
                ->addUsingAlias(DistributionsPlacesUsersTableMap::COL_USERID, $users->getUserid(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesUsersTableMap::COL_USERID, $users->toKeyValue('PrimaryKey', 'Userid'), $comparison);
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
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\Event\EventsPrinters object
     *
     * @param \API\Models\Event\EventsPrinters|ObjectCollection $eventsPrinters The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function filterByEventsPrinters($eventsPrinters, $comparison = null)
    {
        if ($eventsPrinters instanceof \API\Models\Event\EventsPrinters) {
            return $this
                ->addUsingAlias(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $eventsPrinters->getEventsPrinterid(), $comparison);
        } elseif ($eventsPrinters instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID, $eventsPrinters->toKeyValue('EventsPrinterid', 'EventsPrinterid'), $comparison);
        } else {
            throw new PropelException('filterByEventsPrinters() only accepts arguments of type \API\Models\Event\EventsPrinters or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsPrinters relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function joinEventsPrinters($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsPrinters');

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
            $this->addJoinObject($join, 'EventsPrinters');
        }

        return $this;
    }

    /**
     * Use the EventsPrinters relation EventsPrinters object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventsPrintersQuery A secondary query class using the current class as primary query
     */
    public function useEventsPrintersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsPrinters($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsPrinters', '\API\Models\Event\EventsPrintersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionsPlacesUsers $distributionsPlacesUsers Object to remove from the list of results
     *
     * @return $this|ChildDistributionsPlacesUsersQuery The current query, for fluid interface
     */
    public function prune($distributionsPlacesUsers = null)
    {
        if ($distributionsPlacesUsers) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionsPlacesUsersTableMap::COL_DISTRIBUTIONS_PLACEID), $distributionsPlacesUsers->getDistributionsPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionsPlacesUsersTableMap::COL_USERID), $distributionsPlacesUsers->getUserid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(DistributionsPlacesUsersTableMap::COL_EVENTS_PRINTERID), $distributionsPlacesUsers->getEventsPrinterid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distributions_places_users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesUsersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionsPlacesUsersTableMap::clearInstancePool();
            DistributionsPlacesUsersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesUsersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionsPlacesUsersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionsPlacesUsersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionsPlacesUsersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionsPlacesUsersQuery
