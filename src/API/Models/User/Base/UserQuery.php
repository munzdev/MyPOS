<?php

namespace API\Models\User\Base;

use \Exception;
use \PDO;
use API\Models\DistributionPlace\DistributionPlaceUser;
use API\Models\Event\EventUser;
use API\Models\Invoice\Invoice;
use API\Models\OIP\OrderInProgress;
use API\Models\Ordering\Order;
use API\Models\Ordering\OrderDetail;
use API\Models\Payment\Coupon;
use API\Models\Payment\PaymentRecieved;
use API\Models\User\User as ChildUser;
use API\Models\User\UserQuery as ChildUserQuery;
use API\Models\User\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 * @method ChildUserQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method ChildUserQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method ChildUserQuery orderByLastname($order = Criteria::ASC) Order by the lastname column
 * @method ChildUserQuery orderByAutologinHash($order = Criteria::ASC) Order by the autologin_hash column
 * @method ChildUserQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method ChildUserQuery orderByPhonenumber($order = Criteria::ASC) Order by the phonenumber column
 * @method ChildUserQuery orderByCallRequest($order = Criteria::ASC) Order by the call_request column
 * @method ChildUserQuery orderByIsAdmin($order = Criteria::ASC) Order by the is_admin column
 *
 * @method ChildUserQuery groupByUserid() Group by the userid column
 * @method ChildUserQuery groupByUsername() Group by the username column
 * @method ChildUserQuery groupByPassword() Group by the password column
 * @method ChildUserQuery groupByFirstname() Group by the firstname column
 * @method ChildUserQuery groupByLastname() Group by the lastname column
 * @method ChildUserQuery groupByAutologinHash() Group by the autologin_hash column
 * @method ChildUserQuery groupByActive() Group by the active column
 * @method ChildUserQuery groupByPhonenumber() Group by the phonenumber column
 * @method ChildUserQuery groupByCallRequest() Group by the call_request column
 * @method ChildUserQuery groupByIsAdmin() Group by the is_admin column
 *
 * @method ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method ChildUserQuery leftJoinCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupon relation
 * @method ChildUserQuery rightJoinCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupon relation
 * @method ChildUserQuery innerJoinCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupon relation
 *
 * @method ChildUserQuery joinWithCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupon relation
 *
 * @method ChildUserQuery leftJoinWithCoupon() Adds a LEFT JOIN clause and with to the query using the Coupon relation
 * @method ChildUserQuery rightJoinWithCoupon() Adds a RIGHT JOIN clause and with to the query using the Coupon relation
 * @method ChildUserQuery innerJoinWithCoupon() Adds a INNER JOIN clause and with to the query using the Coupon relation
 *
 * @method ChildUserQuery leftJoinDistributionPlaceUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the DistributionPlaceUser relation
 * @method ChildUserQuery rightJoinDistributionPlaceUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DistributionPlaceUser relation
 * @method ChildUserQuery innerJoinDistributionPlaceUser($relationAlias = null) Adds a INNER JOIN clause to the query using the DistributionPlaceUser relation
 *
 * @method ChildUserQuery joinWithDistributionPlaceUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the DistributionPlaceUser relation
 *
 * @method ChildUserQuery leftJoinWithDistributionPlaceUser() Adds a LEFT JOIN clause and with to the query using the DistributionPlaceUser relation
 * @method ChildUserQuery rightJoinWithDistributionPlaceUser() Adds a RIGHT JOIN clause and with to the query using the DistributionPlaceUser relation
 * @method ChildUserQuery innerJoinWithDistributionPlaceUser() Adds a INNER JOIN clause and with to the query using the DistributionPlaceUser relation
 *
 * @method ChildUserQuery leftJoinEventUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventUser relation
 * @method ChildUserQuery rightJoinEventUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventUser relation
 * @method ChildUserQuery innerJoinEventUser($relationAlias = null) Adds a INNER JOIN clause to the query using the EventUser relation
 *
 * @method ChildUserQuery joinWithEventUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventUser relation
 *
 * @method ChildUserQuery leftJoinWithEventUser() Adds a LEFT JOIN clause and with to the query using the EventUser relation
 * @method ChildUserQuery rightJoinWithEventUser() Adds a RIGHT JOIN clause and with to the query using the EventUser relation
 * @method ChildUserQuery innerJoinWithEventUser() Adds a INNER JOIN clause and with to the query using the EventUser relation
 *
 * @method ChildUserQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method ChildUserQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method ChildUserQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method ChildUserQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method ChildUserQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method ChildUserQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method ChildUserQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method ChildUserQuery leftJoinOrderRelatedByCancellationCreatedByUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderRelatedByCancellationCreatedByUserid relation
 * @method ChildUserQuery rightJoinOrderRelatedByCancellationCreatedByUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderRelatedByCancellationCreatedByUserid relation
 * @method ChildUserQuery innerJoinOrderRelatedByCancellationCreatedByUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderRelatedByCancellationCreatedByUserid relation
 *
 * @method ChildUserQuery joinWithOrderRelatedByCancellationCreatedByUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderRelatedByCancellationCreatedByUserid relation
 *
 * @method ChildUserQuery leftJoinWithOrderRelatedByCancellationCreatedByUserid() Adds a LEFT JOIN clause and with to the query using the OrderRelatedByCancellationCreatedByUserid relation
 * @method ChildUserQuery rightJoinWithOrderRelatedByCancellationCreatedByUserid() Adds a RIGHT JOIN clause and with to the query using the OrderRelatedByCancellationCreatedByUserid relation
 * @method ChildUserQuery innerJoinWithOrderRelatedByCancellationCreatedByUserid() Adds a INNER JOIN clause and with to the query using the OrderRelatedByCancellationCreatedByUserid relation
 *
 * @method ChildUserQuery leftJoinOrderRelatedByUserid($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderRelatedByUserid relation
 * @method ChildUserQuery rightJoinOrderRelatedByUserid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderRelatedByUserid relation
 * @method ChildUserQuery innerJoinOrderRelatedByUserid($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderRelatedByUserid relation
 *
 * @method ChildUserQuery joinWithOrderRelatedByUserid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderRelatedByUserid relation
 *
 * @method ChildUserQuery leftJoinWithOrderRelatedByUserid() Adds a LEFT JOIN clause and with to the query using the OrderRelatedByUserid relation
 * @method ChildUserQuery rightJoinWithOrderRelatedByUserid() Adds a RIGHT JOIN clause and with to the query using the OrderRelatedByUserid relation
 * @method ChildUserQuery innerJoinWithOrderRelatedByUserid() Adds a INNER JOIN clause and with to the query using the OrderRelatedByUserid relation
 *
 * @method ChildUserQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method ChildUserQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method ChildUserQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method ChildUserQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method ChildUserQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method ChildUserQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method ChildUserQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method ChildUserQuery leftJoinOrderInProgress($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgress relation
 * @method ChildUserQuery rightJoinOrderInProgress($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgress relation
 * @method ChildUserQuery innerJoinOrderInProgress($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgress relation
 *
 * @method ChildUserQuery joinWithOrderInProgress($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgress relation
 *
 * @method ChildUserQuery leftJoinWithOrderInProgress() Adds a LEFT JOIN clause and with to the query using the OrderInProgress relation
 * @method ChildUserQuery rightJoinWithOrderInProgress() Adds a RIGHT JOIN clause and with to the query using the OrderInProgress relation
 * @method ChildUserQuery innerJoinWithOrderInProgress() Adds a INNER JOIN clause and with to the query using the OrderInProgress relation
 *
 * @method ChildUserQuery leftJoinPaymentRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentRecieved relation
 * @method ChildUserQuery rightJoinPaymentRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentRecieved relation
 * @method ChildUserQuery innerJoinPaymentRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentRecieved relation
 *
 * @method ChildUserQuery joinWithPaymentRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentRecieved relation
 *
 * @method ChildUserQuery leftJoinWithPaymentRecieved() Adds a LEFT JOIN clause and with to the query using the PaymentRecieved relation
 * @method ChildUserQuery rightJoinWithPaymentRecieved() Adds a RIGHT JOIN clause and with to the query using the PaymentRecieved relation
 * @method ChildUserQuery innerJoinWithPaymentRecieved() Adds a INNER JOIN clause and with to the query using the PaymentRecieved relation
 *
 * @method \API\Models\Payment\CouponQuery|\API\Models\DistributionPlace\DistributionPlaceUserQuery|\API\Models\Event\EventUserQuery|\API\Models\Invoice\InvoiceQuery|\API\Models\Ordering\OrderQuery|\API\Models\Ordering\OrderDetailQuery|\API\Models\OIP\OrderInProgressQuery|\API\Models\Payment\PaymentRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method ChildUser findOneByUserid(int $userid) Return the first ChildUser filtered by the userid column
 * @method ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method ChildUser findOneByFirstname(string $firstname) Return the first ChildUser filtered by the firstname column
 * @method ChildUser findOneByLastname(string $lastname) Return the first ChildUser filtered by the lastname column
 * @method ChildUser findOneByAutologinHash(string $autologin_hash) Return the first ChildUser filtered by the autologin_hash column
 * @method ChildUser findOneByActive(int $active) Return the first ChildUser filtered by the active column
 * @method ChildUser findOneByPhonenumber(string $phonenumber) Return the first ChildUser filtered by the phonenumber column
 * @method ChildUser findOneByCallRequest(string $call_request) Return the first ChildUser filtered by the call_request column
 * @method ChildUser findOneByIsAdmin(boolean $is_admin) Return the first ChildUser filtered by the is_admin column *

 * @method ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildUser requireOneByUserid(int $userid) Return the first ChildUser filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByFirstname(string $firstname) Return the first ChildUser filtered by the firstname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByLastname(string $lastname) Return the first ChildUser filtered by the lastname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByAutologinHash(string $autologin_hash) Return the first ChildUser filtered by the autologin_hash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByActive(int $active) Return the first ChildUser filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByPhonenumber(string $phonenumber) Return the first ChildUser filtered by the phonenumber column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByCallRequest(string $call_request) Return the first ChildUser filtered by the call_request column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method ChildUser requireOneByIsAdmin(boolean $is_admin) Return the first ChildUser filtered by the is_admin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method ChildUser[]|ObjectCollection findByUserid(int $userid) Return ChildUser objects filtered by the userid column
 * @method ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method ChildUser[]|ObjectCollection findByFirstname(string $firstname) Return ChildUser objects filtered by the firstname column
 * @method ChildUser[]|ObjectCollection findByLastname(string $lastname) Return ChildUser objects filtered by the lastname column
 * @method ChildUser[]|ObjectCollection findByAutologinHash(string $autologin_hash) Return ChildUser objects filtered by the autologin_hash column
 * @method ChildUser[]|ObjectCollection findByActive(int $active) Return ChildUser objects filtered by the active column
 * @method ChildUser[]|ObjectCollection findByPhonenumber(string $phonenumber) Return ChildUser objects filtered by the phonenumber column
 * @method ChildUser[]|ObjectCollection findByCallRequest(string $call_request) Return ChildUser objects filtered by the call_request column
 * @method ChildUser[]|ObjectCollection findByIsAdmin(boolean $is_admin) Return ChildUser objects filtered by the is_admin column
 * @method ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\User\Base\UserQuery object.
     *
     * @param string $dbName     The database name
     * @param string $modelName  The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\User\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param string   $modelAlias The alias of a model in the query
     * @param Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if ($this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT userid, username, password, firstname, lastname, autologin_hash, active, phonenumber, call_request, is_admin FROM user WHERE userid = :p0';
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
 * @var ChildUser $obj
*/
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(UserTableMap::COL_USERID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(UserTableMap::COL_USERID, $keys, Criteria::IN);
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
     * @param mixed  $userid     The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERID, $userid, $comparison);
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
     * @param string $username   The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
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
     * @param string $password   The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
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
     * @param string $firstname  The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_FIRSTNAME, $firstname, $comparison);
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
     * @param string $lastname   The value to use as filter.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_LASTNAME, $lastname, $comparison);
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
     * @param string $autologinHash The value to use as filter.
     * @param string $comparison    Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAutologinHash($autologinHash = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($autologinHash)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_AUTOLOGIN_HASH, $autologinHash, $comparison);
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
     * @param mixed  $active     The value to use as filter.
     *                           Use scalar values for
     *                           equality. Use array values
     *                           for in_array() equivalent.
     *                           Use associative array('min'
     *                           => $minValue, 'max' =>
     *                           $maxValue) for intervals.
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_array($active)) {
            $useMinMax = false;
            if (isset($active['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ACTIVE, $active['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($active['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ACTIVE, $active['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ACTIVE, $active, $comparison);
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
     * @param string $phonenumber The value to use as filter.
     * @param string $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPhonenumber($phonenumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phonenumber)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PHONENUMBER, $phonenumber, $comparison);
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
     * @param mixed  $callRequest The value to use as filter.
     *                                Values can be integers
     *                                (unix timestamps), DateTime
     *                                objects, or strings. Empty
     *                                strings are treated as
     *                                NULL. Use scalar values for
     *                                equality. Use array values
     *                                for in_array() equivalent.
     *                                Use associative array('min'
     *                                => $minValue, 'max' =>
     *                                $maxValue) for intervals.
     * @param string $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCallRequest($callRequest = null, $comparison = null)
    {
        if (is_array($callRequest)) {
            $useMinMax = false;
            if (isset($callRequest['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CALL_REQUEST, $callRequest['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($callRequest['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CALL_REQUEST, $callRequest['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CALL_REQUEST, $callRequest, $comparison);
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
     * @param boolean|string $isAdmin    The value to use as filter.
     *                                       Non-boolean arguments are
     *                                       converted using the
     *                                       following rules: * 1, '1',
     *                                       'true',  'on',  and 'yes'
     *                                       are converted to boolean
     *                                       true * 0, '0', 'false',
     *                                       'off', and 'no'  are
     *                                       converted to boolean false
     *                                       Check on string values is
     *                                       case insensitive (so
     *                                       'FaLsE' is seen as
     *                                       'false').
     * @param string         $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIsAdmin($isAdmin = null, $comparison = null)
    {
        if (is_string($isAdmin)) {
            $isAdmin = in_array(strtolower($isAdmin), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserTableMap::COL_IS_ADMIN, $isAdmin, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Payment\Coupon object
     *
     * @param \API\Models\Payment\Coupon|ObjectCollection $coupon     the related object to use as filter
     * @param string                                      $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByCoupon($coupon, $comparison = null)
    {
        if ($coupon instanceof \API\Models\Payment\Coupon) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $coupon->getCreatedByUserid(), $comparison);
        } elseif ($coupon instanceof ObjectCollection) {
            return $this
                ->useCouponQuery()
                ->filterByPrimaryKeys($coupon->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCoupon() only accepts arguments of type \API\Models\Payment\Coupon or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Coupon relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinCoupon($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Coupon');

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
            $this->addJoinObject($join, 'Coupon');
        }

        return $this;
    }

    /**
     * Use the Coupon relation Coupon object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\CouponQuery A secondary query class using the current class as primary query
     */
    public function useCouponQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCoupon($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Coupon', '\API\Models\Payment\CouponQuery');
    }

    /**
     * Filter the query by a related \API\Models\DistributionPlace\DistributionPlaceUser object
     *
     * @param \API\Models\DistributionPlace\DistributionPlaceUser|ObjectCollection $distributionPlaceUser the related object to use as filter
     * @param string                                                               $comparison            Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByDistributionPlaceUser($distributionPlaceUser, $comparison = null)
    {
        if ($distributionPlaceUser instanceof \API\Models\DistributionPlace\DistributionPlaceUser) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $distributionPlaceUser->getUserid(), $comparison);
        } elseif ($distributionPlaceUser instanceof ObjectCollection) {
            return $this
                ->useDistributionPlaceUserQuery()
                ->filterByPrimaryKeys($distributionPlaceUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDistributionPlaceUser() only accepts arguments of type \API\Models\DistributionPlace\DistributionPlaceUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DistributionPlaceUser relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinDistributionPlaceUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DistributionPlaceUser');

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
            $this->addJoinObject($join, 'DistributionPlaceUser');
        }

        return $this;
    }

    /**
     * Use the DistributionPlaceUser relation DistributionPlaceUser object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\DistributionPlace\DistributionPlaceUserQuery A secondary query class using the current class as primary query
     */
    public function useDistributionPlaceUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDistributionPlaceUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DistributionPlaceUser', '\API\Models\DistributionPlace\DistributionPlaceUserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventUser object
     *
     * @param \API\Models\Event\EventUser|ObjectCollection $eventUser  the related object to use as filter
     * @param string                                       $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByEventUser($eventUser, $comparison = null)
    {
        if ($eventUser instanceof \API\Models\Event\EventUser) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $eventUser->getUserid(), $comparison);
        } elseif ($eventUser instanceof ObjectCollection) {
            return $this
                ->useEventUserQuery()
                ->filterByPrimaryKeys($eventUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventUser() only accepts arguments of type \API\Models\Event\EventUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventUser relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinEventUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventUser');

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
            $this->addJoinObject($join, 'EventUser');
        }

        return $this;
    }

    /**
     * Use the EventUser relation EventUser object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventUserQuery A secondary query class using the current class as primary query
     */
    public function useEventUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventUser', '\API\Models\Event\EventUserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice    the related object to use as filter
     * @param string                                       $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $invoice->getUserid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            return $this
                ->useInvoiceQuery()
                ->filterByPrimaryKeys($invoice->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoice() only accepts arguments of type \API\Models\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invoice relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinInvoice($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Invoice');

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
            $this->addJoinObject($join, 'Invoice');
        }

        return $this;
    }

    /**
     * Use the Invoice relation Invoice object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoice', '\API\Models\Invoice\InvoiceQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\Order object
     *
     * @param \API\Models\Ordering\Order|ObjectCollection $order      the related object to use as filter
     * @param string                                      $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByOrderRelatedByCancellationCreatedByUserid($order, $comparison = null)
    {
        if ($order instanceof \API\Models\Ordering\Order) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $order->getCancellationCreatedByUserid(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            return $this
                ->useOrderRelatedByCancellationCreatedByUseridQuery()
                ->filterByPrimaryKeys($order->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderRelatedByCancellationCreatedByUserid() only accepts arguments of type \API\Models\Ordering\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderRelatedByCancellationCreatedByUserid relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinOrderRelatedByCancellationCreatedByUserid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderRelatedByCancellationCreatedByUserid');

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
            $this->addJoinObject($join, 'OrderRelatedByCancellationCreatedByUserid');
        }

        return $this;
    }

    /**
     * Use the OrderRelatedByCancellationCreatedByUserid relation Order object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderRelatedByCancellationCreatedByUseridQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrderRelatedByCancellationCreatedByUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderRelatedByCancellationCreatedByUserid', '\API\Models\Ordering\OrderQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\Order object
     *
     * @param \API\Models\Ordering\Order|ObjectCollection $order      the related object to use as filter
     * @param string                                      $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByOrderRelatedByUserid($order, $comparison = null)
    {
        if ($order instanceof \API\Models\Ordering\Order) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $order->getUserid(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            return $this
                ->useOrderRelatedByUseridQuery()
                ->filterByPrimaryKeys($order->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderRelatedByUserid() only accepts arguments of type \API\Models\Ordering\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderRelatedByUserid relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinOrderRelatedByUserid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderRelatedByUserid');

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
            $this->addJoinObject($join, 'OrderRelatedByUserid');
        }

        return $this;
    }

    /**
     * Use the OrderRelatedByUserid relation Order object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Ordering\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderRelatedByUseridQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderRelatedByUserid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderRelatedByUserid', '\API\Models\Ordering\OrderQuery');
    }

    /**
     * Filter the query by a related \API\Models\Ordering\OrderDetail object
     *
     * @param \API\Models\Ordering\OrderDetail|ObjectCollection $orderDetail the related object to use as filter
     * @param string                                            $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $orderDetail->getSinglePriceModifiedByUserid(), $comparison);
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
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \API\Models\OIP\OrderInProgress object
     *
     * @param \API\Models\OIP\OrderInProgress|ObjectCollection $orderInProgress the related object to use as filter
     * @param string                                           $comparison      Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByOrderInProgress($orderInProgress, $comparison = null)
    {
        if ($orderInProgress instanceof \API\Models\OIP\OrderInProgress) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $orderInProgress->getUserid(), $comparison);
        } elseif ($orderInProgress instanceof ObjectCollection) {
            return $this
                ->useOrderInProgressQuery()
                ->filterByPrimaryKeys($orderInProgress->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderInProgress() only accepts arguments of type \API\Models\OIP\OrderInProgress or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderInProgress relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinOrderInProgress($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderInProgress');

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
            $this->addJoinObject($join, 'OrderInProgress');
        }

        return $this;
    }

    /**
     * Use the OrderInProgress relation OrderInProgress object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\OIP\OrderInProgressQuery A secondary query class using the current class as primary query
     */
    public function useOrderInProgressQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderInProgress($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderInProgress', '\API\Models\OIP\OrderInProgressQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentRecieved object
     *
     * @param \API\Models\Payment\PaymentRecieved|ObjectCollection $paymentRecieved the related object to use as filter
     * @param string                                               $comparison      Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPaymentRecieved($paymentRecieved, $comparison = null)
    {
        if ($paymentRecieved instanceof \API\Models\Payment\PaymentRecieved) {
            return $this
                ->addUsingAlias(UserTableMap::COL_USERID, $paymentRecieved->getUserid(), $comparison);
        } elseif ($paymentRecieved instanceof ObjectCollection) {
            return $this
                ->usePaymentRecievedQuery()
                ->filterByPrimaryKeys($paymentRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPaymentRecieved() only accepts arguments of type \API\Models\Payment\PaymentRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentRecieved relation
     *
     * @param string $relationAlias optional alias for the relation
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPaymentRecieved($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PaymentRecieved');

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
            $this->addJoinObject($join, 'PaymentRecieved');
        }

        return $this;
    }

    /**
     * Use the PaymentRecieved relation PaymentRecieved object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentRecievedQuery A secondary query class using the current class as primary query
     */
    public function usePaymentRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentRecieved', '\API\Models\Payment\PaymentRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_USERID, $user->getUserid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param  ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::clearInstancePool();
                UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(
            function () use ($con, $criteria) {
                $affectedRows = 0; // initialize var to track total num of affected rows

                UserTableMap::removeInstanceFromPool($criteria);

                $affectedRows += ModelCriteria::delete($con);
                UserTableMap::clearRelatedInstancePool();

                return $affectedRows;
            }
        );
    }
} // UserQuery
