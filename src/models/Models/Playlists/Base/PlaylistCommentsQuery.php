<?php

namespace Models\Playlists\Base;

use \Exception;
use \PDO;
use Models\Playlists\PlaylistComments as ChildPlaylistComments;
use Models\Playlists\PlaylistCommentsQuery as ChildPlaylistCommentsQuery;
use Models\Playlists\Map\PlaylistCommentsTableMap;
use Models\Users\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playlist_comments' table.
 *
 *
 *
 * @method     ChildPlaylistCommentsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlaylistCommentsQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method     ChildPlaylistCommentsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildPlaylistCommentsQuery orderByPlaylistId($order = Criteria::ASC) Order by the playlist_id column
 * @method     ChildPlaylistCommentsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlaylistCommentsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlaylistCommentsQuery groupById() Group by the id column
 * @method     ChildPlaylistCommentsQuery groupByComment() Group by the comment column
 * @method     ChildPlaylistCommentsQuery groupByUserId() Group by the user_id column
 * @method     ChildPlaylistCommentsQuery groupByPlaylistId() Group by the playlist_id column
 * @method     ChildPlaylistCommentsQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlaylistCommentsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlaylistCommentsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlaylistCommentsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlaylistCommentsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlaylistCommentsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlaylistCommentsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlaylistCommentsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlaylistCommentsQuery leftJoinUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the Users relation
 * @method     ChildPlaylistCommentsQuery rightJoinUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Users relation
 * @method     ChildPlaylistCommentsQuery innerJoinUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the Users relation
 *
 * @method     ChildPlaylistCommentsQuery joinWithUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Users relation
 *
 * @method     ChildPlaylistCommentsQuery leftJoinWithUsers() Adds a LEFT JOIN clause and with to the query using the Users relation
 * @method     ChildPlaylistCommentsQuery rightJoinWithUsers() Adds a RIGHT JOIN clause and with to the query using the Users relation
 * @method     ChildPlaylistCommentsQuery innerJoinWithUsers() Adds a INNER JOIN clause and with to the query using the Users relation
 *
 * @method     ChildPlaylistCommentsQuery leftJoinPlaylists($relationAlias = null) Adds a LEFT JOIN clause to the query using the Playlists relation
 * @method     ChildPlaylistCommentsQuery rightJoinPlaylists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Playlists relation
 * @method     ChildPlaylistCommentsQuery innerJoinPlaylists($relationAlias = null) Adds a INNER JOIN clause to the query using the Playlists relation
 *
 * @method     ChildPlaylistCommentsQuery joinWithPlaylists($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Playlists relation
 *
 * @method     ChildPlaylistCommentsQuery leftJoinWithPlaylists() Adds a LEFT JOIN clause and with to the query using the Playlists relation
 * @method     ChildPlaylistCommentsQuery rightJoinWithPlaylists() Adds a RIGHT JOIN clause and with to the query using the Playlists relation
 * @method     ChildPlaylistCommentsQuery innerJoinWithPlaylists() Adds a INNER JOIN clause and with to the query using the Playlists relation
 *
 * @method     \Models\Users\UsersQuery|\Models\Playlists\PlaylistsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlaylistComments findOne(ConnectionInterface $con = null) Return the first ChildPlaylistComments matching the query
 * @method     ChildPlaylistComments findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlaylistComments matching the query, or a new ChildPlaylistComments object populated from the query conditions when no match is found
 *
 * @method     ChildPlaylistComments findOneById(string $id) Return the first ChildPlaylistComments filtered by the id column
 * @method     ChildPlaylistComments findOneByComment(string $comment) Return the first ChildPlaylistComments filtered by the comment column
 * @method     ChildPlaylistComments findOneByUserId(string $user_id) Return the first ChildPlaylistComments filtered by the user_id column
 * @method     ChildPlaylistComments findOneByPlaylistId(string $playlist_id) Return the first ChildPlaylistComments filtered by the playlist_id column
 * @method     ChildPlaylistComments findOneByCreatedAt(string $created_at) Return the first ChildPlaylistComments filtered by the created_at column
 * @method     ChildPlaylistComments findOneByUpdatedAt(string $updated_at) Return the first ChildPlaylistComments filtered by the updated_at column *

 * @method     ChildPlaylistComments requirePk($key, ConnectionInterface $con = null) Return the ChildPlaylistComments by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistComments requireOne(ConnectionInterface $con = null) Return the first ChildPlaylistComments matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylistComments requireOneById(string $id) Return the first ChildPlaylistComments filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistComments requireOneByComment(string $comment) Return the first ChildPlaylistComments filtered by the comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistComments requireOneByUserId(string $user_id) Return the first ChildPlaylistComments filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistComments requireOneByPlaylistId(string $playlist_id) Return the first ChildPlaylistComments filtered by the playlist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistComments requireOneByCreatedAt(string $created_at) Return the first ChildPlaylistComments filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylistComments requireOneByUpdatedAt(string $updated_at) Return the first ChildPlaylistComments filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylistComments[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlaylistComments objects based on current ModelCriteria
 * @method     ChildPlaylistComments[]|ObjectCollection findById(string $id) Return ChildPlaylistComments objects filtered by the id column
 * @method     ChildPlaylistComments[]|ObjectCollection findByComment(string $comment) Return ChildPlaylistComments objects filtered by the comment column
 * @method     ChildPlaylistComments[]|ObjectCollection findByUserId(string $user_id) Return ChildPlaylistComments objects filtered by the user_id column
 * @method     ChildPlaylistComments[]|ObjectCollection findByPlaylistId(string $playlist_id) Return ChildPlaylistComments objects filtered by the playlist_id column
 * @method     ChildPlaylistComments[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlaylistComments objects filtered by the created_at column
 * @method     ChildPlaylistComments[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlaylistComments objects filtered by the updated_at column
 * @method     ChildPlaylistComments[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlaylistCommentsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Playlists\Base\PlaylistCommentsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Playlists\\PlaylistComments', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlaylistCommentsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlaylistCommentsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlaylistCommentsQuery) {
            return $criteria;
        }
        $query = new ChildPlaylistCommentsQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$id, $user_id, $playlist_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPlaylistComments|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlaylistCommentsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlaylistCommentsTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]))))) {
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
     * @return ChildPlaylistComments A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, comment, user_id, playlist_id, created_at, updated_at FROM playlist_comments WHERE id = :p0 AND user_id = :p1 AND playlist_id = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_STR);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPlaylistComments $obj */
            $obj = new ChildPlaylistComments();
            $obj->hydrate($row);
            PlaylistCommentsTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1]), (null === $key[2] || is_scalar($key[2]) || is_callable([$key[2], '__toString']) ? (string) $key[2] : $key[2])]));
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
     * @return ChildPlaylistComments|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PlaylistCommentsTableMap::COL_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PlaylistCommentsTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(PlaylistCommentsTableMap::COL_PLAYLIST_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PlaylistCommentsTableMap::COL_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PlaylistCommentsTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(PlaylistCommentsTableMap::COL_PLAYLIST_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
     * $query->filterByComment('%fooValue%', Criteria::LIKE); // WHERE comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_COMMENT, $comment, $comparison);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByPlaylistId($playlistId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($playlistId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_PLAYLIST_ID, $playlistId, $comparison);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlaylistCommentsTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlaylistCommentsTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlaylistCommentsTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlaylistCommentsTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(PlaylistCommentsTableMap::COL_USER_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlaylistCommentsTableMap::COL_USER_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
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
     * @return ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function filterByPlaylists($playlists, $comparison = null)
    {
        if ($playlists instanceof \Models\Playlists\Playlists) {
            return $this
                ->addUsingAlias(PlaylistCommentsTableMap::COL_PLAYLIST_ID, $playlists->getId(), $comparison);
        } elseif ($playlists instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlaylistCommentsTableMap::COL_PLAYLIST_ID, $playlists->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
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
     * @param   ChildPlaylistComments $playlistComments Object to remove from the list of results
     *
     * @return $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function prune($playlistComments = null)
    {
        if ($playlistComments) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PlaylistCommentsTableMap::COL_ID), $playlistComments->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PlaylistCommentsTableMap::COL_USER_ID), $playlistComments->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(PlaylistCommentsTableMap::COL_PLAYLIST_ID), $playlistComments->getPlaylistId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playlist_comments table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistCommentsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlaylistCommentsTableMap::clearInstancePool();
            PlaylistCommentsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistCommentsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlaylistCommentsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlaylistCommentsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlaylistCommentsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlaylistCommentsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlaylistCommentsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlaylistCommentsTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlaylistCommentsTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlaylistCommentsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlaylistCommentsTableMap::COL_CREATED_AT);
    }

} // PlaylistCommentsQuery
