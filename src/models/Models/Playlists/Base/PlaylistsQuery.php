<?php

namespace Models\Playlists\Base;

use \Exception;
use \PDO;
use Models\Episodes\PlaylistEpisodes;
use Models\Playlists\Playlists as ChildPlaylists;
use Models\Playlists\PlaylistsQuery as ChildPlaylistsQuery;
use Models\Playlists\Map\PlaylistsTableMap;
use Models\UserTags\UserPlaylistTags;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'playlists' table.
 *
 *
 *
 * @method     ChildPlaylistsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlaylistsQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPlaylistsQuery orderByisParent($order = Criteria::ASC) Order by the is_parent column
 * @method     ChildPlaylistsQuery orderByTagGenerated($order = Criteria::ASC) Order by the tag_generated column
 * @method     ChildPlaylistsQuery orderByFavorites($order = Criteria::ASC) Order by the favorites column
 * @method     ChildPlaylistsQuery orderByShareableStatus($order = Criteria::ASC) Order by the shareable_status column
 * @method     ChildPlaylistsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlaylistsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlaylistsQuery groupById() Group by the id column
 * @method     ChildPlaylistsQuery groupByName() Group by the name column
 * @method     ChildPlaylistsQuery groupByisParent() Group by the is_parent column
 * @method     ChildPlaylistsQuery groupByTagGenerated() Group by the tag_generated column
 * @method     ChildPlaylistsQuery groupByFavorites() Group by the favorites column
 * @method     ChildPlaylistsQuery groupByShareableStatus() Group by the shareable_status column
 * @method     ChildPlaylistsQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlaylistsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlaylistsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlaylistsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlaylistsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlaylistsQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlaylistsQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlaylistsQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlaylistsQuery leftJoinUserPlaylists($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPlaylists relation
 * @method     ChildPlaylistsQuery rightJoinUserPlaylists($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPlaylists relation
 * @method     ChildPlaylistsQuery innerJoinUserPlaylists($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPlaylists relation
 *
 * @method     ChildPlaylistsQuery joinWithUserPlaylists($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPlaylists relation
 *
 * @method     ChildPlaylistsQuery leftJoinWithUserPlaylists() Adds a LEFT JOIN clause and with to the query using the UserPlaylists relation
 * @method     ChildPlaylistsQuery rightJoinWithUserPlaylists() Adds a RIGHT JOIN clause and with to the query using the UserPlaylists relation
 * @method     ChildPlaylistsQuery innerJoinWithUserPlaylists() Adds a INNER JOIN clause and with to the query using the UserPlaylists relation
 *
 * @method     ChildPlaylistsQuery leftJoinPlaylistChildrenRelatedByParentId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistChildrenRelatedByParentId relation
 * @method     ChildPlaylistsQuery rightJoinPlaylistChildrenRelatedByParentId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistChildrenRelatedByParentId relation
 * @method     ChildPlaylistsQuery innerJoinPlaylistChildrenRelatedByParentId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistChildrenRelatedByParentId relation
 *
 * @method     ChildPlaylistsQuery joinWithPlaylistChildrenRelatedByParentId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistChildrenRelatedByParentId relation
 *
 * @method     ChildPlaylistsQuery leftJoinWithPlaylistChildrenRelatedByParentId() Adds a LEFT JOIN clause and with to the query using the PlaylistChildrenRelatedByParentId relation
 * @method     ChildPlaylistsQuery rightJoinWithPlaylistChildrenRelatedByParentId() Adds a RIGHT JOIN clause and with to the query using the PlaylistChildrenRelatedByParentId relation
 * @method     ChildPlaylistsQuery innerJoinWithPlaylistChildrenRelatedByParentId() Adds a INNER JOIN clause and with to the query using the PlaylistChildrenRelatedByParentId relation
 *
 * @method     ChildPlaylistsQuery leftJoinPlaylistChildrenRelatedByChildId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistChildrenRelatedByChildId relation
 * @method     ChildPlaylistsQuery rightJoinPlaylistChildrenRelatedByChildId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistChildrenRelatedByChildId relation
 * @method     ChildPlaylistsQuery innerJoinPlaylistChildrenRelatedByChildId($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistChildrenRelatedByChildId relation
 *
 * @method     ChildPlaylistsQuery joinWithPlaylistChildrenRelatedByChildId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistChildrenRelatedByChildId relation
 *
 * @method     ChildPlaylistsQuery leftJoinWithPlaylistChildrenRelatedByChildId() Adds a LEFT JOIN clause and with to the query using the PlaylistChildrenRelatedByChildId relation
 * @method     ChildPlaylistsQuery rightJoinWithPlaylistChildrenRelatedByChildId() Adds a RIGHT JOIN clause and with to the query using the PlaylistChildrenRelatedByChildId relation
 * @method     ChildPlaylistsQuery innerJoinWithPlaylistChildrenRelatedByChildId() Adds a INNER JOIN clause and with to the query using the PlaylistChildrenRelatedByChildId relation
 *
 * @method     ChildPlaylistsQuery leftJoinPlaylistComments($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistComments relation
 * @method     ChildPlaylistsQuery rightJoinPlaylistComments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistComments relation
 * @method     ChildPlaylistsQuery innerJoinPlaylistComments($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistComments relation
 *
 * @method     ChildPlaylistsQuery joinWithPlaylistComments($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistComments relation
 *
 * @method     ChildPlaylistsQuery leftJoinWithPlaylistComments() Adds a LEFT JOIN clause and with to the query using the PlaylistComments relation
 * @method     ChildPlaylistsQuery rightJoinWithPlaylistComments() Adds a RIGHT JOIN clause and with to the query using the PlaylistComments relation
 * @method     ChildPlaylistsQuery innerJoinWithPlaylistComments() Adds a INNER JOIN clause and with to the query using the PlaylistComments relation
 *
 * @method     ChildPlaylistsQuery leftJoinPlaylistEpisodes($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlaylistEpisodes relation
 * @method     ChildPlaylistsQuery rightJoinPlaylistEpisodes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlaylistEpisodes relation
 * @method     ChildPlaylistsQuery innerJoinPlaylistEpisodes($relationAlias = null) Adds a INNER JOIN clause to the query using the PlaylistEpisodes relation
 *
 * @method     ChildPlaylistsQuery joinWithPlaylistEpisodes($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PlaylistEpisodes relation
 *
 * @method     ChildPlaylistsQuery leftJoinWithPlaylistEpisodes() Adds a LEFT JOIN clause and with to the query using the PlaylistEpisodes relation
 * @method     ChildPlaylistsQuery rightJoinWithPlaylistEpisodes() Adds a RIGHT JOIN clause and with to the query using the PlaylistEpisodes relation
 * @method     ChildPlaylistsQuery innerJoinWithPlaylistEpisodes() Adds a INNER JOIN clause and with to the query using the PlaylistEpisodes relation
 *
 * @method     ChildPlaylistsQuery leftJoinUserPlaylistTags($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserPlaylistTags relation
 * @method     ChildPlaylistsQuery rightJoinUserPlaylistTags($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserPlaylistTags relation
 * @method     ChildPlaylistsQuery innerJoinUserPlaylistTags($relationAlias = null) Adds a INNER JOIN clause to the query using the UserPlaylistTags relation
 *
 * @method     ChildPlaylistsQuery joinWithUserPlaylistTags($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserPlaylistTags relation
 *
 * @method     ChildPlaylistsQuery leftJoinWithUserPlaylistTags() Adds a LEFT JOIN clause and with to the query using the UserPlaylistTags relation
 * @method     ChildPlaylistsQuery rightJoinWithUserPlaylistTags() Adds a RIGHT JOIN clause and with to the query using the UserPlaylistTags relation
 * @method     ChildPlaylistsQuery innerJoinWithUserPlaylistTags() Adds a INNER JOIN clause and with to the query using the UserPlaylistTags relation
 *
 * @method     \Models\Playlists\UserPlaylistsQuery|\Models\Playlists\PlaylistChildrenQuery|\Models\Playlists\PlaylistCommentsQuery|\Models\Episodes\PlaylistEpisodesQuery|\Models\UserTags\UserPlaylistTagsQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlaylists findOne(ConnectionInterface $con = null) Return the first ChildPlaylists matching the query
 * @method     ChildPlaylists findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlaylists matching the query, or a new ChildPlaylists object populated from the query conditions when no match is found
 *
 * @method     ChildPlaylists findOneById(string $id) Return the first ChildPlaylists filtered by the id column
 * @method     ChildPlaylists findOneByName(string $name) Return the first ChildPlaylists filtered by the name column
 * @method     ChildPlaylists findOneByisParent(int $is_parent) Return the first ChildPlaylists filtered by the is_parent column
 * @method     ChildPlaylists findOneByTagGenerated(int $tag_generated) Return the first ChildPlaylists filtered by the tag_generated column
 * @method     ChildPlaylists findOneByFavorites(int $favorites) Return the first ChildPlaylists filtered by the favorites column
 * @method     ChildPlaylists findOneByShareableStatus(int $shareable_status) Return the first ChildPlaylists filtered by the shareable_status column
 * @method     ChildPlaylists findOneByCreatedAt(string $created_at) Return the first ChildPlaylists filtered by the created_at column
 * @method     ChildPlaylists findOneByUpdatedAt(string $updated_at) Return the first ChildPlaylists filtered by the updated_at column *

 * @method     ChildPlaylists requirePk($key, ConnectionInterface $con = null) Return the ChildPlaylists by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOne(ConnectionInterface $con = null) Return the first ChildPlaylists matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylists requireOneById(string $id) Return the first ChildPlaylists filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByName(string $name) Return the first ChildPlaylists filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByisParent(int $is_parent) Return the first ChildPlaylists filtered by the is_parent column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByTagGenerated(int $tag_generated) Return the first ChildPlaylists filtered by the tag_generated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByFavorites(int $favorites) Return the first ChildPlaylists filtered by the favorites column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByShareableStatus(int $shareable_status) Return the first ChildPlaylists filtered by the shareable_status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByCreatedAt(string $created_at) Return the first ChildPlaylists filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlaylists requireOneByUpdatedAt(string $updated_at) Return the first ChildPlaylists filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlaylists[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlaylists objects based on current ModelCriteria
 * @method     ChildPlaylists[]|ObjectCollection findById(string $id) Return ChildPlaylists objects filtered by the id column
 * @method     ChildPlaylists[]|ObjectCollection findByName(string $name) Return ChildPlaylists objects filtered by the name column
 * @method     ChildPlaylists[]|ObjectCollection findByisParent(int $is_parent) Return ChildPlaylists objects filtered by the is_parent column
 * @method     ChildPlaylists[]|ObjectCollection findByTagGenerated(int $tag_generated) Return ChildPlaylists objects filtered by the tag_generated column
 * @method     ChildPlaylists[]|ObjectCollection findByFavorites(int $favorites) Return ChildPlaylists objects filtered by the favorites column
 * @method     ChildPlaylists[]|ObjectCollection findByShareableStatus(int $shareable_status) Return ChildPlaylists objects filtered by the shareable_status column
 * @method     ChildPlaylists[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlaylists objects filtered by the created_at column
 * @method     ChildPlaylists[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlaylists objects filtered by the updated_at column
 * @method     ChildPlaylists[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlaylistsQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Playlists\Base\PlaylistsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Playlists\\Playlists', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlaylistsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlaylistsQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlaylistsQuery) {
            return $criteria;
        }
        $query = new ChildPlaylistsQuery();
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
     * @return ChildPlaylists|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlaylistsTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlaylists A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, is_parent, tag_generated, favorites, shareable_status, created_at, updated_at FROM playlists WHERE id = :p0';
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
            /** @var ChildPlaylists $obj */
            $obj = new ChildPlaylists();
            $obj->hydrate($row);
            PlaylistsTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlaylists|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlaylistsTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlaylistsTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the is_parent column
     *
     * Example usage:
     * <code>
     * $query->filterByisParent(1234); // WHERE is_parent = 1234
     * $query->filterByisParent(array(12, 34)); // WHERE is_parent IN (12, 34)
     * $query->filterByisParent(array('min' => 12)); // WHERE is_parent > 12
     * </code>
     *
     * @param     mixed $isParent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByisParent($isParent = null, $comparison = null)
    {
        if (is_array($isParent)) {
            $useMinMax = false;
            if (isset($isParent['min'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_IS_PARENT, $isParent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($isParent['max'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_IS_PARENT, $isParent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_IS_PARENT, $isParent, $comparison);
    }

    /**
     * Filter the query on the tag_generated column
     *
     * Example usage:
     * <code>
     * $query->filterByTagGenerated(1234); // WHERE tag_generated = 1234
     * $query->filterByTagGenerated(array(12, 34)); // WHERE tag_generated IN (12, 34)
     * $query->filterByTagGenerated(array('min' => 12)); // WHERE tag_generated > 12
     * </code>
     *
     * @param     mixed $tagGenerated The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByTagGenerated($tagGenerated = null, $comparison = null)
    {
        if (is_array($tagGenerated)) {
            $useMinMax = false;
            if (isset($tagGenerated['min'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_TAG_GENERATED, $tagGenerated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tagGenerated['max'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_TAG_GENERATED, $tagGenerated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_TAG_GENERATED, $tagGenerated, $comparison);
    }

    /**
     * Filter the query on the favorites column
     *
     * Example usage:
     * <code>
     * $query->filterByFavorites(1234); // WHERE favorites = 1234
     * $query->filterByFavorites(array(12, 34)); // WHERE favorites IN (12, 34)
     * $query->filterByFavorites(array('min' => 12)); // WHERE favorites > 12
     * </code>
     *
     * @param     mixed $favorites The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByFavorites($favorites = null, $comparison = null)
    {
        if (is_array($favorites)) {
            $useMinMax = false;
            if (isset($favorites['min'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_FAVORITES, $favorites['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($favorites['max'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_FAVORITES, $favorites['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_FAVORITES, $favorites, $comparison);
    }

    /**
     * Filter the query on the shareable_status column
     *
     * Example usage:
     * <code>
     * $query->filterByShareableStatus(1234); // WHERE shareable_status = 1234
     * $query->filterByShareableStatus(array(12, 34)); // WHERE shareable_status IN (12, 34)
     * $query->filterByShareableStatus(array('min' => 12)); // WHERE shareable_status > 12
     * </code>
     *
     * @param     mixed $shareableStatus The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByShareableStatus($shareableStatus = null, $comparison = null)
    {
        if (is_array($shareableStatus)) {
            $useMinMax = false;
            if (isset($shareableStatus['min'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_SHAREABLE_STATUS, $shareableStatus['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shareableStatus['max'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_SHAREABLE_STATUS, $shareableStatus['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_SHAREABLE_STATUS, $shareableStatus, $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlaylistsTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlaylistsTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Playlists\UserPlaylists object
     *
     * @param \Models\Playlists\UserPlaylists|ObjectCollection $userPlaylists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUserPlaylists($userPlaylists, $comparison = null)
    {
        if ($userPlaylists instanceof \Models\Playlists\UserPlaylists) {
            return $this
                ->addUsingAlias(PlaylistsTableMap::COL_ID, $userPlaylists->getPlaylistId(), $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Playlists\PlaylistChildren object
     *
     * @param \Models\Playlists\PlaylistChildren|ObjectCollection $playlistChildren the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistChildrenRelatedByParentId($playlistChildren, $comparison = null)
    {
        if ($playlistChildren instanceof \Models\Playlists\PlaylistChildren) {
            return $this
                ->addUsingAlias(PlaylistsTableMap::COL_ID, $playlistChildren->getParentId(), $comparison);
        } elseif ($playlistChildren instanceof ObjectCollection) {
            return $this
                ->usePlaylistChildrenRelatedByParentIdQuery()
                ->filterByPrimaryKeys($playlistChildren->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlaylistChildrenRelatedByParentId() only accepts arguments of type \Models\Playlists\PlaylistChildren or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlaylistChildrenRelatedByParentId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function joinPlaylistChildrenRelatedByParentId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlaylistChildrenRelatedByParentId');

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
            $this->addJoinObject($join, 'PlaylistChildrenRelatedByParentId');
        }

        return $this;
    }

    /**
     * Use the PlaylistChildrenRelatedByParentId relation PlaylistChildren object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistChildrenQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistChildrenRelatedByParentIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylistChildrenRelatedByParentId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlaylistChildrenRelatedByParentId', '\Models\Playlists\PlaylistChildrenQuery');
    }

    /**
     * Filter the query by a related \Models\Playlists\PlaylistChildren object
     *
     * @param \Models\Playlists\PlaylistChildren|ObjectCollection $playlistChildren the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistChildrenRelatedByChildId($playlistChildren, $comparison = null)
    {
        if ($playlistChildren instanceof \Models\Playlists\PlaylistChildren) {
            return $this
                ->addUsingAlias(PlaylistsTableMap::COL_ID, $playlistChildren->getChildId(), $comparison);
        } elseif ($playlistChildren instanceof ObjectCollection) {
            return $this
                ->usePlaylistChildrenRelatedByChildIdQuery()
                ->filterByPrimaryKeys($playlistChildren->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlaylistChildrenRelatedByChildId() only accepts arguments of type \Models\Playlists\PlaylistChildren or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlaylistChildrenRelatedByChildId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function joinPlaylistChildrenRelatedByChildId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlaylistChildrenRelatedByChildId');

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
            $this->addJoinObject($join, 'PlaylistChildrenRelatedByChildId');
        }

        return $this;
    }

    /**
     * Use the PlaylistChildrenRelatedByChildId relation PlaylistChildren object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\Playlists\PlaylistChildrenQuery A secondary query class using the current class as primary query
     */
    public function usePlaylistChildrenRelatedByChildIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlaylistChildrenRelatedByChildId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlaylistChildrenRelatedByChildId', '\Models\Playlists\PlaylistChildrenQuery');
    }

    /**
     * Filter the query by a related \Models\Playlists\PlaylistComments object
     *
     * @param \Models\Playlists\PlaylistComments|ObjectCollection $playlistComments the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistComments($playlistComments, $comparison = null)
    {
        if ($playlistComments instanceof \Models\Playlists\PlaylistComments) {
            return $this
                ->addUsingAlias(PlaylistsTableMap::COL_ID, $playlistComments->getPlaylistId(), $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Episodes\PlaylistEpisodes object
     *
     * @param \Models\Episodes\PlaylistEpisodes|ObjectCollection $playlistEpisodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistEpisodes($playlistEpisodes, $comparison = null)
    {
        if ($playlistEpisodes instanceof \Models\Episodes\PlaylistEpisodes) {
            return $this
                ->addUsingAlias(PlaylistsTableMap::COL_ID, $playlistEpisodes->getPlaylistId(), $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\UserTags\UserPlaylistTags object
     *
     * @param \Models\UserTags\UserPlaylistTags|ObjectCollection $userPlaylistTags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUserPlaylistTags($userPlaylistTags, $comparison = null)
    {
        if ($userPlaylistTags instanceof \Models\UserTags\UserPlaylistTags) {
            return $this
                ->addUsingAlias(PlaylistsTableMap::COL_ID, $userPlaylistTags->getPlaylistId(), $comparison);
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
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
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
     * Filter the query by a related Users object
     * using the user_playlists table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUsers($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistsQuery()
            ->filterByUsers($users, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Playlists object
     * using the playlists_children table as cross reference
     *
     * @param Playlists $playlists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistsChild($playlists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePlaylistChildrenRelatedByParentIdQuery()
            ->filterByPlaylistsChild($playlists, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Playlists object
     * using the playlists_children table as cross reference
     *
     * @param Playlists $playlists the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistsParent($playlists, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePlaylistChildrenRelatedByChildIdQuery()
            ->filterByPlaylistsParent($playlists, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Episodes object
     * using the playlist_episodes table as cross reference
     *
     * @param Episodes $episodes the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByEpisode($episodes, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePlaylistEpisodesQuery()
            ->filterByEpisode($episodes, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Tags object
     * using the user_playlist_tags table as cross reference
     *
     * @param Tags $tags the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByPlaylistTag($tags, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistTagsQuery()
            ->filterByPlaylistTag($tags, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Users object
     * using the user_playlist_tags table as cross reference
     *
     * @param Users $users the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlaylistsQuery The current query, for fluid interface
     */
    public function filterByUsersPlaylistTags($users, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserPlaylistTagsQuery()
            ->filterByUsersPlaylistTags($users, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlaylists $playlists Object to remove from the list of results
     *
     * @return $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function prune($playlists = null)
    {
        if ($playlists) {
            $this->addUsingAlias(PlaylistsTableMap::COL_ID, $playlists->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the playlists table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlaylistsTableMap::clearInstancePool();
            PlaylistsTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlaylistsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlaylistsTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlaylistsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlaylistsTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlaylistsTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlaylistsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlaylistsTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlaylistsTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlaylistsTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlaylistsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlaylistsTableMap::COL_CREATED_AT);
    }

} // PlaylistsQuery
