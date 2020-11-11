<?php

namespace Models\Users\Base;

use \Exception;
use \PDO;
use Models\Users\UserRelations as ChildUserRelations;
use Models\Users\UserRelationsQuery as ChildUserRelationsQuery;
use Models\Users\Map\UserRelationsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user_relations' table.
 *
 *
 *
 * @method     ChildUserRelationsQuery orderByFollowerId($order = Criteria::ASC) Order by the follower_id column
 * @method     ChildUserRelationsQuery orderByFollowingId($order = Criteria::ASC) Order by the following_id column
 *
 * @method     ChildUserRelationsQuery groupByFollowerId() Group by the follower_id column
 * @method     ChildUserRelationsQuery groupByFollowingId() Group by the following_id column
 *
 * @method     ChildUserRelationsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserRelationsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserRelationsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserRelationsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserRelationsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserRelationsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserRelationsQuery leftJoinFollower($relationAlias = null) Adds a LEFT JOIN clause to the query using the Follower relation
 * @method     ChildUserRelationsQuery rightJoinFollower($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Follower relation
 * @method     ChildUserRelationsQuery innerJoinFollower($relationAlias = null) Adds a INNER JOIN clause to the query using the Follower relation
 *
 * @method     ChildUserRelationsQuery joinWithFollower($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Follower relation
 *
 * @method     ChildUserRelationsQuery leftJoinWithFollower() Adds a LEFT JOIN clause and with to the query using the Follower relation
 * @method     ChildUserRelationsQuery rightJoinWithFollower() Adds a RIGHT JOIN clause and with to the query using the Follower relation
 * @method     ChildUserRelationsQuery innerJoinWithFollower() Adds a INNER JOIN clause and with to the query using the Follower relation
 *
 * @method     ChildUserRelationsQuery leftJoinFollowing($relationAlias = null) Adds a LEFT JOIN clause to the query using the Following relation
 * @method     ChildUserRelationsQuery rightJoinFollowing($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Following relation
 * @method     ChildUserRelationsQuery innerJoinFollowing($relationAlias = null) Adds a INNER JOIN clause to the query using the Following relation
 *
 * @method     ChildUserRelationsQuery joinWithFollowing($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Following relation
 *
 * @method     ChildUserRelationsQuery leftJoinWithFollowing() Adds a LEFT JOIN clause and with to the query using the Following relation
 * @method     ChildUserRelationsQuery rightJoinWithFollowing() Adds a RIGHT JOIN clause and with to the query using the Following relation
 * @method     ChildUserRelationsQuery innerJoinWithFollowing() Adds a INNER JOIN clause and with to the query using the Following relation
 *
 * @method     \Models\Users\UsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserRelations findOne(ConnectionInterface $con = null) Return the first ChildUserRelations matching the query
 * @method     ChildUserRelations findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserRelations matching the query, or a new ChildUserRelations object populated from the query conditions when no match is found
 *
 * @method     ChildUserRelations findOneByFollowerId(string $follower_id) Return the first ChildUserRelations filtered by the follower_id column
 * @method     ChildUserRelations findOneByFollowingId(string $following_id) Return the first ChildUserRelations filtered by the following_id column *

 * @method     ChildUserRelations requirePk($key, ConnectionInterface $con = null) Return the ChildUserRelations by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserRelations requireOne(ConnectionInterface $con = null) Return the first ChildUserRelations matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserRelations requireOneByFollowerId(string $follower_id) Return the first ChildUserRelations filtered by the follower_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserRelations requireOneByFollowingId(string $following_id) Return the first ChildUserRelations filtered by the following_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserRelations[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserRelations objects based on current ModelCriteria
 * @method     ChildUserRelations[]|ObjectCollection findByFollowerId(string $follower_id) Return ChildUserRelations objects filtered by the follower_id column
 * @method     ChildUserRelations[]|ObjectCollection findByFollowingId(string $following_id) Return ChildUserRelations objects filtered by the following_id column
 * @method     ChildUserRelations[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserRelationsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Users\Base\UserRelationsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Users\\UserRelations', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserRelationsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserRelationsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserRelationsQuery) {
            return $criteria;
        }
        $query = new ChildUserRelationsQuery();
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
     * @param array[$follower_id, $following_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUserRelations|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserRelationsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserRelationsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildUserRelations A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT follower_id, following_id FROM user_relations WHERE follower_id = :p0 AND following_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_STR);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUserRelations $obj */
            $obj = new ChildUserRelations();
            $obj->hydrate($row);
            UserRelationsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildUserRelations|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserRelationsTableMap::COL_FOLLOWER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserRelationsTableMap::COL_FOLLOWING_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserRelationsTableMap::COL_FOLLOWER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserRelationsTableMap::COL_FOLLOWING_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the follower_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFollowerId('fooValue');   // WHERE follower_id = 'fooValue'
     * $query->filterByFollowerId('%fooValue%', Criteria::LIKE); // WHERE follower_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $followerId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function filterByFollowerId($followerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($followerId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserRelationsTableMap::COL_FOLLOWER_ID, $followerId, $comparison);
    }

    /**
     * Filter the query on the following_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFollowingId('fooValue');   // WHERE following_id = 'fooValue'
     * $query->filterByFollowingId('%fooValue%', Criteria::LIKE); // WHERE following_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $followingId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function filterByFollowingId($followingId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($followingId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserRelationsTableMap::COL_FOLLOWING_ID, $followingId, $comparison);
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserRelationsQuery The current query, for fluid interface
     */
    public function filterByFollower($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(UserRelationsTableMap::COL_FOLLOWER_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserRelationsTableMap::COL_FOLLOWER_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFollower() only accepts arguments of type \Models\Users\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Follower relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function joinFollower($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Follower');

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
            $this->addJoinObject($join, 'Follower');
        }

        return $this;
    }

    /**
     * Use the Follower relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UsersQuery A secondary query class using the current class as primary query
     */
    public function useFollowerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFollower($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Follower', '\Models\Users\UsersQuery');
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserRelationsQuery The current query, for fluid interface
     */
    public function filterByFollowing($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(UserRelationsTableMap::COL_FOLLOWING_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserRelationsTableMap::COL_FOLLOWING_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFollowing() only accepts arguments of type \Models\Users\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Following relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function joinFollowing($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Following');

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
            $this->addJoinObject($join, 'Following');
        }

        return $this;
    }

    /**
     * Use the Following relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UsersQuery A secondary query class using the current class as primary query
     */
    public function useFollowingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFollowing($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Following', '\Models\Users\UsersQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserRelations $userRelations Object to remove from the list of results
     *
     * @return $this|ChildUserRelationsQuery The current query, for fluid interface
     */
    public function prune($userRelations = null)
    {
        if ($userRelations) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserRelationsTableMap::COL_FOLLOWER_ID), $userRelations->getFollowerId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserRelationsTableMap::COL_FOLLOWING_ID), $userRelations->getFollowingId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_relations table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserRelationsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserRelationsTableMap::clearInstancePool();
            UserRelationsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserRelationsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserRelationsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserRelationsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserRelationsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserRelationsQuery
