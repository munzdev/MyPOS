<?php

namespace API\Models\ORM\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Invoice\InvoiceWarning as ChildInvoiceWarning;
use API\Models\ORM\Invoice\InvoiceWarningQuery as ChildInvoiceWarningQuery;
use API\Models\ORM\Invoice\Map\InvoiceWarningTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoice_warning' table.
 *
 * 
 *
 * @method     ChildInvoiceWarningQuery orderByInvoiceWarningid($order = Criteria::ASC) Order by the invoice_warningid column
 * @method     ChildInvoiceWarningQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildInvoiceWarningQuery orderByInvoiceWarningTypeid($order = Criteria::ASC) Order by the invoice_warning_typeid column
 * @method     ChildInvoiceWarningQuery orderByWarningDate($order = Criteria::ASC) Order by the warning_date column
 * @method     ChildInvoiceWarningQuery orderByMaturityDate($order = Criteria::ASC) Order by the maturity_date column
 * @method     ChildInvoiceWarningQuery orderByWarningValue($order = Criteria::ASC) Order by the warning_value column
 *
 * @method     ChildInvoiceWarningQuery groupByInvoiceWarningid() Group by the invoice_warningid column
 * @method     ChildInvoiceWarningQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildInvoiceWarningQuery groupByInvoiceWarningTypeid() Group by the invoice_warning_typeid column
 * @method     ChildInvoiceWarningQuery groupByWarningDate() Group by the warning_date column
 * @method     ChildInvoiceWarningQuery groupByMaturityDate() Group by the maturity_date column
 * @method     ChildInvoiceWarningQuery groupByWarningValue() Group by the warning_value column
 *
 * @method     ChildInvoiceWarningQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvoiceWarningQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvoiceWarningQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvoiceWarningQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvoiceWarningQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvoiceWarningQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvoiceWarningQuery leftJoinInvoice($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invoice relation
 * @method     ChildInvoiceWarningQuery rightJoinInvoice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invoice relation
 * @method     ChildInvoiceWarningQuery innerJoinInvoice($relationAlias = null) Adds a INNER JOIN clause to the query using the Invoice relation
 *
 * @method     ChildInvoiceWarningQuery joinWithInvoice($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invoice relation
 *
 * @method     ChildInvoiceWarningQuery leftJoinWithInvoice() Adds a LEFT JOIN clause and with to the query using the Invoice relation
 * @method     ChildInvoiceWarningQuery rightJoinWithInvoice() Adds a RIGHT JOIN clause and with to the query using the Invoice relation
 * @method     ChildInvoiceWarningQuery innerJoinWithInvoice() Adds a INNER JOIN clause and with to the query using the Invoice relation
 *
 * @method     ChildInvoiceWarningQuery leftJoinInvoiceWarningType($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceWarningType relation
 * @method     ChildInvoiceWarningQuery rightJoinInvoiceWarningType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceWarningType relation
 * @method     ChildInvoiceWarningQuery innerJoinInvoiceWarningType($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceWarningType relation
 *
 * @method     ChildInvoiceWarningQuery joinWithInvoiceWarningType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceWarningType relation
 *
 * @method     ChildInvoiceWarningQuery leftJoinWithInvoiceWarningType() Adds a LEFT JOIN clause and with to the query using the InvoiceWarningType relation
 * @method     ChildInvoiceWarningQuery rightJoinWithInvoiceWarningType() Adds a RIGHT JOIN clause and with to the query using the InvoiceWarningType relation
 * @method     ChildInvoiceWarningQuery innerJoinWithInvoiceWarningType() Adds a INNER JOIN clause and with to the query using the InvoiceWarningType relation
 *
 * @method     \API\Models\ORM\Invoice\InvoiceQuery|\API\Models\ORM\Invoice\InvoiceWarningTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvoiceWarning findOne(ConnectionInterface $con = null) Return the first ChildInvoiceWarning matching the query
 * @method     ChildInvoiceWarning findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoiceWarning matching the query, or a new ChildInvoiceWarning object populated from the query conditions when no match is found
 *
 * @method     ChildInvoiceWarning findOneByInvoiceWarningid(int $invoice_warningid) Return the first ChildInvoiceWarning filtered by the invoice_warningid column
 * @method     ChildInvoiceWarning findOneByInvoiceid(int $invoiceid) Return the first ChildInvoiceWarning filtered by the invoiceid column
 * @method     ChildInvoiceWarning findOneByInvoiceWarningTypeid(int $invoice_warning_typeid) Return the first ChildInvoiceWarning filtered by the invoice_warning_typeid column
 * @method     ChildInvoiceWarning findOneByWarningDate(string $warning_date) Return the first ChildInvoiceWarning filtered by the warning_date column
 * @method     ChildInvoiceWarning findOneByMaturityDate(string $maturity_date) Return the first ChildInvoiceWarning filtered by the maturity_date column
 * @method     ChildInvoiceWarning findOneByWarningValue(string $warning_value) Return the first ChildInvoiceWarning filtered by the warning_value column *

 * @method     ChildInvoiceWarning requirePk($key, ConnectionInterface $con = null) Return the ChildInvoiceWarning by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceWarning requireOne(ConnectionInterface $con = null) Return the first ChildInvoiceWarning matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoiceWarning requireOneByInvoiceWarningid(int $invoice_warningid) Return the first ChildInvoiceWarning filtered by the invoice_warningid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceWarning requireOneByInvoiceid(int $invoiceid) Return the first ChildInvoiceWarning filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceWarning requireOneByInvoiceWarningTypeid(int $invoice_warning_typeid) Return the first ChildInvoiceWarning filtered by the invoice_warning_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceWarning requireOneByWarningDate(string $warning_date) Return the first ChildInvoiceWarning filtered by the warning_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceWarning requireOneByMaturityDate(string $maturity_date) Return the first ChildInvoiceWarning filtered by the maturity_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoiceWarning requireOneByWarningValue(string $warning_value) Return the first ChildInvoiceWarning filtered by the warning_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoiceWarning[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoiceWarning objects based on current ModelCriteria
 * @method     ChildInvoiceWarning[]|ObjectCollection findByInvoiceWarningid(int $invoice_warningid) Return ChildInvoiceWarning objects filtered by the invoice_warningid column
 * @method     ChildInvoiceWarning[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildInvoiceWarning objects filtered by the invoiceid column
 * @method     ChildInvoiceWarning[]|ObjectCollection findByInvoiceWarningTypeid(int $invoice_warning_typeid) Return ChildInvoiceWarning objects filtered by the invoice_warning_typeid column
 * @method     ChildInvoiceWarning[]|ObjectCollection findByWarningDate(string $warning_date) Return ChildInvoiceWarning objects filtered by the warning_date column
 * @method     ChildInvoiceWarning[]|ObjectCollection findByMaturityDate(string $maturity_date) Return ChildInvoiceWarning objects filtered by the maturity_date column
 * @method     ChildInvoiceWarning[]|ObjectCollection findByWarningValue(string $warning_value) Return ChildInvoiceWarning objects filtered by the warning_value column
 * @method     ChildInvoiceWarning[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InvoiceWarningQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Invoice\Base\InvoiceWarningQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Invoice\\InvoiceWarning', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoiceWarningQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvoiceWarningQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoiceWarningQuery) {
            return $criteria;
        }
        $query = new ChildInvoiceWarningQuery();
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
     * @return ChildInvoiceWarning|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoiceWarningTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoiceWarningTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInvoiceWarning A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoice_warningid, invoiceid, invoice_warning_typeid, warning_date, maturity_date, warning_value FROM invoice_warning WHERE invoice_warningid = :p0';
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
            /** @var ChildInvoiceWarning $obj */
            $obj = new ChildInvoiceWarning();
            $obj->hydrate($row);
            InvoiceWarningTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInvoiceWarning|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNINGID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNINGID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the invoice_warningid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceWarningid(1234); // WHERE invoice_warningid = 1234
     * $query->filterByInvoiceWarningid(array(12, 34)); // WHERE invoice_warningid IN (12, 34)
     * $query->filterByInvoiceWarningid(array('min' => 12)); // WHERE invoice_warningid > 12
     * </code>
     *
     * @param     mixed $invoiceWarningid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByInvoiceWarningid($invoiceWarningid = null, $comparison = null)
    {
        if (is_array($invoiceWarningid)) {
            $useMinMax = false;
            if (isset($invoiceWarningid['min'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNINGID, $invoiceWarningid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceWarningid['max'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNINGID, $invoiceWarningid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNINGID, $invoiceWarningid, $comparison);
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
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICEID, $invoiceid, $comparison);
    }

    /**
     * Filter the query on the invoice_warning_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceWarningTypeid(1234); // WHERE invoice_warning_typeid = 1234
     * $query->filterByInvoiceWarningTypeid(array(12, 34)); // WHERE invoice_warning_typeid IN (12, 34)
     * $query->filterByInvoiceWarningTypeid(array('min' => 12)); // WHERE invoice_warning_typeid > 12
     * </code>
     *
     * @see       filterByInvoiceWarningType()
     *
     * @param     mixed $invoiceWarningTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByInvoiceWarningTypeid($invoiceWarningTypeid = null, $comparison = null)
    {
        if (is_array($invoiceWarningTypeid)) {
            $useMinMax = false;
            if (isset($invoiceWarningTypeid['min'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceWarningTypeid['max'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningTypeid, $comparison);
    }

    /**
     * Filter the query on the warning_date column
     *
     * Example usage:
     * <code>
     * $query->filterByWarningDate('2011-03-14'); // WHERE warning_date = '2011-03-14'
     * $query->filterByWarningDate('now'); // WHERE warning_date = '2011-03-14'
     * $query->filterByWarningDate(array('max' => 'yesterday')); // WHERE warning_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $warningDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByWarningDate($warningDate = null, $comparison = null)
    {
        if (is_array($warningDate)) {
            $useMinMax = false;
            if (isset($warningDate['min'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_WARNING_DATE, $warningDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($warningDate['max'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_WARNING_DATE, $warningDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_WARNING_DATE, $warningDate, $comparison);
    }

    /**
     * Filter the query on the maturity_date column
     *
     * Example usage:
     * <code>
     * $query->filterByMaturityDate('2011-03-14'); // WHERE maturity_date = '2011-03-14'
     * $query->filterByMaturityDate('now'); // WHERE maturity_date = '2011-03-14'
     * $query->filterByMaturityDate(array('max' => 'yesterday')); // WHERE maturity_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $maturityDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByMaturityDate($maturityDate = null, $comparison = null)
    {
        if (is_array($maturityDate)) {
            $useMinMax = false;
            if (isset($maturityDate['min'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_MATURITY_DATE, $maturityDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maturityDate['max'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_MATURITY_DATE, $maturityDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_MATURITY_DATE, $maturityDate, $comparison);
    }

    /**
     * Filter the query on the warning_value column
     *
     * Example usage:
     * <code>
     * $query->filterByWarningValue(1234); // WHERE warning_value = 1234
     * $query->filterByWarningValue(array(12, 34)); // WHERE warning_value IN (12, 34)
     * $query->filterByWarningValue(array('min' => 12)); // WHERE warning_value > 12
     * </code>
     *
     * @param     mixed $warningValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByWarningValue($warningValue = null, $comparison = null)
    {
        if (is_array($warningValue)) {
            $useMinMax = false;
            if (isset($warningValue['min'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_WARNING_VALUE, $warningValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($warningValue['max'])) {
                $this->addUsingAlias(InvoiceWarningTableMap::COL_WARNING_VALUE, $warningValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceWarningTableMap::COL_WARNING_VALUE, $warningValue, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Invoice\Invoice object
     *
     * @param \API\Models\ORM\Invoice\Invoice|ObjectCollection $invoice The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\ORM\Invoice\Invoice) {
            return $this
                ->addUsingAlias(InvoiceWarningTableMap::COL_INVOICEID, $invoice->getInvoiceid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceWarningTableMap::COL_INVOICEID, $invoice->toKeyValue('PrimaryKey', 'Invoiceid'), $comparison);
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
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
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
     * Filter the query by a related \API\Models\ORM\Invoice\InvoiceWarningType object
     *
     * @param \API\Models\ORM\Invoice\InvoiceWarningType|ObjectCollection $invoiceWarningType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function filterByInvoiceWarningType($invoiceWarningType, $comparison = null)
    {
        if ($invoiceWarningType instanceof \API\Models\ORM\Invoice\InvoiceWarningType) {
            return $this
                ->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningType->getInvoiceWarningTypeid(), $comparison);
        } elseif ($invoiceWarningType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNING_TYPEID, $invoiceWarningType->toKeyValue('PrimaryKey', 'InvoiceWarningTypeid'), $comparison);
        } else {
            throw new PropelException('filterByInvoiceWarningType() only accepts arguments of type \API\Models\ORM\Invoice\InvoiceWarningType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceWarningType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function joinInvoiceWarningType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceWarningType');

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
            $this->addJoinObject($join, 'InvoiceWarningType');
        }

        return $this;
    }

    /**
     * Use the InvoiceWarningType relation InvoiceWarningType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\ORM\Invoice\InvoiceWarningTypeQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceWarningTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceWarningType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceWarningType', '\API\Models\ORM\Invoice\InvoiceWarningTypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInvoiceWarning $invoiceWarning Object to remove from the list of results
     *
     * @return $this|ChildInvoiceWarningQuery The current query, for fluid interface
     */
    public function prune($invoiceWarning = null)
    {
        if ($invoiceWarning) {
            $this->addUsingAlias(InvoiceWarningTableMap::COL_INVOICE_WARNINGID, $invoiceWarning->getInvoiceWarningid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoice_warning table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceWarningTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvoiceWarningTableMap::clearInstancePool();
            InvoiceWarningTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceWarningTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoiceWarningTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            InvoiceWarningTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            InvoiceWarningTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InvoiceWarningQuery
