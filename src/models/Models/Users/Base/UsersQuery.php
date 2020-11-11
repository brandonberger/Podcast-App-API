<?php

namespace Models\Users\Base;

use \Exception;
use \PDO;
use Models\AudioPlayerSettings\AudioPlayerSettings;
use Models\Episodes\UserEpisodes;
use Models\Logging\Logging;
use Models\Playlists\PlaylistComments;
use Models\Playlists\UserPlaylists;
use Models\UserPodcasts\UserPodcasts;
use Models\UserSettings\UserSettings;
use Models\UserTags\UserEpisodeTags;
use Models\UserTags\UserPlaylistTags;
use Models\Users\Users as ChildUsers;
use Models\Users\UsersQuery as ChildUsersQuery;
use Models\Users\Map\UsersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'users' table.
 *
 *
 *
 * @method     ChildUsersQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUsersQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUsersQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method     ChildUsersQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method     ChildUsersQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildUsersQuery orderByGoogleId($order = Criteria::ASC) Order by the google_id column
 * @method     ChildUsersQuery orderByImageUrl($order = Criteria::ASC) Order by the image_url column
 *
 * @method     ChildUsersQuery groupById() Group by the id column
 * @method     ChildUsersQuery groupByEmail() Group by the email column
 * @method     ChildUsersQuery groupByFirstName() Group by the first_name column
 * @method     ChildUsersQuery groupByLastName() Group by the last_name column
 * @method     ChildUsersQuery groupByActive() Group by the active column
 * @method     ChildUsersQuery groupByGoogleId() Group by the google_id column
 * @method     ChildUsersQuery groupByImageUrl() Group by the image_url column
 *
 * @method     ChildUsersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUsersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUsersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUsersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUsersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUsersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUsersQuery leftJoinUserSessions($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSessions relation
 * @method     ChildUsersQuery rightJoinUserSessions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSessions relation
 * @method     ChildUsersQuery innerJoinUserSessions($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSessions relation
 *
 * @method     ChildUsersQuery joinWithUserSessions($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserSessions relation
 *
 * @method     ChildUsersQuery leftJoinWithUserSessions() Adds a LEFT JOIN clause and with to the query using the UserSessions relation
 * @method     ChildUsersQuery rightJoinWithUserSessions() Adds a RIGHT JOIN clause and with to the query using the UserSessions relation
 * @method     ChildUsersQuery innerJoinWithUserSessions() Adds a INNER JOIN clause and with to the query using the UserSessions relation
 *
 * @method     ChildUsersQuery leftJoinUserRelationsRelatedByFollowerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelationsRelatedByFollowerId relation
 * @method     ChildUsersQuery rightJoinUserRelationsRelatedByFollowerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelationsRelatedByFollowerId relation
 * @method     ChildUsersQuery innerJoinUserRelationsRelatedByFollowerId($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelationsRelatedByFollowerId relation
 *
 * @method     ChildUsersQuery joinWithUserRelationsRelatedByFollowerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRelationsRelatedByFollowerId relation
 *
 * @method     ChildUsersQuery leftJoinWithUserRelationsRelatedByFollowerId() Adds a LEFT JOIN clause and with to the query using the UserRelationsRelatedByFollowerId relation
 * @method     ChildUsersQuery rightJoinWithUserRelationsRelatedByFollowerId() Adds a RIGHT JOIN clause and with to the query using the UserRelationsRelatedByFollowerId relation
 * @method     ChildUsersQuery innerJoinWithUserRelationsRelatedByFollowerId() Adds a INNER JOIN clause and with to the query using the UserRelationsRelatedByFollowerId relation
 *
 * @method     ChildUsersQuery leftJoinUserRelationsRelatedByFollowingId($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelationsRelatedByFollowingId relation
 * @method     ChildUsersQuery rightJoinUserRelationsRelatedByFollowingId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelationsRelatedByFollowingId relation
 * @method     ChildUsersQuery innerJoinUserRelationsRelatedByFollowingId($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelationsRelatedByFollowingId relation
 *
 * @method     ChildUsersQuery joinWithUserRelationsRelatedByFollowingId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRelationsRelatedByFollowingId relation
 *
 * @method     ChildUsersQuery leftJoinWithUserRelationsRelatedByFollowingId() Adds a LEFT JOIN clause and with to the query using the UserRelationsRelatedByFollowingId relation
 * @method     ChildUsersQuery rightJoinWithUserRelationsRelatedByFollowingId() Adds a RIGHT JOIN clause and with to the query using the UserRelationsRelatedByFollowingId relation
 * @method     ChildUsersQuery innerJoinWithUserRelationsRelatedByFollowingId() Adds a INNER JOIN clause and with to the query using the UserRelationsRelatedByFollowingId relation
 *
 * @method     ChildUsersQuery leftJoinAudioPlayerSettings($relationAlias = null) Adds a LEFT JOIN clause to the query using the AudioPlayerSettings relation
 * @method     ChildUsersQuery rightJoinAudioPlayerSettings($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AudioPlayerSettings relation
 * @method     ChildUsersQuery innerJoinAudioPlayerSettings($relationAlias = null) Adds a INNER JOIN clause to the query using the AudioPlayerSettings relation
 *
 * @method     ChildUsersQuery joinWithAudioPlayerSettings($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AudioPlayerSettings relation
 *
 * @method     ChildUsersQuery leftJoinWithAudioPlayerSettings() Adds a LEFT JOIN clause and with to the query using the AudioPlayerSettings relation
 * @method     ChildUsersQuery rightJoinWithAudioPlayerSettings() Adds a RIGHT JOIN clause and with to the query using the AudioPlayerSettings relation
 * @method     ChildUsersQuery innerJoinWithAudioPlayerSettings() Adds a INNER JOIN clause and with to the query using the AudioPlayerSettings relation
 *
 * @method     ChildUsersQuery leftJoinUserSettings($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSettings relation
 * @method     ChildUsersQuery rightJoinUserSettings($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSettings relation
 * @method     ChildUsersQuery innerJoinUserSettings($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSettings relation
 *
 * @method     ChildUsersQuery joinWithUserSettings($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserSettings relation
 *
 * @method     ChildUsersQuery leftJoinWithUserSettings() Adds a LEFT JOIN clause and with to the query using the UserSettings relation
 * @method     ChildUsersQuery rightJoinWithUserSettings() Adds a RIGHT JOIN clause and with to the query using the UserSettings relation
 * @method     ChildUsersQuery innerJoinWithUserSettings() Adds a INNER JOIN clause and with to the query using the UserSettings relation
 *
 * @method     ChildUsersQuery leftJoinUserPodcasts($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPodcasts relation
 * @method     ChildUsersQuery rightJoinUserPodcasts($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPodcasts relation
 * @method     ChildUsersQuery innerJoinUserPodcasts($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPodcasts relation
 *
 * @method     ChildUsersQuery joinWithUserPodcasts($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPodcasts relation
 *
 * @method     ChildUsersQuery leftJoinWithUserPodcasts() Adds a LEFT JOIN clause and with to the query using the UserPodcasts relation
 * @method     ChildUsersQuery rightJoinWithUserPodcasts() Adds a RIGHT JOIN clause and with to the query using the UserPodcasts relation
 * @method     ChildUsersQuery innerJoinWithUserPodcasts() Adds a INNER JOIN clause and with to the query using the UserPodcasts relation
 *
 * @method     ChildUsersQuery leftJoinUserPlaylists($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPlaylists relation
 * @method     ChildUsersQuery rightJoinUserPlaylists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPlaylists relation
 * @method     ChildUsersQuery innerJoinUserPlaylists($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPlaylists relation
 *
 * @method     ChildUsersQuery joinWithUserPlaylists($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPlaylists relation
 *
 * @method     ChildUsersQuery leftJoinWithUserPlaylists() Adds a LEFT JOIN clause and with to the query using the UserPlaylists relation
 * @method     ChildUsersQuery rightJoinWithUserPlaylists() Adds a RIGHT JOIN clause and with to the query using the UserPlaylists relation
 * @method     ChildUsersQuery innerJoinWithUserPlaylists() Adds a INNER JOIN clause and with to the query using the UserPlaylists relation
 *
 * @method     ChildUsersQuery leftJoinPlaylistComments($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistComments relation
 * @method     ChildUsersQuery rightJoinPlaylistComments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistComments relation
 * @method     ChildUsersQuery innerJoinPlaylistComments($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistComments relation
 *
 * @method     ChildUsersQuery joinWithPlaylistComments($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistComments relation
 *
 * @method     ChildUsersQuery leftJoinWithPlaylistComments() Adds a LEFT JOIN clause and with to the query using the PlaylistComments relation
 * @method     ChildUsersQuery rightJoinWithPlaylistComments() Adds a RIGHT JOIN clause and with to the query using the PlaylistComments relation
 * @method     ChildUsersQuery innerJoinWithPlaylistComments() Adds a INNER JOIN clause and with to the query using the PlaylistComments relation
 *
 * @method     ChildUsersQuery leftJoinUserEpisodes($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEpisodes relation
 * @method     ChildUsersQuery rightJoinUserEpisodes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEpisodes relation
 * @method     ChildUsersQuery innerJoinUserEpisodes($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEpisodes relation
 *
 * @method     ChildUsersQuery joinWithUserEpisodes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserEpisodes relation
 *
 * @method     ChildUsersQuery leftJoinWithUserEpisodes() Adds a LEFT JOIN clause and with to the query using the UserEpisodes relation
 * @method     ChildUsersQuery rightJoinWithUserEpisodes() Adds a RIGHT JOIN clause and with to the query using the UserEpisodes relation
 * @method     ChildUsersQuery innerJoinWithUserEpisodes() Adds a INNER JOIN clause and with to the query using the UserEpisodes relation
 *
 * @method     ChildUsersQuery leftJoinUserPlaylistTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPlaylistTags relation
 * @method     ChildUsersQuery rightJoinUserPlaylistTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPlaylistTags relation
 * @method     ChildUsersQuery innerJoinUserPlaylistTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPlaylistTags relation
 *
 * @method     ChildUsersQuery joinWithUserPlaylistTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPlaylistTags relation
 *
 * @method     ChildUsersQuery leftJoinWithUserPlaylistTags() Adds a LEFT JOIN clause and with to the query using the UserPlaylistTags relation
 * @method     ChildUsersQuery rightJoinWithUserPlaylistTags() Adds a RIGHT JOIN clause and with to the query using the UserPlaylistTags relation
 * @method     ChildUsersQuery innerJoinWithUserPlaylistTags() Adds a INNER JOIN clause and with to the query using the UserPlaylistTags relation
 *
 * @method     ChildUsersQuery leftJoinUserEpisodeTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEpisodeTags relation
 * @method     ChildUsersQuery rightJoinUserEpisodeTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEpisodeTags relation
 * @method     ChildUsersQuery innerJoinUserEpisodeTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEpisodeTags relation
 *
 * @method     ChildUsersQuery joinWithUserEpisodeTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserEpisodeTags relation
 *
 * @method     ChildUsersQuery leftJoinWithUserEpisodeTags() Adds a LEFT JOIN clause and with to the query using the UserEpisodeTags relation
 * @method     ChildUsersQuery rightJoinWithUserEpisodeTags() Adds a RIGHT JOIN clause and with to the query using the UserEpisodeTags relation
 * @method     ChildUsersQuery innerJoinWithUserEpisodeTags() Adds a INNER JOIN clause and with to the query using the UserEpisodeTags relation
 *
 * @method     ChildUsersQuery leftJoinLogging($relationAlias = null) Adds a LEFT JOIN clause to the query using the Logging relation
 * @method     ChildUsersQuery rightJoinLogging($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Logging relation
 * @method     ChildUsersQuery innerJoinLogging($relationAlias = null) Adds a INNER JOIN clause to the query using the Logging relation
 *
 * @method     ChildUsersQuery joinWithLogging($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Logging relation
 *
 * @method     ChildUsersQuery leftJoinWithLogging() Adds a LEFT JOIN clause and with to the query using the Logging relation
 * @method     ChildUsersQuery rightJoinWithLogging() Adds a RIGHT JOIN clause and with to the query using the Logging relation
 * @method     ChildUsersQuery innerJoinWithLogging() Adds a INNER JOIN clause and with to the query using the Logging relation
 *
 * @method     \Models\Users\UserSessionsQuery|\Models\Users\UserRelationsQuery|\Models\AudioPlayerSettings\AudioPlayerSettingsQuery|\Models\UserSettings\UserSettingsQuery|\Models\UserPodcasts\UserPodcastsQuery|\Models\Playlists\UserPlaylistsQuery|\Models\Playlists\PlaylistCommentsQuery|\Models\Episodes\UserEpisodesQuery|\Models\UserTags\UserPlaylistTagsQuery|\Models\UserTags\UserEpisodeTagsQuery|\Models\Logging\LoggingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUsers findOne(ConnectionInterface $con = null) Return the first ChildUsers matching the query
 * @method     ChildUsers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUsers matching the query, or a new ChildUsers object populated from the query conditions when no match is found
 *
 * @method     ChildUsers findOneById(string $id) Return the first ChildUsers filtered by the id column
 * @method     ChildUsers findOneByEmail(string $email) Return the first ChildUsers filtered by the email column
 * @method     ChildUsers findOneByFirstName(string $first_name) Return the first ChildUsers filtered by the first_name column
 * @method     ChildUsers findOneByLastName(string $last_name) Return the first ChildUsers filtered by the last_name column
 * @method     ChildUsers findOneByActive(int $active) Return the first ChildUsers filtered by the active column
 * @method     ChildUsers findOneByGoogleId(string $google_id) Return the first ChildUsers filtered by the google_id column
 * @method     ChildUsers findOneByImageUrl(string $image_url) Return the first ChildUsers filtered by the image_url column *

 * @method     ChildUsers requirePk($key, ConnectionInterface $con = null) Return the ChildUsers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOne(ConnectionInterface $con = null) Return the first ChildUsers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsers requireOneById(string $id) Return the first ChildUsers filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByEmail(string $email) Return the first ChildUsers filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByFirstName(string $first_name) Return the first ChildUsers filtered by the first_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByLastName(string $last_name) Return the first ChildUsers filtered by the last_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByActive(int $active) Return the first ChildUsers filtered by the active column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByGoogleId(string $google_id) Return the first ChildUsers filtered by the google_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsers requireOneByImageUrl(string $image_url) Return the first ChildUsers filtered by the image_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUsers objects based on current ModelCriteria
 * @method     ChildUsers[]|ObjectCollection findById(string $id) Return ChildUsers objects filtered by the id column
 * @method     ChildUsers[]|ObjectCollection findByEmail(string $email) Return ChildUsers objects filtered by the email column
 * @method     ChildUsers[]|ObjectCollection findByFirstName(string $first_name) Return ChildUsers objects filtered by the first_name column
 * @method     ChildUsers[]|ObjectCollection findByLastName(string $last_name) Return ChildUsers objects filtered by the last_name column
 * @method     ChildUsers[]|ObjectCollection findByActive(int $active) Return ChildUsers objects filtered by the active column
 * @method     ChildUsers[]|ObjectCollection findByGoogleId(string $google_id) Return ChildUsers objects filtered by the google_id column
 * @method     ChildUsers[]|ObjectCollection findByImageUrl(string $image_url) Return ChildUsers objects filtered by the image_url column
 * @method     ChildUsers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UsersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Users\Base\UsersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Users\\Users', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUsersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUsersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUsersQuery) {
            return $criteria;
        }
        $query = new ChildUsersQuery();
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
     * @return ChildUsers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UsersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UsersTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUsers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, email, first_name, last_name, active, google_id, image_url FROM users WHERE id = :p0';
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
            /** @var ChildUsers $obj */
            $obj = new ChildUsers();
            $obj->hydrate($row);
            UsersTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUsers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UsersTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UsersTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%', Criteria::LIKE); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%', Criteria::LIKE); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(1234); // WHERE active = 1234
     * $query->filterByActive(array(12, 34)); // WHERE active IN (12, 34)
     * $query->filterByActive(array('min' => 12)); // WHERE active > 12
     * </code>
     *
     * @param     mixed $active The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_array($active)) {
            $useMinMax = false;
            if (isset($active['min'])) {
                $this->addUsingAlias(UsersTableMap::COL_ACTIVE, $active['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($active['max'])) {
                $this->addUsingAlias(UsersTableMap::COL_ACTIVE, $active['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query on the google_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGoogleId('fooValue');   // WHERE google_id = 'fooValue'
     * $query->filterByGoogleId('%fooValue%', Criteria::LIKE); // WHERE google_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $googleId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByGoogleId($googleId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($googleId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_GOOGLE_ID, $googleId, $comparison);
    }

    /**
     * Filter the query on the image_url column
     *
     * Example usage:
     * <code>
     * $query->filterByImageUrl('fooValue');   // WHERE image_url = 'fooValue'
     * $query->filterByImageUrl('%fooValue%', Criteria::LIKE); // WHERE image_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageUrl The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function filterByImageUrl($imageUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersTableMap::COL_IMAGE_URL, $imageUrl, $comparison);
    }

    /**
     * Filter the query by a related \Models\Users\UserSessions object
     *
     * @param \Models\Users\UserSessions|ObjectCollection $userSessions the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserSessions($userSessions, $comparison = null)
    {
        if ($userSessions instanceof \Models\Users\UserSessions) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userSessions->getUserId(), $comparison);
        } elseif ($userSessions instanceof ObjectCollection) {
            return $this
                ->useUserSessionsQuery()
                ->filterByPrimaryKeys($userSessions->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserSessions() only accepts arguments of type \Models\Users\UserSessions or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserSessions relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserSessions($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserSessions');

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
            $this->addJoinObject($join, 'UserSessions');
        }

        return $this;
    }

    /**
     * Use the UserSessions relation UserSessions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UserSessionsQuery A secondary query class using the current class as primary query
     */
    public function useUserSessionsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserSessions($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserSessions', '\Models\Users\UserSessionsQuery');
    }

    /**
     * Filter the query by a related \Models\Users\UserRelations object
     *
     * @param \Models\Users\UserRelations|ObjectCollection $userRelations the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserRelationsRelatedByFollowerId($userRelations, $comparison = null)
    {
        if ($userRelations instanceof \Models\Users\UserRelations) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userRelations->getFollowerId(), $comparison);
        } elseif ($userRelations instanceof ObjectCollection) {
            return $this
                ->useUserRelationsRelatedByFollowerIdQuery()
                ->filterByPrimaryKeys($userRelations->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRelationsRelatedByFollowerId() only accepts arguments of type \Models\Users\UserRelations or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelationsRelatedByFollowerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserRelationsRelatedByFollowerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelationsRelatedByFollowerId');

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
            $this->addJoinObject($join, 'UserRelationsRelatedByFollowerId');
        }

        return $this;
    }

    /**
     * Use the UserRelationsRelatedByFollowerId relation UserRelations object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UserRelationsQuery A secondary query class using the current class as primary query
     */
    public function useUserRelationsRelatedByFollowerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelationsRelatedByFollowerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelationsRelatedByFollowerId', '\Models\Users\UserRelationsQuery');
    }

    /**
     * Filter the query by a related \Models\Users\UserRelations object
     *
     * @param \Models\Users\UserRelations|ObjectCollection $userRelations the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserRelationsRelatedByFollowingId($userRelations, $comparison = null)
    {
        if ($userRelations instanceof \Models\Users\UserRelations) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userRelations->getFollowingId(), $comparison);
        } elseif ($userRelations instanceof ObjectCollection) {
            return $this
                ->useUserRelationsRelatedByFollowingIdQuery()
                ->filterByPrimaryKeys($userRelations->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRelationsRelatedByFollowingId() only accepts arguments of type \Models\Users\UserRelations or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelationsRelatedByFollowingId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserRelationsRelatedByFollowingId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelationsRelatedByFollowingId');

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
            $this->addJoinObject($join, 'UserRelationsRelatedByFollowingId');
        }

        return $this;
    }

    /**
     * Use the UserRelationsRelatedByFollowingId relation UserRelations object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Users\UserRelationsQuery A secondary query class using the current class as primary query
     */
    public function useUserRelationsRelatedByFollowingIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelationsRelatedByFollowingId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelationsRelatedByFollowingId', '\Models\Users\UserRelationsQuery');
    }

    /**
     * Filter the query by a related \Models\AudioPlayerSettings\AudioPlayerSettings object
     *
     * @param \Models\AudioPlayerSettings\AudioPlayerSettings|ObjectCollection $audioPlayerSettings the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByAudioPlayerSettings($audioPlayerSettings, $comparison = null)
    {
        if ($audioPlayerSettings instanceof \Models\AudioPlayerSettings\AudioPlayerSettings) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $audioPlayerSettings->getUserId(), $comparison);
        } elseif ($audioPlayerSettings instanceof ObjectCollection) {
            return $this
                ->useAudioPlayerSettingsQuery()
                ->filterByPrimaryKeys($audioPlayerSettings->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAudioPlayerSettings() only accepts arguments of type \Models\AudioPlayerSettings\AudioPlayerSettings or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AudioPlayerSettings relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinAudioPlayerSettings($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AudioPlayerSettings');

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
            $this->addJoinObject($join, 'AudioPlayerSettings');
        }

        return $this;
    }

    /**
     * Use the AudioPlayerSettings relation AudioPlayerSettings object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\AudioPlayerSettings\AudioPlayerSettingsQuery A secondary query class using the current class as primary query
     */
    public function useAudioPlayerSettingsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAudioPlayerSettings($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AudioPlayerSettings', '\Models\AudioPlayerSettings\AudioPlayerSettingsQuery');
    }

    /**
     * Filter the query by a related \Models\UserSettings\UserSettings object
     *
     * @param \Models\UserSettings\UserSettings|ObjectCollection $userSettings the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserSettings($userSettings, $comparison = null)
    {
        if ($userSettings instanceof \Models\UserSettings\UserSettings) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userSettings->getUserId(), $comparison);
        } elseif ($userSettings instanceof ObjectCollection) {
            return $this
                ->useUserSettingsQuery()
                ->filterByPrimaryKeys($userSettings->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserSettings() only accepts arguments of type \Models\UserSettings\UserSettings or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserSettings relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserSettings($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserSettings');

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
            $this->addJoinObject($join, 'UserSettings');
        }

        return $this;
    }

    /**
     * Use the UserSettings relation UserSettings object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserSettings\UserSettingsQuery A secondary query class using the current class as primary query
     */
    public function useUserSettingsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserSettings($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserSettings', '\Models\UserSettings\UserSettingsQuery');
    }

    /**
     * Filter the query by a related \Models\UserPodcasts\UserPodcasts object
     *
     * @param \Models\UserPodcasts\UserPodcasts|ObjectCollection $userPodcasts the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserPodcasts($userPodcasts, $comparison = null)
    {
        if ($userPodcasts instanceof \Models\UserPodcasts\UserPodcasts) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userPodcasts->getUserId(), $comparison);
        } elseif ($userPodcasts instanceof ObjectCollection) {
            return $this
                ->useUserPodcastsQuery()
                ->filterByPrimaryKeys($userPodcasts->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPodcasts() only accepts arguments of type \Models\UserPodcasts\UserPodcasts or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPodcasts relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserPodcasts($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPodcasts');

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
            $this->addJoinObject($join, 'UserPodcasts');
        }

        return $this;
    }

    /**
     * Use the UserPodcasts relation UserPodcasts object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserPodcasts\UserPodcastsQuery A secondary query class using the current class as primary query
     */
    public function useUserPodcastsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPodcasts($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPodcasts', '\Models\UserPodcasts\UserPodcastsQuery');
    }

    /**
     * Filter the query by a related \Models\Playlists\UserPlaylists object
     *
     * @param \Models\Playlists\UserPlaylists|ObjectCollection $userPlaylists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserPlaylists($userPlaylists, $comparison = null)
    {
        if ($userPlaylists instanceof \Models\Playlists\UserPlaylists) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userPlaylists->getUserId(), $comparison);
        } elseif ($userPlaylists instanceof ObjectCollection) {
            return $this
                ->useUserPlaylistsQuery()
                ->filterByPrimaryKeys($userPlaylists->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPlaylists() only accepts arguments of type \Models\Playlists\UserPlaylists or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPlaylists relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserPlaylists($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPlaylists');

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
            $this->addJoinObject($join, 'UserPlaylists');
        }

        return $this;
    }

    /**
     * Use the UserPlaylists relation UserPlaylists object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\UserPlaylistsQuery A secondary query class using the current class as primary query
     */
    public function useUserPlaylistsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPlaylists($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPlaylists', '\Models\Playlists\UserPlaylistsQuery');
    }

    /**
     * Filter the query by a related \Models\Playlists\PlaylistComments object
     *
     * @param \Models\Playlists\PlaylistComments|ObjectCollection $playlistComments the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPlaylistComments($playlistComments, $comparison = null)
    {
        if ($playlistComments instanceof \Models\Playlists\PlaylistComments) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $playlistComments->getUserId(), $comparison);
        } elseif ($playlistComments instanceof ObjectCollection) {
            return $this
                ->usePlaylistCommentsQuery()
                ->filterByPrimaryKeys($playlistComments->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlaylistComments() only accepts arguments of type \Models\Playlists\PlaylistComments or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlaylistComments relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinPlaylistComments($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlaylistComments');

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
            $this->addJoinObject($join, 'PlaylistComments');
        }

        return $this;
    }

    /**
     * Use the PlaylistComments relation PlaylistComments object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistCommentsQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistCommentsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylistComments($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlaylistComments', '\Models\Playlists\PlaylistCommentsQuery');
    }

    /**
     * Filter the query by a related \Models\Episodes\UserEpisodes object
     *
     * @param \Models\Episodes\UserEpisodes|ObjectCollection $userEpisodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserEpisodes($userEpisodes, $comparison = null)
    {
        if ($userEpisodes instanceof \Models\Episodes\UserEpisodes) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userEpisodes->getUserId(), $comparison);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\UserTags\UserPlaylistTags object
     *
     * @param \Models\UserTags\UserPlaylistTags|ObjectCollection $userPlaylistTags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserPlaylistTags($userPlaylistTags, $comparison = null)
    {
        if ($userPlaylistTags instanceof \Models\UserTags\UserPlaylistTags) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userPlaylistTags->getUserId(), $comparison);
        } elseif ($userPlaylistTags instanceof ObjectCollection) {
            return $this
                ->useUserPlaylistTagsQuery()
                ->filterByPrimaryKeys($userPlaylistTags->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserPlaylistTags() only accepts arguments of type \Models\UserTags\UserPlaylistTags or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserPlaylistTags relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function joinUserPlaylistTags($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserPlaylistTags');

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
            $this->addJoinObject($join, 'UserPlaylistTags');
        }

        return $this;
    }

    /**
     * Use the UserPlaylistTags relation UserPlaylistTags object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserTags\UserPlaylistTagsQuery A secondary query class using the current class as primary query
     */
    public function useUserPlaylistTagsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserPlaylistTags($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserPlaylistTags', '\Models\UserTags\UserPlaylistTagsQuery');
    }

    /**
     * Filter the query by a related \Models\UserTags\UserEpisodeTags object
     *
     * @param \Models\UserTags\UserEpisodeTags|ObjectCollection $userEpisodeTags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByUserEpisodeTags($userEpisodeTags, $comparison = null)
    {
        if ($userEpisodeTags instanceof \Models\UserTags\UserEpisodeTags) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $userEpisodeTags->getUserId(), $comparison);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Logging\Logging object
     *
     * @param \Models\Logging\Logging|ObjectCollection $logging the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByLogging($logging, $comparison = null)
    {
        if ($logging instanceof \Models\Logging\Logging) {
            return $this
                ->addUsingAlias(UsersTableMap::COL_ID, $logging->getUserId(), $comparison);
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
     * @return $this|ChildUsersQuery The current query, for fluid interface
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
     * Filter the query by a related Users object
     * using the user_relations table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByFollowing($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserRelationsRelatedByFollowerIdQuery()
            ->filterByFollowing($users, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Users object
     * using the user_relations table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByFollower($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserRelationsRelatedByFollowingIdQuery()
            ->filterByFollower($users, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Podcasts object
     * using the user_podcasts table as cross reference
     *
     * @param Podcasts $podcasts the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPodcasts($podcasts, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPodcastsQuery()
            ->filterByPodcasts($podcasts, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Playlists object
     * using the user_playlists table as cross reference
     *
     * @param Playlists $playlists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPlaylists($playlists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistsQuery()
            ->filterByPlaylists($playlists, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Playlists object
     * using the user_playlist_tags table as cross reference
     *
     * @param Playlists $playlists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPlaylistsTags($playlists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistTagsQuery()
            ->filterByPlaylistsTags($playlists, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Tags object
     * using the user_playlist_tags table as cross reference
     *
     * @param Tags $tags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByPlaylistTag($tags, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistTagsQuery()
            ->filterByPlaylistTag($tags, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Episodes object
     * using the user_episode_tags table as cross reference
     *
     * @param Episodes $episodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByEpisodesTags($episodes, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEpisodeTagsQuery()
            ->filterByEpisodesTags($episodes, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Tags object
     * using the user_episode_tags table as cross reference
     *
     * @param Tags $tags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUsersQuery The current query, for fluid interface
     */
    public function filterByEpisodeTag($tags, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEpisodeTagsQuery()
            ->filterByEpisodeTag($tags, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUsers $users Object to remove from the list of results
     *
     * @return $this|ChildUsersQuery The current query, for fluid interface
     */
    public function prune($users = null)
    {
        if ($users) {
            $this->addUsingAlias(UsersTableMap::COL_ID, $users->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UsersTableMap::clearInstancePool();
            UsersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UsersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UsersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UsersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UsersQuery
