<?php

namespace Models\Logging\Base;

use \Exception;
use \PDO;
use Models\Logging\LogActionTypes as ChildLogActionTypes;
use Models\Logging\LogActionTypesQuery as ChildLogActionTypesQuery;
use Models\Logging\Map\LogActionTypesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'log_action_types' table.
 *
 *
 *
 * @method     ChildLogActionTypesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLogActionTypesQuery orderByAction($order = Criteria::ASC) Order by the action column
 *
 * @method     ChildLogActionTypesQuery groupById() Group by the id column
 * @method     ChildLogActionTypesQuery groupByAction() Group by the action column
 *
 * @method     ChildLogActionTypesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLogActionTypesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLogActionTypesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLogActionTypesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLogActionTypesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLogActionTypesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLogActionTypesQuery leftJoinLogging($relationAlias = null) Adds a LEFT JOIN clause to the query using the Logging relation
 * @method     ChildLogActionTypesQuery rightJoinLogging($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Logging relation
 * @method     ChildLogActionTypesQuery innerJoinLogging($relationAlias = null) Adds a INNER JOIN clause to the query using the Logging relation
 *
 * @method     ChildLogActionTypesQuery joinWithLogging($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Logging relation
 *
 * @method     ChildLogActionTypesQuery leftJoinWithLogging() Adds a LEFT JOIN clause and with to the query using the Logging relation
 * @method     ChildLogActionTypesQuery rightJoinWithLogging() Adds a RIGHT JOIN clause and with to the query using the Logging relation
 * @method     ChildLogActionTypesQuery innerJoinWithLogging() Adds a INNER JOIN clause and with to the query using the Logging relation
 *
 * @method     \Models\Logging\LoggingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLogActionTypes findOne(ConnectionInterface $con = null) Return the first ChildLogActionTypes matching the query
 * @method     ChildLogActionTypes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLogActionTypes matching the query, or a new ChildLogActionTypes object populated from the query conditions when no match is found
 *
 * @method     ChildLogActionTypes findOneById(string $id) Return the first ChildLogActionTypes filtered by the id column
 * @method     ChildLogActionTypes findOneByAction(string $action) Return the first ChildLogActionTypes filtered by the action column *

 * @method     ChildLogActionTypes requirePk($key, ConnectionInterface $con = null) Return the ChildLogActionTypes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogActionTypes requireOne(ConnectionInterface $con = null) Return the first ChildLogActionTypes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLogActionTypes requireOneById(string $id) Return the first ChildLogActionTypes filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogActionTypes requireOneByAction(string $action) Return the first ChildLogActionTypes filtered by the action column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLogActionTypes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLogActionTypes objects based on current ModelCriteria
 * @method     ChildLogActionTypes[]|ObjectCollection findById(string $id) Return ChildLogActionTypes objects filtered by the id column
 * @method     ChildLogActionTypes[]|ObjectCollection findByAction(string $action) Return ChildLogActionTypes objects filtered by the action column
 * @method     ChildLogActionTypes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LogActionTypesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Logging\Base\LogActionTypesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Logging\\LogActionTypes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLogActionTypesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLogActionTypesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLogActionTypesQuery) {
            return $criteria;
        }
        $query = new ChildLogActionTypesQuery();
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
     * @return ChildLogActionTypes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LogActionTypesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LogActionTypesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLogActionTypes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, action FROM log_action_types WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLogActionTypes $obj */
            $obj = new ChildLogActionTypes();
            $obj->hydrate($row);
            LogActionTypesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLogActionTypes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LogActionTypesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LogActionTypesTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById('fooValue');   // WHERE id = 'fooValue'
     * $query->filterById('%fooValue%', Criteria::LIKE); // WHERE id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $id The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LogActionTypesTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the action column
     *
     * Example usage:
     * <code>
     * $query->filterByAction('fooValue');   // WHERE action = 'fooValue'
     * $query->filterByAction('%fooValue%', Criteria::LIKE); // WHERE action LIKE '%fooValue%'
     * </code>
     *
     * @param     string $action The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function filterByAction($action = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($action)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LogActionTypesTableMap::COL_ACTION, $action, $comparison);
    }

    /**
     * Filter the query by a related \Models\Logging\Logging object
     *
     * @param \Models\Logging\Logging|ObjectCollection $logging the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function filterByLogging($logging, $comparison = null)
    {
        if ($logging instanceof \Models\Logging\Logging) {
            return $this
                ->addUsingAlias(LogActionTypesTableMap::COL_ID, $logging->getLogType(), $comparison);
        } elseif ($logging instanceof ObjectCollection) {
            return $this
                ->useLoggingQuery()
                ->filterByPrimaryKeys($logging->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLogging() only accepts arguments of type \Models\Logging\Logging or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Logging relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function joinLogging($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Logging');

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
            $this->addJoinObject($join, 'Logging');
        }

        return $this;
    }

    /**
     * Use the Logging relation Logging object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Logging\LoggingQuery A secondary query class using the current class as primary query
     */
    public function useLoggingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLogging($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Logging', '\Models\Logging\LoggingQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLogActionTypes $logActionTypes Object to remove from the list of results
     *
     * @return $this|ChildLogActionTypesQuery The current query, for fluid interface
     */
    public function prune($logActionTypes = null)
    {
        if ($logActionTypes) {
            $this->addUsingAlias(LogActionTypesTableMap::COL_ID, $logActionTypes->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the log_action_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LogActionTypesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LogActionTypesTableMap::clearInstancePool();
            LogActionTypesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LogActionTypesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LogActionTypesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LogActionTypesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LogActionTypesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LogActionTypesQuery
