<?php

namespace API\Models\Menu\Base;

use \Exception;
use \PDO;
use API\Models\Menu\Menu as ChildMenu;
use API\Models\Menu\MenuQuery as ChildMenuQuery;
use API\Models\Menu\Map\MenuTableMap;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailMixedWith;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menu' table.
 *
 *
 *
 * @method     ChildMenuQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildMenuQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildMenuQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildMenuQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildMenuQuery orderByAvailabilityAmount($order = Criteria::ASC) Order by the availability_amount column
 *
 * @method     ChildMenuQuery groupByMenuid() Group by the menuid column
 * @method     ChildMenuQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildMenuQuery groupByName() Group by the name column
 * @method     ChildMenuQuery groupByPrice() Group by the price column
 * @method     ChildMenuQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildMenuQuery groupByAvailabilityAmount() Group by the availability_amount column
 *
 * @method     ChildMenuQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuQuery leftJoinAvailability($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availability relation
 * @method     ChildMenuQuery rightJoinAvailability($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availability relation
 * @method     ChildMenuQuery innerJoinAvailability($relationAlias = null) Adds a INNER JOIN clause to the query using the Availability relation
 *
 * @method     ChildMenuQuery joinWithAvailability($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availability relation
 *
 * @method     ChildMenuQuery leftJoinWithAvailability() Adds a LEFT JOIN clause and with to the query using the Availability relation
 * @method     ChildMenuQuery rightJoinWithAvailability() Adds a RIGHT JOIN clause and with to the query using the Availability relation
 * @method     ChildMenuQuery innerJoinWithAvailability() Adds a INNER JOIN clause and with to the query using the Availability relation
 *
 * @method     ChildMenuQuery leftJoinMenuGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroup relation
 * @method     ChildMenuQuery rightJoinMenuGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroup relation
 * @method     ChildMenuQuery innerJoinMenuGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroup relation
 *
 * @method     ChildMenuQuery joinWithMenuGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroup relation
 *
 * @method     ChildMenuQuery leftJoinWithMenuGroup() Adds a LEFT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildMenuQuery rightJoinWithMenuGroup() Adds a RIGHT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildMenuQuery innerJoinWithMenuGroup() Adds a INNER JOIN clause and with to the query using the MenuGroup relation
 *
 * @method     ChildMenuQuery leftJoinMenuPossibleExtra($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuPossibleExtra relation
 * @method     ChildMenuQuery rightJoinMenuPossibleExtra($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuPossibleExtra relation
 * @method     ChildMenuQuery innerJoinMenuPossibleExtra($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuPossibleExtra relation
 *
 * @method     ChildMenuQuery joinWithMenuPossibleExtra($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuPossibleExtra relation
 *
 * @method     ChildMenuQuery leftJoinWithMenuPossibleExtra() Adds a LEFT JOIN clause and with to the query using the MenuPossibleExtra relation
 * @method     ChildMenuQuery rightJoinWithMenuPossibleExtra() Adds a RIGHT JOIN clause and with to the query using the MenuPossibleExtra relation
 * @method     ChildMenuQuery innerJoinWithMenuPossibleExtra() Adds a INNER JOIN clause and with to the query using the MenuPossibleExtra relation
 *
 * @method     ChildMenuQuery leftJoinMenuPossibleSize($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuPossibleSize relation
 * @method     ChildMenuQuery rightJoinMenuPossibleSize($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuPossibleSize relation
 * @method     ChildMenuQuery innerJoinMenuPossibleSize($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuPossibleSize relation
 *
 * @method     ChildMenuQuery joinWithMenuPossibleSize($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuPossibleSize relation
 *
 * @method     ChildMenuQuery leftJoinWithMenuPossibleSize() Adds a LEFT JOIN clause and with to the query using the MenuPossibleSize relation
 * @method     ChildMenuQuery rightJoinWithMenuPossibleSize() Adds a RIGHT JOIN clause and with to the query using the MenuPossibleSize relation
 * @method     ChildMenuQuery innerJoinWithMenuPossibleSize() Adds a INNER JOIN clause and with to the query using the MenuPossibleSize relation
 *
 * @method     ChildMenuQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildMenuQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildMenuQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildMenuQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildMenuQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildMenuQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildMenuQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     ChildMenuQuery leftJoinOrderDetailMixedWith($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetailMixedWith relation
 * @method     ChildMenuQuery rightJoinOrderDetailMixedWith($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetailMixedWith relation
 * @method     ChildMenuQuery innerJoinOrderDetailMixedWith($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetailMixedWith relation
 *
 * @method     ChildMenuQuery joinWithOrderDetailMixedWith($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetailMixedWith relation
 *
 * @method     ChildMenuQuery leftJoinWithOrderDetailMixedWith() Adds a LEFT JOIN clause and with to the query using the OrderDetailMixedWith relation
 * @method     ChildMenuQuery rightJoinWithOrderDetailMixedWith() Adds a RIGHT JOIN clause and with to the query using the OrderDetailMixedWith relation
 * @method     ChildMenuQuery innerJoinWithOrderDetailMixedWith() Adds a INNER JOIN clause and with to the query using the OrderDetailMixedWith relation
 *
 * @method     \API\Models\Menu\AvailabilityQuery|\API\Models\Menu\MenuGroupQuery|\API\Models\Menu\MenuPossibleExtraQuery|\API\Models\Menu\MenuPossibleSizeQuery|\API\Models\Ordering\OrderDetailQuery|\API\Models\Ordering\OrderDetailMixedWithQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenu findOne(ConnectionInterface $con = null) Return the first ChildMenu matching the query
 * @method     ChildMenu findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenu matching the query, or a new ChildMenu object populated from the query conditions when no match is found
 *
 * @method     ChildMenu findOneByMenuid(int $menuid) Return the first ChildMenu filtered by the menuid column
 * @method     ChildMenu findOneByMenuGroupid(int $menu_groupid) Return the first ChildMenu filtered by the menu_groupid column
 * @method     ChildMenu findOneByName(string $name) Return the first ChildMenu filtered by the name column
 * @method     ChildMenu findOneByPrice(string $price) Return the first ChildMenu filtered by the price column
 * @method     ChildMenu findOneByAvailabilityid(int $availabilityid) Return the first ChildMenu filtered by the availabilityid column
 * @method     ChildMenu findOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenu filtered by the availability_amount column *

 * @method     ChildMenu requirePk($key, ConnectionInterface $con = null) Return the ChildMenu by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenu requireOne(ConnectionInterface $con = null) Return the first ChildMenu matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenu requireOneByMenuid(int $menuid) Return the first ChildMenu filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenu requireOneByMenuGroupid(int $menu_groupid) Return the first ChildMenu filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenu requireOneByName(string $name) Return the first ChildMenu filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenu requireOneByPrice(string $price) Return the first ChildMenu filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenu requireOneByAvailabilityid(int $availabilityid) Return the first ChildMenu filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenu requireOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenu filtered by the availability_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenu[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenu objects based on current ModelCriteria
 * @method     ChildMenu[]|ObjectCollection findByMenuid(int $menuid) Return ChildMenu objects filtered by the menuid column
 * @method     ChildMenu[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildMenu objects filtered by the menu_groupid column
 * @method     ChildMenu[]|ObjectCollection findByName(string $name) Return ChildMenu objects filtered by the name column
 * @method     ChildMenu[]|ObjectCollection findByPrice(string $price) Return ChildMenu objects filtered by the price column
 * @method     ChildMenu[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildMenu objects filtered by the availabilityid column
 * @method     ChildMenu[]|ObjectCollection findByAvailabilityAmount(int $availability_amount) Return ChildMenu objects filtered by the availability_amount column
 * @method     ChildMenu[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Menu\Base\MenuQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Menu\\Menu', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuQuery) {
            return $criteria;
        }
        $query = new ChildMenuQuery();
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
     * @return ChildMenu|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMenu A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menuid, menu_groupid, name, price, availabilityid, availability_amount FROM menu WHERE menuid = :p0';
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
            /** @var ChildMenu $obj */
            $obj = new ChildMenu();
            $obj->hydrate($row);
            MenuTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMenu|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MenuTableMap::COL_MENUID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MenuTableMap::COL_MENUID, $keys, Criteria::IN);
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
     * @param     mixed $menuid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(MenuTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(MenuTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTableMap::COL_MENUID, $menuid, $comparison);
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
     * @see       filterByMenuGroup()
     *
     * @param     mixed $menuGroupid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(MenuTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(MenuTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(MenuTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(MenuTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTableMap::COL_PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the availabilityid column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailabilityid(1234); // WHERE availabilityid = 1234
     * $query->filterByAvailabilityid(array(12, 34)); // WHERE availabilityid IN (12, 34)
     * $query->filterByAvailabilityid(array('min' => 12)); // WHERE availabilityid > 12
     * </code>
     *
     * @see       filterByAvailability()
     *
     * @param     mixed $availabilityid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(MenuTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(MenuTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
    }

    /**
     * Filter the query on the availability_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailabilityAmount(1234); // WHERE availability_amount = 1234
     * $query->filterByAvailabilityAmount(array(12, 34)); // WHERE availability_amount IN (12, 34)
     * $query->filterByAvailabilityAmount(array('min' => 12)); // WHERE availability_amount > 12
     * </code>
     *
     * @param     mixed $availabilityAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function filterByAvailabilityAmount($availabilityAmount = null, $comparison = null)
    {
        if (is_array($availabilityAmount)) {
            $useMinMax = false;
            if (isset($availabilityAmount['min'])) {
                $this->addUsingAlias(MenuTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityAmount['max'])) {
                $this->addUsingAlias(MenuTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Menu\Availability object
     *
     * @param \API\Models\Menu\Availability|ObjectCollection $availability The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuQuery The current query, for fluid interface
     */
    public function filterByAvailability($availability, $comparison = null)
    {
        if ($availability instanceof \API\Models\Menu\Availability) {
            return $this
                ->addUsingAlias(MenuTableMap::COL_AVAILABILITYID, $availability->getAvailabilityid(), $comparison);
        } elseif ($availability instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuTableMap::COL_AVAILABILITYID, $availability->toKeyValue('PrimaryKey', 'Availabilityid'), $comparison);
        } else {
            throw new PropelException('filterByAvailability() only accepts arguments of type \API\Models\Menu\Availability or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availability relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function joinAvailability($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Availability');

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
            $this->addJoinObject($join, 'Availability');
        }

        return $this;
    }

    /**
     * Use the Availability relation Availability object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\AvailabilityQuery A secondary query class using the current class as primary query
     */
    public function useAvailabilityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAvailability($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Availability', '\API\Models\Menu\AvailabilityQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuGroup object
     *
     * @param \API\Models\Menu\MenuGroup|ObjectCollection $menuGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuQuery The current query, for fluid interface
     */
    public function filterByMenuGroup($menuGroup, $comparison = null)
    {
        if ($menuGroup instanceof \API\Models\Menu\MenuGroup) {
            return $this
                ->addUsingAlias(MenuTableMap::COL_MENU_GROUPID, $menuGroup->getMenuGroupid(), $comparison);
        } elseif ($menuGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuTableMap::COL_MENU_GROUPID, $menuGroup->toKeyValue('PrimaryKey', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroup() only accepts arguments of type \API\Models\Menu\MenuGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function joinMenuGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuGroup');

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
            $this->addJoinObject($join, 'MenuGroup');
        }

        return $this;
    }

    /**
     * Use the MenuGroup relation MenuGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuGroupQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroup', '\API\Models\Menu\MenuGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuPossibleExtra object
     *
     * @param \API\Models\Menu\MenuPossibleExtra|ObjectCollection $menuPossibleExtra the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleExtra($menuPossibleExtra, $comparison = null)
    {
        if ($menuPossibleExtra instanceof \API\Models\Menu\MenuPossibleExtra) {
            return $this
                ->addUsingAlias(MenuTableMap::COL_MENUID, $menuPossibleExtra->getMenuid(), $comparison);
        } elseif ($menuPossibleExtra instanceof ObjectCollection) {
            return $this
                ->useMenuPossibleExtraQuery()
                ->filterByPrimaryKeys($menuPossibleExtra->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuPossibleExtra() only accepts arguments of type \API\Models\Menu\MenuPossibleExtra or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuPossibleExtra relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function joinMenuPossibleExtra($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuPossibleExtra');

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
            $this->addJoinObject($join, 'MenuPossibleExtra');
        }

        return $this;
    }

    /**
     * Use the MenuPossibleExtra relation MenuPossibleExtra object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuPossibleExtraQuery A secondary query class using the current class as primary query
     */
    public function useMenuPossibleExtraQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuPossibleExtra($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuPossibleExtra', '\API\Models\Menu\MenuPossibleExtraQuery');
    }

    /**
     * Filter the query by a related \API\Models\Menu\MenuPossibleSize object
     *
     * @param \API\Models\Menu\MenuPossibleSize|ObjectCollection $menuPossibleSize the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleSize($menuPossibleSize, $comparison = null)
    {
        if ($menuPossibleSize instanceof \API\Models\Menu\MenuPossibleSize) {
            return $this
                ->addUsingAlias(MenuTableMap::COL_MENUID, $menuPossibleSize->getMenuid(), $comparison);
        } elseif ($menuPossibleSize instanceof ObjectCollection) {
            return $this
                ->useMenuPossibleSizeQuery()
                ->filterByPrimaryKeys($menuPossibleSize->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuPossibleSize() only accepts arguments of type \API\Models\Menu\MenuPossibleSize or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuPossibleSize relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function joinMenuPossibleSize($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuPossibleSize');

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
            $this->addJoinObject($join, 'MenuPossibleSize');
        }

        return $this;
    }

    /**
     * Use the MenuPossibleSize relation MenuPossibleSize object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Menu\MenuPossibleSizeQuery A secondary query class using the current class as primary query
     */
    public function useMenuPossibleSizeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuPossibleSize($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuPossibleSize', '\API\Models\Menu\MenuPossibleSizeQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrderDetail object
     *
     * @param \API\Models\Ordering\OrderDetail|ObjectCollection $orderDetail the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(MenuTableMap::COL_MENUID, $orderDetail->getMenuid(), $comparison);
        } elseif ($orderDetail instanceof ObjectCollection) {
            return $this
                ->useOrderDetailQuery()
                ->filterByPrimaryKeys($orderDetail->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function joinOrderDetail($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useOrderDetailQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrderDetail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetail', '\API\Models\Ordering\OrderDetailQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrderDetailMixedWith object
     *
     * @param \API\Models\Ordering\OrderDetailMixedWith|ObjectCollection $orderDetailMixedWith the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuQuery The current query, for fluid interface
     */
    public function filterByOrderDetailMixedWith($orderDetailMixedWith, $comparison = null)
    {
        if ($orderDetailMixedWith instanceof \API\Models\Ordering\OrderDetailMixedWith) {
            return $this
                ->addUsingAlias(MenuTableMap::COL_MENUID, $orderDetailMixedWith->getMenuid(), $comparison);
        } elseif ($orderDetailMixedWith instanceof ObjectCollection) {
            return $this
                ->useOrderDetailMixedWithQuery()
                ->filterByPrimaryKeys($orderDetailMixedWith->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderDetailMixedWith() only accepts arguments of type \API\Models\Ordering\OrderDetailMixedWith or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetailMixedWith relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function joinOrderDetailMixedWith($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderDetailMixedWith');

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
            $this->addJoinObject($join, 'OrderDetailMixedWith');
        }

        return $this;
    }

    /**
     * Use the OrderDetailMixedWith relation OrderDetailMixedWith object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrderDetailMixedWithQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailMixedWithQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderDetailMixedWith($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetailMixedWith', '\API\Models\Ordering\OrderDetailMixedWithQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenu $menu Object to remove from the list of results
     *
     * @return $this|ChildMenuQuery The current query, for fluid interface
     */
    public function prune($menu = null)
    {
        if ($menu) {
            $this->addUsingAlias(MenuTableMap::COL_MENUID, $menu->getMenuid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menu table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuTableMap::clearInstancePool();
            MenuTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuQuery
