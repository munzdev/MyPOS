<?php

namespace API\Models\Ordering\Base;

use \Exception;
use \PDO;
use API\Models\Menues\Menues;
use API\Models\Ordering\OrdersDetailsMixedWith as ChildOrdersDetailsMixedWith;
use API\Models\Ordering\OrdersDetailsMixedWithQuery as ChildOrdersDetailsMixedWithQuery;
use API\Models\Ordering\Map\OrdersDetailsMixedWithTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'orders_details_mixed_with' table.
 *
 *
 *
 * @method     ChildOrdersDetailsMixedWithQuery orderByOrdersDetailid($order = Criteria::ASC) Order by the orders_detailid column
 * @method     ChildOrdersDetailsMixedWithQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 *
 * @method     ChildOrdersDetailsMixedWithQuery groupByOrdersDetailid() Group by the orders_detailid column
 * @method     ChildOrdersDetailsMixedWithQuery groupByMenuid() Group by the menuid column
 *
 * @method     ChildOrdersDetailsMixedWithQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrdersDetailsMixedWithQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrdersDetailsMixedWithQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrdersDetailsMixedWithQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrdersDetailsMixedWithQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrdersDetailsMixedWithQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrdersDetailsMixedWithQuery leftJoinMenues($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menues relation
 * @method     ChildOrdersDetailsMixedWithQuery rightJoinMenues($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menues relation
 * @method     ChildOrdersDetailsMixedWithQuery innerJoinMenues($relationAlias = null) Adds a INNER JOIN clause to the query using the Menues relation
 *
 * @method     ChildOrdersDetailsMixedWithQuery joinWithMenues($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menues relation
 *
 * @method     ChildOrdersDetailsMixedWithQuery leftJoinWithMenues() Adds a LEFT JOIN clause and with to the query using the Menues relation
 * @method     ChildOrdersDetailsMixedWithQuery rightJoinWithMenues() Adds a RIGHT JOIN clause and with to the query using the Menues relation
 * @method     ChildOrdersDetailsMixedWithQuery innerJoinWithMenues() Adds a INNER JOIN clause and with to the query using the Menues relation
 *
 * @method     ChildOrdersDetailsMixedWithQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildOrdersDetailsMixedWithQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildOrdersDetailsMixedWithQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildOrdersDetailsMixedWithQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildOrdersDetailsMixedWithQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildOrdersDetailsMixedWithQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildOrdersDetailsMixedWithQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     \API\Models\Menues\MenuesQuery|\API\Models\Ordering\OrdersDetailsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrdersDetailsMixedWith findOne(ConnectionInterface $con = null) Return the first ChildOrdersDetailsMixedWith matching the query
 * @method     ChildOrdersDetailsMixedWith findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrdersDetailsMixedWith matching the query, or a new ChildOrdersDetailsMixedWith object populated from the query conditions when no match is found
 *
 * @method     ChildOrdersDetailsMixedWith findOneByOrdersDetailid(int $orders_detailid) Return the first ChildOrdersDetailsMixedWith filtered by the orders_detailid column
 * @method     ChildOrdersDetailsMixedWith findOneByMenuid(int $menuid) Return the first ChildOrdersDetailsMixedWith filtered by the menuid column *

 * @method     ChildOrdersDetailsMixedWith requirePk($key, ConnectionInterface $con = null) Return the ChildOrdersDetailsMixedWith by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetailsMixedWith requireOne(ConnectionInterface $con = null) Return the first ChildOrdersDetailsMixedWith matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersDetailsMixedWith requireOneByOrdersDetailid(int $orders_detailid) Return the first ChildOrdersDetailsMixedWith filtered by the orders_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetailsMixedWith requireOneByMenuid(int $menuid) Return the first ChildOrdersDetailsMixedWith filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersDetailsMixedWith[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrdersDetailsMixedWith objects based on current ModelCriteria
 * @method     ChildOrdersDetailsMixedWith[]|ObjectCollection findByOrdersDetailid(int $orders_detailid) Return ChildOrdersDetailsMixedWith objects filtered by the orders_detailid column
 * @method     ChildOrdersDetailsMixedWith[]|ObjectCollection findByMenuid(int $menuid) Return ChildOrdersDetailsMixedWith objects filtered by the menuid column
 * @method     ChildOrdersDetailsMixedWith[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrdersDetailsMixedWithQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Ordering\Base\OrdersDetailsMixedWithQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Ordering\\OrdersDetailsMixedWith', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrdersDetailsMixedWithQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrdersDetailsMixedWithQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrdersDetailsMixedWithQuery) {
            return $criteria;
        }
        $query = new ChildOrdersDetailsMixedWithQuery();
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
     * @param array[$orders_detailid, $menuid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildOrdersDetailsMixedWith|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrdersDetailsMixedWithTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrdersDetailsMixedWithTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildOrdersDetailsMixedWith A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT orders_detailid, menuid FROM orders_details_mixed_with WHERE orders_detailid = :p0 AND menuid = :p1';
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
            /** @var ChildOrdersDetailsMixedWith $obj */
            $obj = new ChildOrdersDetailsMixedWith();
            $obj->hydrate($row);
            OrdersDetailsMixedWithTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildOrdersDetailsMixedWith|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_MENUID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(OrdersDetailsMixedWithTableMap::COL_MENUID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the orders_detailid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrdersDetailid(1234); // WHERE orders_detailid = 1234
     * $query->filterByOrdersDetailid(array(12, 34)); // WHERE orders_detailid IN (12, 34)
     * $query->filterByOrdersDetailid(array('min' => 12)); // WHERE orders_detailid > 12
     * </code>
     *
     * @see       filterByOrdersDetails()
     *
     * @param     mixed $ordersDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailid($ordersDetailid = null, $comparison = null)
    {
        if (is_array($ordersDetailid)) {
            $useMinMax = false;
            if (isset($ordersDetailid['min'])) {
                $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $ordersDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersDetailid['max'])) {
                $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $ordersDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $ordersDetailid, $comparison);
    }

    /**
     * Filter the query on the menuid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuid(1234); // WHERE menuid = 1234
     * $query->filterByMenuid(array(12, 34)); // WHERE menuid IN (12, 34)
     * $query->filterByMenuid(array('min' => 12)); // WHERE menuid > 12
     * </code>
     *
     * @see       filterByMenues()
     *
     * @param     mixed $menuid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_MENUID, $menuid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menues\Menues object
     *
     * @param \API\Models\Menues\Menues|ObjectCollection $menues The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function filterByMenues($menues, $comparison = null)
    {
        if ($menues instanceof \API\Models\Menues\Menues) {
            return $this
                ->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_MENUID, $menues->getMenuid(), $comparison);
        } elseif ($menues instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_MENUID, $menues->toKeyValue('Menuid', 'Menuid'), $comparison);
        } else {
            throw new PropelException('filterByMenues() only accepts arguments of type \API\Models\Menues\Menues or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menues relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function joinMenues($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Menues');

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
            $this->addJoinObject($join, 'Menues');
        }

        return $this;
    }

    /**
     * Use the Menues relation Menues object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menues\MenuesQuery A secondary query class using the current class as primary query
     */
    public function useMenuesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenues($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menues', '\API\Models\Menues\MenuesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrdersDetails object
     *
     * @param \API\Models\Ordering\OrdersDetails|ObjectCollection $ordersDetails The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \API\Models\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $ordersDetails->getOrdersDetailid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID, $ordersDetails->toKeyValue('OrdersDetailid', 'OrdersDetailid'), $comparison);
        } else {
            throw new PropelException('filterByOrdersDetails() only accepts arguments of type \API\Models\Ordering\OrdersDetails or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersDetails relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function joinOrdersDetails($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersDetails');

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
            $this->addJoinObject($join, 'OrdersDetails');
        }

        return $this;
    }

    /**
     * Use the OrdersDetails relation OrdersDetails object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrdersDetailsQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\API\Models\Ordering\OrdersDetailsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrdersDetailsMixedWith $ordersDetailsMixedWith Object to remove from the list of results
     *
     * @return $this|ChildOrdersDetailsMixedWithQuery The current query, for fluid interface
     */
    public function prune($ordersDetailsMixedWith = null)
    {
        if ($ordersDetailsMixedWith) {
            $this->addCond('pruneCond0', $this->getAliasedColName(OrdersDetailsMixedWithTableMap::COL_ORDERS_DETAILID), $ordersDetailsMixedWith->getOrdersDetailid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(OrdersDetailsMixedWithTableMap::COL_MENUID), $ordersDetailsMixedWith->getMenuid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the orders_details_mixed_with table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsMixedWithTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrdersDetailsMixedWithTableMap::clearInstancePool();
            OrdersDetailsMixedWithTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsMixedWithTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrdersDetailsMixedWithTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrdersDetailsMixedWithTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrdersDetailsMixedWithTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrdersDetailsMixedWithQuery
