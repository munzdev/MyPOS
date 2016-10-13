<?php

namespace Model\DistributionPlace\Base;

use \Exception;
use \PDO;
use Model\DistributionPlace\DistributionsPlacesTables as ChildDistributionsPlacesTables;
use Model\DistributionPlace\DistributionsPlacesTablesQuery as ChildDistributionsPlacesTablesQuery;
use Model\DistributionPlace\Map\DistributionsPlacesTablesTableMap;
use Model\Event\EventsTables;
use Model\Menues\MenuGroupes;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'distributions_places_tables' table.
 *
 *
 *
 * @method     ChildDistributionsPlacesTablesQuery orderByTableid($order = Criteria::ASC) Order by the tableid column
 * @method     ChildDistributionsPlacesTablesQuery orderByDistributionsPlaceid($order = Criteria::ASC) Order by the distributions_placeid column
 * @method     ChildDistributionsPlacesTablesQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 *
 * @method     ChildDistributionsPlacesTablesQuery groupByTableid() Group by the tableid column
 * @method     ChildDistributionsPlacesTablesQuery groupByDistributionsPlaceid() Group by the distributions_placeid column
 * @method     ChildDistributionsPlacesTablesQuery groupByMenuGroupid() Group by the menu_groupid column
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDistributionsPlacesTablesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDistributionsPlacesTablesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesTablesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDistributionsPlacesTablesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinMenuGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesTablesQuery rightJoinMenuGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesTablesQuery innerJoinMenuGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroupes relation
 *
 * @method     ChildDistributionsPlacesTablesQuery joinWithMenuGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinWithMenuGroupes() Adds a LEFT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesTablesQuery rightJoinWithMenuGroupes() Adds a RIGHT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildDistributionsPlacesTablesQuery innerJoinWithMenuGroupes() Adds a INNER JOIN clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinDistributionsPlaces($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesTablesQuery rightJoinDistributionsPlaces($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesTablesQuery innerJoinDistributionsPlaces($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesTablesQuery joinWithDistributionsPlaces($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinWithDistributionsPlaces() Adds a LEFT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesTablesQuery rightJoinWithDistributionsPlaces() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlaces relation
 * @method     ChildDistributionsPlacesTablesQuery innerJoinWithDistributionsPlaces() Adds a INNER JOIN clause and with to the query using the DistributionsPlaces relation
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinEventsTables($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsTables relation
 * @method     ChildDistributionsPlacesTablesQuery rightJoinEventsTables($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsTables relation
 * @method     ChildDistributionsPlacesTablesQuery innerJoinEventsTables($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsTables relation
 *
 * @method     ChildDistributionsPlacesTablesQuery joinWithEventsTables($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsTables relation
 *
 * @method     ChildDistributionsPlacesTablesQuery leftJoinWithEventsTables() Adds a LEFT JOIN clause and with to the query using the EventsTables relation
 * @method     ChildDistributionsPlacesTablesQuery rightJoinWithEventsTables() Adds a RIGHT JOIN clause and with to the query using the EventsTables relation
 * @method     ChildDistributionsPlacesTablesQuery innerJoinWithEventsTables() Adds a INNER JOIN clause and with to the query using the EventsTables relation
 *
 * @method     \Model\Menues\MenuGroupesQuery|\Model\DistributionPlace\DistributionsPlacesQuery|\Model\Event\EventsTablesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDistributionsPlacesTables findOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesTables matching the query
 * @method     ChildDistributionsPlacesTables findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesTables matching the query, or a new ChildDistributionsPlacesTables object populated from the query conditions when no match is found
 *
 * @method     ChildDistributionsPlacesTables findOneByTableid(int $tableid) Return the first ChildDistributionsPlacesTables filtered by the tableid column
 * @method     ChildDistributionsPlacesTables findOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlacesTables filtered by the distributions_placeid column
 * @method     ChildDistributionsPlacesTables findOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionsPlacesTables filtered by the menu_groupid column *

 * @method     ChildDistributionsPlacesTables requirePk($key, ConnectionInterface $con = null) Return the ChildDistributionsPlacesTables by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesTables requireOne(ConnectionInterface $con = null) Return the first ChildDistributionsPlacesTables matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlacesTables requireOneByTableid(int $tableid) Return the first ChildDistributionsPlacesTables filtered by the tableid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesTables requireOneByDistributionsPlaceid(int $distributions_placeid) Return the first ChildDistributionsPlacesTables filtered by the distributions_placeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDistributionsPlacesTables requireOneByMenuGroupid(int $menu_groupid) Return the first ChildDistributionsPlacesTables filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDistributionsPlacesTables[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDistributionsPlacesTables objects based on current ModelCriteria
 * @method     ChildDistributionsPlacesTables[]|ObjectCollection findByTableid(int $tableid) Return ChildDistributionsPlacesTables objects filtered by the tableid column
 * @method     ChildDistributionsPlacesTables[]|ObjectCollection findByDistributionsPlaceid(int $distributions_placeid) Return ChildDistributionsPlacesTables objects filtered by the distributions_placeid column
 * @method     ChildDistributionsPlacesTables[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildDistributionsPlacesTables objects filtered by the menu_groupid column
 * @method     ChildDistributionsPlacesTables[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DistributionsPlacesTablesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\DistributionPlace\Base\DistributionsPlacesTablesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\DistributionPlace\\DistributionsPlacesTables', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDistributionsPlacesTablesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDistributionsPlacesTablesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDistributionsPlacesTablesQuery) {
            return $criteria;
        }
        $query = new ChildDistributionsPlacesTablesQuery();
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
     * @param array[$tableid, $distributions_placeid, $menu_groupid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDistributionsPlacesTables|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DistributionsPlacesTablesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DistributionsPlacesTablesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildDistributionsPlacesTables A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT tableid, distributions_placeid, menu_groupid FROM distributions_places_tables WHERE tableid = :p0 AND distributions_placeid = :p1 AND menu_groupid = :p2';
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
            /** @var ChildDistributionsPlacesTables $obj */
            $obj = new ChildDistributionsPlacesTables();
            $obj->hydrate($row);
            DistributionsPlacesTablesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildDistributionsPlacesTables|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_TABLEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(DistributionsPlacesTablesTableMap::COL_TABLEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the tableid column
     *
     * Example usage:
     * <code>
     * $query->filterByTableid(1234); // WHERE tableid = 1234
     * $query->filterByTableid(array(12, 34)); // WHERE tableid IN (12, 34)
     * $query->filterByTableid(array('min' => 12)); // WHERE tableid > 12
     * </code>
     *
     * @see       filterByEventsTables()
     *
     * @param     mixed $tableid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByTableid($tableid = null, $comparison = null)
    {
        if (is_array($tableid)) {
            $useMinMax = false;
            if (isset($tableid['min'])) {
                $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_TABLEID, $tableid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tableid['max'])) {
                $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_TABLEID, $tableid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_TABLEID, $tableid, $comparison);
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
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaceid($distributionsPlaceid = null, $comparison = null)
    {
        if (is_array($distributionsPlaceid)) {
            $useMinMax = false;
            if (isset($distributionsPlaceid['min'])) {
                $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionsPlaceid['max'])) {
                $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaceid, $comparison);
    }

    /**
     * Filter the query on the menu_groupid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuGroupid(1234); // WHERE menu_groupid = 1234
     * $query->filterByMenuGroupid(array(12, 34)); // WHERE menu_groupid IN (12, 34)
     * $query->filterByMenuGroupid(array('min' => 12)); // WHERE menu_groupid > 12
     * </code>
     *
     * @see       filterByMenuGroupes()
     *
     * @param     mixed $menuGroupid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query by a related \Model\Menues\MenuGroupes object
     *
     * @param \Model\Menues\MenuGroupes|ObjectCollection $menuGroupes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupes($menuGroupes, $comparison = null)
    {
        if ($menuGroupes instanceof \Model\Menues\MenuGroupes) {
            return $this
                ->addUsingAlias(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $menuGroupes->getMenuGroupid(), $comparison);
        } elseif ($menuGroupes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID, $menuGroupes->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroupes() only accepts arguments of type \Model\Menues\MenuGroupes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroupes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function joinMenuGroupes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuGroupes');

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
            $this->addJoinObject($join, 'MenuGroupes');
        }

        return $this;
    }

    /**
     * Use the MenuGroupes relation MenuGroupes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuGroupesQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroupes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroupes', '\Model\Menues\MenuGroupesQuery');
    }

    /**
     * Filter the query by a related \Model\DistributionPlace\DistributionsPlaces object
     *
     * @param \Model\DistributionPlace\DistributionsPlaces|ObjectCollection $distributionsPlaces The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlaces($distributionsPlaces, $comparison = null)
    {
        if ($distributionsPlaces instanceof \Model\DistributionPlace\DistributionsPlaces) {
            return $this
                ->addUsingAlias(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaces->getDistributionsPlaceid(), $comparison);
        } elseif ($distributionsPlaces instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID, $distributionsPlaces->toKeyValue('DistributionsPlaceid', 'DistributionsPlaceid'), $comparison);
        } else {
            throw new PropelException('filterByDistributionsPlaces() only accepts arguments of type \Model\DistributionPlace\DistributionsPlaces or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlaces relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
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
     * @return \Model\DistributionPlace\DistributionsPlacesQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlaces($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlaces', '\Model\DistributionPlace\DistributionsPlacesQuery');
    }

    /**
     * Filter the query by a related \Model\Event\EventsTables object
     *
     * @param \Model\Event\EventsTables|ObjectCollection $eventsTables The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function filterByEventsTables($eventsTables, $comparison = null)
    {
        if ($eventsTables instanceof \Model\Event\EventsTables) {
            return $this
                ->addUsingAlias(DistributionsPlacesTablesTableMap::COL_TABLEID, $eventsTables->getEventsTableid(), $comparison);
        } elseif ($eventsTables instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DistributionsPlacesTablesTableMap::COL_TABLEID, $eventsTables->toKeyValue('EventsTableid', 'EventsTableid'), $comparison);
        } else {
            throw new PropelException('filterByEventsTables() only accepts arguments of type \Model\Event\EventsTables or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsTables relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function joinEventsTables($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsTables');

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
            $this->addJoinObject($join, 'EventsTables');
        }

        return $this;
    }

    /**
     * Use the EventsTables relation EventsTables object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Event\EventsTablesQuery A secondary query class using the current class as primary query
     */
    public function useEventsTablesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsTables($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsTables', '\Model\Event\EventsTablesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDistributionsPlacesTables $distributionsPlacesTables Object to remove from the list of results
     *
     * @return $this|ChildDistributionsPlacesTablesQuery The current query, for fluid interface
     */
    public function prune($distributionsPlacesTables = null)
    {
        if ($distributionsPlacesTables) {
            $this->addCond('pruneCond0', $this->getAliasedColName(DistributionsPlacesTablesTableMap::COL_TABLEID), $distributionsPlacesTables->getTableid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(DistributionsPlacesTablesTableMap::COL_DISTRIBUTIONS_PLACEID), $distributionsPlacesTables->getDistributionsPlaceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(DistributionsPlacesTablesTableMap::COL_MENU_GROUPID), $distributionsPlacesTables->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the distributions_places_tables table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesTablesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DistributionsPlacesTablesTableMap::clearInstancePool();
            DistributionsPlacesTablesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DistributionsPlacesTablesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DistributionsPlacesTablesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DistributionsPlacesTablesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DistributionsPlacesTablesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // DistributionsPlacesTablesQuery
