<?php

namespace API\Models\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\Event\EventBankinformation;
use API\Models\Event\EventContact;
use API\Models\Invoice\Invoice as ChildInvoice;
use API\Models\Invoice\InvoiceQuery as ChildInvoiceQuery;
use API\Models\Invoice\Map\InvoiceTableMap;
use API\Models\Payment\PaymentRecieved;
use API\Models\User\User;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoice' table.
 *
 *
 *
 * @method     ChildInvoiceQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildInvoiceQuery orderByInvoiceTypeid($order = Criteria::ASC) Order by the invoice_typeid column
 * @method     ChildInvoiceQuery orderByEventContactid($order = Criteria::ASC) Order by the event_contactid column
 * @method     ChildInvoiceQuery orderByUserid($order = Criteria::ASC) Order by the userid column
 * @method     ChildInvoiceQuery orderByEventBankinformationid($order = Criteria::ASC) Order by the event_bankinformationid column
 * @method     ChildInvoiceQuery orderByCustomerEventContactid($order = Criteria::ASC) Order by the customer_event_contactid column
 * @method     ChildInvoiceQuery orderByCanceledInvoiceid($order = Criteria::ASC) Order by the canceled_invoiceid column
 * @method     ChildInvoiceQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildInvoiceQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildInvoiceQuery orderByMaturityDate($order = Criteria::ASC) Order by the maturity_date column
 * @method     ChildInvoiceQuery orderByPaymentFinished($order = Criteria::ASC) Order by the payment_finished column
 * @method     ChildInvoiceQuery orderByAmountRecieved($order = Criteria::ASC) Order by the amount_recieved column
 *
 * @method     ChildInvoiceQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildInvoiceQuery groupByInvoiceTypeid() Group by the invoice_typeid column
 * @method     ChildInvoiceQuery groupByEventContactid() Group by the event_contactid column
 * @method     ChildInvoiceQuery groupByUserid() Group by the userid column
 * @method     ChildInvoiceQuery groupByEventBankinformationid() Group by the event_bankinformationid column
 * @method     ChildInvoiceQuery groupByCustomerEventContactid() Group by the customer_event_contactid column
 * @method     ChildInvoiceQuery groupByCanceledInvoiceid() Group by the canceled_invoiceid column
 * @method     ChildInvoiceQuery groupByDate() Group by the date column
 * @method     ChildInvoiceQuery groupByAmount() Group by the amount column
 * @method     ChildInvoiceQuery groupByMaturityDate() Group by the maturity_date column
 * @method     ChildInvoiceQuery groupByPaymentFinished() Group by the payment_finished column
 * @method     ChildInvoiceQuery groupByAmountRecieved() Group by the amount_recieved column
 *
 * @method     ChildInvoiceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvoiceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvoiceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvoiceQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvoiceQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvoiceQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvoiceQuery leftJoinEventContactRelatedByCustomerEventContactid($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventContactRelatedByCustomerEventContactid relation
 * @method     ChildInvoiceQuery rightJoinEventContactRelatedByCustomerEventContactid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventContactRelatedByCustomerEventContactid relation
 * @method     ChildInvoiceQuery innerJoinEventContactRelatedByCustomerEventContactid($relationAlias = null) Adds a INNER JOIN clause to the query using the EventContactRelatedByCustomerEventContactid relation
 *
 * @method     ChildInvoiceQuery joinWithEventContactRelatedByCustomerEventContactid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventContactRelatedByCustomerEventContactid relation
 *
 * @method     ChildInvoiceQuery leftJoinWithEventContactRelatedByCustomerEventContactid() Adds a LEFT JOIN clause and with to the query using the EventContactRelatedByCustomerEventContactid relation
 * @method     ChildInvoiceQuery rightJoinWithEventContactRelatedByCustomerEventContactid() Adds a RIGHT JOIN clause and with to the query using the EventContactRelatedByCustomerEventContactid relation
 * @method     ChildInvoiceQuery innerJoinWithEventContactRelatedByCustomerEventContactid() Adds a INNER JOIN clause and with to the query using the EventContactRelatedByCustomerEventContactid relation
 *
 * @method     ChildInvoiceQuery leftJoinEventBankinformation($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventBankinformation relation
 * @method     ChildInvoiceQuery rightJoinEventBankinformation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventBankinformation relation
 * @method     ChildInvoiceQuery innerJoinEventBankinformation($relationAlias = null) Adds a INNER JOIN clause to the query using the EventBankinformation relation
 *
 * @method     ChildInvoiceQuery joinWithEventBankinformation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventBankinformation relation
 *
 * @method     ChildInvoiceQuery leftJoinWithEventBankinformation() Adds a LEFT JOIN clause and with to the query using the EventBankinformation relation
 * @method     ChildInvoiceQuery rightJoinWithEventBankinformation() Adds a RIGHT JOIN clause and with to the query using the EventBankinformation relation
 * @method     ChildInvoiceQuery innerJoinWithEventBankinformation() Adds a INNER JOIN clause and with to the query using the EventBankinformation relation
 *
 * @method     ChildInvoiceQuery leftJoinEventContactRelatedByEventContactid($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventContactRelatedByEventContactid relation
 * @method     ChildInvoiceQuery rightJoinEventContactRelatedByEventContactid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventContactRelatedByEventContactid relation
 * @method     ChildInvoiceQuery innerJoinEventContactRelatedByEventContactid($relationAlias = null) Adds a INNER JOIN clause to the query using the EventContactRelatedByEventContactid relation
 *
 * @method     ChildInvoiceQuery joinWithEventContactRelatedByEventContactid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventContactRelatedByEventContactid relation
 *
 * @method     ChildInvoiceQuery leftJoinWithEventContactRelatedByEventContactid() Adds a LEFT JOIN clause and with to the query using the EventContactRelatedByEventContactid relation
 * @method     ChildInvoiceQuery rightJoinWithEventContactRelatedByEventContactid() Adds a RIGHT JOIN clause and with to the query using the EventContactRelatedByEventContactid relation
 * @method     ChildInvoiceQuery innerJoinWithEventContactRelatedByEventContactid() Adds a INNER JOIN clause and with to the query using the EventContactRelatedByEventContactid relation
 *
 * @method     ChildInvoiceQuery leftJoinInvoiceRelatedByCanceledInvoiceid($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceRelatedByCanceledInvoiceid relation
 * @method     ChildInvoiceQuery rightJoinInvoiceRelatedByCanceledInvoiceid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceRelatedByCanceledInvoiceid relation
 * @method     ChildInvoiceQuery innerJoinInvoiceRelatedByCanceledInvoiceid($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceRelatedByCanceledInvoiceid relation
 *
 * @method     ChildInvoiceQuery joinWithInvoiceRelatedByCanceledInvoiceid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceRelatedByCanceledInvoiceid relation
 *
 * @method     ChildInvoiceQuery leftJoinWithInvoiceRelatedByCanceledInvoiceid() Adds a LEFT JOIN clause and with to the query using the InvoiceRelatedByCanceledInvoiceid relation
 * @method     ChildInvoiceQuery rightJoinWithInvoiceRelatedByCanceledInvoiceid() Adds a RIGHT JOIN clause and with to the query using the InvoiceRelatedByCanceledInvoiceid relation
 * @method     ChildInvoiceQuery innerJoinWithInvoiceRelatedByCanceledInvoiceid() Adds a INNER JOIN clause and with to the query using the InvoiceRelatedByCanceledInvoiceid relation
 *
 * @method     ChildInvoiceQuery leftJoinInvoiceType($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceType relation
 * @method     ChildInvoiceQuery rightJoinInvoiceType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceType relation
 * @method     ChildInvoiceQuery innerJoinInvoiceType($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceType relation
 *
 * @method     ChildInvoiceQuery joinWithInvoiceType($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceType relation
 *
 * @method     ChildInvoiceQuery leftJoinWithInvoiceType() Adds a LEFT JOIN clause and with to the query using the InvoiceType relation
 * @method     ChildInvoiceQuery rightJoinWithInvoiceType() Adds a RIGHT JOIN clause and with to the query using the InvoiceType relation
 * @method     ChildInvoiceQuery innerJoinWithInvoiceType() Adds a INNER JOIN clause and with to the query using the InvoiceType relation
 *
 * @method     ChildInvoiceQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildInvoiceQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildInvoiceQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildInvoiceQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildInvoiceQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildInvoiceQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildInvoiceQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildInvoiceQuery leftJoinInvoiceRelatedByInvoiceid($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceRelatedByInvoiceid relation
 * @method     ChildInvoiceQuery rightJoinInvoiceRelatedByInvoiceid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceRelatedByInvoiceid relation
 * @method     ChildInvoiceQuery innerJoinInvoiceRelatedByInvoiceid($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceRelatedByInvoiceid relation
 *
 * @method     ChildInvoiceQuery joinWithInvoiceRelatedByInvoiceid($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceRelatedByInvoiceid relation
 *
 * @method     ChildInvoiceQuery leftJoinWithInvoiceRelatedByInvoiceid() Adds a LEFT JOIN clause and with to the query using the InvoiceRelatedByInvoiceid relation
 * @method     ChildInvoiceQuery rightJoinWithInvoiceRelatedByInvoiceid() Adds a RIGHT JOIN clause and with to the query using the InvoiceRelatedByInvoiceid relation
 * @method     ChildInvoiceQuery innerJoinWithInvoiceRelatedByInvoiceid() Adds a INNER JOIN clause and with to the query using the InvoiceRelatedByInvoiceid relation
 *
 * @method     ChildInvoiceQuery leftJoinInvoiceItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery rightJoinInvoiceItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery innerJoinInvoiceItem($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceItem relation
 *
 * @method     ChildInvoiceQuery joinWithInvoiceItem($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceItem relation
 *
 * @method     ChildInvoiceQuery leftJoinWithInvoiceItem() Adds a LEFT JOIN clause and with to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery rightJoinWithInvoiceItem() Adds a RIGHT JOIN clause and with to the query using the InvoiceItem relation
 * @method     ChildInvoiceQuery innerJoinWithInvoiceItem() Adds a INNER JOIN clause and with to the query using the InvoiceItem relation
 *
 * @method     ChildInvoiceQuery leftJoinPaymentRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentRecieved relation
 * @method     ChildInvoiceQuery rightJoinPaymentRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentRecieved relation
 * @method     ChildInvoiceQuery innerJoinPaymentRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentRecieved relation
 *
 * @method     ChildInvoiceQuery joinWithPaymentRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentRecieved relation
 *
 * @method     ChildInvoiceQuery leftJoinWithPaymentRecieved() Adds a LEFT JOIN clause and with to the query using the PaymentRecieved relation
 * @method     ChildInvoiceQuery rightJoinWithPaymentRecieved() Adds a RIGHT JOIN clause and with to the query using the PaymentRecieved relation
 * @method     ChildInvoiceQuery innerJoinWithPaymentRecieved() Adds a INNER JOIN clause and with to the query using the PaymentRecieved relation
 *
 * @method     ChildInvoiceQuery leftJoinInvoiceWarning($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoiceWarning relation
 * @method     ChildInvoiceQuery rightJoinInvoiceWarning($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoiceWarning relation
 * @method     ChildInvoiceQuery innerJoinInvoiceWarning($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoiceWarning relation
 *
 * @method     ChildInvoiceQuery joinWithInvoiceWarning($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoiceWarning relation
 *
 * @method     ChildInvoiceQuery leftJoinWithInvoiceWarning() Adds a LEFT JOIN clause and with to the query using the InvoiceWarning relation
 * @method     ChildInvoiceQuery rightJoinWithInvoiceWarning() Adds a RIGHT JOIN clause and with to the query using the InvoiceWarning relation
 * @method     ChildInvoiceQuery innerJoinWithInvoiceWarning() Adds a INNER JOIN clause and with to the query using the InvoiceWarning relation
 *
 * @method     \API\Models\Event\EventContactQuery|\API\Models\Event\EventBankinformationQuery|\API\Models\Invoice\InvoiceQuery|\API\Models\Invoice\InvoiceTypeQuery|\API\Models\User\UserQuery|\API\Models\Invoice\InvoiceItemQuery|\API\Models\Payment\PaymentRecievedQuery|\API\Models\Invoice\InvoiceWarningQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvoice findOne(ConnectionInterface $con = null) Return the first ChildInvoice matching the query
 * @method     ChildInvoice findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoice matching the query, or a new ChildInvoice object populated from the query conditions when no match is found
 *
 * @method     ChildInvoice findOneByInvoiceid(int $invoiceid) Return the first ChildInvoice filtered by the invoiceid column
 * @method     ChildInvoice findOneByInvoiceTypeid(int $invoice_typeid) Return the first ChildInvoice filtered by the invoice_typeid column
 * @method     ChildInvoice findOneByEventContactid(int $event_contactid) Return the first ChildInvoice filtered by the event_contactid column
 * @method     ChildInvoice findOneByUserid(int $userid) Return the first ChildInvoice filtered by the userid column
 * @method     ChildInvoice findOneByEventBankinformationid(int $event_bankinformationid) Return the first ChildInvoice filtered by the event_bankinformationid column
 * @method     ChildInvoice findOneByCustomerEventContactid(int $customer_event_contactid) Return the first ChildInvoice filtered by the customer_event_contactid column
 * @method     ChildInvoice findOneByCanceledInvoiceid(int $canceled_invoiceid) Return the first ChildInvoice filtered by the canceled_invoiceid column
 * @method     ChildInvoice findOneByDate(string $date) Return the first ChildInvoice filtered by the date column
 * @method     ChildInvoice findOneByAmount(string $amount) Return the first ChildInvoice filtered by the amount column
 * @method     ChildInvoice findOneByMaturityDate(string $maturity_date) Return the first ChildInvoice filtered by the maturity_date column
 * @method     ChildInvoice findOneByPaymentFinished(string $payment_finished) Return the first ChildInvoice filtered by the payment_finished column
 * @method     ChildInvoice findOneByAmountRecieved(string $amount_recieved) Return the first ChildInvoice filtered by the amount_recieved column *

 * @method     ChildInvoice requirePk($key, ConnectionInterface $con = null) Return the ChildInvoice by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOne(ConnectionInterface $con = null) Return the first ChildInvoice matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoice requireOneByInvoiceid(int $invoiceid) Return the first ChildInvoice filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByInvoiceTypeid(int $invoice_typeid) Return the first ChildInvoice filtered by the invoice_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByEventContactid(int $event_contactid) Return the first ChildInvoice filtered by the event_contactid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByUserid(int $userid) Return the first ChildInvoice filtered by the userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByEventBankinformationid(int $event_bankinformationid) Return the first ChildInvoice filtered by the event_bankinformationid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByCustomerEventContactid(int $customer_event_contactid) Return the first ChildInvoice filtered by the customer_event_contactid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByCanceledInvoiceid(int $canceled_invoiceid) Return the first ChildInvoice filtered by the canceled_invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByDate(string $date) Return the first ChildInvoice filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByAmount(string $amount) Return the first ChildInvoice filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByMaturityDate(string $maturity_date) Return the first ChildInvoice filtered by the maturity_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByPaymentFinished(string $payment_finished) Return the first ChildInvoice filtered by the payment_finished column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoice requireOneByAmountRecieved(string $amount_recieved) Return the first ChildInvoice filtered by the amount_recieved column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoice[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoice objects based on current ModelCriteria
 * @method     ChildInvoice[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildInvoice objects filtered by the invoiceid column
 * @method     ChildInvoice[]|ObjectCollection findByInvoiceTypeid(int $invoice_typeid) Return ChildInvoice objects filtered by the invoice_typeid column
 * @method     ChildInvoice[]|ObjectCollection findByEventContactid(int $event_contactid) Return ChildInvoice objects filtered by the event_contactid column
 * @method     ChildInvoice[]|ObjectCollection findByUserid(int $userid) Return ChildInvoice objects filtered by the userid column
 * @method     ChildInvoice[]|ObjectCollection findByEventBankinformationid(int $event_bankinformationid) Return ChildInvoice objects filtered by the event_bankinformationid column
 * @method     ChildInvoice[]|ObjectCollection findByCustomerEventContactid(int $customer_event_contactid) Return ChildInvoice objects filtered by the customer_event_contactid column
 * @method     ChildInvoice[]|ObjectCollection findByCanceledInvoiceid(int $canceled_invoiceid) Return ChildInvoice objects filtered by the canceled_invoiceid column
 * @method     ChildInvoice[]|ObjectCollection findByDate(string $date) Return ChildInvoice objects filtered by the date column
 * @method     ChildInvoice[]|ObjectCollection findByAmount(string $amount) Return ChildInvoice objects filtered by the amount column
 * @method     ChildInvoice[]|ObjectCollection findByMaturityDate(string $maturity_date) Return ChildInvoice objects filtered by the maturity_date column
 * @method     ChildInvoice[]|ObjectCollection findByPaymentFinished(string $payment_finished) Return ChildInvoice objects filtered by the payment_finished column
 * @method     ChildInvoice[]|ObjectCollection findByAmountRecieved(string $amount_recieved) Return ChildInvoice objects filtered by the amount_recieved column
 * @method     ChildInvoice[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InvoiceQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Invoice\Base\InvoiceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Invoice\\Invoice', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoiceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvoiceQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoiceQuery) {
            return $criteria;
        }
        $query = new ChildInvoiceQuery();
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
     * @return ChildInvoice|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoiceTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoiceTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInvoice A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoiceid, invoice_typeid, event_contactid, userid, event_bankinformationid, customer_event_contactid, canceled_invoiceid, date, amount, maturity_date, payment_finished, amount_recieved FROM invoice WHERE invoiceid = :p0';
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
            /** @var ChildInvoice $obj */
            $obj = new ChildInvoice();
            $obj->hydrate($row);
            InvoiceTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInvoice|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $keys, Criteria::IN);
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
     * @param     mixed $invoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceid, $comparison);
    }

    /**
     * Filter the query on the invoice_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoiceTypeid(1234); // WHERE invoice_typeid = 1234
     * $query->filterByInvoiceTypeid(array(12, 34)); // WHERE invoice_typeid IN (12, 34)
     * $query->filterByInvoiceTypeid(array('min' => 12)); // WHERE invoice_typeid > 12
     * </code>
     *
     * @see       filterByInvoiceType()
     *
     * @param     mixed $invoiceTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceTypeid($invoiceTypeid = null, $comparison = null)
    {
        if (is_array($invoiceTypeid)) {
            $useMinMax = false;
            if (isset($invoiceTypeid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_INVOICE_TYPEID, $invoiceTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceTypeid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_INVOICE_TYPEID, $invoiceTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_INVOICE_TYPEID, $invoiceTypeid, $comparison);
    }

    /**
     * Filter the query on the event_contactid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventContactid(1234); // WHERE event_contactid = 1234
     * $query->filterByEventContactid(array(12, 34)); // WHERE event_contactid IN (12, 34)
     * $query->filterByEventContactid(array('min' => 12)); // WHERE event_contactid > 12
     * </code>
     *
     * @see       filterByEventContactRelatedByEventContactid()
     *
     * @param     mixed $eventContactid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEventContactid($eventContactid = null, $comparison = null)
    {
        if (is_array($eventContactid)) {
            $useMinMax = false;
            if (isset($eventContactid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_EVENT_CONTACTID, $eventContactid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventContactid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_EVENT_CONTACTID, $eventContactid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_EVENT_CONTACTID, $eventContactid, $comparison);
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
     * @see       filterByUser()
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the event_bankinformationid column
     *
     * Example usage:
     * <code>
     * $query->filterByEventBankinformationid(1234); // WHERE event_bankinformationid = 1234
     * $query->filterByEventBankinformationid(array(12, 34)); // WHERE event_bankinformationid IN (12, 34)
     * $query->filterByEventBankinformationid(array('min' => 12)); // WHERE event_bankinformationid > 12
     * </code>
     *
     * @see       filterByEventBankinformation()
     *
     * @param     mixed $eventBankinformationid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEventBankinformationid($eventBankinformationid = null, $comparison = null)
    {
        if (is_array($eventBankinformationid)) {
            $useMinMax = false;
            if (isset($eventBankinformationid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID, $eventBankinformationid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventBankinformationid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID, $eventBankinformationid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID, $eventBankinformationid, $comparison);
    }

    /**
     * Filter the query on the customer_event_contactid column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerEventContactid(1234); // WHERE customer_event_contactid = 1234
     * $query->filterByCustomerEventContactid(array(12, 34)); // WHERE customer_event_contactid IN (12, 34)
     * $query->filterByCustomerEventContactid(array('min' => 12)); // WHERE customer_event_contactid > 12
     * </code>
     *
     * @see       filterByEventContactRelatedByCustomerEventContactid()
     *
     * @param     mixed $customerEventContactid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByCustomerEventContactid($customerEventContactid = null, $comparison = null)
    {
        if (is_array($customerEventContactid)) {
            $useMinMax = false;
            if (isset($customerEventContactid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID, $customerEventContactid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerEventContactid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID, $customerEventContactid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID, $customerEventContactid, $comparison);
    }

    /**
     * Filter the query on the canceled_invoiceid column
     *
     * Example usage:
     * <code>
     * $query->filterByCanceledInvoiceid(1234); // WHERE canceled_invoiceid = 1234
     * $query->filterByCanceledInvoiceid(array(12, 34)); // WHERE canceled_invoiceid IN (12, 34)
     * $query->filterByCanceledInvoiceid(array('min' => 12)); // WHERE canceled_invoiceid > 12
     * </code>
     *
     * @see       filterByInvoiceRelatedByCanceledInvoiceid()
     *
     * @param     mixed $canceledInvoiceid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByCanceledInvoiceid($canceledInvoiceid = null, $comparison = null)
    {
        if (is_array($canceledInvoiceid)) {
            $useMinMax = false;
            if (isset($canceledInvoiceid['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CANCELED_INVOICEID, $canceledInvoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canceledInvoiceid['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_CANCELED_INVOICEID, $canceledInvoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_CANCELED_INVOICEID, $canceledInvoiceid, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_DATE, $date, $comparison);
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_AMOUNT, $amount, $comparison);
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
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByMaturityDate($maturityDate = null, $comparison = null)
    {
        if (is_array($maturityDate)) {
            $useMinMax = false;
            if (isset($maturityDate['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_MATURITY_DATE, $maturityDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maturityDate['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_MATURITY_DATE, $maturityDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_MATURITY_DATE, $maturityDate, $comparison);
    }

    /**
     * Filter the query on the payment_finished column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentFinished('2011-03-14'); // WHERE payment_finished = '2011-03-14'
     * $query->filterByPaymentFinished('now'); // WHERE payment_finished = '2011-03-14'
     * $query->filterByPaymentFinished(array('max' => 'yesterday')); // WHERE payment_finished > '2011-03-13'
     * </code>
     *
     * @param     mixed $paymentFinished The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPaymentFinished($paymentFinished = null, $comparison = null)
    {
        if (is_array($paymentFinished)) {
            $useMinMax = false;
            if (isset($paymentFinished['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_PAYMENT_FINISHED, $paymentFinished['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentFinished['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_PAYMENT_FINISHED, $paymentFinished['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_PAYMENT_FINISHED, $paymentFinished, $comparison);
    }

    /**
     * Filter the query on the amount_recieved column
     *
     * Example usage:
     * <code>
     * $query->filterByAmountRecieved(1234); // WHERE amount_recieved = 1234
     * $query->filterByAmountRecieved(array(12, 34)); // WHERE amount_recieved IN (12, 34)
     * $query->filterByAmountRecieved(array('min' => 12)); // WHERE amount_recieved > 12
     * </code>
     *
     * @param     mixed $amountRecieved The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByAmountRecieved($amountRecieved = null, $comparison = null)
    {
        if (is_array($amountRecieved)) {
            $useMinMax = false;
            if (isset($amountRecieved['min'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_AMOUNT_RECIEVED, $amountRecieved['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amountRecieved['max'])) {
                $this->addUsingAlias(InvoiceTableMap::COL_AMOUNT_RECIEVED, $amountRecieved['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoiceTableMap::COL_AMOUNT_RECIEVED, $amountRecieved, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Event\EventContact object
     *
     * @param \API\Models\Event\EventContact|ObjectCollection $eventContact The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEventContactRelatedByCustomerEventContactid($eventContact, $comparison = null)
    {
        if ($eventContact instanceof \API\Models\Event\EventContact) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID, $eventContact->getEventContactid(), $comparison);
        } elseif ($eventContact instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CUSTOMER_EVENT_CONTACTID, $eventContact->toKeyValue('PrimaryKey', 'EventContactid'), $comparison);
        } else {
            throw new PropelException('filterByEventContactRelatedByCustomerEventContactid() only accepts arguments of type \API\Models\Event\EventContact or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventContactRelatedByCustomerEventContactid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinEventContactRelatedByCustomerEventContactid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventContactRelatedByCustomerEventContactid');

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
            $this->addJoinObject($join, 'EventContactRelatedByCustomerEventContactid');
        }

        return $this;
    }

    /**
     * Use the EventContactRelatedByCustomerEventContactid relation EventContact object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventContactQuery A secondary query class using the current class as primary query
     */
    public function useEventContactRelatedByCustomerEventContactidQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEventContactRelatedByCustomerEventContactid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventContactRelatedByCustomerEventContactid', '\API\Models\Event\EventContactQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventBankinformation object
     *
     * @param \API\Models\Event\EventBankinformation|ObjectCollection $eventBankinformation The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEventBankinformation($eventBankinformation, $comparison = null)
    {
        if ($eventBankinformation instanceof \API\Models\Event\EventBankinformation) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID, $eventBankinformation->getEventBankinformationid(), $comparison);
        } elseif ($eventBankinformation instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_EVENT_BANKINFORMATIONID, $eventBankinformation->toKeyValue('PrimaryKey', 'EventBankinformationid'), $comparison);
        } else {
            throw new PropelException('filterByEventBankinformation() only accepts arguments of type \API\Models\Event\EventBankinformation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventBankinformation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinEventBankinformation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventBankinformation');

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
            $this->addJoinObject($join, 'EventBankinformation');
        }

        return $this;
    }

    /**
     * Use the EventBankinformation relation EventBankinformation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventBankinformationQuery A secondary query class using the current class as primary query
     */
    public function useEventBankinformationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventBankinformation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventBankinformation', '\API\Models\Event\EventBankinformationQuery');
    }

    /**
     * Filter the query by a related \API\Models\Event\EventContact object
     *
     * @param \API\Models\Event\EventContact|ObjectCollection $eventContact The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByEventContactRelatedByEventContactid($eventContact, $comparison = null)
    {
        if ($eventContact instanceof \API\Models\Event\EventContact) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_EVENT_CONTACTID, $eventContact->getEventContactid(), $comparison);
        } elseif ($eventContact instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_EVENT_CONTACTID, $eventContact->toKeyValue('PrimaryKey', 'EventContactid'), $comparison);
        } else {
            throw new PropelException('filterByEventContactRelatedByEventContactid() only accepts arguments of type \API\Models\Event\EventContact or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventContactRelatedByEventContactid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinEventContactRelatedByEventContactid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventContactRelatedByEventContactid');

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
            $this->addJoinObject($join, 'EventContactRelatedByEventContactid');
        }

        return $this;
    }

    /**
     * Use the EventContactRelatedByEventContactid relation EventContact object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Event\EventContactQuery A secondary query class using the current class as primary query
     */
    public function useEventContactRelatedByEventContactidQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventContactRelatedByEventContactid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventContactRelatedByEventContactid', '\API\Models\Event\EventContactQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceRelatedByCanceledInvoiceid($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CANCELED_INVOICEID, $invoice->getInvoiceid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_CANCELED_INVOICEID, $invoice->toKeyValue('PrimaryKey', 'Invoiceid'), $comparison);
        } else {
            throw new PropelException('filterByInvoiceRelatedByCanceledInvoiceid() only accepts arguments of type \API\Models\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceRelatedByCanceledInvoiceid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinInvoiceRelatedByCanceledInvoiceid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceRelatedByCanceledInvoiceid');

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
            $this->addJoinObject($join, 'InvoiceRelatedByCanceledInvoiceid');
        }

        return $this;
    }

    /**
     * Use the InvoiceRelatedByCanceledInvoiceid relation Invoice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceRelatedByCanceledInvoiceidQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInvoiceRelatedByCanceledInvoiceid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceRelatedByCanceledInvoiceid', '\API\Models\Invoice\InvoiceQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\InvoiceType object
     *
     * @param \API\Models\Invoice\InvoiceType|ObjectCollection $invoiceType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceType($invoiceType, $comparison = null)
    {
        if ($invoiceType instanceof \API\Models\Invoice\InvoiceType) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICE_TYPEID, $invoiceType->getInvoiceTypeid(), $comparison);
        } elseif ($invoiceType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICE_TYPEID, $invoiceType->toKeyValue('PrimaryKey', 'InvoiceTypeid'), $comparison);
        } else {
            throw new PropelException('filterByInvoiceType() only accepts arguments of type \API\Models\Invoice\InvoiceType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinInvoiceType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceType');

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
            $this->addJoinObject($join, 'InvoiceType');
        }

        return $this;
    }

    /**
     * Use the InvoiceType relation InvoiceType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceTypeQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceType', '\API\Models\Invoice\InvoiceTypeQuery');
    }

    /**
     * Filter the query by a related \API\Models\User\User object
     *
     * @param \API\Models\User\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \API\Models\User\User) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_USERID, $user->getUserid(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoiceTableMap::COL_USERID, $user->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \API\Models\User\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return \API\Models\User\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\API\Models\User\UserQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\Invoice object
     *
     * @param \API\Models\Invoice\Invoice|ObjectCollection $invoice the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceRelatedByInvoiceid($invoice, $comparison = null)
    {
        if ($invoice instanceof \API\Models\Invoice\Invoice) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoice->getCanceledInvoiceid(), $comparison);
        } elseif ($invoice instanceof ObjectCollection) {
            return $this
                ->useInvoiceRelatedByInvoiceidQuery()
                ->filterByPrimaryKeys($invoice->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceRelatedByInvoiceid() only accepts arguments of type \API\Models\Invoice\Invoice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceRelatedByInvoiceid relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinInvoiceRelatedByInvoiceid($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceRelatedByInvoiceid');

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
            $this->addJoinObject($join, 'InvoiceRelatedByInvoiceid');
        }

        return $this;
    }

    /**
     * Use the InvoiceRelatedByInvoiceid relation Invoice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceRelatedByInvoiceidQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInvoiceRelatedByInvoiceid($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceRelatedByInvoiceid', '\API\Models\Invoice\InvoiceQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\InvoiceItem object
     *
     * @param \API\Models\Invoice\InvoiceItem|ObjectCollection $invoiceItem the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceItem($invoiceItem, $comparison = null)
    {
        if ($invoiceItem instanceof \API\Models\Invoice\InvoiceItem) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceItem->getInvoiceid(), $comparison);
        } elseif ($invoiceItem instanceof ObjectCollection) {
            return $this
                ->useInvoiceItemQuery()
                ->filterByPrimaryKeys($invoiceItem->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceItem() only accepts arguments of type \API\Models\Invoice\InvoiceItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinInvoiceItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return \API\Models\Invoice\InvoiceItemQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceItem', '\API\Models\Invoice\InvoiceItemQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\PaymentRecieved object
     *
     * @param \API\Models\Payment\PaymentRecieved|ObjectCollection $paymentRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByPaymentRecieved($paymentRecieved, $comparison = null)
    {
        if ($paymentRecieved instanceof \API\Models\Payment\PaymentRecieved) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $paymentRecieved->getInvoiceid(), $comparison);
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
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * Filter the query by a related \API\Models\Invoice\InvoiceWarning object
     *
     * @param \API\Models\Invoice\InvoiceWarning|ObjectCollection $invoiceWarning the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoiceQuery The current query, for fluid interface
     */
    public function filterByInvoiceWarning($invoiceWarning, $comparison = null)
    {
        if ($invoiceWarning instanceof \API\Models\Invoice\InvoiceWarning) {
            return $this
                ->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoiceWarning->getInvoiceid(), $comparison);
        } elseif ($invoiceWarning instanceof ObjectCollection) {
            return $this
                ->useInvoiceWarningQuery()
                ->filterByPrimaryKeys($invoiceWarning->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoiceWarning() only accepts arguments of type \API\Models\Invoice\InvoiceWarning or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoiceWarning relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function joinInvoiceWarning($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvoiceWarning');

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
            $this->addJoinObject($join, 'InvoiceWarning');
        }

        return $this;
    }

    /**
     * Use the InvoiceWarning relation InvoiceWarning object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Invoice\InvoiceWarningQuery A secondary query class using the current class as primary query
     */
    public function useInvoiceWarningQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoiceWarning($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoiceWarning', '\API\Models\Invoice\InvoiceWarningQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInvoice $invoice Object to remove from the list of results
     *
     * @return $this|ChildInvoiceQuery The current query, for fluid interface
     */
    public function prune($invoice = null)
    {
        if ($invoice) {
            $this->addUsingAlias(InvoiceTableMap::COL_INVOICEID, $invoice->getInvoiceid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoice table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvoiceTableMap::clearInstancePool();
            InvoiceTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoiceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoiceTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvoiceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvoiceTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InvoiceQuery
