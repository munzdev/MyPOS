<?php

namespace Model\Invoice\Base;

use \Exception;
use \PDO;
use Model\Invoice\InvoicesItems as ChildInvoicesItems;
use Model\Invoice\InvoicesItemsQuery as ChildInvoicesItemsQuery;
use Model\Invoice\Map\InvoicesItemsTableMap;
use Model\Ordering\OrdersDetails;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoices_items' table.
 *
 *
 *
 * @method     ChildInvoicesItemsQuery orderByInvoicesItemid($order = Criteria::ASC) Order by the invoices_itemid column
 * @method     ChildInvoicesItemsQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildInvoicesItemsQuery orderByOrdersDetailid($order = Criteria::ASC) Order by the orders_detailid column
 * @method     ChildInvoicesItemsQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildInvoicesItemsQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildInvoicesItemsQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildInvoicesItemsQuery orderByTax($order = Criteria::ASC) Order by the tax column
 *
 * @method     ChildInvoicesItemsQuery groupByInvoicesItemid() Group by the invoices_itemid column
 * @method     ChildInvoicesItemsQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildInvoicesItemsQuery groupByOrdersDetailid() Group by the orders_detailid column
 * @method     ChildInvoicesItemsQuery groupByAmount() Group by the amount column
 * @method     ChildInvoicesItemsQuery groupByPrice() Group by the price column
 * @method     ChildInvoicesItemsQuery groupByDescription() Group by the description column
 * @method     ChildInvoicesItemsQuery groupByTax() Group by the tax column
 *
 * @method     ChildInvoicesItemsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvoicesItemsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvoicesItemsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvoicesItemsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvoicesItemsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvoicesItemsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvoicesItemsQuery leftJoinInvoices($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoices relation
 * @method     ChildInvoicesItemsQuery rightJoinInvoices($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoices relation
 * @method     ChildInvoicesItemsQuery innerJoinInvoices($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoices relation
 *
 * @method     ChildInvoicesItemsQuery joinWithInvoices($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoices relation
 *
 * @method     ChildInvoicesItemsQuery leftJoinWithInvoices() Adds a LEFT JOIN clause and with to the query using the Invoices relation
 * @method     ChildInvoicesItemsQuery rightJoinWithInvoices() Adds a RIGHT JOIN clause and with to the query using the Invoices relation
 * @method     ChildInvoicesItemsQuery innerJoinWithInvoices() Adds a INNER JOIN clause and with to the query using the Invoices relation
 *
 * @method     ChildInvoicesItemsQuery leftJoinOrdersDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildInvoicesItemsQuery rightJoinOrdersDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrdersDetails relation
 * @method     ChildInvoicesItemsQuery innerJoinOrdersDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the OrdersDetails relation
 *
 * @method     ChildInvoicesItemsQuery joinWithOrdersDetails($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrdersDetails relation
 *
 * @method     ChildInvoicesItemsQuery leftJoinWithOrdersDetails() Adds a LEFT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildInvoicesItemsQuery rightJoinWithOrdersDetails() Adds a RIGHT JOIN clause and with to the query using the OrdersDetails relation
 * @method     ChildInvoicesItemsQuery innerJoinWithOrdersDetails() Adds a INNER JOIN clause and with to the query using the OrdersDetails relation
 *
 * @method     \Model\Invoice\InvoicesQuery|\Model\Ordering\OrdersDetailsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvoicesItems findOne(ConnectionInterface $con = null) Return the first ChildInvoicesItems matching the query
 * @method     ChildInvoicesItems findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoicesItems matching the query, or a new ChildInvoicesItems object populated from the query conditions when no match is found
 *
 * @method     ChildInvoicesItems findOneByInvoicesItemid(int $invoices_itemid) Return the first ChildInvoicesItems filtered by the invoices_itemid column
 * @method     ChildInvoicesItems findOneByInvoiceid(int $invoiceid) Return the first ChildInvoicesItems filtered by the invoiceid column
 * @method     ChildInvoicesItems findOneByOrdersDetailid(int $orders_detailid) Return the first ChildInvoicesItems filtered by the orders_detailid column
 * @method     ChildInvoicesItems findOneByAmount(int $amount) Return the first ChildInvoicesItems filtered by the amount column
 * @method     ChildInvoicesItems findOneByPrice(string $price) Return the first ChildInvoicesItems filtered by the price column
 * @method     ChildInvoicesItems findOneByDescription(string $description) Return the first ChildInvoicesItems filtered by the description column
 * @method     ChildInvoicesItems findOneByTax(int $tax) Return the first ChildInvoicesItems filtered by the tax column *

 * @method     ChildInvoicesItems requirePk($key, ConnectionInterface $con = null) Return the ChildInvoicesItems by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOne(ConnectionInterface $con = null) Return the first ChildInvoicesItems matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoicesItems requireOneByInvoicesItemid(int $invoices_itemid) Return the first ChildInvoicesItems filtered by the invoices_itemid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOneByInvoiceid(int $invoiceid) Return the first ChildInvoicesItems filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOneByOrdersDetailid(int $orders_detailid) Return the first ChildInvoicesItems filtered by the orders_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOneByAmount(int $amount) Return the first ChildInvoicesItems filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOneByPrice(string $price) Return the first ChildInvoicesItems filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOneByDescription(string $description) Return the first ChildInvoicesItems filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoicesItems requireOneByTax(int $tax) Return the first ChildInvoicesItems filtered by the tax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoicesItems[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoicesItems objects based on current ModelCriteria
 * @method     ChildInvoicesItems[]|ObjectCollection findByInvoicesItemid(int $invoices_itemid) Return ChildInvoicesItems objects filtered by the invoices_itemid column
 * @method     ChildInvoicesItems[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildInvoicesItems objects filtered by the invoiceid column
 * @method     ChildInvoicesItems[]|ObjectCollection findByOrdersDetailid(int $orders_detailid) Return ChildInvoicesItems objects filtered by the orders_detailid column
 * @method     ChildInvoicesItems[]|ObjectCollection findByAmount(int $amount) Return ChildInvoicesItems objects filtered by the amount column
 * @method     ChildInvoicesItems[]|ObjectCollection findByPrice(string $price) Return ChildInvoicesItems objects filtered by the price column
 * @method     ChildInvoicesItems[]|ObjectCollection findByDescription(string $description) Return ChildInvoicesItems objects filtered by the description column
 * @method     ChildInvoicesItems[]|ObjectCollection findByTax(int $tax) Return ChildInvoicesItems objects filtered by the tax column
 * @method     ChildInvoicesItems[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InvoicesItemsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Invoice\Base\InvoicesItemsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Invoice\\InvoicesItems', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoicesItemsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvoicesItemsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoicesItemsQuery) {
            return $criteria;
        }
        $query = new ChildInvoicesItemsQuery();
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
     * @param array[$invoices_itemid, $invoiceid, $orders_detailid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildInvoicesItems|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoicesItemsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoicesItemsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildInvoicesItems A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoices_itemid, invoiceid, orders_detailid, amount, price, description, tax FROM invoices_items WHERE invoices_itemid = :p0 AND invoiceid = :p1 AND orders_detailid = :p2';
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
            /** @var ChildInvoicesItems $obj */
            $obj = new ChildInvoicesItems();
            $obj->hydrate($row);
            InvoicesItemsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildInvoicesItems|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICES_ITEMID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICEID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(InvoicesItemsTableMap::COL_INVOICES_ITEMID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(InvoicesItemsTableMap::COL_INVOICEID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the invoices_itemid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoicesItemid(1234); // WHERE invoices_itemid = 1234
     * $query->filterByInvoicesItemid(array(12, 34)); // WHERE invoices_itemid IN (12, 34)
     * $query->filterByInvoicesItemid(array('min' => 12)); // WHERE invoices_itemid > 12
     * </code>
     *
     * @param     mixed $invoicesItemid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByInvoicesItemid($invoicesItemid = null, $comparison = null)
    {
        if (is_array($invoicesItemid)) {
            $useMinMax = false;
            if (isset($invoicesItemid['min'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICES_ITEMID, $invoicesItemid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoicesItemid['max'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICES_ITEMID, $invoicesItemid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICES_ITEMID, $invoicesItemid, $comparison);
    }

    /**
     * Filter the query on the invoiceid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceid(1234); // WHERE invoiceid = 1234
     * $query->filterByInvoiceid(array(12, 34)); // WHERE invoiceid IN (12, 34)
     * $query->filterByInvoiceid(array('min' => 12)); // WHERE invoiceid > 12
     * </code>
     *
     * @see       filterByInvoices()
     *
     * @param     mixed $invoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_INVOICEID, $invoiceid, $comparison);
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
     * @see       filterByOrdersDetails()
     *
     * @param     mixed $ordersDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByOrdersDetailid($ordersDetailid = null, $comparison = null)
    {
        if (is_array($ordersDetailid)) {
            $useMinMax = false;
            if (isset($ordersDetailid['min'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $ordersDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ordersDetailid['max'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $ordersDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $ordersDetailid, $comparison);
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
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_AMOUNT, $amount, $comparison);
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
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the tax column
     *
     * Example usage:
     * <code>
     * $query->filterByTax(1234); // WHERE tax = 1234
     * $query->filterByTax(array(12, 34)); // WHERE tax IN (12, 34)
     * $query->filterByTax(array('min' => 12)); // WHERE tax > 12
     * </code>
     *
     * @param     mixed $tax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByTax($tax = null, $comparison = null)
    {
        if (is_array($tax)) {
            $useMinMax = false;
            if (isset($tax['min'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_TAX, $tax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tax['max'])) {
                $this->addUsingAlias(InvoicesItemsTableMap::COL_TAX, $tax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesItemsTableMap::COL_TAX, $tax, $comparison);
    }

    /**
     * Filter the query by a related \Model\Invoice\Invoices object
     *
     * @param \Model\Invoice\Invoices|ObjectCollection $invoices The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByInvoices($invoices, $comparison = null)
    {
        if ($invoices instanceof \Model\Invoice\Invoices) {
            return $this
                ->addUsingAlias(InvoicesItemsTableMap::COL_INVOICEID, $invoices->getInvoiceid(), $comparison);
        } elseif ($invoices instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoicesItemsTableMap::COL_INVOICEID, $invoices->toKeyValue('Invoiceid', 'Invoiceid'), $comparison);
        } else {
            throw new PropelException('filterByInvoices() only accepts arguments of type \Model\Invoice\Invoices or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invoices relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
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
     * @return \Model\Invoice\InvoicesQuery A secondary query class using the current class as primary query
     */
    public function useInvoicesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoices($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoices', '\Model\Invoice\InvoicesQuery');
    }

    /**
     * Filter the query by a related \Model\Ordering\OrdersDetails object
     *
     * @param \Model\Ordering\OrdersDetails|ObjectCollection $ordersDetails The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function filterByOrdersDetails($ordersDetails, $comparison = null)
    {
        if ($ordersDetails instanceof \Model\Ordering\OrdersDetails) {
            return $this
                ->addUsingAlias(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $ordersDetails->getOrdersDetailid(), $comparison);
        } elseif ($ordersDetails instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoicesItemsTableMap::COL_ORDERS_DETAILID, $ordersDetails->toKeyValue('OrdersDetailid', 'OrdersDetailid'), $comparison);
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
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function joinOrdersDetails($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useOrdersDetailsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrdersDetails($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrdersDetails', '\Model\Ordering\OrdersDetailsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInvoicesItems $invoicesItems Object to remove from the list of results
     *
     * @return $this|ChildInvoicesItemsQuery The current query, for fluid interface
     */
    public function prune($invoicesItems = null)
    {
        if ($invoicesItems) {
            $this->addCond('pruneCond0', $this->getAliasedColName(InvoicesItemsTableMap::COL_INVOICES_ITEMID), $invoicesItems->getInvoicesItemid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(InvoicesItemsTableMap::COL_INVOICEID), $invoicesItems->getInvoiceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(InvoicesItemsTableMap::COL_ORDERS_DETAILID), $invoicesItems->getOrdersDetailid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoices_items table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesItemsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvoicesItemsTableMap::clearInstancePool();
            InvoicesItemsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesItemsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoicesItemsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvoicesItemsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvoicesItemsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InvoicesItemsQuery
