<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Menu\MenuPossibleExtra as ChildMenuPossibleExtra;
use API\Models\Menu\MenuPossibleExtraQuery as ChildMenuPossibleExtraQuery;
use API\Models\Menu\Map\MenuPossibleExtraTableMap;
use API\Models\Ordering\OrderDetailExtra;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_possible_extra' table.
 *
 * @method ChildMenuPossibleExtraQuery orderByMenuPossibleExtraid($order = Criteria::ASC) Order by the menu_possible_extraid column
 * @method ChildMenuPossibleExtraQuery orderByMenuExtraid($order = Criteria::ASC) Order by the menu_extraid column
 * @method ChildMenuPossibleExtraQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method ChildMenuPossibleExtraQuery orderByPrice($order = Criteria::ASC) Order by the price column
 *
 * @method ChildMenuPossibleExtraQuery groupByMenuPossibleExtraid() Group by the menu_possible_extraid column
 * @method ChildMenuPossibleExtraQuery groupByMenuExtraid() Group by the menu_extraid column
 * @method ChildMenuPossibleExtraQuery groupByMenuid() Group by the menuid column
 * @method ChildMenuPossibleExtraQuery groupByPrice() Group by the price column
 *
 * @method ChildMenuPossibleExtraQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ChildMenuPossibleExtraQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ChildMenuPossibleExtraQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ChildMenuPossibleExtraQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method ChildMenuPossibleExtraQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method ChildMenuPossibleExtraQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method ChildMenuPossibleExtraQuery leftJoinMenuExtra($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuExtra relation
 * @method ChildMenuPossibleExtraQuery rightJoinMenuExtra($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuExtra relation
 * @method ChildMenuPossibleExtraQuery innerJoinMenuExtra($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuExtra relation
 *
 * @method ChildMenuPossibleExtraQuery joinWithMenuExtra($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuExtra relation
 *
 * @method ChildMenuPossibleExtraQuery leftJoinWithMenuExtra() Adds a LEFT JOIN clause and with to the query using the MenuExtra relation
 * @method ChildMenuPossibleExtraQuery rightJoinWithMenuExtra() Adds a RIGHT JOIN clause and with to the query using the MenuExtra relation
 * @method ChildMenuPossibleExtraQuery innerJoinWithMenuExtra() Adds a INNER JOIN clause and with to the query using the MenuExtra relation
 *
 * @method ChildMenuPossibleExtraQuery leftJoinMenu($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menu relation
 * @method ChildMenuPossibleExtraQuery rightJoinMenu($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menu relation
 * @method ChildMenuPossibleExtraQuery innerJoinMenu($relationAlias = null) Adds a INNER JOIN clause to the query using the Menu relation
 *
 * @method ChildMenuPossibleExtraQuery joinWithMenu($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menu relation
 *
 * @method ChildMenuPossibleExtraQuery leftJoinWithMenu() Adds a LEFT JOIN clause and with to the query using the Menu relation
 * @method ChildMenuPossibleExtraQuery rightJoinWithMenu() Adds a RIGHT JOIN clause and with to the query using the Menu relation
 * @method ChildMenuPossibleExtraQuery innerJoinWithMenu() Adds a INNER JOIN clause and with to the query using the Menu relation
 *
 * @method ChildMenuPossibleExtraQuery leftJoinOrderDetailExtra($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetailExtra relation
 * @method ChildMenuPossibleExtraQuery rightJoinOrderDetailExtra($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetailExtra relation
 * @method ChildMenuPossibleExtraQuery innerJoinOrderDetailExtra($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetailExtra relation
 *
 * @method ChildMenuPossibleExtraQuery joinWithOrderDetailExtra($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetailExtra relation
 *
 * @method ChildMenuPossibleExtraQuery leftJoinWithOrderDetailExtra() Adds a LEFT JOIN clause and with to the query using the OrderDetailExtra relation
 * @method ChildMenuPossibleExtraQuery rightJoinWithOrderDetailExtra() Adds a RIGHT JOIN clause and with to the query using the OrderDetailExtra relation
 * @method ChildMenuPossibleExtraQuery innerJoinWithOrderDetailExtra() Adds a INNER JOIN clause and with to the query using the OrderDetailExtra relation
 *
 * @method \API\Models\Menu\MenuExtraQuery|\API\Models\Menu\MenuQuery|\API\Models\Ordering\OrderDetailExtraQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method ChildMenuPossibleExtra findOne(ConnectionInterface $con = null) Return the first ChildMenuPossibleExtra matching the query
 * @method ChildMenuPossibleExtra findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuPossibleExtra matching the query, or a new ChildMenuPossibleExtra object populated from the query conditions when no match is found
 *
 * @method ChildMenuPossibleExtra findOneByMenuPossibleExtraid(int $menu_possible_extraid) Return the first ChildMenuPossibleExtra filtered by the menu_possible_extraid column
 * @method ChildMenuPossibleExtra findOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuPossibleExtra filtered by the menu_extraid column
 * @method ChildMenuPossibleExtra findOneByMenuid(int $menuid) Return the first ChildMenuPossibleExtra filtered by the menuid column
 * @method ChildMenuPossibleExtra findOneByPrice(string $price) Return the first ChildMenuPossibleExtra filtered by the price column *

 * @method ChildMenuPossibleExtra requirePk($key, ConnectionInterface $con = null) Return the ChildMenuPossibleExtra by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildMenuPossibleExtra requireOne(ConnectionInterface $con = null) Return the first ChildMenuPossibleExtra matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildMenuPossibleExtra requireOneByMenuPossibleExtraid(int $menu_possible_extraid) Return the first ChildMenuPossibleExtra filtered by the menu_possible_extraid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildMenuPossibleExtra requireOneByMenuExtraid(int $menu_extraid) Return the first ChildMenuPossibleExtra filtered by the menu_extraid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildMenuPossibleExtra requireOneByMenuid(int $menuid) Return the first ChildMenuPossibleExtra filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildMenuPossibleExtra requireOneByPrice(string $price) Return the first ChildMenuPossibleExtra filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildMenuPossibleExtra[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuPossibleExtra objects based on current ModelCriteria
 * @method ChildMenuPossibleExtra[]|ObjectCollection findByMenuPossibleExtraid(int $menu_possible_extraid) Return ChildMenuPossibleExtra objects filtered by the menu_possible_extraid column
 * @method ChildMenuPossibleExtra[]|ObjectCollection findByMenuExtraid(int $menu_extraid) Return ChildMenuPossibleExtra objects filtered by the menu_extraid column
 * @method ChildMenuPossibleExtra[]|ObjectCollection findByMenuid(int $menuid) Return ChildMenuPossibleExtra objects filtered by the menuid column
 * @method ChildMenuPossibleExtra[]|ObjectCollection findByPrice(string $price) Return ChildMenuPossibleExtra objects filtered by the price column
 * @method ChildMenuPossibleExtra[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class MenuPossibleExtraQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menu\Base\MenuPossibleExtraQuery object.
     *
     * @param string $dbName     The database name
     * @param string $modelName  The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menu\\MenuPossibleExtra', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuPossibleExtraQuery object.
     *
     * @param string   $modelAlias The alias of a model in the query
     * @param Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildMenuPossibleExtraQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuPossibleExtraQuery) {
            return $criteria;
        }
        $query = new ChildMenuPossibleExtraQuery();
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
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuPossibleExtra|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if ($this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuPossibleExtraTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuPossibleExtra A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menu_possible_extraid, menu_extraid, menuid, price FROM menu_possible_extra WHERE menu_possible_extraid = :p0';
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
            /**
 * @var ChildMenuPossibleExtra $obj
*/
            $obj = new ChildMenuPossibleExtra();
            $obj->hydrate($row);
            MenuPossibleExtraTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildMenuPossibleExtra|array|mixed the result, formatted by the current formatter
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
     *
     * @param array               $keys Primary keys to use for the query
     * @param ConnectionInterface $con  an optional connection object
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the menu_possible_extraid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuPossibleExtraid(1234); // WHERE menu_possible_extraid = 1234
     * $query->filterByMenuPossibleExtraid(array(12, 34)); // WHERE menu_possible_extraid IN (12, 34)
     * $query->filterByMenuPossibleExtraid(array('min' => 12)); // WHERE menu_possible_extraid > 12
     * </code>
     *
     * @param mixed  $menuPossibleExtraid The value to use as filter.
     *                                        Use scalar values for
     *                                        equality. Use array values
     *                                        for in_array() equivalent.
     *                                        Use associative array('min'
     *                                        => $minValue, 'max' =>
     *                                        $maxValue) for intervals.
     * @param string $comparison          Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleExtraid($menuPossibleExtraid = null, $comparison = null)
    {
        if (is_array($menuPossibleExtraid)) {
            $useMinMax = false;
            if (isset($menuPossibleExtraid['min'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $menuPossibleExtraid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuPossibleExtraid['max'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $menuPossibleExtraid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $menuPossibleExtraid, $comparison);
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
     * @see filterByMenuExtra()
     *
     * @param mixed  $menuExtraid The value to use as filter.
     *                                Use scalar values for
     *                                equality. Use array values
     *                                for in_array() equivalent.
     *                                Use associative array('min'
     *                                => $minValue, 'max' =>
     *                                $maxValue) for intervals.
     * @param string $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByMenuExtraid($menuExtraid = null, $comparison = null)
    {
        if (is_array($menuExtraid)) {
            $useMinMax = false;
            if (isset($menuExtraid['min'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_EXTRAID, $menuExtraid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuExtraid['max'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_EXTRAID, $menuExtraid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_EXTRAID, $menuExtraid, $comparison);
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
     * @see filterByMenu()
     *
     * @param mixed  $menuid     The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENUID, $menuid, $comparison);
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
     * @param mixed  $price      The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(MenuPossibleExtraTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleExtraTableMap::COL_PRICE, $price, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuExtra object
     *
     * @param \API\Models\Menu\MenuExtra|ObjectCollection $menuExtra  The related object(s) to use as filter
     * @param string                                      $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByMenuExtra($menuExtra, $comparison = null)
    {
        if ($menuExtra instanceof \API\Models\Menu\MenuExtra) {
            return $this
                ->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_EXTRAID, $menuExtra->getMenuExtraid(), $comparison);
        } elseif ($menuExtra instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_EXTRAID, $menuExtra->toKeyValue('PrimaryKey', 'MenuExtraid'), $comparison);
        } else {
            throw new PropelException('filterByMenuExtra() only accepts arguments of type \API\Models\Menu\MenuExtra or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuExtra relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function joinMenuExtra($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuExtra');

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
            $this->addJoinObject($join, 'MenuExtra');
        }

        return $this;
    }

    /**
     * Use the MenuExtra relation MenuExtra object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuExtraQuery A secondary query class using the current class as primary query
     */
    public function useMenuExtraQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuExtra($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuExtra', '\API\Models\Menu\MenuExtraQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\Menu object
     *
     * @param \API\Models\Menu\Menu|ObjectCollection $menu       The related object(s) to use as filter
     * @param string                                 $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = null)
    {
        if ($menu instanceof \API\Models\Menu\Menu) {
            return $this
                ->addUsingAlias(MenuPossibleExtraTableMap::COL_MENUID, $menu->getMenuid(), $comparison);
        } elseif ($menu instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuPossibleExtraTableMap::COL_MENUID, $menu->toKeyValue('PrimaryKey', 'Menuid'), $comparison);
        } else {
            throw new PropelException('filterByMenu() only accepts arguments of type \API\Models\Menu\Menu or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menu relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \API\Models\Ordering\OrderDetailExtra object
     *
     * @param \API\Models\Ordering\OrderDetailExtra|ObjectCollection $orderDetailExtra the related object to use as filter
     * @param string                                                 $comparison       Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByOrderDetailExtra($orderDetailExtra, $comparison = null)
    {
        if ($orderDetailExtra instanceof \API\Models\Ordering\OrderDetailExtra) {
            return $this
                ->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $orderDetailExtra->getMenuPossibleExtraid(), $comparison);
        } elseif ($orderDetailExtra instanceof ObjectCollection) {
            return $this
                ->useOrderDetailExtraQuery()
                ->filterByPrimaryKeys($orderDetailExtra->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderDetailExtra() only accepts arguments of type \API\Models\Ordering\OrderDetailExtra or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetailExtra relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function joinOrderDetailExtra($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderDetailExtra');

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
            $this->addJoinObject($join, 'OrderDetailExtra');
        }

        return $this;
    }

    /**
     * Use the OrderDetailExtra relation OrderDetailExtra object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrderDetailExtraQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailExtraQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderDetailExtra($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetailExtra', '\API\Models\Ordering\OrderDetailExtraQuery');
    }

    /**
     * Filter the query by a related OrderDetail object
     * using the order_detail_extra table as cross reference
     *
     * @param OrderDetail $orderDetail the related object to use as filter
     * @param string      $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useOrderDetailExtraQuery()
            ->filterByOrderDetail($orderDetail, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param ChildMenuPossibleExtra $menuPossibleExtra Object to remove from the list of results
     *
     * @return $this|ChildMenuPossibleExtraQuery The current query, for fluid interface
     */
    public function prune($menuPossibleExtra = null)
    {
        if ($menuPossibleExtra) {
            $this->addUsingAlias(MenuPossibleExtraTableMap::COL_MENU_POSSIBLE_EXTRAID, $menuPossibleExtra->getMenuPossibleExtraid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_possible_extra table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con) {
                $affectedRows = 0; // initialize var to track total num of affected rows
                $affectedRows += parent::doDeleteAll($con);
                // Because this db requires some delete cascade/set null emulation, we have to
                // clear the cached instance *after* the emulation has happened (since
                // instances get re-added by the select statement contained therein).
                MenuPossibleExtraTableMap::clearInstancePool();
                MenuPossibleExtraTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleExtraTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuPossibleExtraTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con, $criteria) {
                $affectedRows = 0; // initialize var to track total num of affected rows

                MenuPossibleExtraTableMap::removeInstanceFromPool($criteria);

                $affectedRows += ModelCriteria::delete($con);
                MenuPossibleExtraTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }
} // MenuPossibleExtraQuery
