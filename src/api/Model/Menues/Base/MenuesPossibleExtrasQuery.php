<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\Menues\MenuesPossibleExtras as ChildMenuesPossibleExtras;
use Model\Menues\MenuesPossibleExtrasQuery as ChildMenuesPossibleExtrasQuery;
use Model\Menues\Map\MenuesPossibleExtrasTableMap;
use Model\Ordering\OrdersDetailExtras;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menues_possible_extras' table.
 *
 *
 *
 * @method     ChildMenuesPossibleExtrasQuery orderByMenuesPossibleExtraid($order = Criteria::ASC) Order by the menues_possible_extraid column
 * @method     ChildMenuesPossibleExtrasQuery orderByMenuExtraid($order = Criteria::ASC) Order by the menu_extraid column
 * @method     ChildMenuesPossibleExtrasQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildMenuesPossibleExtrasQuery orderByPrice($order = Criteria::ASC) Order by the price column
 *
 * @method     ChildMenuesPossibleExtrasQuery groupByMenuesPossibleExtraid() Group by the menues_possible_extraid column
 * @method     ChildMenuesPossibleExtrasQuery groupByMenuExtraid() Group by the menu_extraid column
 * @method     ChildMenuesPossibleExtrasQuery groupByMenuid() Group by the menuid column
 * @method     ChildMenuesPossibleExtrasQuery groupByPrice() Group by the price column
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuesPossibleExtrasQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuesPossibleExtrasQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuesPossibleExtrasQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuesPossibleExtrasQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinMenuExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuExtras relation
 * @method     ChildMenuesPossibleExtrasQuery rightJoinMenuExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuExtras relation
 * @method     ChildMenuesPossibleExtrasQuery innerJoinMenuExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuExtras relation
 *
 * @method     ChildMenuesPossibleExtrasQuery joinWithMenuExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuExtras relation
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinWithMenuExtras() Adds a LEFT JOIN clause and with to the query using the MenuExtras relation
 * @method     ChildMenuesPossibleExtrasQuery rightJoinWithMenuExtras() Adds a RIGHT JOIN clause and with to the query using the MenuExtras relation
 * @method     ChildMenuesPossibleExtrasQuery innerJoinWithMenuExtras() Adds a INNER JOIN clause and with to the query using the MenuExtras relation
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinMenues($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menues relation
 * @method     ChildMenuesPossibleExtrasQuery rightJoinMenues($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menues relation
 * @method     ChildMenuesPossibleExtrasQuery innerJoinMenues($relationAlias = null) Adds a INNER JOIN clause to the query using the Menues relation
 *
 * @method     ChildMenuesPossibleExtrasQuery joinWithMenues($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menues relation
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinWithMenues() Adds a LEFT JOIN clause and with to the query using the Menues relation
 * @method     ChildMenuesPossibleExtrasQuery rightJoinWithMenues() Adds a RIGHT JOIN clause and with to the query using the Menues relation
 * @method     ChildMenuesPossibleExtrasQuery innerJoinWithMenues() Adds a INNER JOIN clause and with to the query using the Menues relation
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinOrdersDetailExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetailExtras relation
 * @method     ChildMenuesPossibleExtrasQuery rightJoinOrdersDetailExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetailExtras relation
 * @method     ChildMenuesPossibleExtrasQuery innerJoinOrdersDetailExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetailExtras relation
 *
 * @method     ChildMenuesPossibleExtrasQuery joinWithOrdersDetailExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetailExtras relation
 *
 * @method     ChildMenuesPossibleExtrasQuery leftJoinWithOrdersDetailExtras() Adds a LEFT JOIN clause and with to the query using the OrdersDetailExtras relation
 * @method     ChildMenuesPossibleExtrasQuery rightJoinWithOrdersDetailExtras() Adds a RIGHT JOIN clause and with to the query using the OrdersDetailExtras relation
 * @method     ChildMenuesPossibleExtrasQuery innerJoinWithOrdersDetailExtras() Adds a INNER JOIN clause and with to the query using the OrdersDetailExtras relation
 *
 * @method     \Model\Menues\MenuExtrasQuery|\Model\Menues\MenuesQuery|\Model\Ordering\OrdersDetailExtrasQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuesPossibleExtras findOne(ConnectionInterface $con = null) Return the first ChildMenuesPossibleExtras matching the query
 * @method     ChildMenuesPossibleExtras findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuesPossibleExtras matching the query, or a new ChildMenuesPossibleExtras object populated from the query conditions when no match is found
 *
 * @method     ChildMenuesPossibleExtras findOneByMenuesPossibleExtraid(int $menues_possible_extraid) Return the first ChildMenuesPossibleExtras filtered by the menues_possible_extraid column
 * @method     ChildMenuesPossibleExtras findOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuesPossibleExtras filtered by the menu_extraid column
 * @method     ChildMenuesPossibleExtras findOneByMenuid(int $menuid) Return the first ChildMenuesPossibleExtras filtered by the menuid column
 * @method     ChildMenuesPossibleExtras findOneByPrice(string $price) Return the first ChildMenuesPossibleExtras filtered by the price column *

 * @method     ChildMenuesPossibleExtras requirePk($key, ConnectionInterface $con = null) Return the ChildMenuesPossibleExtras by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleExtras requireOne(ConnectionInterface $con = null) Return the first ChildMenuesPossibleExtras matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuesPossibleExtras requireOneByMenuesPossibleExtraid(int $menues_possible_extraid) Return the first ChildMenuesPossibleExtras filtered by the menues_possible_extraid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleExtras requireOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuesPossibleExtras filtered by the menu_extraid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleExtras requireOneByMenuid(int $menuid) Return the first ChildMenuesPossibleExtras filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleExtras requireOneByPrice(string $price) Return the first ChildMenuesPossibleExtras filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuesPossibleExtras[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuesPossibleExtras objects based on current ModelCriteria
 * @method     ChildMenuesPossibleExtras[]|ObjectCollection findByMenuesPossibleExtraid(int $menues_possible_extraid) Return ChildMenuesPossibleExtras objects filtered by the menues_possible_extraid column
 * @method     ChildMenuesPossibleExtras[]|ObjectCollection findByMenuExtraid(int $menu_extraid) Return ChildMenuesPossibleExtras objects filtered by the menu_extraid column
 * @method     ChildMenuesPossibleExtras[]|ObjectCollection findByMenuid(int $menuid) Return ChildMenuesPossibleExtras objects filtered by the menuid column
 * @method     ChildMenuesPossibleExtras[]|ObjectCollection findByPrice(string $price) Return ChildMenuesPossibleExtras objects filtered by the price column
 * @method     ChildMenuesPossibleExtras[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuesPossibleExtrasQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Menues\Base\MenuesPossibleExtrasQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Menues\\MenuesPossibleExtras', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuesPossibleExtrasQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuesPossibleExtrasQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuesPossibleExtrasQuery) {
            return $criteria;
        }
        $query = new ChildMenuesPossibleExtrasQuery();
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
     * @param array[$menues_possible_extraid, $menu_extraid, $menuid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuesPossibleExtras|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuesPossibleExtrasTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuesPossibleExtrasTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildMenuesPossibleExtras A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menues_possible_extraid, menu_extraid, menuid, price FROM menues_possible_extras WHERE menues_possible_extraid = :p0 AND menu_extraid = :p1 AND menuid = :p2';
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
            /** @var ChildMenuesPossibleExtras $obj */
            $obj = new ChildMenuesPossibleExtras();
            $obj->hydrate($row);
            MenuesPossibleExtrasTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildMenuesPossibleExtras|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(MenuesPossibleExtrasTableMap::COL_MENUID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the menues_possible_extraid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuesPossibleExtraid(1234); // WHERE menues_possible_extraid = 1234
     * $query->filterByMenuesPossibleExtraid(array(12, 34)); // WHERE menues_possible_extraid IN (12, 34)
     * $query->filterByMenuesPossibleExtraid(array('min' => 12)); // WHERE menues_possible_extraid > 12
     * </code>
     *
     * @param     mixed $menuesPossibleExtraid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByMenuesPossibleExtraid($menuesPossibleExtraid = null, $comparison = null)
    {
        if (is_array($menuesPossibleExtraid)) {
            $useMinMax = false;
            if (isset($menuesPossibleExtraid['min'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $menuesPossibleExtraid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuesPossibleExtraid['max'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $menuesPossibleExtraid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $menuesPossibleExtraid, $comparison);
    }

    /**
     * Filter the query on the menu_extraid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuExtraid(1234); // WHERE menu_extraid = 1234
     * $query->filterByMenuExtraid(array(12, 34)); // WHERE menu_extraid IN (12, 34)
     * $query->filterByMenuExtraid(array('min' => 12)); // WHERE menu_extraid > 12
     * </code>
     *
     * @see       filterByMenuExtras()
     *
     * @param     mixed $menuExtraid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByMenuExtraid($menuExtraid = null, $comparison = null)
    {
        if (is_array($menuExtraid)) {
            $useMinMax = false;
            if (isset($menuExtraid['min'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $menuExtraid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuExtraid['max'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $menuExtraid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $menuExtraid, $comparison);
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
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUID, $menuid, $comparison);
    }

    /**
     * Filter the query on the price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice(1234); // WHERE price = 1234
     * $query->filterByPrice(array(12, 34)); // WHERE price IN (12, 34)
     * $query->filterByPrice(array('min' => 12)); // WHERE price > 12
     * </code>
     *
     * @param     mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleExtrasTableMap::COL_PRICE, $price, $comparison);
    }

    /**
     * Filter the query by a related \Model\Menues\MenuExtras object
     *
     * @param \Model\Menues\MenuExtras|ObjectCollection $menuExtras The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByMenuExtras($menuExtras, $comparison = null)
    {
        if ($menuExtras instanceof \Model\Menues\MenuExtras) {
            return $this
                ->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $menuExtras->getMenuExtraid(), $comparison);
        } elseif ($menuExtras instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID, $menuExtras->toKeyValue('MenuExtraid', 'MenuExtraid'), $comparison);
        } else {
            throw new PropelException('filterByMenuExtras() only accepts arguments of type \Model\Menues\MenuExtras or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuExtras relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function joinMenuExtras($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuExtras');

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
            $this->addJoinObject($join, 'MenuExtras');
        }

        return $this;
    }

    /**
     * Use the MenuExtras relation MenuExtras object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuExtrasQuery A secondary query class using the current class as primary query
     */
    public function useMenuExtrasQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuExtras($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuExtras', '\Model\Menues\MenuExtrasQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\Menues object
     *
     * @param \Model\Menues\Menues|ObjectCollection $menues The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByMenues($menues, $comparison = null)
    {
        if ($menues instanceof \Model\Menues\Menues) {
            return $this
                ->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUID, $menues->getMenuid(), $comparison);
        } elseif ($menues instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUID, $menues->toKeyValue('Menuid', 'Menuid'), $comparison);
        } else {
            throw new PropelException('filterByMenues() only accepts arguments of type \Model\Menues\Menues or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menues relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
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
     * @return \Model\Menues\MenuesQuery A secondary query class using the current class as primary query
     */
    public function useMenuesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenues($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menues', '\Model\Menues\MenuesQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetailExtras object
     *
     * @param \Model\Ordering\OrdersDetailExtras|ObjectCollection $ordersDetailExtras the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailExtras($ordersDetailExtras, $comparison = null)
    {
        if ($ordersDetailExtras instanceof \Model\Ordering\OrdersDetailExtras) {
            return $this
                ->addUsingAlias(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID, $ordersDetailExtras->getMenuesPossibleExtraid(), $comparison);
        } elseif ($ordersDetailExtras instanceof ObjectCollection) {
            return $this
                ->useOrdersDetailExtrasQuery()
                ->filterByPrimaryKeys($ordersDetailExtras->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersDetailExtras() only accepts arguments of type \Model\Ordering\OrdersDetailExtras or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersDetailExtras relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function joinOrdersDetailExtras($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersDetailExtras');

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
            $this->addJoinObject($join, 'OrdersDetailExtras');
        }

        return $this;
    }

    /**
     * Use the OrdersDetailExtras relation OrdersDetailExtras object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Ordering\OrdersDetailExtrasQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailExtrasQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersDetailExtras($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetailExtras', '\Model\Ordering\OrdersDetailExtrasQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuesPossibleExtras $menuesPossibleExtras Object to remove from the list of results
     *
     * @return $this|ChildMenuesPossibleExtrasQuery The current query, for fluid interface
     */
    public function prune($menuesPossibleExtras = null)
    {
        if ($menuesPossibleExtras) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuesPossibleExtrasTableMap::COL_MENUES_POSSIBLE_EXTRAID), $menuesPossibleExtras->getMenuesPossibleExtraid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuesPossibleExtrasTableMap::COL_MENU_EXTRAID), $menuesPossibleExtras->getMenuExtraid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(MenuesPossibleExtrasTableMap::COL_MENUID), $menuesPossibleExtras->getMenuid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menues_possible_extras table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesPossibleExtrasTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuesPossibleExtrasTableMap::clearInstancePool();
            MenuesPossibleExtrasTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesPossibleExtrasTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuesPossibleExtrasTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuesPossibleExtrasTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuesPossibleExtrasTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuesPossibleExtrasQuery
