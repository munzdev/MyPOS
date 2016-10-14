<?php

namespace API\Models\User\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionsPlacesUsers;
use API\Models\Event\EventsUser;
use API\Models\Invoice\Invoices;
use API\Models\OIP\OrdersInProgress;
use API\Models\Ordering\Orders;
use API\Models\Ordering\OrdersDetails;
use API\Models\Payment\Coupons;
use API\Models\User\Users as ChildUsers;
use API\Models\User\UsersQuery as ChildUsersQuery;
use API\Models\User\Map\UsersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'users' table.
 *
 *
 *
 * @method     ChildUsersQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildUsersQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUsersQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUsersQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method     ChildUsersQuery orderByLastname($order = Criteria::ASC) Order by the lastname column
 * @method     ChildUsersQuery orderByAutologinHash($order = Criteria::ASC) Order by the autologin_hash column
 * @method     ChildUsersQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildUsersQuery orderByPhonenumber($order = Criteria::ASC) Order by the phonenumber column
 * @method     ChildUsersQuery orderByCallRequest($order = Criteria::ASC) Order by the call_request column
 * @method     ChildUsersQuery orderByIsAdmin($order = Criteria::ASC) Order by the is_admin column
 *
 * @method     ChildUsersQuery groupByUserid() Group by the userid column
 * @method     ChildUsersQuery groupByUsername() Group by the username column
 * @method     ChildUsersQuery groupByPassword() Group by the password column
 * @method     ChildUsersQuery groupByFirstname() Group by the firstname column
 * @method     ChildUsersQuery groupByLastname() Group by the lastname column
 * @method     ChildUsersQuery groupByAutologinHash() Group by the autologin_hash column
 * @method     ChildUsersQuery groupByActive() Group by the active column
 * @method     ChildUsersQuery groupByPhonenumber() Group by the phonenumber column
 * @method     ChildUsersQuery groupByCallRequest() Group by the call_request column
 * @method     ChildUsersQuery groupByIsAdmin() Group by the is_admin column
 *
 * @method     ChildUsersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUsersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUsersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUsersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUsersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUsersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUsersQuery leftJoinCoupons($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupons relation
 * @method     ChildUsersQuery rightJoinCoupons($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupons relation
 * @method     ChildUsersQuery innerJoinCoupons($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupons relation
 *
 * @method     ChildUsersQuery joinWithCoupons($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupons relation
 *
 * @method     ChildUsersQuery leftJoinWithCoupons() Adds a LEFT JOIN clause and with to the query using the Coupons relation
 * @method     ChildUsersQuery rightJoinWithCoupons() Adds a RIGHT JOIN clause and with to the query using the Coupons relation
 * @method     ChildUsersQuery innerJoinWithCoupons() Adds a INNER JOIN clause and with to the query using the Coupons relation
 *
 * @method     ChildUsersQuery leftJoinDistributionsPlacesUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionsPlacesUsers relation
 * @method     ChildUsersQuery rightJoinDistributionsPlacesUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionsPlacesUsers relation
 * @method     ChildUsersQuery innerJoinDistributionsPlacesUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildUsersQuery joinWithDistributionsPlacesUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildUsersQuery leftJoinWithDistributionsPlacesUsers() Adds a LEFT JOIN clause and with to the query using the DistributionsPlacesUsers relation
 * @method     ChildUsersQuery rightJoinWithDistributionsPlacesUsers() Adds a RIGHT JOIN clause and with to the query using the DistributionsPlacesUsers relation
 * @method     ChildUsersQuery innerJoinWithDistributionsPlacesUsers() Adds a INNER JOIN clause and with to the query using the DistributionsPlacesUsers relation
 *
 * @method     ChildUsersQuery leftJoinEventsUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventsUser relation
 * @method     ChildUsersQuery rightJoinEventsUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventsUser relation
 * @method     ChildUsersQuery innerJoinEventsUser($relationAlias = null) Adds a INNER JOIN clause to the query using the EventsUser relation
 *
 * @method     ChildUsersQuery joinWithEventsUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventsUser relation
 *
 * @method     ChildUsersQuery leftJoinWithEventsUser() Adds a LEFT JOIN clause and with to the query using the EventsUser relation
 * @method     ChildUsersQuery rightJoinWithEventsUser() Adds a RIGHT JOIN clause and with to the query using the EventsUser relation
 * @method     ChildUsersQuery innerJoinWithEventsUser() Adds a INNER JOIN clause and with to the query using the EventsUser relation
 *
 * @method     ChildUsersQuery leftJoinInvoices($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoices relation
 * @method     ChildUsersQuery rightJoinInvoices($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoices relation
 * @method     ChildUsersQuery innerJoinInvoices($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoices relation
 *
 * @method     ChildUsersQuery joinWithInvoices($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoices relation
 *
 * @method     ChildUsersQuery leftJoinWithInvoices() Adds a LEFT JOIN clause and with to the query using the Invoices relation
 * @method     ChildUsersQuery rightJoinWithInvoices() Adds a RIGHT JOIN clause and with to the query using the Invoices relation
 * @method     ChildUsersQuery innerJoinWithInvoices() Adds a INNER JOIN clause and with to the query using the Invoices relation
 *
 * @method     ChildUsersQuery leftJoinOrders($relationAlias = null) Adds a LEFT JOIN clause to the query using the Orders relation
 * @method     ChildUsersQuery rightJoinOrders($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Orders relation
 * @method     ChildUsersQuery innerJoinOrders($relationAlias = null) Adds a INNER JOIN clause to the query using the Orders relation
 *
 * @method     ChildUsersQuery joinWithOrders($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Orders relation
 *
 * @method     ChildUsersQuery leftJoinWithOrders() Adds a LEFT JOIN clause and with to the query using the Orders relation
 * @method     ChildUsersQuery rightJoinWithOrders() Adds a RIGHT JOIN clause and with to the query using the Orders relation
 * @method     ChildUsersQuery innerJoinWithOrders() Adds a INNER JOIN clause and with to the query using the Orders relation
 *
 * @method     ChildUsersQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildUsersQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildUsersQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildUsersQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildUsersQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildUsersQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildUsersQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildUsersQuery leftJoinOrdersInProgress($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersInProgress relation
 * @method     ChildUsersQuery rightJoinOrdersInProgress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersInProgress relation
 * @method     ChildUsersQuery innerJoinOrdersInProgress($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersInProgress relation
 *
 * @method     ChildUsersQuery joinWithOrdersInProgress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersInProgress relation
 *
 * @method     ChildUsersQuery leftJoinWithOrdersInProgress() Adds a LEFT JOIN clause and with to the query using the OrdersInProgress relation
 * @method     ChildUsersQuery rightJoinWithOrdersInProgress() Adds a RIGHT JOIN clause and with to the query using the OrdersInProgress relation
 * @method     ChildUsersQuery innerJoinWithOrdersInProgress() Adds a INNER JOIN clause and with to the query using the OrdersInProgress relation
 *
 * @method     \API\Models\Payment\CouponsQuery|\API\Models\DistributionPlace\DistributionsPlacesUsersQuery|\API\Models\Event\EventsUserQuery|\API\Models\Invoice\InvoicesQuery|\API\Models\Ordering\OrdersQuery|\API\Models\Ordering\OrdersDetailsQuery|\API\Models\OIP\OrdersInProgressQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUsers findOne(ConnectionInterface $con = null) Return the first ChildUsers matching the query
 * @method     ChildUsers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUsers matching the query, or a new ChildUsers object populated from the query conditions when no match is found
 *
 * @method     ChildUsers findOneByUserid(int $userid) Return the first ChildUsers filtered by the userid column
 * @method     ChildUsers findOneByUsername(string $username) Return the first ChildUsers filtered by the username column
 * @method     ChildUsers findOneByPassword(string $password) Return the first ChildUsers filtered by the password column
 * @method     ChildUsers findOneByFirstname(string $firstname) Return the first ChildUsers filtered by the firstname column
 * @method     ChildUsers findOneByLastname(string $lastname) Return the first ChildUsers filtered by the lastname column
 * @method     ChildUsers findOneByAutologinHash(string $autologin_hash) Return the first ChildUsers filtered by the autologin_hash column
 * @method     ChildUsers findOneByActive(int $active) Return the first ChildUsers filtered by the active column
 * @method     ChildUsers findOneByPhonenumber(string $phonenumber) Return the first ChildUsers filtered by the phonenumber column
 * @method     ChildUsers findOneByCallRequest(string $call_request) Return the first ChildUsers filtered by the call_request column
 * @method     ChildUsers findOneByIsAdmin(boolean $is_admin) Return the first ChildUsers filtered by the is_admin column *

 * @method     ChildUsers requirePk($key, ConnectionInterface $con = null) Return the ChildUsers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOne(ConnectionInterface $con = null) Return the first ChildUsers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsers requireOneByUserid(int $userid) Return the first ChildUsers filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByUsername(string $username) Return the first ChildUsers filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByPassword(string $password) Return the first ChildUsers filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByFirstname(string $firstname) Return the first ChildUsers filtered by the firstname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByLastname(string $lastname) Return the first ChildUsers filtered by the lastname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByAutologinHash(string $autologin_hash) Return the first ChildUsers filtered by the autologin_hash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByActive(int $active) Return the first ChildUsers filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByPhonenumber(string $phonenumber) Return the first ChildUsers filtered by the phonenumber column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByCallRequest(string $call_request) Return the first ChildUsers filtered by the call_request column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByIsAdmin(boolean $is_admin) Return the first ChildUsers filtered by the is_admin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUsers objects based on current ModelCriteria
 * @method     ChildUsers[]|ObjectCollection findByUserid(int $userid) Return ChildUsers objects filtered by the userid column
 * @method     ChildUsers[]|ObjectCollection findByUsername(string $username) Return ChildUsers objects filtered by the username column
 * @method     ChildUsers[]|ObjectCollection findByPassword(string $password) Return ChildUsers objects filtered by the password column
 * @method     ChildUsers[]|ObjectCollection findByFirstname(string $firstname) Return ChildUsers objects filtered by the firstname column
 * @method     ChildUsers[]|ObjectCollection findByLastname(string $lastname) Return ChildUsers objects filtered by the lastname column
 * @method     ChildUsers[]|ObjectCollection findByAutologinHash(string $autologin_hash) Return ChildUsers objects filtered by the autologin_hash column
 * @method     ChildUsers[]|ObjectCollection findByActive(int $active) Return ChildUsers objects filtered by the active column
 * @method     ChildUsers[]|ObjectCollection findByPhonenumber(string $phonenumber) Return ChildUsers objects filtered by the phonenumber column
 * @method     ChildUsers[]|ObjectCollection findByCallRequest(string $call_request) Return ChildUsers objects filtered by the call_request column
 * @method     ChildUsers[]|ObjectCollection findByIsAdmin(boolean $is_admin) Return ChildUsers objects filtered by the is_admin column
 * @method     ChildUsers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UsersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\User\Base\UsersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\User\\Users', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUsersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUsersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUsersQuery) {
            return $criteria;
        }
        $query = new ChildUsersQuery();
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
     * @return ChildUsers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UsersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UsersTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUsers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT userid, username, password, firstname, lastname, autologin_hash, active, phonenumber, call_request, is_admin FROM users WHERE userid = :p0';
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
            /** @var ChildUsers $obj */
            $obj = new ChildUsers();
            $obj->hydrate($row);
            UsersTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUsers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UsersTableMap::COL_USERID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UsersTableMap::COL_USERID, $keys, Criteria::IN);
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
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(UsersTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(UsersTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%', Criteria::LIKE); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE lastname = 'fooValue'
     * $query->filterByLastname('%fooValue%', Criteria::LIKE); // WHERE lastname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_LASTNAME, $lastname, $comparison);
    }

    /**
     * Filter the query on the autologin_hash column
     *
     * Example usage:
     * <code>
     * $query->filterByAutologinHash('fooValue');   // WHERE autologin_hash = 'fooValue'
     * $query->filterByAutologinHash('%fooValue%', Criteria::LIKE); // WHERE autologin_hash LIKE '%fooValue%'
     * </code>
     *
     * @param     string $autologinHash The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByAutologinHash($autologinHash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($autologinHash)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_AUTOLOGIN_HASH, $autologinHash, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(1234); // WHERE active = 1234
     * $query->filterByActive(array(12, 34)); // WHERE active IN (12, 34)
     * $query->filterByActive(array('min' => 12)); // WHERE active > 12
     * </code>
     *
     * @param     mixed $active The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_array($active)) {
            $useMinMax = false;
            if (isset($active['min'])) {
                $this->addUsingAlias(UsersTableMap::COL_ACTIVE, $active['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($active['max'])) {
                $this->addUsingAlias(UsersTableMap::COL_ACTIVE, $active['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query on the phonenumber column
     *
     * Example usage:
     * <code>
     * $query->filterByPhonenumber('fooValue');   // WHERE phonenumber = 'fooValue'
     * $query->filterByPhonenumber('%fooValue%', Criteria::LIKE); // WHERE phonenumber LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phonenumber The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPhonenumber($phonenumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phonenumber)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_PHONENUMBER, $phonenumber, $comparison);
    }

    /**
     * Filter the query on the call_request column
     *
     * Example usage:
     * <code>
     * $query->filterByCallRequest('2011-03-14'); // WHERE call_request = '2011-03-14'
     * $query->filterByCallRequest('now'); // WHERE call_request = '2011-03-14'
     * $query->filterByCallRequest(array('max' => 'yesterday')); // WHERE call_request > '2011-03-13'
     * </code>
     *
     * @param     mixed $callRequest The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByCallRequest($callRequest = null, $comparison = null)
    {
        if (is_array($callRequest)) {
            $useMinMax = false;
            if (isset($callRequest['min'])) {
                $this->addUsingAlias(UsersTableMap::COL_CALL_REQUEST, $callRequest['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($callRequest['max'])) {
                $this->addUsingAlias(UsersTableMap::COL_CALL_REQUEST, $callRequest['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_CALL_REQUEST, $callRequest, $comparison);
    }

    /**
     * Filter the query on the is_admin column
     *
     * Example usage:
     * <code>
     * $query->filterByIsAdmin(true); // WHERE is_admin = true
     * $query->filterByIsAdmin('yes'); // WHERE is_admin = true
     * </code>
     *
     * @param     boolean|string $isAdmin The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByIsAdmin($isAdmin = null, $comparison = null)
    {
        if (is_string($isAdmin)) {
            $isAdmin = in_array(strtolower($isAdmin), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UsersTableMap::COL_IS_ADMIN, $isAdmin, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Payment\Coupons object
     *
     * @param \API\Models\Payment\Coupons|ObjectCollection $coupons the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByCoupons($coupons, $comparison = null)
    {
        if ($coupons instanceof \API\Models\Payment\Coupons) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $coupons->getCreatedBy(), $comparison);
        } elseif ($coupons instanceof ObjectCollection) {
            return $this
                ->useCouponsQuery()
                ->filterByPrimaryKeys($coupons->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCoupons() only accepts arguments of type \API\Models\Payment\Coupons or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Coupons relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinCoupons($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Coupons');

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
            $this->addJoinObject($join, 'Coupons');
        }

        return $this;
    }

    /**
     * Use the Coupons relation Coupons object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\CouponsQuery A secondary query class using the current class as primary query
     */
    public function useCouponsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCoupons($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Coupons', '\API\Models\Payment\CouponsQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionsPlacesUsers object
     *
     * @param \API\Models\DistributionPlace\DistributionsPlacesUsers|ObjectCollection $distributionsPlacesUsers the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByDistributionsPlacesUsers($distributionsPlacesUsers, $comparison = null)
    {
        if ($distributionsPlacesUsers instanceof \API\Models\DistributionPlace\DistributionsPlacesUsers) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $distributionsPlacesUsers->getUserid(), $comparison);
        } elseif ($distributionsPlacesUsers instanceof ObjectCollection) {
            return $this
                ->useDistributionsPlacesUsersQuery()
                ->filterByPrimaryKeys($distributionsPlacesUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionsPlacesUsers() only accepts arguments of type \API\Models\DistributionPlace\DistributionsPlacesUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionsPlacesUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinDistributionsPlacesUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionsPlacesUsers');

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
            $this->addJoinObject($join, 'DistributionsPlacesUsers');
        }

        return $this;
    }

    /**
     * Use the DistributionsPlacesUsers relation DistributionsPlacesUsers object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionsPlacesUsersQuery A secondary query class using the current class as primary query
     */
    public function useDistributionsPlacesUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionsPlacesUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionsPlacesUsers', '\API\Models\DistributionPlace\DistributionsPlacesUsersQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventsUser object
     *
     * @param \API\Models\Event\EventsUser|ObjectCollection $eventsUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByEventsUser($eventsUser, $comparison = null)
    {
        if ($eventsUser instanceof \API\Models\Event\EventsUser) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $eventsUser->getUserid(), $comparison);
        } elseif ($eventsUser instanceof ObjectCollection) {
            return $this
                ->useEventsUserQuery()
                ->filterByPrimaryKeys($eventsUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventsUser() only accepts arguments of type \API\Models\Event\EventsUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventsUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinEventsUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventsUser');

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
            $this->addJoinObject($join, 'EventsUser');
        }

        return $this;
    }

    /**
     * Use the EventsUser relation EventsUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventsUserQuery A secondary query class using the current class as primary query
     */
    public function useEventsUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventsUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventsUser', '\API\Models\Event\EventsUserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoices object
     *
     * @param \API\Models\Invoice\Invoices|ObjectCollection $invoices the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByInvoices($invoices, $comparison = null)
    {
        if ($invoices instanceof \API\Models\Invoice\Invoices) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $invoices->getCashierUserid(), $comparison);
        } elseif ($invoices instanceof ObjectCollection) {
            return $this
                ->useInvoicesQuery()
                ->filterByPrimaryKeys($invoices->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoices() only accepts arguments of type \API\Models\Invoice\Invoices or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invoices relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinInvoices($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Invoices');

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
            $this->addJoinObject($join, 'Invoices');
        }

        return $this;
    }

    /**
     * Use the Invoices relation Invoices object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoicesQuery A secondary query class using the current class as primary query
     */
    public function useInvoicesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoices($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoices', '\API\Models\Invoice\InvoicesQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\Orders object
     *
     * @param \API\Models\Ordering\Orders|ObjectCollection $orders the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByOrders($orders, $comparison = null)
    {
        if ($orders instanceof \API\Models\Ordering\Orders) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $orders->getUserid(), $comparison);
        } elseif ($orders instanceof ObjectCollection) {
            return $this
                ->useOrdersQuery()
                ->filterByPrimaryKeys($orders->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrders() only accepts arguments of type \API\Models\Ordering\Orders or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Orders relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinOrders($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Orders');

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
            $this->addJoinObject($join, 'Orders');
        }

        return $this;
    }

    /**
     * Use the Orders relation Orders object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrdersQuery A secondary query class using the current class as primary query
     */
    public function useOrdersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrders($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Orders', '\API\Models\Ordering\OrdersQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrdersDetails object
     *
     * @param \API\Models\Ordering\OrdersDetails|ObjectCollection $ordersDetails the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \API\Models\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $ordersDetails->getSinglePriceModifiedByUserid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            return $this
                ->useOrdersDetailsQuery()
                ->filterByPrimaryKeys($ordersDetails->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
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
     * @return \API\Models\Ordering\OrdersDetailsQuery A secondary query class using the current class as primary query
     */
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\API\Models\Ordering\OrdersDetailsQuery');
    }

    /**
     * Filter the query by a related \API\Models\OIP\OrdersInProgress object
     *
     * @param \API\Models\OIP\OrdersInProgress|ObjectCollection $ordersInProgress the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgress($ordersInProgress, $comparison = null)
    {
        if ($ordersInProgress instanceof \API\Models\OIP\OrdersInProgress) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_USERID, $ordersInProgress->getUserid(), $comparison);
        } elseif ($ordersInProgress instanceof ObjectCollection) {
            return $this
                ->useOrdersInProgressQuery()
                ->filterByPrimaryKeys($ordersInProgress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersInProgress() only accepts arguments of type \API\Models\OIP\OrdersInProgress or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersInProgress relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinOrdersInProgress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersInProgress');

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
            $this->addJoinObject($join, 'OrdersInProgress');
        }

        return $this;
    }

    /**
     * Use the OrdersInProgress relation OrdersInProgress object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\OIP\OrdersInProgressQuery A secondary query class using the current class as primary query
     */
    public function useOrdersInProgressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersInProgress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersInProgress', '\API\Models\OIP\OrdersInProgressQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUsers $users Object to remove from the list of results
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function prune($users = null)
    {
        if ($users) {
            $this->addUsingAlias(UsersTableMap::COL_USERID, $users->getUserid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UsersTableMap::clearInstancePool();
            UsersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UsersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UsersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UsersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UsersQuery
