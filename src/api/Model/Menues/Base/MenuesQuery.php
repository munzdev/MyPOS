<?php

namespace Model\Menues\Base;

use \Exception;
use \PDO;
use Model\Menues\Menues as ChildMenues;
use Model\Menues\MenuesQuery as ChildMenuesQuery;
use Model\Menues\Map\MenuesTableMap;
use Model\Ordering\OrdersDetails;
use Model\Ordering\OrdersDetailsMixedWith;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'menues' table.
 *
 *
 *
 * @method     ChildMenuesQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildMenuesQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildMenuesQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMenuesQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildMenuesQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildMenuesQuery orderByAvailabilityAmount($order = Criteria::ASC) Order by the availability_amount column
 *
 * @method     ChildMenuesQuery groupByMenuid() Group by the menuid column
 * @method     ChildMenuesQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildMenuesQuery groupByName() Group by the name column
 * @method     ChildMenuesQuery groupByPrice() Group by the price column
 * @method     ChildMenuesQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildMenuesQuery groupByAvailabilityAmount() Group by the availability_amount column
 *
 * @method     ChildMenuesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMenuesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMenuesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMenuesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMenuesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMenuesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMenuesQuery leftJoinAvailabilitys($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availabilitys relation
 * @method     ChildMenuesQuery rightJoinAvailabilitys($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availabilitys relation
 * @method     ChildMenuesQuery innerJoinAvailabilitys($relationAlias = null) Adds a INNER JOIN clause to the query using the Availabilitys relation
 *
 * @method     ChildMenuesQuery joinWithAvailabilitys($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availabilitys relation
 *
 * @method     ChildMenuesQuery leftJoinWithAvailabilitys() Adds a LEFT JOIN clause and with to the query using the Availabilitys relation
 * @method     ChildMenuesQuery rightJoinWithAvailabilitys() Adds a RIGHT JOIN clause and with to the query using the Availabilitys relation
 * @method     ChildMenuesQuery innerJoinWithAvailabilitys() Adds a INNER JOIN clause and with to the query using the Availabilitys relation
 *
 * @method     ChildMenuesQuery leftJoinMenuGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildMenuesQuery rightJoinMenuGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildMenuesQuery innerJoinMenuGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroupes relation
 *
 * @method     ChildMenuesQuery joinWithMenuGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildMenuesQuery leftJoinWithMenuGroupes() Adds a LEFT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildMenuesQuery rightJoinWithMenuGroupes() Adds a RIGHT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildMenuesQuery innerJoinWithMenuGroupes() Adds a INNER JOIN clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildMenuesQuery leftJoinMenuesPossibleExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuesQuery rightJoinMenuesPossibleExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuesQuery innerJoinMenuesPossibleExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuesPossibleExtras relation
 *
 * @method     ChildMenuesQuery joinWithMenuesPossibleExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuesPossibleExtras relation
 *
 * @method     ChildMenuesQuery leftJoinWithMenuesPossibleExtras() Adds a LEFT JOIN clause and with to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuesQuery rightJoinWithMenuesPossibleExtras() Adds a RIGHT JOIN clause and with to the query using the MenuesPossibleExtras relation
 * @method     ChildMenuesQuery innerJoinWithMenuesPossibleExtras() Adds a INNER JOIN clause and with to the query using the MenuesPossibleExtras relation
 *
 * @method     ChildMenuesQuery leftJoinMenuesPossibleSizes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuesQuery rightJoinMenuesPossibleSizes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuesQuery innerJoinMenuesPossibleSizes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuesPossibleSizes relation
 *
 * @method     ChildMenuesQuery joinWithMenuesPossibleSizes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuesPossibleSizes relation
 *
 * @method     ChildMenuesQuery leftJoinWithMenuesPossibleSizes() Adds a LEFT JOIN clause and with to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuesQuery rightJoinWithMenuesPossibleSizes() Adds a RIGHT JOIN clause and with to the query using the MenuesPossibleSizes relation
 * @method     ChildMenuesQuery innerJoinWithMenuesPossibleSizes() Adds a INNER JOIN clause and with to the query using the MenuesPossibleSizes relation
 *
 * @method     ChildMenuesQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildMenuesQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildMenuesQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildMenuesQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildMenuesQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildMenuesQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildMenuesQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildMenuesQuery leftJoinOrdersDetailsMixedWith($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetailsMixedWith relation
 * @method     ChildMenuesQuery rightJoinOrdersDetailsMixedWith($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetailsMixedWith relation
 * @method     ChildMenuesQuery innerJoinOrdersDetailsMixedWith($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetailsMixedWith relation
 *
 * @method     ChildMenuesQuery joinWithOrdersDetailsMixedWith($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetailsMixedWith relation
 *
 * @method     ChildMenuesQuery leftJoinWithOrdersDetailsMixedWith() Adds a LEFT JOIN clause and with to the query using the OrdersDetailsMixedWith relation
 * @method     ChildMenuesQuery rightJoinWithOrdersDetailsMixedWith() Adds a RIGHT JOIN clause and with to the query using the OrdersDetailsMixedWith relation
 * @method     ChildMenuesQuery innerJoinWithOrdersDetailsMixedWith() Adds a INNER JOIN clause and with to the query using the OrdersDetailsMixedWith relation
 *
 * @method     \Model\Menues\AvailabilitysQuery|\Model\Menues\MenuGroupesQuery|\Model\Menues\MenuesPossibleExtrasQuery|\Model\Menues\MenuesPossibleSizesQuery|\Model\Ordering\OrdersDetailsQuery|\Model\Ordering\OrdersDetailsMixedWithQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMenues findOne(ConnectionInterface $con = null) Return the first ChildMenues matching the query
 * @method     ChildMenues findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMenues matching the query, or a new ChildMenues object populated from the query conditions when no match is found
 *
 * @method     ChildMenues findOneByMenuid(int $menuid) Return the first ChildMenues filtered by the menuid column
 * @method     ChildMenues findOneByMenuGroupid(int $menu_groupid) Return the first ChildMenues filtered by the menu_groupid column
 * @method     ChildMenues findOneByName(string $name) Return the first ChildMenues filtered by the name column
 * @method     ChildMenues findOneByPrice(string $price) Return the first ChildMenues filtered by the price column
 * @method     ChildMenues findOneByAvailabilityid(int $availabilityid) Return the first ChildMenues filtered by the availabilityid column
 * @method     ChildMenues findOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenues filtered by the availability_amount column *

 * @method     ChildMenues requirePk($key, ConnectionInterface $con = null) Return the ChildMenues by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenues requireOne(ConnectionInterface $con = null) Return the first ChildMenues matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenues requireOneByMenuid(int $menuid) Return the first ChildMenues filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenues requireOneByMenuGroupid(int $menu_groupid) Return the first ChildMenues filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenues requireOneByName(string $name) Return the first ChildMenues filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenues requireOneByPrice(string $price) Return the first ChildMenues filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenues requireOneByAvailabilityid(int $availabilityid) Return the first ChildMenues filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMenues requireOneByAvailabilityAmount(int $availability_amount) Return the first ChildMenues filtered by the availability_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMenues[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMenues objects based on current ModelCriteria
 * @method     ChildMenues[]|ObjectCollection findByMenuid(int $menuid) Return ChildMenues objects filtered by the menuid column
 * @method     ChildMenues[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildMenues objects filtered by the menu_groupid column
 * @method     ChildMenues[]|ObjectCollection findByName(string $name) Return ChildMenues objects filtered by the name column
 * @method     ChildMenues[]|ObjectCollection findByPrice(string $price) Return ChildMenues objects filtered by the price column
 * @method     ChildMenues[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildMenues objects filtered by the availabilityid column
 * @method     ChildMenues[]|ObjectCollection findByAvailabilityAmount(int $availability_amount) Return ChildMenues objects filtered by the availability_amount column
 * @method     ChildMenues[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MenuesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Menues\Base\MenuesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Menues\\Menues', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMenuesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMenuesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMenuesQuery) {
            return $criteria;
        }
        $query = new ChildMenuesQuery();
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
     * @param array[$menuid, $menu_groupid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMenues|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MenuesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MenuesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMenues A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT menuid, menu_groupid, name, price, availabilityid, availability_amount FROM menues WHERE menuid = :p0 AND menu_groupid = :p1';
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
            /** @var ChildMenues $obj */
            $obj = new ChildMenues();
            $obj->hydrate($row);
            MenuesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMenues|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MenuesTableMap::COL_MENUID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MenuesTableMap::COL_MENU_GROUPID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MenuesTableMap::COL_MENUID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MenuesTableMap::COL_MENU_GROUPID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(MenuesTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(MenuesTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesTableMap::COL_MENUID, $menuid, $comparison);
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(MenuesTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(MenuesTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(MenuesTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(MenuesTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesTableMap::COL_PRICE, $price, $comparison);
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
     * @see       filterByAvailabilitys()
     *
     * @param     mixed $availabilityid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(MenuesTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(MenuesTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByAvailabilityAmount($availabilityAmount = null, $comparison = null)
    {
        if (is_array($availabilityAmount)) {
            $useMinMax = false;
            if (isset($availabilityAmount['min'])) {
                $this->addUsingAlias(MenuesTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityAmount['max'])) {
                $this->addUsingAlias(MenuesTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MenuesTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount, $comparison);
    }

    /**
     * Filter the query by a related \Model\Menues\Availabilitys object
     *
     * @param \Model\Menues\Availabilitys|ObjectCollection $availabilitys The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByAvailabilitys($availabilitys, $comparison = null)
    {
        if ($availabilitys instanceof \Model\Menues\Availabilitys) {
            return $this
                ->addUsingAlias(MenuesTableMap::COL_AVAILABILITYID, $availabilitys->getAvailabilityid(), $comparison);
        } elseif ($availabilitys instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuesTableMap::COL_AVAILABILITYID, $availabilitys->toKeyValue('PrimaryKey', 'Availabilityid'), $comparison);
        } else {
            throw new PropelException('filterByAvailabilitys() only accepts arguments of type \Model\Menues\Availabilitys or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availabilitys relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function joinAvailabilitys($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Availabilitys');

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
            $this->addJoinObject($join, 'Availabilitys');
        }

        return $this;
    }

    /**
     * Use the Availabilitys relation Availabilitys object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\AvailabilitysQuery A secondary query class using the current class as primary query
     */
    public function useAvailabilitysQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAvailabilitys($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Availabilitys', '\Model\Menues\AvailabilitysQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuGroupes object
     *
     * @param \Model\Menues\MenuGroupes|ObjectCollection $menuGroupes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByMenuGroupes($menuGroupes, $comparison = null)
    {
        if ($menuGroupes instanceof \Model\Menues\MenuGroupes) {
            return $this
                ->addUsingAlias(MenuesTableMap::COL_MENU_GROUPID, $menuGroupes->getMenuGroupid(), $comparison);
        } elseif ($menuGroupes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MenuesTableMap::COL_MENU_GROUPID, $menuGroupes->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
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
     * @return $this|ChildMenuesQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Menues\MenuesPossibleExtras object
     *
     * @param \Model\Menues\MenuesPossibleExtras|ObjectCollection $menuesPossibleExtras the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByMenuesPossibleExtras($menuesPossibleExtras, $comparison = null)
    {
        if ($menuesPossibleExtras instanceof \Model\Menues\MenuesPossibleExtras) {
            return $this
                ->addUsingAlias(MenuesTableMap::COL_MENUID, $menuesPossibleExtras->getMenuid(), $comparison);
        } elseif ($menuesPossibleExtras instanceof ObjectCollection) {
            return $this
                ->useMenuesPossibleExtrasQuery()
                ->filterByPrimaryKeys($menuesPossibleExtras->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuesPossibleExtras() only accepts arguments of type \Model\Menues\MenuesPossibleExtras or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuesPossibleExtras relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function joinMenuesPossibleExtras($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuesPossibleExtras');

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
            $this->addJoinObject($join, 'MenuesPossibleExtras');
        }

        return $this;
    }

    /**
     * Use the MenuesPossibleExtras relation MenuesPossibleExtras object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuesPossibleExtrasQuery A secondary query class using the current class as primary query
     */
    public function useMenuesPossibleExtrasQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuesPossibleExtras($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuesPossibleExtras', '\Model\Menues\MenuesPossibleExtrasQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuesPossibleSizes object
     *
     * @param \Model\Menues\MenuesPossibleSizes|ObjectCollection $menuesPossibleSizes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByMenuesPossibleSizes($menuesPossibleSizes, $comparison = null)
    {
        if ($menuesPossibleSizes instanceof \Model\Menues\MenuesPossibleSizes) {
            return $this
                ->addUsingAlias(MenuesTableMap::COL_MENUID, $menuesPossibleSizes->getMenuid(), $comparison);
        } elseif ($menuesPossibleSizes instanceof ObjectCollection) {
            return $this
                ->useMenuesPossibleSizesQuery()
                ->filterByPrimaryKeys($menuesPossibleSizes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMenuesPossibleSizes() only accepts arguments of type \Model\Menues\MenuesPossibleSizes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuesPossibleSizes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function joinMenuesPossibleSizes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MenuesPossibleSizes');

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
            $this->addJoinObject($join, 'MenuesPossibleSizes');
        }

        return $this;
    }

    /**
     * Use the MenuesPossibleSizes relation MenuesPossibleSizes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Menues\MenuesPossibleSizesQuery A secondary query class using the current class as primary query
     */
    public function useMenuesPossibleSizesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMenuesPossibleSizes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuesPossibleSizes', '\Model\Menues\MenuesPossibleSizesQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetails object
     *
     * @param \Model\Ordering\OrdersDetails|ObjectCollection $ordersDetails the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \Model\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(MenuesTableMap::COL_MENUID, $ordersDetails->getMenuid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            return $this
                ->useOrdersDetailsQuery()
                ->filterByPrimaryKeys($ordersDetails->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersDetails() only accepts arguments of type \Model\Ordering\OrdersDetails or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersDetails relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function joinOrdersDetails($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return \Model\Ordering\OrdersDetailsQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\Model\Ordering\OrdersDetailsQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetailsMixedWith object
     *
     * @param \Model\Ordering\OrdersDetailsMixedWith|ObjectCollection $ordersDetailsMixedWith the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMenuesQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailsMixedWith($ordersDetailsMixedWith, $comparison = null)
    {
        if ($ordersDetailsMixedWith instanceof \Model\Ordering\OrdersDetailsMixedWith) {
            return $this
                ->addUsingAlias(MenuesTableMap::COL_MENUID, $ordersDetailsMixedWith->getMenuid(), $comparison);
        } elseif ($ordersDetailsMixedWith instanceof ObjectCollection) {
            return $this
                ->useOrdersDetailsMixedWithQuery()
                ->filterByPrimaryKeys($ordersDetailsMixedWith->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersDetailsMixedWith() only accepts arguments of type \Model\Ordering\OrdersDetailsMixedWith or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersDetailsMixedWith relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function joinOrdersDetailsMixedWith($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersDetailsMixedWith');

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
            $this->addJoinObject($join, 'OrdersDetailsMixedWith');
        }

        return $this;
    }

    /**
     * Use the OrdersDetailsMixedWith relation OrdersDetailsMixedWith object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Ordering\OrdersDetailsMixedWithQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailsMixedWithQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersDetailsMixedWith($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetailsMixedWith', '\Model\Ordering\OrdersDetailsMixedWithQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMenues $menues Object to remove from the list of results
     *
     * @return $this|ChildMenuesQuery The current query, for fluid interface
     */
    public function prune($menues = null)
    {
        if ($menues) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MenuesTableMap::COL_MENUID), $menues->getMenuid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MenuesTableMap::COL_MENU_GROUPID), $menues->getMenuGroupid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the menues table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MenuesTableMap::clearInstancePool();
            MenuesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MenuesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MenuesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MenuesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MenuesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MenuesQuery
