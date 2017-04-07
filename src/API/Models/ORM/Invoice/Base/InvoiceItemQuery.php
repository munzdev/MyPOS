<?php

namespace API\Models\ORM\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Invoice\InvoiceItem as ChildInvoiceItem;
use API\Models\ORM\Invoice\InvoiceItemQuery as ChildInvoiceItemQuery;
use API\Models\ORM\Invoice\Map\InvoiceItemTableMap;
use API\Models\ORM\Ordering\OrderDetail;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoice_item' table.
 *
 *
 *
 * @method     ChildInvoiceItemQuery orderByInvoiceItemid($order = Criteria::ASC) Order by the invoice_itemid column
 * @method     ChildInvoiceItemQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildInvoiceItemQuery orderByOrderDetailid($order = Criteria::ASC) Order by the order_detailid column
 * @method     ChildInvoiceItemQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildInvoiceItemQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method     ChildInvoiceItemQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildInvoiceItemQuery orderByTax($order = Criteria::ASC) Order by the tax column
 *
 * @method     ChildInvoiceItemQuery groupByInvoiceItemid() Group by the invoice_itemid column
 * @method     ChildInvoiceItemQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildInvoiceItemQuery groupByOrderDetailid() Group by the order_detailid column
 * @method     ChildInvoiceItemQuery groupByAmount() Group by the amount column
 * @method     ChildInvoiceItemQuery groupByPrice() Group by the price column
 * @method     ChildInvoiceItemQuery groupByDescription() Group by the description column
 * @method     ChildInvoiceItemQuery groupByTax() Group by the tax column
 *
 * @method     ChildInvoiceItemQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvoiceItemQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvoiceItemQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvoiceItemQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvoiceItemQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvoiceItemQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvoiceItemQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method     ChildInvoiceItemQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method     ChildInvoiceItemQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method     ChildInvoiceItemQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method     ChildInvoiceItemQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method     ChildInvoiceItemQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method     ChildInvoiceItemQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method     ChildInvoiceItemQuery leftJoinOrderDetail($relationAlias = null) Adds a LEFT JOIN clause to the query using the OrderDetail relation
 * @method     ChildInvoiceItemQuery rightJoinOrderDetail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OrderDetail relation
 * @method     ChildInvoiceItemQuery innerJoinOrderDetail($relationAlias = null) Adds a INNER JOIN clause to the query using the OrderDetail relation
 *
 * @method     ChildInvoiceItemQuery joinWithOrderDetail($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OrderDetail relation
 *
 * @method     ChildInvoiceItemQuery leftJoinWithOrderDetail() Adds a LEFT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildInvoiceItemQuery rightJoinWithOrderDetail() Adds a RIGHT JOIN clause and with to the query using the OrderDetail relation
 * @method     ChildInvoiceItemQuery innerJoinWithOrderDetail() Adds a INNER JOIN clause and with to the query using the OrderDetail relation
 *
 * @method     \API\Models\ORM\Invoice\InvoiceQuery|\API\Models\ORM\Ordering\OrderDetailQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvoiceItem findOne(ConnectionInterface $con = null) Return the first ChildInvoiceItem matching the query
 * @method     ChildInvoiceItem findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoiceItem matching the query, or a new ChildInvoiceItem object populated from the query conditions when no match is found
 *
 * @method     ChildInvoiceItem findOneByInvoiceItemid(int $invoice_itemid) Return the first ChildInvoiceItem filtered by the invoice_itemid column
 * @method     ChildInvoiceItem findOneByInvoiceid(int $invoiceid) Return the first ChildInvoiceItem filtered by the invoiceid column
 * @method     ChildInvoiceItem findOneByOrderDetailid(int $order_detailid) Return the first ChildInvoiceItem filtered by the order_detailid column
 * @method     ChildInvoiceItem findOneByAmount(int $amount) Return the first ChildInvoiceItem filtered by the amount column
 * @method     ChildInvoiceItem findOneByPrice(string $price) Return the first ChildInvoiceItem filtered by the price column
 * @method     ChildInvoiceItem findOneByDescription(string $description) Return the first ChildInvoiceItem filtered by the description column
 * @method     ChildInvoiceItem findOneByTax(int $tax) Return the first ChildInvoiceItem filtered by the tax column *

 * @method     ChildInvoiceItem requirePk($key, ConnectionInterface $con = null) Return the ChildInvoiceItem by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOne(ConnectionInterface $con = null) Return the first ChildInvoiceItem matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoiceItem requireOneByInvoiceItemid(int $invoice_itemid) Return the first ChildInvoiceItem filtered by the invoice_itemid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOneByInvoiceid(int $invoiceid) Return the first ChildInvoiceItem filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOneByOrderDetailid(int $order_detailid) Return the first ChildInvoiceItem filtered by the order_detailid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOneByAmount(int $amount) Return the first ChildInvoiceItem filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOneByPrice(string $price) Return the first ChildInvoiceItem filtered by the price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOneByDescription(string $description) Return the first ChildInvoiceItem filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceItem requireOneByTax(int $tax) Return the first ChildInvoiceItem filtered by the tax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoiceItem[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoiceItem objects based on current ModelCriteria
 * @method     ChildInvoiceItem[]|ObjectCollection findByInvoiceItemid(int $invoice_itemid) Return ChildInvoiceItem objects filtered by the invoice_itemid column
 * @method     ChildInvoiceItem[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildInvoiceItem objects filtered by the invoiceid column
 * @method     ChildInvoiceItem[]|ObjectCollection findByOrderDetailid(int $order_detailid) Return ChildInvoiceItem objects filtered by the order_detailid column
 * @method     ChildInvoiceItem[]|ObjectCollection findByAmount(int $amount) Return ChildInvoiceItem objects filtered by the amount column
 * @method     ChildInvoiceItem[]|ObjectCollection findByPrice(string $price) Return ChildInvoiceItem objects filtered by the price column
 * @method     ChildInvoiceItem[]|ObjectCollection findByDescription(string $description) Return ChildInvoiceItem objects filtered by the description column
 * @method     ChildInvoiceItem[]|ObjectCollection findByTax(int $tax) Return ChildInvoiceItem objects filtered by the tax column
 * @method     ChildInvoiceItem[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InvoiceItemQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Invoice\Base\InvoiceItemQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Invoice\\InvoiceItem', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoiceItemQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvoiceItemQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoiceItemQuery) {
            return $criteria;
        }
        $query = new ChildInvoiceItemQuery();
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
     * @return ChildInvoiceItem|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoiceItemTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoiceItemTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInvoiceItem A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoice_itemid, invoiceid, order_detailid, amount, price, description, tax FROM invoice_item WHERE invoice_itemid = :p0';
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
            /** @var ChildInvoiceItem $obj */
            $obj = new ChildInvoiceItem();
            $obj->hydrate($row);
            InvoiceItemTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInvoiceItem|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICE_ITEMID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICE_ITEMID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the invoice_itemid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceItemid(1234); // WHERE invoice_itemid = 1234
     * $query->filterByInvoiceItemid(array(12, 34)); // WHERE invoice_itemid IN (12, 34)
     * $query->filterByInvoiceItemid(array('min' => 12)); // WHERE invoice_itemid > 12
     * </code>
     *
     * @param     mixed $invoiceItemid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByInvoiceItemid($invoiceItemid = null, $comparison = null)
    {
        if (is_array($invoiceItemid)) {
            $useMinMax = false;
            if (isset($invoiceItemid['min'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICE_ITEMID, $invoiceItemid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceItemid['max'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICE_ITEMID, $invoiceItemid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICE_ITEMID, $invoiceItemid, $comparison);
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
     * @see       filterByInvoice()
     *
     * @param     mixed $invoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICEID, $invoiceid, $comparison);
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
     * @see       filterByOrderDetail()
     *
     * @param     mixed $orderDetailid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByOrderDetailid($orderDetailid = null, $comparison = null)
    {
        if (is_array($orderDetailid)) {
            $useMinMax = false;
            if (isset($orderDetailid['min'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_ORDER_DETAILID, $orderDetailid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderDetailid['max'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_ORDER_DETAILID, $orderDetailid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_ORDER_DETAILID, $orderDetailid, $comparison);
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
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_AMOUNT, $amount, $comparison);
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
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_PRICE, $price, $comparison);
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
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_DESCRIPTION, $description, $comparison);
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
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByTax($tax = null, $comparison = null)
    {
        if (is_array($tax)) {
            $useMinMax = false;
            if (isset($tax['min'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_TAX, $tax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tax['max'])) {
                $this->addUsingAlias(InvoiceItemTableMap::COL_TAX, $tax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceItemTableMap::COL_TAX, $tax, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Invoice\Invoice object
     *
     * @param \API\Models\ORM\Invoice\Invoice|ObjectCollection $invoice The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\ORM\Invoice\Invoice) {
            return $this
                ->addUsingAlias(InvoiceItemTableMap::COL_INVOICEID, $invoice->getInvoiceid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceItemTableMap::COL_INVOICEID, $invoice->toKeyValue('PrimaryKey', 'Invoiceid'), $comparison);
        } else {
            throw new PropelException('filterByInvoice() only accepts arguments of type \API\Models\ORM\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invoice relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invoice', '\API\Models\ORM\Invoice\InvoiceQuery');
    }

    /**
     * Filter the query by a related \API\Models\ORM\Ordering\OrderDetail object
     *
     * @param \API\Models\ORM\Ordering\OrderDetail|ObjectCollection $orderDetail The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function filterByOrderDetail($orderDetail, $comparison = null)
    {
        if ($orderDetail instanceof \API\Models\ORM\Ordering\OrderDetail) {
            return $this
                ->addUsingAlias(InvoiceItemTableMap::COL_ORDER_DETAILID, $orderDetail->getOrderDetailid(), $comparison);
        } elseif ($orderDetail instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceItemTableMap::COL_ORDER_DETAILID, $orderDetail->toKeyValue('PrimaryKey', 'OrderDetailid'), $comparison);
        } else {
            throw new PropelException('filterByOrderDetail() only accepts arguments of type \API\Models\ORM\Ordering\OrderDetail or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OrderDetail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Ordering\OrderDetailQuery A secondary query class using the current class as primary query
     */
    public function useOrderDetailQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrderDetail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OrderDetail', '\API\Models\ORM\Ordering\OrderDetailQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInvoiceItem $invoiceItem Object to remove from the list of results
     *
     * @return $this|ChildInvoiceItemQuery The current query, for fluid interface
     */
    public function prune($invoiceItem = null)
    {
        if ($invoiceItem) {
            $this->addUsingAlias(InvoiceItemTableMap::COL_INVOICE_ITEMID, $invoiceItem->getInvoiceItemid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoice_item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceItemTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvoiceItemTableMap::clearInstancePool();
            InvoiceItemTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceItemTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoiceItemTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvoiceItemTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvoiceItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InvoiceItemQuery
