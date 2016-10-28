<?php

namespace API\Models\Ordering\Base;

use \Exception;
use \PDO;
use API\Models\Menu\Menu;
use API\Models\Ordering\OrderDetailMixedWith as ChildOrderDetailMixedWith;
use API\Models\Ordering\OrderDetailMixedWithQuery as ChildOrderDetailMixedWithQuery;
use API\Models\Ordering\Map\OrderDetailMixedWithTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'order_detail_mixed_with' table.
 *
 *
 *
 * @method     ChildOrderDetailMixedWithQuery orderByOrderDetailid($order = Criteria::ASC) Order by the order_detailid column
 * @method     ChildOrderDetailMixedWithQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 *
 * @method     ChildOrderDetailMixedWithQuery groupByOrderDetailid() Group by the order_detailid column
 * @method     ChildOrderDetailMixedWithQuery groupByMenuid() Group by the menuid column
 *
 * @method     ChildOrderDetailMixedWithQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrderDetailMixedWithQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrderDetailMixedWithQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrderDetailMixedWithQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrderDetailMixedWithQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrderDetailMixedWithQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrderDetailMixedWithQuery leftJoinMenu($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menu relation
 * @method     ChildOrderDetailMixedWithQuery rightJoinMenu($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menu relation
 * @method     ChildOrderDetailMixedWithQuery innerJoinMenu($relationAlias = null) Adds a INNER JOIN clause to the query using the Menu relation
 *
 * @method     ChildOrderDetailMixedWithQuery joinWithMenu($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menu relation
 *
 * @method     ChildOrderDetailMixedWithQuery leftJoinWithMenu() Adds a LEFT JOIN clause and with to the query using the Menu relation
 * @method     ChildOrderDetailMixedWithQuery rightJoinWithMenu() Adds a RIGHT JOIN clause and with to the query using the Menu relation
 * @method     ChildOrderDetailMixedWithQuery innerJoinWithMenu() Adds a INNER JOIN clause and with to the query using the Menu relation
 *
 * @method     ChildOrderDetailMixedWithQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildOrderDetailMixedWithQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildOrderDetailMixedWithQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildOrderDetailMixedWithQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildOrderDetailMixedWithQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildOrderDetailMixedWithQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildOrderDetailMixedWithQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     \API\Models\Menu\MenuQuery|\API\Models\Ordering\OrderDetailQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrderDetailMixedWith findOne(ConnectionInterface $con = null) Return the first ChildOrderDetailMixedWith matching the query
 * @method     ChildOrderDetailMixedWith findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrderDetailMixedWith matching the query, or a new ChildOrderDetailMixedWith object populated from the query conditions when no match is found
 *
 * @method     ChildOrderDetailMixedWith findOneByOrderDetailid(int $order_detailid) Return the first ChildOrderDetailMixedWith filtered by the order_detailid column
 * @method     ChildOrderDetailMixedWith findOneByMenuid(int $menuid) Return the first ChildOrderDetailMixedWith filtered by the menuid column *

 * @method     ChildOrderDetailMixedWith requirePk($key, ConnectionInterface $con = null) Return the ChildOrderDetailMixedWith by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetailMixedWith requireOne(ConnectionInterface $con = null) Return the first ChildOrderDetailMixedWith matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderDetailMixedWith requireOneByOrderDetailid(int $order_detailid) Return the first ChildOrderDetailMixedWith filtered by the order_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetailMixedWith requireOneByMenuid(int $menuid) Return the first ChildOrderDetailMixedWith filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderDetailMixedWith[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrderDetailMixedWith objects based on current ModelCriteria
 * @method     ChildOrderDetailMixedWith[]|ObjectCollection findByOrderDetailid(int $order_detailid) Return ChildOrderDetailMixedWith objects filtered by the order_detailid column
 * @method     ChildOrderDetailMixedWith[]|ObjectCollection findByMenuid(int $menuid) Return ChildOrderDetailMixedWith objects filtered by the menuid column
 * @method     ChildOrderDetailMixedWith[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrderDetailMixedWithQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Ordering\Base\OrderDetailMixedWithQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Ordering\\OrderDetailMixedWith', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderDetailMixedWithQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderDetailMixedWithQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrderDetailMixedWithQuery) {
            return $criteria;
        }
        $query = new ChildOrderDetailMixedWithQuery();
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
     * @param array[$order_detailid, $menuid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildOrderDetailMixedWith|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrderDetailMixedWithTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrderDetailMixedWithTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildOrderDetailMixedWith A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT order_detailid, menuid FROM order_detail_mixed_with WHERE order_detailid = :p0 AND menuid = :p1';
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
            /** @var ChildOrderDetailMixedWith $obj */
            $obj = new ChildOrderDetailMixedWith();
            $obj->hydrate($row);
            OrderDetailMixedWithTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildOrderDetailMixedWith|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_MENUID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(OrderDetailMixedWithTableMap::COL_MENUID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the order_detailid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderDetailid(1234); // WHERE order_detailid = 1234
     * $query->filterByOrderDetailid(array(12, 34)); // WHERE order_detailid IN (12, 34)
     * $query->filterByOrderDetailid(array('min' => 12)); // WHERE order_detailid > 12
     * </code>
     *
     * @see       filterByOrderDetail()
     *
     * @param     mixed $orderDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function filterByOrderDetailid($orderDetailid = null, $comparison = null)
    {
        if (is_array($orderDetailid)) {
            $useMinMax = false;
            if (isset($orderDetailid['min'])) {
                $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $orderDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderDetailid['max'])) {
                $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $orderDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $orderDetailid, $comparison);
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
     * @see       filterByMenu()
     *
     * @param     mixed $menuid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailMixedWithTableMap::COL_MENUID, $menuid, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menu\Menu object
     *
     * @param \API\Models\Menu\Menu|ObjectCollection $menu The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = null)
    {
        if ($menu instanceof \API\Models\Menu\Menu) {
            return $this
                ->addUsingAlias(OrderDetailMixedWithTableMap::COL_MENUID, $menu->getMenuid(), $comparison);
        } elseif ($menu instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailMixedWithTableMap::COL_MENUID, $menu->toKeyValue('Menuid', 'Menuid'), $comparison);
        } else {
            throw new PropelException('filterByMenu() only accepts arguments of type \API\Models\Menu\Menu or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menu relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function joinMenu($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Menu');

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
            $this->addJoinObject($join, 'Menu');
        }

        return $this;
    }

    /**
     * Use the Menu relation Menu object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuQuery A secondary query class using the current class as primary query
     */
    public function useMenuQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenu($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menu', '\API\Models\Menu\MenuQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrderDetail object
     *
     * @param \API\Models\Ordering\OrderDetail|ObjectCollection $orderDetail The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $orderDetail->getOrderDetailid(), $comparison);
        } elseif ($orderDetail instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID, $orderDetail->toKeyValue('OrderDetailid', 'OrderDetailid'), $comparison);
        } else {
            throw new PropelException('filterByOrderDetail() only accepts arguments of type \API\Models\Ordering\OrderDetail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function joinOrderDetail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderDetail');

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
            $this->addJoinObject($join, 'OrderDetail');
        }

        return $this;
    }

    /**
     * Use the OrderDetail relation OrderDetail object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrderDetailQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderDetail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetail', '\API\Models\Ordering\OrderDetailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrderDetailMixedWith $orderDetailMixedWith Object to remove from the list of results
     *
     * @return $this|ChildOrderDetailMixedWithQuery The current query, for fluid interface
     */
    public function prune($orderDetailMixedWith = null)
    {
        if ($orderDetailMixedWith) {
            $this->addCond('pruneCond0', $this->getAliasedColName(OrderDetailMixedWithTableMap::COL_ORDER_DETAILID), $orderDetailMixedWith->getOrderDetailid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(OrderDetailMixedWithTableMap::COL_MENUID), $orderDetailMixedWith->getMenuid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the order_detail_mixed_with table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailMixedWithTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrderDetailMixedWithTableMap::clearInstancePool();
            OrderDetailMixedWithTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailMixedWithTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrderDetailMixedWithTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrderDetailMixedWithTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrderDetailMixedWithTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrderDetailMixedWithQuery
