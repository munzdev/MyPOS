<?php

namespace API\Models\Payment\Base;

use \Exception;
use \PDO;
use API\Models\Payment\PaymentTypes as ChildPaymentTypes;
use API\Models\Payment\PaymentTypesQuery as ChildPaymentTypesQuery;
use API\Models\Payment\Map\PaymentTypesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payment_types' table.
 *
 *
 *
 * @method     ChildPaymentTypesQuery orderByIdpaymentTypeid($order = Criteria::ASC) Order by the idpayment_typeid column
 * @method     ChildPaymentTypesQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildPaymentTypesQuery groupByIdpaymentTypeid() Group by the idpayment_typeid column
 * @method     ChildPaymentTypesQuery groupByName() Group by the name column
 *
 * @method     ChildPaymentTypesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentTypesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentTypesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentTypesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentTypesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentTypesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentTypesQuery leftJoinPayments($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payments relation
 * @method     ChildPaymentTypesQuery rightJoinPayments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payments relation
 * @method     ChildPaymentTypesQuery innerJoinPayments($relationAlias = null) Adds a INNER JOIN clause to the query using the Payments relation
 *
 * @method     ChildPaymentTypesQuery joinWithPayments($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payments relation
 *
 * @method     ChildPaymentTypesQuery leftJoinWithPayments() Adds a LEFT JOIN clause and with to the query using the Payments relation
 * @method     ChildPaymentTypesQuery rightJoinWithPayments() Adds a RIGHT JOIN clause and with to the query using the Payments relation
 * @method     ChildPaymentTypesQuery innerJoinWithPayments() Adds a INNER JOIN clause and with to the query using the Payments relation
 *
 * @method     \API\Models\Payment\PaymentsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPaymentTypes findOne(ConnectionInterface $con = null) Return the first ChildPaymentTypes matching the query
 * @method     ChildPaymentTypes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPaymentTypes matching the query, or a new ChildPaymentTypes object populated from the query conditions when no match is found
 *
 * @method     ChildPaymentTypes findOneByIdpaymentTypeid(int $idpayment_typeid) Return the first ChildPaymentTypes filtered by the idpayment_typeid column
 * @method     ChildPaymentTypes findOneByName(string $name) Return the first ChildPaymentTypes filtered by the name column *

 * @method     ChildPaymentTypes requirePk($key, ConnectionInterface $con = null) Return the ChildPaymentTypes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentTypes requireOne(ConnectionInterface $con = null) Return the first ChildPaymentTypes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentTypes requireOneByIdpaymentTypeid(int $idpayment_typeid) Return the first ChildPaymentTypes filtered by the idpayment_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentTypes requireOneByName(string $name) Return the first ChildPaymentTypes filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentTypes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPaymentTypes objects based on current ModelCriteria
 * @method     ChildPaymentTypes[]|ObjectCollection findByIdpaymentTypeid(int $idpayment_typeid) Return ChildPaymentTypes objects filtered by the idpayment_typeid column
 * @method     ChildPaymentTypes[]|ObjectCollection findByName(string $name) Return ChildPaymentTypes objects filtered by the name column
 * @method     ChildPaymentTypes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentTypesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\Payment\Base\PaymentTypesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\Payment\\PaymentTypes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentTypesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentTypesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentTypesQuery) {
            return $criteria;
        }
        $query = new ChildPaymentTypesQuery();
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
     * @return ChildPaymentTypes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentTypesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentTypesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPaymentTypes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT idpayment_typeid, name FROM payment_types WHERE idpayment_typeid = :p0';
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
            /** @var ChildPaymentTypes $obj */
            $obj = new ChildPaymentTypes();
            $obj->hydrate($row);
            PaymentTypesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPaymentTypes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the idpayment_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByIdpaymentTypeid(1234); // WHERE idpayment_typeid = 1234
     * $query->filterByIdpaymentTypeid(array(12, 34)); // WHERE idpayment_typeid IN (12, 34)
     * $query->filterByIdpaymentTypeid(array('min' => 12)); // WHERE idpayment_typeid > 12
     * </code>
     *
     * @param     mixed $idpaymentTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentTypesQuery The current query, for fluid interface
     */
    public function filterByIdpaymentTypeid($idpaymentTypeid = null, $comparison = null)
    {
        if (is_array($idpaymentTypeid)) {
            $useMinMax = false;
            if (isset($idpaymentTypeid['min'])) {
                $this->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $idpaymentTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idpaymentTypeid['max'])) {
                $this->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $idpaymentTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $idpaymentTypeid, $comparison);
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
     * @return $this|ChildPaymentTypesQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTypesTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\Payment\Payments object
     *
     * @param \API\Models\Payment\Payments|ObjectCollection $payments the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPaymentTypesQuery The current query, for fluid interface
     */
    public function filterByPayments($payments, $comparison = null)
    {
        if ($payments instanceof \API\Models\Payment\Payments) {
            return $this
                ->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $payments->getPaymentTypeid(), $comparison);
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
     * @return $this|ChildPaymentTypesQuery The current query, for fluid interface
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
     * @param   ChildPaymentTypes $paymentTypes Object to remove from the list of results
     *
     * @return $this|ChildPaymentTypesQuery The current query, for fluid interface
     */
    public function prune($paymentTypes = null)
    {
        if ($paymentTypes) {
            $this->addUsingAlias(PaymentTypesTableMap::COL_IDPAYMENT_TYPEID, $paymentTypes->getIdpaymentTypeid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payment_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTypesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentTypesTableMap::clearInstancePool();
            PaymentTypesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTypesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentTypesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentTypesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentTypesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentTypesQuery
