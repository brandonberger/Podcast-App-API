<?php

namespace Models\Logging\Base;

use \Exception;
use \PDO;
use Models\Logging\Logging as ChildLogging;
use Models\Logging\LoggingQuery as ChildLoggingQuery;
use Models\Logging\Map\LoggingTableMap;
use Models\Users\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'logging' table.
 *
 *
 *
 * @method     ChildLoggingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLoggingQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildLoggingQuery orderByLogType($order = Criteria::ASC) Order by the log_type column
 * @method     ChildLoggingQuery orderByActionType($order = Criteria::ASC) Order by the action_type column
 * @method     ChildLoggingQuery orderByActionMessage($order = Criteria::ASC) Order by the action_message column
 * @method     ChildLoggingQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildLoggingQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildLoggingQuery groupById() Group by the id column
 * @method     ChildLoggingQuery groupByUserId() Group by the user_id column
 * @method     ChildLoggingQuery groupByLogType() Group by the log_type column
 * @method     ChildLoggingQuery groupByActionType() Group by the action_type column
 * @method     ChildLoggingQuery groupByActionMessage() Group by the action_message column
 * @method     ChildLoggingQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildLoggingQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildLoggingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLoggingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLoggingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLoggingQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLoggingQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLoggingQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLoggingQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildLoggingQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildLoggingQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildLoggingQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildLoggingQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildLoggingQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildLoggingQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildLoggingQuery leftJoinLogActionTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the LogActionTypes relation
 * @method     ChildLoggingQuery rightJoinLogActionTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LogActionTypes relation
 * @method     ChildLoggingQuery innerJoinLogActionTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the LogActionTypes relation
 *
 * @method     ChildLoggingQuery joinWithLogActionTypes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LogActionTypes relation
 *
 * @method     ChildLoggingQuery leftJoinWithLogActionTypes() Adds a LEFT JOIN clause and with to the query using the LogActionTypes relation
 * @method     ChildLoggingQuery rightJoinWithLogActionTypes() Adds a RIGHT JOIN clause and with to the query using the LogActionTypes relation
 * @method     ChildLoggingQuery innerJoinWithLogActionTypes() Adds a INNER JOIN clause and with to the query using the LogActionTypes relation
 *
 * @method     ChildLoggingQuery leftJoinLogTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the LogTypes relation
 * @method     ChildLoggingQuery rightJoinLogTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LogTypes relation
 * @method     ChildLoggingQuery innerJoinLogTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the LogTypes relation
 *
 * @method     ChildLoggingQuery joinWithLogTypes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LogTypes relation
 *
 * @method     ChildLoggingQuery leftJoinWithLogTypes() Adds a LEFT JOIN clause and with to the query using the LogTypes relation
 * @method     ChildLoggingQuery rightJoinWithLogTypes() Adds a RIGHT JOIN clause and with to the query using the LogTypes relation
 * @method     ChildLoggingQuery innerJoinWithLogTypes() Adds a INNER JOIN clause and with to the query using the LogTypes relation
 *
 * @method     \Models\Users\UsersQuery|\Models\Logging\LogActionTypesQuery|\Models\Logging\LogTypesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLogging findOne(ConnectionInterface $con = null) Return the first ChildLogging matching the query
 * @method     ChildLogging findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLogging matching the query, or a new ChildLogging object populated from the query conditions when no match is found
 *
 * @method     ChildLogging findOneById(string $id) Return the first ChildLogging filtered by the id column
 * @method     ChildLogging findOneByUserId(string $user_id) Return the first ChildLogging filtered by the user_id column
 * @method     ChildLogging findOneByLogType(string $log_type) Return the first ChildLogging filtered by the log_type column
 * @method     ChildLogging findOneByActionType(string $action_type) Return the first ChildLogging filtered by the action_type column
 * @method     ChildLogging findOneByActionMessage(string $action_message) Return the first ChildLogging filtered by the action_message column
 * @method     ChildLogging findOneByCreatedAt(string $created_at) Return the first ChildLogging filtered by the created_at column
 * @method     ChildLogging findOneByUpdatedAt(string $updated_at) Return the first ChildLogging filtered by the updated_at column *

 * @method     ChildLogging requirePk($key, ConnectionInterface $con = null) Return the ChildLogging by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOne(ConnectionInterface $con = null) Return the first ChildLogging matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLogging requireOneById(string $id) Return the first ChildLogging filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOneByUserId(string $user_id) Return the first ChildLogging filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOneByLogType(string $log_type) Return the first ChildLogging filtered by the log_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOneByActionType(string $action_type) Return the first ChildLogging filtered by the action_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOneByActionMessage(string $action_message) Return the first ChildLogging filtered by the action_message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOneByCreatedAt(string $created_at) Return the first ChildLogging filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLogging requireOneByUpdatedAt(string $updated_at) Return the first ChildLogging filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLogging[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLogging objects based on current ModelCriteria
 * @method     ChildLogging[]|ObjectCollection findById(string $id) Return ChildLogging objects filtered by the id column
 * @method     ChildLogging[]|ObjectCollection findByUserId(string $user_id) Return ChildLogging objects filtered by the user_id column
 * @method     ChildLogging[]|ObjectCollection findByLogType(string $log_type) Return ChildLogging objects filtered by the log_type column
 * @method     ChildLogging[]|ObjectCollection findByActionType(string $action_type) Return ChildLogging objects filtered by the action_type column
 * @method     ChildLogging[]|ObjectCollection findByActionMessage(string $action_message) Return ChildLogging objects filtered by the action_message column
 * @method     ChildLogging[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildLogging objects filtered by the created_at column
 * @method     ChildLogging[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildLogging objects filtered by the updated_at column
 * @method     ChildLogging[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LoggingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Logging\Base\LoggingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Logging\\Logging', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLoggingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLoggingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLoggingQuery) {
            return $criteria;
        }
        $query = new ChildLoggingQuery();
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
     * $obj = $c->findPk(array(12, 34, 56, 78), $con);
     * </code>
     *
     * @param array[$id, $user_id, $log_type, $action_type] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLogging|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LoggingTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LoggingTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2]), (null === $key[3] || is_scalar($key[3]) || is_callable([$key[3], '__toString']) ? (string) $key[3] : $key[3])]))))) {
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
     * @return ChildLogging A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, user_id, log_type, action_type, action_message, created_at, updated_at FROM logging WHERE id = :p0 AND user_id = :p1 AND log_type = :p2 AND action_type = :p3';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_STR);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_STR);
            $stmt->bindValue(':p3', $key[3], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLogging $obj */
            $obj = new ChildLogging();
            $obj->hydrate($row);
            LoggingTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2]), (null === $key[3] || is_scalar($key[3]) || is_callable([$key[3], '__toString']) ? (string) $key[3] : $key[3])]));
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
     * @return ChildLogging|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(LoggingTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(LoggingTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(LoggingTableMap::COL_LOG_TYPE, $key[2], Criteria::EQUAL);
        $this->addUsingAlias(LoggingTableMap::COL_ACTION_TYPE, $key[3], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(LoggingTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(LoggingTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(LoggingTableMap::COL_LOG_TYPE, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
            $cton3 = $this->getNewCriterion(LoggingTableMap::COL_ACTION_TYPE, $key[3], Criteria::EQUAL);
            $cton0->addAnd($cton3);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId('fooValue');   // WHERE user_id = 'fooValue'
     * $query->filterByUserId('%fooValue%', Criteria::LIKE); // WHERE user_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the log_type column
     *
     * Example usage:
     * <code>
     * $query->filterByLogType('fooValue');   // WHERE log_type = 'fooValue'
     * $query->filterByLogType('%fooValue%', Criteria::LIKE); // WHERE log_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logType The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByLogType($logType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logType)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_LOG_TYPE, $logType, $comparison);
    }

    /**
     * Filter the query on the action_type column
     *
     * Example usage:
     * <code>
     * $query->filterByActionType('fooValue');   // WHERE action_type = 'fooValue'
     * $query->filterByActionType('%fooValue%', Criteria::LIKE); // WHERE action_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $actionType The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByActionType($actionType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($actionType)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_ACTION_TYPE, $actionType, $comparison);
    }

    /**
     * Filter the query on the action_message column
     *
     * Example usage:
     * <code>
     * $query->filterByActionMessage('fooValue');   // WHERE action_message = 'fooValue'
     * $query->filterByActionMessage('%fooValue%', Criteria::LIKE); // WHERE action_message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $actionMessage The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByActionMessage($actionMessage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($actionMessage)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_ACTION_MESSAGE, $actionMessage, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LoggingTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LoggingTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LoggingTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LoggingTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LoggingTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(LoggingTableMap::COL_USER_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LoggingTableMap::COL_USER_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUsers() only accepts arguments of type \Models\Users\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Users relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
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
     * @return \Models\Users\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Users', '\Models\Users\UsersQuery');
    }

    /**
     * Filter the query by a related \Models\Logging\LogActionTypes object
     *
     * @param \Models\Logging\LogActionTypes|ObjectCollection $logActionTypes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByLogActionTypes($logActionTypes, $comparison = null)
    {
        if ($logActionTypes instanceof \Models\Logging\LogActionTypes) {
            return $this
                ->addUsingAlias(LoggingTableMap::COL_LOG_TYPE, $logActionTypes->getId(), $comparison);
        } elseif ($logActionTypes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LoggingTableMap::COL_LOG_TYPE, $logActionTypes->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLogActionTypes() only accepts arguments of type \Models\Logging\LogActionTypes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LogActionTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function joinLogActionTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LogActionTypes');

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
            $this->addJoinObject($join, 'LogActionTypes');
        }

        return $this;
    }

    /**
     * Use the LogActionTypes relation LogActionTypes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Logging\LogActionTypesQuery A secondary query class using the current class as primary query
     */
    public function useLogActionTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLogActionTypes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LogActionTypes', '\Models\Logging\LogActionTypesQuery');
    }

    /**
     * Filter the query by a related \Models\Logging\LogTypes object
     *
     * @param \Models\Logging\LogTypes|ObjectCollection $logTypes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLoggingQuery The current query, for fluid interface
     */
    public function filterByLogTypes($logTypes, $comparison = null)
    {
        if ($logTypes instanceof \Models\Logging\LogTypes) {
            return $this
                ->addUsingAlias(LoggingTableMap::COL_ACTION_TYPE, $logTypes->getId(), $comparison);
        } elseif ($logTypes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LoggingTableMap::COL_ACTION_TYPE, $logTypes->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLogTypes() only accepts arguments of type \Models\Logging\LogTypes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LogTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function joinLogTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LogTypes');

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
            $this->addJoinObject($join, 'LogTypes');
        }

        return $this;
    }

    /**
     * Use the LogTypes relation LogTypes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Logging\LogTypesQuery A secondary query class using the current class as primary query
     */
    public function useLogTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLogTypes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LogTypes', '\Models\Logging\LogTypesQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLogging $logging Object to remove from the list of results
     *
     * @return $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function prune($logging = null)
    {
        if ($logging) {
            $this->addCond('pruneCond0', $this->getAliasedColName(LoggingTableMap::COL_ID), $logging->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(LoggingTableMap::COL_USER_ID), $logging->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(LoggingTableMap::COL_LOG_TYPE), $logging->getLogType(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond3', $this->getAliasedColName(LoggingTableMap::COL_ACTION_TYPE), $logging->getActionType(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2', 'pruneCond3'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the logging table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LoggingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LoggingTableMap::clearInstancePool();
            LoggingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LoggingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LoggingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LoggingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LoggingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LoggingTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LoggingTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LoggingTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LoggingTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LoggingTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildLoggingQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LoggingTableMap::COL_CREATED_AT);
    }

} // LoggingQuery
