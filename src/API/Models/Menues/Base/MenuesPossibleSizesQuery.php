<?php

namespace API\Models\Menues\Base;

use \Exception;
use \PDO;
use API\Models\Menues\MenuesPossibleSizes as ChildMenuesPossibleSizes;
use API\Models\Menues\MenuesPossibleSizesQuery as ChildMenuesPossibleSizesQuery;
use API\Models\Menues\Map\MenuesPossibleSizesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menues_possible_sizes' table.
 *
 *
 *
 * @method     ChildMenuesPossibleSizesQuery orderByMenuesPossibleSizeid($order = Criteria::ASC) Order by the menues_possible_sizeid column
 * @method     ChildMenuesPossibleSizesQuery orderByMenuSizeid($order = Criteria::ASC) Order by the menu_sizeid column
 * @method     ChildMenuesPossibleSizesQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildMenuesPossibleSizesQuery orderByPrice($order = Criteria::ASC) Order by the price column
 *
 * @method     ChildMenuesPossibleSizesQuery groupByMenuesPossibleSizeid() Group by the menues_possible_sizeid column
 * @method     ChildMenuesPossibleSizesQuery groupByMenuSizeid() Group by the menu_sizeid column
 * @method     ChildMenuesPossibleSizesQuery groupByMenuid() Group by the menuid column
 * @method     ChildMenuesPossibleSizesQuery groupByPrice() Group by the price column
 *
 * @method     ChildMenuesPossibleSizesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuesPossibleSizesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuesPossibleSizesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuesPossibleSizesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuesPossibleSizesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuesPossibleSizesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuesPossibleSizesQuery leftJoinMenuSizes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuSizes relation
 * @method     ChildMenuesPossibleSizesQuery rightJoinMenuSizes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuSizes relation
 * @method     ChildMenuesPossibleSizesQuery innerJoinMenuSizes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuSizes relation
 *
 * @method     ChildMenuesPossibleSizesQuery joinWithMenuSizes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuSizes relation
 *
 * @method     ChildMenuesPossibleSizesQuery leftJoinWithMenuSizes() Adds a LEFT JOIN clause and with to the query using the MenuSizes relation
 * @method     ChildMenuesPossibleSizesQuery rightJoinWithMenuSizes() Adds a RIGHT JOIN clause and with to the query using the MenuSizes relation
 * @method     ChildMenuesPossibleSizesQuery innerJoinWithMenuSizes() Adds a INNER JOIN clause and with to the query using the MenuSizes relation
 *
 * @method     ChildMenuesPossibleSizesQuery leftJoinMenues($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menues relation
 * @method     ChildMenuesPossibleSizesQuery rightJoinMenues($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menues relation
 * @method     ChildMenuesPossibleSizesQuery innerJoinMenues($relationAlias = null) Adds a INNER JOIN clause to the query using the Menues relation
 *
 * @method     ChildMenuesPossibleSizesQuery joinWithMenues($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menues relation
 *
 * @method     ChildMenuesPossibleSizesQuery leftJoinWithMenues() Adds a LEFT JOIN clause and with to the query using the Menues relation
 * @method     ChildMenuesPossibleSizesQuery rightJoinWithMenues() Adds a RIGHT JOIN clause and with to the query using the Menues relation
 * @method     ChildMenuesPossibleSizesQuery innerJoinWithMenues() Adds a INNER JOIN clause and with to the query using the Menues relation
 *
 * @method     \API\Models\Menues\MenuSizesQuery|\API\Models\Menues\MenuesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenuesPossibleSizes findOne(ConnectionInterface $con = null) Return the first ChildMenuesPossibleSizes matching the query
 * @method     ChildMenuesPossibleSizes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenuesPossibleSizes matching the query, or a new ChildMenuesPossibleSizes object populated from the query conditions when no match is found
 *
 * @method     ChildMenuesPossibleSizes findOneByMenuesPossibleSizeid(int $menues_possible_sizeid) Return the first ChildMenuesPossibleSizes filtered by the menues_possible_sizeid column
 * @method     ChildMenuesPossibleSizes findOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuesPossibleSizes filtered by the menu_sizeid column
 * @method     ChildMenuesPossibleSizes findOneByMenuid(int $menuid) Return the first ChildMenuesPossibleSizes filtered by the menuid column
 * @method     ChildMenuesPossibleSizes findOneByPrice(string $price) Return the first ChildMenuesPossibleSizes filtered by the price column *

 * @method     ChildMenuesPossibleSizes requirePk($key, ConnectionInterface $con = null) Return the ChildMenuesPossibleSizes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleSizes requireOne(ConnectionInterface $con = null) Return the first ChildMenuesPossibleSizes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuesPossibleSizes requireOneByMenuesPossibleSizeid(int $menues_possible_sizeid) Return the first ChildMenuesPossibleSizes filtered by the menues_possible_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleSizes requireOneByMenuSizeid(int $menu_sizeid) Return the first ChildMenuesPossibleSizes filtered by the menu_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleSizes requireOneByMenuid(int $menuid) Return the first ChildMenuesPossibleSizes filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenuesPossibleSizes requireOneByPrice(string $price) Return the first ChildMenuesPossibleSizes filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenuesPossibleSizes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenuesPossibleSizes objects based on current ModelCriteria
 * @method     ChildMenuesPossibleSizes[]|ObjectCollection findByMenuesPossibleSizeid(int $menues_possible_sizeid) Return ChildMenuesPossibleSizes objects filtered by the menues_possible_sizeid column
 * @method     ChildMenuesPossibleSizes[]|ObjectCollection findByMenuSizeid(int $menu_sizeid) Return ChildMenuesPossibleSizes objects filtered by the menu_sizeid column
 * @method     ChildMenuesPossibleSizes[]|ObjectCollection findByMenuid(int $menuid) Return ChildMenuesPossibleSizes objects filtered by the menuid column
 * @method     ChildMenuesPossibleSizes[]|ObjectCollection findByPrice(string $price) Return ChildMenuesPossibleSizes objects filtered by the price column
 * @method     ChildMenuesPossibleSizes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuesPossibleSizesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menues\Base\MenuesPossibleSizesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menues\\MenuesPossibleSizes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuesPossibleSizesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuesPossibleSizesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuesPossibleSizesQuery) {
            return $criteria;
        }
        $query = new ChildMenuesPossibleSizesQuery();
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
     * @param array[$menues_possible_sizeid, $menu_sizeid, $menuid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenuesPossibleSizes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuesPossibleSizesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuesPossibleSizesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildMenuesPossibleSizes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menues_possible_sizeid, menu_sizeid, menuid, price FROM menues_possible_sizes WHERE menues_possible_sizeid = :p0 AND menu_sizeid = :p1 AND menuid = :p2';
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
            /** @var ChildMenuesPossibleSizes $obj */
            $obj = new ChildMenuesPossibleSizes();
            $obj->hydrate($row);
            MenuesPossibleSizesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildMenuesPossibleSizes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUES_POSSIBLE_SIZEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuesPossibleSizesTableMap::COL_MENUES_POSSIBLE_SIZEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(MenuesPossibleSizesTableMap::COL_MENUID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the menues_possible_sizeid column
     *
     * Example usage:
     * <code>
     * $query->filterByMenuesPossibleSizeid(1234); // WHERE menues_possible_sizeid = 1234
     * $query->filterByMenuesPossibleSizeid(array(12, 34)); // WHERE menues_possible_sizeid IN (12, 34)
     * $query->filterByMenuesPossibleSizeid(array('min' => 12)); // WHERE menues_possible_sizeid > 12
     * </code>
     *
     * @param     mixed $menuesPossibleSizeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByMenuesPossibleSizeid($menuesPossibleSizeid = null, $comparison = null)
    {
        if (is_array($menuesPossibleSizeid)) {
            $useMinMax = false;
            if (isset($menuesPossibleSizeid['min'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUES_POSSIBLE_SIZEID, $menuesPossibleSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuesPossibleSizeid['max'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUES_POSSIBLE_SIZEID, $menuesPossibleSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUES_POSSIBLE_SIZEID, $menuesPossibleSizeid, $comparison);
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
     * @see       filterByMenuSizes()
     *
     * @param     mixed $menuSizeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByMenuSizeid($menuSizeid = null, $comparison = null)
    {
        if (is_array($menuSizeid)) {
            $useMinMax = false;
            if (isset($menuSizeid['min'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $menuSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuSizeid['max'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $menuSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $menuSizeid, $comparison);
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
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUID, $menuid, $comparison);
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
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesPossibleSizesTableMap::COL_PRICE, $price, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menues\MenuSizes object
     *
     * @param \API\Models\Menues\MenuSizes|ObjectCollection $menuSizes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByMenuSizes($menuSizes, $comparison = null)
    {
        if ($menuSizes instanceof \API\Models\Menues\MenuSizes) {
            return $this
                ->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $menuSizes->getMenuSizeid(), $comparison);
        } elseif ($menuSizes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENU_SIZEID, $menuSizes->toKeyValue('MenuSizeid', 'MenuSizeid'), $comparison);
        } else {
            throw new PropelException('filterByMenuSizes() only accepts arguments of type \API\Models\Menues\MenuSizes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuSizes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function joinMenuSizes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuSizes');

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
            $this->addJoinObject($join, 'MenuSizes');
        }

        return $this;
    }

    /**
     * Use the MenuSizes relation MenuSizes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menues\MenuSizesQuery A secondary query class using the current class as primary query
     */
    public function useMenuSizesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuSizes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuSizes', '\API\Models\Menues\MenuSizesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menues\Menues object
     *
     * @param \API\Models\Menues\Menues|ObjectCollection $menues The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function filterByMenues($menues, $comparison = null)
    {
        if ($menues instanceof \API\Models\Menues\Menues) {
            return $this
                ->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUID, $menues->getMenuid(), $comparison);
        } elseif ($menues instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuesPossibleSizesTableMap::COL_MENUID, $menues->toKeyValue('Menuid', 'Menuid'), $comparison);
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
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildMenuesPossibleSizes $menuesPossibleSizes Object to remove from the list of results
     *
     * @return $this|ChildMenuesPossibleSizesQuery The current query, for fluid interface
     */
    public function prune($menuesPossibleSizes = null)
    {
        if ($menuesPossibleSizes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuesPossibleSizesTableMap::COL_MENUES_POSSIBLE_SIZEID), $menuesPossibleSizes->getMenuesPossibleSizeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuesPossibleSizesTableMap::COL_MENU_SIZEID), $menuesPossibleSizes->getMenuSizeid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(MenuesPossibleSizesTableMap::COL_MENUID), $menuesPossibleSizes->getMenuid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menues_possible_sizes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesPossibleSizesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuesPossibleSizesTableMap::clearInstancePool();
            MenuesPossibleSizesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesPossibleSizesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuesPossibleSizesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuesPossibleSizesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuesPossibleSizesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuesPossibleSizesQuery
