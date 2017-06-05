<?php

namespace API\Models\ORM\Menu\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Menu\MenuPossibleSize as ChildMenuPossibleSize;
use API\Models\ORM\Menu\MenuPossibleSizeQuery as ChildMenuPossibleSizeQuery;
use API\Models\ORM\Menu\Map\MenuPossibleSizeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu_possible_size' table.
 *
 * 
 *
 * @method     ChildMenuPossibleSizeQuery orderByMenuPossibleSizeid($order = Criteria::ASC) Order by the menu_possible_sizeid column
 * @method     ChildMenuPossibleSizeQuery orderByMenuSizeid($order = Criteria::ASC) Order by the menu_sizeid column
 * @method     ChildMenuPossibleSizeQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildMenuPossibleSizeQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildMenuPossibleSizeQuery orderByIsDeleted($order = Criteria::ASC) Order by the is_deleted column
 *
 * @method     ChildMenuPossibleSizeQuery groupByMenuPossibleSizeid() Group by the menu_possible_sizeid column
 * @method     ChildMenuPossibleSizeQuery groupByMenuSizeid() Group by the menu_sizeid column
 * @method     ChildMenuPossibleSizeQuery groupByMenuid() Group by the menuid column
 * @method     ChildMenuPossibleSizeQuery groupByPrice() Group by the price column
 * @method     ChildMenuPossibleSizeQuery groupByIsDeleted() Group by the is_deleted column
 *
 * @method     ChildMenuPossibleSizeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuPossibleSizeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuPossibleSizeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuPossibleSizeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuPossibleSizeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuPossibleSizeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuPossibleSizeQuery leftJoinMenuSize($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuSize relation
 * @method     ChildMenuPossibleSizeQuery rightJoinMenuSize($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuSize relation
 * @method     ChildMenuPossibleSizeQuery innerJoinMenuSize($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuSize relation
 *
 * @method     ChildMenuPossibleSizeQuery joinWithMenuSize($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuSize relation
 *
 * @method     ChildMenuPossibleSizeQuery leftJoinWithMenuSize() Adds a LEFT JOIN clause and with to the query using the MenuSize relation
 * @method     ChildMenuPossibleSizeQuery rightJoinWithMenuSize() Adds a RIGHT JOIN clause and with to the query using the MenuSize relation
 * @method     ChildMenuPossibleSizeQuery innerJoinWithMenuSize() Adds a INNER JOIN clause and with to the query using the MenuSize relation
 *
 * @method     ChildMenuPossibleSizeQuery leftJoinMenu($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menu relation
 * @method     ChildMenuPossibleSizeQuery rightJoinMenu($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menu relation
 * @method     ChildMenuPossibleSizeQuery innerJoinMenu($relationAlias = null) Adds a INNER JOIN clause to the query using the Menu relation
 *
 * @method     ChildMenuPossibleSizeQuery joinWithMenu($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menu relation
 *
 * @method     ChildMenuPossibleSizeQuery leftJoinWithMenu() Adds a LEFT JOIN clause and with to the query using the Menu relation
 * @method     ChildMenuPossibleSizeQuery rightJoinWithMenu() Adds a RIGHT JOIN clause and with to the query using the Menu relation
 * @method     ChildMenuPossibleSizeQuery innerJoinWithMenu() Adds a INNER JOIN clause and with to the query using the Menu relation
 *
 * @method     \API\Models\ORM\Menu\MenuSizeQuery|\API\Models\ORM\Menu\MenuQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuPossibleSize findOne(ConnectionInterface $con = null) Return the first ChildMenuPossibleSize matching the query
 * @method     ChildMenuPossibleSize findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuPossibleSize matching the query, or a new ChildMenuPossibleSize object populated from the query conditions when no match is found
 *
 * @method     ChildMenuPossibleSize findOneByMenuPossibleSizeid(int $menu_possible_sizeid) Return the first ChildMenuPossibleSize filtered by the menu_possible_sizeid column
 * @method     ChildMenuPossibleSize findOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuPossibleSize filtered by the menu_sizeid column
 * @method     ChildMenuPossibleSize findOneByMenuid(int $menuid) Return the first ChildMenuPossibleSize filtered by the menuid column
 * @method     ChildMenuPossibleSize findOneByPrice(string $price) Return the first ChildMenuPossibleSize filtered by the price column
 * @method     ChildMenuPossibleSize findOneByIsDeleted(string $is_deleted) Return the first ChildMenuPossibleSize filtered by the is_deleted column *

 * @method     ChildMenuPossibleSize requirePk($key, ConnectionInterface $con = null) Return the ChildMenuPossibleSize by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuPossibleSize requireOne(ConnectionInterface $con = null) Return the first ChildMenuPossibleSize matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuPossibleSize requireOneByMenuPossibleSizeid(int $menu_possible_sizeid) Return the first ChildMenuPossibleSize filtered by the menu_possible_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuPossibleSize requireOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuPossibleSize filtered by the menu_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuPossibleSize requireOneByMenuid(int $menuid) Return the first ChildMenuPossibleSize filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuPossibleSize requireOneByPrice(string $price) Return the first ChildMenuPossibleSize filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuPossibleSize requireOneByIsDeleted(string $is_deleted) Return the first ChildMenuPossibleSize filtered by the is_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuPossibleSize[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuPossibleSize objects based on current ModelCriteria
 * @method     ChildMenuPossibleSize[]|ObjectCollection findByMenuPossibleSizeid(int $menu_possible_sizeid) Return ChildMenuPossibleSize objects filtered by the menu_possible_sizeid column
 * @method     ChildMenuPossibleSize[]|ObjectCollection findByMenuSizeid(int $menu_sizeid) Return ChildMenuPossibleSize objects filtered by the menu_sizeid column
 * @method     ChildMenuPossibleSize[]|ObjectCollection findByMenuid(int $menuid) Return ChildMenuPossibleSize objects filtered by the menuid column
 * @method     ChildMenuPossibleSize[]|ObjectCollection findByPrice(string $price) Return ChildMenuPossibleSize objects filtered by the price column
 * @method     ChildMenuPossibleSize[]|ObjectCollection findByIsDeleted(string $is_deleted) Return ChildMenuPossibleSize objects filtered by the is_deleted column
 * @method     ChildMenuPossibleSize[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuPossibleSizeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Menu\Base\MenuPossibleSizeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Menu\\MenuPossibleSize', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuPossibleSizeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuPossibleSizeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuPossibleSizeQuery) {
            return $criteria;
        }
        $query = new ChildMenuPossibleSizeQuery();
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
     * @return ChildMenuPossibleSize|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuPossibleSizeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuPossibleSizeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMenuPossibleSize A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`, `is_deleted` FROM `menu_possible_size` WHERE `menu_possible_sizeid` = :p0';
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
            /** @var ChildMenuPossibleSize $obj */
            $obj = new ChildMenuPossibleSize();
            $obj->hydrate($row);
            MenuPossibleSizeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMenuPossibleSize|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_POSSIBLE_SIZEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_POSSIBLE_SIZEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the menu_possible_sizeid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuPossibleSizeid(1234); // WHERE menu_possible_sizeid = 1234
     * $query->filterByMenuPossibleSizeid(array(12, 34)); // WHERE menu_possible_sizeid IN (12, 34)
     * $query->filterByMenuPossibleSizeid(array('min' => 12)); // WHERE menu_possible_sizeid > 12
     * </code>
     *
     * @param     mixed $menuPossibleSizeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleSizeid($menuPossibleSizeid = null, $comparison = null)
    {
        if (is_array($menuPossibleSizeid)) {
            $useMinMax = false;
            if (isset($menuPossibleSizeid['min'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_POSSIBLE_SIZEID, $menuPossibleSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuPossibleSizeid['max'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_POSSIBLE_SIZEID, $menuPossibleSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_POSSIBLE_SIZEID, $menuPossibleSizeid, $comparison);
    }

    /**
     * Filter the query on the menu_sizeid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuSizeid(1234); // WHERE menu_sizeid = 1234
     * $query->filterByMenuSizeid(array(12, 34)); // WHERE menu_sizeid IN (12, 34)
     * $query->filterByMenuSizeid(array('min' => 12)); // WHERE menu_sizeid > 12
     * </code>
     *
     * @see       filterByMenuSize()
     *
     * @param     mixed $menuSizeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByMenuSizeid($menuSizeid = null, $comparison = null)
    {
        if (is_array($menuSizeid)) {
            $useMinMax = false;
            if (isset($menuSizeid['min'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_SIZEID, $menuSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuSizeid['max'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_SIZEID, $menuSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_SIZEID, $menuSizeid, $comparison);
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
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENUID, $menuid, $comparison);
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
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the is_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByIsDeleted('2011-03-14'); // WHERE is_deleted = '2011-03-14'
     * $query->filterByIsDeleted('now'); // WHERE is_deleted = '2011-03-14'
     * $query->filterByIsDeleted(array('max' => 'yesterday')); // WHERE is_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $isDeleted The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByIsDeleted($isDeleted = null, $comparison = null)
    {
        if (is_array($isDeleted)) {
            $useMinMax = false;
            if (isset($isDeleted['min'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_IS_DELETED, $isDeleted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isDeleted['max'])) {
                $this->addUsingAlias(MenuPossibleSizeTableMap::COL_IS_DELETED, $isDeleted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuPossibleSizeTableMap::COL_IS_DELETED, $isDeleted, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\MenuSize object
     *
     * @param \API\Models\ORM\Menu\MenuSize|ObjectCollection $menuSize The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByMenuSize($menuSize, $comparison = null)
    {
        if ($menuSize instanceof \API\Models\ORM\Menu\MenuSize) {
            return $this
                ->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_SIZEID, $menuSize->getMenuSizeid(), $comparison);
        } elseif ($menuSize instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_SIZEID, $menuSize->toKeyValue('PrimaryKey', 'MenuSizeid'), $comparison);
        } else {
            throw new PropelException('filterByMenuSize() only accepts arguments of type \API\Models\ORM\Menu\MenuSize or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuSize relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function joinMenuSize($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuSize');

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
            $this->addJoinObject($join, 'MenuSize');
        }

        return $this;
    }

    /**
     * Use the MenuSize relation MenuSize object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Menu\MenuSizeQuery A secondary query class using the current class as primary query
     */
    public function useMenuSizeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuSize($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuSize', '\API\Models\ORM\Menu\MenuSizeQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\Menu object
     *
     * @param \API\Models\ORM\Menu\Menu|ObjectCollection $menu The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = null)
    {
        if ($menu instanceof \API\Models\ORM\Menu\Menu) {
            return $this
                ->addUsingAlias(MenuPossibleSizeTableMap::COL_MENUID, $menu->getMenuid(), $comparison);
        } elseif ($menu instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuPossibleSizeTableMap::COL_MENUID, $menu->toKeyValue('PrimaryKey', 'Menuid'), $comparison);
        } else {
            throw new PropelException('filterByMenu() only accepts arguments of type \API\Models\ORM\Menu\Menu or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Menu relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Menu\MenuQuery A secondary query class using the current class as primary query
     */
    public function useMenuQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenu($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menu', '\API\Models\ORM\Menu\MenuQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenuPossibleSize $menuPossibleSize Object to remove from the list of results
     *
     * @return $this|ChildMenuPossibleSizeQuery The current query, for fluid interface
     */
    public function prune($menuPossibleSize = null)
    {
        if ($menuPossibleSize) {
            $this->addUsingAlias(MenuPossibleSizeTableMap::COL_MENU_POSSIBLE_SIZEID, $menuPossibleSize->getMenuPossibleSizeid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu_possible_size table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleSizeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuPossibleSizeTableMap::clearInstancePool();
            MenuPossibleSizeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuPossibleSizeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuPossibleSizeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            MenuPossibleSizeTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            MenuPossibleSizeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuPossibleSizeQuery
