<?php

namespace Model\Ordering\Base;

use \Exception;
use \PDO;
use Model\Invoice\InvoicesItems;
use Model\Menues\Availabilitys;
use Model\Menues\MenuGroupes;
use Model\Menues\MenuSizes;
use Model\Menues\Menues;
use Model\OIP\OrdersInProgressRecieved;
use Model\Ordering\OrdersDetails as ChildOrdersDetails;
use Model\Ordering\OrdersDetailsQuery as ChildOrdersDetailsQuery;
use Model\Ordering\Map\OrdersDetailsTableMap;
use Model\User\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'orders_details' table.
 *
 *
 *
 * @method     ChildOrdersDetailsQuery orderByOrdersDetailid($order = Criteria::ASC) Order by the orders_detailid column
 * @method     ChildOrdersDetailsQuery orderByOrderid($order = Criteria::ASC) Order by the orderid column
 * @method     ChildOrdersDetailsQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildOrdersDetailsQuery orderByMenuSizeid($order = Criteria::ASC) Order by the menu_sizeid column
 * @method     ChildOrdersDetailsQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildOrdersDetailsQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildOrdersDetailsQuery orderBySinglePrice($order = Criteria::ASC) Order by the single_price column
 * @method     ChildOrdersDetailsQuery orderBySinglePriceModifiedByUserid($order = Criteria::ASC) Order by the single_price_modified_by_userid column
 * @method     ChildOrdersDetailsQuery orderByExtraDetail($order = Criteria::ASC) Order by the extra_detail column
 * @method     ChildOrdersDetailsQuery orderByFinished($order = Criteria::ASC) Order by the finished column
 * @method     ChildOrdersDetailsQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildOrdersDetailsQuery orderByAvailabilityAmount($order = Criteria::ASC) Order by the availability_amount column
 * @method     ChildOrdersDetailsQuery orderByVerified($order = Criteria::ASC) Order by the verified column
 *
 * @method     ChildOrdersDetailsQuery groupByOrdersDetailid() Group by the orders_detailid column
 * @method     ChildOrdersDetailsQuery groupByOrderid() Group by the orderid column
 * @method     ChildOrdersDetailsQuery groupByMenuid() Group by the menuid column
 * @method     ChildOrdersDetailsQuery groupByMenuSizeid() Group by the menu_sizeid column
 * @method     ChildOrdersDetailsQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildOrdersDetailsQuery groupByAmount() Group by the amount column
 * @method     ChildOrdersDetailsQuery groupBySinglePrice() Group by the single_price column
 * @method     ChildOrdersDetailsQuery groupBySinglePriceModifiedByUserid() Group by the single_price_modified_by_userid column
 * @method     ChildOrdersDetailsQuery groupByExtraDetail() Group by the extra_detail column
 * @method     ChildOrdersDetailsQuery groupByFinished() Group by the finished column
 * @method     ChildOrdersDetailsQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildOrdersDetailsQuery groupByAvailabilityAmount() Group by the availability_amount column
 * @method     ChildOrdersDetailsQuery groupByVerified() Group by the verified column
 *
 * @method     ChildOrdersDetailsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrdersDetailsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrdersDetailsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrdersDetailsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrdersDetailsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrdersDetailsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrdersDetailsQuery leftJoinAvailabilitys($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availabilitys relation
 * @method     ChildOrdersDetailsQuery rightJoinAvailabilitys($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availabilitys relation
 * @method     ChildOrdersDetailsQuery innerJoinAvailabilitys($relationAlias = null) Adds a INNER JOIN clause to the query using the Availabilitys relation
 *
 * @method     ChildOrdersDetailsQuery joinWithAvailabilitys($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availabilitys relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithAvailabilitys() Adds a LEFT JOIN clause and with to the query using the Availabilitys relation
 * @method     ChildOrdersDetailsQuery rightJoinWithAvailabilitys() Adds a RIGHT JOIN clause and with to the query using the Availabilitys relation
 * @method     ChildOrdersDetailsQuery innerJoinWithAvailabilitys() Adds a INNER JOIN clause and with to the query using the Availabilitys relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinMenuGroupes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildOrdersDetailsQuery rightJoinMenuGroupes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroupes relation
 * @method     ChildOrdersDetailsQuery innerJoinMenuGroupes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroupes relation
 *
 * @method     ChildOrdersDetailsQuery joinWithMenuGroupes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithMenuGroupes() Adds a LEFT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildOrdersDetailsQuery rightJoinWithMenuGroupes() Adds a RIGHT JOIN clause and with to the query using the MenuGroupes relation
 * @method     ChildOrdersDetailsQuery innerJoinWithMenuGroupes() Adds a INNER JOIN clause and with to the query using the MenuGroupes relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinMenuSizes($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuSizes relation
 * @method     ChildOrdersDetailsQuery rightJoinMenuSizes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuSizes relation
 * @method     ChildOrdersDetailsQuery innerJoinMenuSizes($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuSizes relation
 *
 * @method     ChildOrdersDetailsQuery joinWithMenuSizes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuSizes relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithMenuSizes() Adds a LEFT JOIN clause and with to the query using the MenuSizes relation
 * @method     ChildOrdersDetailsQuery rightJoinWithMenuSizes() Adds a RIGHT JOIN clause and with to the query using the MenuSizes relation
 * @method     ChildOrdersDetailsQuery innerJoinWithMenuSizes() Adds a INNER JOIN clause and with to the query using the MenuSizes relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinMenues($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menues relation
 * @method     ChildOrdersDetailsQuery rightJoinMenues($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menues relation
 * @method     ChildOrdersDetailsQuery innerJoinMenues($relationAlias = null) Adds a INNER JOIN clause to the query using the Menues relation
 *
 * @method     ChildOrdersDetailsQuery joinWithMenues($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menues relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithMenues() Adds a LEFT JOIN clause and with to the query using the Menues relation
 * @method     ChildOrdersDetailsQuery rightJoinWithMenues() Adds a RIGHT JOIN clause and with to the query using the Menues relation
 * @method     ChildOrdersDetailsQuery innerJoinWithMenues() Adds a INNER JOIN clause and with to the query using the Menues relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinOrders($relationAlias = null) Adds a LEFT JOIN clause to the query using the Orders relation
 * @method     ChildOrdersDetailsQuery rightJoinOrders($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Orders relation
 * @method     ChildOrdersDetailsQuery innerJoinOrders($relationAlias = null) Adds a INNER JOIN clause to the query using the Orders relation
 *
 * @method     ChildOrdersDetailsQuery joinWithOrders($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Orders relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithOrders() Adds a LEFT JOIN clause and with to the query using the Orders relation
 * @method     ChildOrdersDetailsQuery rightJoinWithOrders() Adds a RIGHT JOIN clause and with to the query using the Orders relation
 * @method     ChildOrdersDetailsQuery innerJoinWithOrders() Adds a INNER JOIN clause and with to the query using the Orders relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildOrdersDetailsQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildOrdersDetailsQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildOrdersDetailsQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildOrdersDetailsQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildOrdersDetailsQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinInvoicesItems($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoicesItems relation
 * @method     ChildOrdersDetailsQuery rightJoinInvoicesItems($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoicesItems relation
 * @method     ChildOrdersDetailsQuery innerJoinInvoicesItems($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoicesItems relation
 *
 * @method     ChildOrdersDetailsQuery joinWithInvoicesItems($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoicesItems relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithInvoicesItems() Adds a LEFT JOIN clause and with to the query using the InvoicesItems relation
 * @method     ChildOrdersDetailsQuery rightJoinWithInvoicesItems() Adds a RIGHT JOIN clause and with to the query using the InvoicesItems relation
 * @method     ChildOrdersDetailsQuery innerJoinWithInvoicesItems() Adds a INNER JOIN clause and with to the query using the InvoicesItems relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinOrdersDetailExtras($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetailExtras relation
 * @method     ChildOrdersDetailsQuery rightJoinOrdersDetailExtras($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetailExtras relation
 * @method     ChildOrdersDetailsQuery innerJoinOrdersDetailExtras($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetailExtras relation
 *
 * @method     ChildOrdersDetailsQuery joinWithOrdersDetailExtras($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetailExtras relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithOrdersDetailExtras() Adds a LEFT JOIN clause and with to the query using the OrdersDetailExtras relation
 * @method     ChildOrdersDetailsQuery rightJoinWithOrdersDetailExtras() Adds a RIGHT JOIN clause and with to the query using the OrdersDetailExtras relation
 * @method     ChildOrdersDetailsQuery innerJoinWithOrdersDetailExtras() Adds a INNER JOIN clause and with to the query using the OrdersDetailExtras relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinOrdersDetailsMixedWith($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetailsMixedWith relation
 * @method     ChildOrdersDetailsQuery rightJoinOrdersDetailsMixedWith($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetailsMixedWith relation
 * @method     ChildOrdersDetailsQuery innerJoinOrdersDetailsMixedWith($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetailsMixedWith relation
 *
 * @method     ChildOrdersDetailsQuery joinWithOrdersDetailsMixedWith($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetailsMixedWith relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithOrdersDetailsMixedWith() Adds a LEFT JOIN clause and with to the query using the OrdersDetailsMixedWith relation
 * @method     ChildOrdersDetailsQuery rightJoinWithOrdersDetailsMixedWith() Adds a RIGHT JOIN clause and with to the query using the OrdersDetailsMixedWith relation
 * @method     ChildOrdersDetailsQuery innerJoinWithOrdersDetailsMixedWith() Adds a INNER JOIN clause and with to the query using the OrdersDetailsMixedWith relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinOrdersInProgressRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersDetailsQuery rightJoinOrdersInProgressRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersDetailsQuery innerJoinOrdersInProgressRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersInProgressRecieved relation
 *
 * @method     ChildOrdersDetailsQuery joinWithOrdersInProgressRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersInProgressRecieved relation
 *
 * @method     ChildOrdersDetailsQuery leftJoinWithOrdersInProgressRecieved() Adds a LEFT JOIN clause and with to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersDetailsQuery rightJoinWithOrdersInProgressRecieved() Adds a RIGHT JOIN clause and with to the query using the OrdersInProgressRecieved relation
 * @method     ChildOrdersDetailsQuery innerJoinWithOrdersInProgressRecieved() Adds a INNER JOIN clause and with to the query using the OrdersInProgressRecieved relation
 *
 * @method     \Model\Menues\AvailabilitysQuery|\Model\Menues\MenuGroupesQuery|\Model\Menues\MenuSizesQuery|\Model\Menues\MenuesQuery|\Model\Ordering\OrdersQuery|\Model\User\UsersQuery|\Model\Invoice\InvoicesItemsQuery|\Model\Ordering\OrdersDetailExtrasQuery|\Model\Ordering\OrdersDetailsMixedWithQuery|\Model\OIP\OrdersInProgressRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrdersDetails findOne(ConnectionInterface $con = null) Return the first ChildOrdersDetails matching the query
 * @method     ChildOrdersDetails findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrdersDetails matching the query, or a new ChildOrdersDetails object populated from the query conditions when no match is found
 *
 * @method     ChildOrdersDetails findOneByOrdersDetailid(int $orders_detailid) Return the first ChildOrdersDetails filtered by the orders_detailid column
 * @method     ChildOrdersDetails findOneByOrderid(int $orderid) Return the first ChildOrdersDetails filtered by the orderid column
 * @method     ChildOrdersDetails findOneByMenuid(int $menuid) Return the first ChildOrdersDetails filtered by the menuid column
 * @method     ChildOrdersDetails findOneByMenuSizeid(int $menu_sizeid) Return the first ChildOrdersDetails filtered by the menu_sizeid column
 * @method     ChildOrdersDetails findOneByMenuGroupid(int $menu_groupid) Return the first ChildOrdersDetails filtered by the menu_groupid column
 * @method     ChildOrdersDetails findOneByAmount(int $amount) Return the first ChildOrdersDetails filtered by the amount column
 * @method     ChildOrdersDetails findOneBySinglePrice(string $single_price) Return the first ChildOrdersDetails filtered by the single_price column
 * @method     ChildOrdersDetails findOneBySinglePriceModifiedByUserid(int $single_price_modified_by_userid) Return the first ChildOrdersDetails filtered by the single_price_modified_by_userid column
 * @method     ChildOrdersDetails findOneByExtraDetail(string $extra_detail) Return the first ChildOrdersDetails filtered by the extra_detail column
 * @method     ChildOrdersDetails findOneByFinished(string $finished) Return the first ChildOrdersDetails filtered by the finished column
 * @method     ChildOrdersDetails findOneByAvailabilityid(int $availabilityid) Return the first ChildOrdersDetails filtered by the availabilityid column
 * @method     ChildOrdersDetails findOneByAvailabilityAmount(int $availability_amount) Return the first ChildOrdersDetails filtered by the availability_amount column
 * @method     ChildOrdersDetails findOneByVerified(boolean $verified) Return the first ChildOrdersDetails filtered by the verified column *

 * @method     ChildOrdersDetails requirePk($key, ConnectionInterface $con = null) Return the ChildOrdersDetails by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOne(ConnectionInterface $con = null) Return the first ChildOrdersDetails matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersDetails requireOneByOrdersDetailid(int $orders_detailid) Return the first ChildOrdersDetails filtered by the orders_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByOrderid(int $orderid) Return the first ChildOrdersDetails filtered by the orderid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByMenuid(int $menuid) Return the first ChildOrdersDetails filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByMenuSizeid(int $menu_sizeid) Return the first ChildOrdersDetails filtered by the menu_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByMenuGroupid(int $menu_groupid) Return the first ChildOrdersDetails filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByAmount(int $amount) Return the first ChildOrdersDetails filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneBySinglePrice(string $single_price) Return the first ChildOrdersDetails filtered by the single_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneBySinglePriceModifiedByUserid(int $single_price_modified_by_userid) Return the first ChildOrdersDetails filtered by the single_price_modified_by_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByExtraDetail(string $extra_detail) Return the first ChildOrdersDetails filtered by the extra_detail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByFinished(string $finished) Return the first ChildOrdersDetails filtered by the finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByAvailabilityid(int $availabilityid) Return the first ChildOrdersDetails filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByAvailabilityAmount(int $availability_amount) Return the first ChildOrdersDetails filtered by the availability_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrdersDetails requireOneByVerified(boolean $verified) Return the first ChildOrdersDetails filtered by the verified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrdersDetails[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrdersDetails objects based on current ModelCriteria
 * @method     ChildOrdersDetails[]|ObjectCollection findByOrdersDetailid(int $orders_detailid) Return ChildOrdersDetails objects filtered by the orders_detailid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByOrderid(int $orderid) Return ChildOrdersDetails objects filtered by the orderid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByMenuid(int $menuid) Return ChildOrdersDetails objects filtered by the menuid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByMenuSizeid(int $menu_sizeid) Return ChildOrdersDetails objects filtered by the menu_sizeid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildOrdersDetails objects filtered by the menu_groupid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByAmount(int $amount) Return ChildOrdersDetails objects filtered by the amount column
 * @method     ChildOrdersDetails[]|ObjectCollection findBySinglePrice(string $single_price) Return ChildOrdersDetails objects filtered by the single_price column
 * @method     ChildOrdersDetails[]|ObjectCollection findBySinglePriceModifiedByUserid(int $single_price_modified_by_userid) Return ChildOrdersDetails objects filtered by the single_price_modified_by_userid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByExtraDetail(string $extra_detail) Return ChildOrdersDetails objects filtered by the extra_detail column
 * @method     ChildOrdersDetails[]|ObjectCollection findByFinished(string $finished) Return ChildOrdersDetails objects filtered by the finished column
 * @method     ChildOrdersDetails[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildOrdersDetails objects filtered by the availabilityid column
 * @method     ChildOrdersDetails[]|ObjectCollection findByAvailabilityAmount(int $availability_amount) Return ChildOrdersDetails objects filtered by the availability_amount column
 * @method     ChildOrdersDetails[]|ObjectCollection findByVerified(boolean $verified) Return ChildOrdersDetails objects filtered by the verified column
 * @method     ChildOrdersDetails[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrdersDetailsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Ordering\Base\OrdersDetailsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Ordering\\OrdersDetails', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrdersDetailsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrdersDetailsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrdersDetailsQuery) {
            return $criteria;
        }
        $query = new ChildOrdersDetailsQuery();
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
     * @param array[$orders_detailid, $orderid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildOrdersDetails|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrdersDetailsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildOrdersDetails A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT orders_detailid, orderid, menuid, menu_sizeid, menu_groupid, amount, single_price, single_price_modified_by_userid, extra_detail, finished, availabilityid, availability_amount, verified FROM orders_details WHERE orders_detailid = :p0 AND orderid = :p1';
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
            /** @var ChildOrdersDetails $obj */
            $obj = new ChildOrdersDetails();
            $obj->hydrate($row);
            OrdersDetailsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildOrdersDetails|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(OrdersDetailsTableMap::COL_ORDERID, $key[1], Criteria::EQUAL);
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
     * @param     mixed $ordersDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailid($ordersDetailid = null, $comparison = null)
    {
        if (is_array($ordersDetailid)) {
            $useMinMax = false;
            if (isset($ordersDetailid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $ordersDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersDetailid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $ordersDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $ordersDetailid, $comparison);
    }

    /**
     * Filter the query on the orderid column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderid(1234); // WHERE orderid = 1234
     * $query->filterByOrderid(array(12, 34)); // WHERE orderid IN (12, 34)
     * $query->filterByOrderid(array('min' => 12)); // WHERE orderid > 12
     * </code>
     *
     * @see       filterByOrders()
     *
     * @param     mixed $orderid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByOrderid($orderid = null, $comparison = null)
    {
        if (is_array($orderid)) {
            $useMinMax = false;
            if (isset($orderid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERID, $orderid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERID, $orderid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_ORDERID, $orderid, $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_MENUID, $menuid, $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByMenuSizeid($menuSizeid = null, $comparison = null)
    {
        if (is_array($menuSizeid)) {
            $useMinMax = false;
            if (isset($menuSizeid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_MENU_SIZEID, $menuSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuSizeid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_MENU_SIZEID, $menuSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_MENU_SIZEID, $menuSizeid, $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
    }

    /**
     * Filter the query on the amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the single_price column
     *
     * Example usage:
     * <code>
     * $query->filterBySinglePrice(1234); // WHERE single_price = 1234
     * $query->filterBySinglePrice(array(12, 34)); // WHERE single_price IN (12, 34)
     * $query->filterBySinglePrice(array('min' => 12)); // WHERE single_price > 12
     * </code>
     *
     * @param     mixed $singlePrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterBySinglePrice($singlePrice = null, $comparison = null)
    {
        if (is_array($singlePrice)) {
            $useMinMax = false;
            if (isset($singlePrice['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE, $singlePrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($singlePrice['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE, $singlePrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE, $singlePrice, $comparison);
    }

    /**
     * Filter the query on the single_price_modified_by_userid column
     *
     * Example usage:
     * <code>
     * $query->filterBySinglePriceModifiedByUserid(1234); // WHERE single_price_modified_by_userid = 1234
     * $query->filterBySinglePriceModifiedByUserid(array(12, 34)); // WHERE single_price_modified_by_userid IN (12, 34)
     * $query->filterBySinglePriceModifiedByUserid(array('min' => 12)); // WHERE single_price_modified_by_userid > 12
     * </code>
     *
     * @see       filterByUsers()
     *
     * @param     mixed $singlePriceModifiedByUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterBySinglePriceModifiedByUserid($singlePriceModifiedByUserid = null, $comparison = null)
    {
        if (is_array($singlePriceModifiedByUserid)) {
            $useMinMax = false;
            if (isset($singlePriceModifiedByUserid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $singlePriceModifiedByUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($singlePriceModifiedByUserid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $singlePriceModifiedByUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $singlePriceModifiedByUserid, $comparison);
    }

    /**
     * Filter the query on the extra_detail column
     *
     * Example usage:
     * <code>
     * $query->filterByExtraDetail('fooValue');   // WHERE extra_detail = 'fooValue'
     * $query->filterByExtraDetail('%fooValue%', Criteria::LIKE); // WHERE extra_detail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extraDetail The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByExtraDetail($extraDetail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extraDetail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_EXTRA_DETAIL, $extraDetail, $comparison);
    }

    /**
     * Filter the query on the finished column
     *
     * Example usage:
     * <code>
     * $query->filterByFinished('2011-03-14'); // WHERE finished = '2011-03-14'
     * $query->filterByFinished('now'); // WHERE finished = '2011-03-14'
     * $query->filterByFinished(array('max' => 'yesterday')); // WHERE finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $finished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByFinished($finished = null, $comparison = null)
    {
        if (is_array($finished)) {
            $useMinMax = false;
            if (isset($finished['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_FINISHED, $finished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($finished['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_FINISHED, $finished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_FINISHED, $finished, $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByAvailabilityAmount($availabilityAmount = null, $comparison = null)
    {
        if (is_array($availabilityAmount)) {
            $useMinMax = false;
            if (isset($availabilityAmount['min'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityAmount['max'])) {
                $this->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount, $comparison);
    }

    /**
     * Filter the query on the verified column
     *
     * Example usage:
     * <code>
     * $query->filterByVerified(true); // WHERE verified = true
     * $query->filterByVerified('yes'); // WHERE verified = true
     * </code>
     *
     * @param     boolean|string $verified The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByVerified($verified = null, $comparison = null)
    {
        if (is_string($verified)) {
            $verified = in_array(strtolower($verified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(OrdersDetailsTableMap::COL_VERIFIED, $verified, $comparison);
    }

    /**
     * Filter the query by a related \Model\Menues\Availabilitys object
     *
     * @param \Model\Menues\Availabilitys|ObjectCollection $availabilitys The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByAvailabilitys($availabilitys, $comparison = null)
    {
        if ($availabilitys instanceof \Model\Menues\Availabilitys) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITYID, $availabilitys->getAvailabilityid(), $comparison);
        } elseif ($availabilitys instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_AVAILABILITYID, $availabilitys->toKeyValue('PrimaryKey', 'Availabilityid'), $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinAvailabilitys($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useAvailabilitysQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByMenuGroupes($menuGroupes, $comparison = null)
    {
        if ($menuGroupes instanceof \Model\Menues\MenuGroupes) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_MENU_GROUPID, $menuGroupes->getMenuGroupid(), $comparison);
        } elseif ($menuGroupes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_MENU_GROUPID, $menuGroupes->toKeyValue('MenuGroupid', 'MenuGroupid'), $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinMenuGroupes($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useMenuGroupesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMenuGroupes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroupes', '\Model\Menues\MenuGroupesQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\MenuSizes object
     *
     * @param \Model\Menues\MenuSizes|ObjectCollection $menuSizes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByMenuSizes($menuSizes, $comparison = null)
    {
        if ($menuSizes instanceof \Model\Menues\MenuSizes) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_MENU_SIZEID, $menuSizes->getMenuSizeid(), $comparison);
        } elseif ($menuSizes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_MENU_SIZEID, $menuSizes->toKeyValue('MenuSizeid', 'MenuSizeid'), $comparison);
        } else {
            throw new PropelException('filterByMenuSizes() only accepts arguments of type \Model\Menues\MenuSizes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuSizes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinMenuSizes($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return \Model\Menues\MenuSizesQuery A secondary query class using the current class as primary query
     */
    public function useMenuSizesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMenuSizes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuSizes', '\Model\Menues\MenuSizesQuery');
    }

    /**
     * Filter the query by a related \Model\Menues\Menues object
     *
     * @param \Model\Menues\Menues|ObjectCollection $menues The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByMenues($menues, $comparison = null)
    {
        if ($menues instanceof \Model\Menues\Menues) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_MENUID, $menues->getMenuid(), $comparison);
        } elseif ($menues instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_MENUID, $menues->toKeyValue('Menuid', 'Menuid'), $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinMenues($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useMenuesQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMenues($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menues', '\Model\Menues\MenuesQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\Orders object
     *
     * @param \Model\Ordering\Orders|ObjectCollection $orders The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByOrders($orders, $comparison = null)
    {
        if ($orders instanceof \Model\Ordering\Orders) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_ORDERID, $orders->getOrderid(), $comparison);
        } elseif ($orders instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_ORDERID, $orders->toKeyValue('Orderid', 'Orderid'), $comparison);
        } else {
            throw new PropelException('filterByOrders() only accepts arguments of type \Model\Ordering\Orders or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Orders relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
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
     * @return \Model\Ordering\OrdersQuery A secondary query class using the current class as primary query
     */
    public function useOrdersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrders($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Orders', '\Model\Ordering\OrdersQuery');
    }

    /**
     * Filter the query by a related \Model\User\Users object
     *
     * @param \Model\User\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \Model\User\Users) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $users->getUserid(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $users->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \Model\User\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinUsers($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Users');

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
            $this->addJoinObject($join, 'Users');
        }

        return $this;
    }

    /**
     * Use the Users relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\User\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\Model\User\UsersQuery');
    }

    /**
     * Filter the query by a related \Model\Invoice\InvoicesItems object
     *
     * @param \Model\Invoice\InvoicesItems|ObjectCollection $invoicesItems the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByInvoicesItems($invoicesItems, $comparison = null)
    {
        if ($invoicesItems instanceof \Model\Invoice\InvoicesItems) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $invoicesItems->getOrdersDetailid(), $comparison);
        } elseif ($invoicesItems instanceof ObjectCollection) {
            return $this
                ->useInvoicesItemsQuery()
                ->filterByPrimaryKeys($invoicesItems->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoicesItems() only accepts arguments of type \Model\Invoice\InvoicesItems or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoicesItems relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinInvoicesItems($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoicesItems');

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
            $this->addJoinObject($join, 'InvoicesItems');
        }

        return $this;
    }

    /**
     * Use the InvoicesItems relation InvoicesItems object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\Invoice\InvoicesItemsQuery A secondary query class using the current class as primary query
     */
    public function useInvoicesItemsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoicesItems($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoicesItems', '\Model\Invoice\InvoicesItemsQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetailExtras object
     *
     * @param \Model\Ordering\OrdersDetailExtras|ObjectCollection $ordersDetailExtras the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailExtras($ordersDetailExtras, $comparison = null)
    {
        if ($ordersDetailExtras instanceof \Model\Ordering\OrdersDetailExtras) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $ordersDetailExtras->getOrdersDetailid(), $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\Ordering\OrdersDetailsMixedWith object
     *
     * @param \Model\Ordering\OrdersDetailsMixedWith|ObjectCollection $ordersDetailsMixedWith the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailsMixedWith($ordersDetailsMixedWith, $comparison = null)
    {
        if ($ordersDetailsMixedWith instanceof \Model\Ordering\OrdersDetailsMixedWith) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $ordersDetailsMixedWith->getOrdersDetailid(), $comparison);
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
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
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
     * Filter the query by a related \Model\OIP\OrdersInProgressRecieved object
     *
     * @param \Model\OIP\OrdersInProgressRecieved|ObjectCollection $ordersInProgressRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function filterByOrdersInProgressRecieved($ordersInProgressRecieved, $comparison = null)
    {
        if ($ordersInProgressRecieved instanceof \Model\OIP\OrdersInProgressRecieved) {
            return $this
                ->addUsingAlias(OrdersDetailsTableMap::COL_ORDERS_DETAILID, $ordersInProgressRecieved->getOrdersDetailid(), $comparison);
        } elseif ($ordersInProgressRecieved instanceof ObjectCollection) {
            return $this
                ->useOrdersInProgressRecievedQuery()
                ->filterByPrimaryKeys($ordersInProgressRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrdersInProgressRecieved() only accepts arguments of type \Model\OIP\OrdersInProgressRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrdersInProgressRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function joinOrdersInProgressRecieved($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrdersInProgressRecieved');

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
            $this->addJoinObject($join, 'OrdersInProgressRecieved');
        }

        return $this;
    }

    /**
     * Use the OrdersInProgressRecieved relation OrdersInProgressRecieved object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OIP\OrdersInProgressRecievedQuery A secondary query class using the current class as primary query
     */
    public function useOrdersInProgressRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersInProgressRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersInProgressRecieved', '\Model\OIP\OrdersInProgressRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrdersDetails $ordersDetails Object to remove from the list of results
     *
     * @return $this|ChildOrdersDetailsQuery The current query, for fluid interface
     */
    public function prune($ordersDetails = null)
    {
        if ($ordersDetails) {
            $this->addCond('pruneCond0', $this->getAliasedColName(OrdersDetailsTableMap::COL_ORDERS_DETAILID), $ordersDetails->getOrdersDetailid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(OrdersDetailsTableMap::COL_ORDERID), $ordersDetails->getOrderid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the orders_details table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrdersDetailsTableMap::clearInstancePool();
            OrdersDetailsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrdersDetailsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrdersDetailsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrdersDetailsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrdersDetailsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrdersDetailsQuery
