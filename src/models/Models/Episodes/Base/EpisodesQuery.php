<?php

namespace Models\Episodes\Base;

use \Exception;
use \PDO;
use Models\Bookmarks\Bookmarks;
use Models\Episodes\Episodes as ChildEpisodes;
use Models\Episodes\EpisodesQuery as ChildEpisodesQuery;
use Models\Episodes\Map\EpisodesTableMap;
use Models\Podcasts\Podcasts;
use Models\UserTags\UserEpisodeTags;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'episodes' table.
 *
 *
 *
 * @method     ChildEpisodesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEpisodesQuery orderByEpisodeId($order = Criteria::ASC) Order by the episode_id column
 * @method     ChildEpisodesQuery orderByPodcastId($order = Criteria::ASC) Order by the podcast_id column
 * @method     ChildEpisodesQuery orderByNumberOfPlays($order = Criteria::ASC) Order by the number_of_plays column
 * @method     ChildEpisodesQuery orderByNumberOfDownloads($order = Criteria::ASC) Order by the number_of_downloads column
 * @method     ChildEpisodesQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEpisodesQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildEpisodesQuery groupById() Group by the id column
 * @method     ChildEpisodesQuery groupByEpisodeId() Group by the episode_id column
 * @method     ChildEpisodesQuery groupByPodcastId() Group by the podcast_id column
 * @method     ChildEpisodesQuery groupByNumberOfPlays() Group by the number_of_plays column
 * @method     ChildEpisodesQuery groupByNumberOfDownloads() Group by the number_of_downloads column
 * @method     ChildEpisodesQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEpisodesQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildEpisodesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEpisodesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEpisodesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEpisodesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEpisodesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEpisodesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEpisodesQuery leftJoinPodcasts($relationAlias = null) Adds a LEFT JOIN clause to the query using the Podcasts relation
 * @method     ChildEpisodesQuery rightJoinPodcasts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Podcasts relation
 * @method     ChildEpisodesQuery innerJoinPodcasts($relationAlias = null) Adds a INNER JOIN clause to the query using the Podcasts relation
 *
 * @method     ChildEpisodesQuery joinWithPodcasts($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Podcasts relation
 *
 * @method     ChildEpisodesQuery leftJoinWithPodcasts() Adds a LEFT JOIN clause and with to the query using the Podcasts relation
 * @method     ChildEpisodesQuery rightJoinWithPodcasts() Adds a RIGHT JOIN clause and with to the query using the Podcasts relation
 * @method     ChildEpisodesQuery innerJoinWithPodcasts() Adds a INNER JOIN clause and with to the query using the Podcasts relation
 *
 * @method     ChildEpisodesQuery leftJoinUserEpisodes($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEpisodes relation
 * @method     ChildEpisodesQuery rightJoinUserEpisodes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEpisodes relation
 * @method     ChildEpisodesQuery innerJoinUserEpisodes($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEpisodes relation
 *
 * @method     ChildEpisodesQuery joinWithUserEpisodes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserEpisodes relation
 *
 * @method     ChildEpisodesQuery leftJoinWithUserEpisodes() Adds a LEFT JOIN clause and with to the query using the UserEpisodes relation
 * @method     ChildEpisodesQuery rightJoinWithUserEpisodes() Adds a RIGHT JOIN clause and with to the query using the UserEpisodes relation
 * @method     ChildEpisodesQuery innerJoinWithUserEpisodes() Adds a INNER JOIN clause and with to the query using the UserEpisodes relation
 *
 * @method     ChildEpisodesQuery leftJoinPlaylistEpisodes($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistEpisodes relation
 * @method     ChildEpisodesQuery rightJoinPlaylistEpisodes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistEpisodes relation
 * @method     ChildEpisodesQuery innerJoinPlaylistEpisodes($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistEpisodes relation
 *
 * @method     ChildEpisodesQuery joinWithPlaylistEpisodes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistEpisodes relation
 *
 * @method     ChildEpisodesQuery leftJoinWithPlaylistEpisodes() Adds a LEFT JOIN clause and with to the query using the PlaylistEpisodes relation
 * @method     ChildEpisodesQuery rightJoinWithPlaylistEpisodes() Adds a RIGHT JOIN clause and with to the query using the PlaylistEpisodes relation
 * @method     ChildEpisodesQuery innerJoinWithPlaylistEpisodes() Adds a INNER JOIN clause and with to the query using the PlaylistEpisodes relation
 *
 * @method     ChildEpisodesQuery leftJoinBookmarks($relationAlias = null) Adds a LEFT JOIN clause to the query using the Bookmarks relation
 * @method     ChildEpisodesQuery rightJoinBookmarks($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Bookmarks relation
 * @method     ChildEpisodesQuery innerJoinBookmarks($relationAlias = null) Adds a INNER JOIN clause to the query using the Bookmarks relation
 *
 * @method     ChildEpisodesQuery joinWithBookmarks($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Bookmarks relation
 *
 * @method     ChildEpisodesQuery leftJoinWithBookmarks() Adds a LEFT JOIN clause and with to the query using the Bookmarks relation
 * @method     ChildEpisodesQuery rightJoinWithBookmarks() Adds a RIGHT JOIN clause and with to the query using the Bookmarks relation
 * @method     ChildEpisodesQuery innerJoinWithBookmarks() Adds a INNER JOIN clause and with to the query using the Bookmarks relation
 *
 * @method     ChildEpisodesQuery leftJoinUserEpisodeTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEpisodeTags relation
 * @method     ChildEpisodesQuery rightJoinUserEpisodeTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEpisodeTags relation
 * @method     ChildEpisodesQuery innerJoinUserEpisodeTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEpisodeTags relation
 *
 * @method     ChildEpisodesQuery joinWithUserEpisodeTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserEpisodeTags relation
 *
 * @method     ChildEpisodesQuery leftJoinWithUserEpisodeTags() Adds a LEFT JOIN clause and with to the query using the UserEpisodeTags relation
 * @method     ChildEpisodesQuery rightJoinWithUserEpisodeTags() Adds a RIGHT JOIN clause and with to the query using the UserEpisodeTags relation
 * @method     ChildEpisodesQuery innerJoinWithUserEpisodeTags() Adds a INNER JOIN clause and with to the query using the UserEpisodeTags relation
 *
 * @method     \Models\Podcasts\PodcastsQuery|\Models\Episodes\UserEpisodesQuery|\Models\Episodes\PlaylistEpisodesQuery|\Models\Bookmarks\BookmarksQuery|\Models\UserTags\UserEpisodeTagsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEpisodes findOne(ConnectionInterface $con = null) Return the first ChildEpisodes matching the query
 * @method     ChildEpisodes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEpisodes matching the query, or a new ChildEpisodes object populated from the query conditions when no match is found
 *
 * @method     ChildEpisodes findOneById(string $id) Return the first ChildEpisodes filtered by the id column
 * @method     ChildEpisodes findOneByEpisodeId(string $episode_id) Return the first ChildEpisodes filtered by the episode_id column
 * @method     ChildEpisodes findOneByPodcastId(string $podcast_id) Return the first ChildEpisodes filtered by the podcast_id column
 * @method     ChildEpisodes findOneByNumberOfPlays(int $number_of_plays) Return the first ChildEpisodes filtered by the number_of_plays column
 * @method     ChildEpisodes findOneByNumberOfDownloads(int $number_of_downloads) Return the first ChildEpisodes filtered by the number_of_downloads column
 * @method     ChildEpisodes findOneByCreatedAt(string $created_at) Return the first ChildEpisodes filtered by the created_at column
 * @method     ChildEpisodes findOneByUpdatedAt(string $updated_at) Return the first ChildEpisodes filtered by the updated_at column *

 * @method     ChildEpisodes requirePk($key, ConnectionInterface $con = null) Return the ChildEpisodes by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOne(ConnectionInterface $con = null) Return the first ChildEpisodes matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEpisodes requireOneById(string $id) Return the first ChildEpisodes filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOneByEpisodeId(string $episode_id) Return the first ChildEpisodes filtered by the episode_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOneByPodcastId(string $podcast_id) Return the first ChildEpisodes filtered by the podcast_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOneByNumberOfPlays(int $number_of_plays) Return the first ChildEpisodes filtered by the number_of_plays column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOneByNumberOfDownloads(int $number_of_downloads) Return the first ChildEpisodes filtered by the number_of_downloads column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOneByCreatedAt(string $created_at) Return the first ChildEpisodes filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEpisodes requireOneByUpdatedAt(string $updated_at) Return the first ChildEpisodes filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEpisodes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEpisodes objects based on current ModelCriteria
 * @method     ChildEpisodes[]|ObjectCollection findById(string $id) Return ChildEpisodes objects filtered by the id column
 * @method     ChildEpisodes[]|ObjectCollection findByEpisodeId(string $episode_id) Return ChildEpisodes objects filtered by the episode_id column
 * @method     ChildEpisodes[]|ObjectCollection findByPodcastId(string $podcast_id) Return ChildEpisodes objects filtered by the podcast_id column
 * @method     ChildEpisodes[]|ObjectCollection findByNumberOfPlays(int $number_of_plays) Return ChildEpisodes objects filtered by the number_of_plays column
 * @method     ChildEpisodes[]|ObjectCollection findByNumberOfDownloads(int $number_of_downloads) Return ChildEpisodes objects filtered by the number_of_downloads column
 * @method     ChildEpisodes[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildEpisodes objects filtered by the created_at column
 * @method     ChildEpisodes[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildEpisodes objects filtered by the updated_at column
 * @method     ChildEpisodes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EpisodesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Episodes\Base\EpisodesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Episodes\\Episodes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEpisodesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEpisodesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEpisodesQuery) {
            return $criteria;
        }
        $query = new ChildEpisodesQuery();
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
     * @return ChildEpisodes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EpisodesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EpisodesTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEpisodes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, episode_id, podcast_id, number_of_plays, number_of_downloads, created_at, updated_at FROM episodes WHERE id = :p0';
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
            /** @var ChildEpisodes $obj */
            $obj = new ChildEpisodes();
            $obj->hydrate($row);
            EpisodesTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEpisodes|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EpisodesTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EpisodesTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByEpisodeId($episodeId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($episodeId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_EPISODE_ID, $episodeId, $comparison);
    }

    /**
     * Filter the query on the podcast_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPodcastId('fooValue');   // WHERE podcast_id = 'fooValue'
     * $query->filterByPodcastId('%fooValue%', Criteria::LIKE); // WHERE podcast_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $podcastId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByPodcastId($podcastId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($podcastId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_PODCAST_ID, $podcastId, $comparison);
    }

    /**
     * Filter the query on the number_of_plays column
     *
     * Example usage:
     * <code>
     * $query->filterByNumberOfPlays(1234); // WHERE number_of_plays = 1234
     * $query->filterByNumberOfPlays(array(12, 34)); // WHERE number_of_plays IN (12, 34)
     * $query->filterByNumberOfPlays(array('min' => 12)); // WHERE number_of_plays > 12
     * </code>
     *
     * @param     mixed $numberOfPlays The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByNumberOfPlays($numberOfPlays = null, $comparison = null)
    {
        if (is_array($numberOfPlays)) {
            $useMinMax = false;
            if (isset($numberOfPlays['min'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_NUMBER_OF_PLAYS, $numberOfPlays['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numberOfPlays['max'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_NUMBER_OF_PLAYS, $numberOfPlays['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_NUMBER_OF_PLAYS, $numberOfPlays, $comparison);
    }

    /**
     * Filter the query on the number_of_downloads column
     *
     * Example usage:
     * <code>
     * $query->filterByNumberOfDownloads(1234); // WHERE number_of_downloads = 1234
     * $query->filterByNumberOfDownloads(array(12, 34)); // WHERE number_of_downloads IN (12, 34)
     * $query->filterByNumberOfDownloads(array('min' => 12)); // WHERE number_of_downloads > 12
     * </code>
     *
     * @param     mixed $numberOfDownloads The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByNumberOfDownloads($numberOfDownloads = null, $comparison = null)
    {
        if (is_array($numberOfDownloads)) {
            $useMinMax = false;
            if (isset($numberOfDownloads['min'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS, $numberOfDownloads['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numberOfDownloads['max'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS, $numberOfDownloads['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_NUMBER_OF_DOWNLOADS, $numberOfDownloads, $comparison);
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
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EpisodesTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EpisodesTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Podcasts\Podcasts object
     *
     * @param \Models\Podcasts\Podcasts|ObjectCollection $podcasts The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByPodcasts($podcasts, $comparison = null)
    {
        if ($podcasts instanceof \Models\Podcasts\Podcasts) {
            return $this
                ->addUsingAlias(EpisodesTableMap::COL_PODCAST_ID, $podcasts->getId(), $comparison);
        } elseif ($podcasts instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EpisodesTableMap::COL_PODCAST_ID, $podcasts->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPodcasts() only accepts arguments of type \Models\Podcasts\Podcasts or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Podcasts relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function joinPodcasts($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Podcasts');

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
            $this->addJoinObject($join, 'Podcasts');
        }

        return $this;
    }

    /**
     * Use the Podcasts relation Podcasts object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Podcasts\PodcastsQuery A secondary query class using the current class as primary query
     */
    public function usePodcastsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPodcasts($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Podcasts', '\Models\Podcasts\PodcastsQuery');
    }

    /**
     * Filter the query by a related \Models\Episodes\UserEpisodes object
     *
     * @param \Models\Episodes\UserEpisodes|ObjectCollection $userEpisodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByUserEpisodes($userEpisodes, $comparison = null)
    {
        if ($userEpisodes instanceof \Models\Episodes\UserEpisodes) {
            return $this
                ->addUsingAlias(EpisodesTableMap::COL_ID, $userEpisodes->getEpisodeId(), $comparison);
        } elseif ($userEpisodes instanceof ObjectCollection) {
            return $this
                ->useUserEpisodesQuery()
                ->filterByPrimaryKeys($userEpisodes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserEpisodes() only accepts arguments of type \Models\Episodes\UserEpisodes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserEpisodes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function joinUserEpisodes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserEpisodes');

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
            $this->addJoinObject($join, 'UserEpisodes');
        }

        return $this;
    }

    /**
     * Use the UserEpisodes relation UserEpisodes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Episodes\UserEpisodesQuery A secondary query class using the current class as primary query
     */
    public function useUserEpisodesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserEpisodes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserEpisodes', '\Models\Episodes\UserEpisodesQuery');
    }

    /**
     * Filter the query by a related \Models\Episodes\PlaylistEpisodes object
     *
     * @param \Models\Episodes\PlaylistEpisodes|ObjectCollection $playlistEpisodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByPlaylistEpisodes($playlistEpisodes, $comparison = null)
    {
        if ($playlistEpisodes instanceof \Models\Episodes\PlaylistEpisodes) {
            return $this
                ->addUsingAlias(EpisodesTableMap::COL_ID, $playlistEpisodes->getEpisodeId(), $comparison);
        } elseif ($playlistEpisodes instanceof ObjectCollection) {
            return $this
                ->usePlaylistEpisodesQuery()
                ->filterByPrimaryKeys($playlistEpisodes->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlaylistEpisodes() only accepts arguments of type \Models\Episodes\PlaylistEpisodes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlaylistEpisodes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function joinPlaylistEpisodes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlaylistEpisodes');

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
            $this->addJoinObject($join, 'PlaylistEpisodes');
        }

        return $this;
    }

    /**
     * Use the PlaylistEpisodes relation PlaylistEpisodes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Episodes\PlaylistEpisodesQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistEpisodesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylistEpisodes($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlaylistEpisodes', '\Models\Episodes\PlaylistEpisodesQuery');
    }

    /**
     * Filter the query by a related \Models\Bookmarks\Bookmarks object
     *
     * @param \Models\Bookmarks\Bookmarks|ObjectCollection $bookmarks the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByBookmarks($bookmarks, $comparison = null)
    {
        if ($bookmarks instanceof \Models\Bookmarks\Bookmarks) {
            return $this
                ->addUsingAlias(EpisodesTableMap::COL_ID, $bookmarks->getEpisodeId(), $comparison);
        } elseif ($bookmarks instanceof ObjectCollection) {
            return $this
                ->useBookmarksQuery()
                ->filterByPrimaryKeys($bookmarks->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookmarks() only accepts arguments of type \Models\Bookmarks\Bookmarks or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Bookmarks relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function joinBookmarks($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Bookmarks');

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
            $this->addJoinObject($join, 'Bookmarks');
        }

        return $this;
    }

    /**
     * Use the Bookmarks relation Bookmarks object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Bookmarks\BookmarksQuery A secondary query class using the current class as primary query
     */
    public function useBookmarksQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBookmarks($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Bookmarks', '\Models\Bookmarks\BookmarksQuery');
    }

    /**
     * Filter the query by a related \Models\UserTags\UserEpisodeTags object
     *
     * @param \Models\UserTags\UserEpisodeTags|ObjectCollection $userEpisodeTags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByUserEpisodeTags($userEpisodeTags, $comparison = null)
    {
        if ($userEpisodeTags instanceof \Models\UserTags\UserEpisodeTags) {
            return $this
                ->addUsingAlias(EpisodesTableMap::COL_ID, $userEpisodeTags->getEpisodeId(), $comparison);
        } elseif ($userEpisodeTags instanceof ObjectCollection) {
            return $this
                ->useUserEpisodeTagsQuery()
                ->filterByPrimaryKeys($userEpisodeTags->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserEpisodeTags() only accepts arguments of type \Models\UserTags\UserEpisodeTags or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserEpisodeTags relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function joinUserEpisodeTags($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserEpisodeTags');

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
            $this->addJoinObject($join, 'UserEpisodeTags');
        }

        return $this;
    }

    /**
     * Use the UserEpisodeTags relation UserEpisodeTags object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserTags\UserEpisodeTagsQuery A secondary query class using the current class as primary query
     */
    public function useUserEpisodeTagsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserEpisodeTags($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserEpisodeTags', '\Models\UserTags\UserEpisodeTagsQuery');
    }

    /**
     * Filter the query by a related Playlists object
     * using the playlist_episodes table as cross reference
     *
     * @param Playlists $playlists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByPlaylist($playlists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePlaylistEpisodesQuery()
            ->filterByPlaylist($playlists, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Tags object
     * using the user_episode_tags table as cross reference
     *
     * @param Tags $tags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByEpisodeTag($tags, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEpisodeTagsQuery()
            ->filterByEpisodeTag($tags, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Users object
     * using the user_episode_tags table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEpisodesQuery The current query, for fluid interface
     */
    public function filterByUsersEpisodeTags($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEpisodeTagsQuery()
            ->filterByUsersEpisodeTags($users, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEpisodes $episodes Object to remove from the list of results
     *
     * @return $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function prune($episodes = null)
    {
        if ($episodes) {
            $this->addUsingAlias(EpisodesTableMap::COL_ID, $episodes->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the episodes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EpisodesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EpisodesTableMap::clearInstancePool();
            EpisodesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EpisodesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EpisodesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EpisodesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EpisodesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EpisodesTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EpisodesTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EpisodesTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EpisodesTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EpisodesTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildEpisodesQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EpisodesTableMap::COL_CREATED_AT);
    }

} // EpisodesQuery
