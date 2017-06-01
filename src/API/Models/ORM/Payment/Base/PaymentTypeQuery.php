<?php

namespace API\Models\ORM\Payment\Base;

use \Exception;
use \PDO;
use API\Models\ORM\Payment\PaymentType as ChildPaymentType;
use API\Models\ORM\Payment\PaymentTypeQuery as ChildPaymentTypeQuery;
use API\Models\ORM\Payment\Map\PaymentTypeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payment_type' table.
 *
 * 
 *
 * @method     ChildPaymentTypeQuery orderByPaymentTypeid($order = Criteria::ASC) Order by the payment_typeid column
 * @method     ChildPaymentTypeQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildPaymentTypeQuery groupByPaymentTypeid() Group by the payment_typeid column
 * @method     ChildPaymentTypeQuery groupByName() Group by the name column
 *
 * @method     ChildPaymentTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentTypeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentTypeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentTypeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPaymentTypeQuery leftJoinPaymentRecieved($relationAlias = null) Adds a LEFT JOIN clause to the query using the PaymentRecieved relation
 * @method     ChildPaymentTypeQuery rightJoinPaymentRecieved($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PaymentRecieved relation
 * @method     ChildPaymentTypeQuery innerJoinPaymentRecieved($relationAlias = null) Adds a INNER JOIN clause to the query using the PaymentRecieved relation
 *
 * @method     ChildPaymentTypeQuery joinWithPaymentRecieved($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PaymentRecieved relation
 *
 * @method     ChildPaymentTypeQuery leftJoinWithPaymentRecieved() Adds a LEFT JOIN clause and with to the query using the PaymentRecieved relation
 * @method     ChildPaymentTypeQuery rightJoinWithPaymentRecieved() Adds a RIGHT JOIN clause and with to the query using the PaymentRecieved relation
 * @method     ChildPaymentTypeQuery innerJoinWithPaymentRecieved() Adds a INNER JOIN clause and with to the query using the PaymentRecieved relation
 *
 * @method     \API\Models\ORM\Payment\PaymentRecievedQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPaymentType findOne(ConnectionInterface $con = null) Return the first ChildPaymentType matching the query
 * @method     ChildPaymentType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPaymentType matching the query, or a new ChildPaymentType object populated from the query conditions when no match is found
 *
 * @method     ChildPaymentType findOneByPaymentTypeid(int $payment_typeid) Return the first ChildPaymentType filtered by the payment_typeid column
 * @method     ChildPaymentType findOneByName(string $name) Return the first ChildPaymentType filtered by the name column *

 * @method     ChildPaymentType requirePk($key, ConnectionInterface $con = null) Return the ChildPaymentType by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentType requireOne(ConnectionInterface $con = null) Return the first ChildPaymentType matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentType requireOneByPaymentTypeid(int $payment_typeid) Return the first ChildPaymentType filtered by the payment_typeid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPaymentType requireOneByName(string $name) Return the first ChildPaymentType filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPaymentType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPaymentType objects based on current ModelCriteria
 * @method     ChildPaymentType[]|ObjectCollection findByPaymentTypeid(int $payment_typeid) Return ChildPaymentType objects filtered by the payment_typeid column
 * @method     ChildPaymentType[]|ObjectCollection findByName(string $name) Return ChildPaymentType objects filtered by the name column
 * @method     ChildPaymentType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentTypeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \API\Models\ORM\Payment\Base\PaymentTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\API\\Models\\ORM\\Payment\\PaymentType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentTypeQuery) {
            return $criteria;
        }
        $query = new ChildPaymentTypeQuery();
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
     * @return ChildPaymentType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentTypeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentTypeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPaymentType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `payment_typeid`, `name` FROM `payment_type` WHERE `payment_typeid` = :p0';
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
            /** @var ChildPaymentType $obj */
            $obj = new ChildPaymentType();
            $obj->hydrate($row);
            PaymentTypeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPaymentType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the payment_typeid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentTypeid(1234); // WHERE payment_typeid = 1234
     * $query->filterByPaymentTypeid(array(12, 34)); // WHERE payment_typeid IN (12, 34)
     * $query->filterByPaymentTypeid(array('min' => 12)); // WHERE payment_typeid > 12
     * </code>
     *
     * @param     mixed $paymentTypeid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentTypeQuery The current query, for fluid interface
     */
    public function filterByPaymentTypeid($paymentTypeid = null, $comparison = null)
    {
        if (is_array($paymentTypeid)) {
            $useMinMax = false;
            if (isset($paymentTypeid['min'])) {
                $this->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentTypeid['max'])) {
                $this->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $paymentTypeid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $paymentTypeid, $comparison);
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
     * @return $this|ChildPaymentTypeQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTypeTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \API\Models\ORM\Payment\PaymentRecieved object
     *
     * @param \API\Models\ORM\Payment\PaymentRecieved|ObjectCollection $paymentRecieved the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPaymentTypeQuery The current query, for fluid interface
     */
    public function filterByPaymentRecieved($paymentRecieved, $comparison = null)
    {
        if ($paymentRecieved instanceof \API\Models\ORM\Payment\PaymentRecieved) {
            return $this
                ->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $paymentRecieved->getPaymentTypeid(), $comparison);
        } elseif ($paymentRecieved instanceof ObjectCollection) {
            return $this
                ->usePaymentRecievedQuery()
                ->filterByPrimaryKeys($paymentRecieved->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPaymentRecieved() only accepts arguments of type \API\Models\ORM\Payment\PaymentRecieved or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PaymentRecieved relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPaymentTypeQuery The current query, for fluid interface
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
     * @return \API\Models\ORM\Payment\PaymentRecievedQuery A secondary query class using the current class as primary query
     */
    public function usePaymentRecievedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPaymentRecieved($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PaymentRecieved', '\API\Models\ORM\Payment\PaymentRecievedQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPaymentType $paymentType Object to remove from the list of results
     *
     * @return $this|ChildPaymentTypeQuery The current query, for fluid interface
     */
    public function prune($paymentType = null)
    {
        if ($paymentType) {
            $this->addUsingAlias(PaymentTypeTableMap::COL_PAYMENT_TYPEID, $paymentType->getPaymentTypeid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payment_type table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentTypeTableMap::clearInstancePool();
            PaymentTypeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            PaymentTypeTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            PaymentTypeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PaymentTypeQuery
