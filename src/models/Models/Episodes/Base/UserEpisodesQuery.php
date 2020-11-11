<?php

namespace Models\Episodes\Base;

use \Exception;
use \PDO;
use Models\Bookmarks\Bookmarks;
use Models\Episodes\UserEpisodes as ChildUserEpisodes;
use Models\Episodes\UserEpisodesQuery as ChildUserEpisodesQuery;
use Models\Episodes\Map\UserEpisodesTableMap;
use Models\Users\Users;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user_episodes' table.
 *
 *
 *
 * @method     ChildUserEpisodesQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUserEpisodesQuery orderByEpisodeId($order = Criteria::ASC) Order by the episode_id column
 * @method     ChildUserEpisodesQuery orderByDownloaded($order = Criteria::ASC) Order by the downloaded column
 * @method     ChildUserEpisodesQuery orderBySaved($order = Criteria::ASC) Order by the saved column
 * @method     ChildUserEpisodesQuery orderByLastProgress($order = Criteria::ASC) Order by the last_progress column
 * @method     ChildUserEpisodesQuery orderByLastPlayed($order = Criteria::ASC) Order by the last_played column
 * @method     ChildUserEpisodesQuery orderByBookmarkId($order = Criteria::ASC) Order by the bookmark_id column
 * @method     ChildUserEpisodesQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserEpisodesQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserEpisodesQuery groupByUserId() Group by the user_id column
 * @method     ChildUserEpisodesQuery groupByEpisodeId() Group by the episode_id column
 * @method     ChildUserEpisodesQuery groupByDownloaded() Group by the downloaded column
 * @method     ChildUserEpisodesQuery groupBySaved() Group by the saved column
 * @method     ChildUserEpisodesQuery groupByLastProgress() Group by the last_progress column
 * @method     ChildUserEpisodesQuery groupByLastPlayed() Group by the last_played column
 * @method     ChildUserEpisodesQuery groupByBookmarkId() Group by the bookmark_id column
 * @method     ChildUserEpisodesQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserEpisodesQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserEpisodesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserEpisodesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserEpisodesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserEpisodesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserEpisodesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserEpisodesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserEpisodesQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildUserEpisodesQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildUserEpisodesQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildUserEpisodesQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildUserEpisodesQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildUserEpisodesQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildUserEpisodesQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildUserEpisodesQuery leftJoinEpisode($relationAlias = null) Adds a LEFT JOIN clause to the query using the Episode relation
 * @method     ChildUserEpisodesQuery rightJoinEpisode($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Episode relation
 * @method     ChildUserEpisodesQuery innerJoinEpisode($relationAlias = null) Adds a INNER JOIN clause to the query using the Episode relation
 *
 * @method     ChildUserEpisodesQuery joinWithEpisode($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Episode relation
 *
 * @method     ChildUserEpisodesQuery leftJoinWithEpisode() Adds a LEFT JOIN clause and with to the query using the Episode relation
 * @method     ChildUserEpisodesQuery rightJoinWithEpisode() Adds a RIGHT JOIN clause and with to the query using the Episode relation
 * @method     ChildUserEpisodesQuery innerJoinWithEpisode() Adds a INNER JOIN clause and with to the query using the Episode relation
 *
 * @method     ChildUserEpisodesQuery leftJoinBookmark($relationAlias = null) Adds a LEFT JOIN clause to the query using the Bookmark relation
 * @method     ChildUserEpisodesQuery rightJoinBookmark($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Bookmark relation
 * @method     ChildUserEpisodesQuery innerJoinBookmark($relationAlias = null) Adds a INNER JOIN clause to the query using the Bookmark relation
 *
 * @method     ChildUserEpisodesQuery joinWithBookmark($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Bookmark relation
 *
 * @method     ChildUserEpisodesQuery leftJoinWithBookmark() Adds a LEFT JOIN clause and with to the query using the Bookmark relation
 * @method     ChildUserEpisodesQuery rightJoinWithBookmark() Adds a RIGHT JOIN clause and with to the query using the Bookmark relation
 * @method     ChildUserEpisodesQuery innerJoinWithBookmark() Adds a INNER JOIN clause and with to the query using the Bookmark relation
 *
 * @method     \Models\Users\UsersQuery|\Models\Episodes\EpisodesQuery|\Models\Bookmarks\BookmarksQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUserEpisodes findOne(ConnectionInterface $con = null) Return the first ChildUserEpisodes matching the query
 * @method     ChildUserEpisodes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUserEpisodes matching the query, or a new ChildUserEpisodes object populated from the query conditions when no match is found
 *
 * @method     ChildUserEpisodes findOneByUserId(string $user_id) Return the first ChildUserEpisodes filtered by the user_id column
 * @method     ChildUserEpisodes findOneByEpisodeId(string $episode_id) Return the first ChildUserEpisodes filtered by the episode_id column
 * @method     ChildUserEpisodes findOneByDownloaded(int $downloaded) Return the first ChildUserEpisodes filtered by the downloaded column
 * @method     ChildUserEpisodes findOneBySaved(int $saved) Return the first ChildUserEpisodes filtered by the saved column
 * @method     ChildUserEpisodes findOneByLastProgress(string $last_progress) Return the first ChildUserEpisodes filtered by the last_progress column
 * @method     ChildUserEpisodes findOneByLastPlayed(string $last_played) Return the first ChildUserEpisodes filtered by the last_played column
 * @method     ChildUserEpisodes findOneByBookmarkId(string $bookmark_id) Return the first ChildUserEpisodes filtered by the bookmark_id column
 * @method     ChildUserEpisodes findOneByCreatedAt(string $created_at) Return the first ChildUserEpisodes filtered by the created_at column
 * @method     ChildUserEpisodes findOneByUpdatedAt(string $updated_at) Return the first ChildUserEpisodes filtered by the updated_at column *

 * @method     ChildUserEpisodes requirePk($key, ConnectionInterface $con = null) Return the ChildUserEpisodes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOne(ConnectionInterface $con = null) Return the first ChildUserEpisodes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserEpisodes requireOneByUserId(string $user_id) Return the first ChildUserEpisodes filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByEpisodeId(string $episode_id) Return the first ChildUserEpisodes filtered by the episode_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByDownloaded(int $downloaded) Return the first ChildUserEpisodes filtered by the downloaded column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneBySaved(int $saved) Return the first ChildUserEpisodes filtered by the saved column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByLastProgress(string $last_progress) Return the first ChildUserEpisodes filtered by the last_progress column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByLastPlayed(string $last_played) Return the first ChildUserEpisodes filtered by the last_played column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByBookmarkId(string $bookmark_id) Return the first ChildUserEpisodes filtered by the bookmark_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByCreatedAt(string $created_at) Return the first ChildUserEpisodes filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUserEpisodes requireOneByUpdatedAt(string $updated_at) Return the first ChildUserEpisodes filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUserEpisodes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUserEpisodes objects based on current ModelCriteria
 * @method     ChildUserEpisodes[]|ObjectCollection findByUserId(string $user_id) Return ChildUserEpisodes objects filtered by the user_id column
 * @method     ChildUserEpisodes[]|ObjectCollection findByEpisodeId(string $episode_id) Return ChildUserEpisodes objects filtered by the episode_id column
 * @method     ChildUserEpisodes[]|ObjectCollection findByDownloaded(int $downloaded) Return ChildUserEpisodes objects filtered by the downloaded column
 * @method     ChildUserEpisodes[]|ObjectCollection findBySaved(int $saved) Return ChildUserEpisodes objects filtered by the saved column
 * @method     ChildUserEpisodes[]|ObjectCollection findByLastProgress(string $last_progress) Return ChildUserEpisodes objects filtered by the last_progress column
 * @method     ChildUserEpisodes[]|ObjectCollection findByLastPlayed(string $last_played) Return ChildUserEpisodes objects filtered by the last_played column
 * @method     ChildUserEpisodes[]|ObjectCollection findByBookmarkId(string $bookmark_id) Return ChildUserEpisodes objects filtered by the bookmark_id column
 * @method     ChildUserEpisodes[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildUserEpisodes objects filtered by the created_at column
 * @method     ChildUserEpisodes[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildUserEpisodes objects filtered by the updated_at column
 * @method     ChildUserEpisodes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserEpisodesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Episodes\Base\UserEpisodesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Episodes\\UserEpisodes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserEpisodesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserEpisodesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserEpisodesQuery) {
            return $criteria;
        }
        $query = new ChildUserEpisodesQuery();
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
     * @param array[$user_id, $episode_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUserEpisodes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserEpisodesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserEpisodesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildUserEpisodes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT user_id, episode_id, downloaded, saved, last_progress, last_played, bookmark_id, created_at, updated_at FROM user_episodes WHERE user_id = :p0 AND episode_id = :p1';
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
            /** @var ChildUserEpisodes $obj */
            $obj = new ChildUserEpisodes();
            $obj->hydrate($row);
            UserEpisodesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildUserEpisodes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UserEpisodesTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UserEpisodesTableMap::COL_EPISODE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UserEpisodesTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UserEpisodesTableMap::COL_EPISODE_ID, $key[1], Criteria::EQUAL);
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
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the episode_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEpisodeId('fooValue');   // WHERE episode_id = 'fooValue'
     * $query->filterByEpisodeId('%fooValue%', Criteria::LIKE); // WHERE episode_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $episodeId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByEpisodeId($episodeId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($episodeId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_EPISODE_ID, $episodeId, $comparison);
    }

    /**
     * Filter the query on the downloaded column
     *
     * Example usage:
     * <code>
     * $query->filterByDownloaded(1234); // WHERE downloaded = 1234
     * $query->filterByDownloaded(array(12, 34)); // WHERE downloaded IN (12, 34)
     * $query->filterByDownloaded(array('min' => 12)); // WHERE downloaded > 12
     * </code>
     *
     * @param     mixed $downloaded The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByDownloaded($downloaded = null, $comparison = null)
    {
        if (is_array($downloaded)) {
            $useMinMax = false;
            if (isset($downloaded['min'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_DOWNLOADED, $downloaded['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($downloaded['max'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_DOWNLOADED, $downloaded['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_DOWNLOADED, $downloaded, $comparison);
    }

    /**
     * Filter the query on the saved column
     *
     * Example usage:
     * <code>
     * $query->filterBySaved(1234); // WHERE saved = 1234
     * $query->filterBySaved(array(12, 34)); // WHERE saved IN (12, 34)
     * $query->filterBySaved(array('min' => 12)); // WHERE saved > 12
     * </code>
     *
     * @param     mixed $saved The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterBySaved($saved = null, $comparison = null)
    {
        if (is_array($saved)) {
            $useMinMax = false;
            if (isset($saved['min'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_SAVED, $saved['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($saved['max'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_SAVED, $saved['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_SAVED, $saved, $comparison);
    }

    /**
     * Filter the query on the last_progress column
     *
     * Example usage:
     * <code>
     * $query->filterByLastProgress('fooValue');   // WHERE last_progress = 'fooValue'
     * $query->filterByLastProgress('%fooValue%', Criteria::LIKE); // WHERE last_progress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastProgress The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByLastProgress($lastProgress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastProgress)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_LAST_PROGRESS, $lastProgress, $comparison);
    }

    /**
     * Filter the query on the last_played column
     *
     * Example usage:
     * <code>
     * $query->filterByLastPlayed('fooValue');   // WHERE last_played = 'fooValue'
     * $query->filterByLastPlayed('%fooValue%', Criteria::LIKE); // WHERE last_played LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastPlayed The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByLastPlayed($lastPlayed = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastPlayed)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_LAST_PLAYED, $lastPlayed, $comparison);
    }

    /**
     * Filter the query on the bookmark_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookmarkId('fooValue');   // WHERE bookmark_id = 'fooValue'
     * $query->filterByBookmarkId('%fooValue%', Criteria::LIKE); // WHERE bookmark_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bookmarkId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByBookmarkId($bookmarkId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bookmarkId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_BOOKMARK_ID, $bookmarkId, $comparison);
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
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserEpisodesTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserEpisodesTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Users\Users object
     *
     * @param \Models\Users\Users|ObjectCollection $users The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByUser($users, $comparison = null)
    {
        if ($users instanceof \Models\Users\Users) {
            return $this
                ->addUsingAlias(UserEpisodesTableMap::COL_USER_ID, $users->getId(), $comparison);
        } elseif ($users instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEpisodesTableMap::COL_USER_ID, $users->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Models\Users\Users or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
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
     * Use the User relation Users object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UsersQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Models\Users\UsersQuery');
    }

    /**
     * Filter the query by a related \Models\Episodes\Episodes object
     *
     * @param \Models\Episodes\Episodes|ObjectCollection $episodes The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByEpisode($episodes, $comparison = null)
    {
        if ($episodes instanceof \Models\Episodes\Episodes) {
            return $this
                ->addUsingAlias(UserEpisodesTableMap::COL_EPISODE_ID, $episodes->getId(), $comparison);
        } elseif ($episodes instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEpisodesTableMap::COL_EPISODE_ID, $episodes->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEpisode() only accepts arguments of type \Models\Episodes\Episodes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Episode relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function joinEpisode($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Episode');

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
            $this->addJoinObject($join, 'Episode');
        }

        return $this;
    }

    /**
     * Use the Episode relation Episodes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Episodes\EpisodesQuery A secondary query class using the current class as primary query
     */
    public function useEpisodeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEpisode($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Episode', '\Models\Episodes\EpisodesQuery');
    }

    /**
     * Filter the query by a related \Models\Bookmarks\Bookmarks object
     *
     * @param \Models\Bookmarks\Bookmarks|ObjectCollection $bookmarks The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function filterByBookmark($bookmarks, $comparison = null)
    {
        if ($bookmarks instanceof \Models\Bookmarks\Bookmarks) {
            return $this
                ->addUsingAlias(UserEpisodesTableMap::COL_BOOKMARK_ID, $bookmarks->getId(), $comparison);
        } elseif ($bookmarks instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserEpisodesTableMap::COL_BOOKMARK_ID, $bookmarks->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBookmark() only accepts arguments of type \Models\Bookmarks\Bookmarks or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Bookmark relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function joinBookmark($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Bookmark');

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
            $this->addJoinObject($join, 'Bookmark');
        }

        return $this;
    }

    /**
     * Use the Bookmark relation Bookmarks object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Bookmarks\BookmarksQuery A secondary query class using the current class as primary query
     */
    public function useBookmarkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookmark($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Bookmark', '\Models\Bookmarks\BookmarksQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUserEpisodes $userEpisodes Object to remove from the list of results
     *
     * @return $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function prune($userEpisodes = null)
    {
        if ($userEpisodes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UserEpisodesTableMap::COL_USER_ID), $userEpisodes->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UserEpisodesTableMap::COL_EPISODE_ID), $userEpisodes->getEpisodeId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user_episodes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserEpisodesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserEpisodesTableMap::clearInstancePool();
            UserEpisodesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserEpisodesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserEpisodesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserEpisodesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserEpisodesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserEpisodesTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserEpisodesTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserEpisodesTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserEpisodesTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserEpisodesTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserEpisodesQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserEpisodesTableMap::COL_CREATED_AT);
    }

} // UserEpisodesQuery
