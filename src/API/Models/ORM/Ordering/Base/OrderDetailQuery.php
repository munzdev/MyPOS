<?php

namespace API\Models\ORM\Ordering\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Invoice\InvoiceItem;
use API\Models\ORM\Menu\Availability;
use API\Models\ORM\Menu\Menu;
use API\Models\ORM\Menu\MenuGroup;
use API\Models\ORM\Menu\MenuSize;
use API\Models\ORM\OIP\OrderInProgressRecieved;
use API\Models\ORM\Ordering\OrderDetail as ChildOrderDetail;
use API\Models\ORM\Ordering\OrderDetailQuery as ChildOrderDetailQuery;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
use API\Models\ORM\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'order_detail' table.
 *
 * 
 *
 * @method     ChildOrderDetailQuery orderByOrderDetailid($order = Criteria::ASC) Order by the order_detailid column
 * @method     ChildOrderDetailQuery orderByOrderid($order = Criteria::ASC) Order by the orderid column
 * @method     ChildOrderDetailQuery orderByMenuid($order = Criteria::ASC) Order by the menuid column
 * @method     ChildOrderDetailQuery orderByMenuSizeid($order = Criteria::ASC) Order by the menu_sizeid column
 * @method     ChildOrderDetailQuery orderByMenuGroupid($order = Criteria::ASC) Order by the menu_groupid column
 * @method     ChildOrderDetailQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildOrderDetailQuery orderBySinglePrice($order = Criteria::ASC) Order by the single_price column
 * @method     ChildOrderDetailQuery orderBySinglePriceModifiedByUserid($order = Criteria::ASC) Order by the single_price_modified_by_userid column
 * @method     ChildOrderDetailQuery orderByExtraDetail($order = Criteria::ASC) Order by the extra_detail column
 * @method     ChildOrderDetailQuery orderByAvailabilityid($order = Criteria::ASC) Order by the availabilityid column
 * @method     ChildOrderDetailQuery orderByAvailabilityAmount($order = Criteria::ASC) Order by the availability_amount column
 * @method     ChildOrderDetailQuery orderByVerified($order = Criteria::ASC) Order by the verified column
 * @method     ChildOrderDetailQuery orderByDistributionFinished($order = Criteria::ASC) Order by the distribution_finished column
 * @method     ChildOrderDetailQuery orderByInvoiceFinished($order = Criteria::ASC) Order by the invoice_finished column
 *
 * @method     ChildOrderDetailQuery groupByOrderDetailid() Group by the order_detailid column
 * @method     ChildOrderDetailQuery groupByOrderid() Group by the orderid column
 * @method     ChildOrderDetailQuery groupByMenuid() Group by the menuid column
 * @method     ChildOrderDetailQuery groupByMenuSizeid() Group by the menu_sizeid column
 * @method     ChildOrderDetailQuery groupByMenuGroupid() Group by the menu_groupid column
 * @method     ChildOrderDetailQuery groupByAmount() Group by the amount column
 * @method     ChildOrderDetailQuery groupBySinglePrice() Group by the single_price column
 * @method     ChildOrderDetailQuery groupBySinglePriceModifiedByUserid() Group by the single_price_modified_by_userid column
 * @method     ChildOrderDetailQuery groupByExtraDetail() Group by the extra_detail column
 * @method     ChildOrderDetailQuery groupByAvailabilityid() Group by the availabilityid column
 * @method     ChildOrderDetailQuery groupByAvailabilityAmount() Group by the availability_amount column
 * @method     ChildOrderDetailQuery groupByVerified() Group by the verified column
 * @method     ChildOrderDetailQuery groupByDistributionFinished() Group by the distribution_finished column
 * @method     ChildOrderDetailQuery groupByInvoiceFinished() Group by the invoice_finished column
 *
 * @method     ChildOrderDetailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrderDetailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrderDetailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrderDetailQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrderDetailQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrderDetailQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrderDetailQuery leftJoinAvailability($relationAlias = null) Adds a LEFT JOIN clause to the query using the Availability relation
 * @method     ChildOrderDetailQuery rightJoinAvailability($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Availability relation
 * @method     ChildOrderDetailQuery innerJoinAvailability($relationAlias = null) Adds a INNER JOIN clause to the query using the Availability relation
 *
 * @method     ChildOrderDetailQuery joinWithAvailability($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Availability relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithAvailability() Adds a LEFT JOIN clause and with to the query using the Availability relation
 * @method     ChildOrderDetailQuery rightJoinWithAvailability() Adds a RIGHT JOIN clause and with to the query using the Availability relation
 * @method     ChildOrderDetailQuery innerJoinWithAvailability() Adds a INNER JOIN clause and with to the query using the Availability relation
 *
 * @method     ChildOrderDetailQuery leftJoinMenuGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuGroup relation
 * @method     ChildOrderDetailQuery rightJoinMenuGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuGroup relation
 * @method     ChildOrderDetailQuery innerJoinMenuGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuGroup relation
 *
 * @method     ChildOrderDetailQuery joinWithMenuGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuGroup relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithMenuGroup() Adds a LEFT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildOrderDetailQuery rightJoinWithMenuGroup() Adds a RIGHT JOIN clause and with to the query using the MenuGroup relation
 * @method     ChildOrderDetailQuery innerJoinWithMenuGroup() Adds a INNER JOIN clause and with to the query using the MenuGroup relation
 *
 * @method     ChildOrderDetailQuery leftJoinMenuSize($relationAlias = null) Adds a LEFT JOIN clause to the query using the MenuSize relation
 * @method     ChildOrderDetailQuery rightJoinMenuSize($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MenuSize relation
 * @method     ChildOrderDetailQuery innerJoinMenuSize($relationAlias = null) Adds a INNER JOIN clause to the query using the MenuSize relation
 *
 * @method     ChildOrderDetailQuery joinWithMenuSize($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MenuSize relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithMenuSize() Adds a LEFT JOIN clause and with to the query using the MenuSize relation
 * @method     ChildOrderDetailQuery rightJoinWithMenuSize() Adds a RIGHT JOIN clause and with to the query using the MenuSize relation
 * @method     ChildOrderDetailQuery innerJoinWithMenuSize() Adds a INNER JOIN clause and with to the query using the MenuSize relation
 *
 * @method     ChildOrderDetailQuery leftJoinMenu($relationAlias = null) Adds a LEFT JOIN clause to the query using the Menu relation
 * @method     ChildOrderDetailQuery rightJoinMenu($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Menu relation
 * @method     ChildOrderDetailQuery innerJoinMenu($relationAlias = null) Adds a INNER JOIN clause to the query using the Menu relation
 *
 * @method     ChildOrderDetailQuery joinWithMenu($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Menu relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithMenu() Adds a LEFT JOIN clause and with to the query using the Menu relation
 * @method     ChildOrderDetailQuery rightJoinWithMenu() Adds a RIGHT JOIN clause and with to the query using the Menu relation
 * @method     ChildOrderDetailQuery innerJoinWithMenu() Adds a INNER JOIN clause and with to the query using the Menu relation
 *
 * @method     ChildOrderDetailQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildOrderDetailQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildOrderDetailQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildOrderDetailQuery joinWithOrder($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Order relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithOrder() Adds a LEFT JOIN clause and with to the query using the Order relation
 * @method     ChildOrderDetailQuery rightJoinWithOrder() Adds a RIGHT JOIN clause and with to the query using the Order relation
 * @method     ChildOrderDetailQuery innerJoinWithOrder() Adds a INNER JOIN clause and with to the query using the Order relation
 *
 * @method     ChildOrderDetailQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildOrderDetailQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildOrderDetailQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildOrderDetailQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildOrderDetailQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildOrderDetailQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildOrderDetailQuery leftJoinInvoiceItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceItem relation
 * @method     ChildOrderDetailQuery rightJoinInvoiceItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceItem relation
 * @method     ChildOrderDetailQuery innerJoinInvoiceItem($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceItem relation
 *
 * @method     ChildOrderDetailQuery joinWithInvoiceItem($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceItem relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithInvoiceItem() Adds a LEFT JOIN clause and with to the query using the InvoiceItem relation
 * @method     ChildOrderDetailQuery rightJoinWithInvoiceItem() Adds a RIGHT JOIN clause and with to the query using the InvoiceItem relation
 * @method     ChildOrderDetailQuery innerJoinWithInvoiceItem() Adds a INNER JOIN clause and with to the query using the InvoiceItem relation
 *
 * @method     ChildOrderDetailQuery leftJoinOrderDetailExtra($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetailExtra relation
 * @method     ChildOrderDetailQuery rightJoinOrderDetailExtra($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetailExtra relation
 * @method     ChildOrderDetailQuery innerJoinOrderDetailExtra($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetailExtra relation
 *
 * @method     ChildOrderDetailQuery joinWithOrderDetailExtra($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetailExtra relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithOrderDetailExtra() Adds a LEFT JOIN clause and with to the query using the OrderDetailExtra relation
 * @method     ChildOrderDetailQuery rightJoinWithOrderDetailExtra() Adds a RIGHT JOIN clause and with to the query using the OrderDetailExtra relation
 * @method     ChildOrderDetailQuery innerJoinWithOrderDetailExtra() Adds a INNER JOIN clause and with to the query using the OrderDetailExtra relation
 *
 * @method     ChildOrderDetailQuery leftJoinOrderDetailMixedWith($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetailMixedWith relation
 * @method     ChildOrderDetailQuery rightJoinOrderDetailMixedWith($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetailMixedWith relation
 * @method     ChildOrderDetailQuery innerJoinOrderDetailMixedWith($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetailMixedWith relation
 *
 * @method     ChildOrderDetailQuery joinWithOrderDetailMixedWith($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetailMixedWith relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithOrderDetailMixedWith() Adds a LEFT JOIN clause and with to the query using the OrderDetailMixedWith relation
 * @method     ChildOrderDetailQuery rightJoinWithOrderDetailMixedWith() Adds a RIGHT JOIN clause and with to the query using the OrderDetailMixedWith relation
 * @method     ChildOrderDetailQuery innerJoinWithOrderDetailMixedWith() Adds a INNER JOIN clause and with to the query using the OrderDetailMixedWith relation
 *
 * @method     ChildOrderDetailQuery leftJoinOrderInProgressRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderDetailQuery rightJoinOrderInProgressRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderDetailQuery innerJoinOrderInProgressRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderInProgressRecieved relation
 *
 * @method     ChildOrderDetailQuery joinWithOrderInProgressRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderInProgressRecieved relation
 *
 * @method     ChildOrderDetailQuery leftJoinWithOrderInProgressRecieved() Adds a LEFT JOIN clause and with to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderDetailQuery rightJoinWithOrderInProgressRecieved() Adds a RIGHT JOIN clause and with to the query using the OrderInProgressRecieved relation
 * @method     ChildOrderDetailQuery innerJoinWithOrderInProgressRecieved() Adds a INNER JOIN clause and with to the query using the OrderInProgressRecieved relation
 *
 * @method     \API\Models\ORM\Menu\AvailabilityQuery|\API\Models\ORM\Menu\MenuGroupQuery|\API\Models\ORM\Menu\MenuSizeQuery|\API\Models\ORM\Menu\MenuQuery|\API\Models\ORM\Ordering\OrderQuery|\API\Models\ORM\User\UserQuery|\API\Models\ORM\Invoice\InvoiceItemQuery|\API\Models\ORM\Ordering\OrderDetailExtraQuery|\API\Models\ORM\Ordering\OrderDetailMixedWithQuery|\API\Models\ORM\OIP\OrderInProgressRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrderDetail findOne(ConnectionInterface $con = null) Return the first ChildOrderDetail matching the query
 * @method     ChildOrderDetail findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrderDetail matching the query, or a new ChildOrderDetail object populated from the query conditions when no match is found
 *
 * @method     ChildOrderDetail findOneByOrderDetailid(int $order_detailid) Return the first ChildOrderDetail filtered by the order_detailid column
 * @method     ChildOrderDetail findOneByOrderid(int $orderid) Return the first ChildOrderDetail filtered by the orderid column
 * @method     ChildOrderDetail findOneByMenuid(int $menuid) Return the first ChildOrderDetail filtered by the menuid column
 * @method     ChildOrderDetail findOneByMenuSizeid(int $menu_sizeid) Return the first ChildOrderDetail filtered by the menu_sizeid column
 * @method     ChildOrderDetail findOneByMenuGroupid(int $menu_groupid) Return the first ChildOrderDetail filtered by the menu_groupid column
 * @method     ChildOrderDetail findOneByAmount(int $amount) Return the first ChildOrderDetail filtered by the amount column
 * @method     ChildOrderDetail findOneBySinglePrice(string $single_price) Return the first ChildOrderDetail filtered by the single_price column
 * @method     ChildOrderDetail findOneBySinglePriceModifiedByUserid(int $single_price_modified_by_userid) Return the first ChildOrderDetail filtered by the single_price_modified_by_userid column
 * @method     ChildOrderDetail findOneByExtraDetail(string $extra_detail) Return the first ChildOrderDetail filtered by the extra_detail column
 * @method     ChildOrderDetail findOneByAvailabilityid(int $availabilityid) Return the first ChildOrderDetail filtered by the availabilityid column
 * @method     ChildOrderDetail findOneByAvailabilityAmount(int $availability_amount) Return the first ChildOrderDetail filtered by the availability_amount column
 * @method     ChildOrderDetail findOneByVerified(boolean $verified) Return the first ChildOrderDetail filtered by the verified column
 * @method     ChildOrderDetail findOneByDistributionFinished(string $distribution_finished) Return the first ChildOrderDetail filtered by the distribution_finished column
 * @method     ChildOrderDetail findOneByInvoiceFinished(string $invoice_finished) Return the first ChildOrderDetail filtered by the invoice_finished column *

 * @method     ChildOrderDetail requirePk($key, ConnectionInterface $con = null) Return the ChildOrderDetail by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOne(ConnectionInterface $con = null) Return the first ChildOrderDetail matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderDetail requireOneByOrderDetailid(int $order_detailid) Return the first ChildOrderDetail filtered by the order_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByOrderid(int $orderid) Return the first ChildOrderDetail filtered by the orderid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByMenuid(int $menuid) Return the first ChildOrderDetail filtered by the menuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByMenuSizeid(int $menu_sizeid) Return the first ChildOrderDetail filtered by the menu_sizeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByMenuGroupid(int $menu_groupid) Return the first ChildOrderDetail filtered by the menu_groupid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByAmount(int $amount) Return the first ChildOrderDetail filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneBySinglePrice(string $single_price) Return the first ChildOrderDetail filtered by the single_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneBySinglePriceModifiedByUserid(int $single_price_modified_by_userid) Return the first ChildOrderDetail filtered by the single_price_modified_by_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByExtraDetail(string $extra_detail) Return the first ChildOrderDetail filtered by the extra_detail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByAvailabilityid(int $availabilityid) Return the first ChildOrderDetail filtered by the availabilityid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByAvailabilityAmount(int $availability_amount) Return the first ChildOrderDetail filtered by the availability_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByVerified(boolean $verified) Return the first ChildOrderDetail filtered by the verified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByDistributionFinished(string $distribution_finished) Return the first ChildOrderDetail filtered by the distribution_finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrderDetail requireOneByInvoiceFinished(string $invoice_finished) Return the first ChildOrderDetail filtered by the invoice_finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrderDetail[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrderDetail objects based on current ModelCriteria
 * @method     ChildOrderDetail[]|ObjectCollection findByOrderDetailid(int $order_detailid) Return ChildOrderDetail objects filtered by the order_detailid column
 * @method     ChildOrderDetail[]|ObjectCollection findByOrderid(int $orderid) Return ChildOrderDetail objects filtered by the orderid column
 * @method     ChildOrderDetail[]|ObjectCollection findByMenuid(int $menuid) Return ChildOrderDetail objects filtered by the menuid column
 * @method     ChildOrderDetail[]|ObjectCollection findByMenuSizeid(int $menu_sizeid) Return ChildOrderDetail objects filtered by the menu_sizeid column
 * @method     ChildOrderDetail[]|ObjectCollection findByMenuGroupid(int $menu_groupid) Return ChildOrderDetail objects filtered by the menu_groupid column
 * @method     ChildOrderDetail[]|ObjectCollection findByAmount(int $amount) Return ChildOrderDetail objects filtered by the amount column
 * @method     ChildOrderDetail[]|ObjectCollection findBySinglePrice(string $single_price) Return ChildOrderDetail objects filtered by the single_price column
 * @method     ChildOrderDetail[]|ObjectCollection findBySinglePriceModifiedByUserid(int $single_price_modified_by_userid) Return ChildOrderDetail objects filtered by the single_price_modified_by_userid column
 * @method     ChildOrderDetail[]|ObjectCollection findByExtraDetail(string $extra_detail) Return ChildOrderDetail objects filtered by the extra_detail column
 * @method     ChildOrderDetail[]|ObjectCollection findByAvailabilityid(int $availabilityid) Return ChildOrderDetail objects filtered by the availabilityid column
 * @method     ChildOrderDetail[]|ObjectCollection findByAvailabilityAmount(int $availability_amount) Return ChildOrderDetail objects filtered by the availability_amount column
 * @method     ChildOrderDetail[]|ObjectCollection findByVerified(boolean $verified) Return ChildOrderDetail objects filtered by the verified column
 * @method     ChildOrderDetail[]|ObjectCollection findByDistributionFinished(string $distribution_finished) Return ChildOrderDetail objects filtered by the distribution_finished column
 * @method     ChildOrderDetail[]|ObjectCollection findByInvoiceFinished(string $invoice_finished) Return ChildOrderDetail objects filtered by the invoice_finished column
 * @method     ChildOrderDetail[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrderDetailQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Ordering\Base\OrderDetailQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Ordering\\OrderDetail', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderDetailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderDetailQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOrderDetailQuery) {
            return $criteria;
        }
        $query = new ChildOrderDetailQuery();
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
     * @return ChildOrderDetail|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrderDetailTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOrderDetail A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT order_detailid, orderid, menuid, menu_sizeid, menu_groupid, amount, single_price, single_price_modified_by_userid, extra_detail, availabilityid, availability_amount, verified, distribution_finished, invoice_finished FROM order_detail WHERE order_detailid = :p0';
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
            /** @var ChildOrderDetail $obj */
            $obj = new ChildOrderDetail();
            $obj->hydrate($row);
            OrderDetailTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOrderDetail|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $keys, Criteria::IN);
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
     * @param     mixed $orderDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByOrderDetailid($orderDetailid = null, $comparison = null)
    {
        if (is_array($orderDetailid)) {
            $useMinMax = false;
            if (isset($orderDetailid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderDetailid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderDetailid, $comparison);
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
     * @see       filterByOrder()
     *
     * @param     mixed $orderid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByOrderid($orderid = null, $comparison = null)
    {
        if (is_array($orderid)) {
            $useMinMax = false;
            if (isset($orderid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_ORDERID, $orderid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_ORDERID, $orderid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_ORDERID, $orderid, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenuid($menuid = null, $comparison = null)
    {
        if (is_array($menuid)) {
            $useMinMax = false;
            if (isset($menuid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_MENUID, $menuid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_MENUID, $menuid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_MENUID, $menuid, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenuSizeid($menuSizeid = null, $comparison = null)
    {
        if (is_array($menuSizeid)) {
            $useMinMax = false;
            if (isset($menuSizeid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_MENU_SIZEID, $menuSizeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuSizeid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_MENU_SIZEID, $menuSizeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_MENU_SIZEID, $menuSizeid, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenuGroupid($menuGroupid = null, $comparison = null)
    {
        if (is_array($menuGroupid)) {
            $useMinMax = false;
            if (isset($menuGroupid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_MENU_GROUPID, $menuGroupid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($menuGroupid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_MENU_GROUPID, $menuGroupid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_MENU_GROUPID, $menuGroupid, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_AMOUNT, $amount, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterBySinglePrice($singlePrice = null, $comparison = null)
    {
        if (is_array($singlePrice)) {
            $useMinMax = false;
            if (isset($singlePrice['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE, $singlePrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($singlePrice['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE, $singlePrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE, $singlePrice, $comparison);
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
     * @see       filterByUser()
     *
     * @param     mixed $singlePriceModifiedByUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterBySinglePriceModifiedByUserid($singlePriceModifiedByUserid = null, $comparison = null)
    {
        if (is_array($singlePriceModifiedByUserid)) {
            $useMinMax = false;
            if (isset($singlePriceModifiedByUserid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $singlePriceModifiedByUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($singlePriceModifiedByUserid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $singlePriceModifiedByUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $singlePriceModifiedByUserid, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByExtraDetail($extraDetail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extraDetail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_EXTRA_DETAIL, $extraDetail, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByAvailabilityid($availabilityid = null, $comparison = null)
    {
        if (is_array($availabilityid)) {
            $useMinMax = false;
            if (isset($availabilityid['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITYID, $availabilityid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityid['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITYID, $availabilityid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITYID, $availabilityid, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByAvailabilityAmount($availabilityAmount = null, $comparison = null)
    {
        if (is_array($availabilityAmount)) {
            $useMinMax = false;
            if (isset($availabilityAmount['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityAmount['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITY_AMOUNT, $availabilityAmount, $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByVerified($verified = null, $comparison = null)
    {
        if (is_string($verified)) {
            $verified = in_array(strtolower($verified), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_VERIFIED, $verified, $comparison);
    }

    /**
     * Filter the query on the distribution_finished column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributionFinished('2011-03-14'); // WHERE distribution_finished = '2011-03-14'
     * $query->filterByDistributionFinished('now'); // WHERE distribution_finished = '2011-03-14'
     * $query->filterByDistributionFinished(array('max' => 'yesterday')); // WHERE distribution_finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $distributionFinished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByDistributionFinished($distributionFinished = null, $comparison = null)
    {
        if (is_array($distributionFinished)) {
            $useMinMax = false;
            if (isset($distributionFinished['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_DISTRIBUTION_FINISHED, $distributionFinished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($distributionFinished['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_DISTRIBUTION_FINISHED, $distributionFinished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_DISTRIBUTION_FINISHED, $distributionFinished, $comparison);
    }

    /**
     * Filter the query on the invoice_finished column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceFinished('2011-03-14'); // WHERE invoice_finished = '2011-03-14'
     * $query->filterByInvoiceFinished('now'); // WHERE invoice_finished = '2011-03-14'
     * $query->filterByInvoiceFinished(array('max' => 'yesterday')); // WHERE invoice_finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $invoiceFinished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByInvoiceFinished($invoiceFinished = null, $comparison = null)
    {
        if (is_array($invoiceFinished)) {
            $useMinMax = false;
            if (isset($invoiceFinished['min'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_INVOICE_FINISHED, $invoiceFinished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceFinished['max'])) {
                $this->addUsingAlias(OrderDetailTableMap::COL_INVOICE_FINISHED, $invoiceFinished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderDetailTableMap::COL_INVOICE_FINISHED, $invoiceFinished, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\Availability object
     *
     * @param \API\Models\ORM\Menu\Availability|ObjectCollection $availability The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByAvailability($availability, $comparison = null)
    {
        if ($availability instanceof \API\Models\ORM\Menu\Availability) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITYID, $availability->getAvailabilityid(), $comparison);
        } elseif ($availability instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_AVAILABILITYID, $availability->toKeyValue('PrimaryKey', 'Availabilityid'), $comparison);
        } else {
            throw new PropelException('filterByAvailability() only accepts arguments of type \API\Models\ORM\Menu\Availability or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Availability relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Menu\AvailabilityQuery A secondary query class using the current class as primary query
     */
    public function useAvailabilityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAvailability($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Availability', '\API\Models\ORM\Menu\AvailabilityQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\MenuGroup object
     *
     * @param \API\Models\ORM\Menu\MenuGroup|ObjectCollection $menuGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenuGroup($menuGroup, $comparison = null)
    {
        if ($menuGroup instanceof \API\Models\ORM\Menu\MenuGroup) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_MENU_GROUPID, $menuGroup->getMenuGroupid(), $comparison);
        } elseif ($menuGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_MENU_GROUPID, $menuGroup->toKeyValue('PrimaryKey', 'MenuGroupid'), $comparison);
        } else {
            throw new PropelException('filterByMenuGroup() only accepts arguments of type \API\Models\ORM\Menu\MenuGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MenuGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinMenuGroup($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return \API\Models\ORM\Menu\MenuGroupQuery A secondary query class using the current class as primary query
     */
    public function useMenuGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMenuGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MenuGroup', '\API\Models\ORM\Menu\MenuGroupQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Menu\MenuSize object
     *
     * @param \API\Models\ORM\Menu\MenuSize|ObjectCollection $menuSize The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenuSize($menuSize, $comparison = null)
    {
        if ($menuSize instanceof \API\Models\ORM\Menu\MenuSize) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_MENU_SIZEID, $menuSize->getMenuSizeid(), $comparison);
        } elseif ($menuSize instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_MENU_SIZEID, $menuSize->toKeyValue('PrimaryKey', 'MenuSizeid'), $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinMenuSize($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useMenuSizeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenu($menu, $comparison = null)
    {
        if ($menu instanceof \API\Models\ORM\Menu\Menu) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_MENUID, $menu->getMenuid(), $comparison);
        } elseif ($menu instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_MENUID, $menu->toKeyValue('PrimaryKey', 'Menuid'), $comparison);
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
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinMenu($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useMenuQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMenu($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Menu', '\API\Models\ORM\Menu\MenuQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\Order object
     *
     * @param \API\Models\ORM\Ordering\Order|ObjectCollection $order The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByOrder($order, $comparison = null)
    {
        if ($order instanceof \API\Models\ORM\Ordering\Order) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_ORDERID, $order->getOrderid(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_ORDERID, $order->toKeyValue('PrimaryKey', 'Orderid'), $comparison);
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \API\Models\ORM\Ordering\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Ordering\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\API\Models\ORM\Ordering\OrderQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\User\User object
     *
     * @param \API\Models\ORM\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\ORM\User\User) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_SINGLE_PRICE_MODIFIED_BY_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\ORM\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\ORM\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Invoice\InvoiceItem object
     *
     * @param \API\Models\ORM\Invoice\InvoiceItem|ObjectCollection $invoiceItem the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByInvoiceItem($invoiceItem, $comparison = null)
    {
        if ($invoiceItem instanceof \API\Models\ORM\Invoice\InvoiceItem) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $invoiceItem->getOrderDetailid(), $comparison);
        } elseif ($invoiceItem instanceof ObjectCollection) {
            return $this
                ->useInvoiceItemQuery()
                ->filterByPrimaryKeys($invoiceItem->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceItem() only accepts arguments of type \API\Models\ORM\Invoice\InvoiceItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinInvoiceItem($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceItem');

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
            $this->addJoinObject($join, 'InvoiceItem');
        }

        return $this;
    }

    /**
     * Use the InvoiceItem relation InvoiceItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Invoice\InvoiceItemQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceItemQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInvoiceItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceItem', '\API\Models\ORM\Invoice\InvoiceItemQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\OrderDetailExtra object
     *
     * @param \API\Models\ORM\Ordering\OrderDetailExtra|ObjectCollection $orderDetailExtra the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByOrderDetailExtra($orderDetailExtra, $comparison = null)
    {
        if ($orderDetailExtra instanceof \API\Models\ORM\Ordering\OrderDetailExtra) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderDetailExtra->getOrderDetailid(), $comparison);
        } elseif ($orderDetailExtra instanceof ObjectCollection) {
            return $this
                ->useOrderDetailExtraQuery()
                ->filterByPrimaryKeys($orderDetailExtra->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderDetailExtra() only accepts arguments of type \API\Models\ORM\Ordering\OrderDetailExtra or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetailExtra relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Ordering\OrderDetailExtraQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailExtraQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderDetailExtra($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetailExtra', '\API\Models\ORM\Ordering\OrderDetailExtraQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\OrderDetailMixedWith object
     *
     * @param \API\Models\ORM\Ordering\OrderDetailMixedWith|ObjectCollection $orderDetailMixedWith the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByOrderDetailMixedWith($orderDetailMixedWith, $comparison = null)
    {
        if ($orderDetailMixedWith instanceof \API\Models\ORM\Ordering\OrderDetailMixedWith) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderDetailMixedWith->getOrderDetailid(), $comparison);
        } elseif ($orderDetailMixedWith instanceof ObjectCollection) {
            return $this
                ->useOrderDetailMixedWithQuery()
                ->filterByPrimaryKeys($orderDetailMixedWith->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderDetailMixedWith() only accepts arguments of type \API\Models\ORM\Ordering\OrderDetailMixedWith or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetailMixedWith relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Ordering\OrderDetailMixedWithQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailMixedWithQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderDetailMixedWith($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetailMixedWith', '\API\Models\ORM\Ordering\OrderDetailMixedWithQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\OIP\OrderInProgressRecieved object
     *
     * @param \API\Models\ORM\OIP\OrderInProgressRecieved|ObjectCollection $orderInProgressRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByOrderInProgressRecieved($orderInProgressRecieved, $comparison = null)
    {
        if ($orderInProgressRecieved instanceof \API\Models\ORM\OIP\OrderInProgressRecieved) {
            return $this
                ->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderInProgressRecieved->getOrderDetailid(), $comparison);
        } elseif ($orderInProgressRecieved instanceof ObjectCollection) {
            return $this
                ->useOrderInProgressRecievedQuery()
                ->filterByPrimaryKeys($orderInProgressRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOrderInProgressRecieved() only accepts arguments of type \API\Models\ORM\OIP\OrderInProgressRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderInProgressRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function joinOrderInProgressRecieved($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OrderInProgressRecieved');

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
            $this->addJoinObject($join, 'OrderInProgressRecieved');
        }

        return $this;
    }

    /**
     * Use the OrderInProgressRecieved relation OrderInProgressRecieved object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\OIP\OrderInProgressRecievedQuery A secondary query class using the current class as primary query
     */
    public function useOrderInProgressRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrderInProgressRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderInProgressRecieved', '\API\Models\ORM\OIP\OrderInProgressRecievedQuery');
    }

    /**
     * Filter the query by a related MenuPossibleExtra object
     * using the order_detail_extra table as cross reference
     *
     * @param MenuPossibleExtra $menuPossibleExtra the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderDetailQuery The current query, for fluid interface
     */
    public function filterByMenuPossibleExtra($menuPossibleExtra, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useOrderDetailExtraQuery()
            ->filterByMenuPossibleExtra($menuPossibleExtra, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOrderDetail $orderDetail Object to remove from the list of results
     *
     * @return $this|ChildOrderDetailQuery The current query, for fluid interface
     */
    public function prune($orderDetail = null)
    {
        if ($orderDetail) {
            $this->addUsingAlias(OrderDetailTableMap::COL_ORDER_DETAILID, $orderDetail->getOrderDetailid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the order_detail table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrderDetailTableMap::clearInstancePool();
            OrderDetailTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderDetailTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrderDetailTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            OrderDetailTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            OrderDetailTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // OrderDetailQuery
