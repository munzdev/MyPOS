<?php

namespace API\Models\Invoice\Base;

use \Exception;
use \PDO;
use API\Models\Invoice\Invoices as ChildInvoices;
use API\Models\Invoice\InvoicesQuery as ChildInvoicesQuery;
use API\Models\Invoice\Map\InvoicesTableMap;
use API\Models\Payment\Payments;
use API\Models\User\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'invoices' table.
 *
 *
 *
 * @method     ChildInvoicesQuery orderByInvoiceid($order = Criteria::ASC) Order by the invoiceid column
 * @method     ChildInvoicesQuery orderByCashierUserid($order = Criteria::ASC) Order by the cashier_userid column
 * @method     ChildInvoicesQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildInvoicesQuery orderByCanceled($order = Criteria::ASC) Order by the canceled column
 *
 * @method     ChildInvoicesQuery groupByInvoiceid() Group by the invoiceid column
 * @method     ChildInvoicesQuery groupByCashierUserid() Group by the cashier_userid column
 * @method     ChildInvoicesQuery groupByDate() Group by the date column
 * @method     ChildInvoicesQuery groupByCanceled() Group by the canceled column
 *
 * @method     ChildInvoicesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvoicesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvoicesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvoicesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvoicesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvoicesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvoicesQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildInvoicesQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildInvoicesQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildInvoicesQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildInvoicesQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildInvoicesQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildInvoicesQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildInvoicesQuery leftJoinInvoicesItems($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvoicesItems relation
 * @method     ChildInvoicesQuery rightJoinInvoicesItems($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvoicesItems relation
 * @method     ChildInvoicesQuery innerJoinInvoicesItems($relationAlias = null) Adds a INNER JOIN clause to the query using the InvoicesItems relation
 *
 * @method     ChildInvoicesQuery joinWithInvoicesItems($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvoicesItems relation
 *
 * @method     ChildInvoicesQuery leftJoinWithInvoicesItems() Adds a LEFT JOIN clause and with to the query using the InvoicesItems relation
 * @method     ChildInvoicesQuery rightJoinWithInvoicesItems() Adds a RIGHT JOIN clause and with to the query using the InvoicesItems relation
 * @method     ChildInvoicesQuery innerJoinWithInvoicesItems() Adds a INNER JOIN clause and with to the query using the InvoicesItems relation
 *
 * @method     ChildInvoicesQuery leftJoinPayments($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payments relation
 * @method     ChildInvoicesQuery rightJoinPayments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payments relation
 * @method     ChildInvoicesQuery innerJoinPayments($relationAlias = null) Adds a INNER JOIN clause to the query using the Payments relation
 *
 * @method     ChildInvoicesQuery joinWithPayments($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payments relation
 *
 * @method     ChildInvoicesQuery leftJoinWithPayments() Adds a LEFT JOIN clause and with to the query using the Payments relation
 * @method     ChildInvoicesQuery rightJoinWithPayments() Adds a RIGHT JOIN clause and with to the query using the Payments relation
 * @method     ChildInvoicesQuery innerJoinWithPayments() Adds a INNER JOIN clause and with to the query using the Payments relation
 *
 * @method     \API\Models\User\UsersQuery|\API\Models\Invoice\InvoicesItemsQuery|\API\Models\Payment\PaymentsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvoices findOne(ConnectionInterface $con = null) Return the first ChildInvoices matching the query
 * @method     ChildInvoices findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInvoices matching the query, or a new ChildInvoices object populated from the query conditions when no match is found
 *
 * @method     ChildInvoices findOneByInvoiceid(int $invoiceid) Return the first ChildInvoices filtered by the invoiceid column
 * @method     ChildInvoices findOneByCashierUserid(int $cashier_userid) Return the first ChildInvoices filtered by the cashier_userid column
 * @method     ChildInvoices findOneByDate(string $date) Return the first ChildInvoices filtered by the date column
 * @method     ChildInvoices findOneByCanceled(string $canceled) Return the first ChildInvoices filtered by the canceled column *

 * @method     ChildInvoices requirePk($key, ConnectionInterface $con = null) Return the ChildInvoices by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoices requireOne(ConnectionInterface $con = null) Return the first ChildInvoices matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoices requireOneByInvoiceid(int $invoiceid) Return the first ChildInvoices filtered by the invoiceid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoices requireOneByCashierUserid(int $cashier_userid) Return the first ChildInvoices filtered by the cashier_userid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoices requireOneByDate(string $date) Return the first ChildInvoices filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvoices requireOneByCanceled(string $canceled) Return the first ChildInvoices filtered by the canceled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvoices[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInvoices objects based on current ModelCriteria
 * @method     ChildInvoices[]|ObjectCollection findByInvoiceid(int $invoiceid) Return ChildInvoices objects filtered by the invoiceid column
 * @method     ChildInvoices[]|ObjectCollection findByCashierUserid(int $cashier_userid) Return ChildInvoices objects filtered by the cashier_userid column
 * @method     ChildInvoices[]|ObjectCollection findByDate(string $date) Return ChildInvoices objects filtered by the date column
 * @method     ChildInvoices[]|ObjectCollection findByCanceled(string $canceled) Return ChildInvoices objects filtered by the canceled column
 * @method     ChildInvoices[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InvoicesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Invoice\Base\InvoicesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Invoice\\Invoices', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvoicesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvoicesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInvoicesQuery) {
            return $criteria;
        }
        $query = new ChildInvoicesQuery();
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
     * @param array[$invoiceid, $cashier_userid] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildInvoices|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvoicesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvoicesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildInvoices A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invoiceid, cashier_userid, date, canceled FROM invoices WHERE invoiceid = :p0 AND cashier_userid = :p1';
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
            /** @var ChildInvoices $obj */
            $obj = new ChildInvoices();
            $obj->hydrate($row);
            InvoicesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildInvoices|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(InvoicesTableMap::COL_INVOICEID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(InvoicesTableMap::COL_CASHIER_USERID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(InvoicesTableMap::COL_INVOICEID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(InvoicesTableMap::COL_CASHIER_USERID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByInvoiceid($invoiceid = null, $comparison = null)
    {
        if (is_array($invoiceid)) {
            $useMinMax = false;
            if (isset($invoiceid['min'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_INVOICEID, $invoiceid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invoiceid['max'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_INVOICEID, $invoiceid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesTableMap::COL_INVOICEID, $invoiceid, $comparison);
    }

    /**
     * Filter the query on the cashier_userid column
     *
     * Example usage:
     * <code>
     * $query->filterByCashierUserid(1234); // WHERE cashier_userid = 1234
     * $query->filterByCashierUserid(array(12, 34)); // WHERE cashier_userid IN (12, 34)
     * $query->filterByCashierUserid(array('min' => 12)); // WHERE cashier_userid > 12
     * </code>
     *
     * @see       filterByUsers()
     *
     * @param     mixed $cashierUserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByCashierUserid($cashierUserid = null, $comparison = null)
    {
        if (is_array($cashierUserid)) {
            $useMinMax = false;
            if (isset($cashierUserid['min'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_CASHIER_USERID, $cashierUserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cashierUserid['max'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_CASHIER_USERID, $cashierUserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesTableMap::COL_CASHIER_USERID, $cashierUserid, $comparison);
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
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the canceled column
     *
     * Example usage:
     * <code>
     * $query->filterByCanceled('2011-03-14'); // WHERE canceled = '2011-03-14'
     * $query->filterByCanceled('now'); // WHERE canceled = '2011-03-14'
     * $query->filterByCanceled(array('max' => 'yesterday')); // WHERE canceled > '2011-03-13'
     * </code>
     *
     * @param     mixed $canceled The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByCanceled($canceled = null, $comparison = null)
    {
        if (is_array($canceled)) {
            $useMinMax = false;
            if (isset($canceled['min'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_CANCELED, $canceled['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canceled['max'])) {
                $this->addUsingAlias(InvoicesTableMap::COL_CANCELED, $canceled['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InvoicesTableMap::COL_CANCELED, $canceled, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\User\Users object
     *
     * @param \API\Models\User\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \API\Models\User\Users) {
            return $this
                ->addUsingAlias(InvoicesTableMap::COL_CASHIER_USERID, $users->getUserid(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InvoicesTableMap::COL_CASHIER_USERID, $users->toKeyValue('PrimaryKey', 'Userid'), $comparison);
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \API\Models\User\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function joinUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return \API\Models\User\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\API\Models\User\UsersQuery');
    }

    /**
     * Filter the query by a related \API\Models\Invoice\InvoicesItems object
     *
     * @param \API\Models\Invoice\InvoicesItems|ObjectCollection $invoicesItems the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByInvoicesItems($invoicesItems, $comparison = null)
    {
        if ($invoicesItems instanceof \API\Models\Invoice\InvoicesItems) {
            return $this
                ->addUsingAlias(InvoicesTableMap::COL_INVOICEID, $invoicesItems->getInvoiceid(), $comparison);
        } elseif ($invoicesItems instanceof ObjectCollection) {
            return $this
                ->useInvoicesItemsQuery()
                ->filterByPrimaryKeys($invoicesItems->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInvoicesItems() only accepts arguments of type \API\Models\Invoice\InvoicesItems or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvoicesItems relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
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
     * @return \API\Models\Invoice\InvoicesItemsQuery A secondary query class using the current class as primary query
     */
    public function useInvoicesItemsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvoicesItems($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvoicesItems', '\API\Models\Invoice\InvoicesItemsQuery');
    }

    /**
     * Filter the query by a related \API\Models\Payment\Payments object
     *
     * @param \API\Models\Payment\Payments|ObjectCollection $payments the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInvoicesQuery The current query, for fluid interface
     */
    public function filterByPayments($payments, $comparison = null)
    {
        if ($payments instanceof \API\Models\Payment\Payments) {
            return $this
                ->addUsingAlias(InvoicesTableMap::COL_INVOICEID, $payments->getInvoiceid(), $comparison);
        } elseif ($payments instanceof ObjectCollection) {
            return $this
                ->usePaymentsQuery()
                ->filterByPrimaryKeys($payments->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPayments() only accepts arguments of type \API\Models\Payment\Payments or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Payments relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function joinPayments($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Payments');

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
            $this->addJoinObject($join, 'Payments');
        }

        return $this;
    }

    /**
     * Use the Payments relation Payments object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \API\Models\Payment\PaymentsQuery A secondary query class using the current class as primary query
     */
    public function usePaymentsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPayments($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Payments', '\API\Models\Payment\PaymentsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInvoices $invoices Object to remove from the list of results
     *
     * @return $this|ChildInvoicesQuery The current query, for fluid interface
     */
    public function prune($invoices = null)
    {
        if ($invoices) {
            $this->addCond('pruneCond0', $this->getAliasedColName(InvoicesTableMap::COL_INVOICEID), $invoices->getInvoiceid(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(InvoicesTableMap::COL_CASHIER_USERID), $invoices->getCashierUserid(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invoices table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvoicesTableMap::clearInstancePool();
            InvoicesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvoicesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvoicesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvoicesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvoicesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InvoicesQuery
