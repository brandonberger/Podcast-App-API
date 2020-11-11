<?php

namespace Models\Playlists\Base;

use \Exception;
use \PDO;
use Models\Playlists\UserPlaylists as ChildUserPlaylists;
use Models\Playlists\UserPlaylistsQuery as ChildUserPlaylistsQuery;
use Models\Playlists\Map\UserPlaylistsTableMap;
use Models\Users\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user_playlists' table.
 *
 *
 *
 * @method     ChildUserPlaylistsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUserPlaylistsQuery orderByPlaylistId($order = Criteria::ASC) Order by the playlist_id column
 *
 * @method     ChildUserPlaylistsQuery groupByUserId() Group by the user_id column
 * @method     ChildUserPlaylistsQuery groupByPlaylistId() Group by the playlist_id column
 *
 * @method     ChildUserPlaylistsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserPlaylistsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserPlaylistsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserPlaylistsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserPlaylistsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserPlaylistsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserPlaylistsQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildUserPlaylistsQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildUserPlaylistsQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildUserPlaylistsQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildUserPlaylistsQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildUserPlaylistsQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildUserPlaylistsQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildUserPlaylistsQuery leftJoinPlaylists($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playlists relation
 * @method     ChildUserPlaylistsQuery rightJoinPlaylists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playlists relation
 * @method     ChildUserPlaylistsQuery innerJoinPlaylists($relationAlias = null) Adds a INNER JOIN clause to the query using the Playlists relation
 *
 * @method     ChildUserPlaylistsQuery joinWithPlaylists($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Playlists relation
 *
 * @method     ChildUserPlaylistsQuery leftJoinWithPlaylists() Adds a LEFT JOIN clause and with to the query using the Playlists relation
 * @method     ChildUserPlaylistsQuery rightJoinWithPlaylists() Adds a RIGHT JOIN clause and with to the query using the Playlists relation
 * @method     ChildUserPlaylistsQuery innerJoinWithPlaylists() Adds a INNER JOIN clause and with to the query using the Playlists relation
 *
 * @method     \Models\Users\UsersQuery|\Models\Playlists\PlaylistsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserPlaylists findOne(ConnectionInterface $con = null) Return the first ChildUserPlaylists matching the query
 * @method     ChildUserPlaylists findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserPlaylists matching the query, or a new ChildUserPlaylists object populated from the query conditions when no match is found
 *
 * @method     ChildUserPlaylists findOneByUserId(string $user_id) Return the first ChildUserPlaylists filtered by the user_id column
 * @method     ChildUserPlaylists findOneByPlaylistId(string $playlist_id) Return the first ChildUserPlaylists filtered by the playlist_id column *

 * @method     ChildUserPlaylists requirePk($key, ConnectionInterface $con = null) Return the ChildUserPlaylists by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserPlaylists requireOne(ConnectionInterface $con = null) Return the first ChildUserPlaylists matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserPlaylists requireOneByUserId(string $user_id) Return the first ChildUserPlaylists filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserPlaylists requireOneByPlaylistId(string $playlist_id) Return the first ChildUserPlaylists filtered by the playlist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserPlaylists[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserPlaylists objects based on current ModelCriteria
 * @method     ChildUserPlaylists[]|ObjectCollection findByUserId(string $user_id) Return ChildUserPlaylists objects filtered by the user_id column
 * @method     ChildUserPlaylists[]|ObjectCollection findByPlaylistId(string $playlist_id) Return ChildUserPlaylists objects filtered by the playlist_id column
 * @method     ChildUserPlaylists[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserPlaylistsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Playlists\Base\UserPlaylistsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Playlists\\UserPlaylists', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserPlaylistsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserPlaylistsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserPlaylistsQuery) {
            return $criteria;
        }
        $query = new ChildUserPlaylistsQuery();
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
     * @param array[$user_id, $playlist_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUserPlaylists|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserPlaylistsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserPlaylistsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildUserPlaylists A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT user_id, playlist_id FROM user_playlists WHERE user_id = :p0 AND playlist_id = :p1';
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
            /** @var ChildUserPlaylists $obj */
            $obj = new ChildUserPlaylists();
            $obj->hydrate($row);
            UserPlaylistsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildUserPlaylists|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserPlaylistsTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserPlaylistsTableMap::COL_PLAYLIST_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserPlaylistsTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserPlaylistsTableMap::COL_PLAYLIST_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPlaylistsTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the playlist_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlaylistId('fooValue');   // WHERE playlist_id = 'fooValue'
     * $query->filterByPlaylistId('%fooValue%', Criteria::LIKE); // WHERE playlist_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $playlistId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistId($playlistId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($playlistId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPlaylistsTableMap::COL_PLAYLIST_ID, $playlistId, $comparison);
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(UserPlaylistsTableMap::COL_USER_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPlaylistsTableMap::COL_USER_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Playlists\Playlists object
     *
     * @param \Models\Playlists\Playlists|ObjectCollection $playlists The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylists($playlists, $comparison = null)
    {
        if ($playlists instanceof \Models\Playlists\Playlists) {
            return $this
                ->addUsingAlias(UserPlaylistsTableMap::COL_PLAYLIST_ID, $playlists->getId(), $comparison);
        } elseif ($playlists instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserPlaylistsTableMap::COL_PLAYLIST_ID, $playlists->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlaylists() only accepts arguments of type \Models\Playlists\Playlists or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Playlists relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function joinPlaylists($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Playlists');

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
            $this->addJoinObject($join, 'Playlists');
        }

        return $this;
    }

    /**
     * Use the Playlists relation Playlists object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistsQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylists($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Playlists', '\Models\Playlists\PlaylistsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserPlaylists $userPlaylists Object to remove from the list of results
     *
     * @return $this|ChildUserPlaylistsQuery The current query, for fluid interface
     */
    public function prune($userPlaylists = null)
    {
        if ($userPlaylists) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserPlaylistsTableMap::COL_USER_ID), $userPlaylists->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserPlaylistsTableMap::COL_PLAYLIST_ID), $userPlaylists->getPlaylistId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_playlists table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserPlaylistsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserPlaylistsTableMap::clearInstancePool();
            UserPlaylistsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserPlaylistsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserPlaylistsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserPlaylistsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserPlaylistsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserPlaylistsQuery
